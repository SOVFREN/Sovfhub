<?php

use SleekDB\Store;

$result = array();
$noerror = true;

$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$membership_package_ids = array();

if (role(['permissions' => ['membership_packages' => 'delete']])) {
    if (isset($data['membership_package_id'])) {
        if (!is_array($data['membership_package_id'])) {
            $data["membership_package_id"] = filter_var($data["membership_package_id"], FILTER_SANITIZE_NUMBER_INT);
            $membership_package_ids[] = $data["membership_package_id"];
        } else {
            $membership_package_ids = array_filter($data["membership_package_id"], 'ctype_digit');
        }
    }

    if (!empty($membership_package_ids)) {

        DB::connect()->delete("membership_packages", ["membership_package_id" => $membership_package_ids]);

        if (!DB::connect()->error) {

            $membership_package_names = array();
            $no_sql = new Store('membership_package_benefits', 'assets/nosql_database/');

            foreach ($membership_package_ids as $membership_package_id) {
                $membership_package_names[] = 'membership_package_'.$membership_package_id;
                $no_sql->deleteById($membership_package_id);
            }

            language(['delete_string' => $membership_package_names]);

            $result = array();
            $result['success'] = true;
            $result['todo'] = 'reload';
            $result['reload'] = 'membership_packages';
        } else {
            $result['error_message'] = Registry::load('strings')->went_wrong;
        }
    }
}
?>