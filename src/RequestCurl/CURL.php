<?php

namespace Shieldforce\GoogleSearch\RequestCurl;

class CURL
{
    public static function execute (
        string $urlServer,
        string $requestType,
        array $paramsHeader,
        array $paramsPost,
        string $typeHeader="array",
        string $typeBody="array"
    )
    {
        $header = $paramsHeader;
        if($typeHeader == "json") {
            $header = json_encode($paramsHeader);
        }
        $body = $paramsPost;
        if($typeBody == "json") {
            $body = json_encode($paramsPost);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL               => "{$urlServer}",
            CURLOPT_RETURNTRANSFER    => true,
            CURLOPT_ENCODING          => '',
            CURLOPT_MAXREDIRS         => 10,
            CURLOPT_TIMEOUT           => 0,
            CURLOPT_FOLLOWLOCATION    => true,
            CURLOPT_HTTP_VERSION      => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST     => $requestType,
            CURLOPT_POSTFIELDS        => $body,
            CURLOPT_HTTPHEADER        => $header,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
}
