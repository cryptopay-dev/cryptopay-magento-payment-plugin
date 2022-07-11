<?php

namespace Cryptopay\PaymentGateway\Model\Adminhtml\Source;

use Magento\Framework\UrlInterface;

class CallbackUrlComment implements \Magento\Config\Model\Config\CommentInterface
{
    protected $urlInterface;

    public function __construct(
        UrlInterface $urlInterface
    ) {
        $this->urlInterface = $urlInterface;
    }

    public function getCommentText($elementValue)
    {
        $webhook = $this->urlInterface->getBaseUrl() . 'rest/all/V1/order/status/update';
        $pointOne = '1. <a href="https://business.cryptopay.me" target="_blank">Log in</a> to your account on business.cryptopay.me';
        $pointTwo = __('2. Then go to <a href="https://business.cryptopay.me/app/settings/api" target="_blank"> the Settings -&gt; API page </a> and save %1 in the Callback URL field', $webhook);
        return "$pointOne <br/> $pointTwo";
    }
}
