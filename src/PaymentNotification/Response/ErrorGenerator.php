<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Response;

class ErrorGenerator extends AbstractGenerator
{
    public function generate()
    {
        return $this->errorUrl;
    }
}
