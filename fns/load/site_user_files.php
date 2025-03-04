<?php

if (role(['permissions' => ['storage' => 'access_storage']])) {

    include_once 'fns/filters/load.php';
    include 'fns/files/load.php';

    $output = array();
    $columns = [
        'site_users.display_name', 'site_users.email_address', 'site_users.profile_picture',
        'site_users.username'
    ];

    $pdf_file_formats = ['application/pdf', 'application/x-pdf', 'application/acrobat'];
    $image_file_formats = ['image/jpeg', 'image/png', 'image/x-png', 'image/gif', 'image/bmp', 'image/x-ms-bmp', 'image/webp'];
    $video_file_formats = ['video/mp4', 'video/mpeg', 'video/ogg', 'video/quicktime', 'video/webm'];
    $audio_file_formats = ['audio/ogg', 'audio/mpeg', 'audio/webm'];
    $unknown_file_extension = Registry::load('config')->site_url."assets/files/file_extensions/unknown.png";

    $user_id = Registry::load('current_user')->id;

    if (isset($data["user_id"])) {

        if (role(['permissions' => ['storage' => 'super_privileges']])) {

            $data["user_id"] = filter_var($data["user_id"], FILTER_SANITIZE_NUMBER_INT);

            if (!empty($data["user_id"])) {
                $user_id = $data["user_id"];
            }
        }
    }

    $where["site_users.user_id"] = $user_id;
    $where["LIMIT"] = 1;

    $user = DB::connect()->select('site_users', $columns, $where);

    if (isset($user[0])) {
        $user = $user[0];
        $i = 1;
        $output = array();
        $output['loaded'] = new stdClass();
        $output['loaded']->title = Registry::load('strings')->files;

        if (!empty($data["offset"])) {
            $output['loaded']->offset = $data["offset"];
        } else {
            $data["offset"] = 0;
        }

        if (role(['permissions' => ['storage' => 'upload_files']])) {
            if ((int)$user_id === (int)Registry::load('current_user')->id) {
                $output['todo'] = new stdClass();
                $output['todo']->class = 'upload_storage_files';
                $output['todo']->title = Registry::load('strings')->upload_files;
            }
        }

        if (role(['permissions' => ['storage' => 'delete_files']])) {
            $output['multiple_select'] = new stdClass();
            $output['multiple_select']->title = Registry::load('strings')->delete;
            $output['multiple_select']->attributes['class'] = 'ask_confirmation';
            $output['multiple_select']->attributes['data-remove'] = 'site_user_files';
            $output['multiple_select']->attributes['data-user_id'] = $user_id;
            $output['multiple_select']->attributes['multi_select'] = 'file';
            $output['multiple_select']->attributes['submit_button'] = Registry::load('strings')->yes;
            $output['multiple_select']->attributes['cancel_button'] = Registry::load('strings')->no;
            $output['multiple_select']->attributes['confirmation'] = Registry::load('strings')->confirm_action;
        }

        $output['filters'][1] = new stdClass();
        $output['filters'][1]->filter = Registry::load('strings')->all;
        $output['filters'][1]->class = 'load_aside';
        $output['filters'][1]->attributes['load'] = 'site_user_files';
        $output['filters'][1]->attributes['data-user_id'] = $user_id;

        $output['filters'][2] = new stdClass();
        $output['filters'][2]->filter = Registry::load('strings')->images;
        $output['filters'][2]->class = 'load_aside';
        $output['filters'][2]->attributes['load'] = 'site_user_files';
        $output['filters'][2]->attributes['data-user_id'] = $user_id;
        $output['filters'][2]->attributes['filter'] = 'images';

        $output['filters'][3] = new stdClass();
        $output['filters'][3]->filter = Registry::load('strings')->videos;
        $output['filters'][3]->class = 'load_aside';
        $output['filters'][3]->attributes['load'] = 'site_user_files';
        $output['filters'][3]->attributes['data-user_id'] = $user_id;
        $output['filters'][3]->attributes['filter'] = 'videos';

        $output['filters'][4] = new stdClass();
        $output['filters'][4]->filter = Registry::load('strings')->audio;
        $output['filters'][4]->class = 'load_aside';
        $output['filters'][4]->attributes['load'] = 'site_user_files';
        $output['filters'][4]->attributes['data-user_id'] = $user_id;
        $output['filters'][4]->attributes['filter'] = 'audio';

        $output['filters'][5] = new stdClass();
        $output['filters'][5]->filter = Registry::load('strings')->others;
        $output['filters'][5]->class = 'load_aside';
        $output['filters'][5]->attributes['load'] = 'site_user_files';
        $output['filters'][5]->attributes['data-user_id'] = $user_id;
        $output['filters'][5]->attributes['filter'] = 'others';


        $files = array();
        $third_party_user = false;
        $output['loaded']->offset = intval($data["offset"])+intval(Registry::load('settings')->records_per_call);

        if (Registry::load('settings')->cloud_storage !== 'disable') {
            include('fns/load/subrtn_cloud_user_files.php');
        } else {
            include('fns/load/subrtn_server_user_files.php');
        }

        if ((int)$user_id !== (int)Registry::load('current_user')->id && empty($data["offset"])) {
            $folder['file_name'] = $folder['file_basename'] = $user['display_name'];
            $folder['file_size'] = $user['username'];
            $folder['thumbnail'] = get_img_url(['from' => 'site_users/profile_pics', 'image' => $user['profile_picture'], 'gravatar' => $user['email_address']]);
            $folder['user_folder'] = true;
            $third_party_user = true;
            array_unshift($files, $folder);
        }

        foreach ($files as $file) {
            $output['content'][$i] = new stdClass();
            $output['content'][$i]->class = "file";
            $output['content'][$i]->unread = $output['content'][$i]->icon = 0;
            $output['content'][$i]->title = $file['file_name'];
            $output['content'][$i]->subtitle = $file['file_size'];
            $output['content'][$i]->identifier = $file['file_basename'];
            $output['content'][$i]->image = $file['thumbnail'];

            $option_index = 1;

            if (isset($file['user_folder'])) {
                if (role(['permissions' => ['storage' => 'super_privileges']])) {
                    $output['options'][$i][$option_index] = new stdClass();
                    $output['options'][$i][$option_index]->option = Registry::load('strings')->delete_all;
                    $output['options'][$i][$option_index]->class = 'ask_confirmation';
                    $output['options'][$i][$option_index]->attributes['data-remove'] = 'site_user_files';
                    $output['options'][$i][$option_index]->attributes['data-user_id'] = $user_id;
                    $output['options'][$i][$option_index]->attributes['data-delete_all'] = true;
                    $output['options'][$i][$option_index]->attributes['confirmation'] = Registry::load('strings')->delete_all_files_confirmation;
                    $output['options'][$i][$option_index]->attributes['submit_button'] = Registry::load('strings')->yes;
                    $output['options'][$i][$option_index]->attributes['cancel_button'] = Registry::load('strings')->no;
                    $option_index++;
                }
            } else {

                if ($third_party_user) {
                    $output['content'][$i]->class = "file sub";
                }

                if ($file['file_format'] === 'image') {
                    $output['options'][$i][$option_index] = new stdClass();
                    $output['options'][$i][$option_index]->option = Registry::load('strings')->view;
                    $output['options'][$i][$option_index]->class = 'preview_image';
                    $output['options'][$i][$option_index]->attributes['load_image'] = $file['file_path'];
                    $option_index++;
                } else if ($file['file_format'] === 'video') {
                    $output['options'][$i][$option_index] = new stdClass();
                    $output['options'][$i][$option_index]->option = Registry::load('strings')->play;
                    $output['options'][$i][$option_index]->class = 'preview_video';
                    $output['options'][$i][$option_index]->attributes['video_file'] = $file['file_path'];
                    $output['options'][$i][$option_index]->attributes['mime_type'] = $file['file_type'];
                    $option_index++;
                } else if ($file['file_format'] === 'audio') {
                    $output['options'][$i][$option_index] = new stdClass();
                    $output['options'][$i][$option_index]->option = Registry::load('strings')->play;
                    $output['options'][$i][$option_index]->class = 'preview_video';
                    $output['options'][$i][$option_index]->attributes['video_file'] = $file['file_path'];
                    $output['options'][$i][$option_index]->attributes['thumbnail'] = Registry::load('config')->site_url.'assets/files/audio_player/images/default.png';
                    $output['options'][$i][$option_index]->attributes['mime_type'] = $file['file_type'];
                    $option_index++;
                } else if ($file['file_format'] === 'pdf_file') {
                    $output['options'][$i][$option_index] = new stdClass();
                    $output['options'][$i][$option_index]->option = Registry::load('strings')->view;
                    $output['options'][$i][$option_index]->class = 'preview_pdf';
                    $output['options'][$i][$option_index]->attributes['pdf'] = $file['file_path'];
                    $option_index++;
                }



                if (isset($data["conversation_loaded"])) {
                    $data["share_files"] = true;
                }

                if (role(['permissions' => ['private_conversations' => 'attach_from_storage', 'groups' => 'attach_from_storage'], 'condition' => 'OR'])) {
                    if (isset($data["share_files"])) {
                        $output['options'][$i][$option_index] = new stdClass();
                        $output['options'][$i][$option_index]->option = Registry::load('strings')->share;
                        $output['options'][$i][$option_index]->class = 'share_file';
                        $output['options'][$i][$option_index]->attributes['file_name'] = $file['file_basename'];
                        $option_index++;
                    }
                }


                if (role(['permissions' => ['storage' => 'download_files']])) {
                    $output['options'][$i][$option_index] = new stdClass();
                    $output['options'][$i][$option_index]->option = Registry::load('strings')->download;
                    $output['options'][$i][$option_index]->class = 'download_file';
                    $output['options'][$i][$option_index]->attributes['download'] = 'file';
                    $output['options'][$i][$option_index]->attributes['data-user_id'] = $user_id;
                    $output['options'][$i][$option_index]->attributes['data-file_name'] = $file['file_basename'];
                    $option_index++;
                }

                if (role(['permissions' => ['storage' => 'delete_files']])) {
                    $output['options'][$i][$option_index] = new stdClass();
                    $output['options'][$i][$option_index]->option = Registry::load('strings')->delete;
                    $output['options'][$i][$option_index]->class = 'ask_confirmation';
                    $output['options'][$i][$option_index]->attributes['data-remove'] = 'site_user_files';
                    $output['options'][$i][$option_index]->attributes['data-user_id'] = $user_id;
                    $output['options'][$i][$option_index]->attributes['data-file'] = $file['file_basename'];
                    $output['options'][$i][$option_index]->attributes['confirmation'] = Registry::load('strings')->delete_file_confirmation;
                    $output['options'][$i][$option_index]->attributes['submit_button'] = Registry::load('strings')->yes;
                    $output['options'][$i][$option_index]->attributes['cancel_button'] = Registry::load('strings')->no;
                    $option_index++;
                }
            }

            $i++;
        }

    }
}
?>