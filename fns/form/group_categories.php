<?php

if (role(['permissions' => ['super_privileges' => 'manage_group_categories']])) {

    $todo = 'add';

    $form['loaded'] = new stdClass();
    $form['fields'] = new stdClass();

    if (isset($load["group_category_id"])) {

        $load["group_category_id"] = filter_var($load["group_category_id"], FILTER_SANITIZE_NUMBER_INT);

        if (empty($load["group_category_id"])) {
            return;
        }

        $columns = [
            'group_categories.group_category_id', 'group_categories.category_name',
            'group_categories.disabled', 'group_categories.group_category_image',
            'group_categories.category_order'
        ];

        $where["group_categories.group_category_id"] = $load["group_category_id"];
        $where["LIMIT"] = 1;

        $group_category = DB::connect()->select('group_categories', $columns, $where);

        if (isset($group_category[0])) {
            $group_category = $group_category[0];
        } else {
            return;
        }

        $form['fields']->group_category_id = [
            "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => $load["group_category_id"]
        ];

        $todo = 'update';
        $form['loaded']->title = Registry::load('strings')->edit_category;
        $form['loaded']->button = Registry::load('strings')->update;


        $form['fields']->group_category_identifier = [
            "title" => Registry::load('strings')->group_category_id, "tag" => 'input', "type" => 'text', "class" => 'field',
            "attributes" => ["disabled" => "disabled"],
            "value" => $load["group_category_id"],
        ];

    } else {
        $form['loaded']->title = Registry::load('strings')->create_category;
        $form['loaded']->button = Registry::load('strings')->create;
    }

    $form['fields']->$todo = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "group_categories"
    ];

    $form['fields']->category_name = [
        "title" => Registry::load('strings')->category_name, "tag" => 'input', "type" => "text",
        "class" => 'field', "placeholder" => Registry::load('strings')->category_name,
        "required" => true
    ];

    $form['fields']->category_image = [
        "title" => Registry::load('strings')->category_image, "tag" => 'input', "type" => 'file', "class" => 'field filebrowse',
        "accept" => 'image/png,image/x-png,image/gif,image/jpeg,image/webp'
    ];

    $form['fields']->category_order = [
        "title" => Registry::load('strings')->category_order, "tag" => 'input', "type" => 'number', "class" => 'field',
        "placeholder" => Registry::load('strings')->category_order,
    ];

    $form['fields']->disabled = [
        "title" => Registry::load('strings')->disabled, "tag" => 'select', "class" => 'field'
    ];
    $form['fields']->disabled['options'] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no,
    ];


    if (isset($load["group_category_id"])) {
        $form['fields']->category_name["value"] = $group_category['category_name'];
        $form['fields']->category_order["value"] = $group_category['category_order'];


        $disabled = 'no';

        if ((int)$group_category['disabled'] === 1) {
            $disabled = 'yes';
        }

        $form['fields']->disabled["value"] = $disabled;
    }
}
?>