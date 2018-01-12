<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 12/01/18
 * Time: 15.23
 */

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Result;


interface MybankPaymentResultInterface extends NonErrorPaymentResultInterface
{
    const TRANSACTION_AUTHORISED_CODE   = 'AUTHORISED';
    const TRANSACTION_ERROR_CODE        = 'ERROR';
    const TRANSACTION_ABORTED_CODE      = 'AUTHORISINGPARTYABORTED';
    const TRANSACTION_CANCELED_CODE     = 'CANCELED';

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getMyBankId();
}
