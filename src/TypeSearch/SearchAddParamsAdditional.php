<?php

namespace Shieldforce\GoogleSearch\TypeSearch;

use Shieldforce\GoogleSearch\Config\Credentials;

class SearchAddParamsAdditional
{
    public static function run(string $termMain, array $termsAdditional = [])
    {
        $listSearch = [];
        foreach ($termsAdditional as $termAdditional) {
            $listSearch = Search::run("{$termMain} $termAdditional");
        }
        return self::sanitizeRemoveDuplicate($listSearch);
    }

    private static function sanitizeRemoveDuplicate($listSearch)
    {
        return $listSearch;
    }
}