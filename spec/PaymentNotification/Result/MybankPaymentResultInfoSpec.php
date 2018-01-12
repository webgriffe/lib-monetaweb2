<?php

namespace spec\Webgriffe\LibMonetaWebDue\PaymentNotification\Result;

use PhpSpec\ObjectBehavior;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\MybankPaymentResultInterface;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInfo;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\CCPaymentResultInterface;

class MybankPaymentResultInfoSpec extends ObjectBehavior
{
    public function it_should_be_successful()
    {
        $this->beConstructedWith(
            '1234567890',
            MybankPaymentResultInterface::TRANSACTION_AUTHORISED_CODE,
            'desc',
            '54664641411',
            'merchant id',
            'mybank id',
            'custom',
            '80957febda6a467c82d34da0e0673a6e'
        );

        $this->isSuccessful()->shouldReturn(true);
        $this->isCanceled()->shouldReturn(false);
        $this->isError()->shouldReturn(false);
    }

    public function it_should_be_canceled()
    {
        $this->beConstructedWith(
            '1234567890',
            MybankPaymentResultInterface::TRANSACTION_CANCELED_CODE,
            'desc',
            '54664641411',
            'merchant id',
            'mybank id',
            'custom',
            '80957febda6a467c82d34da0e0673a6e'
        );

        $this->isSuccessful()->shouldReturn(false);
        $this->isCanceled()->shouldReturn(true);
        $this->isError()->shouldReturn(false);
    }

    public function it_should_be_aborted()
    {
        $this->beConstructedWith(
            '1234567890',
            MybankPaymentResultInterface::TRANSACTION_ABORTED_CODE,
            'desc',
            '54664641411',
            'merchant id',
            'mybank id',
            'custom',
            '80957febda6a467c82d34da0e0673a6e'
        );

        $this->isSuccessful()->shouldReturn(false);
        $this->isCanceled()->shouldReturn(true);
        $this->isError()->shouldReturn(false);
    }

    public function it_should_be_error()
    {
        $this->beConstructedWith(
            '1234567890',
            MybankPaymentResultInterface::TRANSACTION_ERROR_CODE,
            'desc',
            '54664641411',
            'merchant id',
            'mybank id',
            'custom',
            '80957febda6a467c82d34da0e0673a6e'
        );

        $this->isSuccessful()->shouldReturn(false);
        $this->isCanceled()->shouldReturn(false);
        $this->isError()->shouldReturn(true);
    }
}
