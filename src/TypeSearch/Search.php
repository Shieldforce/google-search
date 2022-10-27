<?php

namespace Shieldforce\GoogleSearch\TypeSearch;

class Search
{

    protected $search_engine_id;
    protected $api_key;
    public function __construct($search_engine_id, $api_key)
    {
        $this->search_engine_id = $search_engine_id;
        $this->api_key = $api_key;
    }

    private function request($params)
    {
        $params = array_merge(
            $params,
            [
                'key' => $this->api_key,
                'cx' => $this->search_engine_id
            ]
        );

        // disable peer verification
        $context = stream_context_create([
            'http' => [
                'ignore_errors' => true
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);

        // use cURL if avaible, otherwise fallback to file_get_contents
        if (function_exists('curl_version')) {
            $response = $this->getSslPage('https://www.googleapis.com/customsearch/v1?' . http_build_query($params));
        } else {
            $response = file_get_contents('https://www.googleapis.com/customsearch/v1?' . http_build_query($params), false, $context);
        }

        return json_decode($response);
    }

    public function search($terms, $page=1, $per_page=10, $extra=[])
    {

        $per_page = ($per_page > 10) ? 10 : $per_page;

        $params = [
            'q' => ''.$terms.'',
            'start' => (($page - 1) * $per_page) + 1,
            'num' => $per_page
        ];
        if (sizeof($extra)) {
            $params = array_merge($params, $extra);
        }

        $response = $this->request($params);

        if (isset($response->error)) {
            throw new \Exception($response->error->message);
        }

        $request_info = $response->queries->request[0];

        $results = new \stdClass();
        $results->page = $page;
        $results->perPage = $per_page;
        $results->start = $request_info->startIndex;
        $results->end = ($request_info->startIndex + $request_info->count) - 1;
        $results->totalResults = $request_info->totalResults;
        $results->results = [];

        if (isset($response->items)) {
            foreach ($response->items as $result) {
                $results->results[] = (object) [
                    'title' => $result->title,
                    'snippet' => $result->snippet,
                    'htmlSnippet' => $result->htmlSnippet,
                    'link' => $result->link,
                    'image' => isset($result->pagemap->cse_image) ? $result->pagemap->cse_image[0]->src : '',
                    'thumbnail' => isset($result->pagemap->cse_thumbnail) ? $result->pagemap->cse_thumbnail[0]->src : '',
                ];
            }
        }

        return $results;
    }

    public function getSslPage($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}