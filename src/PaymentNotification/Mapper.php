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
            $this->coalesceOperator('authorizationcode', $requestBody),
            $this->coalesceOperator('cardcountry', $requestBody),
            $this->coalesceOperator('cardexpirydate', $requestBody),
            $this->coalesceOperator('cardtype', $requestBody),
            $this->coalesceOperator('customfield', $requestBody),
            $this->coalesceOperator('maskedpan', $requestBody),
            $this->coalesceOperator('merchantorderid', $requestBody),
            $requestBody['paymentid'],
            $this->coalesceOperator('responsecode', $requestBody),
            $requestBody['result'],
            $this->coalesceOperator('rrn', $requestBody),
            $this->coalesceOperator('securitytoken', $requestBody),
            $requestBody['threedsecure']
        );

        return $paymentResultInfo;
    }

    /**
     * @param string $key
     * @param array $data
     * @return mixed|null
     */
    private function coalesceOperator($key, $data)
    {
        return isset($data[$key]) ? $data[$key] : null;
    }
}
