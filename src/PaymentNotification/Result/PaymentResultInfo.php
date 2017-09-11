<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Result;

class PaymentResultInfo implements PaymentResultInterface
{
    const SUCCESSFUL_RESPONSE_CODE = '000';
    const TRANSACTION_AUTHORIZED_CODE = 'APPROVED';
    const TRANSACTION_CAPTURED_CODE = 'CAPTURED';
    const TRANSACTION_CANCELED_CODE = 'CANCELED';

    private $authorizationCode;
    private $cardCountry;
    private $cardExpiryDate;
    private $cardType;
    private $customField;
    private $maskedPan;
    private $merchantOrderId;
    private $paymentId;
    private $responseCode;
    private $result;
    private $retrievalReferenceNumber;
    private $securityToken;
    private $threeDSecure;

    /**
     * PaymentResultInfo constructor.
     * @param $authorizationCode
     * @param $cardCountry
     * @param $cardExpiryDate
     * @param $cardType
     * @param $customField
     * @param $maskedPan
     * @param $merchantOrderId
     * @param $paymentId
     * @param $responseCode
     * @param $result
     * @param $retrievalReferenceNumber
     * @param $securityToken
     * @param $threeDSecure
     */
    public function __construct(
        $authorizationCode,
        $cardCountry,
        $cardExpiryDate,
        $cardType,
        $customField,
        $maskedPan,
        $merchantOrderId,
        $paymentId,
        $responseCode,
        $result,
        $retrievalReferenceNumber,
        $securityToken,
        $threeDSecure
    ) {
        $this->authorizationCode = $authorizationCode;
        $this->cardCountry = $cardCountry;
        $this->cardExpiryDate = $cardExpiryDate;
        $this->cardType = $cardType;
        $this->customField = $customField;
        $this->maskedPan = $maskedPan;
        $this->merchantOrderId = $merchantOrderId;
        $this->paymentId = $paymentId;
        $this->responseCode = $responseCode;
        $this->result = $result;
        $this->retrievalReferenceNumber = $retrievalReferenceNumber;
        $this->securityToken = $securityToken;
        $this->threeDSecure = $threeDSecure;
    }

    public function isAuthorizationOnly()
    {
        return $this->result === self::TRANSACTION_AUTHORIZED_CODE;
    }

    public function isAuthorizationCaptured()
    {
        return $this->result === self::TRANSACTION_CAPTURED_CODE;
    }

    public function isCanceled()
    {
        return $this->result === self::TRANSACTION_CANCELED_CODE;
    }

    public function isSuccessful()
    {
        return $this->responseCode === self::SUCCESSFUL_RESPONSE_CODE;
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
    public function getCardCountry()
    {
        return $this->cardCountry;
    }

    /**
     * @return mixed
     */
    public function getCardExpiryDate()
    {
        return $this->cardExpiryDate;
    }

    /**
     * @return mixed
     */
    public function getCardType()
    {
        return $this->cardType;
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
    public function getMaskedPan()
    {
        return $this->maskedPan;
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
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->responseCode;
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
    public function getRetrievalReferenceNumber()
    {
        return $this->retrievalReferenceNumber;
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
    public function getThreeDSecure()
    {
        return $this->threeDSecure;
    }
}
