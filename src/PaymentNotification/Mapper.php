<?php

namespace Webgriffe\LibMonetaWebDue\PaymentNotification;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Webgriffe\LibMonetaWebDue\PaymentInit\UrlGenerator;

class Mapper
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param ServerRequestInterface $request
     * @param string $operationType
     *
     * @return Result\PaymentResultInterface
     * @throws \InvalidArgumentException
     */
    public function map(ServerRequestInterface $request, $operationType = UrlGenerator::OPERATION_TYPE_INITIALIZE)
    {
        $this->log(
            sprintf(
                'Mapping the following PSR request into a PaymentResult object: %s',
                PHP_EOL . print_r($request, true)
            )
        );
        $requestBody = $request->getParsedBody();
        $requestBody = array_change_key_case($requestBody, CASE_LOWER);

        $paymentid = null;
        if (array_key_exists('paymentid', $requestBody)) {
            $paymentid = $requestBody['paymentid'];
        }

        if (isset($requestBody['errorcode'])) {
            $paymentError = new Result\PaymentResultErrorInfo(
                $requestBody['errorcode'],
                $requestBody['errormessage'],
                $paymentid
            );
            $this->log(
                sprintf(
                    'Got a request with errors, the following PaymentResult object will be returned: %s',
                    PHP_EOL . print_r($paymentError, true)
                )
            );
            return $paymentError;
        }

        if ($operationType == UrlGenerator::OPERATION_TYPE_INITIALIZE_MYBANK) {
            $this->checkRequiredMyBankParameters($requestBody);

            $result = new Result\MyBankPaymentResultInfo(
                $paymentid,
                $requestBody['result'],
                $this->coalesceOperator('description', $requestBody),
                $this->coalesceOperator('authorizationcode', $requestBody),
                $this->coalesceOperator('merchantorderid', $requestBody),
                $this->coalesceOperator('mybankid', $requestBody),
                $this->coalesceOperator('customfield', $requestBody),
                $this->coalesceOperator('securitytoken', $requestBody)
            );
        } else {
            $this->checkRequiredParameters($requestBody);

            $result = new Result\PaymentResultInfo(
                $this->coalesceOperator('authorizationcode', $requestBody),
                $this->coalesceOperator('cardcountry', $requestBody),
                $this->coalesceOperator('cardexpirydate', $requestBody),
                $this->coalesceOperator('cardtype', $requestBody),
                $this->coalesceOperator('customfield', $requestBody),
                $this->coalesceOperator('maskedpan', $requestBody),
                $this->coalesceOperator('merchantorderid', $requestBody),
                $paymentid,
                $this->coalesceOperator('responsecode', $requestBody),
                $requestBody['result'],
                $this->coalesceOperator('rrn', $requestBody),
                $this->coalesceOperator('securitytoken', $requestBody),
                $requestBody['threedsecure']
            );
        }

        $this->log(
            sprintf('Returing the following PaymentResult object: %s', PHP_EOL . print_r($result, true))
        );

        return $result;
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

    /**
     * @param array $requestBody
     * @throws \InvalidArgumentException
     */
    private function checkRequiredParameters($requestBody)
    {
        if (!isset($requestBody['paymentid'], $requestBody['result'], $requestBody['threedsecure'])) {
            $message = 'One or more required parameters are missing: paymentid, result and threedsecure';
            $this->log($message, LogLevel::ERROR);
            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * @param array $requestBody
     * @throws \InvalidArgumentException
     */
    private function checkRequiredMyBankParameters($requestBody)
    {
        if (!isset($requestBody['paymentid'], $requestBody['result'])) {
            $message = 'One or more required parameters are missing: paymentid and result';
            $this->log($message, LogLevel::ERROR);
            throw new \InvalidArgumentException($message);
        }
    }

    private function log($message, $level = LogLevel::DEBUG)
    {
        if ($this->logger) {
            $this->logger->log($level, '[Lib MonetaWeb2]: ' . $message);
        }
    }
}
