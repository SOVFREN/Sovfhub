<?php

if (role(['permissions' => ['memberships' => ['view_personal_transactions', 'view_site_transactions']], 'condition' => 'OR'])) {

    $columns = [
        'membership_orders.order_id', 'membership_orders.user_id',
        'membership_orders.membership_package_id',
        'membership_orders.order_status',
        'membership_orders.payment_gateway_id',
        'membership_orders.transaction_info',
        'membership_orders.created_on',
        'site_users.username', 'site_users.email_address'
    ];

    $join["[>]site_users"] = ["membership_orders.user_id" => "user_id"];

    if (!empty($data["offset"])) {
        $data["offset"] = array_map('intval', explode(',', $data["offset"]));
        $where["membership_orders.order_id[!]"] = $data["offset"];
    }

    if (!empty($data["search"])) {
        if (isset($private_data["site_transactions"])) {
            if (filter_var($data["search"], FILTER_VALIDATE_EMAIL)) {
                $where["site_users.email_address[~]"] = $data["search"];
            } else if (is_numeric($data["search"])) {
                $where["membership_orders.order_id"] = $data["search"];
            } else {
                $where["site_users.username[~]"] = $data["search"];
            }

        } else {
            $where["membership_orders.order_id[~]"] = $data["search"];
        }
    }

    if (!isset($private_data["site_transactions"])) {
        $load_data = 'transactions';
        $where["membership_orders.user_id"] = Registry::load('current_user')->id;
    } else {
        $load_data = 'site_transactions';
    }

    $where["LIMIT"] = Registry::load('settings')->records_per_call;

    if ($data["sortby"] === 'order_id_asc') {
        $where["ORDER"] = ["membership_orders.order_id" => "ASC"];
    } else if ($data["sortby"] === 'order_id_desc') {
        $where["ORDER"] = ["membership_orders.order_id" => "DESC"];
    } else if ($data["sortby"] === 'status_asc') {
        $where["ORDER"] = ["membership_orders.order_status" => "ASC"];
    } else if ($data["sortby"] === 'status_desc') {
        $where["ORDER"] = ["membership_orders.order_status" => "DESC"];
    } else {
        $where["ORDER"] = ["membership_orders.order_id" => "DESC"];
    }

    $membership_orders = DB::connect()->select('membership_orders', $join, $columns, $where);

    $i = 1;
    $output = array();
    $output['loaded'] = new stdClass();
    $output['loaded']->title = Registry::load('strings')->transactions;
    $output['loaded']->loaded = $load_data;
    $output['loaded']->offset = array();

    if (!empty($data["offset"])) {
        $output['loaded']->offset = $data["offset"];
    }

    if (role(['permissions' => ['memberships' => 'edit_site_transactions']])) {
        $output['multiple_select'] = new stdClass();
        $output['multiple_select']->title = Registry::load('strings')->delete;
        $output['multiple_select']->attributes['class'] = 'ask_confirmation';
        $output['multiple_select']->attributes['data-remove'] = 'transactions';
        $output['multiple_select']->attributes['multi_select'] = 'order_id';
        $output['multiple_select']->attributes['submit_button'] = Registry::load('strings')->yes;
        $output['multiple_select']->attributes['cancel_button'] = Registry::load('strings')->no;
        $output['multiple_select']->attributes['confirmation'] = Registry::load('strings')->confirm_action;
    }

    $output['sortby'][1] = new stdClass();
    $output['sortby'][1]->sortby = Registry::load('strings')->sort_by_default;
    $output['sortby'][1]->class = 'load_aside';
    $output['sortby'][1]->attributes['load'] = $load_data;

    $output['sortby'][2] = new stdClass();
    $output['sortby'][2]->sortby = Registry::load('strings')->order_id;
    $output['sortby'][2]->class = 'load_aside sort_asc';
    $output['sortby'][2]->attributes['load'] = $load_data;
    $output['sortby'][2]->attributes['sort'] = 'order_id_asc';

    $output['sortby'][3] = new stdClass();
    $output['sortby'][3]->sortby = Registry::load('strings')->order_id;
    $output['sortby'][3]->class = 'load_aside sort_desc';
    $output['sortby'][3]->attributes['load'] = $load_data;
    $output['sortby'][3]->attributes['sort'] = 'order_id_desc';

    $output['sortby'][4] = new stdClass();
    $output['sortby'][4]->sortby = Registry::load('strings')->status;
    $output['sortby'][4]->class = 'load_aside sort_asc';
    $output['sortby'][4]->attributes['load'] = $load_data;
    $output['sortby'][4]->attributes['sort'] = 'status_asc';

    $output['sortby'][5] = new stdClass();
    $output['sortby'][5]->sortby = Registry::load('strings')->status;
    $output['sortby'][5]->class = 'load_aside sort_desc';
    $output['sortby'][5]->attributes['load'] = $load_data;
    $output['sortby'][5]->attributes['sort'] = 'status_desc';

    foreach ($membership_orders as $membership_order) {
        $output['loaded']->offset[] = $membership_order['order_id'];

        $package_name = 'membership_package_'.$membership_order['membership_package_id'];

        if (isset(Registry::load('strings')->$package_name)) {
            $package_name = Registry::load('strings')->$package_name;
        } else {
            $package_name = Registry::load('strings')->unknown;
        }

        $output['content'][$i] = new stdClass();
        $output['content'][$i]->title = Registry::load('strings')->order_id.': '.$membership_order['order_id'];
        $output['content'][$i]->title .= ' ['.$package_name.']';
        $output['content'][$i]->identifier = $membership_order['order_id'];
        $output['content'][$i]->class = "transaction";

        if ((int)$membership_order['order_status'] === 1) {
            $output['content'][$i]->subtitle = Registry::load('strings')->successful;
            $output['content'][$i]->image = Registry::load('config')->site_url."assets/files/defaults/successful.png";
        } else if ((int)$membership_order['order_status'] === 0) {
            $output['content'][$i]->subtitle = Registry::load('strings')->pending;
            $output['content'][$i]->image = Registry::load('config')->site_url."assets/files/defaults/pending.png";
        } else {
            $output['content'][$i]->subtitle = Registry::load('strings')->failed;
            $output['content'][$i]->image = Registry::load('config')->site_url."assets/files/defaults/failed.png";
        }

        if (isset($private_data["site_transactions"])) {
            $output['content'][$i]->subtitle .= ' [@'.$membership_order['username'].']';
        }

        $output['content'][$i]->icon = 0;
        $output['content'][$i]->unread = 0;

        $index = 1;

        if ((int)$membership_order['order_status'] === 1) {
            if (role(['permissions' => ['memberships' => 'download_invoice']])) {
                $output['options'][$i][$index] = new stdClass();
                $output['options'][$i][$index]->option = Registry::load('strings')->invoice;
                $output['options'][$i][$index]->class = 'download_file';
                $output['options'][$i][$index]->attributes['download'] = 'invoice';
                $output['options'][$i][$index]->attributes['data-order_id'] = $membership_order['order_id'];
                $index++;
            }
        } else if (!isset($private_data["site_transactions"]) && (int)$membership_order['order_status'] === 0) {
            $validation_url = Registry::load('config')->site_url.'validate_order/'.$membership_order['order_id'].'/';
            $output['options'][$i][$index] = new stdClass();
            $output['options'][$i][$index]->option = Registry::load('strings')->validate;
            $output['options'][$i][$index]->class = 'openlink';
            $output['options'][$i][$index]->attributes['url'] = $validation_url;
            $index++;
        }

        $output['options'][$i][$index] = new stdClass();

        if (role(['permissions' => ['memberships' => 'edit_site_transactions']])) {
            $output['options'][$i][$index]->option = Registry::load('strings')->edit_order;
        } else {
            $output['options'][$i][$index]->option = Registry::load('strings')->view_order;
        }

        $output['options'][$i][$index]->class = 'load_form';
        $output['options'][$i][$index]->attributes['form'] = 'transactions';
        $output['options'][$i][$index]->attributes['data-order_id'] = $membership_order['order_id'];
        $index++;

        if (role(['permissions' => ['memberships' => 'edit_site_transactions']])) {
            $output['options'][$i][$index] = new stdClass();
            $output['options'][$i][$index]->option = Registry::load('strings')->delete;
            $output['options'][$i][$index]->class = 'ask_confirmation';
            $output['options'][$i][$index]->attributes['data-remove'] = 'transactions';
            $output['options'][$i][$index]->attributes['data-order_id'] = $membership_order['order_id'];
            $output['options'][$i][$index]->attributes['submit_button'] = Registry::load('strings')->yes;
            $output['options'][$i][$index]->attributes['cancel_button'] = Registry::load('strings')->no;
            $output['options'][$i][$index]->attributes['confirmation'] = Registry::load('strings')->confirm_action;
            $index++;
        }


        $i++;
    }
}
?>