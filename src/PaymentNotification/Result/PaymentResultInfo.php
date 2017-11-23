<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Result;

class PaymentResultInfo implements PaymentResultInterface
{
    const SUCCESSFUL_RESPONSE_CODE = '000';

    const TRANSACTION_APPROVED_CODE     = 'APPROVED';
    const TRANSACTION_NOT_APPROVED_CODE = 'NOT APPROVED';
    const TRANSACTION_CAPTURED_CODE     = 'CAPTURED';
    const TRANSACTION_CANCELED_CODE     = 'CANCELED';

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
     *
     * @param string $authorizationCode
     * @param string $cardCountry
     * @param string $cardExpiryDate
     * @param string $cardType
     * @param string $customField
     * @param string $maskedPan
     * @param string $merchantOrderId
     * @param string $paymentId
     * @param string $responseCode
     * @param string $result
     * @param string $retrievalReferenceNumber
     * @param string $securityToken
     * @param string $threeDSecure
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

    /**
     * This returns true for both authorize-only payment and immediate capture payments. Does NOT return true for
     * capture-only operations, where the isCapturedOnly() method returns true.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->result === self::TRANSACTION_APPROVED_CODE;
    }

    /**
     * This only returns true when capturing an amount that was authorized previously. This will NOT return true in case
     * of an immediate capture payment.
     *
     * @return bool
     */
    public function isCapturedOnly()
    {
        return $this->result === self::TRANSACTION_CAPTURED_CODE;
    }

    /**
     * @return bool
     */
    public function isCanceled()
    {
        return $this->result === self::TRANSACTION_CANCELED_CODE;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->responseCode === self::SUCCESSFUL_RESPONSE_CODE;
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
    public function getCardCountry()
    {
        return $this->cardCountry;
    }

    /**
     * @return string
     */
    public function getCardExpiryDate()
    {
        return $this->cardExpiryDate;
    }

    /**
     * @return string
     */
    public function getCardType()
    {
        return $this->cardType;
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
    public function getMaskedPan()
    {
        return $this->maskedPan;
    }

    /**
     * @return string
     */
    public function getMerchantOrderId()
    {
        return $this->merchantOrderId;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @return string
     */
    public function getResponseCode()
    {
        return $this->responseCode;
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
    public function getRetrievalReferenceNumber()
    {
        return $this->retrievalReferenceNumber;
    }

    /**
     * @return string
     */
    public function getSecurityToken()
    {
        return $this->securityToken;
    }

    /**
     * @return string
     */
    public function getThreeDSecure()
    {
        return $this->threeDSecure;
    }
}
