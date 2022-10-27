<?php

declare(strict_types=1);

namespace Shieldforce\GoogleSearch\Config;

class Credentials
{
    private $url;
    private $apiKey;
    private $id;

    public function __construct(string $apiKey, string $id, string $url="https://www.googleapis.com.br/customsearch/v1")
    {
        $this->url = $url;
        $this->apiKey = $apiKey;
        $this->id = $id;
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