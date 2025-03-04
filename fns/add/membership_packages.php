<?php

if (role(['permissions' => ['membership_packages' => 'create']])) {

    include 'fns/filters/load.php';
    $result = array();
    $noerror = true;
    $disabled = $duration = $pricing = $is_recurring = 0;
    $result['success'] = false;
    $result['error_message'] = Registry::load('strings')->invalid_value;
    $result['error_key'] = 'invalid_value';
    $result['error_variables'] = [];

    $fields_to_check = ['package_name', 'related_site_role_id', 'site_role_id_on_expire', 'billing_interval'];

    foreach ($fields_to_check as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            $result['error_variables'][] = $field;
            $noerror = false;
        }
    }

    if ($noerror) {
        $data['related_site_role_id'] = filter_var($data['related_site_role_id'], FILTER_SANITIZE_NUMBER_INT);
        $data['site_role_id_on_expire'] = filter_var($data['site_role_id_on_expire'], FILTER_SANITIZE_NUMBER_INT);

        if (empty($data['related_site_role_id'])) {
            $result['error_variables'][] = ['related_site_role_id'];
            $noerror = false;
        }

        if (empty($data['site_role_id_on_expire'])) {
            $result['error_variables'][] = ['site_role_id_on_expire'];
            $noerror = false;
        }

    }

    if ($noerror) {

        $data['package_name'] = htmlspecialchars($data['package_name'], ENT_QUOTES, 'UTF-8');

        if (isset($data['disabled']) && $data['disabled'] === 'yes') {
            $disabled = 1;
        }

        if (isset($data['pricing'])) {
            $data['pricing'] = filter_var($data['pricing'], FILTER_SANITIZE_NUMBER_INT);

            if (!empty($data['pricing'])) {
                $pricing = $data['pricing'];
            }
        }

        if (isset($data['package_sort_index'])) {
            $data['package_sort_index'] = filter_var($data['package_sort_index'], FILTER_SANITIZE_NUMBER_INT);
        }

        if (!isset($data['package_sort_index']) || empty($data['package_sort_index'])) {
            $data['package_sort_index'] = 10;
        }

        if ($data['billing_interval'] === 'one_time') {
            $is_recurring = 1;
        } else if ($data['billing_interval'] === 'monthly') {
            $duration = 30;
        } else if ($data['billing_interval'] === 'yearly') {
            $duration = 365;
        } else if ($data['billing_interval'] === 'custom') {
            $duration = 1;

            if (isset($data['no_of_days'])) {
                $data['no_of_days'] = filter_var($data['no_of_days'], FILTER_SANITIZE_NUMBER_INT);

                if (!empty($data['no_of_days'])) {
                    $duration = $data['no_of_days'];
                }
            }
        }

        DB::connect()->insert("membership_packages", [
            "string_constant" => $data['package_name'],
            "is_recurring" => $is_recurring,
            "pricing" => $pricing,
            "package_sort_index" => $data['package_sort_index'],
            "duration" => $duration,
            "related_site_role_id" => $data['related_site_role_id'],
            "site_role_id_on_expire" => $data['site_role_id_on_expire'],
            "disabled" => $disabled,
            "updated_on" => Registry::load('current_user')->time_stamp,
        ]);

        if (!DB::connect()->error) {
            $membership_package_id = DB::connect()->id();
            $string_constant = 'membership_package_'.$membership_package_id;

            DB::connect()->update("membership_packages", ["string_constant" => $string_constant], ["membership_package_id" => $membership_package_id]);

            language(['add_string' => $string_constant, 'value' => $data['package_name']]);


            $result = array();
            $result['success'] = true;
            $result['todo'] = 'reload';
            $result['reload'] = 'membership_packages';
        } else {
            $result['error_message'] = Registry::load('strings')->went_wrong;
            $result['error_key'] = 'something_went_wrong';
        }

    }
}
?>