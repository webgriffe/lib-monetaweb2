<?php

namespace spec\Webgriffe\LibMonetaWebDue\PaymentNotification;

use GuzzleHttp\Psr7\ServerRequest;
use PhpSpec\ObjectBehavior;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Mapper;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInfo;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\CCPaymentResultInterface;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\MyBankPaymentResultInfo;

class MapperSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Mapper::class);
    }

    public function it_should_create_payment_result_info_with_approved_transaction()
    {
        $request = new ServerRequest('POST', 'any uri');
        $parsedBody = [
            'authorizationcode' => '85963',
            'cardcountry' => 'ITALY',
            'cardexpirydate' => '0115',
            'cardtype' => 'VISA',
            'customfield' => 'some custom field',
            'maskedpan' => '483054******1294',
            'merchantorderid' => 'TRCK0001',
            'paymentid' => '123456789012345678',
            'responsecode' => '000',
            'result' => 'APPROVED',
            'rrn' => '123456789012',
            'securitytoken' => '80957febda6a467c82d34da0e0673a6e',
            'threedsecure' => 'S'
        ];
        $request = $request->withParsedBody($parsedBody);

        $expectedPaymentResult = new PaymentResultInfo(
            '85963',
            'ITALY',
            '0115',
            'VISA',
            'some custom field',
            '483054******1294',
            'TRCK0001',
            '123456789012345678',
            '000',
            'APPROVED',
            '123456789012',
            '80957febda6a467c82d34da0e0673a6e',
            'S'
        );
        $this->map($request)->shouldBeLike($expectedPaymentResult);
    }

    public function it_should_create_payment_result_info_with_canceled_transaction()
    {
        $request = new ServerRequest('POST', 'any uri');
        $parsedBody = [
            'paymentid' => '123456789012345678',
            'result' => CCPaymentResultInterface::TRANSACTION_CANCELED_CODE,
            'threedsecure' => 'S'
        ];
        $request = $request->withParsedBody($parsedBody);

        $expectedPaymentResult = new PaymentResultInfo(
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            '123456789012345678',
            null,
            CCPaymentResultInterface::TRANSACTION_CANCELED_CODE,
            null,
            null,
            'S'
        );
        $this->map($request)->shouldBeLike($expectedPaymentResult);
    }

    public function it_should_throw_exception_when_required_fields_are_missing()
    {
        $request = new ServerRequest('POST', 'any uri');
        $this->shouldThrow(\InvalidArgumentException::class)->duringMap($request->withParsedBody([]));
        $this->shouldThrow(\InvalidArgumentException::class)->duringMap($request->withParsedBody(['paymentid' => '1']));
        $this->shouldThrow(\InvalidArgumentException::class)->duringMap(
            $request->withParsedBody(['paymentid' => '1', 'result' => '000'])
        );
        $this->shouldThrow(\InvalidArgumentException::class)->duringMap(
            $request->withParsedBody(['threedsecure' => '1', 'result' => '000'])
        );
    }

    public function it_should_create_mybank_payment_result_info_with_approved_mybank_transaction()
    {
        $request = new ServerRequest('POST', 'any uri');
        $parsedBody = [
            'paymentid' => '123456789012345678',
            'result' => 'AUTHORISED',
            'description' => 'desc',
            'authorizationcode' => '85963',
            'merchantorderid' => 'TRCK0001',
            'mybankid' => '9876543210',
            'customfield' => 'some custom field',
            'securitytoken' => '80957febda6a467c82d34da0e0673a6e',
        ];
        $request = $request->withParsedBody($parsedBody);

        $expectedPaymentResult = new MyBankPaymentResultInfo(
            '123456789012345678',
            'AUTHORISED',
            'desc',
            '85963',
            'TRCK0001',
            '9876543210',
            'some custom field',
            '80957febda6a467c82d34da0e0673a6e'
        );
        $this->map($request, 'initializemybank')->shouldBeLike($expectedPaymentResult);
    }
}
