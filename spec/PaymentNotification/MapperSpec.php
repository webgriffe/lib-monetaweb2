<?php

namespace spec\Webgriffe\LibMonetaWebDue\PaymentNotification;

use GuzzleHttp\Psr7\ServerRequest;
use PhpSpec\ObjectBehavior;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Mapper;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInfo;

class MapperSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Mapper::class);
    }

    public function it_should_create_payment_result_info()
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
}
