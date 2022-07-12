<?php

namespace Cryptopay\PaymentGateway\Model\Adminhtml\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class ThemeAction
 */
class ThemeAction implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'light',
                'label' => 'Light'
            ],
            [
                'value' => 'dark',
                'label' => 'Dark'
            ]
        ];
    }
}
