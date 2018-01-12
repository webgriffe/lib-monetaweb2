<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 17/11/17
 * Time: 17.09
 */

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Result;

class MyBankPaymentResultInfo implements MybankPaymentResultInterface
{
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
     * @param string $paymentId
     * @param string $result
     * @param string $description
     * @param string $authorizationCode
     * @param string $merchantOrderId
     * @param string $myBankId
     * @param string $customField
     * @param string $securityToken
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

    /**
     * {@inheritdoc}
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->result === MybankPaymentResultInterface::TRANSACTION_AUTHORISED_CODE;
    }

    /**
     * @return bool
     */
    public function isCanceled()
    {
        return $this->result === MybankPaymentResultInterface::TRANSACTION_CANCELED_CODE ||
            $this->result == MybankPaymentResultInterface::TRANSACTION_ABORTED_CODE;
    }

    /**
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @return string
     */
    public function getMerchantOrderId()
    {
        return $this->merchantOrderId;
    }

    /**
     * @return string
     */
    public function getMyBankId()
    {
        return $this->myBankId;
    }

    /**
     * @return string
     */
    public function getCustomField()
    {
        return $this->customField;
    }

    /**
     * @return string
     */
    public function getSecurityToken()
    {
        return $this->securityToken;
    }
}
