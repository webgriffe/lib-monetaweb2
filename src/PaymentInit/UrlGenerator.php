<?php

namespace Webgriffe\LibMonetaWebDue\PaymentInit;

use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Respect\Validation\Validator;
use Webgriffe\LibMonetaWebDue\Lists\Currencies;

class UrlGenerator
{
    const OPERATION_TYPE_INITIALIZE = 'initialize';
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger = null)
    {
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
     * @return string
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    public function generate(
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
        $this->log('Generating payment initialization url');
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
            Validator::url()->length(1, 2048)->assert($responseToMerchantUrl);
            Validator::optional(Validator::url()->length(null, 2048))->assert($recoveryUrl);
            Validator::alnum()->noWhitespace()->length(1, 18)->assert($orderId);
            Validator::optional(Validator::length(null, 255))->assert($paymentDescription);
            Validator::optional(Validator::length(null, 125))->assert($cardHolderName);
            Validator::optional(Validator::length(null, 125))->assert($cardholderEmail);
            Validator::optional(Validator::length(null, 255))->assert($customField);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), LogLevel::CRITICAL);
            throw new InvalidArgumentException($e->getMessage());
        }

        $params = [
            'id' => $terminalId,
            'password' => $terminalPassword,
            'operationType' => self::OPERATION_TYPE_INITIALIZE,
            'amount' => number_format($amount, 2, '.', ''),
            'currencyCode' => $currencyCode ? $this->getCurrencyNumericCode($currencyCode) : null,
            'language' => $this->validateLanguage($language),
            'responseToMerchantUrl' => $responseToMerchantUrl,
            'recoveryUrl' => $recoveryUrl,
            'merchantOrderId' => $orderId,
            'description' => $paymentDescription,
            'cardHolderName' => $cardHolderName,
            'cardHolderEmail' => $cardholderEmail,
            'customField' => $customField,
        ];
        $generatedUrl = $gatewayBaseUrl . '?' . http_build_query($params);
        $this->log('Generated URL is: ' . $generatedUrl);
        return $generatedUrl;
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

    private function validateLanguage($language)
    {
        $allowedLanguages = ['DEU', 'FRA', 'ITA', 'POR', 'RUS', 'SPA', 'USA'];

        if (!in_array($language, $allowedLanguages, true)) {
            return 'USA';
        }
        return $language;
    }

    private function log($message, $level = LogLevel::DEBUG)
    {
        if ($this->logger) {
            $this->logger->log($level, '[Lib MonetaWeb2]: ' . $message);
        }
    }
}
