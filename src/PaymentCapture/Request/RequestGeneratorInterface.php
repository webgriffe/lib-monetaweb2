<?php
/**
 * Created by PhpStorm.
 * User: kraken
 * Date: 18/09/18
 * Time: 15.58
 */

namespace Webgriffe\LibMonetaWebDue\PaymentCapture\Request;

use GuzzleHttp\Psr7\Request;

interface RequestGeneratorInterface
{
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
    );
}
