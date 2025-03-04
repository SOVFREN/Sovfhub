<?php
use Medoo\Medoo;

$super_privileges = false;

if (role(['permissions' => ['super_privileges' => 'manage_group_categories']])) {
    $super_privileges = true;
}

if (Registry::load('settings')->categorize_groups === 'yes') {
    $columns = $join = $where = null;
    $columns = [
        'group_categories.group_category_id', 'group_categories.category_name',
        'group_categories.disabled', 'group_categories.group_category_image'
    ];

    $columns['total_groups'] = Medoo::raw('COUNT(<total_groups.group_id>)');
    $join["[>]groups(total_groups)"] = ["group_categories.group_category_id" => "group_category_id"];

    if (!empty($data["offset"])) {
        $data["offset"] = array_map('intval', explode(',', $data["offset"]));
        $where["group_categories.group_category_id[!]"] = $data["offset"];
    }

    if ($super_privileges && isset($data["filter"]) && $data["filter"] === 'disabled') {
        $where["group_categories.disabled"] = 1;
    } else {
        $where["group_categories.disabled[!]"] = 1;
    }

    if (!empty($data["search"])) {

        $id_search = filter_var($data["search"], FILTER_SANITIZE_NUMBER_INT);

        if (empty($id_search)) {
            $id_search = 0;
        }

        $where["AND #search_query"]["OR"] = [
            "group_categories.group_category_id[~]" => $id_search,
            "group_categories.category_name[~]" => $data["search"]
        ];
    }

    $where["LIMIT"] = Registry::load('settings')->records_per_call;
    $where["ORDER"] = ["group_categories.category_order" => "ASC"];
    $where["GROUP"] = ["group_categories.group_category_id"];

    $group_categories = DB::connect()->select('group_categories', $join, $columns, $where);

    $i = 1;
    $output = array();
    $output['loaded'] = new stdClass();
    $output['loaded']->title = Registry::load('strings')->group_categories;
    $output['loaded']->loaded = 'group_categories';
    $output['loaded']->offset = array();

    if ($super_privileges) {
        $output['filters'][1] = new stdClass();
        $output['filters'][1]->filter = Registry::load('strings')->enabled;
        $output['filters'][1]->class = 'load_aside';
        $output['filters'][1]->attributes['load'] = 'group_categories';

        $output['filters'][2] = new stdClass();
        $output['filters'][2]->filter = Registry::load('strings')->disabled;
        $output['filters'][2]->class = 'load_aside';
        $output['filters'][2]->attributes['load'] = 'group_categories';
        $output['filters'][2]->attributes['filter'] = 'disabled';
        $output['filters'][2]->attributes['skip_filter_title'] = true;
    }


    if ($super_privileges) {
        $output['todo'] = new stdClass();
        $output['todo']->class = 'load_form';
        $output['todo']->title = Registry::load('strings')->create_category;
        $output['todo']->attributes['form'] = 'group_categories';

        $output['multiple_select'] = new stdClass();
        $output['multiple_select']->title = Registry::load('strings')->delete;
        $output['multiple_select']->attributes['class'] = 'ask_confirmation';
        $output['multiple_select']->attributes['data-remove'] = 'group_categories';
        $output['multiple_select']->attributes['multi_select'] = 'group_category_id';
        $output['multiple_select']->attributes['submit_button'] = Registry::load('strings')->yes;
        $output['multiple_select']->attributes['cancel_button'] = Registry::load('strings')->no;
        $output['multiple_select']->attributes['confirmation'] = Registry::load('strings')->confirm_action;
    }

    if (!empty($data["offset"])) {
        $output['loaded']->offset = $data["offset"];
    }

    foreach ($group_categories as $group_category) {
        $output['loaded']->offset[] = $group_category['group_category_id'];
        $output['content'][$i] = new stdClass();
        $output['content'][$i]->identifier = $group_category['group_category_id'];
        $output['content'][$i]->title = $group_category['category_name'];
        $output['content'][$i]->image = get_img_url(['from' => 'group_categories', 'image' => $group_category['group_category_image']]);
        $output['content'][$i]->class = "group_category load_aside";
        $output['content'][$i]->icon = 0;
        $output['content'][$i]->unread = 0;
        $output['content'][$i]->attributes = [
            'load' => 'groups',
            'data-group_category_id' => $group_category['group_category_id'],
            'stopPropagation' => true
        ];

        if ($super_privileges && (int)$group_category['disabled'] === 1) {
            $output['content'][$i]->subtitle = Registry::load('strings')->disabled;
        } else {
            $output['content'][$i]->subtitle = $group_category['total_groups'].' '.Registry::load('strings')->groups;
        }

        if ($super_privileges) {

            $output['options'][$i][1] = new stdClass();
            $output['options'][$i][1]->option = Registry::load('strings')->edit;
            $output['options'][$i][1]->class = 'load_form';
            $output['options'][$i][1]->attributes['form'] = 'group_categories';
            $output['options'][$i][1]->attributes['data-group_category_id'] = $group_category['group_category_id'];

            $output['options'][$i][2] = new stdClass();
            $output['options'][$i][2]->option = Registry::load('strings')->delete;
            $output['options'][$i][2]->class = 'ask_confirmation';
            $output['options'][$i][2]->attributes['data-remove'] = 'group_categories';
            $output['options'][$i][2]->attributes['data-group_category_id'] = $group_category['group_category_id'];
            $output['options'][$i][2]->attributes['submit_button'] = Registry::load('strings')->yes;
            $output['options'][$i][2]->attributes['cancel_button'] = Registry::load('strings')->no;
            $output['options'][$i][2]->attributes['confirmation'] = Registry::load('strings')->confirm_action;
        }

        $i++;
    }
}
?>