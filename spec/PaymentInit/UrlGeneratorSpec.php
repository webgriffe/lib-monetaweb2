<?php

namespace spec\Webgriffe\LibMonetaWebDue\PaymentInit;

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
        // TODO: To Be Implemented
    }

    public function it_uses_english_language_when_the_given_language_is_not_supported()
    {
        // TODO: To Be Implemented
    }
}
