<?php

$curl_url = 'https://api.redgifs.com/v1/oembed?url='.$url;
$curl_request = curl_init();
curl_setopt($curl_request, CURLOPT_HEADER, 0);
curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_request, CURLOPT_ENCODING, "");
curl_setopt($curl_request, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:99.0) Gecko/20100101 Firefox/99.0');
curl_setopt($curl_request, CURLOPT_URL, $curl_url);
curl_setopt($curl_request, CURLOPT_AUTOREFERER, true);
curl_setopt($curl_request, CURLOPT_CONNECTTIMEOUT, 0);
curl_setopt($curl_request, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl_request, CURLOPT_TIMEOUT, $curl_timeout);
$oembed_request = curl_exec($curl_request);

if (!empty($oembed_request)) {
    $oembed_request = json_decode($oembed_request);
    if (!empty($oembed_request)) {
        if (isset($oembed_request->title) && isset($oembed_request->html)) {
            $result['title'] = $oembed_request->title;
            $result['image'] = $oembed_request->thumbnail_url;

            if (isset($oembed_request->description)) {
                $result['description'] = $oembed_request->description;
            } elseif (isset($oembed_request->author_name)) {
                $result['description'] = $oembed_request->author_name;
            }

            $html_embed_url = $oembed_request->html;

            preg_match("/src='([^']+)'/", $html_embed_url, $html_embed_url_match);

            if (isset($html_embed_url_match[1])) {
                $html_embed_url_match = $html_embed_url_match[1];

                $result['iframe_embed'] = $html_embed_url_match;
                $result['iframe_class'] = 'w-auto h-75';
            }
        }
    }
}