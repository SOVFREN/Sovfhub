<?php

if (role(['permissions' => ['site_users' => 'advanced_user_searches']])) {

    $output = array();
    $output['loaded'] = new stdClass();
    $output['loaded']->title = Registry::load('strings')->users;
    $output['loaded']->loaded = 'search_history';
    $output['loaded']->offset = array();

    $current_user_id = Registry::load('current_user')->id;
    $search_id = 0;

    if (isset($data['search_id']) && !empty($data['search_id'])) {

        $search_id = filter_var($data['search_id'], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($search_id)) {

            $search_history_dir = 'assets/cache/search_history/';
            $search_history = $search_history_dir.'search_'.$current_user_id.'_'.$search_id.'.search';

            if (file_exists($search_history)) {
                $i = 1;

                $search_fields = file_get_contents($search_history);
                $search_fields = json_decode($search_fields);

                if (!empty($search_fields)) {

                    $columns = $where = $join = null;
                    $join_custom_fields = false;
                    $columns = [
                        'site_users.user_id', 'site_users.display_name', 'site_users.username', 'site_users.site_role_id',
                        'site_users.profile_picture'
                    ];

                    if (!empty($data["offset"])) {
                        $data["offset"] = array_map('intval', explode(',', $data["offset"]));
                        $where["site_users.user_id[!]#offset"] = $data["offset"];

                        if (!empty($data["offset"])) {
                            $output['loaded']->offset = $data["offset"];
                        }
                    }

                    foreach ($search_fields as $field_name => $search_field) {
                        $search_entire_string = false;

                        if (isset($search_field->search_entire_string) && (bool)$search_field->search_entire_string) {
                            $search_entire_string = true;
                        }

                        if ($field_name === 'full_name') {
                            $where["site_users.display_name[~]"] = $search_field->value;
                        } else if ($field_name === 'username') {
                            $where["site_users.username[~]"] = $search_field->value;
                        } else if ($field_name === 'email_address') {
                            $where["site_users.email_address[~]"] = $search_field->value;
                        } else if (isset($search_field->field_id)) {

                            $custom_field_name = 'custom_field_'.$search_field->field_id;

                            if ($search_entire_string) {
                                $join["[>]custom_fields_values(".$custom_field_name.")"] = ["site_users.user_id" => "user_id"];
                                $where["AND #".$custom_field_name] = [
                                    $custom_field_name.".field_value" => $search_field->value,
                                    $custom_field_name.".field_id" => $search_field->field_id
                                ];
                            } else {
                                $join["[>]custom_fields_values(".$custom_field_name.")"] = ["site_users.user_id" => "user_id"];
                                $where["AND #".$custom_field_name] = [
                                    $custom_field_name.".field_value[~]" => $search_field->value,
                                    $custom_field_name.".field_id" => $search_field->field_id
                                ];
                            }

                            $join_custom_fields = true;
                        }
                    }

                    $where["LIMIT"] = Registry::load('settings')->records_per_call;


                    if ($join_custom_fields) {
                        $site_users = DB::connect()->select('site_users', $join, $columns, $where);
                    } else {
                        $site_users = DB::connect()->select('site_users', $columns, $where);
                    }

                    foreach ($site_users as $site_user) {
                        $output['loaded']->offset[] = $site_user['user_id'];
                        $rolename = 'site_role_'.$site_user['site_role_id'];

                        $output['content'][$i] = new stdClass();
                        $output['content'][$i]->identifier = $site_user['user_id'];
                        $output['content'][$i]->title = $site_user['display_name'];
                        $output['content'][$i]->attributes = ['user_id' => $site_user['user_id']];
                        $output['content'][$i]->attributes['stopPropagation'] = true;
                        $output['content'][$i]->class = "site_users get_info";
                        $output['content'][$i]->icon = 0;
                        $output['content'][$i]->unread = 0;
                        $output['content'][$i]->subtitle = Registry::load('strings')->$rolename;
                        $output['content'][$i]->image = get_img_url(['from' => 'site_users/profile_pics', 'image' => $site_user['profile_picture']]);
                        $i++;
                    }
                }
            }
        }
    }

}
?>