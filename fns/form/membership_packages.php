<?php

if (role(['permissions' => ['membership_packages' => 'create']]) || role(['permissions' => ['membership_packages' => 'edit']])) {
    $form = array();

    $todo = 'add';
    $form['loaded'] = new stdClass();
    $form['fields'] = new stdClass();
    $language_id = Registry::load('current_user')->language;

    if (isset($load["membership_package_id"])) {

        $todo = 'update';

        $columns = [
            'languages.name', 'languages.language_id'
        ];

        $where["languages.language_id[!]"] = null;

        $languages = DB::connect()->select('languages', $columns, $where);

        if (isset($load["language_id"])) {
            $load["language_id"] = filter_var($load["language_id"], FILTER_SANITIZE_NUMBER_INT);

            if (!empty($load["language_id"])) {
                $language_id = $load["language_id"];
            }
        }

        $columns = $join = $where = null;

        $columns = [
            'membership_packages.membership_package_id', 'string.string_value(package_name)',
            'membership_packages.disabled', "membership_packages.is_recurring", "membership_packages.pricing",
            "membership_packages.duration", "membership_packages.related_site_role_id", "membership_packages.site_role_id_on_expire",
            "membership_packages.package_sort_index",
        ];

        $join = ["[>]language_strings(string)" => [
            "membership_packages.string_constant" => "string_constant",
            "AND" => ["language_id" => $language_id]
        ]];

        $where["membership_packages.membership_package_id"] = $load["membership_package_id"];
        $where["LIMIT"] = 1;

        $package = DB::connect()->select('membership_packages', $join, $columns, $where);

        if (!isset($package[0])) {
            return false;
        } else {
            $package = $package[0];
        }

        $form['fields']->language_id = [
            "title" => Registry::load('strings')->language, "tag" => 'select', "class" => 'field'
        ];

        if (isset($load["language_id"]) && !empty($load["language_id"])) {
            $form['fields']->language_id['value'] = $load["language_id"];
        }

        $form['fields']->language_id["class"] = 'field switch_form';
        $form['fields']->language_id["parent_attributes"] = [
            "form" => "membership_packages",
            "data-membership_package_id" => $load["membership_package_id"],
            "enlarge" => true
        ];

        foreach ($languages as $language) {
            $language_identifier = $language['language_id'];
            $form['fields']->language_id['options'][$language_identifier] = $language['name'];
        }

        $form['fields']->membership_package_id = [
            "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => $load["membership_package_id"]
        ];

        $form['loaded']->title = Registry::load('strings')->edit_package;
        $form['loaded']->button = Registry::load('strings')->update;
    } else {
        $form['loaded']->title = Registry::load('strings')->add_package;
        $form['loaded']->button = Registry::load('strings')->create;
    }

    $form['fields']->$todo = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "membership_packages"
    ];

    $form['fields']->package_name = [
        "title" => Registry::load('strings')->package_name, "tag" => 'input', "type" => "text", "class" => 'field',
        "placeholder" => Registry::load('strings')->package_name,
    ];

    $form['fields']->pricing = [
        "title" => Registry::load('strings')->pricing.' ('.Registry::load('settings')->default_currency_symbol.')', "tag" => 'input', "type" => "number", "class" => 'field',
        "placeholder" => Registry::load('strings')->pricing, "value" => 0
    ];

    $form['fields']->billing_interval = [
        "title" => Registry::load('strings')->billing_interval, "tag" => 'select', "class" => 'field toggle_form_fields',
        "attributes" => ["hide_field" => "billing_interval_fields", "show_fields" => "custom|no_of_days"]
    ];

    $form['fields']->billing_interval['options'] = [
        "one_time" => Registry::load('strings')->one_time,
        "monthly" => Registry::load('strings')->monthly,
        "yearly" => Registry::load('strings')->yearly,
        "custom" => Registry::load('strings')->custom,
    ];

    $form['fields']->no_of_days = [
        "title" => Registry::load('strings')->no_of_days, "tag" => 'input', "type" => "number", "class" => 'field billing_interval_fields no_of_days d-none',
        "placeholder" => Registry::load('strings')->no_of_days, "value" => 30
    ];

    $language_id = Registry::load('current_user')->language;
    $join = ["[>]language_strings(string)" => [
        "site_roles.string_constant" => "string_constant",
        "AND" => ["language_id" => $language_id]]
    ];
    $columns = ['site_roles.site_role_id', 'string.string_value(name)'];
    $where = ['site_roles.site_role_id[!]' => Registry::load('site_role_attributes')->banned_users];
    $site_roles = DB::connect()->select('site_roles', $join, $columns, $where);
    $site_roles = array_column($site_roles, 'name', 'site_role_id');

    $form['fields']->related_site_role_id = [
        "title" => Registry::load('strings')->related_site_role, "tag" => 'select', "class" => 'field',
        "options" => $site_roles
    ];

    $form['fields']->site_role_id_on_expire = [
        "title" => Registry::load('strings')->site_role_id_on_expire, "tag" => 'select', "class" => 'field',
        "options" => $site_roles
    ];

    $form['fields']->package_sort_index = [
        "title" => Registry::load('strings')->sort_index, "tag" => 'input', "type" => "number",
        "class" => 'field', "placeholder" => Registry::load('strings')->sort_index, "value" => 1
    ];

    $form['fields']->disabled = [
        "title" => Registry::load('strings')->disabled, "tag" => 'select', "class" => 'field'
    ];
    $form['fields']->disabled['options'] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no,
    ];

    if (isset($load["membership_package_id"])) {
        $disabled = 'no';

        if ((int)$package['disabled'] === 1) {
            $disabled = 'yes';
        }

        $form['fields']->package_name["value"] = $package['package_name'];
        $form['fields']->pricing["value"] = $package['pricing'];
        $form['fields']->related_site_role_id["value"] = $package['related_site_role_id'];
        $form['fields']->site_role_id_on_expire["value"] = $package['site_role_id_on_expire'];
        $form['fields']->package_sort_index["value"] = $package['package_sort_index'];
        $form['fields']->disabled["value"] = $disabled;

        if (!empty($package['is_recurring'])) {
            $package['billing_interval'] = 'one_time';
        } else if ((int)$package['duration'] === 30) {
            $package['billing_interval'] = 'monthly';
        } else if ((int)$package['duration'] === 365) {
            $package['billing_interval'] = 'yearly';
        } else {
            $package['billing_interval'] = 'custom';
        }

        $form['fields']->billing_interval["value"] = $package['billing_interval'];
        $form['fields']->no_of_days["value"] = $package['duration'];

    }
}
?>