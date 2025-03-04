<?php

if (role(['permissions' => ['membership_packages' => 'view']])) {

    $columns = [
        'membership_packages.membership_package_id', 'string.string_value(package_name)',
        'membership_packages.disabled',
    ];

    if (!empty($data["offset"])) {
        $data["offset"] = array_map('intval', explode(',', $data["offset"]));
        $where["membership_packages.membership_package_id[!]"] = $data["offset"];
    }

    if (!empty($data["search"])) {
        $where["string.string_value[~]"] = $data["search"];
    }

    $join["[>]language_strings(string)"] = [
        "membership_packages.string_constant" => "string_constant",
        "AND" => ["language_id" => Registry::load('current_user')->language]
    ];

    $where["LIMIT"] = Registry::load('settings')->records_per_call;

    if ($data["sortby"] === 'name_asc') {
        $where["ORDER"] = ["string.string_value" => "ASC"];
    } else if ($data["sortby"] === 'name_desc') {
        $where["ORDER"] = ["string.string_value" => "DESC"];
    } else if ($data["sortby"] === 'status_asc') {
        $where["ORDER"] = ["membership_packages.disabled" => "ASC"];
    } else if ($data["sortby"] === 'status_desc') {
        $where["ORDER"] = ["membership_packages.disabled" => "DESC"];
    } else {
        $where["ORDER"] = ["membership_packages.membership_package_id" => "DESC"];
    }

    $packages = DB::connect()->select('membership_packages', $join, $columns, $where);

    $i = 1;
    $output = array();
    $output['loaded'] = new stdClass();
    $output['loaded']->title = Registry::load('strings')->memberships;
    $output['loaded']->loaded = 'membership_packages';
    $output['loaded']->offset = array();

    if (role(['permissions' => ['membership_packages' => 'delete']])) {
        $output['multiple_select'] = new stdClass();
        $output['multiple_select']->title = Registry::load('strings')->delete;
        $output['multiple_select']->attributes['class'] = 'ask_confirmation';
        $output['multiple_select']->attributes['data-remove'] = 'membership_packages';
        $output['multiple_select']->attributes['multi_select'] = 'membership_package_id';
        $output['multiple_select']->attributes['submit_button'] = Registry::load('strings')->yes;
        $output['multiple_select']->attributes['cancel_button'] = Registry::load('strings')->no;
        $output['multiple_select']->attributes['confirmation'] = Registry::load('strings')->confirm_action;
    }


    if (role(['permissions' => ['membership_packages' => 'create']])) {
        $output['todo'] = new stdClass();
        $output['todo']->class = 'load_form';
        $output['todo']->title = Registry::load('strings')->add_package;
        $output['todo']->attributes['form'] = 'membership_packages';
        $output['todo']->attributes['enlarge'] = true;
    }

    if (!empty($data["offset"])) {
        $output['loaded']->offset = $data["offset"];
    }

    $output['sortby'][1] = new stdClass();
    $output['sortby'][1]->sortby = Registry::load('strings')->sort_by_default;
    $output['sortby'][1]->class = 'load_aside';
    $output['sortby'][1]->attributes['load'] = 'membership_packages';

    $output['sortby'][2] = new stdClass();
    $output['sortby'][2]->sortby = Registry::load('strings')->name;
    $output['sortby'][2]->class = 'load_aside sort_asc';
    $output['sortby'][2]->attributes['load'] = 'membership_packages';
    $output['sortby'][2]->attributes['sort'] = 'name_asc';

    $output['sortby'][3] = new stdClass();
    $output['sortby'][3]->sortby = Registry::load('strings')->name;
    $output['sortby'][3]->class = 'load_aside sort_desc';
    $output['sortby'][3]->attributes['load'] = 'membership_packages';
    $output['sortby'][3]->attributes['sort'] = 'name_desc';

    $output['sortby'][4] = new stdClass();
    $output['sortby'][4]->sortby = Registry::load('strings')->status;
    $output['sortby'][4]->class = 'load_aside sort_asc';
    $output['sortby'][4]->attributes['load'] = 'membership_packages';
    $output['sortby'][4]->attributes['sort'] = 'status_asc';

    $output['sortby'][5] = new stdClass();
    $output['sortby'][5]->sortby = Registry::load('strings')->status;
    $output['sortby'][5]->class = 'load_aside sort_desc';
    $output['sortby'][5]->attributes['load'] = 'membership_packages';
    $output['sortby'][5]->attributes['sort'] = 'status_desc';

    foreach ($packages as $package) {
        $output['loaded']->offset[] = $package['membership_package_id'];

        $output['content'][$i] = new stdClass();
        $output['content'][$i]->title = $package['package_name'];
        $output['content'][$i]->alphaicon = true;
        $output['content'][$i]->identifier = $package['membership_package_id'];
        $output['content'][$i]->class = "membership_package";

        if ((int)$package['disabled'] === 1) {
            $output['content'][$i]->subtitle = Registry::load('strings')->disabled;
        } else {
            $output['content'][$i]->subtitle = Registry::load('strings')->enabled;
        }

        $output['content'][$i]->icon = 0;
        $output['content'][$i]->unread = 0;

        if (role(['permissions' => ['membership_packages' => 'edit']])) {
            $output['options'][$i][1] = new stdClass();
            $output['options'][$i][1]->option = Registry::load('strings')->edit;
            $output['options'][$i][1]->class = 'load_form';
            $output['options'][$i][1]->attributes['form'] = 'membership_packages';
            $output['options'][$i][1]->attributes['enlarge'] = true;
            $output['options'][$i][1]->attributes['data-membership_package_id'] = $package['membership_package_id'];
            
            $output['options'][$i][2] = new stdClass();
            $output['options'][$i][2]->option = Registry::load('strings')->benefits;
            $output['options'][$i][2]->class = 'load_form';
            $output['options'][$i][2]->attributes['form'] = 'membership_package_benefits';
            $output['options'][$i][2]->attributes['enlarge'] = true;
            $output['options'][$i][2]->attributes['data-membership_package_id'] = $package['membership_package_id'];
        }

        if (role(['permissions' => ['membership_packages' => 'delete']])) {
            $output['options'][$i][3] = new stdClass();
            $output['options'][$i][3]->class = 'ask_confirmation';
            $output['options'][$i][3]->option = Registry::load('strings')->delete;
            $output['options'][$i][3]->attributes['data-remove'] = 'membership_packages';
            $output['options'][$i][3]->attributes['data-membership_package_id'] = $package['membership_package_id'];
            $output['options'][$i][3]->attributes['submit_button'] = Registry::load('strings')->yes;
            $output['options'][$i][3]->attributes['cancel_button'] = Registry::load('strings')->no;
            $output['options'][$i][3]->attributes['confirmation'] = Registry::load('strings')->confirm_action;
        }

        $i++;
    }
}
?>