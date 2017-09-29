
Webgriffe MonetaWeb 2.0 PHP library
===================================

[![Run Status](https://travis-ci.org/webgriffe/lib-monetaweb2.svg?branch=master)](https://travis-ci.org/webgriffe/lib-monetaweb2.svg?branch=master)

This library provides a complete integration for the XML Hosted 3DSecure protocol of Setefi MonetaWeb 2.0 payment gateway.
It was developed following the technical documentation provided by Setefi released in July 2017.

Installation
------------

In order to use this library, first of all import it using composer:

```
composer require webgriffe/lib-monetaweb2
```

Usage
-----

### Payment initialization

You can generate a payment initialization URL using the `GatewayClient`:

    $gatewayClient = new Webgriffe\LibMonetaWebDue\Api\GatewayClient();
	$getPaymentPageInfo = $gatewayClient->getPaymentPageInfo(
        'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet',
        '99999999',
        '99999999',
        1428.7,
        'EUR',
        'ITA',
        'http://www.merchant.it/notify/',
        'http://www.merchant.it/error/',
        'TRCK0001',
        'Descrizione',
        'Nome Cognome',
        'nome@dominio.com',
        'Campo Personalizzabile'
    );
    
The `getPaymentPageInfo` method returns a `GatewayPageInfo` value object that encapsulates 3 values: 
* Payment ID, unique ID of the payment session
* Security Token, an hash that should be compared to the one returned in the response of the notify request (server-to-server notification, of which we talk below)
* Hosted Page URL, the URL to which the user must be redirected in order to perform the payment

### Server to server notification

Once that you redirected the user to the `Hosted Page URL` a server-to-server notification will be perfomed by MonetaWebDue to one of the 2 urls that you specified when you called `getPaymentPageInfo`.
In the example of the payment initialization the user will be redirected to `http://www.merchant.it/notify/` in case of successful operation and to `http://www.merchant.it/error/` otherwise.

You can handle this request by using the `handleNotify` method of the `GatewayClient`:

    // $psrRequest must be an instance of an object that implements the \Psr\Http\Message\RequestInterface
	$paymentResult = $gatewayClient->handleNotify($psrRequest);
    
The result of the `handleNotify` method could be a `PaymentResultInfo` object or a `PaymentResultErrorInfo` in case of error during the notification.

TODO: explain the two objects

TODO: explain the verify security token

Contributing
------------

Fork this repository, make your changes and submit a pull request.
Please run the tests and the coding standards checks before submitting a pull request. You can do it with:

```
vendor/bin/phpspec run
vendor/bin/phpcs --standard=PSR2 src/
```

License
-------

This library is under the MIT license. See the complete license in the LICENSE file.

Credits
-------

Developed by [Webgriffe®](http://www.webgriffe.com/).
