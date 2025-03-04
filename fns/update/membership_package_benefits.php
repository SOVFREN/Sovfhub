<?php

use SleekDB\Store;

if (role(['permissions' => ['membership_packages' => 'edit']])) {

    include 'fns/filters/load.php';
    $result = array();
    $noerror = true;
    $disabled = $duration = $pricing = $is_recurring = 0;
    $membership_package_id = 0;
    $result['success'] = false;
    $result['error_message'] = Registry::load('strings')->invalid_value;
    $result['error_key'] = 'invalid_value';
    $result['error_variables'] = [];

    $language_id = Registry::load('current_user')->language;

    if (isset($data["language_id"])) {
        $data["language_id"] = filter_var($data["language_id"], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($data["language_id"])) {
            $language_id = $data["language_id"];
        }
    }

    if (!isset($data['benefits'])) {
        $result['error_variables'][] = 'benefits';
        $noerror = false;
    }

    if (isset($data['membership_package_id'])) {
        $membership_package_id = filter_var($data["membership_package_id"], FILTER_SANITIZE_NUMBER_INT);
    }

    if ($noerror && !empty($membership_package_id)) {
        $language_index = 'language_'.$language_id;

        $data['benefits'] = array_filter($data['benefits']);
        $data['benefits'] = array_map('htmlspecialchars', $data['benefits']);

        $benefits = array();
        $benefits[$language_index] = $data['benefits'];

        $no_sql = new Store('membership_package_benefits', 'assets/nosql_database/');
        $check_benefits = $no_sql->findById($membership_package_id);

        if (empty($check_benefits)) {
            $benefits["_id"] = $membership_package_id;
            $no_sql->updateOrInsert($benefits, false);
        } else {
            $no_sql->updateById($membership_package_id, $benefits);
        }

        $result = array();
        $result['success'] = true;
        $result['todo'] = 'reload';
        $result['reload'] = 'membership_packages';

    }
}
?>