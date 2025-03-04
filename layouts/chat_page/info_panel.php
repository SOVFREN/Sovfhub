<div class="col-md-5 col-lg-3 info_panel d-none page_column" column="fourth">
    <div class="fixed_header">
        <div class="icons">
            <div class="left">

            </div>
            <div class="right">
                <span class="icon close_info_panel">
                    <i class="icon_xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"></path>
                        </svg>
                    </i>
                </span>
            </div>
        </div>
    </div>
    <div class="coverpic">
        <span class="img"></span>
    </div>

    <div class="confirm_box d-none animate__animated animate__flipInX">
        <div class="error">
            <span class="message"><?php echo(Registry::load('strings')->error) ?> : <span></span></span>
        </div>
        <div class="content">
            <span class="text"></span>
            <span class="btn cancel" column="fourth"><span></span></span>
            <span class="btn submit"><span></span></span>
        </div>
    </div>

    <div class="info_box">
        <span class="img">
            <img>
            <span class="online_status"><span></span></span>
        </span>
        <span class="heading"></span>
        <span class="subheading"></span>
    </div>

    <div class="controls">
        <div>
            <span class="button"></span>
            <div class="options dropdown_button">
                <span class="text"><?php echo(Registry::load('strings')->options) ?></span>
                <div class="dropdown_list">
                    <ul></ul>
                </div>
            </div>
        </div>
    </div>


    <div class="statistics">
        <div></div>
    </div>


    <div class="content">
        <div class="fields"></div>
    </div>


    <?php
    if (isset($site_adverts['info_panel'])) {
        $site_advert = $site_adverts['info_panel'];
        $advert_css = 'max-height:'.$site_advert['site_advert_max_height'].'px;';

        if (!empty($site_advert['site_advert_min_height'])) {
            $advert_css .= 'min-height:'.$site_advert['site_advert_min_height'].'px;';
        }
        ?>

        <div class="site_advert_block" style="<?php echo $advert_css; ?>">
            <div>
                <?php echo $site_advert['site_advert_content']; ?>
            </div>
        </div>
        <?php
    }
    ?>

    <div class="loader">
        <div>
            <span class="cover_pic"></span>
            <span class="image">
                <span class="default_image"></span>
                <span class="error_image"><img class="lazy" data-src="<?php echo Registry::load('config')->site_url ?>assets/files/defaults/error_image.webp" /></span>
            </span>

            <span class="error_text">
                <span class="title"></span>
                <span class="subtitle"></span>
            </span>

            <span class="heading"><span></span></span>
            <span class="subheading">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </span>
            <span class="icons">
                <span></span>
                <span></span><span></span><span></span>
                <span></span>
                <span></span>
            </span>
            <span class="contents">
                <span>
                    <span class="title"></span>
                    <span class="value"></span>
                </span>
                <span>
                    <span class="title"></span>
                    <span class="value"></span>
                </span>
                <span>
                    <span class="title"></span>
                    <span class="value"></span>
                </span>
                <span>
                    <span class="title"></span>
                    <span class="value"></span>
                </span>
                <span>
                    <span class="title"></span>
                    <span class="value"></span>
                </span>
            </span>
        </div>
    </div>

</div>