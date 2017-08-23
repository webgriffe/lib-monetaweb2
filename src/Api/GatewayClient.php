<?php

namespace Webgriffe\LibMonetaWebDue\Api;

use GuzzleHttp\Psr7\Request;
use Webgriffe\LibMonetaWebDue\PaymentInit\UrlGenerator;

class GatewayClient
{
    /** @var  \GuzzleHttp\ClientInterface $client */
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function getPaymentPageInfo(
        $baseUrl,
        $terminalId,
        $terminalPassword,
        $amount,
        $currencyCode,
        $language,
        $responseToMerchantUrl,
        $recoveryUrl,
        $orderId,
        $paymentDescription = null,
        $cardHolderName = null,
        $cardholderEmail = null,
        $customField = null
    )
    {
        $urlGenerator = new UrlGenerator();
        $paymentInitUrl = $urlGenerator->generate(
            $baseUrl,
            $terminalId,
            $terminalPassword,
            $amount,
            $currencyCode,
            $language,
            $responseToMerchantUrl,
            $recoveryUrl,
            $orderId,
            $paymentDescription,
            $cardHolderName,
            $cardholderEmail,
            $customField
        );

        $request = new Request('GET', $paymentInitUrl);
        $response = $this->client->send($request);

        $parsedResponseBody = simplexml_load_string($response->getBody());
        $hostedpageurl = (string)$parsedResponseBody->hostedpageurl;
        $paymentid = (string)$parsedResponseBody->paymentid;
        $securityToken = (string)$parsedResponseBody->securitytoken;
        $hostedpageurl .= (parse_url($hostedpageurl, PHP_URL_QUERY) ? '&' : '?') . 'paymentid=' .$paymentid;

        $gatewayPageInfo = new GatewayPageInfo($hostedpageurl, $securityToken);
        return $gatewayPageInfo;
    }
}
