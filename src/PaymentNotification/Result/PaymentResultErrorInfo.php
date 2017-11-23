<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification\Result;

class PaymentResultErrorInfo implements PaymentResultInterface
{
    /**
     * @var string
     */
    private $errorCode;

    /**
     * @var string
     */
    private $errorMessage;

    /**
     * @var string
     */
    private $paymentId;

    /**
     * PaymentResultErrorInfo constructor.
     *
     * @param string $errorCode
     * @param string $errorMessage
     * @param string $paymentId
     */
    public function __construct($errorCode, $errorMessage, $paymentId)
    {
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
        $this->paymentId = $paymentId;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }
}
