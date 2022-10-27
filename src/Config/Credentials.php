<?php

declare(strict_types=1);

class Credentials
{
    private $url="https://www.googleapis.com.br/customsearch/v1";
    private $apiKey;
    private $id;

    public function __construct()
    {
        $this->url = getenv("GOOGLE_URL_SEARCH");
        $this->apiKey = getenv("GOOGLE_APIKEY_SEARCH");
        $this->id = getenv("GOOGLE_ID_SEARCH");
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getId(): string
    {
        return $this->id;
    }
}