<?php
if (role(['permissions' => ['site_users' => 'generate_fake_users']])) {

    $form = array();

    $form['loaded'] = new stdClass();
    $form['fields'] = new stdClass();


    $form['loaded']->title = Registry::load('strings')->fake_users;
    $form['loaded']->button = Registry::load('strings')->add;


    $check_site_role_condition = ['site_roles.disabled' => 0];

    if (Registry::load('current_user')->site_role_attribute !== 'administrators') {
        $check_site_role_condition['site_roles.role_hierarchy[<]'] = Registry::load('current_user')->role_hierarchy;
    }

    $site_roles = DB::connect()->select('site_roles', ['site_roles.site_role_id', 'site_roles.string_constant'], $check_site_role_condition);
    $site_roles = array_column($site_roles, 'string_constant', 'site_role_id');
    array_walk($site_roles, function(&$value, $key) {
        $value = Registry::load('strings')->$value;
    });

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



    $form['fields']->process = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "add"
    ];

    $form['fields']->add = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "fake_users"
    ];

    $form['fields']->no_of_users = [
        "title" => Registry::load('strings')->no_of_users, "tag" => 'input', "type" => 'number', "class" => 'field',
        "placeholder" => Registry::load('strings')->no_of_users,
    ];

    $form['fields']->no_of_users = [
        "title" => Registry::load('strings')->no_of_users, "tag" => 'input', "type" => 'number', "class" => 'field',
        "placeholder" => Registry::load('strings')->no_of_users,
    ];

    $form['fields']->locale = [
        "title" => Registry::load('strings')->locale, "tag" => 'select', "class" => 'field'
    ];

    $form['fields']->locale['options'] = $localesWithNames;


    $form['fields']->site_role = [
        "title" => Registry::load('strings')->site_role, "tag" => 'select', "class" => 'field'
    ];

    $form['fields']->site_role['options'] = $site_roles;

    $form['fields']->generate_profile_picture = [
        "title" => Registry::load('strings')->generate_profile_picture, "tag" => 'select', "class" => 'field'
    ];
    $form['fields']->generate_profile_picture['options'] = [
        "yes" => Registry::load('strings')->yes, "no" => Registry::load('strings')->no,
    ];

    $form['fields']->stay_online = [
        "title" => Registry::load('strings')->stay_online, "tag" => 'select', "class" => 'field'
    ];
    $form['fields']->stay_online['options'] = [
        "yes" => Registry::load('strings')->yes, "no" => Registry::load('strings')->no,
    ];
    
    $form['fields']->email_domain = [
        "title" => Registry::load('strings')->email_domain, "tag" => 'input', "type" => 'text', "class" => 'field',
        "value" => '@testuser.test',
    ];

}
?>