<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Result;

interface PaymentResultInterface
{
    /**
     * @return string
     */
    public function getPaymentId();
}
