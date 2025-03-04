<?php

if (role(['permissions' => ['super_privileges' => 'core_settings']])) {

    $form = array();

    $form['loaded'] = new stdClass();
    $form['loaded']->title = 'License Info';
    $form['loaded']->button = Registry::load('strings')->update;

    $form['fields'] = new stdClass();

    $form['fields']->update = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "license_info"
    ];

    $license_info = array();
    $license_info_file = 'assets/cache/license_record.cache';

    if (file_exists($license_info_file)) {
        $license_info = file_get_contents($license_info_file);
        if (!empty($license_info)) {
            $license_info = json_decode($license_info);
        }
    }


    if (isset(Registry::load('config')->app_version)) {
        $form['fields']->script_version = [
            "title" => 'Grupo Version', "tag" => 'input', "type" => 'text', "class" => 'field',
            "attributes" => ['disabled' => 'disabled'], 'value' => Registry::load('config')->app_version
        ];
    }

    $form['fields']->purchase_code = [
        "title" => 'Purchase Code', "tag" => 'input', "type" => 'text', "class" => 'field',
        "placeholder" => "Enter to Verify or Reconfirm"
    ];

    if (!empty($license_info)) {
        $form['fields']->sold_at = [
            "title" => 'Purchase Date', "tag" => 'input', "type" => 'text', "class" => 'field',
            "attributes" => ['disabled' => 'disabled'], 'value' => $license_info->sold_at
        ];

        $form['fields']->license = [
            "title" => 'License Name', "tag" => 'input', "type" => 'text', "class" => 'field',
            "attributes" => ['disabled' => 'disabled'], 'value' => $license_info->license
        ];
        $form['fields']->supported_until = [
            "title" => 'Item Support End Date', "tag" => 'input', "type" => 'text', "class" => 'field',
            "attributes" => ['disabled' => 'disabled'], 'value' => $license_info->supported_until
        ];
        $form['fields']->buyer = [
            "title" => 'Codecanyon Username', "tag" => 'input', "type" => 'text', "class" => 'field',
            "attributes" => ['disabled' => 'disabled'], 'value' => $license_info->buyer
        ];
    }

}