<?php

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$group_category_id = 0;

include 'fns/filters/load.php';
include 'fns/files/load.php';
include_once('fns/cloud_storage/load.php');

if (role(['permissions' => ['super_privileges' => 'manage_group_categories']])) {

    $noerror = true;
    $disabled = 0;
    $result['success'] = false;
    $result['error_message'] = Registry::load('strings')->invalid_value;
    $result['error_key'] = 'invalid_value';
    $result['error_variables'] = [];
    $currentMonthYear = date('mY');

    if (!isset($data['category_name']) || empty($data['category_name'])) {
        $result['error_variables'][] = ['category_name'];
        $noerror = false;
    }

    if (isset($data["category_order"])) {
        $data["category_order"] = filter_var($data["category_order"], FILTER_SANITIZE_NUMBER_INT);
    }

    if (!isset($data['category_order']) || empty($data['category_order'])) {
        $result['error_variables'][] = ['category_order'];
        $noerror = false;
    }

    if (isset($data['group_category_id'])) {
        $group_category_id = filter_var($data["group_category_id"], FILTER_SANITIZE_NUMBER_INT);
    }

    if ($noerror && !empty($group_category_id)) {

        $data['category_name'] = htmlspecialchars(trim($data['category_name']), ENT_QUOTES, 'UTF-8');

        if (isset($data['disabled']) && $data['disabled'] === 'yes') {
            $disabled = 1;
        }

        $update_data = [
            "category_name" => $data['category_name'],
            "category_order" => $data['category_order'],
            "disabled" => $disabled,
            "updated_on" => Registry::load('current_user')->time_stamp,
        ];

        $where = ['group_category_id' => $group_category_id];

        DB::connect()->update("group_categories", $update_data, $where);

        if (!DB::connect()->error) {

            if (isset($_FILES['category_image']['name']) && !empty($_FILES['category_image']['name'])) {


                if (isImage($_FILES['category_image']['tmp_name'])) {

                    $extension = pathinfo($_FILES['category_image']['name'])['extension'];
                    $filename = $group_category_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.'.$extension;

                    $folder_path = 'assets/files/group_categories/'.$currentMonthYear.'/';

                    if (!file_exists($folder_path)) {
                        mkdir($folder_path, 0755, true);
                    }

                    if (files('upload', ['upload' => 'category_image', 'folder' => 'group_categories/'.$currentMonthYear, 'saveas' => $filename])['result']) {
                        files('resize_img', ['resize' => 'group_categories/'.$currentMonthYear.'/'.$filename, 'width' => 150, 'height' => 150, 'crop' => true]);
                        $group_category_image = $folder_path.$filename;


                        $group_category = DB::connect()->select("group_categories", ["group_category_image"], ["group_category_id" => $group_category_id]);

                        if (isset($group_category[0])) {
                            $group_category = $group_category[0];

                            if (!empty($group_category['group_category_image'])) {

                                $delete_image = false;

                                if (!empty($group_category['group_category_image']) && basename($group_category['group_category_image']) !== 'default.png') {
                                    $delete_image = true;
                                }

                                if ($delete_image && file_exists($group_category['group_category_image'])) {
                                    unlink($group_category['group_category_image']);
                                }

                                if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                                    cloud_storage_module(['delete_file' => $group_category['group_category_image']]);
                                }
                            }
                        }

                        DB::connect()->update("group_categories", ["group_category_image" => $group_category_image], ["group_category_id" => $group_category_id]);

                        if (Registry::load('settings')->cloud_storage !== 'disable') {
                            cloud_storage_module(['upload_file' => $group_category_image, 'delete' => true]);
                        }

                    }
                }
            }

            $result = array();
            $result['success'] = true;
            $result['todo'] = 'reload';
            $result['reload'] = 'group_categories';
        } else {
            $result['error_message'] = Registry::load('strings')->went_wrong;
            $result['error_key'] = 'something_went_wrong';
        }

    }
}

?>