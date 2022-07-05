# CRYPTOPAY Extension for Magento 2

This is the official Magento en for the CRYPTOPAY [cryptocurrency payment gateway](https://business.cryptopay.me/). Accept and settle payments in digital currencies in your Magento shop.

This extension for Magento 2 implements the REST API documented at https://developers.cryptopay.me/

## Requirements

* A CRYPTOPAY merchant account -> Sign up [here](https://business.cryptopay.me/).
* Settings API Key -> Find them [here](https://business.cryptopay.me/app/settings/api).
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

Your feedback is appreciated! If you have specific problems or bugs with this Magento module, please file an issue on Github. For general feedback and support requests, send an email to service@cryptopay.com
