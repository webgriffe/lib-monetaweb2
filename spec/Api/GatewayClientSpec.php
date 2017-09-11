<?php

namespace spec\Webgriffe\LibMonetaWebDue\Api;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Webgriffe\LibMonetaWebDue\Api\GatewayClient;
use Webgriffe\LibMonetaWebDue\Api\GatewayPageInfo;

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
}
