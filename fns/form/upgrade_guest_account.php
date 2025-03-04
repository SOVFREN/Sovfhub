<?php

if (!empty(Registry::load('current_user')->site_role_attribute)) {
    if (Registry::load('current_user')->site_role_attribute === 'guest_users') {
        if (Registry::load('settings')->allow_guest_users_create_accounts === 'yes') {

            $user_id = Registry::load('current_user')->id;
            $columns = $where = $join = null;
            
            $columns = [
                'custom_fields.string_constant(field_name)', 'custom_fields.field_type',
                'custom_fields.editable_only_once', 'custom_fields.required', 'custom_fields_values.field_value'
            ];
            
            $join["[>]custom_fields_values"] = ["custom_fields.field_id" => "field_id", "AND" => ["user_id" => $user_id]];
            
            $where['AND'] = ['custom_fields.field_category' => 'profile', 'custom_fields.disabled' => 0, 'custom_fields.show_on_signup' => 1];
            $where["ORDER"] = ["custom_fields.field_id" => "ASC"];
            $custom_fields = DB::connect()->select('custom_fields', $join, $columns, $where);

            $form['loaded'] = new stdClass();
            $form['loaded']->title = Registry::load('strings')->create_account;
            $form['loaded']->button = Registry::load('strings')->create;

            $form['fields'] = new stdClass();
            $form['fields']->update = [
                "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "guest_account"
            ];

            $form['fields']->email_address = [
                "title" => Registry::load('strings')->email_address, "tag" => 'input', "type" => 'email', "class" => 'field',
                "placeholder" => Registry::load('strings')->email_address,
            ];

            $form['fields']->password = [
                "title" => Registry::load('strings')->password, "tag" => 'input', "type" => 'password', "class" => 'field',
                "placeholder" => Registry::load('strings')->password,
            ];

            $form['fields']->confirm_password = [
                "title" => Registry::load('strings')->confirm_password, "tag" => 'input', "type" => 'password', "class" => 'field',
                "placeholder" => Registry::load('strings')->confirm_password,
            ];

            foreach ($custom_fields as $custom_field) {
                $field_name = $custom_field['field_name'];
                $form['fields']->$field_name = [
                    "title" => Registry::load('strings')->$field_name, "class" => 'field',
                    "placeholder" => Registry::load('strings')->$field_name,
                ];

                if ($custom_field['field_type'] === 'short_text' || $custom_field['field_type'] === 'link') {
                    $form['fields']->$field_name['tag'] = 'input';
                    $form['fields']->$field_name['type'] = 'text';
                } else if ($custom_field['field_type'] === 'long_text') {
                    $form['fields']->$field_name['tag'] = 'textarea';
                    $form['fields']->$field_name["attributes"] = ["rows" => 6];
                } else if ($custom_field['field_type'] === 'date') {
                    $form['fields']->$field_name['tag'] = 'input';
                    $form['fields']->$field_name['type'] = 'date';
                } else if ($custom_field['field_type'] === 'number') {
                    $form['fields']->$field_name['tag'] = 'input';
                    $form['fields']->$field_name['type'] = 'number';
                } else if ($custom_field['field_type'] === 'dropdown') {

                    $dropdownoptions = $field_name.'_options';

                    if (isset(Registry::load('strings')->$dropdownoptions)) {
                        $field_options = json_decode(Registry::load('strings')->$dropdownoptions);
                    }

                    $form['fields']->$field_name['tag'] = 'select';
                    $form['fields']->$field_name['options'] = $field_options;

                }

                if (isset($custom_field['field_value']) && !empty($custom_field['field_value'])) {
                    $form['fields']->$field_name['value'] = $custom_field['field_value'];
                    if (!empty($custom_field['editable_only_once']) && !role(['permissions' => ['site_users' => 'edit_users']])) {
                        $form['fields']->$field_name['attributes']['disabled'] = 'disabled';
                    }
                }
            }
        }
    }
}
?>