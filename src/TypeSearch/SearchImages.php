<?php

namespace App\GoogleAPIs;

use Shieldforce\GoogleSearch\RequestCurl\CURL;
use Shieldforce\GoogleSearch\Config\Credentials;

class SearchImages
{
    public static function execute($search)
    {
        $newSearch = urlencode($search);
        $credentials = Credentials::getInstance();
        return  CURL::execute(
            "{$credentials->getUrl()}/?key={$credentials->getApiKey()}&cx={$credentials->getId()}&q={$newSearch}&num=10&searchType=image",
            "GET",
            [],
            [],
            "array",
            "array"
        );
    }
}
