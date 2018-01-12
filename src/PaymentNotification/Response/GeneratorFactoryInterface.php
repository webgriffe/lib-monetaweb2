<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 12/01/18
 * Time: 15.48
 */

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Response;

use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInterface;

interface GeneratorFactoryInterface
{
    /**
     * @param PaymentResultInterface $result
     * @param string $successUrl
     * @param string $errorUrl
     *
     * @return GeneratorInterface
     */
    public function getGenerator(PaymentResultInterface $result, $successUrl, $errorUrl);
}
