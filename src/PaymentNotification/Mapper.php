<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification;

use Psr\Http\Message\ServerRequestInterface;

class Mapper
{
    /**
     * @param ServerRequestInterface $request
     * @return PaymentResultInfo
     */
    public function map(ServerRequestInterface $request)
    {
        // todo: validation
        // todo: error
        $paymentResultInfo = new PaymentResultInfo();
        $requestBody = $request->getParsedBody();
        $paymentResultInfo->setAuthorizationCode($requestBody['authorizationcode']);
        $paymentResultInfo->setCardCountry($requestBody['cardcountry']);
        $paymentResultInfo->setCardExpiryDate($requestBody['cardexpirydate']);
        $paymentResultInfo->setCardType($requestBody['cardtype']);
        $paymentResultInfo->setCustomField($requestBody['customfield']);
        $paymentResultInfo->setMaskedPan($requestBody['maskedpan']);
        $paymentResultInfo->setMerchantOrderId($requestBody['merchantorderid']);
        $paymentResultInfo->setResponseCode($requestBody['responsecode']);
        $paymentResultInfo->setResult($requestBody['result']);
        $paymentResultInfo->setRetrievalReferenceNumber($requestBody['rrn']);
        $paymentResultInfo->setSecurityToken($requestBody['securitytoken']);
        $paymentResultInfo->setThreeDSecure($requestBody['threedsecure']);

        return $paymentResultInfo;
    }
}
