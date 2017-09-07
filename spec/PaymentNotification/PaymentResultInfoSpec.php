<?php

namespace spec\Webgriffe\LibMonetaWebDue\PaymentNotification;

use PhpSpec\ObjectBehavior;
use Webgriffe\LibMonetaWebDue\PaymentNotification\PaymentResultInfo;

class PaymentResultInfoSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(PaymentResultInfo::class);
    }

    public function it_should_be_successful()
    {
        $this->setResponseCode(PaymentResultInfo::SUCCESSFUL_RESPONSE_CODE);
        $this->isSuccessful()->shouldReturn(true);
    }

    public function it_should_be_not_successful()
    {
        $this->setResponseCode('020');
        $this->isSuccessful()->shouldReturn(false);
    }

    public function it_should_be_authorized_only()
    {
        $this->setResult('APPROVED');
        $this->isAuthorizationOnly()->shouldReturn(true);
        $this->isAuthorizationCaptured()->shouldReturn(false);
    }

    public function it_should_be_authorized_and_captured()
    {
        $this->setResult('CAPTURED');
        $this->isAuthorizationCaptured()->shouldReturn(true);
        $this->isAuthorizationOnly()->shouldReturn(false);
    }

    public function it_should_be_canceled()
    {
        $this->setResult('CANCELED');
        $this->isCanceled()->shouldReturn(true);
        $this->isAuthorizationOnly()->shouldReturn(false);
        $this->isAuthorizationCaptured()->shouldReturn(false);
    }
}
