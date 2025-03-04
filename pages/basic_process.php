<?php

session_write_close();
ignore_user_abort(false);

include 'fns/firewall/load.php';
include_once 'fns/sql/load.php';
include_once 'fns/SleekDB/Store.php';
include 'fns/variables/load.php';

$data = get_data('request');
$force_request = false;

if (file_exists('upgrade')) {
    $force_request = true;
}

if (Registry::load('current_user')->logged_in || $force_request) {
    if ($force_request || role(['permissions' => ['super_privileges' => 'core_settings']])) {
        include 'fns/basic_processes/load.php';
    } else {
        redirect('404');
    }

} else {
    $entry_page = 'entry/';

    if (!empty(Registry::load('config')->url_path)) {
        $entry_page .= '?redirect='.urlencode(Registry::load('config')->url_path);
    }

    redirect($entry_page);
}

?>