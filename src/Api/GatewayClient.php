<?php

namespace Webgriffe\LibMonetaWebDue\Api;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Webgriffe\LibMonetaWebDue\PaymentInit\UrlGenerator;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Mapper;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInfo;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInterface;

class GatewayClient
{
    /**
     * @var ClientInterface $client
     */
    private $client;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ClientInterface $client, LoggerInterface $logger = null)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */
    /**
     * @param string $gatewayBaseUrl
     * @param string $terminalId
     * @param string $terminalPassword
     * @param float $amount
     * @param string $currencyCode
     * @param string $language
     * @param string $responseToMerchantUrl
     * @param string|null $recoveryUrl
     * @param string $orderId
     * @param string|null $paymentDescription
     * @param string|null $cardHolderName
     * @param string|null $cardholderEmail
     * @param string|null $customField
     * @return GatewayPageInfo
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     */
    public function getPaymentPageInfo(
        $gatewayBaseUrl,
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
        $this->log('Get payment page info method called');
        $urlGenerator = new UrlGenerator();
        $paymentInitUrl = $urlGenerator->generate(
            $gatewayBaseUrl,
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
        $this->log(sprintf('Doing a request with the following data: %s', PHP_EOL . print_r($request, true)));
        $response = $this->client->send($request);
        $this->log(sprintf('The request returned the following response: %s', PHP_EOL . print_r($response, true)));

        // todo: handle xml parsing errors
        $parsedResponseBody = simplexml_load_string($response->getBody());
        // todo: it could throw a custom exception that encapsulate the error code and message
        if (isset($parsedResponseBody->errorcode) || isset($parsedResponseBody->errormessage)) {
            $message = sprintf(
                'The request sent to MonetaWebDue gateway generated an error with code "%s" and message: %s',
                $parsedResponseBody->errorCode,
                $parsedResponseBody->errorMessage
            );
            $this->log($message, LogLevel::ERROR);
            throw new \RuntimeException($message);
        }

        $hostedPageUrl = (string)$parsedResponseBody->hostedpageurl;
        $paymentId = (string)$parsedResponseBody->paymentid;
        $securityToken = (string)$parsedResponseBody->securitytoken;
        $hostedPageUrl .= (parse_url($hostedPageUrl, PHP_URL_QUERY) ? '&' : '?') . 'paymentid=' . $paymentId;

        $gatewayPageInfo = new GatewayPageInfo($hostedPageUrl, $securityToken, $paymentId);
        $this->log(
            sprintf(
                'Generated a GatewayPageInfo object with the following data: %s',
                PHP_EOL . print_r($gatewayPageInfo, true)
            )
        );
        return $gatewayPageInfo;
    }

    /**
     * @param ServerRequestInterface $request
     * @return PaymentResultInterface
     * @throws \InvalidArgumentException
     */
    public function handleNotify(ServerRequestInterface $request)
    {
        $this->log('Handle notify method called');
        $mapper = new Mapper($this->logger);
        return $mapper->map($request);
    }

    /**
     * @param string $storedSecurityToken
     * @param PaymentResultInfo $paymentResult
     * @return bool
     */
    public function verifySecurityToken($storedSecurityToken, PaymentResultInfo $paymentResult)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($storedSecurityToken, $paymentResult->getSecurityToken());
        }
        return strcmp($storedSecurityToken, $paymentResult->getSecurityToken()) === 0;
    }

    private function log($message, $level = LogLevel::DEBUG)
    {
        if ($this->logger) {
            $this->logger->log($level, '[Lib MonetaWeb2]: ' . $message);
        }
    }
}
