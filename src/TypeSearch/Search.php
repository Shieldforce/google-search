<?php

namespace Shieldforce\GoogleSearch\TypeSearch;

use Shieldforce\GoogleSearch\Config\Credentials;

class Search
{
    private static function request($params) : array
    {

        $params = self::addParams($params);

        if (!function_exists('curl_version')) {
            return json_decode(self::getNotSslPage(
                'https://www.googleapis.com/customsearch/v1?' . http_build_query($params)
            ));
        }

        return json_decode(self::getSslPage(
            'https://www.googleapis.com/customsearch/v1?' . http_build_query($params)
        ));
    }

    public static function run(string $terms, int $page=1, int $per_page=10, array $extra=[]) : \Stdclass
    {

        $params = self::addTextSearchParams($terms);

        $params = self::addPaginateParams($page, $per_page, $params);

        $params = self::addExtraParams($extra, $params);

        $response = self::request($params);

        if (isset($response->error)) {
            throw new \Exception($response->error->message);
        }

        $request_info = $response->queries->request[0] ?? null;

        $results               = new \stdClass();
        $results->page         = $page;
        $results->perPage      = $per_page;
        $results->start        = $request_info->startIndex;
        $results->end          = ($request_info->startIndex + $request_info->count) - 1;
        $results->totalResults = $request_info->totalResults;
        $results->results      = [];

        if (isset($response->items)) {
            foreach ($response->items as $result) {
                $results->results[] = (object) [
                    'title'       => $result->title,
                    'snippet'     => $result->snippet,
                    'htmlSnippet' => $result->htmlSnippet,
                    'link'        => $result->link,
                    'image'       => isset($result->pagemap->cse_image) ? $result->pagemap->cse_image[0]->src : '',
                    'thumbnail'   => isset($result->pagemap->cse_thumbnail) ? $result->pagemap->cse_thumbnail[0]->src : '',
                ];
            }
        }

        return $results;
    }

    private static function getSslPage($url)
    {
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

    private static function getNotSslPage($url)
    {
        $context = stream_context_create([
            'http' => [ 'ignore_errors' => true ],
            'ssl'  => [ 'verify_peer' => false, 'verify_peer_name' => false, ]
        ]);

        return file_get_contents(
            $url,
            false,
            $context
        );
    }

    private static function addParams($params)
    {
        $credentials = Credentials::getInstance();
        return array_merge($params, [
            'key' => $credentials->getApiKey(),
            'cx'  => $credentials->getId()
        ]);
    }

    private static function addPaginateParams($page, $per_page, $params=[])
    {
        $per_page = ($per_page > 10) ? 10 : $per_page;
        return array_merge($params, [
            'start'  => (($page - 1) * $per_page) + 1,
            'num'    => $per_page
        ]);
    }

    private static function addTextSearchParams($textSearch, $params=[])
    {
        return array_merge($params, [
            'q'  => $textSearch,
        ]);
    }

    private static function addExtraParams($extra, $params=[])
    {
        return array_merge($params, $extra);
    }
}