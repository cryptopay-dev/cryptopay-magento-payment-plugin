<?php

namespace Cryptopay\PaymentGateway\Controller\Payment;

use Magento\Framework\App\ActionInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;

class Create implements ActionInterface
{
    private $checkoutSession;
    private $resultJsonFactory;
    private $scopeConfig;
    protected $urlBuilder;
    protected $widgetKey;

    public function __construct(
        Session $checkoutSession,
        JsonFactory $resultJsonFactory,
        UrlInterface $urlBuilder,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->urlBuilder = $urlBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->widgetKey = $this->scopeConfig->getValue('payment/cryptopay_paymentgateway/widget_key', ScopeInterface::SCOPE_STORE);
    }

    public function execute()
    {
        $order = $this->getOrder();
        $pendingStatus = $this->scopeConfig->getValue(
            'payment/cryptopay_paymentgateway/status_pending',
            ScopeInterface::SCOPE_STORE); // \Magento\Sales\Model\Order::STATE_PENDING_PAYMENT
        $order->setStatus($pendingStatus)->save();

        $urlParams = $this->getUrlParameters($order);

        $environment = $this->scopeConfig->getValue('payment/cryptopay_paymentgateway/environment', ScopeInterface::SCOPE_STORE);
        $redirectUrl = $environment == 'Sandbox'
            ? 'https://pay-business-sandbox.cryptopay.me'
            : 'https://business-pay.cryptopay.me';

        $url = $redirectUrl . '?' . http_build_query($urlParams);

        $result = $this->resultJsonFactory->create();
        return $result->setData(['redirectUrl' => $url]);
    }

    private function getUrlParameters($order): array {
        $customId = $order->getId();
        $orderAmount = $order->getGrandTotal();
        $orderCurrency = $order->getOrderCurrencyCode();
        $widgetKey = $this->widgetKey;
        $showQrCode = $this->scopeConfig->getValue('payment/cryptopay_paymentgateway/qr_code', ScopeInterface::SCOPE_STORE);
        $theme = $this->scopeConfig->getValue('payment/cryptopay_paymentgateway/theme', ScopeInterface::SCOPE_STORE);

        $data['customId'] = 'magento_order_' . $customId;
        $data['widgetKey'] = $widgetKey;
        $data['isShowQr'] = $showQrCode == 1 ? 'true' : 'false';
        $data['theme'] = $theme;
        $data['priceCurrency'] = $orderCurrency;
        $data['priceAmount'] = $orderAmount;
        $data['successRedirectUrl'] = $this->urlBuilder->getUrl(
            'cryptopay/payment/success',
            ['_query' => ['order_id' => $order->getId()]]
        );
        $data['unsuccessRedirectUrl'] = $this->urlBuilder->getUrl(
            'cryptopay/payment/cancel',
            ['_query' => ['order_id' => $order->getId()]]
        );

        return $data;
    }


    private function getOrder(): \Magento\Sales\Model\Order
    {
        return $this->checkoutSession->getLastRealOrder();
    }
}
