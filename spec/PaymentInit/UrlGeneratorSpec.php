<?php

namespace spec\Webgriffe\LibMonetaWebDue\PaymentInit;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
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
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
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
            )->getParams()->shouldBe([
                'id' => '99999999',
                'password' => '99999999',
                'operationType' => 'initialize',
                'amount' => '1428.70',
                'responseToMerchantUrl' => 'http://www.merchant.it/notify.jsp',
                'recoveryUrl' => 'http://www.merchant.it/error.jsp',
                'merchantOrderId' => 'TRCK0001',
                'description' => 'Descrizione',
                'cardHolderName' => 'NomeCognome',
                'cardHolderEmail' => 'nome@dominio.com',
                'customField' => 'campoPersonalizzabile',
                'currencyCode' => '978',
                'language' => 'ITA'
            ]);
    }

    public function it_generates_correct_mybank_url()
    {
        $this
            ->generate(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
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
                'campoPersonalizzabile',
                'initializemybank'
            )->getParams()->shouldBe([
                'id' => '99999999',
                'password' => '99999999',
                'operationType' => 'initializemybank',
                'amount' => '1428.70',
                'responseToMerchantUrl' => 'http://www.merchant.it/notify.jsp',
                'recoveryUrl' => 'http://www.merchant.it/error.jsp',
                'merchantOrderId' => 'TRCK0001',
                'description' => 'Descrizione',
                'cardHolderName' => 'NomeCognome',
                'cardHolderEmail' => 'nome@dominio.com',
                'customField' => 'campoPersonalizzabile',
            ]);
    }

    public function it_throws_exception_when_currency_is_not_supported()
    {
        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
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
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
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
            )->getParams()->shouldBe([
                'id' => '99999999',
                'password' => '99999999',
                'operationType' => 'initialize',
                'amount' => '1428.70',
                'responseToMerchantUrl' => 'http://www.merchant.it/notify.jsp',
                'recoveryUrl' => 'http://www.merchant.it/error.jsp',
                'merchantOrderId' => 'TRCK0001',
                'description' => 'Descrizione',
                'cardHolderName' => 'NomeCognome',
                'cardHolderEmail' => 'nome@dominio.com',
                'customField' => 'campoPersonalizzabile',
                'currencyCode' => '978',
                'language' => 'USA'
            ]);
    }

    public function it_should_log_request_params_if_a_logger_is_given(LoggerInterface $logger)
    {
        $params = [
            'id' => '99999999',
            'password' => '99999999',
            'operationType' => 'initialize',
            'amount' => '1428.70',
            'responseToMerchantUrl' => 'http://www.merchant.it/notify.jsp',
            'recoveryUrl' => 'http://www.merchant.it/error.jsp',
            'merchantOrderId' => 'TRCK0001',
            'description' => 'Descrizione',
            'cardHolderName' => 'NomeCognome',
            'cardHolderEmail' => 'nome@dominio.com',
            'customField' => 'campoPersonalizzabile',
            'currencyCode' => '978',
            'language' => 'ITA'
        ];

        $logger->log(LogLevel::DEBUG, '[Lib MonetaWeb2]: Generating payment initialization url')->shouldBeCalled();
        $logger->log(LogLevel::DEBUG, '[Lib MonetaWeb2]: Request params: '.print_r($params, true))->shouldBeCalled();

        $this->beConstructedWith($logger);
        $this->generate(
            'https://www.monetaonline.it/monetaweb/payment/2/xml',
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
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
                '99999999',
                '99999999',
                1428.7,
                null,
                'ITA',
                'http://www.merchant.it/notify.jsp',
                null,
                'TRCK0001'
            )->getParams()->shouldBe([
                'id' => '99999999',
                'password' => '99999999',
                'operationType' => 'initialize',
                'amount' => '1428.70',
                'responseToMerchantUrl' => 'http://www.merchant.it/notify.jsp',
                'recoveryUrl' => null,
                'merchantOrderId' => 'TRCK0001',
                'description' => null,
                'cardHolderName' => null,
                'cardHolderEmail' => null,
                'customField' => null,
                'currencyCode' => null,
                'language' => 'ITA'
            ]);
    }

    public function it_should_throw_error_when_required_parameters_are_not_given()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
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
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
                '99999999',
                '99999999',
                0.0,
                null,
                'ITA',
                'http://www.merchant.it/notify.jsp',
                null,
                'TRCK0001'
            );
    }

    public function it_generates_correct_url_even_with_amount_is_close_to_zero()
    {
        $this
            ->generate(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
                '99999999',
                '99999999',
                0.01,
                null,
                'ITA',
                'http://www.merchant.it/notify.jsp',
                null,
                'TRCK0001'
            )->getParams()->shouldBe([
                'id' => '99999999',
                'password' => '99999999',
                'operationType' => 'initialize',
                'amount' => '0.01',
                'responseToMerchantUrl' => 'http://www.merchant.it/notify.jsp',
                'recoveryUrl' => null,
                'merchantOrderId' => 'TRCK0001',
                'description' => null,
                'cardHolderName' => null,
                'cardHolderEmail' => null,
                'customField' => null,
                'currencyCode' => null,
                'language' => 'ITA'
            ]);
    }

    public function it_should_throw_exception_when_terminal_id_is_too_long()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
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
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
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

    public function it_should_throw_exception_when_response_to_merchant_url_is_wrong()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
                '99999999',
                '99999999',
                1428.7,
                'EUR',
                'ITA',
                'httpwww.merchant.it/notify.jsp',
                'http://www.merchant.it/error.jsp',
                'TRCK0001',
                'Descrizione',
                'NomeCognome',
                'nome@dominio.com',
                'campoPersonalizzabile'
            );
    }

    public function it_should_throw_exception_when_rrecovery_url_is_wrong()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
                '99999999',
                '99999999',
                1428.7,
                'EUR',
                'ITA',
                'http://www.merchant.it/notify.jsp',
                'httpwww.merchant.it/error.jsp',
                'TRCK0001',
                'Descrizione',
                'NomeCognome',
                'nome@dominio.com',
                'campoPersonalizzabile'
            );
    }

    public function it_should_throw_exception_when_merchant_order_id_is_wrong()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
                '99999999',
                '99999999',
                1428.7,
                'EUR',
                'ITA',
                'http://www.merchant.it/notify.jsp',
                'http://www.merchant.it/error.jsp',
                '__123-- A',
                'Descrizione',
                'NomeCognome',
                'nome@dominio.com',
                'campoPersonalizzabile'
            );
    }

    public function it_should_throw_exception_when_operation_type_is_not_valid()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringGenerate(
                'https://www.monetaonline.it/monetaweb/payment/2/xml',
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
                'campoPersonalizzabile',
                'wrong'
            );
    }
}
