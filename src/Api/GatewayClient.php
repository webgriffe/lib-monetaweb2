<?php

namespace Webgriffe\LibMonetaWebDue\Api;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Webgriffe\LibMonetaWebDue\PaymentInit\UrlGenerator;

class GatewayClient
{
    /** @var ClientInterface $client */
    private $client;

    public function __construct(ClientInterface $client)
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
    ) {
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

        $request = new Request('POST', $paymentInitUrl);
        $response = $this->client->send($request);

        $parsedResponseBody = simplexml_load_string($response->getBody());
        $hostedPageUrl = (string)$parsedResponseBody->hostedpageurl;
        $paymentId = (string)$parsedResponseBody->paymentid;
        $securityToken = (string)$parsedResponseBody->securitytoken;
        $hostedPageUrl .= (parse_url($hostedPageUrl, PHP_URL_QUERY) ? '&' : '?') . 'paymentid=' .$paymentId;

        return new GatewayPageInfo($hostedPageUrl, $securityToken);
    }
}
