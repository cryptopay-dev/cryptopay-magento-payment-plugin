define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'cryptopay_paymentgateway',
                component: 'Cryptopay_PaymentGateway/js/view/payment/method-renderer/cryptopay-method'
            }
        );
        return Component.extend({});
    }
);
