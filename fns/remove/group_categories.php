<?php

include_once 'fns/filters/load.php';
include_once 'fns/files/load.php';
include_once 'fns/cloud_storage/load.php';

$result = array();
$noerror = true;

$result['success'] = false;
$result['error_message'] = Registry::load('strings')->something_went_wrong;
$result['error_key'] = 'something_went_wrong';
$group_category_ids = array();
$delete_files = array();
$irremovable = [1];


if ($force_request || role(['permissions' => ['super_privileges' => 'manage_group_categories']])) {

    if (isset($data['group_category_id'])) {
        if (!is_array($data['group_category_id'])) {
            $data["group_category_id"] = filter_var($data["group_category_id"], FILTER_SANITIZE_NUMBER_INT);
            $group_category_ids[] = $data["group_category_id"];
        } else {
            $group_category_ids = array_filter($data["group_category_id"], 'ctype_digit');
        }
    }

    if (!empty($group_category_ids)) {
        $group_category_ids = array_diff($group_category_ids, $irremovable);
    }

    if (!empty($group_category_ids)) {
        $columns = $join = $where = null;

        $columns = [
            'group_categories.group_category_id',
            'group_categories.group_category_image'
        ];

        $where["group_categories.group_category_id"] = $group_category_ids;

        $group_categories = DB::connect()->select('group_categories', $columns, $where);

        foreach ($group_categories as $group_category) {

            if (!empty($group_category['group_category_image']) && basename($group_category['group_category_image']) !== 'default.png') {
                if (file_exists($group_category['group_category_image'])) {
                    unlink($group_category['group_category_image']);
                }

                $delete_files[] = $group_category['group_category_image'];
            }
        }


        DB::connect()->update("groups", ["group_category_id" => 1], ["group_category_id" => $group_category_ids]);

        DB::connect()->delete("group_categories", ["group_category_id" => $group_category_ids]);

        if (!DB::connect()->error) {


            if (!empty($delete_files)) {
                if (Registry::load('settings')->cloud_storage !== 'disable') {
                    cloud_storage_module(['delete_files' => $delete_files]);
                }
            }

            $result = array();
            $result['success'] = true;
            $result['todo'] = 'reload';
            $result['reload'] = 'group_categories';
        } else {
            $result['error_message'] = Registry::load('strings')->something_went_wrong;
            $result['error_key'] = 'something_went_wrong';
        }
    }

}
?>