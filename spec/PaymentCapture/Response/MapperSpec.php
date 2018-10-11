<?php
/**
 * Created by PhpStorm.
 * User: kraken
 * Date: 11/10/18
 * Time: 14.44
 */

namespace spec\Webgriffe\LibMonetaWebDue\PaymentCapture\Response;

use PhpSpec\ObjectBehavior;
use Psr\Http\Message\StreamInterface;
use Webgriffe\LibMonetaWebDue\PaymentCapture\Response\Mapper;
use \Psr\Http\Message\ResponseInterface;
use Webgriffe\LibMonetaWebDue\PaymentCapture\Response\SuccessResponse;

class MapperSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Mapper::class);
    }

    public function it_should_throw_exception_if_gateway_returns_malformed_xml(
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $stream->getContents()->shouldBeCalled()->willReturn('skjdfhsdfhgkdfh');
        $response->getBody()->shouldBeCalled()->willReturn($stream);

        $this->shouldThrow(\RuntimeException::class)->duringMap($response);
    }

    public function it_should_throw_exception_if_gateway_returns_error_xml(
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $errorXml = <<<XML
<?xml version='1.0' ?>
<error>
    <errorcode>XYZ123</errorcode>
    <errormessage>Invalid amount</errormessage>
</error>
XML;

        $stream->getContents()->shouldBeCalled()->willReturn($errorXml);
        $response->getBody()->shouldBeCalled()->willReturn($stream);

        $this->shouldThrow(\RuntimeException::class)->duringMap($response);
    }

    public function it_should_return_succesful_response_if_everything_was_fine(
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $errorXml = <<<XML
<?xml version='1.0' ?>
<response>
    <result>CAPTURED</result>
    <authorizationcode>fakews</authorizationcode>
    <paymentid>841826491344182719</paymentid>
    <merchantorderid>000000156C</merchantorderid>
    <responsecode>000</responsecode>
    <customfield></customfield>
    <description></description>
</response>
XML;

        $stream->getContents()->shouldBeCalled()->willReturn($errorXml);
        $response->getBody()->shouldBeCalled()->willReturn($stream);

        $this->map($response)->shouldBeLike(
            new SuccessResponse(
                '841826491344182719',
                'CAPTURED',
                '000',
                'fakews',
                '000000156C',
                '',
                ''
            )
        );
    }
}
