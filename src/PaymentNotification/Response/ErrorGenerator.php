<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Response;

class ErrorGenerator extends AbstractGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        return $this->errorUrl;
    }
}
