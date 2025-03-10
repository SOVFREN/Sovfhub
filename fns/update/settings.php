<?php

include 'fns/filters/load.php';
include 'fns/files/load.php';

$noerror = true;
$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->something_went_wrong;
$result['error_key'] = 'something_went_wrong';

if (role(['permissions' => ['super_privileges' => 'core_settings']])) {

    $result['error_message'] = Registry::load('strings')->invalid_value;
    $result['error_key'] = 'invalid_value';

    if (!isset($data['category']) || empty($data['category'])) {
        return false;
    } else {
        $data["category"] = preg_replace("/[^a-zA-Z0-9_]+/", "", $data["category"]);
    }

    if (!empty($data['category'])) {

        $columns = [
            'settings.setting_id', 'settings.setting', 'settings.options', 'settings.value', 'settings.required'
        ];

        $where['settings.category'] = $data['category'];
        $where['ORDER'] = ['settings_order' => 'ASC'];

        $settings = DB::connect()->select('settings', $columns, $where);

        foreach ($settings as $setting) {
            if (!empty($setting['required'])) {

                $required_field = $setting['setting'];

                if (!isset($data[$required_field]) || empty($data[$required_field])) {
                    $result['error_variables'][] = [$required_field];
                    $noerror = false;
                }
            }
        }

        if ($data['category'] === 'pwa_settings') {

            if (isset($_FILES['pwa_icon']['name']) && !empty($_FILES['pwa_icon']['name'])) {
                if (isImage($_FILES['pwa_icon']['tmp_name'])) {

                    $pwa_icon_info = getimagesize($_FILES["pwa_icon"]["tmp_name"]);
                    $pwa_icon_width = $pwa_icon_info[0];
                    $pwa_icon_height = $pwa_icon_info[1];

                    if ($pwa_icon_width < 510 || $pwa_icon_height < 510) {
                        $noerror = false;
                        $result['error_message'] = Registry::load('strings')->invalid_pwa_icon_dimensions;
                        $result['error_variables'][] = ['pwa_icon'];
                        $result['error_key'] = 'invalid_pwa_icon_dimensions';
                    }
                }
            }
        } else if ($data['category'] === 'cloud_storage') {
            include('fns/update/include_fn_cloud_storage_settings.php');
        }

        if ($noerror) {
            foreach ($settings as $setting) {

                $setting_id = $setting['setting_id'];
                $setting_name = $setting['setting'];
                $setting_value = $setting['value'];
                $setting_options = $setting['options'];
                $skip_check = 0;


                if (!isset($data[$setting_name])) {
                    if (!empty($setting_options) && mb_strpos($setting_options, '[multi_select]') !== false) {
                        $data[$setting_name] = array();
                    }
                }

                if (isset($data[$setting_name]) && $data[$setting_name] != $setting_value) {
                    if (!empty($setting_options) && mb_strpos($setting_options, '[multi_select]') !== false) {
                        $setting_options = str_replace('[multi_select]', '', $setting_options);
                        $setting_options = json_decode($setting_options);
                        if (count(array_intersect($data[$setting_name], $setting_options)) === count($data[$setting_name])) {
                            $skip_check = 1;
                        } else {
                            $data[$setting_name] = null;
                        }
                    } else if ($setting_options === 'select' || !empty($setting_options) && isJson($setting_options)) {

                        if ($setting_name === 'default_timezone') {
                            $setting_options = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

                        } else if ($setting_name === 'default_font') {
                            $setting_options = array();
                            $fonts = glob('assets/fonts/*');
                            foreach ($fonts as $font) {
                                $font = basename($font);
                                if ($font !== 'iconicfont') {
                                    $setting_options[] = $font;
                                }
                            }
                        } else if ($setting_name === 'default_language') {

                            $setting_options = DB::connect()->select('languages', ['languages.language_id', 'languages.name'], ["languages.language_id" => $data[$setting_name]]);
                            if (isset($setting_options[0])) {
                                $skip_check = 1;
                            } else {
                                $data[$setting_name] = null;
                            }

                        } else if ($setting_name === 'site_terms_conditions') {

                            $setting_options = DB::connect()->select('custom_pages', ['custom_pages.page_id'], ["custom_pages.page_id" => $data[$setting_name]]);
                            if (isset($setting_options[0])) {
                                $skip_check = 1;
                            } else {
                                $data[$setting_name] = null;
                            }

                        } else if ($setting_name === 'default_currency') {
                            include_once('fns/data_arrays/currencies.php');

                            $setting_options = array();

                            foreach ($currencies as $currencyCode) {
                                $setting_options[$currencyCode] = $currencyCode;
                            }

                        } else if ($setting_name === 'notification_tone') {
                            $setting_options = glob('assets/files/sound_notifications/*');
                        } else {
                            $setting_options = json_decode($setting_options);
                        }
                    } else if ($setting_options === 'number') {
                        $skip_check = 1;
                        $content = $data[$setting_name];
                        $data[$setting_name] = filter_var($content, FILTER_SANITIZE_NUMBER_INT);
                    } else if ($setting_options === 'float') {
                        $skip_check = 1;
                        $content = $data[$setting_name];
                        $data[$setting_name] = filter_var($content, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    } else if ($setting_name === 'disallowed_slugs') {

                        $skip_check = 1;

                        if (!empty($data[$setting_name])) {
                            $content = preg_split("/\r\n|\n|\r/", $data[$setting_name]);

                            if (!empty($content)) {
                                foreach ($content as $index => $slug) {
                                    $slug = sanitize_slug($slug);
                                    if (empty($slug)) {
                                        unset($content[$index]);
                                    }
                                }
                            }

                            if (!empty($content)) {
                                $data[$setting_name] = $content;
                            } else {
                                $data[$setting_name] = array();
                            }

                        } else {
                            $data[$setting_name] = array();
                        }

                    } else {
                        $skip_check = 1;
                        $content = $data[$setting_name];
                        $data[$setting_name] = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
                    }

                    if ($setting_name === 'maintenance_mode_access_code') {
                        continue;
                    }

                    if ($skip_check === 1 || !empty($data[$setting_name]) && in_array($data[$setting_name], $setting_options)) {
                        DB::connect()->update("settings", ["value" => $data[$setting_name], "updated_on" => Registry::load('current_user')->time_stamp], ["setting_id" => $setting_id]);

                        if ($setting_name === 'under_maintenance_mode') {
                            if ($data[$setting_name] === 'disable') {
                                $um_code = random_string(['length' => 8]);
                                DB::connect()->update("settings", ["value" => $um_code, "updated_on" => Registry::load('current_user')->time_stamp], ["setting" => 'maintenance_mode_access_code']);
                            } else if ($data[$setting_name] === 'enable') {
                                $um_access = Registry::load('settings')->maintenance_mode_access_code;
                                $cookie_time = time() + (86400 * 60);
                                add_cookie('um_access', $um_access, $cookie_time);
                            }
                        }

                        if ($setting_name === 'force_https') {
                            $config_file = 'include/config.php';
                            if (is_writable($config_file)) {
                                $file_contents = file_get_contents($config_file);
                                $force_https = 'false';

                                if ($data[$setting_name] === 'yes') {
                                    $force_https = 'true';
                                }

                                $file_contents = preg_replace('/\$config->force_https=(.*?);/', '$config->force_https='.$force_https.';', $file_contents);
                                file_put_contents($config_file, $file_contents);
                            }
                        }

                    }
                }
            }



            if ($data['category'] === 'notification_settings') {
                if ($data['push_notifications'] === 'firebase') {
                    include('fns/push_notification/sfn_create_firebase_files.php');
                    create_firebase_files($data);
                }
            }


            cache(['rebuild' => 'settings']);


            if ($data['category'] === 'realtime_settings') {
                if (isset($data['clear_realtime_activity_logs']) && $data['clear_realtime_activity_logs'] === 'yes') {
                    DB::connect()->delete("realtime_logs", ['realtime_logs.realtime_log_id[!]' => 0]);
                }
            }

            if ($data['category'] === 'notification_settings') {

                if (isset($_FILES['push_notification_icon']['name']) && !empty($_FILES['push_notification_icon']['name'])) {
                    if (isImage($_FILES['push_notification_icon']['tmp_name'])) {

                        $push_notification_icon = 'assets/files/defaults/push_notification_icon.png';

                        if (file_exists($push_notification_icon)) {
                            unlink($push_notification_icon);
                        }

                        if (files('upload', ['upload' => 'push_notification_icon', 'folder' => 'defaults', 'saveas' => 'push_notification_icon.png'])['result']) {
                            files('resize_img', ['resize' => 'defaults/push_notification_icon.png', 'width' => 150, 'height' => 150, 'crop' => true]);
                        }
                    }
                }
            } else if ($data['category'] === 'general_settings') {

                if (isset($data['csrf_token_feature']) && !empty($data['csrf_token_feature'])) {
                    $config_file = 'include/config.php';
                    if (is_writable($config_file)) {
                        $file_contents = file_get_contents($config_file);
                        $csrf_token = "true";

                        if ($data['csrf_token_feature'] === "disable") {
                            $csrf_token = "false";
                        }

                        $file_contents = preg_replace('/\$config->csrf_token=(.*?);/', '$config->csrf_token='.$csrf_token.';', $file_contents);
                        file_put_contents($config_file, $file_contents);
                    }
                }

                if (isset($_FILES['favicon']['name']) && !empty($_FILES['favicon']['name'])) {
                    if (isImage($_FILES['favicon']['tmp_name'])) {

                        $favicon = 'assets/files/defaults/favicon.png';

                        if (file_exists($favicon)) {
                            unlink($favicon);
                        }

                        if (files('upload', ['upload' => 'favicon', 'folder' => 'defaults', 'saveas' => 'favicon.png'])['result']) {
                            files('resize_img', ['resize' => 'defaults/favicon.png', 'width' => 48, 'height' => 48, 'crop' => true]);
                        }
                    }
                }

                if (isset($_FILES['social_share_image']['name']) && !empty($_FILES['social_share_image']['name'])) {
                    if (isImage($_FILES['social_share_image']['tmp_name'])) {

                        $social_share_image = 'assets/files/defaults/social_share_image.jpg';

                        if (file_exists($social_share_image)) {
                            unlink($social_share_image);
                        }

                        if (files('upload', ['upload' => 'social_share_image', 'folder' => 'defaults', 'saveas' => 'social_share_image.jpg'])['result']) {
                            files('resize_img', ['resize' => 'defaults/social_share_image.jpg', 'width' => 1200, 'height' => 630, 'crop' => false]);
                        }
                    }
                }

            } else if ($data['category'] === 'login_settings') {
                $samesite_cookies_attributes = ["default", "None", "Strict", "Lax"];

                if (isset($data['samesite_cookies']) && in_array($data['samesite_cookies'], $samesite_cookies_attributes)) {
                    $config_file = 'include/config.php';
                    if (is_writable($config_file)) {
                        $file_contents = file_get_contents($config_file);
                        $samesite_cookies = "'".$data['samesite_cookies']."'";

                        $file_contents = preg_replace('/\$config->samesite_cookies=(.*?);/', '$config->samesite_cookies='.$samesite_cookies.';', $file_contents);
                        file_put_contents($config_file, $file_contents);
                    }
                }

            } else if ($data['category'] === 'email_settings') {

                if (isset($_FILES['email_logo']['name']) && !empty($_FILES['email_logo']['name'])) {
                    if (isImage($_FILES['email_logo']['tmp_name'])) {

                        $email_logo = 'assets/files/logos/email_logo.png';

                        if (file_exists($email_logo)) {
                            unlink($email_logo);
                        }

                        if (files('upload', ['upload' => 'email_logo', 'folder' => 'logos', 'saveas' => 'email_logo.png'])['result']) {
                            files('resize_img', ['resize' => 'logos/email_logo.png', 'width' => 150, 'height' => 150, 'crop' => false]);
                        }
                    }
                }

            } else if ($data['category'] === 'memberships') {

                if (isset($_FILES['invoice_logo']['name']) && !empty($_FILES['invoice_logo']['name'])) {
                    if (isImage($_FILES['invoice_logo']['tmp_name'])) {

                        $invoice_logo = 'assets/files/logos/invoice_logo.png';

                        if (file_exists($invoice_logo)) {
                            unlink($invoice_logo);
                        }

                        if (files('upload', ['upload' => 'invoice_logo', 'folder' => 'logos', 'saveas' => 'invoice_logo.png'])['result']) {
                            files('resize_img', ['resize' => 'logos/invoice_logo.png', 'width' => 150, 'height' => 150, 'crop' => false]);
                        }
                    }
                }

            } else if ($data['category'] === 'pwa_settings') {

                cache(['rebuild' => 'manifest']);

                if (isset($_FILES['pwa_icon']['name']) && !empty($_FILES['pwa_icon']['name'])) {
                    if (isImage($_FILES['pwa_icon']['tmp_name'])) {

                        $pwa_icon = 'assets/files/defaults/pwa_icon.png';

                        if (file_exists($pwa_icon)) {
                            unlink($pwa_icon);
                        }

                        if (files('upload', ['upload' => 'pwa_icon', 'folder' => 'defaults', 'saveas' => 'pwa_icon.png'])['result']) {
                            files('resize_img', ['resize' => 'defaults/pwa_icon.png', 'width' => 72, 'height' => 72, 'crop' => true, 'saveas' => 'defaults/pwa_icon-72x72.png']);
                            files('resize_img', ['resize' => 'defaults/pwa_icon.png', 'width' => 96, 'height' => 96, 'crop' => true, 'saveas' => 'defaults/pwa_icon-96x96.png']);
                            files('resize_img', ['resize' => 'defaults/pwa_icon.png', 'width' => 128, 'height' => 128, 'crop' => true, 'saveas' => 'defaults/pwa_icon-128x128.png']);
                            files('resize_img', ['resize' => 'defaults/pwa_icon.png', 'width' => 144, 'height' => 144, 'crop' => true, 'saveas' => 'defaults/pwa_icon-144x144.png']);
                            files('resize_img', ['resize' => 'defaults/pwa_icon.png', 'width' => 152, 'height' => 152, 'crop' => true, 'saveas' => 'defaults/pwa_icon-152x152.png']);
                            files('resize_img', ['resize' => 'defaults/pwa_icon.png', 'width' => 192, 'height' => 192, 'crop' => true, 'saveas' => 'defaults/pwa_icon-192x192.png']);
                            files('resize_img', ['resize' => 'defaults/pwa_icon.png', 'width' => 512, 'height' => 512, 'crop' => true, 'saveas' => 'defaults/pwa_icon-512x512.png']);
                        }
                    }
                }

            } else if ($data['category'] === 'message_settings') {

                if (isset($_FILES['default_bg_group_chat']['name']) && !empty($_FILES['default_bg_group_chat']['name'])) {
                    if (isImage($_FILES['default_bg_group_chat']['tmp_name'])) {

                        if (file_exists('assets/files/groups/backgrounds/default.png')) {
                            unlink('assets/files/groups/backgrounds/default.png');
                        }

                        if (files('upload', ['upload' => 'default_bg_group_chat', 'folder' => 'groups/backgrounds/', 'saveas' => 'default.png'])['result']) {
                            files('resize_img', ['resize' => 'groups/backgrounds/default.png', 'width' => 1001, 'height' => 754, 'crop' => true]);
                        }
                    }
                }

                if (isset($_FILES['default_bg_private_chat']['name']) && !empty($_FILES['default_bg_private_chat']['name'])) {
                    if (isImage($_FILES['default_bg_private_chat']['tmp_name'])) {

                        if (file_exists('assets/files/defaults/private_chat_default_bg.png')) {
                            unlink('assets/files/defaults/private_chat_default_bg.png');
                        }

                        if (files('upload', ['upload' => 'default_bg_private_chat', 'folder' => 'defaults', 'saveas' => 'private_chat_default_bg.png'])['result']) {
                            files('resize_img', ['resize' => 'defaults/private_chat_default_bg.png', 'width' => 1001, 'height' => 754, 'crop' => true]);
                        }
                    }
                }
            }

            if ($noerror) {
                $result = array();
                $result['success'] = true;
                $result['todo'] = 'refresh';
                $result['on_refresh'] = [
                    'attributes' => [
                        'class' => 'load_form',
                        'form' => 'settings',
                        'data-category' => $data['category']
                    ]
                ];

                if ($data['category'] === 'email_settings') {
                    if (isset($data['validate_smtp']) && $data['validate_smtp'] === 'yes') {
                        $result['todo'] = 'redirect';
                        $result['redirect'] = Registry::load('config')->site_url.'validate_smtp/';
                    }
                }
            }
        }
    }
}
?>