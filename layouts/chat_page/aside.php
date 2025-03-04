<div class="col-md-5 col-lg-3 aside page_column visible" column="first">
    <div class='head'>
        <?php
        if (Registry::load('current_user')->logged_in) {
            ?>
            <span class='menu toggle_side_navigation'>
                <i>
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 1024 1024">
                        <path fill="currentColor" d="M127.999 271.999c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0zM127.999 512c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0zM127.999 752.001c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0z"></path>
                    </svg>
                </i>
                <span class="total_unread_notifications"></span>
            </span>
            <?php
        }
        ?>
        <span class='logo refresh_page'>
            <?php if (Registry::load('current_user')->color_scheme === 'dark_mode') {
                ?>
                <img width="100px" height="50px" src="<?php echo Registry::load('config')->site_url.'assets/files/logos/chat_page_logo_dark_mode.png'.$cache_timestamp; ?>" />
                <?php
            } else {
                ?>
                <img width="100px" height="50px" src="<?php echo Registry::load('config')->site_url.'assets/files/logos/chat_page_logo.png'.$cache_timestamp; ?>" />
                <?php
            } ?>
        </span>
        <span class='icons'>
            <?php
            if (Registry::load('current_user')->logged_in && role(['permissions' => ['private_conversations' => 'view_private_chats']])) {
                ?>
                <span class="icon load_aside pm_shortcut" load="private_conversations">
                    <i>
                        <svg width="26" height="26" viewBox="0 0 24 24" id="magicoon-Regular" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                            <g id="inbox-Regular">
                                <path id="inbox-Regular-2" data-name="inbox-Regular" class="cls-1" d="M7.25,10A.75.75,0,0,1,8,9.25h8a.75.75,0,0,1,0,1.5H8A.75.75,0,0,1,7.25,10ZM9,7.75h6a.75.75,0,0,0,0-1.5H9a.75.75,0,0,0,0,1.5Zm12.75,5.737V16A4.756,4.756,0,0,1,17,20.75H7A4.756,4.756,0,0,1,2.25,16V13.487A3.762,3.762,0,0,1,2.442,12.3L4.377,6.5A4.743,4.743,0,0,1,8.883,3.25h6.234A4.743,4.743,0,0,1,19.623,6.5l1.935,5.8A3.762,3.762,0,0,1,21.75,13.487ZM4.04,12.25H9a.75.75,0,0,1,.75.75,2.25,2.25,0,0,0,4.5,0,.75.75,0,0,1,.75-.75h4.96L18.2,6.972A3.246,3.246,0,0,0,15.117,4.75H8.883A3.246,3.246,0,0,0,5.8,6.972ZM20.25,16V13.75H15.675a3.751,3.751,0,0,1-7.35,0H3.75V16A3.254,3.254,0,0,0,7,19.25H17A3.254,3.254,0,0,0,20.25,16Z"></path>
                            </g>
                        </svg>
                    </i>
                    <span class="notification_count"></span>
                </span>
                <?php
            }
            if (!Registry::load('current_user')->logged_in) {
                if (Registry::load('settings')->hide_groups_on_group_url) {
                    ?>
                    <i class="iconic_groups load_groups load_aside d-none" load="group_members" data-group_id="<?php echo(Registry::load('config')->load_group_conversation) ?>"></i>
                    <?php
                } else {
                    ?>
                    <i class="iconic_groups load_groups load_aside d-none" load="groups"></i>
                    <?php
                }
            }
            ?>
        </span>
    </div>

    <div class="storage_files_upload_status">
        <div class="center">
            <div class="error">
                <span class="message"><?php echo Registry::load('strings')->error ?> : <span></span></span>
            </div>
            <div class="text">
                <span><?php echo Registry::load('strings')->uploading_files ?> : <span class="percentage">0%</span></span>
            </div>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
            </div>
            <div class="files_attached">
                <form class="files_attached_form" enctype="multipart/form-data">
                    <input type="hidden" name="upload" value="storage" />
                    <input type="hidden" name="frontend" value='true' />
                    <input type="file" multiple="" name="storage_file_attachments[]" class="storage_file_attachments" />
                </form>
            </div>
        </div>
    </div>

    <div class="audio_player_box module hidden">
        <?php include 'layouts/chat_page/audio_player_box.php'; ?>
    </div>

    <div class="site_records module">
        <?php include 'layouts/chat_page/site_records.php'; ?>
    </div>


    <?php
    if (!Registry::load('current_user')->logged_in) {
        ?>
        <div class="info_box">
            <div>
                <div class="text">
                    <?php echo Registry::load('strings')->not_logged_in_message ?>
                </div>
                <span class="button open_link" autosync=true link="<?php echo Registry::load('config')->site_url ?>entry/">
                    <?php echo Registry::load('strings')->login ?>
                </span>
            </div>
        </div>
        <?php
    }
    ?>


    <?php
    if (isset($site_adverts['left_content_block'])) {
        $site_advert = $site_adverts['left_content_block'];
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

    <?php include 'layouts/chat_page/mini_audio_player.php'; ?>

</div>