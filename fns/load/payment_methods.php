<?php

if (role(['permissions' => ['super_privileges' => 'manage_payment_gateways']])) {

    $columns = [
        'payment_gateways.payment_gateway_id', 'payment_gateways.identifier',
        'payment_gateways.disabled',
    ];

    if (!empty($data["offset"])) {
        $data["offset"] = array_map('intval', explode(',', $data["offset"]));
        $where["payment_gateways.payment_gateway_id[!]"] = $data["offset"];
    }

    if (!empty($data["search"])) {
        $where["payment_gateways.identifier[~]"] = $data["search"];
    }

    $where["LIMIT"] = Registry::load('settings')->records_per_call;

    if ($data["sortby"] === 'name_asc') {
        $where["ORDER"] = ["payment_gateways.identifier" => "ASC"];
    } else if ($data["sortby"] === 'name_desc') {
        $where["ORDER"] = ["payment_gateways.identifier" => "DESC"];
    } else if ($data["sortby"] === 'status_asc') {
        $where["ORDER"] = ["payment_gateways.disabled" => "ASC"];
    } else if ($data["sortby"] === 'status_desc') {
        $where["ORDER"] = ["payment_gateways.disabled" => "DESC"];
    } else {
        $where["ORDER"] = ["payment_gateways.payment_gateway_id" => "DESC"];
    }

    $payment_methods = DB::connect()->select('payment_gateways', $columns, $where);

    $i = 1;
    $output = array();
    $output['loaded'] = new stdClass();
    $output['loaded']->title = Registry::load('strings')->payment_methods;
    $output['loaded']->loaded = 'payment_methods';
    $output['loaded']->offset = array();

    $output['multiple_select'] = new stdClass();
    $output['multiple_select']->title = Registry::load('strings')->delete;
    $output['multiple_select']->attributes['class'] = 'ask_confirmation';
    $output['multiple_select']->attributes['data-remove'] = 'payment_methods';
    $output['multiple_select']->attributes['multi_select'] = 'payment_gateway_id';
    $output['multiple_select']->attributes['submit_button'] = Registry::load('strings')->yes;
    $output['multiple_select']->attributes['cancel_button'] = Registry::load('strings')->no;
    $output['multiple_select']->attributes['confirmation'] = Registry::load('strings')->confirm_action;


    $output['todo'] = new stdClass();
    $output['todo']->class = 'load_form';
    $output['todo']->title = Registry::load('strings')->add_payment_method;
    $output['todo']->attributes['form'] = 'payment_methods';


    if (!empty($data["offset"])) {
        $output['loaded']->offset = $data["offset"];
    }

    $output['sortby'][1] = new stdClass();
    $output['sortby'][1]->sortby = Registry::load('strings')->sort_by_default;
    $output['sortby'][1]->class = 'load_aside';
    $output['sortby'][1]->attributes['load'] = 'payment_methods';

    $output['sortby'][2] = new stdClass();
    $output['sortby'][2]->sortby = Registry::load('strings')->name;
    $output['sortby'][2]->class = 'load_aside sort_asc';
    $output['sortby'][2]->attributes['load'] = 'payment_methods';
    $output['sortby'][2]->attributes['sort'] = 'name_asc';

    $output['sortby'][3] = new stdClass();
    $output['sortby'][3]->sortby = Registry::load('strings')->name;
    $output['sortby'][3]->class = 'load_aside sort_desc';
    $output['sortby'][3]->attributes['load'] = 'payment_methods';
    $output['sortby'][3]->attributes['sort'] = 'name_desc';

    $output['sortby'][4] = new stdClass();
    $output['sortby'][4]->sortby = Registry::load('strings')->status;
    $output['sortby'][4]->class = 'load_aside sort_asc';
    $output['sortby'][4]->attributes['load'] = 'payment_methods';
    $output['sortby'][4]->attributes['sort'] = 'status_asc';

    $output['sortby'][5] = new stdClass();
    $output['sortby'][5]->sortby = Registry::load('strings')->status;
    $output['sortby'][5]->class = 'load_aside sort_desc';
    $output['sortby'][5]->attributes['load'] = 'payment_methods';
    $output['sortby'][5]->attributes['sort'] = 'status_desc';

    foreach ($payment_methods as $payment_method) {
        $output['loaded']->offset[] = $payment_method['payment_gateway_id'];
        $payment_method['identifier'] = str_replace('_', ' ', $payment_method['identifier']);

        $output['content'][$i] = new stdClass();
        $output['content'][$i]->title = ucwords($payment_method['identifier']);
        $output['content'][$i]->alphaicon = true;
        $output['content'][$i]->identifier = $payment_method['payment_gateway_id'];
        $output['content'][$i]->class = "payment_method";

        if ((int)$payment_method['disabled'] === 1) {
            $output['content'][$i]->subtitle = Registry::load('strings')->disabled;
        } else {
            $output['content'][$i]->subtitle = Registry::load('strings')->enabled;
        }

        $output['content'][$i]->icon = 0;
        $output['content'][$i]->unread = 0;

        $output['options'][$i][1] = new stdClass();
        $output['options'][$i][1]->option = Registry::load('strings')->edit;
        $output['options'][$i][1]->class = 'load_form';
        $output['options'][$i][1]->attributes['form'] = 'payment_methods';
        $output['options'][$i][1]->attributes['data-payment_gateway_id'] = $payment_method['payment_gateway_id'];

        $output['options'][$i][3] = new stdClass();
        $output['options'][$i][3]->class = 'ask_confirmation';
        $output['options'][$i][3]->option = Registry::load('strings')->delete;
        $output['options'][$i][3]->attributes['data-remove'] = 'payment_methods';
        $output['options'][$i][3]->attributes['data-payment_gateway_id'] = $payment_method['payment_gateway_id'];
        $output['options'][$i][3]->attributes['submit_button'] = Registry::load('strings')->yes;
        $output['options'][$i][3]->attributes['cancel_button'] = Registry::load('strings')->no;
        $output['options'][$i][3]->attributes['confirmation'] = Registry::load('strings')->confirm_action;


        $i++;
    }
}
?>