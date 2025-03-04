<?php

if (role(['permissions' => ['super_privileges' => 'core_settings']])) {

    $language_id = Registry::load('current_user')->language;

    $columns = $join = $where = null;
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


    $form = array();
    $form['loaded'] = new stdClass();
    $form['loaded']->title = Registry::load('strings')->email_contents;
    $form['loaded']->button = Registry::load('strings')->update;

    $form['fields'] = new stdClass();

    $form['fields']->update = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "email_contents"
    ];

    $form['fields']->language_id = [
        "title" => Registry::load('strings')->language, "tag" => 'select', "class" => 'field'
    ];

    if (isset($load["language_id"]) && !empty($load["language_id"])) {
        $form['fields']->language_id['value'] = $load["language_id"];
    }

    $form['fields']->language_id["class"] = 'field switch_form';
    $form['fields']->language_id["parent_attributes"] = [
        "form" => "email_contents", "enlarge" => "true", "pass_field_values" => "email_category"
    ];

    foreach ($languages as $language) {
        $language_identifier = $language['language_id'];
        $form['fields']->language_id['options'][$language_identifier] = $language['name'];
    }



    $form['fields']->email_category = [
        "title" => Registry::load('strings')->email_category, "tag" => 'select', "class" => 'field',
    ];

    $form['fields']->email_category["class"] = 'field switch_form';
    $form['fields']->email_category["parent_attributes"] = [
        "form" => "email_contents", "enlarge" => "true", "pass_field_values" => "language_id"
    ];

    $form['fields']->email_category['options'] = [
        "welcome_user_email" => Registry::load('strings')->welcome_email,
        "reset_password_email" => Registry::load('strings')->reset_password,
        "verification_email" => Registry::load('strings')->verification_email,
        "login_link_email" => Registry::load('strings')->login_link_email,
        "group_invitation_email" => Registry::load('strings')->group_invitation_email,
        "new_friend_request_email" => Registry::load('strings')->new_friend_request,
        "new_private_message_email" => Registry::load('strings')->new_private_message,
        "user_pending_approval_email" => Registry::load('strings')->user_pending_approval,
    ];



    if (isset($load["email_category"]) && !empty($load["email_category"])) {

        $load['email_category'] = htmlspecialchars(trim($load['email_category']), ENT_QUOTES, 'UTF-8');

        if (!empty($load["email_category"])) {

            $columns = $join = $where = null;
            $columns = ['language_strings.string_constant', 'language_strings.string_value'];

            $where["language_strings.language_id"] = $language_id;

            $email_category = $load["email_category"];

            $where["AND"]["OR"] = [
                "language_strings.string_constant #condition_01" => $email_category.'_subject',
                "language_strings.string_constant #condition_02" => $email_category.'_heading',
                "language_strings.string_constant #condition_03" => $email_category.'_content',
                "language_strings.string_constant #condition_04" => $email_category.'_button_label',
            ];
            $email_contents = DB::connect()->select('language_strings', $columns, $where);

            $form['fields']->email_category['value'] = $load["email_category"];


            $form['fields']->email_subject = [
                "title" => Registry::load('strings')->email_subject, "tag" => 'input', "type" => "text", "class" => 'field',
            ];

            $form['fields']->email_heading = [
                "title" => Registry::load('strings')->email_heading, "tag" => 'input', "type" => "text", "class" => 'field',
            ];

            $form['fields']->email_content = [
                "title" => Registry::load('strings')->email_content, "tag" => 'textarea', "class" => 'field',
            ];

            $form['fields']->email_button_label = [
                "title" => Registry::load('strings')->email_button_label, "tag" => 'input', "type" => "text", "class" => 'field',
            ];

            foreach ($email_contents as $email_content) {
                if ($email_content['string_constant'] === $email_category.'_subject') {
                    $form['fields']->email_subject['value'] = $email_content['string_value'];
                } else if ($email_content['string_constant'] === $email_category.'_heading') {
                    $form['fields']->email_heading['value'] = $email_content['string_value'];
                } else if ($email_content['string_constant'] === $email_category.'_content') {
                    $form['fields']->email_content['value'] = $email_content['string_value'];
                } else if ($email_content['string_constant'] === $email_category.'_button_label') {
                    $form['fields']->email_button_label['value'] = $email_content['string_value'];
                }
            }
        }

    }



}
?>