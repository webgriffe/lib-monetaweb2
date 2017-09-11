<?php

namespace spec\Webgriffe\LibMonetaWebDue\PaymentNotification\Result;

use PhpSpec\ObjectBehavior;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInfo;

class PaymentResultInfoSpec extends ObjectBehavior
{
    public function it_should_be_successful_and_authorized_only()
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
            PaymentResultInfo::TRANSACTION_AUTHORIZED_CODE,
            '123456789012',
            '80957febda6a467c82d34da0e0673a6e',
            'S'
        );
        $this->isSuccessful()->shouldReturn(true);
        $this->isAuthorizationOnly()->shouldReturn(true);
        $this->isAuthorizationCaptured()->shouldReturn(false);
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
            PaymentResultInfo::TRANSACTION_AUTHORIZED_CODE,
            '123456789012',
            '80957febda6a467c82d34da0e0673a6e',
            'S'
        );
        $this->isSuccessful()->shouldReturn(false);
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
        $this->isAuthorizationCaptured()->shouldReturn(true);
        $this->isAuthorizationOnly()->shouldReturn(false);
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
        $this->isAuthorizationOnly()->shouldReturn(false);
        $this->isAuthorizationCaptured()->shouldReturn(false);
    }
}
