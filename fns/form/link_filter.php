<?php

if (role(['permissions' => ['super_privileges' => 'link_filter']])) {

    $form = array();
    $form['loaded'] = new stdClass();
    $form['loaded']->title = Registry::load('strings')->link_filter;
    $form['loaded']->button = Registry::load('strings')->update;

    $url_blacklist = array();
    $url_blacklist_file = 'assets/cache/url_blacklist.cache';

    if (file_exists($url_blacklist_file)) {
        include($url_blacklist_file);

        if (!empty($url_blacklist)) {
            $url_blacklist = implode(PHP_EOL, $url_blacklist);
        }
    }

    $form['fields'] = new stdClass();

    $form['fields']->update = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "link_filter"
    ];


    $form['fields']->status = [
        "title" => Registry::load('strings')->status, "tag" => 'select', "class" => 'field',
        "value" => Registry::load('settings')->link_filter
    ];
    $form['fields']->status['options'] = [
        "enable" => Registry::load('strings')->enable,
        "disable" => Registry::load('strings')->disable,
    ];


    $form['fields']->url_blacklist = [
        "title" => Registry::load('strings')->blacklist, "tag" => 'textarea', "class" => 'field url_blacklist',
        "value" => $url_blacklist,
    ];

    $form['fields']->url_blacklist["attributes"] = ["rows" => 17];
    $form['fields']->url_blacklist['infotip'] = Registry::load('strings')->link_filter_tip;


}

?>