<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 23/11/17
 * Time: 17.10
 */

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Result;

interface NonErrorPaymentResultInterface extends PaymentResultInterface
{
    /**
     * @return bool
     */
    public function isSuccessful();

    /**
     * @return string
     */
    public function getResult();

    /**
     * @return string
     */
    public function getAuthorizationCode();

    /**
     * @return string
     */
    public function getMerchantOrderId();

    /**
     * @return string
     */
    public function getCustomField();

    /**
     * @return string
     */
    public function getSecurityToken();
}
