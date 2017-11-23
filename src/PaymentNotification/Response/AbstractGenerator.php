<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Response;

abstract class AbstractGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    protected $successUrl;

    /**
     * @var string
     */
    protected $errorUrl;

    /**
     * AbstractGenerator constructor.
     * @param string $successUrl
     * @param string $errorUrl
     */
    public function __construct($successUrl, $errorUrl)
    {
        $this->successUrl = $successUrl;
        $this->errorUrl = $errorUrl;
    }

    /**
     * @return string
     */
    abstract public function generate();
}
