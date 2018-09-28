<?php
/**
 * Created by PhpStorm.
 * User: kraken
 * Date: 18/09/18
 * Time: 16.16
 */

namespace Webgriffe\LibMonetaWebDue\PaymentCapture\Response;

class SuccessResponse implements SuccessResponseInterface
{
    /**
     * @var string
     */
    private $paymentId;

    /**
     * @var string
     */
    private $result;

    /**
     * @var string
     */
    private $responseCode;

    /**
     * @var string
     */
    private $authorizationCode;

    /**
     * @var string
     */
    private $merchantOrderId;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $customField;

    public function __construct(
        $paymentId,
        $result,
        $responseCode,
        $authorizationCode,
        $merchantOrderId,
        $description,
        $customField
    ) {

        $this->paymentId = $paymentId;
        $this->result = $result;
        $this->responseCode = $responseCode;
        $this->authorizationCode = $authorizationCode;
        $this->merchantOrderId = $merchantOrderId;
        $this->description = $description;
        $this->customField = $customField;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getMerchantOrderId()
    {
        return $this->merchantOrderId;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomField()
    {
        return $this->customField;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccesful()
    {
        return $this->getResult() == self::SUCCESS_RESULT;
    }
}
