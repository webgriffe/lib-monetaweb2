<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 12/01/18
 * Time: 15.21
 */

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Result;

interface CCPaymentResultInterface extends NonErrorPaymentResultInterface
{
    const SUCCESSFUL_RESPONSE_CODE = '000';

    const TRANSACTION_APPROVED_CODE     = 'APPROVED';
    const TRANSACTION_NOT_APPROVED_CODE = 'NOT APPROVED';
    const TRANSACTION_CAPTURED_CODE     = 'CAPTURED';
    const TRANSACTION_CANCELED_CODE     = 'CANCELED';

    /**
     * This returns true for both authorize-only payment and immediate capture payments. Does NOT return true for
     * capture-only operations, where the isCapturedOnly() method returns true.
     *
     * @return bool
     */
    public function isApproved();

    /**
     * This only returns true when capturing an amount that was authorized previously. This will NOT return true in case
     * of an immediate capture payment.
     *
     * @return bool
     */
    public function isCapturedOnly();

    /**
     * @return string
     */
    public function getCardCountry();

    /**
     * @return string
     */
    public function getCardExpiryDate();

    /**
     * @return string
     */
    public function getCardType();

    /**
     * @return string
     */
    public function getMaskedPan();

    /**
     * @return string
     */
    public function getResponseCode();

    /**
     * @return string
     */
    public function getRetrievalReferenceNumber();

    /**
     * @return string
     */
    public function getThreeDSecure();
}
