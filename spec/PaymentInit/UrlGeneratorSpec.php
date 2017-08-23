<?php

namespace spec\Webgriffe\LibMonetaWebDue\PaymentInit;

use Psr\Log\LoggerInterface;
use Webgriffe\LibMonetaWebDue\PaymentInit\UrlGenerator;
use PhpSpec\ObjectBehavior;

class UrlGeneratorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(UrlGenerator::class);
    }

    public function it_generates_correct_url()
    {
        $this
            ->generate(
                'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet',
                '99999999',
                '99999999',
                1428.7,
                'EUR',
                'ITA',
                'http://www.merchant.it/notify.jsp',
                'http://www.merchant.it/error.jsp',
                'TRCK0001',
                'Descrizione',
                'NomeCognome',
                'nome@dominio.com',
                'campoPersonalizzabile'
            )
            ->shouldReturn(
                'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet' .
                '?id=99999999' .
                '&password=99999999' .
                '&operationType=initialize' .
                '&amount=1428.70' .
                '&currencyCode=978' .
                '&language=ITA' .
                '&responseToMerchantUrl=http%3A%2F%2Fwww.merchant.it%2Fnotify.jsp' .
                '&recoveryUrl=http%3A%2F%2Fwww.merchant.it%2Ferror.jsp' .
                '&merchantOrderId=TRCK0001' .
                '&description=Descrizione' .
                '&cardHolderName=NomeCognome' .
                '&cardHolderEmail=nome%40dominio.com' .
                '&customField=campoPersonalizzabile'
            );
    }

    public function it_throws_exception_when_currency_is_not_supported()
    {
        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet',
                '99999999',
                '99999999',
                1428.7,
                'XXX',
                'ITA',
                'http://www.merchant.it/notify.jsp',
                'http://www.merchant.it/error.jsp',
                'TRCK0001',
                'Descrizione',
                'NomeCognome',
                'nome@dominio.com',
                'campoPersonalizzabile'
            );
    }

    public function it_uses_english_language_when_the_given_language_is_not_supported()
    {
        $this
            ->generate(
                'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet',
                '99999999',
                '99999999',
                1428.7,
                'EUR',
                'XXX',
                'http://www.merchant.it/notify.jsp',
                'http://www.merchant.it/error.jsp',
                'TRCK0001',
                'Descrizione',
                'NomeCognome',
                'nome@dominio.com',
                'campoPersonalizzabile'
            )
            ->shouldReturn(
                'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet' .
                '?id=99999999' .
                '&password=99999999' .
                '&operationType=initialize' .
                '&amount=1428.70' .
                '&currencyCode=978' .
                '&language=USA' .
                '&responseToMerchantUrl=http%3A%2F%2Fwww.merchant.it%2Fnotify.jsp' .
                '&recoveryUrl=http%3A%2F%2Fwww.merchant.it%2Ferror.jsp' .
                '&merchantOrderId=TRCK0001' .
                '&description=Descrizione' .
                '&cardHolderName=NomeCognome' .
                '&cardHolderEmail=nome%40dominio.com' .
                '&customField=campoPersonalizzabile'
            );
    }

    public function it_should_log_generated_url_if_a_logger_is_given(LoggerInterface $logger)
    {
        $generatedUrl = 'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet' .
            '?id=99999999' .
            '&password=99999999' .
            '&operationType=initialize' .
            '&amount=1428.70' .
            '&currencyCode=978' .
            '&language=ITA' .
            '&responseToMerchantUrl=http%3A%2F%2Fwww.merchant.it%2Fnotify.jsp' .
            '&recoveryUrl=http%3A%2F%2Fwww.merchant.it%2Ferror.jsp' .
            '&merchantOrderId=TRCK0001' .
            '&description=Descrizione' .
            '&cardHolderName=NomeCognome' .
            '&cardHolderEmail=nome%40dominio.com' .
            '&customField=campoPersonalizzabile';

        $this->beConstructedWith($logger);
        $logger->debug('Generated URL is: '.$generatedUrl)->shouldBeCalled();
        $this->generate(
            'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet',
            '99999999',
            '99999999',
            1428.7,
            'EUR',
            'ITA',
            'http://www.merchant.it/notify.jsp',
            'http://www.merchant.it/error.jsp',
            'TRCK0001',
            'Descrizione',
            'NomeCognome',
            'nome@dominio.com',
            'campoPersonalizzabile'
        );
    }

    public function it_generates_correct_url_when_only_required_params_are_given()
    {
        $this
            ->generate(
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
            ->shouldReturn(
                'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet' .
                '?id=99999999' .
                '&password=99999999' .
                '&operationType=initialize' .
                '&amount=1428.70' .
                '&language=ITA' .
                '&responseToMerchantUrl=http%3A%2F%2Fwww.merchant.it%2Fnotify.jsp' .
                '&merchantOrderId=TRCK0001'
            );
    }

    public function it_should_throw_error_when_required_parameters_are_not_given()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet',
                '99999999',
                '99999999',
                null,
                null,
                'ITA',
                'http://www.merchant.it/notify.jsp',
                null,
                'TRCK0001'
            );
    }

    public function it_should_throw_error_when_amount_is_zero()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet',
                '99999999',
                '99999999',
                0,
                null,
                'ITA',
                'http://www.merchant.it/notify.jsp',
                null,
                'TRCK0001'
            );
    }

    public function it_should_throw_exception_when_terminal_id_is_too_long()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet',
                '99999999111',
                '99999999',
                1428.7,
                'EUR',
                'ITA',
                'http://www.merchant.it/notify.jsp',
                'http://www.merchant.it/error.jsp',
                'TRCK0001',
                'Descrizione',
                'NomeCognome',
                'nome@dominio.com',
                'campoPersonalizzabile'
            );
    }

    public function it_should_throw_exception_when_amount_format_is_wrong()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet',
                '99999999',
                '99999999',
                'a',
                'EUR',
                'ITA',
                'http://www.merchant.it/notify.jsp',
                'http://www.merchant.it/error.jsp',
                'TRCK0001',
                'Descrizione',
                'NomeCognome',
                'nome@dominio.com',
                'campoPersonalizzabile'
            );
    }
}
