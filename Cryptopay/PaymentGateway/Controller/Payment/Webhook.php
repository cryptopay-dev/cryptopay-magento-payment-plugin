<?php

namespace Cryptopay\PaymentGateway\Controller\Payment;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\ScopeInterface;

class Webhook extends Action
{
    private $file;
    private $jsonResultFactory;
    private $scopeConfig;
    private $orderRepository;

    public function __construct(
        Context $context,
        File $file,
        JsonFactory $jsonResultFactory,
        ScopeConfigInterface $scopeConfig,
        OrderRepositoryInterface $orderRepository,
    ) {
        parent::__construct($context);
        $this->file = $file;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->scopeConfig = $scopeConfig;
        $this->orderRepository = $orderRepository;

        $this->execute();
    }

    public function execute()
    {
        try {
            $request = file_get_contents('php://input');

            if (!$this->validateWebhook($request, $_SERVER['HTTP_X_CRYPTOPAY_SIGNATURE'])) {
                $result = $this->jsonResultFactory->create();
                $result->setHttpResponseCode(401);
                $result->setData(['success' => false, 'message' => __('Webhook validation failed.')]);
                return $result;
            }

            $body = json_decode($request, true);

            if ($body['type'] != 'Invoice') {
                $result = $this->jsonResultFactory->create();
                $result->setHttpResponseCode(400);
                $result->setData(['success' => false, 'message' => __('Something went wrong.')]);
                return $result;
            }

            $data = $body['data'];
            $order_id = str_replace('magento_order_', "", 'magento_order_' . $data['custom_id']);
            $order = $this->getOrder($order_id);

            if (!$order) {
                $result = $this->jsonResultFactory->create();
                $result->setHttpResponseCode(400);
                $result->setData(['success' => false, 'message' => __('Could not find matching order.')]);
                return $result;
            }

            $this->updateOrderState($order, $body);

            $result = $this->jsonResultFactory->create();
            $result->setHttpResponseCode(200);
            $result->setData(['success' => true]);
            return $result;
        } catch (\Exception $e) {
            $result = $this->jsonResultFactory->create();
            $result->setHttpResponseCode(400);
            $result->setData(['error_message' => __('Webhook receive error.')]);
            return $result;
        }
    }

    public function updateOrderState($order, $data) {
        if ($data['status'] == 'new') {
            $pendingStatus = $this->scopeConfig->getValue(
                'payment/cryptopay_paymentgateway/status_pending',
                ScopeInterface::SCOPE_STORE);
            $order->setStatus($pendingStatus)->save();
            return;
        }

        if ($data['status'] == 'completed' || $data['status'] == 'unresolved' && $data['status_context'] == 'overpaid') {
            $successStatus = $this->scopeConfig->getValue(
                'payment/cryptopay_paymentgateway/status_completed',
                ScopeInterface::SCOPE_STORE);
            $order->setStatus($successStatus)->save();
            return;
        }

        if ($data['status'] == 'cancelled' || $data['status'] == 'refunded' || $data['status'] == 'unresolved') {
            $failedStatus = $this->scopeConfig->getValue(
                'payment/cryptopay_paymentgateway/status_failed',
                ScopeInterface::SCOPE_STORE
            );
            $order->setStatus($failedStatus)->save();
            $order->save();
        }
    }

    /**
     * Validate the webhook request
     */
    private function validateWebhook($body, $signature)
    {
        $callbackSecret = $this->scopeConfig->getValue(
            'payment/cryptopay_paymentgateway/callback_secret',
            ScopeInterface::SCOPE_STORE
        );
        $expected = hash_hmac('sha256', $body, $callbackSecret);
        return $expected === $signature;
    }

    private function getOrder($id): \Magento\Sales\Api\Data\OrderInterface
    {
        return $this->orderRepository->get($id);
    }
}
