<?php

namespace spec\Webgriffe\LibMonetaWebDue\PaymentNotification\Response;

use PhpSpec\ObjectBehavior;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Response\ErrorGenerator;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Response\SuccessGenerator;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\MyBankPaymentResultInfo;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInfo;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\CCPaymentResultInterface;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\MybankPaymentResultInterface;

class GeneratorFactorySpec extends ObjectBehavior
{
    public function it_should_return_success_generator_when_payment_result_is_successful()
    {
        $paymentResult = $this->getPaymentResult(CCPaymentResultInterface::SUCCESSFUL_RESPONSE_CODE);
        $this->getGenerator($paymentResult, 'success', 'error')->shouldReturnAnInstanceOf(SuccessGenerator::class);
    }

    public function it_should_return_error_generator_when_payment_result_is_not_successful()
    {
        $paymentResult = $this->getPaymentResult('020');
        $this->getGenerator($paymentResult, 'success', 'error')->shouldReturnAnInstanceOf(ErrorGenerator::class);
    }

    public function it_should_return_success_generator_when_mybank_payment_result_is_successful()
    {
        $paymentResult = $this->getMybankPaymentResult(MybankPaymentResultInterface::TRANSACTION_AUTHORISED_CODE);
        $this->getGenerator($paymentResult, 'success', 'error')->shouldReturnAnInstanceOf(SuccessGenerator::class);
    }

    /**
     * @param string $responseCode
     * @return PaymentResultInfo
     */
    protected function getPaymentResult($responseCode)
    {
        return new PaymentResultInfo(
            '85963',
            'ITALY',
            '0115',
            'VISA',
            'some custom field',
            '483054******1294',
            'TRCK0001',
            '123456789012345678',
            $responseCode,
            CCPaymentResultInterface::TRANSACTION_APPROVED_CODE,
            '123456789012',
            '80957febda6a467c82d34da0e0673a6e',
            'S'
        );
    }

    protected function getMyBankPaymentResult($result)
    {
        return new MyBankPaymentResultInfo(
            '123456789012345678',
            $result,
            'trans',
            '85963',
            'TRCK0001',
            'Mybank id',
            'custom',
            '80957febda6a467c82d34da0e0673a6e'
        );
    }
}
