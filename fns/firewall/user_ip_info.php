<?php
function user_ip_info($ip_address) {
    $result = array();
    $result['countryCode'] = null;
    $result['timezone'] = null;

    if (isset($_SERVER['HTTP_CF_IPCOUNTRY']) && !empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
        $result['countryCode'] = $_SERVER['HTTP_CF_IPCOUNTRY'];
        $result['timezone'] = identify_country_timezone($_SERVER['HTTP_CF_IPCOUNTRY']);
        $result['platform'] = 'cloudflare';
    }

    if (empty($result['countryCode'])) {
        $curl_url = 'https://api.country.is/'.$ip_address;

        $response = user_ip_info_curl($curl_url);
        $response = json_decode($response);

        if (!empty($response) && isset($response->country) && !empty($response->country)) {
            $result['countryCode'] = $response->country;
            $result['timezone'] = identify_country_timezone($response->country);
            $result['platform'] = 'country.is';
        }
    }

    if (empty($result['countryCode']) || empty($result['timezone'])) {
        $curl_url = 'http://www.geoplugin.net/json.gp?ip='.$ip_address;
        $response = user_ip_info_curl($curl_url);
        $response = json_decode($response);

        if (!empty($response) && isset($response->geoplugin_countryCode) && !empty($response->geoplugin_countryCode)) {
            $result['countryCode'] = $response->geoplugin_countryCode;
            $result['timezone'] = $response->geoplugin_timezone;
            $result['platform'] = 'geoplugin.net';
        }
    }

    if (empty($result['countryCode'])) {
        $curl_url = 'https://get.geojs.io/v1/ip/geo/'.$ip_address.'.json';
        $response = user_ip_info_curl($curl_url);
        $response = json_decode($response);

        if (!empty($response) && isset($response->country_code) && !empty($response->country_code)) {
            $result['countryCode'] = $response->country_code;
            $result['timezone'] = $response->timezone;
            $result['platform'] = 'geojs.io';
        }
    }

    return $result;
}

function user_ip_info_curl($curl_url) {
    $response = null;
    if (!empty($curl_url)) {
        $curl_request = curl_init();
        $curl_timeout = 8;
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
        $response = curl_exec($curl_request);
        curl_close($curl_request);
    }
    return $response;
}

function identify_country_timezone($country) {
    $timezoneIdentifiers = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

    $timezones = array();
    $result = null;
    foreach ($timezoneIdentifiers as $timezoneIdentifier) {
        $timezone = new DateTimeZone($timezoneIdentifier);
        $countryCode = $timezone->getLocation()['country_code'];
        if (!isset($timezones[$countryCode])) {
            $timezones[$countryCode] = array();
        }
        $timezones[$countryCode][] = $timezoneIdentifier;
    }

    if (isset($timezones[$country])) {
        $timezone_list = $timezones[$country];
        if (count($timezone_list) === 1) {
            $result = $timezones[$country][0];
        }
    }
    return $result;
}
?>