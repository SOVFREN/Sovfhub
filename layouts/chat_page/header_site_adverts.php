<?php
if (isset($site_adverts['chat_page_header'])) {
    $site_advert = $site_adverts['chat_page_header'];
    $advert_css = 'max-height:'.$site_advert['site_advert_max_height'].'px;';
    Registry::load('appearance')->main_window_style = 'style="padding-top:'.$site_advert['site_advert_max_height'].'px;"';

    if (!empty($site_advert['site_advert_min_height'])) {
        $advert_css .= 'min-height:'.$site_advert['site_advert_min_height'].'px;';
    }

    ?>

    <div class="header_site_adverts" style='height:<?php echo $site_advert['site_advert_max_height']; ?>px;'>
        <div class="site_advert_block" style="<?php echo $advert_css; ?>">
            <div>
                <?php echo $site_advert['site_advert_content']; ?>
            </div>
        </div>
    </div>
    <?php
}
?>