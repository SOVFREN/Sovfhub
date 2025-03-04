<?php

if (role(['permissions' => ['site_users' => 'advanced_user_searches']])) {
    $form = array();
    $form['loaded'] = new stdClass();
    $form['loaded']->title = Registry::load('strings')->search_users;
    $form['loaded']->button = Registry::load('strings')->search;

    $columns = $where = null;
    $columns = [
        'custom_fields.string_constant(field_name)', 'custom_fields.field_type'
    ];
    $where['AND'] = [
        'custom_fields.field_category' => 'profile',
        'custom_fields.disabled' => 0,
        'custom_fields.searchable_field' => 1
    ];
    $where["ORDER"] = ["custom_fields.field_id" => "ASC"];
    $custom_fields = DB::connect()->select('custom_fields', $columns, $where);

    $form['fields'] = new stdClass();

    $form['fields']->add = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "search_history"
    ];

    $form['fields']->full_name = [
        "title" => Registry::load('strings')->full_name, "tag" => 'input', "type" => "text",
        "class" => 'field', "placeholder" => Registry::load('strings')->full_name
    ];

    $form['fields']->username = [
        "title" => Registry::load('strings')->username, "tag" => 'input', "type" => 'text',
        "class" => 'field', "placeholder" => Registry::load('strings')->username,
    ];

    $form['fields']->email_address = [
        "title" => Registry::load('strings')->email_address, "tag" => 'input', "type" => 'email', "class" => 'field',
        "placeholder" => Registry::load('strings')->email_address,
    ];

    foreach ($custom_fields as $custom_field) {
        $field_name = $custom_field['field_name'];

        if ($custom_field['field_type'] === 'short_text' || $custom_field['field_type'] === 'link' || $custom_field['field_type'] === 'long_text') {

            $form['fields']->$field_name = [
                "title" => Registry::load('strings')->$field_name, "tag" => 'input', "type" => 'text', "class" => 'field',
                "placeholder" => Registry::load('strings')->$field_name,
            ];

        } else if ($custom_field['field_type'] === 'date') {

            $form['fields']->$field_name = [
                "title" => Registry::load('strings')->$field_name, "tag" => 'input', "type" => 'date', "class" => 'field',
                "placeholder" => Registry::load('strings')->$field_name,
            ];

        } else if ($custom_field['field_type'] === 'number') {

            $form['fields']->$field_name = [
                "title" => Registry::load('strings')->$field_name, "tag" => 'input', "type" => 'number', "class" => 'field',
                "placeholder" => Registry::load('strings')->$field_name,
            ];

        } else if ($custom_field['field_type'] === 'dropdown') {

            $field_options = array();
            $dropdownoptions = $field_name.'_options';

            if (isset(Registry::load('strings')->$dropdownoptions)) {
                $field_options = json_decode(Registry::load('strings')->$dropdownoptions);
            }

            $form['fields']->$field_name = [
                "title" => Registry::load('strings')->$field_name, "tag" => 'select', "class" => 'field',
                "options" => $field_options,
            ];

        }
    }

}
?>