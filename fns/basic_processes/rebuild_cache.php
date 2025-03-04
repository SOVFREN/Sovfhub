<?php

cache(['rebuild' => 'css_variables']);
cache(['rebuild' => 'css']);
cache(['rebuild' => 'js']);
cache(['rebuild' => 'languages']);
cache(['rebuild' => 'settings']);
cache(['rebuild' => 'sitemap']);
cache(['rebuild' => 'manifest']);
cache(['rebuild' => 'site_roles']);
cache(['rebuild' => 'group_roles']);


if (file_exists('upgrade')) {
    $page_content = [
        'title' => 'Finalizing Upgrade',
        'loading_text' => 'Finalizing Upgrade',
        'subtitle' => 'Please Wait',
        'redirect' => Registry::load('config')->site_url.'upgrade/index.php?process=finalizing_upgrade'
    ];
} else {
    $page_content = [
        'title' => 'Successfully Completed',
        'page_content' => 'Process Successfully Completed',
        'heading' => 'Yay!',
        'page_status' => 'success',
        'button_text' => 'Go to Homepage',
        'button_link' => Registry::load('config')->site_url
    ];
}
?>