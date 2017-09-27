<?php

namespace Webgriffe\LibMonetaWebDue\Lists;

class Languages implements ValuesList
{
    const ITA_LANGUAGE_CODE = 'ITA';
    const USA_LANGUAGE_CODE = 'USA';
    const FRA_LANGUAGE_CODE = 'FRA';
    const DEU_LANGUAGE_CODE = 'DEU';
    const SPA_LANGUAGE_CODE = 'SPA';
    const POR_LANGUAGE_CODE = 'POR';
    const RUS_LANGUAGE_CODE = 'RUS';

    public function getList()
    {
        return [
            self::ITA_LANGUAGE_CODE,
            self::USA_LANGUAGE_CODE,
            self::FRA_LANGUAGE_CODE,
            self::DEU_LANGUAGE_CODE,
            self::SPA_LANGUAGE_CODE,
            self::POR_LANGUAGE_CODE,
            self::RUS_LANGUAGE_CODE,
        ];
    }
}
