<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Response;

class SuccessGenerator extends AbstractGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        return $this->successUrl;
    }
}
