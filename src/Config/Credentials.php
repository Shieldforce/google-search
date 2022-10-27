<?php

declare(strict_types=1);

namespace Shieldforce\GoogleSearch\Config;

class Credentials
{
    private static $instance;

    private static $apiKey = null;
    private static $id = null;
    private static $url = "https://www.googleapis.com.br/customsearch/v1";

    private function __construct(){}

    public static function setCredentials(
        string $apiKey,
        string $id,
        string $url=null
    )
    {
        self::$apiKey = $apiKey;
        self::$id = $id;
        self::$url = $url ?? self::$url;
    }

    private function __clone(){}
    public function __wakeup(){}

    public static function getInstance() : Credentials
    {
        if(!isset(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public static function getUrl(): string
    {
        return self::$url;
    }

    public static  function getApiKey(): string
    {
        return self::$apiKey;
    }

    public static  function getId(): string
    {
        return self::$id;
    }
}