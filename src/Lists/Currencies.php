<?php

namespace Webgriffe\LibMonetaWebDue\Lists;

class Currencies implements ValuesList
{
    public function getList()
    {
        return [
            'CHF' => '756',
            'EUR' => '978',
            'GBP' => '826',
            'USD' => '840',
        ];
    }
}
