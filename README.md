# Cryptopay Extension for Magento 2

This is the official Magento en for the Cryptopay [cryptocurrency payment gateway](https://business.cryptopay.me/). Accept and settle payments in digital currencies in your Magento shop.

The extension allows you to integrate the Cryptopay payment gateway to Magento simply and quickly. Your clients will be able to pay for their purchases with cryptocurrency, and you will receive fiat to your bank account. We already support BTC, USDT, ETH, LTC, BTH, XLM, XRP, USDC and DAI and constantly add new coins.

## Requirements

* A Cryptopay merchant account -> Sign up [here](https://business.cryptopay.me/).
* Widget Key -> Find them [here](https://business.cryptopay.me/app/settings/widget).
* Download the extension from the Magento Marketplace [here](https://marketplace.magento.com/cryptopay-paymentgateway.html).


## Extension installation

* Create a folder structure in Magento root as app/code/Cryptopay/PaymentGateway.
* Download and extract the zip folder from the Magento Marketplace and upload the extension files to app/code/Cryptopay/PaymentGateway.
* Login to your SSH and run below commands:

    ```bash
    php bin/magento setup:upgrade
  
    // For Magento version 2.0.x to 2.1.x
    php bin/magento setup:static-content:deploy
  
    // For Magento version 2.2.x & above
    php bin/magento setup:static-content:deploy –f
   
    php bin/magento cache:flush
    
    rm -rf var/cache var/generation var/di var/page_cache generated/*
  
    ```

Support and Feedback
--------------------
Magento 2.4.4

Your feedback is appreciated! If you have specific problems or bugs with this Magento module, please file an issue on Github. For general feedback and support requests, send an email to support@cryptopay.me
