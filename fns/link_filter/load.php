<?php
function link_filter($data) {
    $result = true;

    if (Registry::load('settings')->link_filter === 'enable') {

        $url_blacklist = array();
        $url_blacklist_file = 'assets/cache/url_blacklist.cache';
        $links = $data['links'];

        if (file_exists($url_blacklist_file)) {
            include($url_blacklist_file);

            if (!empty($url_blacklist)) {
                foreach ($links as $urlToCheck) {
                    foreach ($url_blacklist as $blacklistedUrl) {
                        if (strpos($blacklistedUrl, '*') !== false) {
                            $blacklistDomain = rtrim($blacklistedUrl, '*');
                            $blacklistDomain = parse_url($blacklistDomain, PHP_URL_HOST);
                            $urlDomain = parse_url($urlToCheck, PHP_URL_HOST);

                            if ($urlDomain === $blacklistDomain) {
                                $result = false;
                                break 2;
                            }
                        } else {
                            if ($urlToCheck === trim($blacklistedUrl)) {
                                $result = false;
                                break 2;
                            }
                        }
                    }
                }
            }
        }

    }


    return $result;
}
?>