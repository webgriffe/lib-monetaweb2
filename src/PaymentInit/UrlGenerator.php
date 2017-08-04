<?php

namespace Webgriffe\LibMonetaWebDue\PaymentInit;

use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;

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

    public function generate(
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

        if(empty($baseUrl) || empty($terminalId) || empty($terminalPassword) || $amount === null) {
            throw new InvalidArgumentException('Base Url, Terminal ID, Terminal Password and Amount ere required');
        }

        if($amount === 0) {
            throw new InvalidArgumentException('Amount should be grater than zero');
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
        $generatedUrl = $baseUrl . '?' . http_build_query($params);
        $this->debug('Generated URL is: ' . $generatedUrl);
        return $generatedUrl;
    }


    private function getCurrencyNumericCode($currencyCode)
    {
        $map = array(
            'AED' => '784',
            'AOA' => '973',
            'ARS' => '032',
            'AUD' => '036',
            'AZN' => '944',
            'BGN' => '975',
            'BHD' => '048',
            'BRL' => '986',
            'BYR' => '974',
            'CAD' => '124',
            'CHF' => '756',
            'CLP' => '152',
            'CNY' => '156',
            'COP' => '170',
            'CZK' => '203',
            'DKK' => '208',
            'EGP' => '818',
            'EUR' => '978',
            'GBP' => '826',
            'HKD' => '344',
            'HRK' => '191',
            'HUF' => '348',
            'INR' => '356',
            'JOD' => '400',
            'JPY' => '392',
            'KRW' => '410',
            'KWD' => '414',
            'KZT' => '398',
            'MXN' => '484',
            'MYR' => '458',
            'NGN' => '566',
            'NOK' => '578',
            'PHP' => '608',
            'PLN' => '985',
            'QAR' => '634',
            'RON' => '946',
            'RSD' => '941',
            'RUB' => '643',
            'SAR' => '682',
            'SEK' => '752',
            'SGD' => '702',
            'THB' => '764',
            'TRY' => '949',
            'TWD' => '901',
            'USD' => '840',
            'VEF' => '937',
            'VND' => '704',
            'ZAR' => '710',
        );
        if (!array_key_exists($currencyCode, $map)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cannot get the numeric code for currency "%s", is not one of the supported currencies.',
                    $currencyCode
                )
            );
        }
        return $map[$currencyCode];
    }

    private function validateLanguage($language)
    {
        $allowedLanguages = ['DEU', 'FRA', 'ITA', 'POR', 'RUS', 'SPA', 'USA'];

        if(!in_array($language, $allowedLanguages, true)) {
            return 'USA';
        }
        return $language;
    }

    /**
     * @param $message
     */
    private function debug($message)
    {
        if ($this->logger) {
            $this->logger->debug($message);
        }
    }
}
