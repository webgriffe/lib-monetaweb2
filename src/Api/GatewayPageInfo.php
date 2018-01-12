<?php

namespace Webgriffe\LibMonetaWebDue\Api;

class GatewayPageInfo implements GatewayPageInfoInterface
{
    private $hostedPageUrl;
    private $securityToken;
    private $paymentId;

    /**
     * GatewayPageInfo constructor.
     *
     * @param string $hostedPageUrl
     * @param string $securityToken
     * @param string $paymentId
     */
    public function __construct($hostedPageUrl, $securityToken, $paymentId)
    {
        $this->hostedPageUrl = $hostedPageUrl;
        $this->securityToken = $securityToken;
        $this->paymentId = $paymentId;
    }

    /**
     * @return string
     */
    public function getHostedPageUrl()
    {
        return $this->hostedPageUrl;
    }

    /**
     * @return string
     */
    public function getSecurityToken()
    {
        return $this->securityToken;
    }

    /**
     * @return string
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }
}
