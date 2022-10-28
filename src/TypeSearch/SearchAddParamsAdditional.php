<?php

namespace Shieldforce\GoogleSearch\TypeSearch;

class SearchAddParamsAdditional
{
    public static function run(
        string $termMain,
        array $termsAdditional,
        array $extra=[]
    )
    {
        $listSearch      = [];
        foreach ($termsAdditional as $index => $termAdditional) {
            $search = Search::run(
                "{$termMain} $index",
                $termsAdditional[$index]["page"] ?? null,
                $termsAdditional[$index]["perPage"] ?? null,
                $extra
            );
            $listSearch[$index]["page"]         = $search["page"];
            $listSearch[$index]["perPage"]      = $search["perPage"];
            $listSearch[$index]["start"]        = $search["start"];
            $listSearch[$index]["end"]          = $search["end"];
            $listSearch[$index]["totalResults"] = $search["totalResults"];
            foreach ($search["results"] as $result) {
                $listSearch[$index]["results"][] = $result;
            }
        }
        return $listSearch;
    }

}