<?php
/**
 * Created by PhpStorm.
 * User: kraken
 * Date: 18/09/18
 * Time: 16.20
 */

namespace Webgriffe\LibMonetaWebDue\PaymentCapture\Response;

interface SuccessResponseInterface extends ResponseInterface
{
    const SUCCESS_RESULT = 'CAPTURED';

    /**
     * @return string
     */
    public function getPaymentId();

    /**
     * @return string
     */
    public function getResult();

    /**
     * @return string
     */
    public function getResponseCode();

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
    public function getDescription();

    /**
     * @return string
     */
    public function getCustomField();

    /**
     * @return boolean
     */
    public function isSuccesful();
}
