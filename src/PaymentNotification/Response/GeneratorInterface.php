<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Response;

interface GeneratorInterface
{
    /**
     * @return string
     */
    public function generate();
}
