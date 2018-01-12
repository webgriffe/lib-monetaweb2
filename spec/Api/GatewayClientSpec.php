<?php

namespace spec\Webgriffe\LibMonetaWebDue\Api;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Webgriffe\LibMonetaWebDue\Api\GatewayClient;
use Webgriffe\LibMonetaWebDue\Api\GatewayPageInfo;
use GuzzleHttp\Psr7\ServerRequest;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\CCPaymentResultInterface;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\MyBankPaymentResultInfo;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\MybankPaymentResultInterface;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\NonErrorPaymentResultInterface;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultErrorInfo;
use Webgriffe\LibMonetaWebDue\PaymentNotification\Result\PaymentResultInfo;

class GatewayClientSpec extends ObjectBehavior
{
    public function it_is_initializable(ClientInterface $client)
    {
        $this->beConstructedWith($client);
        $this->shouldHaveType(GatewayClient::class);
    }

    public function it_should_make_a_request(ClientInterface $client)
    {
        $expectedResponseBody = <<<XML
<?xml version='1.0' ?>
<response>
    <paymentid>123456789012345678</paymentid>
    <securitytoken>80957febda6a467c82d34da0e0673a6e</securitytoken>
    <hostedpageurl>https://www.monetaonline.it/monetaweb</hostedpageurl>
</response>
XML;
        $expectedResponse = new Response(200, [], $expectedResponseBody);

        $client->send(Argument::type(RequestInterface::class))->shouldBeCalled()->willReturn($expectedResponse);
        $this->beConstructedWith($client);
        $this->getPaymentPageInfo(
            'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet',
            '99999999',
            '99999999',
            1428.7,
            null,
            'ITA',
            'http://www.merchant.it/notify.jsp',
            null,
            'TRCK0001'
        )
            ->shouldBeLike(
                new GatewayPageInfo(
                    'https://www.monetaonline.it/monetaweb?paymentid=123456789012345678',
                    '80957febda6a467c82d34da0e0673a6e',
                    '123456789012345678'
                )
            );
    }

    public function it_should_throw_error_when_parameters_are_wrong(ClientInterface $client)
    {
        $client->send(Argument::type(RequestInterface::class))->shouldNotBeCalled();
        $this->beConstructedWith($client);
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGetPaymentPageInfo(
                null,
                null,
                null,
                null,
                null,
                'ITA',
                'http://www.merchant.it/notify.jsp',
                null,
                'TRCK0001'
            );
    }

    public function it_should_throw_exception_when_the_gateway_response_is_an_error(ClientInterface $client)
    {
        $expectedResponseBody = <<<XML
<?xml version='1.0' ?>
<error>
<errorcode>XYZ123</errorcode>
<errormessage>Invalid amount</errormessage>
</error>
XML;
        $expectedResponse = new Response(200, [], $expectedResponseBody);

        $client->send(Argument::type(RequestInterface::class))->shouldBeCalled()->willReturn($expectedResponse);
        $this->beConstructedWith($client);
        $this->shouldThrow(\RuntimeException::class)
            ->duringGetPaymentPageInfo(
                'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet',
                '99999999',
                '99999999',
                1428.7,
                null,
                'ITA',
                'http://www.merchant.it/notify.jsp',
                null,
                'TRCK0001'
            );
    }

    public function it_should_create_positive_payment_result_on_positive_notify(
        ClientInterface $client,
        ServerRequestInterface $request
    ) {
        $requestParams = array(
            'authorizationcode' => '54664641411',
            'cardcountry' => 'IT',
            'cardexpirydate' => '0220',
            'cardtype' => 'visa',
            'customfield' => 'custom',
            'maskedpan' => '483054******1294',
            'merchantorderid' => 'merchant id',
            'paymentid' => '1234567890',
            'responsecode' => CCPaymentResultInterface::SUCCESSFUL_RESPONSE_CODE,
            'result' => CCPaymentResultInterface::TRANSACTION_APPROVED_CODE,
            'rrn' => '123456789012',
            'securitytoken' => '80957febda6a467c82d34da0e0673a6e',
            'threedsecure' => 'S',
        );

        $request->getParsedBody()->shouldBeCalled()->willReturn($requestParams);

        $this->beConstructedWith($client);
        $this->handleNotify($request)
            ->shouldBeLike(new PaymentResultInfo(
                '54664641411',
                'IT',
                '0220',
                'visa',
                'custom',
                '483054******1294',
                'merchant id',
                '1234567890',
                CCPaymentResultInterface::SUCCESSFUL_RESPONSE_CODE,
                CCPaymentResultInterface::TRANSACTION_APPROVED_CODE,
                '123456789012',
                '80957febda6a467c82d34da0e0673a6e',
                'S'
            ));
    }

    public function it_should_create_positive_mybank_payment_result_on_positive_mybank_notify(
        ClientInterface $client,
        ServerRequestInterface $request
    ) {
        $requestParams = array(
            'paymentid' => '1234567890',
            'result' => MybankPaymentResultInterface::TRANSACTION_AUTHORISED_CODE,
            'description' => 'desc',
            'authorizationcode' => '54664641411',
            'merchantorderid' => 'merchant id',
            'mybankid' => 'mybank id',
            'customfield' => 'custom',
            'securitytoken' => '80957febda6a467c82d34da0e0673a6e',
        );

        $request->getParsedBody()->shouldBeCalled()->willReturn($requestParams);

        $this->beConstructedWith($client);
        $this->handleNotify($request)
            ->shouldBeLike(new MyBankPaymentResultInfo(
                '1234567890',
                MybankPaymentResultInterface::TRANSACTION_AUTHORISED_CODE,
                'desc',
                '54664641411',
                'merchant id',
                'mybank id',
                'custom',
                '80957febda6a467c82d34da0e0673a6e'
            ));
    }

    public function it_should_create_error_payment_result_on_error_notify(
        ClientInterface $client,
        ServerRequestInterface $request)
    {
        $requestParams = array(
            'paymentid' => '1234567890',
            'errorcode' => 'code',
            'errormessage' => 'message',
        );

        $request->getParsedBody()->shouldBeCalled()->willReturn($requestParams);

        $this->beConstructedWith($client);
        $this->handleNotify($request)
            ->shouldBeLike(new PaymentResultErrorInfo(
                'code',
                'message',
                '1234567890'
            ));
    }

    public function it_should_verify_matching_security_token(
        ClientInterface $client,
        NonErrorPaymentResultInterface $result
    ) {
        $result->getSecurityToken()->shouldBeCalled()->willReturn('stored');

        $this->beConstructedWith($client);
        $this->verifySecurityToken('stored', $result)->shouldReturn(true);
    }

    public function it_should_not_verify_wrong_security_token(
        ClientInterface $client,
        NonErrorPaymentResultInterface $result
    ) {
        $result->getSecurityToken()->shouldBeCalled()->willReturn('other');

        $this->beConstructedWith($client);
        $this->verifySecurityToken('stored', $result)->shouldReturn(false);
    }
}
