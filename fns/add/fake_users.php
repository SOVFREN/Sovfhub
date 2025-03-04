<?php

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';

if (role(['permissions' => ['site_users' => 'generate_fake_users']])) {

    $localesWithNames = [
        'ar_EG' => 'Arabic (Egypt)', 'ar_JO' => 'Arabic (Jordan)', 'ar_SA' => 'Arabic (Saudi Arabia)', 'at_AT' => 'Austrian German',
        'bg_BG' => 'Bulgarian (Bulgaria)', 'bn_BD' => 'Bangla (Bangladesh)', 'cs_CZ' => 'Czech (Czech Republic)',
        'da_DK' => 'Danish (Denmark)', 'de_AT' => 'German (Austria)', 'de_CH' => 'German (Switzerland)', 'de_DE' => 'German (Germany)',
        'el_CY' => 'Greek (Cyprus)', 'el_GR' => 'Greek (Greece)', 'en_AU' => 'English (Australia)', 'en_CA' => 'English (Canada)',
        'en_GB' => 'English (United Kingdom)', 'en_HK' => 'English (Hong Kong SAR China)', 'en_IN' => 'English (India)',
        'en_NG' => 'English (Nigeria)', 'en_NZ' => 'English (New Zealand)', 'en_PH' => 'English (Philippines)', 'en_SG' => 'English (Singapore)',
        'en_UG' => 'English (Uganda)', 'en_US' => 'English (United States)', 'en_ZA' => 'English (South Africa)', 'es_AR' => 'Spanish (Argentina)',
        'es_ES' => 'Spanish (Spain)', 'es_PE' => 'Spanish (Peru)', 'es_VE' => 'Spanish (Venezuela)', 'et_EE' => 'Estonian (Estonia)',
        'fa_IR' => 'Persian (Iran)', 'fi_FI' => 'Finnish (Finland)', 'fr_BE' => 'French (Belgium)', 'fr_CA' => 'French (Canada)',
        'fr_CH' => 'French (Switzerland)', 'fr_FR' => 'French (France)', 'he_IL' => 'Hebrew (Israel)', 'hr_HR' => 'Croatian (Croatia)',
        'hu_HU' => 'Hungarian (Hungary)', 'hy_AM' => 'Armenian (Armenia)', 'id_ID' => 'Indonesian (Indonesia)', 'is_IS' => 'Icelandic (Iceland)',
        'it_CH' => 'Italian (Switzerland)', 'it_IT' => 'Italian (Italy)', 'ja_JP' => 'Japanese (Japan)', 'ka_GE' => 'Georgian (Georgia)',
        'kk_KZ' => 'Kazakh (Kazakhstan)', 'ko_KR' => 'Korean (South Korea)', 'lt_LT' => 'Lithuanian (Lithuania)',
        'lv_LV' => 'Latvian (Latvia)', 'me_ME' => 'Montenegrin (Montenegro)', 'mn_MN' => 'Mongolian (Mongolia)',
        'ms_MY' => 'Malay (Malaysia)', 'nb_NO' => 'Norwegian Bokmål (Norway)', 'ne_NP' => 'Nepali (Nepal)', 'nl_BE' => 'Dutch (Belgium)',
        'nl_NL' => 'Dutch (Netherlands)', 'pl_PL' => 'Polish (Poland)', 'pt_BR' => 'Portuguese (Brazil)', 'pt_PT' => 'Portuguese (Portugal)',
        'ro_MD' => 'Romanian (Moldova)', 'ro_RO' => 'Romanian (Romania)', 'ru_RU' => 'Russian (Russia)', 'sk_SK' => 'Slovak (Slovakia)',
        'sl_SI' => 'Slovenian (Slovenia)', 'sr_Cyrl_RS' => 'Serbian (Cyrillic, Serbia)', 'sr_Latn_RS' => 'Serbian (Latin, Serbia)',
        'sr_RS' => 'Serbian (Serbia)', 'sv_SE' => 'Swedish (Sweden)', 'th_TH' => 'Thai (Thailand)', 'tr_TR' => 'Turkish (Turkey)',
        'uk_UA' => 'Ukrainian (Ukraine)', 'vi_VN' => 'Vietnamese (Vietnam)', 'zh_CN' => 'Chinese (Simplified, China)',
        'zh_TW' => 'Chinese (Traditional, Taiwan)'
    ];


    $noerror = true;
    $disabled = 0;
    $result['success'] = false;
    $result['error_message'] = Registry::load('strings')->invalid_value;
    $result['error_key'] = 'invalid_value';
    $result['error_variables'] = [];
    $locale = 'en_GB';
    $add_profile_pic = false;
    $site_role = 1;
    $email_domain = '@testuser.test';

    if (isset($data['no_of_users'])) {
        $data["no_of_users"] = filter_var($data["no_of_users"], FILTER_SANITIZE_NUMBER_INT);
    }

    if (!isset($data['no_of_users']) || empty($data['no_of_users'])) {
        $result['error_variables'][] = ['no_of_users'];
        $noerror = false;
    }

    if (isset(Registry::load('site_role_attributes')->default_site_role)) {
        $site_role = Registry::load('site_role_attributes')->default_site_role;
    }

    if (!empty($data['no_of_users'])) {
        if ((int)$data['no_of_users'] > 50) {
            $result['error_message'] = Registry::load('strings')->max_fake_users_limit;
            $result['error_variables'][] = ['no_of_users'];
            $noerror = false;
        }
    }

    if (isset($data['locale']) && !empty($data['locale'])) {
        $locales = array_keys($localesWithNames);

        if (in_array($data['locale'], $locales)) {
            $locale = $data['locale'];
        }
    }

    if (isset($data['generate_profile_picture']) && $data['generate_profile_picture'] === 'yes') {
        $add_profile_pic = true;
    }

    if (isset($data['site_role']) && !empty($data['site_role'])) {
        $check_site_role_condition = ["site_roles.site_role_id" => $data['site_role']];

        if (Registry::load('current_user')->site_role_attribute !== 'administrators') {
            $check_site_role_condition['site_roles.role_hierarchy[<]'] = Registry::load('current_user')->role_hierarchy;
        }

        $check_site_role = DB::connect()->select('site_roles', ['site_roles.site_role_id'], $check_site_role_condition);
        if (isset($check_site_role[0])) {
            $site_role = (int)$data['site_role'];
        }
    }

    if (isset($data['email_domain']) && !empty($data['email_domain'])) {

        $data['email_domain'] = htmlspecialchars(trim($data['email_domain']), ENT_QUOTES, 'UTF-8');
        $data['email_domain'] = ltrim($data['email_domain'], '@');

        $test_email = 'test@'.$data['email_domain'];

        if (filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
            $email_domain = '@'.$data['email_domain'];
        }
    }

    if ($noerror) {
        include_once 'fns/fakerphp/autoload.php';
        include 'fns/filters/load.php';
        include 'fns/files/load.php';

        $no_of_users = (int)$data['no_of_users'];

        $faker = Faker\Factory::create($locale);

        if (!empty($no_of_users)) {

            for ($i = 0; $i < $no_of_users; $i++) {

                $name = $faker->name();
                $email = substr(md5($name), 0, 15). $email_domain;

                $username = sanitize_username($name, false);

                if (empty($username)) {
                    $username = 'user_'.random_string(['length' => 9]);
                } else if (username_exists($username)) {
                    $username = $username.'_'.random_string(['length' => 5]);
                }

                $password = random_string(['length' => 9]);

                $user_data = [
                    'add' => 'site_users',
                    'full_name' => $name,
                    'username' => $username,
                    'email_address' => $email,
                    'password' => $password,
                    'site_role' => $site_role,
                    'return' => true
                ];

                if ($add_profile_pic) {
                    $imageId = rand(15, 1000);
                    $user_data['avatarURL'] = "https://picsum.photos/150/?random=$imageId";
                }

                $user_private_data = [
                    'force_request' => true,
                    'exclude_filters_function' => true,
                    'exclude_files_function' => true
                ];


                if (isset($data['stay_online']) && $data['stay_online'] === 'yes') {
                    $user_private_data['stay_online'] = true;
                }

                add($user_data, $user_private_data);

            }
        }

        $result = array();
        $result['success'] = true;
        $result['todo'] = 'reload';
        $result['reload'] = 'site_users';
    }

}
?>