<?php
/**
 * Created by PhpStorm.
 * User: kraken
 * Date: 18/09/18
 * Time: 15.57
 */

namespace Webgriffe\LibMonetaWebDue\PaymentCapture\Request;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Webgriffe\LibMonetaWebDue\Lists\Currencies;
use GuzzleHttp\Psr7\Request;
use Psr\Log\InvalidArgumentException;
use Respect\Validation\Validator;

class RequestGenerator implements RequestGeneratorInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
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
     * @return \Psr\Http\Message\RequestInterface
     */
    public function generate(
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
        $this->log('Generating payment capture request');

        if (empty($gatewayBaseUrl) || empty($terminalId) || empty($terminalPassword) || $amount === null) {
            $message = 'Base Url, Terminal ID, Terminal Password and Amount are required';
            $this->log($message, LogLevel::CRITICAL);
            throw new InvalidArgumentException($message);
        }

        if ((float)$amount === 0.0) {
            $message = 'Amount should be greater than zero';
            $this->log($message, LogLevel::CRITICAL);
            throw new InvalidArgumentException($message);
        }

        try {
            Validator::stringType()->length(1, 8)->assert($terminalId);
            Validator::stringType()->length(1, 50)->assert($terminalPassword);
            Validator::floatType()->assert($amount);
            Validator::stringType()->length(3, 3)->assert($currencyCode);
            Validator::alnum()->noWhitespace()->length(1, 18)->assert($orderId);
            Validator::optional(Validator::length(null, 255))->assert($customField);
            Validator::optional(Validator::length(null, 255))->assert($description);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), LogLevel::CRITICAL);
            throw new InvalidArgumentException($e->getMessage());
        }

        $params = [
            'id' => $terminalId,
            'password' => $terminalPassword,
            'operationtype' => 'confirm',
            'amount' => number_format($amount, 2, '.', ''),
            'currencytode' => $this->getCurrencyNumericCode($currencyCode),
            'merchantorderid' => $orderId.'C',
            'paymentid' => $paymentId,
            'customfield' => $customField,
            'description' => $description,
        ];

        $this->log('Request params: '.print_r($params, true));

        return new Request('POST', $gatewayBaseUrl, [], http_build_query($params));
    }

    /**
     * @param string $currencyCode
     * @return mixed
     * @throws \InvalidArgumentException
     */
    private function getCurrencyNumericCode($currencyCode)
    {
        $currencies = new Currencies();
        $currenciesList = $currencies->getList();
        if (!array_key_exists($currencyCode, $currenciesList)) {
            $message = sprintf(
                'Cannot get the numeric code for currency "%s", it is not one of the supported currencies.',
                $currencyCode
            );
            $this->log($message, LogLevel::CRITICAL);
            throw new \InvalidArgumentException($message);
        }
        return $currenciesList[$currencyCode];
    }

    private function log($message, $level = LogLevel::DEBUG)
    {
        if ($this->logger) {
            $this->logger->log($level, '[Lib MonetaWeb2]: ' . $message);
        }
    }
}
