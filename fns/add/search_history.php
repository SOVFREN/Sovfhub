<?php

if (role(['permissions' => ['site_users' => 'advanced_user_searches']])) {

    include 'fns/filters/load.php';

    $current_user_id = Registry::load('current_user')->id;
    $columns = $where = null;
    $columns = [
        'custom_fields.string_constant(field_name)', 'custom_fields.field_type', 'custom_fields.field_id'
    ];
    $where['AND'] = [
        'custom_fields.field_category' => 'profile',
        'custom_fields.disabled' => 0,
        'custom_fields.searchable_field' => 1
    ];
    $where["ORDER"] = ["custom_fields.field_id" => "ASC"];
    $custom_fields = DB::connect()->select('custom_fields', $columns, $where);
    $search_fields = array();


    $check_fields = ['full_name', 'username', 'email_address'];

    foreach ($check_fields as $index => $field_name) {
        if (isset($data[$field_name]) && !empty($data[$field_name])) {
            $search_fields[$field_name]['value'] = htmlspecialchars(trim($data[$field_name]), ENT_QUOTES, 'UTF-8');
            $search_fields[$field_name]['search_entire_string'] = false;
        }
    }



    foreach ($custom_fields as $custom_field) {

        $field_name = $custom_field['field_name'];

        if (isset($data[$field_name]) && !empty($data[$field_name])) {
            if ($custom_field['field_type'] === 'date') {
                if (validate_date($data[$field_name], 'Y-m-d')) {
                    $search_fields[$field_name]['value'] = $data[$field_name];
                    $search_fields[$field_name]['search_entire_string'] = true;
                    $search_fields[$field_name]['field_id'] = $custom_field['field_id'];
                }
            } else if ($custom_field['field_type'] === 'number') {
                $data[$field_name] = filter_var($data[$field_name], FILTER_SANITIZE_NUMBER_INT);

                if (!empty($data[$field_name])) {
                    $search_fields[$field_name]['value'] = $data[$field_name];
                    $search_fields[$field_name]['search_entire_string'] = false;
                    $search_fields[$field_name]['field_id'] = $custom_field['field_id'];
                }

            } elseif ($custom_field['field_type'] === 'link') {
                $data[$field_name] = filter_var($data[$field_name], FILTER_SANITIZE_URL);
                if (!empty($data[$field_name]) && filter_var($data[$field_name], FILTER_VALIDATE_URL)) {
                    $search_fields[$field_name]['value'] = $data[$field_name];
                    $search_fields[$field_name]['search_entire_string'] = false;
                    $search_fields[$field_name]['field_id'] = $custom_field['field_id'];
                }
            } else if ($custom_field['field_type'] === 'dropdown') {

                $dropdownoptions = $field_name.'_options';
                if (isset(Registry::load('strings')->$dropdownoptions)) {
                    $field_options = json_decode(Registry::load('strings')->$dropdownoptions);
                    $find_index = $data[$field_name];
                    if (isset($field_options->$find_index)) {
                        $search_fields[$field_name]['value'] = $data[$field_name];
                        $search_fields[$field_name]['search_entire_string'] = true;
                        $search_fields[$field_name]['field_id'] = $custom_field['field_id'];
                    }
                }

            } else {
                $search_fields[$field_name]['value'] = htmlspecialchars(trim($data[$field_name]), ENT_QUOTES, 'UTF-8');
                $search_fields[$field_name]['search_entire_string'] = false;
                $search_fields[$field_name]['field_id'] = $custom_field['field_id'];
            }
        }
    }

    $search_history_dir = 'assets/cache/search_history/';

    $files = glob($search_history_dir . '*');

    foreach ($files as $file) {
        $diff = (time() - filemtime($file)) / 60;

        if ($diff >= 15) {
            unlink($file);
        }
    }

    foreach (glob($search_history_dir.'search_'.$current_user_id.'_'."*.*") as $old_searches) {
        unlink($old_searches);
    }

    if (!empty($search_fields)) {
        $search_id = strtotime("now");
        $search_history = $search_history_dir.'search_'.$current_user_id.'_'.$search_id.'.search';

        if (file_exists($search_history)) {
            $search_id = (int)$search_id+rand(500, 1500);
            $search_history = $search_history_dir.'search_'.$current_user_id.'_'.$search_id.'.search';
        }

        $search_fields = json_encode($search_fields);

        $search_history = fopen($search_history, "w");
        fwrite($search_history, $search_fields);
        fclose($search_history);

        $result = array();
        $result['success'] = true;
        $result['todo'] = 'load_aside';
        $result['attributes'] = [
            'data-search_id' => $search_id,
            'load' => 'search_history',
            'hide_search_bar' => true
        ];

    } else {
        $result = array();
        $result['success'] = false;
        $result['error_message'] = Registry::load('strings')->advanced_search_criteria;
        $result['error_key'] = 'invalid_value';
        $result['error_variables'] = [];
    }

}
?>