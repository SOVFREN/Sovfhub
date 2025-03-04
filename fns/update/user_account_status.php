<?php
$user_id = 0;

if (role(['permissions' => ['site_users' => 'approve_users']])) {

    if (isset($data['user_id'])) {
        $user_id = filter_var($data["user_id"], FILTER_SANITIZE_NUMBER_INT);
    }

    $site_user = array();

    if (!empty($user_id)) {


        $columns = $where = $join = null;

        $columns = ['site_users.site_role_id', 'site_users.email_address', 'site_roles.site_role_attribute', 'site_roles.role_hierarchy'];
        $join["[>]site_roles"] = ["site_users.site_role_id" => "site_role_id"];
        $site_user = DB::connect()->select('site_users', $join, $columns, ['user_id' => $user_id]);

        if (!$force_request && Registry::load('current_user')->site_role_attribute !== 'administrators') {

            if (!empty($site_user)) {
                if ((int)$site_user[0]['role_hierarchy'] >= (int)Registry::load('current_user')->role_hierarchy) {
                    $result['error_message'] = Registry::load('strings')->permission_denied;
                    $result['error_key'] = 'permission_denied';
                    return;
                }
            } else {
                $result['error_message'] = Registry::load('strings')->permission_denied;
                $result['error_key'] = 'permission_denied';
                return;
            }
        }


        $update_data = ['updated_on' => Registry::load('current_user')->time_stamp];
        $update_data['approved'] = 1;

        if (isset($data['disapprove'])) {
            $update_data['approved'] = 0;
        }

        DB::connect()->update("site_users", $update_data, ["user_id" => $user_id]);

        if (isset($site_user[0]) && !isset($data['disapprove'])) {

            include_once('fns/mailer/load.php');

            $mail = array();
            $mail['email_addresses'] = $site_user[0]['email_address'];
            $mail['category'] = 'welcome_user';
            $mail['user_id'] = $user_id;
            $mail['parameters'] = ['link' => Registry::load('config')->site_url];
            $mail['send_now'] = true;
            mailer('compose', $mail);
        }
    }

    $result = array();
    $result['success'] = true;
    $result['todo'] = 'reload';
    $result['reload'] = 'site_users';
    $result['filter_data'] = 'pending_approval';

    if (isset($data['info_box']) && !empty($user_id)) {
        $result['info_box']['user_id'] = $user_id;
    }

} else {
    $result['error_message'] = Registry::load('strings')->went_wrong;
    $result['error_key'] = 'something_went_wrong';
}