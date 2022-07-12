<?php

declare(strict_types=1);

namespace Cryptopay\PaymentGateway\Api;

interface UpdateStatusOrderInterface
{
    /**
     * Postback Cryptopay
     * @return string
     */
    public function doUpdate();
}
