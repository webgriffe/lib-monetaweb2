<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Response;

class SuccessGenerator extends AbstractGenerator
{
    public function generate()
    {
        return $this->successUrl;
    }
}
