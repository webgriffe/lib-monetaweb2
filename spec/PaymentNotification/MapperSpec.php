<?php

namespace spec\Webgriffe\LibMonetaWebDue\PaymentNotification;

use GuzzleHttp\Psr7\ServerRequest;
use PhpSpec\ObjectBehavior;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Mapper;
use Webgriffe\LibMonetaWebDue\PaymentNotification\PaymentResultInfo;

class MapperSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Mapper::class);
    }

    public function it_should_create_payment_result_info()
    {
        $request = new ServerRequest(
            'POST',
            'any uri'
        );

        $parsedBody = [
            'authorizationcode' => '85963',
            'cardcountry' => 'ITALY',
            'cardexpirydate' => '0115',
            'cardtype' => 'VISA',
            'customfield' => 'some custom field',
            'maskedpan' => '483054******1294',
            'merchantorderid' => 'TRCK0001',
            'responsecode' => '000',
            'result' => 'APPROVED',
            'rrn' => '123456789012',
            'securitytoken' => '80957febda6a467c82d34da0e0673a6e',
            'threedsecure' => 'S'
        ];
        $request = $request->withParsedBody($parsedBody);

        $expectedPaymentResult = new PaymentResultInfo();
        $expectedPaymentResult->setAuthorizationCode('85963');
        $expectedPaymentResult->setCardCountry('ITALY');
        $expectedPaymentResult->setCardExpiryDate('0115');
        $expectedPaymentResult->setCardType('VISA');
        $expectedPaymentResult->setCustomField('some custom field');
        $expectedPaymentResult->setMaskedPan('483054******1294');
        $expectedPaymentResult->setMerchantOrderId('TRCK0001');
        $expectedPaymentResult->setResponseCode('000');
        $expectedPaymentResult->setResult('APPROVED');
        $expectedPaymentResult->setRetrievalReferenceNumber('123456789012');
        $expectedPaymentResult->setSecurityToken('80957febda6a467c82d34da0e0673a6e');
        $expectedPaymentResult->setThreeDSecure('S');

        $this->map($request)->shouldBeLike($expectedPaymentResult);
    }
}
