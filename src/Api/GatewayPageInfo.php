<?php

namespace Webgriffe\LibMonetaWebDue\Api;

class GatewayPageInfo
{

    private $hostedPageUrl;
    private $securityToken;

    /**
     * GatewayPageInfo constructor.
     * @param $hostedPageUrl
     * @param $securityToken
     */
    public function __construct($hostedPageUrl, $securityToken)
    {

        $this->hostedPageUrl = $hostedPageUrl;
        $this->securityToken = $securityToken;
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
}
