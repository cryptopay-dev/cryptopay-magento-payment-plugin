<?php

namespace Cryptopay\PaymentGateway\Model\Payment;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CustomPaymentConfigProvider implements ConfigProviderInterface
{
    protected $methodCodes = [
        Cryptopaymethod::CODE
    ];

    protected $methods = [];
    protected $escaper;
    private $scopeConfig;

    public function __construct(
        PaymentHelper $paymentHelper,
        Escaper $escaper,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->escaper = $escaper;
        $this->scopeConfig = $scopeConfig;

        foreach ($this->methodCodes as $code) {
            $this->methods[$code] = $paymentHelper->getMethodInstance($code);
        }
    }

    public function getConfig()
    {
        $config = [];
        foreach ($this->methodCodes as $code) {
            if ($this->methods[$code]->isAvailable()) {
                $config['payment']['cryptopay_logo'][$code] = $this->getCryptopayLogo();
                $config['payment']['cryptopay_message'][$code] = $this->getCryptopayMessage();
            }
        }
        return $config;
    }

    protected function getCryptopayLogo()
    {
        return $this->scopeConfig->getValue('payment/cryptopay_paymentgateway/show_logo', ScopeInterface::SCOPE_STORE);
    }

    protected function getCryptopayMessage()
    {
        $message = $this->scopeConfig->getValue('payment/cryptopay_paymentgateway/checkout_page_message', ScopeInterface::SCOPE_STORE);
        $output = ($message == NULL) ? false : '<p class="payment-method-redirect-message">' . nl2br($this->escaper->escapeHtml($message)) . '</p>';
        return $output;
    }
}
