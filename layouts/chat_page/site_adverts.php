<?php
$site_adverts = array();

if (!role(['permissions' => ['site_adverts' => 'ad_free_account']])) {
    $adverts_columns = [
        'site_advertisements.site_advert_max_height', 'site_advertisements.site_advert_min_height',
        'site_advertisements.site_advert_content', 'site_advertisements.site_advert_placement'
    ];
    $adverts_placements = ['chat_page_header', 'chat_page_footer', 'welcome_screen', 'left_content_block', 'info_panel'];
    $site_adverts = DB::connect()->rand("site_advertisements",
        ['site_advertisements.site_advert_placement' => $adverts_columns],
        ["site_advertisements.site_advert_placement" => $adverts_placements, "site_advertisements.disabled[!]" => 1]
    );
}

?>