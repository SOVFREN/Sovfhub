<?php
use SleekDB\Store;

if (role(['permissions' => ['membership_packages' => 'edit']])) {
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
            'membership_packages.membership_package_id', 'string.string_value(package_name)'
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
            "form" => "membership_package_benefits",
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

        $form['loaded']->title = Registry::load('strings')->benefits;
        $form['loaded']->button = Registry::load('strings')->update;

        $form['fields']->$todo = [
            "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "membership_package_benefits"
        ];

        $form['fields']->package_name = [
            "title" => Registry::load('strings')->package_name, "tag" => 'input', "attributes" => ["disabled" => "disabled"],
            "class" => 'field', "value" => $package['package_name'],
        ];

        $form['fields']->benefits = [
            "title" => Registry::load('strings')->benefits, "tag" => 'input', "type" => "text", "class" => 'field',
            "placeholder" => Registry::load('strings')->benefits, "clone_field_on_input" => true,
        ];


        $no_sql = new Store('membership_package_benefits', 'assets/nosql_database/');
        $benefits = $no_sql->findById($load["membership_package_id"]);

        if (!empty($benefits)) {
            $language_index = 'language_'.$language_id;
            if (isset($benefits[$language_index])) {
                $form['fields']->benefits["values"] = $benefits[$language_index];
            }
        }

    }

}
?>