<?php

namespace Webgriffe\LibMonetaWebDue\Api;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Webgriffe\LibMonetaWebDue\PaymentCapture\Request\RequestGenerator;
use Webgriffe\LibMonetaWebDue\PaymentCapture\Response\ResponseInterface;
use Webgriffe\LibMonetaWebDue\PaymentInit\UrlGenerator;
use Webgriffe\LibMonetaWebDue\PaymentInit\UrlGeneratorInterface;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Mapper;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInterface;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\NonErrorPaymentResultInterface;

class GatewayClient implements GatewayClientInterface
{
    /**
     * @var ClientInterface $client
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * GatewayClient constructor.
     *
     * @param ClientInterface $client
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        ClientInterface $client,
        LoggerInterface $logger = null,
        UrlGeneratorInterface $urlGenerator = null
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->urlGenerator = $urlGenerator ?: new UrlGenerator();
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
     * @param string $operationType One of
     *      \Webgriffe\LibMonetaWebDue\PaymentInit\UrlGenerator::OPERATION_TYPE_INITIALIZE or
     *      \Webgriffe\LibMonetaWebDue\PaymentInit\UrlGenerator::OPERATION_TYPE_INITIALIZE_MYBANK
     *
     * @return GatewayPageInfoInterface
     *
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
        $customField = null,
        $operationType = UrlGeneratorInterface::OPERATION_TYPE_INITIALIZE
    ) {
        $this->log('Get payment page info method called');

        $requestData = $this->urlGenerator->generate(
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
            $customField,
            $operationType
        );

        $this->log(
            sprintf('Doing a request with the following data: %s', PHP_EOL . print_r($requestData->getParams(), true))
        );
        $response = $this->client->request(
            $requestData->getMethod(),
            $requestData->getUrl(),
            ['form_params' => $requestData->getParams()]
        );
        $this->log(sprintf('The request returned the following response: %s', PHP_EOL . print_r($response, true)));

        // todo: handle xml parsing errors
        $parsedResponseBody = simplexml_load_string($response->getBody());
        // todo: it could throw a custom exception that encapsulate the error code and message
        if (isset($parsedResponseBody->errorcode) || isset($parsedResponseBody->errormessage)) {
            $message = sprintf(
                'The request sent to MonetaWebDue gateway generated an error with code "%s" and message: %s',
                $parsedResponseBody->errorcode,
                $parsedResponseBody->errormessage
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
     *
     * @return PaymentResultInterface
     *
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
     * @param NonErrorPaymentResultInterface $paymentResult
     * @return bool
     */
    public function verifySecurityToken($storedSecurityToken, NonErrorPaymentResultInterface $paymentResult)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($storedSecurityToken, $paymentResult->getSecurityToken());
        }

        return strcmp($storedSecurityToken, $paymentResult->getSecurityToken()) === 0;
    }

    /**
     * @param $gatewayBaseUrl
     * @param $terminalId
     * @param $terminalPassword
     * @param $amount
     * @param $currencyCode
     * @param $orderId
     * @param $paymentId
     * @param null $customField
     * @param null $description
     *
     * @return ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function capture(
        $gatewayBaseUrl,
        $terminalId,
        $terminalPassword,
        $amount,
        $currencyCode,
        $orderId,
        $paymentId,
        $customField = null,
        $description = null
    ) {
        $requestGenerator = new RequestGenerator($this->logger);
        $requestData = $requestGenerator->generate(
            $gatewayBaseUrl,
            $terminalId,
            $terminalPassword,
            $amount,
            $currencyCode,
            $orderId,
            $paymentId,
            $customField,
            $description
        );

        $this->log('Before sending capture request to the gateway');
        $response = $this->client->request(
            $requestData->getMethod(),
            $requestData->getUrl(),
            ['form_params' => $requestData->getParams()]
        );
        $this->log('After sending capture request to the gateway');

        $mapper = new \Webgriffe\LibMonetaWebDue\PaymentCapture\Response\Mapper($this->logger);
        return $mapper->map($response);
    }

    private function log($message, $level = LogLevel::DEBUG)
    {
        if ($this->logger) {
            $this->logger->log($level, '[Lib MonetaWeb2]: ' . $message);
        }
    }
}
