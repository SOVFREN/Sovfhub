<?php

ini_set('max_execution_time', 0);
$not_found = true;

if (isset($data["process"])) {
    $data["process"] = preg_replace("/[^a-zA-Z0-9_]+/", "", $data["process"]);
} 

if (!isset($data["process"]) || isset($data["process"]) && empty($data["process"])) {
    $data["process"] = 'allocate_images';
}

if (isset($data["process"]) && !empty($data["process"])) {

    $data["process"] = str_replace('sp_', '', $data["process"]);

    $loadfnfile = 'fns/basic_processes/'.$data["process"].'.php';
    if (file_exists($loadfnfile)) {
        $not_found = false;
        include($loadfnfile);

        $body_class = 'loading_page';

        if (isset($page_content['page_status'])) {
            $body_class = $page_content['page_status'];
        }

        include('fns/basic_processes/page_content.php');
    }
}

if ($not_found) {
    redirect('404');
}
?>