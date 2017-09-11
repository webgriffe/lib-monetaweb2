<?php

namespace Webgriffe\LibMonetaWebDue\Api;

class GatewayPageInfo
{

    private $hostedPageUrl;
    private $securityToken;
    private $paymentId;

    /**
     * GatewayPageInfo constructor.
     * @param $hostedPageUrl
     * @param $securityToken
     * @param $paymentId
     */
    public function __construct($hostedPageUrl, $securityToken, $paymentId)
    {

        $this->hostedPageUrl = $hostedPageUrl;
        $this->securityToken = $securityToken;
        $this->paymentId = $paymentId;
    }

    /**
     * @return mixed
     */
    public function getHostedPageUrl()
    {
        return $this->hostedPageUrl;
    }

    /**
     * @return mixed
     */
    public function getSecurityToken()
    {
        return $this->securityToken;
    }

    /**
     * @return mixed
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }
}
