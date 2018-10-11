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
use Webgriffe\LibMonetaWebDue\LogicRequestDataContainer;
use Webgriffe\LibMonetaWebDue\PaymentInit\UrlGeneratorInterface;
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

    public function it_should_make_a_request(ClientInterface $client, UrlGeneratorInterface $urlGenerator)
    {
        $urlGenerator->generate(
            'https://www.monetaonline.it/monetaweb/payment/2/xml',
            '99999999',
            '99999999',
            1428.7,
            null,
            'ITA',
            'http://www.merchant.it/notify.jsp',
            null,
            'TRCK0001',
            null,
            null,
            null,
            null,
            UrlGeneratorInterface::OPERATION_TYPE_INITIALIZE
        )->shouldBeCalled()->willReturn(
            new LogicRequestDataContainer(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
                'POST',
                array()
            )
        );

        $expectedResponseBody = <<<XML
<?xml version='1.0' ?>
<response>
    <paymentid>123456789012345678</paymentid>
    <securitytoken>80957febda6a467c82d34da0e0673a6e</securitytoken>
    <hostedpageurl>https://www.monetaonline.it/monetaweb</hostedpageurl>
</response>
XML;
        $expectedResponse = new Response(200, [], $expectedResponseBody);

        $client->request(
            'POST',
            'https://www.monetaonline.it/monetaweb/payment/2/xml',
            Argument::type('array')
        )
            ->shouldBeCalled()
            ->willReturn($expectedResponse);

        $this->beConstructedWith($client, null, $urlGenerator);
        $this->getPaymentPageInfo(
            'https://www.monetaonline.it/monetaweb/payment/2/xml',
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

    public function it_should_throw_exception_when_the_gateway_response_is_an_error(
        ClientInterface $client,
        UrlGeneratorInterface $urlGenerator
    ) {
        $urlGenerator->generate(
            'https://www.monetaonline.it/monetaweb/payment/2/xml',
            '99999999',
            '99999999',
            1428.7,
            null,
            'ITA',
            'http://www.merchant.it/notify.jsp',
            null,
            'TRCK0001',
            null,
            null,
            null,
            null,
            UrlGeneratorInterface::OPERATION_TYPE_INITIALIZE
        )->shouldBeCalled()->willReturn(
            new LogicRequestDataContainer(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
                'POST',
                array()
            )
        );

        $expectedResponseBody = <<<XML
<?xml version='1.0' ?>
<error>
<errorcode>XYZ123</errorcode>
<errormessage>Invalid amount</errormessage>
</error>
XML;
        $expectedResponse = new Response(200, [], $expectedResponseBody);

        $client->request(
            'POST',
            'https://www.monetaonline.it/monetaweb/payment/2/xml',
            Argument::type('array')
        )->shouldBeCalled()->willReturn($expectedResponse);
        $this->beConstructedWith($client, null, $urlGenerator);
        $this->shouldThrow(\RuntimeException::class)
            ->duringGetPaymentPageInfo(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
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

    public function it_should_throw_exception_when_the_gateway_response_is_not_a_valid_xml(
        ClientInterface $client,
        UrlGeneratorInterface $urlGenerator
    ) {
        $urlGenerator->generate(
            'https://www.monetaonline.it/monetaweb/payment/2/xml',
            '99999999',
            '99999999',
            1428.7,
            null,
            'ITA',
            'http://www.merchant.it/notify.jsp',
            null,
            'TRCK0001',
            null,
            null,
            null,
            null,
            UrlGeneratorInterface::OPERATION_TYPE_INITIALIZE
        )->shouldBeCalled()->willReturn(
            new LogicRequestDataContainer(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
                'POST',
                array()
            )
        );

        $client->request(
            'POST',
            'https://www.monetaonline.it/monetaweb/payment/2/xml',
            Argument::type('array')
        )->shouldBeCalled()->willReturn(new Response(200, [], 'sudifghsihgis'));

        $this->beConstructedWith($client, null, $urlGenerator);
        $this->shouldThrow(\RuntimeException::class)
            ->duringGetPaymentPageInfo(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
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
