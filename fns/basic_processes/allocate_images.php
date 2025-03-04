<?php
$sub_process = null;
$redirect = null;

$page_content = [
    'title' => 'Allocating Images',
    'loading_text' => 'Allocating User Images',
    'subtitle' => 'Please Wait',
    'redirect' => Registry::load('config')->site_url.'basic_process?process=allocate_images&sub_process=allocate_user_images'
];

if (isset($data["sub_process"]) && !empty($data["sub_process"])) {
    $sub_process = $data["sub_process"];
    $sub_process = preg_replace("/[^a-zA-Z0-9_]+/", "", $sub_process);
}

if (!empty($sub_process)) {
    $sub_process = 'sp_'.$sub_process;
    $loadfnfile = 'fns/basic_processes/'.$sub_process.'.php';
    if (file_exists($loadfnfile)) {
        include($loadfnfile);
    } else {
        $page_content = [
            'title' => 'Error',
            'page_content' => 'Error, Something Went Wrong',
            'heading' => 'Oops',
            'page_status' => 'error',
            'button_text' => 'Reload Page',
            'button_link' => Registry::load('config')->site_url.'basic_process?process=allocate_images&sub_process=allocate_user_images'
        ];
    }

}

?>