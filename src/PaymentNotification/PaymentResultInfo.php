<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification;

class PaymentResultInfo
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
    private $errorCode;
    private $errorMessage;

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

    public function isError()
    {
        return $this->errorCode !== null;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @param mixed $authorizationCode
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;
    }

    /**
     * @return mixed
     */
    public function getCardCountry()
    {
        return $this->cardCountry;
    }

    /**
     * @param mixed $cardCountry
     */
    public function setCardCountry($cardCountry)
    {
        $this->cardCountry = $cardCountry;
    }

    /**
     * @return mixed
     */
    public function getCardExpiryDate()
    {
        return $this->cardExpiryDate;
    }

    /**
     * @param mixed $cardExpiryDate
     */
    public function setCardExpiryDate($cardExpiryDate)
    {
        $this->cardExpiryDate = $cardExpiryDate;
    }

    /**
     * @return mixed
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * @param mixed $cardType
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;
    }

    /**
     * @return mixed
     */
    public function getCustomField()
    {
        return $this->customField;
    }

    /**
     * @param mixed $customField
     */
    public function setCustomField($customField)
    {
        $this->customField = $customField;
    }

    /**
     * @return mixed
     */
    public function getMaskedPan()
    {
        return $this->maskedPan;
    }

    /**
     * @param mixed $maskedPan
     */
    public function setMaskedPan($maskedPan)
    {
        $this->maskedPan = $maskedPan;
    }

    /**
     * @return mixed
     */
    public function getMerchantOrderId()
    {
        return $this->merchantOrderId;
    }

    /**
     * @param mixed $merchantOrderId
     */
    public function setMerchantOrderId($merchantOrderId)
    {
        $this->merchantOrderId = $merchantOrderId;
    }

    /**
     * @return mixed
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @param mixed $paymentId
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param mixed $responseCode
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getRetrievalReferenceNumber()
    {
        return $this->retrievalReferenceNumber;
    }

    /**
     * @param mixed $retrievalReferenceNumber
     */
    public function setRetrievalReferenceNumber($retrievalReferenceNumber)
    {
        $this->retrievalReferenceNumber = $retrievalReferenceNumber;
    }

    /**
     * @return mixed
     */
    public function getSecurityToken()
    {
        return $this->securityToken;
    }

    /**
     * @param mixed $securityToken
     */
    public function setSecurityToken($securityToken)
    {
        $this->securityToken = $securityToken;
    }

    /**
     * @return mixed
     */
    public function getThreeDSecure()
    {
        return $this->threeDSecure;
    }

    /**
     * @param mixed $threeDSecure
     */
    public function setThreeDSecure($threeDSecure)
    {
        $this->threeDSecure = $threeDSecure;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param mixed $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param mixed $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
}
