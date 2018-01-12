<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Response;

use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\NonErrorPaymentResultInterface;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInterface;

class GeneratorFactory implements GeneratorFactoryInterface
{
    /**
     * @param PaymentResultInterface $result
     * @param string $successUrl
     * @param string $errorUrl
     *
     * @return GeneratorInterface
     */
    public function getGenerator(PaymentResultInterface $result, $successUrl, $errorUrl)
    {
        if ($result instanceof NonErrorPaymentResultInterface && $result->isSuccessful()) {
            return new SuccessGenerator($successUrl, $errorUrl);
        }

        return new ErrorGenerator($successUrl, $errorUrl);
    }
}
