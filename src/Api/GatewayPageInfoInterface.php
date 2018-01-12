<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 12/01/18
 * Time: 14.57
 */

namespace Webgriffe\LibMonetaWebDue\Api;

interface GatewayPageInfoInterface
{
    /**
     * @return string
     */
    public function getHostedPageUrl();

    /**
     * @return string
     */
    public function getSecurityToken();

    /**
     * @return string
     */
    public function getPaymentId();
}