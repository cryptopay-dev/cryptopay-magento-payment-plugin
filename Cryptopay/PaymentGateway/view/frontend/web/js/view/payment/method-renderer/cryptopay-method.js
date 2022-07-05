define(
    [
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'mage/url',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function ($, Component, url, customerData, errorProcessor, fullScreenLoader) {
        'use strict';
        return Component.extend({
            redirectAfterPlaceOrder: false,
            defaults: {
                template: 'Cryptopay_PaymentGateway/payment/cryptopay-form'
            },
            getCode: function() {
                return 'cryptopay_paymentgateway';
            },
            isActive: function() {
                return true;
            },
            afterPlaceOrder: function () {
                var custom_controller_url = url.build('cryptopay/payment/create');

                $.post(custom_controller_url, 'json')
                    .done(function (response) {
                        window.location.href = response.redirectUrl;
                    })
                    .fail(function (response) {
                        errorProcessor.process(response, this.messageContainer);
                    })
                    .always(function () {
                        fullScreenLoader.stopLoader();
                    });
            },
            getCryptopayMessage: function () {
                return window.checkoutConfig.payment.cryptopay_message[this.item.method];
            }
        });
    }
);
