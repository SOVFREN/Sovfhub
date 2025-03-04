<?php

if (role(['permissions' => ['super_privileges' => 'core_settings']])) {

    $form = array();

    $form['loaded'] = new stdClass();
    $form['fields'] = new stdClass();


    $form['loaded']->title = Registry::load('strings')->role_attributes;
    $form['loaded']->button = Registry::load('strings')->update;

    $form['fields']->update = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "role_attributes"
    ];

    $language_id = Registry::load('current_user')->language;

    $join = $columns = $where = null;
    $join = ["[>]language_strings(string)" => ["site_roles.string_constant" => "string_constant", "AND" => ["language_id" => $language_id]]];
    $columns = ['site_roles.site_role_id', 'string.string_value(name)'];

    $site_roles = DB::connect()->select('site_roles', $join, $columns, $where);

    $site_roles = array_column($site_roles, 'name', 'site_role_id');

    $form['fields']->default_site_role = [
        "title" => Registry::load('strings')->default_site_role, "tag" => 'select', "class" => 'field',
        "options" => $site_roles, "value" => Registry::load('site_role_attributes')->default_site_role
    ];

    $form['fields']->site_admin_role = [
        "title" => Registry::load('strings')->site_admin_role, "tag" => 'select', "class" => 'field',
        "options" => $site_roles, "value" => Registry::load('site_role_attributes')->administrators
    ];

    $form['fields']->guest_user_role = [
        "title" => Registry::load('strings')->guest_user_role, "tag" => 'select', "class" => 'field',
        "options" => $site_roles, "value" => Registry::load('site_role_attributes')->guest_users
    ];

    $form['fields']->site_unverified_user_role = [
        "title" => Registry::load('strings')->site_unverified_user_role, "tag" => 'select', "class" => 'field',
        "options" => $site_roles, "value" => Registry::load('site_role_attributes')->unverified_users
    ];

    $form['fields']->site_banned_user_role = [
        "title" => Registry::load('strings')->site_banned_user_role, "tag" => 'select', "class" => 'field',
        "options" => $site_roles, "value" => Registry::load('site_role_attributes')->banned_users
    ];


    $join = $columns = $where = null;
    $join = ["[>]language_strings(string)" => ["group_roles.string_constant" => "string_constant", "AND" => ["language_id" => $language_id]]];
    $columns = ['group_roles.group_role_id', 'string.string_value(name)'];

    $group_roles = DB::connect()->select('group_roles', $join, $columns, $where);

    $group_roles = array_column($group_roles, 'name', 'group_role_id');

    $form['fields']->default_group_role = [
        "title" => Registry::load('strings')->default_group_role, "tag" => 'select', "class" => 'field',
        "options" => $group_roles, "value" => Registry::load('group_role_attributes')->default_group_role
    ];

    $form['fields']->group_admin_role = [
        "title" => Registry::load('strings')->group_admin_role, "tag" => 'select', "class" => 'field',
        "options" => $group_roles, "value" => Registry::load('group_role_attributes')->administrators
    ];

    $form['fields']->group_moderator_role = [
        "title" => Registry::load('strings')->group_moderator_role, "tag" => 'select', "class" => 'field',
        "options" => $group_roles, "value" => Registry::load('group_role_attributes')->moderators
    ];

    $form['fields']->group_banned_user_role = [
        "title" => Registry::load('strings')->group_banned_user_role, "tag" => 'select', "class" => 'field',
        "options" => $group_roles, "value" => Registry::load('group_role_attributes')->banned_users
    ];



}
?>