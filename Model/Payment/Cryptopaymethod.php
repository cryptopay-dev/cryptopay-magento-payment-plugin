<?php

namespace Cryptopay\PaymentGateway\Model\Payment;

class Cryptopaymethod extends \Magento\Payment\Model\Method\AbstractMethod
{
    const CODE = 'cryptopay_paymentgateway';

    protected $_code = 'cryptopay_paymentgateway';

    public function isAvailable(
        \Magento\Quote\Api\Data\CartInterface $quote = null
    ) {

        $widget_key = $this->_scopeConfig->getValue(
            'payment/cryptopay_paymentgateway/widget_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $callback_secret = $this->_scopeConfig->getValue(
            'payment/cryptopay_paymentgateway/callback_secret',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if (!$widget_key || !$callback_secret) {
            return false;
        }
        return parent::isAvailable($quote);
    }
}
