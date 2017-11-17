<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 17/11/17
 * Time: 17.09
 */

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Result;

class MyBankPaymentResultInfo implements PaymentResultInterface
{
    const TRANSACTION_AUTHORISED_CODE   = 'AUTHORISED';
    const TRANSACTION_ERROR_CODE        = 'ERROR';
    const TRANSACTION_ABORTED_CODE      = 'AUTHORISINGPARTYABORTED';
    const TRANSACTION_CANCELED_CODE     = 'CANCELED';

    private $paymentId;
    private $result;
    private $description;
    private $authorizationCode;
    private $merchantOrderId;
    private $myBankId;
    private $customField;
    private $securityToken;

    /**
     * MyBankPaymentResultInfo constructor.
     *
     * @param $paymentId
     * @param $result
     * @param $description
     * @param $authorizationCode
     * @param $merchantOrderId
     * @param $myBankId
     * @param $customField
     * @param $securityToken
     */
    public function __construct(
        $paymentId,
        $result,
        $description,
        $authorizationCode,
        $merchantOrderId,
        $myBankId,
        $customField,
        $securityToken
    ) {
        $this->paymentId = $paymentId;
        $this->result = $result;
        $this->description = $description;
        $this->authorizationCode = $authorizationCode;
        $this->merchantOrderId = $merchantOrderId;
        $this->myBankId = $myBankId;
        $this->customField = $customField;
        $this->securityToken = $securityToken;
    }

    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @return mixed
     */
    public function getMerchantOrderId()
    {
        return $this->merchantOrderId;
    }

    /**
     * @return mixed
     */
    public function getMyBankId()
    {
        return $this->myBankId;
    }

    /**
     * @return mixed
     */
    public function getCustomField()
    {
        return $this->customField;
    }

    /**
     * @return mixed
     */
    public function getSecurityToken()
    {
        return $this->securityToken;
    }
}
