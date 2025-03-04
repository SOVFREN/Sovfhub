<?php

$noerror = true;
$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';

if (role(['permissions' => ['super_privileges' => 'core_settings']])) {

    $result['error_message'] = Registry::load('strings')->invalid_value;
    $result['error_key'] = 'invalid_value';
    $result['error_variables'] = [];

    $email_categories = [
        "welcome_user_email", "reset_password_email", "verification_email", "login_link_email",
        "group_invitation_email", "new_friend_request_email", "new_private_message_email", "user_pending_approval_email",
    ];

    if (!isset($data['email_category']) || empty($data['email_category'])) {
        $result['error_variables'][] = ['email_category'];
        $noerror = false;
    } else if (!in_array($data['email_category'], $email_categories)) {
        $result['error_variables'][] = ['email_category'];
        $noerror = false;
    }

    $required_fields = [
        'email_subject', 'email_heading',
        'email_content', 'email_button_label'
    ];

    foreach ($required_fields as $required_field) {

        if (!isset($data[$required_field]) || empty($data[$required_field])) {
            $result['error_variables'][] = [$required_field];
            $noerror = false;
        }
    }

    if ($noerror) {

        $language_id = Registry::load('current_user')->language;

        if (isset($data["language_id"])) {
            $data["language_id"] = filter_var($data["language_id"], FILTER_SANITIZE_NUMBER_INT);

            if (!empty($data["language_id"])) {
                $language_id = $data["language_id"];
            }
        }

        $email_category = $data['email_category'];

        language(['edit_string' => $email_category.'_subject', 'value' => $data['email_subject'], 'language_id' => $language_id]);
        language(['edit_string' => $email_category.'_heading', 'value' => $data['email_heading'], 'language_id' => $language_id]);
        language(['edit_string' => $email_category.'_content', 'value' => $data['email_content'], 'language_id' => $language_id]);
        language(['edit_string' => $email_category.'_button_label', 'value' => $data['email_button_label'], 'language_id' => $language_id]);

        $result['success'] = true;
        $result['todo'] = 'refresh';


        $result['on_refresh'] = [
            'attributes' => [
                'class' => 'load_form',
                'form' => 'email_contents',
                'enlarge' => true,
                'data-email_category' => $data['email_category']
            ]
        ];
    }
}