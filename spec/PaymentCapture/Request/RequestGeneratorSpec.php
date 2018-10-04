<?php
/**
 * Created by PhpStorm.
 * User: kraken
 * Date: 28/09/18
 * Time: 17.37
 */

namespace spec\Webgriffe\LibMonetaWebDue\PaymentCapture\Request;

use PhpSpec\ObjectBehavior;
use Webgriffe\LibMonetaWebDue\LogicRequestDataContainerInterface;
use Webgriffe\LibMonetaWebDue\PaymentCapture\Request\RequestGenerator;

class RequestGeneratorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(RequestGenerator::class);
    }

    public function it_should_generate_request_data_with_valid_params()
    {
        $this->generate(
            'https://url.com/',
            '99999999',
            '99999999',
            59.00,
            'EUR',
            '000000156',
            '841826491344182719'
        )
            ->shouldHaveType(LogicRequestDataContainerInterface::class);
    }

    public function it_should_generate_request_data_with_the_same_url_that_was_provided()
    {
        $this->generate(
            'https://url.com/',
            '99999999',
            '99999999',
            59.00,
            'EUR',
            '000000156',
            '841826491344182719'
        )
            ->getUrl()->shouldBeEqualTo('https://url.com/');
    }

    public function it_should_generate_request_data_with_post_method()
    {
        $this->generate(
            'https://url.com/',
            '99999999',
            '99999999',
            59.00,
            'EUR',
            '000000156',
            '841826491344182719'
        )
            ->getMethod()->shouldBeEqualTo('POST');
    }

    public function it_should_generate_request_data_with_correct_parameters()
    {
        $this->generate(
            'https://url.com/',
            '99999999',
            '99999999',
            59.00,
            'EUR',
            '000000156',
            '841826491344182719'
        )
            ->getParams()->shouldBeEqualTo(
                [
                    'id' => '99999999',
                    'password' => '99999999',
                    'operationType' => 'confirm',
                    'amount' => '59.00',
                    'currencyCode' => '978',
                    'merchantOrderId' => '000000156C',
                    'paymentId' => '841826491344182719',
                    'customField' => null,
                    'description' => null,
                ]
            );
    }

    public function it_should_throw_exception_if_url_is_missing()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                '',
                '99999999',
                '99999999',
                59.00,
                'EUR',
                '000000156',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_terminal_id_is_missing()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '',
                '99999999',
                59.00,
                'EUR',
                '000000156',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_terminal_password_is_missing()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '',
                59.00,
                'EUR',
                '000000156',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_amount_is_null()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '99999999',
                null,
                'EUR',
                '000000156',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_amount_is_zero()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '99999999',
                0,
                'EUR',
                '000000156',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_terminal_id_is_too_long()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '1234567890',
                '99999999',
                59.00,
                'EUR',
                '000000156',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_password_is_too_long()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '123456789012345678901234567890123456789012345678901',
                59.00,
                'EUR',
                '000000156',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_amount_is_integer()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '99999999',
                59,
                'EUR',
                '000000156',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_amount_is_string()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '99999999',
                '59.00',
                'EUR',
                '000000156',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_currency_code_is_too_short()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '99999999',
                59.00,
                'EU',
                '000000156',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_currency_code_is_too_long()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '99999999',
                59.00,
                'EURO',
                '000000156',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_order_id_contains_not_alnum_char()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '99999999',
                59.00,
                'EUR',
                '000000156!',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_order_id_contains_whitespace()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '99999999',
                59.00,
                'EUR',
                '000000 156',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_order_id_is_empty()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '99999999',
                59.00,
                'EUR',
                '',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_order_id_is_too_long()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '99999999',
                59.00,
                'EUR',
                '12345678901234567890',
                '841826491344182719'
            );
    }

    public function it_should_throw_exception_if_custom_field_is_too_long()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '99999999',
                59.00,
                'EUR',
                '000000156',
                '841826491344182719',
                str_repeat('1234567890', 26)
            );
    }

    public function it_should_throw_exception_if_description_is_too_long()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://url.com/',
                '99999999',
                '99999999',
                59.00,
                'EUR',
                '000000156',
                '841826491344182719',
                '',
                str_repeat('1234567890', 26)
            );
    }
}
