<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 12/01/18
 * Time: 14.56
 */

namespace Webgriffe\LibMonetaWebDue\Api;

use Psr\Http\Message\ServerRequestInterface;
use Webgriffe\LibMonetaWebDue\PaymentInit\UrlGeneratorInterface;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\NonErrorPaymentResultInterface;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInterface;

interface GatewayClientInterface
{
    /**
     * @param $gatewayBaseUrl
     * @param $terminalId
     * @param $terminalPassword
     * @param $amount
     * @param $currencyCode
     * @param $language
     * @param $responseToMerchantUrl
     * @param $recoveryUrl
     * @param $orderId
     * @param null $paymentDescription
     * @param null $cardHolderName
     * @param null $cardholderEmail
     * @param null $customField
     * @param $operationType
     *
     * @return GatewayPageInfoInterface
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
    );

    /**
     * @param ServerRequestInterface $request
     *
     * @return PaymentResultInterface
     *
     * @throws \InvalidArgumentException
     */
    public function handleNotify(ServerRequestInterface $request);

    /**
     * @param string $storedSecurityToken
     * @param NonErrorPaymentResultInterface $paymentResult
     *
     * @return bool
     */
    public function verifySecurityToken($storedSecurityToken, NonErrorPaymentResultInterface $paymentResult);
}
