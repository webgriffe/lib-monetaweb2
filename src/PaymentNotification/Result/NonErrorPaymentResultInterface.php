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
     * Returns true if everything went well
     *
     * @return bool
     */
    public function isSuccessful();

    /**
     * Returns true if the payment was aborted intentionally by the payer. Will not return true in case of technical
     * problems.
     *
     * @return bool
     */
    public function isCanceled();

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
