<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification;

use Psr\Http\Message\ServerRequestInterface;

class Mapper
{
    /**
     * @param ServerRequestInterface $request
     * @return Result\PaymentResultInterface
     */
    public function map(ServerRequestInterface $request)
    {
        $requestBody = $request->getParsedBody();
        $requestBody = array_change_key_case($requestBody, CASE_LOWER);

        if (isset($requestBody['errorcode'])) {
            $paymentError = new Result\PaymentResultErrorInfo(
                $requestBody['errorcode'],
                $requestBody['errormessage'],
                $requestBody['paymentid']
            );
            return $paymentError;
        }

        $paymentResultInfo = new Result\PaymentResultInfo(
            $requestBody['authorizationcode'],
            $requestBody['cardcountry'],
            $requestBody['cardexpirydate'],
            $requestBody['cardtype'],
            $requestBody['customfield'],
            $requestBody['maskedpan'],
            $requestBody['merchantorderid'],
            $requestBody['paymentid'],
            $requestBody['responsecode'],
            $requestBody['result'],
            $requestBody['rrn'],
            $requestBody['securitytoken'],
            $requestBody['threedsecure']
        );

        return $paymentResultInfo;
    }
}
