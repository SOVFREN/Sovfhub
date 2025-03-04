<?php

if (isset($site_adverts['chat_page_footer'])) {
    $site_advert = $site_adverts['chat_page_footer'];
    $advert_css = 'max-height:'.$site_advert['site_advert_max_height'].'px;';

    if (!empty($site_advert['site_advert_min_height'])) {
        $advert_css .= 'min-height:'.$site_advert['site_advert_min_height'].'px;';
    }

    ?>

    <div class="footer_site_adverts" style='height:<?php echo $site_advert['site_advert_max_height']; ?>px;'>
        <div class="site_advert_block" style="<?php echo $advert_css; ?>">
            <div>
                <?php echo $site_advert['site_advert_content']; ?>
            </div>
        </div>
    </div>
    <style>
        .main.main_window {
            padding-bottom: <?php echo $site_advert['site_advert_max_height'].'px;';
            ?>
        }
    </style>
    <?php
}
?>