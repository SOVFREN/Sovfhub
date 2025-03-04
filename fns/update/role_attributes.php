<?php

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->invalid_value;
$result['error_key'] = 'invalid_value';

if (role(['permissions' => ['super_privileges' => 'core_settings']])) {

    $noerror = true;

    $site_role_attributes = [
        'default_site_role', 'site_admin_role', 'guest_user_role', 'site_unverified_user_role', 'site_banned_user_role'
    ];

    $group_role_attributes = [
        'default_group_role', 'group_admin_role', 'group_moderator_role', 'group_banned_user_role'
    ];

    $required_fields = array_merge($site_role_attributes, $group_role_attributes);

    foreach ($required_fields as $required_field) {
        if (!isset($data[$required_field]) || empty($data[$required_field])) {
            $result['error_variables'][] = [$required_field];
            $noerror = false;
        }
    }

    if ($noerror) {
        $seenValues = array();
        foreach ($site_role_attributes as $site_role_attribute) {

            $value = $data[$site_role_attribute];

            if (in_array($value, $seenValues)) {
                $duplicateIndex = array_search($value, $seenValues);
                $result['error_message'] = Registry::load('strings')->found_duplicate_roles;
                $result['error_key'] = 'found_duplicate_roles';
                $result['error_variables'][] = [$site_role_attribute];
                $result['error_variables'][] = [$duplicateIndex];
                $noerror = false;
            }

            $seenValues[$site_role_attribute] = $value;
        }
    }

    if ($noerror) {
        $seenValues = array();
        foreach ($group_role_attributes as $group_role_attribute) {

            $value = $data[$group_role_attribute];

            if (in_array($value, $seenValues)) {
                $duplicateIndex = array_search($value, $seenValues);
                $result['error_message'] = Registry::load('strings')->found_duplicate_roles;
                $result['error_key'] = 'found_duplicate_roles';
                $result['error_variables'][] = [$group_role_attribute];
                $result['error_variables'][] = [$duplicateIndex];
                $noerror = false;
            }

            $seenValues[$group_role_attribute] = $value;
        }
    }

    if ($noerror) {
        DB::connect()->update("site_roles", ["site_role_attribute" => "custom_site_role"]);

        foreach ($site_role_attributes as $value_index) {

            $value = $data[$value_index];
            $role_attribute = null;

            if ($value_index === 'default_site_role') {
                $role_attribute = 'default_site_role';
            } else if ($value_index === 'site_admin_role') {
                $role_attribute = 'administrators';
            } else if ($value_index === 'guest_user_role') {
                $role_attribute = 'guest_users';
            } else if ($value_index === 'site_unverified_user_role') {
                $role_attribute = 'unverified_users';
            } else if ($value_index === 'site_banned_user_role') {
                $role_attribute = 'banned_users';
            }

            if (!empty($role_attribute)) {
                DB::connect()->update("site_roles", ["site_role_attribute" => $role_attribute], ["site_role_id" => $value]);
            }
        }


        DB::connect()->update("group_roles", ["group_role_attribute" => "custom_group_role"]);

        foreach ($group_role_attributes as $value_index) {

            $value = $data[$value_index];
            $role_attribute = null;

            if ($value_index === 'default_group_role') {
                $role_attribute = 'default_group_role';
            } else if ($value_index === 'group_admin_role') {
                $role_attribute = 'administrators';
            } else if ($value_index === 'group_moderator_role') {
                $role_attribute = 'moderators';
            } else if ($value_index === 'group_banned_user_role') {
                $role_attribute = 'banned_users';
            }

            if (!empty($role_attribute)) {
                DB::connect()->update("group_roles", ["group_role_attribute" => $role_attribute], ["group_role_id" => $value]);
            }
        }


        cache(['rebuild' => 'site_roles']);
        cache(['rebuild' => 'group_roles']);

        $result = array();
        $result['success'] = true;
        $result['todo'] = 'reload';
        $result['reload'] = ['site_roles', 'group_roles'];


    }



}