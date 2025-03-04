<?php

if (role(['permissions' => ['memberships' => ['view_personal_transactions', 'view_site_transactions']], 'condition' => 'OR'])) {

    $form = array();
    $form['loaded'] = new stdClass();
    $form['fields'] = new stdClass();

    if (isset($load["order_id"])) {

        $load["order_id"] = filter_var($load["order_id"], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($load['order_id'])) {

            $columns = [
                'membership_orders.order_id', 'membership_orders.user_id',
                'membership_orders.membership_package_id',
                'membership_orders.order_status',
                'membership_orders.payment_gateway_id',
                'membership_orders.transaction_info',
                'membership_orders.created_on', 'site_users.display_name',
            ];

            $join["[>]site_users"] = ["membership_orders.user_id" => "user_id"];

            $where["membership_orders.order_id"] = $load["order_id"];

            if (!role(['permissions' => ['memberships' => 'view_site_transactions']])) {
                $where["membership_orders.user_id"] = Registry::load('current_user')->id;
            }

            $where["LIMIT"] = 1;

            $order = DB::connect()->select('membership_orders', $join, $columns, $where);

            if (isset($order[0])) {

                $order = $order[0];
                $transaction_info = array();

                if (!empty($order['transaction_info'])) {
                    $transaction_info = json_decode($order['transaction_info']);
                }

                if (empty($transaction_info)) {
                    $transaction_info = new stdClass();
                }

                $form['loaded']->title = Registry::load('strings')->view_order;

                if (role(['permissions' => ['memberships' => 'edit_site_transactions']])) {
                    $form['loaded']->title = Registry::load('strings')->edit_order;
                    $form['loaded']->button = Registry::load('strings')->update;
                }

                $package_name = 'membership_package_'.$order['membership_package_id'];

                if (!isset(Registry::load('strings')->$package_name)) {
                    $package_name = 'unknown';
                }

                $form['fields']->order_id = [
                    "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => $load["order_id"]
                ];

                $form['fields']->update = [
                    "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "transactions"
                ];

                $form['fields']->order_identifier = [
                    "title" => Registry::load('strings')->order_id, "tag" => 'input', "type" => "text", "class" => 'field',
                    "value" => $order['order_id'], "attributes" => ['disabled' => 'disabled']
                ];


                $form['fields']->placed_by = [
                    "title" => Registry::load('strings')->placed_by, "tag" => 'input', "type" => "text", "class" => 'field',
                    "value" => $order['display_name'], "attributes" => ['disabled' => 'disabled']
                ];

                $form['fields']->membership_package_id = [
                    "title" => Registry::load('strings')->membership_package_id, "tag" => 'input', "type" => "text", "class" => 'field',
                    "value" => $order['membership_package_id'], "attributes" => ['disabled' => 'disabled']
                ];

                $form['fields']->package_name = [
                    "title" => Registry::load('strings')->package_name, "tag" => 'input', "type" => "text", "class" => 'field',
                    "value" => Registry::load('strings')->$package_name, "attributes" => ['disabled' => 'disabled']
                ];


                if (role(['permissions' => ['memberships' => 'edit_site_transactions']])) {

                    $form['fields']->take_action = [
                        "title" => Registry::load('strings')->take_action, "tag" => 'select', "class" => 'field toggle_form_fields',
                        "attributes" => ["hide_field" => "site_role_field", "show_fields" => "disapprove_unenroll|site_role_field"]
                    ];
                    $form['fields']->take_action['options'] = [
                        "approve" => Registry::load('strings')->approve,
                        "approve_enroll" => Registry::load('strings')->approve_enroll,
                        "disapprove" => Registry::load('strings')->disapprove,
                        "disapprove_unenroll" => Registry::load('strings')->disapprove_unenroll,
                    ];

                    $columns = $join = $where = null;
                    $language_id = Registry::load('current_user')->language;
                    $join = ["[>]language_strings(string)" => [
                        "site_roles.string_constant" => "string_constant",
                        "AND" => ["language_id" => $language_id]]
                    ];
                    $columns = ['site_roles.site_role_id', 'string.string_value(name)'];
                    $where = ['site_roles.site_role_id[!]' => Registry::load('site_role_attributes')->banned_users];
                    $site_roles = DB::connect()->select('site_roles', $join, $columns, $where);
                    $site_roles = array_column($site_roles, 'name', 'site_role_id');

                    $form['fields']->site_role = [
                        "title" => Registry::load('strings')->reassign_site_role, "tag" => 'select', "class" => 'field site_role_field',
                        "options" => $site_roles
                    ];
                }


                if ((int)$order['order_status'] === 1) {
                    $order_status = Registry::load('strings')->successful;
                } else if ((int)$order['order_status'] === 0) {
                    $order_status = Registry::load('strings')->pending;
                } else {
                    $order_status = Registry::load('strings')->failed;
                }

                $form['fields']->order_status = [
                    "title" => Registry::load('strings')->status, "tag" => 'input', "type" => "text", "class" => 'field',
                    "value" => $order_status, "attributes" => ['disabled' => 'disabled']
                ];

                if (role(['permissions' => ['memberships' => 'edit_site_transactions']])) {
                    if ((int)$order['order_status'] !== 1) {
                        if (isset($transaction_info->error_message)) {
                            $form['fields']->error_message = [
                                "title" => Registry::load('strings')->error, "tag" => 'textarea', "type" => "text", "class" => 'field',
                                "value" => $transaction_info->error_message, "attributes" => ['disabled' => 'disabled']
                            ];
                        }
                    }
                }

                $created_on = array();
                $created_on['date'] = $order['created_on'];
                $created_on['auto_format'] = true;
                $created_on['include_time'] = true;
                $created_on['timezone'] = Registry::load('current_user')->time_zone;
                $created_on = get_date($created_on);

                $form['fields']->date_text = [
                    "title" => Registry::load('strings')->date_text, "tag" => 'input', "type" => "text", "class" => 'field',
                    "value" => $created_on['date'].' '.$created_on['time'], "attributes" => ['disabled' => 'disabled']
                ];

                if (isset($transaction_info->pricing)) {
                    $transaction_info->pricing = Registry::load('settings')->default_currency_symbol.''.$transaction_info->pricing;
                    $form['fields']->pricing = [
                        "title" => Registry::load('strings')->pricing, "tag" => 'input', "type" => "text", "class" => 'field',
                        "value" => $transaction_info->pricing, "attributes" => ['disabled' => 'disabled']
                    ];
                }

                if (isset($transaction_info->gateway) && !empty($transaction_info->gateway)) {
                    $transaction_info->gateway = ucwords($transaction_info->gateway);
                    $form['fields']->payment_method = [
                        "title" => Registry::load('strings')->payment_method, "tag" => 'input', "type" => "text", "class" => 'field',
                        "value" => $transaction_info->gateway, "attributes" => ['disabled' => 'disabled']
                    ];
                }

                if (isset($transaction_info->duration) && !empty($transaction_info->duration)) {
                    $form['fields']->payment_method = [
                        "title" => Registry::load('strings')->duration, "tag" => 'input', "type" => "text", "class" => 'field',
                        "value" => $transaction_info->duration, "attributes" => ['disabled' => 'disabled']
                    ];
                }


            }
        }
    }
}
?>