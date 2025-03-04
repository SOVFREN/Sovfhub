<?php

include 'fns/curl/load.php';
use Curl\Curl;

if (Registry::load('settings')->video_search_engine !== 'disable') {
    if (role(['permissions' => ['private_conversations' => 'search_share_videos', 'groups' => 'search_share_videos'], 'condition' => 'OR'])) {

        $limit = Registry::load('settings')->videos_per_load;
        $content_filter = '';


        $searchQuery = '';

        $video_search_engine_api = Registry::load('settings')->video_search_engine_api;


        if (!empty($data["search"])) {
            $searchQuery = '&q='.urlencode(htmlspecialchars($data["search"]));
        }

        $endpoint = "https://www.googleapis.com/youtube/v3/search?part=snippet".$searchQuery;
        $endpoint .= "&type=video&maxResults=$limit&key=$video_search_engine_api";

        if (!empty($data["offset"])) {
            $data["offset"] = htmlspecialchars($data["offset"]);
            $endpoint .= "&pageToken=".$data["offset"];
        }


        $curl = new Curl();
        $curl->setOpt(CURLOPT_TIMEOUT, 15);
        $curl->setOpt(CURLOPT_ENCODING, '');
        $curl->get($endpoint);

        if (!$curl->error) {
            $video_results = $curl->response;

            if (!empty($video_results)) {

                if (isset($video_results->nextPageToken)) {
                    $output['offset'] = $video_results->nextPageToken;
                } else {
                    $output['offset'] = 'endofresults';
                }

                $i = 0;

                $video_results = $video_results->items;

                foreach ($video_results as $video_result) {
                    $video_url = 'https://www.youtube.com/watch?v='.$video_result->id->videoId;
                    $thumbnail = $video_result->snippet->thumbnails->medium->url;
                    $output['content'][$i] = new stdClass();
                    $output['content'][$i]->image = $thumbnail;
                    $output['content'][$i]->attributes = ['media_url' => $video_url, 'media_type' => 'video_url'];
                    $output['content'][$i]->class = "attach_media";

                    $i = $i+1;
                }

            }
        }
    }
}