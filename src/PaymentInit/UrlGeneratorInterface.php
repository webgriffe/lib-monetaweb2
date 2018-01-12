<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 12/01/18
 * Time: 15.00
 */

namespace Webgriffe\LibMonetaWebDue\PaymentInit;

interface UrlGeneratorInterface
{
    const OPERATION_TYPE_INITIALIZE         = 'initialize';
    const OPERATION_TYPE_INITIALIZE_MYBANK  = 'initializemybank';

    /**
     * @param $gatewayBaseUrl
     * @param $terminalId
     * @param $terminalPassword
     * @param $amount
     * @param $currencyCode
     * @param $languageCode
     * @param $responseToMerchantUrl
     * @param $recoveryUrl
     * @param $orderId
     * @param null $paymentDescription
     * @param null $cardHolderName
     * @param null $cardholderEmail
     * @param null $customField
     * @param $operationType
     *
     * @return string
     */
    public function generate(
        $gatewayBaseUrl,
        $terminalId,
        $terminalPassword,
        $amount,
        $currencyCode,
        $languageCode,
        $responseToMerchantUrl,
        $recoveryUrl,
        $orderId,
        $paymentDescription = null,
        $cardHolderName = null,
        $cardholderEmail = null,
        $customField = null,
        $operationType = self::OPERATION_TYPE_INITIALIZE
    );
}
