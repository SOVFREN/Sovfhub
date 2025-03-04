<?php

$noerror = true;
$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->something_went_wrong;
$result['error_key'] = 'something_went_wrong';

if (role(['permissions' => ['super_privileges' => 'link_filter']])) {

    $result['error_message'] = Registry::load('strings')->invalid_value;
    $result['error_key'] = 'invalid_value';
    $result['error_variables'] = [];

    $url_blacklist = '';

    $status = ["enable", "disable"];

    if (!isset($data['status']) || empty($data['status'])) {
        $result['error_variables'][] = ['status'];
        $noerror = false;
    } else if (!in_array($data['status'], $status)) {
        $result['error_variables'][] = ['status'];
        $noerror = false;
    }

    if ($noerror) {

        if (isset($data['url_blacklist'])) {

            $url_blacklist = "<?php \n";
            $url_blacklist .= 'array_push($url_blacklist,';

            if (is_array($data['url_blacklist'])) {
                $blacklists = $data['url_blacklist'];
            } else {
                $blacklists = preg_split("/\r\n|\n|\r/", $data['url_blacklist']);
            }


            $blacklists = array_unique($blacklists);

            $total_domains = count($blacklists);
            $domain_index = 1;

            foreach ($blacklists as $blacklist) {

                $blacklist = strip_tags($blacklist);

                if (!empty(trim($blacklist))) {
                    $url_blacklist .= "\n".'"'.addslashes($blacklist).'"';
                    if ($total_domains !== $domain_index) {
                        $url_blacklist .= ',';
                    }
                }
                $domain_index = $domain_index+1;
            }

            $url_blacklist .= "\n);";

            $build = fopen("assets/cache/url_blacklist.cache", "w");
            fwrite($build, $url_blacklist);
            fclose($build);
        }

        if ($data['status'] !== Registry::load('settings')->link_filter) {
            $time_stamp = Registry::load('current_user')->time_stamp;
            DB::connect()->update("settings", ["value" => $data['status'], "updated_on" => $time_stamp], ["setting" => 'link_filter']);
            cache(['rebuild' => 'settings']);
        }

        $result = array();
        $result['success'] = true;
        $result['todo'] = 'refresh';
    }
}

?>