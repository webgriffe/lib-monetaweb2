<?php

namespace spec\Webgriffe\LibMonetaWebDue\PaymentNotification\Result;

use PhpSpec\ObjectBehavior;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInfo;

class PaymentResultInfoSpec extends ObjectBehavior
{
    public function it_should_be_successful_and_authorized()
    {
        $this->beConstructedWith(
            '85963',
            'ITALY',
            '0115',
            'VISA',
            'some custom field',
            '483054******1294',
            'TRCK0001',
            '123456789012345678',
            PaymentResultInfo::SUCCESSFUL_RESPONSE_CODE,
            PaymentResultInfo::TRANSACTION_APPROVED_CODE,
            '123456789012',
            '80957febda6a467c82d34da0e0673a6e',
            'S'
        );
        $this->isSuccessful()->shouldReturn(true);
        $this->isApproved()->shouldReturn(true);
        $this->isCapturedOnly()->shouldReturn(false);
    }

    public function it_should_be_not_successful()
    {
        $this->beConstructedWith(
            '85963',
            'ITALY',
            '0115',
            'VISA',
            'some custom field',
            '483054******1294',
            'TRCK0001',
            '123456789012345678',
            '020',
            PaymentResultInfo::TRANSACTION_NOT_APPROVED_CODE,
            '123456789012',
            '80957febda6a467c82d34da0e0673a6e',
            'S'
        );
        $this->isSuccessful()->shouldReturn(false);
        $this->isApproved()->shouldReturn(false);
        $this->isCapturedOnly()->shouldReturn(false);
    }

    public function it_should_be_captured()
    {
        $this->beConstructedWith(
            '85963',
            'ITALY',
            '0115',
            'VISA',
            'some custom field',
            '483054******1294',
            'TRCK0001',
            '123456789012345678',
            '000',
            PaymentResultInfo::TRANSACTION_CAPTURED_CODE,
            '123456789012',
            '80957febda6a467c82d34da0e0673a6e',
            'S'
        );
        $this->isCanceled()->shouldReturn(false);
        $this->isApproved()->shouldReturn(false);
        $this->isCapturedOnly()->shouldReturn(true);
    }

    public function it_should_be_canceled()
    {
        $this->beConstructedWith(
            '85963',
            'ITALY',
            '0115',
            'VISA',
            'some custom field',
            '483054******1294',
            'TRCK0001',
            '123456789012345678',
            '000',
            PaymentResultInfo::TRANSACTION_CANCELED_CODE,
            '123456789012',
            '80957febda6a467c82d34da0e0673a6e',
            'S'
        );
        $this->isCanceled()->shouldReturn(true);
        $this->isApproved()->shouldReturn(false);
        $this->isCapturedOnly()->shouldReturn(false);
    }
}
