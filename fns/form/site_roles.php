<?php

$form = array();

if (role(['permissions' => ['site_roles' => ['create', 'edit']], 'condition' => 'OR'])) {


    $form['loaded'] = new stdClass();
    $todo = 'add';
    $language_id = Registry::load('current_user')->language;

    $form['fields'] = new stdClass();

    if (isset($load["site_role_id"])) {

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
            'site_roles.site_role_id', 'site_roles.permissions',
            'string.string_value(name)', 'site_roles.disabled',
            'site_roles.site_role_attribute', 'site_roles.role_hierarchy'
        ];

        $join["[>]language_strings(string)"] = ["site_roles.string_constant" => "string_constant", "AND" => ["language_id" => $language_id]];

        $where["site_roles.site_role_id"] = $load["site_role_id"];
        $where["LIMIT"] = 1;

        $siterole = DB::connect()->select('site_roles', $join, $columns, $where);

        if (!isset($siterole[0])) {
            return false;
        } else {
            $siterole = $siterole[0];
        }

        $form['fields']->site_role_id = [
            "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => $load["site_role_id"]
        ];
        $form['loaded']->title = Registry::load('strings')->edit_site_role;
        $form['loaded']->button = Registry::load('strings')->update;
    } else {
        $form['loaded']->title = Registry::load('strings')->create_site_role;
        $form['loaded']->button = Registry::load('strings')->create;
    }

    $form['fields']->$todo = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => 'site_roles'
    ];

    if (isset($load["site_role_id"])) {

        $form['fields']->language_id = [
            "title" => Registry::load('strings')->language, "tag" => 'select', "class" => 'field',
        ];

        $form['fields']->language_id["class"] = 'field switch_form';

        if (isset($load["language_id"]) && !empty($load["language_id"])) {
            $form['fields']->language_id['value'] = $language_id;
        }

        $form['fields']->language_id["parent_attributes"] = [
            "form" => "site_roles",
            "data-site_role_id" => $load["site_role_id"],
        ];

        foreach ($languages as $language) {
            $language_identifier = $language['language_id'];
            $form['fields']->language_id['options'][$language_identifier] = $language['name'];
        }

        $form['fields']->identifier = [
            "title" => Registry::load('strings')->identifier, "tag" => 'input', "type" => 'text', "class" => 'field',
            "attributes" => ["disabled" => "disabled"],
            "value" => $load["site_role_id"],
        ];

    }


    $form['fields']->name = [
        "title" => Registry::load('strings')->role_name, "tag" => 'input', "type" => 'text', "class" => 'field', "placeholder" => Registry::load('strings')->role_name
    ];

    $form['fields']->badge = [
        "title" => Registry::load('strings')->badge, "tag" => 'input', "type" => 'file', "class" => 'field filebrowse',
        "accept" => 'image/png,image/x-png,image/gif,image/jpeg'
    ];

    $form['fields']->name_color = [
        "title" => Registry::load('strings')->name_color, "tag" => 'input', "type" => 'color', "class" => 'field'
    ];

    $form['fields']->role_hierarchy = [
        "title" => Registry::load('strings')->role_hierarchy, "tag" => 'input', "type" => 'number', "class" => 'field',
        'value' => 1
    ];

    $form['fields']->disabled = [
        "title" => Registry::load('strings')->disabled, "tag" => 'select', "class" => 'field'
    ];
    $form['fields']->disabled['options'] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no,
    ];

    $form['fields']->load_profile_on_page_load = [
        "title" => Registry::load('strings')->load_profile_on_page_load, "tag" => 'select', "class" => 'field'
    ];
    $form['fields']->load_profile_on_page_load['options'] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no,
    ];


    $form['fields']->left_panel_content_on_page_load = [
        "title" => Registry::load('strings')->left_panel_content_on_page_load, "tag" => 'select', "class" => 'field',
    ];

    $form['fields']->left_panel_content_on_page_load['options'] = [
        "groups" => Registry::load('strings')->groups,
        "group_categories" => Registry::load('strings')->group_categories,
        "online_users" => Registry::load('strings')->online_users,
        "private_conversations" => Registry::load('strings')->private_conversations,
        "site_notifications" => Registry::load('strings')->site_notifications,
        "site_users" => Registry::load('strings')->site_users,
        "friends" => Registry::load('strings')->friends,
        "nearby_users" => Registry::load('strings')->nearby_users,
    ];

    $form['fields']->main_panel_content_on_page_load = [
        "title" => Registry::load('strings')->main_panel_content_on_page_load, "tag" => 'select', "class" => 'field',
    ];

    $form['fields']->main_panel_content_on_page_load['options'] = [
        "welcome_screen" => Registry::load('strings')->welcome_screen,
        "statistics" => Registry::load('strings')->statistics,
    ];

    $form['fields']->site_notifications = [
        "title" => Registry::load('strings')->site_notifications, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->site_notifications['options'] = [
        "view" => Registry::load('strings')->view,
        "delete" => Registry::load('strings')->delete
    ];


    $form['fields']->hide_groups_on_group_url = [
        "title" => Registry::load('strings')->hide_groups_on_group_url, "tag" => 'select', "class" => 'field'
    ];
    $form['fields']->hide_groups_on_group_url['options'] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no,
    ];


    $form['fields']->skip_text_moderation = [
        "title" => Registry::load('strings')->skip_text_moderation, "tag" => 'select', "class" => 'field'
    ];
    $form['fields']->skip_text_moderation['options'] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no,
    ];


    $form['fields']->skip_image_moderation = [
        "title" => Registry::load('strings')->skip_image_moderation, "tag" => 'select', "class" => 'field'
    ];
    $form['fields']->skip_image_moderation['options'] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no,
    ];

    $form['fields']->groups = [
        "title" => Registry::load('strings')->groups, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->groups['options'] = [

        "view_public_groups" => Registry::load('strings')->view_public_groups,
        "view_secret_groups" => Registry::load('strings')->view_secret_groups,
        "view_password_protected_groups" => Registry::load('strings')->view_password_protected_groups,
        "view_joined_groups" => Registry::load('strings')->view_joined_groups,

        "create_groups" => Registry::load('strings')->create_groups,
        "create_unleavable_group" => Registry::load('strings')->create_unleavable_group,
        "create_secret_group" => Registry::load('strings')->create_secret_group,
        "create_protected_group" => Registry::load('strings')->create_protected_group,
        "create_video_chat_groups" => Registry::load('strings')->create_video_chat_groups,
        "create_audio_chat_groups" => Registry::load('strings')->create_audio_chat_groups,

        "video_chat" => Registry::load('strings')->video_chat,
        "audio_chat" => Registry::load('strings')->audio_chat,
        "set_group_slug" => Registry::load('strings')->set_group_slug,
        "set_group_category" => Registry::load('strings')->set_group_category,
        "pin_groups" => Registry::load('strings')->pin_groups,
        "set_auto_join_groups" => Registry::load('strings')->set_auto_join_groups,
        "set_participant_settings" => Registry::load('strings')->set_participant_settings,
        "set_default_group_role_within_group" => Registry::load('strings')->set_default_group_role_within_group,

        "add_meta_tags" => Registry::load('strings')->add_meta_tags,
        "set_cover_pic" => Registry::load('strings')->set_cover_pic,
        "set_custom_background" => Registry::load('strings')->set_custom_background,

        "download_attachments" => Registry::load('strings')->download_attachments,
        "typing_indicator" => Registry::load('strings')->typing_indicator,
        "mention_users" => Registry::load('strings')->mention_users,
        "mention_everyone" => Registry::load('strings')->mention_everyone,
        "reply_messages" => Registry::load('strings')->reply_messages,
        "forward_messages" => Registry::load('strings')->forward_messages,
        "check_read_receipts" => Registry::load('strings')->check_read_receipts,
        "join_group" => Registry::load('strings')->join_group,
        "leave_group" => Registry::load('strings')->leave_group,
        "invite_users" => Registry::load('strings')->invite_users,
        "add_site_members" => Registry::load('strings')->add_site_members,

        "view_reactions" => Registry::load('strings')->view_reactions,
        "react_messages" => Registry::load('strings')->react_messages,

        "send_message" => Registry::load('strings')->send_message,
        "send_audio_message" => Registry::load('strings')->send_audio_message,
        "attach_files" => Registry::load('strings')->attach_files,
        "attach_from_storage" => Registry::load('strings')->attach_from_storage,
        "attach_gifs" => Registry::load('strings')->attach_gifs,
        "search_share_videos" => Registry::load('strings')->search_share_videos,
        "attach_stickers" => Registry::load('strings')->attach_stickers,
        "share_screenshot" => Registry::load('strings')->share_screenshot,
        "preview_pdf_files" => Registry::load('strings')->preview_pdf_files,
        "share_location" => Registry::load('strings')->share_location,
        "allow_sharing_links" => Registry::load('strings')->allow_sharing_links,
        "allow_sharing_email_addresses" => Registry::load('strings')->allow_sharing_email_addresses,
        "generate_link_preview" => Registry::load('strings')->generate_link_preview,
        "clear_chat_history" => Registry::load('strings')->clear_chat_history,
        "export_chat" => Registry::load('strings')->export_chat,
        "embed_group" => Registry::load('strings')->embed_group,
        "send_as_another_user" => Registry::load('strings')->send_as_another_user,
        "super_privileges" => Registry::load('strings')->super_privileges,
    ];


    $form['fields']->default_group_visibility = [
        "title" => Registry::load('strings')->default_group_visibility, "tag" => 'select', "class" => 'field'
    ];

    $form['fields']->default_group_visibility['options'] = [
        "visible" => Registry::load('strings')->visible,
        "hidden" => Registry::load('strings')->hidden,
    ];

    $form['fields']->group_create_limit = [
        "title" => Registry::load('strings')->group_create_limit, "tag" => 'input', "type" => 'number', "class" => 'field',
        "value" => 500
    ];

    $columns = ['group_categories.group_category_id', 'group_categories.category_name'];
    $where = ['group_categories.disabled[!]' => 1];

    $group_categories = DB::connect()->select('group_categories', $columns, $where);
    $group_categories = array_column($group_categories, 'category_name', 'group_category_id');

    $form['fields']->default_group_category = [
        "title" => Registry::load('strings')->default_group_category, "tag" => 'select', "class" => 'field',
        "options" => $group_categories,
    ];

    $form['fields']->friend_system = [
        "title" => Registry::load('strings')->friend_system, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->friend_system['options'] = [
        "view_friends" => Registry::load('strings')->view_friends,
        "send_requests" => Registry::load('strings')->send_requests,
        "receive_requests" => Registry::load('strings')->receive_requests,
    ];

    $form['fields']->private_conversations = [
        "title" => Registry::load('strings')->private_conversations, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->private_conversations['options'] = [
        "super_privileges" => Registry::load('strings')->super_privileges,
        "initiate_private_chat" => Registry::load('strings')->initiate_private_chat,
        "view_private_chats" => Registry::load('strings')->view_private_chats,
        "video_chat" => Registry::load('strings')->video_chat,
        "audio_chat" => Registry::load('strings')->audio_chat,
        "send_message" => Registry::load('strings')->send_message,
        "message_non_friends" => Registry::load('strings')->message_non_friends,
        "send_audio_message" => Registry::load('strings')->send_audio_message,
        "view_reactions" => Registry::load('strings')->view_reactions,
        "react_messages" => Registry::load('strings')->react_messages,
        "attach_files" => Registry::load('strings')->attach_files,
        "attach_from_storage" => Registry::load('strings')->attach_from_storage,
        "attach_gifs" => Registry::load('strings')->attach_gifs,
        "search_share_videos" => Registry::load('strings')->search_share_videos,
        "attach_stickers" => Registry::load('strings')->attach_stickers,
        "share_screenshot" => Registry::load('strings')->share_screenshot,
        "preview_pdf_files" => Registry::load('strings')->preview_pdf_files,
        "share_location" => Registry::load('strings')->share_location,
        "allow_sharing_links" => Registry::load('strings')->allow_sharing_links,
        "allow_sharing_email_addresses" => Registry::load('strings')->allow_sharing_email_addresses,
        "generate_link_preview" => Registry::load('strings')->generate_link_preview,
        "typing_indicator" => Registry::load('strings')->typing_indicator,
        "reply_messages" => Registry::load('strings')->reply_messages,
        "check_read_receipts" => Registry::load('strings')->check_read_receipts,
        "edit_own_message" => Registry::load('strings')->edit_own_message,
        "delete_own_message" => Registry::load('strings')->delete_own_message,
        "download_attachments" => Registry::load('strings')->download_attachments,
        "export_chat" => Registry::load('strings')->export_chat,
        "clear_chat_history" => Registry::load('strings')->clear_chat_history,
    ];

    $form['fields']->pm_only_specific_roles = [
        "title" => Registry::load('strings')->pm_only_specific_roles, "tag" => 'select', "class" => 'field toggle_form_fields'
    ];

    $form['fields']->pm_only_specific_roles["attributes"] = [
        "hide_field" => "choose_roles_pm",
        "show_fields" => "yes|choose_roles_pm"
    ];

    $form['fields']->pm_only_specific_roles['options'] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no,
    ];

    $language_id = Registry::load('current_user')->language;

    $join = ["[>]language_strings(string)" => ["site_roles.string_constant" => "string_constant", "AND" => ["language_id" => $language_id]]];
    $columns = ['site_roles.site_role_id', 'string.string_value(name)'];
    $where = ['site_role_attribute[!]' => 'banned_users'];

    $site_roles = DB::connect()->select('site_roles', $join, $columns, $where);

    $site_roles = array_column($site_roles, 'name', 'site_role_id');

    $form['fields']->pm_restricted_roles = [
        "title" => Registry::load('strings')->choose_site_roles, "tag" => 'checkbox', "class" => 'field choose_roles_pm'
    ];

    $form['fields']->pm_restricted_roles['options'] = $site_roles;



    $form['fields']->group_join_limit = [
        "title" => Registry::load('strings')->group_join_limit, "tag" => 'input', "type" => 'number', "class" => 'field', "value" => 100
    ];

    $form['fields']->flood_control_time_difference = [
        "title" => Registry::load('strings')->flood_control_time_difference, "tag" => 'input', "type" => 'number', "class" => 'field',
        "value" => 20
    ];

    $form['fields']->daily_send_limit_group_messages = [
        "title" => Registry::load('strings')->daily_send_limit_group_messages.' '.Registry::load('strings')->zero_equals_unlimited,
        "tag" => 'input', "type" => 'number', "class" => 'field',
        "value" => 0
    ];

    $form['fields']->daily_send_limit_private_messages = [
        "title" => Registry::load('strings')->daily_send_limit_private_messages.' '.Registry::load('strings')->zero_equals_unlimited,
        "tag" => 'input', "type" => 'number', "class" => 'field',
        "value" => 0
    ];

    $form['fields']->edit_message_time_limit = [
        "title" => Registry::load('strings')->edit_message_time_limit, "tag" => 'input', "type" => 'number', "class" => 'field',
        "value" => 10
    ];

    $form['fields']->delete_message_time_limit = [
        "title" => Registry::load('strings')->delete_message_time_limit, "tag" => 'input', "type" => 'number', "class" => 'field',
        "value" => 10
    ];

    $form['fields']->storage = [
        "title" => Registry::load('strings')->storage, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->storage['options'] = [
        "access_storage" => Registry::load('strings')->access_storage,
        "upload_files" => Registry::load('strings')->upload_files,
        "download_files" => Registry::load('strings')->download_files,
        "delete_files" => Registry::load('strings')->delete_files,
        "super_privileges" => Registry::load('strings')->super_privileges,
    ];


    $form['fields']->max_file_upload_size = [
        "title" => Registry::load('strings')->max_file_upload_size, "tag" => 'input', "type" => 'number', "class" => 'field', "value" => 500
    ];


    $form['fields']->maximum_storage_space = [
        "title" => Registry::load('strings')->maximum_storage_space, "tag" => 'input', "type" => 'number', "class" => 'field', "value" => 500
    ];

    $form['fields']->allowed_file_formats = [
        "title" => Registry::load('strings')->allowed_file_formats, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->allowed_file_formats['options'] = [
        "image_files" => Registry::load('strings')->image_files,
        "video_files" => Registry::load('strings')->video_files,
        "audio_files" => Registry::load('strings')->audio_files,
        "documents" => Registry::load('strings')->documents,
        "all_file_formats" => Registry::load('strings')->all_file_formats,
    ];

    $form['fields']->site_users = [
        "title" => Registry::load('strings')->site_users, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->site_users['options'] = [
        "block_users" => Registry::load('strings')->block_users,
        "ignore_users" => Registry::load('strings')->ignore_users,
        "create_user" => Registry::load('strings')->create_user,
        "import_users" => Registry::load('strings')->import_users,
        "generate_fake_users" => Registry::load('strings')->generate_fake_users,
        "set_fake_online_users" => Registry::load('strings')->set_fake_online_users,
        "edit_users" => Registry::load('strings')->edit_users,
        "delete_users" => Registry::load('strings')->delete_users,
        "approve_users" => Registry::load('strings')->approve_users,
        "ban_users_from_site" => Registry::load('strings')->ban_users_from_site,
        "unban_users_from_site" => Registry::load('strings')->unban_users_from_site,
        "view_site_users" => Registry::load('strings')->view_site_users,
        "view_online_users" => Registry::load('strings')->view_online_users,
        "view_nearby_users" => Registry::load('strings')->view_nearby_users,
        "view_invisible_users" => Registry::load('strings')->view_invisible_users,
        "ban_ip_addresses" => Registry::load('strings')->ban_ip_addresses,
        "unban_ip_addresses" => Registry::load('strings')->unban_ip_addresses,
        "manage_user_access_logs" => Registry::load('strings')->manage_user_access_logs,
        "login_as_another_user" => Registry::load('strings')->login_as_another_user,
        "advanced_user_searches" => Registry::load('strings')->advanced_user_searches,
    ];

    $form['fields']->profile = [
        "title" => Registry::load('strings')->profile, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->profile['options'] = [
        "edit_profile" => Registry::load('strings')->edit_profile,
        "change_full_name" => Registry::load('strings')->change_full_name,
        "change_username" => Registry::load('strings')->change_username,
        "change_email_address" => Registry::load('strings')->change_email_address,
        "change_password" => Registry::load('strings')->change_password,
        "change_avatar" => Registry::load('strings')->change_avatar,
        "set_timezone" => Registry::load('strings')->set_timezone,
        "upload_custom_avatar" => Registry::load('strings')->upload_custom_avatar,
        "set_cover_pic" => Registry::load('strings')->set_cover_pic,
        "set_custom_background" => Registry::load('strings')->set_custom_background,
        "go_offline" => Registry::load('strings')->go_offline,
        "view_email_address" => Registry::load('strings')->view_email_address,
        "view_phone_number" => Registry::load('strings')->view_phone_number,
        "view_profile_url" => Registry::load('strings')->view_profile_url,
        "switch_languages" => Registry::load('strings')->switch_languages,
        "switch_color_scheme" => Registry::load('strings')->switch_color_scheme,
        "disable_private_messages" => Registry::load('strings')->disable_private_messages,
        "deactivate_account" => Registry::load('strings')->deactivate_account,
        "delete_account" => Registry::load('strings')->delete_account,
    ];
    $form['fields']->site_roles = [
        "title" => Registry::load('strings')->site_roles, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->site_roles['options'] = [
        "create" => Registry::load('strings')->create,
        "view" => Registry::load('strings')->view,
        "edit" => Registry::load('strings')->edit,
        "delete" => Registry::load('strings')->delete,
    ];

    $form['fields']->memberships = [
        "title" => Registry::load('strings')->memberships, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->memberships['options'] = [
        "view_membership_info" => Registry::load('strings')->view_membership_info,
        "enroll_membership" => Registry::load('strings')->enroll_membership,
        "view_personal_transactions" => Registry::load('strings')->view_personal_transactions,
        "download_invoice" => Registry::load('strings')->download_invoice,
        "view_site_transactions" => Registry::load('strings')->view_site_transactions,
        "edit_site_transactions" => Registry::load('strings')->edit_site_transactions,
    ];

    $form['fields']->bank_transfer_receipts = [
        "title" => Registry::load('strings')->bank_transfer_receipts, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->bank_transfer_receipts['options'] = [
        "view" => Registry::load('strings')->view,
        "validate" => Registry::load('strings')->validate,
        "delete" => Registry::load('strings')->delete,
    ];

    $form['fields']->membership_packages = [
        "title" => Registry::load('strings')->membership_packages, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->membership_packages['options'] = [
        "create" => Registry::load('strings')->create,
        "view" => Registry::load('strings')->view,
        "edit" => Registry::load('strings')->edit,
        "delete" => Registry::load('strings')->delete,
    ];

    $form['fields']->group_roles = [
        "title" => Registry::load('strings')->group_roles, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->group_roles['options'] = [
        "create" => Registry::load('strings')->create,
        "view" => Registry::load('strings')->view,
        "edit" => Registry::load('strings')->edit,
        "delete" => Registry::load('strings')->delete,
    ];

    $form['fields']->custom_fields = [
        "title" => Registry::load('strings')->custom_fields, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->custom_fields['options'] = [
        "create" => Registry::load('strings')->create,
        "view" => Registry::load('strings')->view,
        "edit" => Registry::load('strings')->edit,
        "delete" => Registry::load('strings')->delete,
    ];

    $form['fields']->stickers = [
        "title" => Registry::load('strings')->stickers, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->stickers['options'] = [
        "create" => Registry::load('strings')->create,
        "view" => Registry::load('strings')->view,
        "edit" => Registry::load('strings')->edit,
        "delete" => Registry::load('strings')->delete,
    ];

    $form['fields']->custom_pages = [
        "title" => Registry::load('strings')->custom_pages, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->custom_pages['options'] = [
        "create" => Registry::load('strings')->create,
        "view" => Registry::load('strings')->view,
        "edit" => Registry::load('strings')->edit,
        "delete" => Registry::load('strings')->delete,
    ];

    $form['fields']->custom_menu = [
        "title" => Registry::load('strings')->custom_menu, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->custom_menu['options'] = [
        "create" => Registry::load('strings')->create,
        "view" => Registry::load('strings')->view,
        "edit" => Registry::load('strings')->edit,
        "delete" => Registry::load('strings')->delete,
    ];

    $form['fields']->avatars = [
        "title" => Registry::load('strings')->avatars, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->avatars['options'] = [
        "upload" => Registry::load('strings')->upload,
        "view" => Registry::load('strings')->view,
        "delete" => Registry::load('strings')->delete,
    ];

    $form['fields']->languages = [
        "title" => Registry::load('strings')->languages, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->languages['options'] = [
        "create" => Registry::load('strings')->create,
        "view" => Registry::load('strings')->view,
        "edit" => Registry::load('strings')->edit,
        "delete" => Registry::load('strings')->delete,
        "export" => Registry::load('strings')->export,
    ];

    $form['fields']->social_login_providers = [
        "title" => Registry::load('strings')->social_login_providers, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->social_login_providers['options'] = [
        "add" => Registry::load('strings')->add,
        "view" => Registry::load('strings')->view,
        "edit" => Registry::load('strings')->edit,
        "delete" => Registry::load('strings')->delete,
    ];

    $form['fields']->audio_player = [
        "title" => Registry::load('strings')->audio_player, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->audio_player['options'] = [
        "listen_music" => Registry::load('strings')->listen_music,
        "add" => Registry::load('strings')->add,
        "view" => Registry::load('strings')->view,
        "edit" => Registry::load('strings')->edit,
        "delete" => Registry::load('strings')->delete,
    ];

    $form['fields']->site_adverts = [
        "title" => Registry::load('strings')->site_adverts, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->site_adverts['options'] = [
        "ad_free_account" => Registry::load('strings')->ad_free_account,
        "create" => Registry::load('strings')->create,
        "view" => Registry::load('strings')->view,
        "edit" => Registry::load('strings')->edit,
        "delete" => Registry::load('strings')->delete,
    ];

    $form['fields']->badges = [
        "title" => Registry::load('strings')->badges, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->badges['options'] = [
        "assign" => Registry::load('strings')->assign,
        "create" => Registry::load('strings')->create,
        "view" => Registry::load('strings')->view,
        "edit" => Registry::load('strings')->edit,
        "delete" => Registry::load('strings')->delete,
    ];

    $form['fields']->complaints = [
        "title" => Registry::load('strings')->complaints, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->complaints['options'] = [
        "report" => Registry::load('strings')->report,
        "track_status" => Registry::load('strings')->track_status,
        "review_complaints" => Registry::load('strings')->review_complaints,
        "delete_complaints" => Registry::load('strings')->delete_complaints,
    ];

    $form['fields']->super_privileges = [
        "title" => Registry::load('strings')->super_privileges, "tag" => 'checkbox', "class" => 'field'
    ];

    $form['fields']->super_privileges['options'] = [
        "monitor_group_chats" => Registry::load('strings')->monitor_group_chats,
        "monitor_private_chats" => Registry::load('strings')->monitor_private_chats,
        "view_statistics" => Registry::load('strings')->view_statistics,
        "core_settings" => Registry::load('strings')->core_settings,
        "manage_group_categories" => Registry::load('strings')->manage_group_categories,
        "manage_payment_gateways" => Registry::load('strings')->manage_payment_gateways,
        "customizer" => Registry::load('strings')->customizer,
        "slideshows" => Registry::load('strings')->slideshows,
        "group_headers" => Registry::load('strings')->group_headers,
        "header_footer" => Registry::load('strings')->headers_footers,
        "firewall" => Registry::load('strings')->firewall,
        "email_validator" => Registry::load('strings')->email_validator,
        "link_filter" => Registry::load('strings')->link_filter,
        "profanity_filter" => Registry::load('strings')->profanity_filter,
        "message_scheduler" => Registry::load('strings')->message_scheduler,
        "cron_jobs" => Registry::load('strings')->cron_jobs,
    ];


    if (isset($load["site_role_id"])) {

        $disabled = 'no';

        if ((int)$siterole['disabled'] === 1) {
            $disabled = 'yes';
        }

        $form['fields']->disabled["value"] = $disabled;

        $permissions = get_object_vars(json_decode($siterole['permissions']));

        unset($form['fields']->name["placeholder"]);
        $form['fields']->name["value"] = $siterole['name'];
        $form['fields']->role_hierarchy["value"] = $siterole['role_hierarchy'];

        foreach ($permissions as $permission => $allowed_permissions) {
            if (isset($form['fields']->$permission)) {
                $form['fields']->$permission["value"] = $allowed_permissions;
            }
        }

    }

}

?>