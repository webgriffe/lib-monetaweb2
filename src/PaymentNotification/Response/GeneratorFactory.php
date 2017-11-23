<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Response;

use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInfo;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInterface;

class GeneratorFactory
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
        if ($result instanceof PaymentResultInfo && $result->isSuccessful()) {
            return new SuccessGenerator($successUrl, $errorUrl);
        }
        return new ErrorGenerator($successUrl, $errorUrl);
    }
}
