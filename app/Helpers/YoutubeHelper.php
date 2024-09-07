<?php

namespace App\Helpers;

class YoutubeHelper
{
    public static function embed($url)
    {
        // Extract the video ID from the URL
        $videoId = self::getVideoId($url);

        // Construct the embeddable YouTube link
        return "https://www.youtube.com/embed/$videoId";
    }

    private static function getVideoId($url)
    {
        // Parse the URL and extract the query parameters
        $queryParts = parse_url($url, PHP_URL_QUERY);
        parse_str($queryParts, $params);

        // Check if the 'v' parameter exists
        if (isset($params['v'])) {
            return $params['v'];
        }

        // If 'v' parameter doesn't exist, attempt to extract the video ID from the URL path
        preg_match('/(?:\/|v=)([a-zA-Z0-9_-]{11}).*/', $url, $matches);
        return $matches[1] ?? '';
    }
}
