<?php
$result = array();
$noerror = true;

$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$scheduled_message_ids = array();

if (role(['permissions' => ['super_privileges' => 'message_scheduler']])) {

    if (isset($data['scheduled_message_id'])) {
        if (!is_array($data['scheduled_message_id'])) {
            $data["scheduled_message_id"] = filter_var($data["scheduled_message_id"], FILTER_SANITIZE_NUMBER_INT);
            $scheduled_message_ids[] = $data["scheduled_message_id"];
        } else {
            $scheduled_message_ids = array_filter($data["scheduled_message_id"], 'ctype_digit');
        }
    }

    if (!empty($scheduled_message_ids)) {

        DB::connect()->delete("scheduled_messages", ["scheduled_message_id" => $scheduled_message_ids]);

        if (!DB::connect()->error) {
            $result = array();
            $result['success'] = true;
            $result['todo'] = 'reload';
            $result['reload'] = 'scheduled_messages';
        } else {
            $result['errormsg'] = Registry::load('strings')->went_wrong;
        }
    }
}
?>