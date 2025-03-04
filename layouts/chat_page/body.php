<body class='<?php echo(Registry::load('appearance')->body_class) ?> overflow-hidden'>

    <?php include 'assets/headers_footers/chat_page/body.php'; ?>

    <div class="preloader">
        <div class="content">
            <div>
                <div class="loader_image">
                    <?php if (Registry::load('current_user')->color_scheme === 'dark_mode') {
                        ?>
                        <img src="<?php echo Registry::load('config')->site_url.'assets/files/defaults/loading_image_dark_mode.png'.$cache_timestamp; ?>" />
                        <?php
                    } else {
                        ?>
                        <img src="<?php echo Registry::load('config')->site_url.'assets/files/defaults/loading_image_light_mode.png'.$cache_timestamp; ?>" />
                        <?php
                    } ?>
                </div>
                <div class="loader">
                    <div class="loading">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'layouts/chat_page/header_site_adverts.php'; ?>

    <section class="main main_window" <?php echo(Registry::load('appearance')->main_window_style) ?> last_realtime_log_id=0>
        <div class='window fh'>
            <div class="container-fluid fh">
                <div class="row fh nowrap page_row chat_page_container">
                    <?php if (Registry::load('current_user')->logged_in) {
                        include 'layouts/chat_page/side_navigation.php';
                    } ?>
                    <?php include 'layouts/chat_page/aside.php'; ?>
                    <?php include 'layouts/chat_page/middle.php'; ?>
                    <?php include 'layouts/chat_page/form.php'; ?>
                    <?php include 'layouts/chat_page/info_panel.php'; ?>
                </div>
            </div>
        </div>
    </section>


    <?php include 'layouts/chat_page/footer_site_adverts.php'; ?>


    <?php if (Registry::load('settings')->adblock_detector === 'enable') {
        include 'layouts/chat_page/ad_block_detector.php';
    } ?>

    <?php
    if (Registry::load('settings')->video_chat !== 'disable') {
        if (role(['permissions' => ['private_conversations' => 'video_chat']])) {
            ?>
            <div class="call_notification d-none" current_call_id=0>
                <div class="user-image"></div>
                <div class="call_notification-text">
                    <p>
                        <strong class="user_name"></strong> <?php echo Registry::load('strings')->is_calling_text ?>
                    </p>
                </div>
                <div class="action-buttons">
                    <button class="action-button attend_video_call"><?php echo Registry::load('strings')->join ?></button>
                    <button class="action-button reject_video_call"><?php echo Registry::load('strings')->reject ?></button>
                </div>
                <div class="d-none">
                    <audio class="call_ringtone" controls loop>
                        <source src="<?php echo(Registry::load('config')->site_url) ?>assets/files/defaults/call_notification.mp3" type="audio/mpeg">
                    </audio>
                </div>
            </div>
            <?php
        }
    }
    ?>

    <div class="on_site_load d-none">
        <?php if (isset(Registry::load('config')->load_user_profile) && !empty(Registry::load('config')->load_user_profile)) {
            ?>
            <span class="get_info" user_id="<?php echo(Registry::load('config')->load_user_profile) ?>">Profile</span>
            <?php
        } else if (isset(Registry::load('config')->load_membership_packages)) {
            ?>
            <span class="load_membership_info" package_id="<?php echo(Registry::load('config')->load_membership_package_id) ?>">Membership Packages</span>
            <?php
        } else if (isset(Registry::load('config')->load_private_conversation) && !empty(Registry::load('config')->load_private_conversation)) {
            ?>
            <span class="load_conversation" user_id="<?php echo(Registry::load('config')->load_private_conversation) ?>">Group</span>
            <?php
        } else if (isset(Registry::load('config')->load_group_conversation) && !empty(Registry::load('config')->load_group_conversation)) {
            ?>
            <span class="load_conversation" group_id="<?php echo(Registry::load('config')->load_group_conversation) ?>">Group</span>
            <?php
        } else if (isset(Registry::load('config')->join_group_conversation) && !empty(Registry::load('config')->join_group_conversation)) {
            ?>
            <span class="load_form" form="join_group" data-group_secret_code="<?php echo(Registry::load('config')->join_group_secret_code) ?>" data-group_id="<?php echo(Registry::load('config')->join_group_conversation) ?>">Group</span>
            <?php
        } else if (isset(Registry::load('config')->load_page) && !empty(Registry::load('config')->load_page)) {
            ?>
            <span class="load_page" page_id="<?php echo(Registry::load('config')->load_page) ?>">Page</span>
            <?php
        } else if (Registry::load('current_user')->logged_in && !isset(Registry::load('config')->load_user_profile)) {
            if (role(['find' => 'load_profile_on_page_load']) === 'yes') {
                ?>
                <span class="get_info load_profile_on_page_load">User Profile</span>
                <?php
            }
        }
        ?>
    </div>

    <div class="content_on_page_load d-none">
        <?php
        if (Registry::load('current_user')->logged_in) {
            $left_panel_content_on_page_load = role(['find' => 'left_panel_content_on_page_load']);

            if ($left_panel_content_on_page_load === 'group_categories' && Registry::load('settings')->categorize_groups !== 'yes') {
                $left_panel_content_on_page_load = 'groups';
            }
            ?>
            <span class="left_panel_content_on_page_load"><?php echo $left_panel_content_on_page_load; ?></span>
            <span class="main_panel_content_on_page_load"><?php echo role(['find' => 'main_panel_content_on_page_load']); ?></span>
            <?php
        }
        ?>
    </div>

    <div class="load_on_refresh d-none"></div>

    <div class="language_strings d-none">
        <span class="string_uploading_files"><?php echo(Registry::load('strings')->uploading_files) ?></span>
        <span class='string_loading'><?php echo(Registry::load('strings')->loading) ?></span>
        <span class='string_sort'><?php echo(Registry::load('strings')->sort) ?></span>
        <span class='string_error'><?php echo(Registry::load('strings')->error) ?></span>
        <span class='string_error_message'><?php echo(Registry::load('strings')->error_message) ?></span>
        <span class='string_choose_file'><?php echo(Registry::load('strings')->choose_file) ?></span>
        <span class='string_load_more'><?php echo(Registry::load('strings')->load_more) ?></span>
        <span class='string_new'><?php echo(Registry::load('strings')->new) ?></span>
        <span class='string_sharing_your_location'><?php echo(Registry::load('strings')->sharing_your_location) ?></span>
        <span class='string_sharing_video'><?php echo(Registry::load('strings')->sharing_video) ?></span>
        <span class='string_new_message_notification'><?php echo(Registry::load('strings')->new_message_notification) ?></span>
        <span class='string_is_typing'><?php echo(Registry::load('strings')->is_typing) ?></span>
        <span class='string_recording'><?php echo(Registry::load('strings')->recording) ?></span>
        <span class='string_message_textarea_placeholder'><?php echo(Registry::load('strings')->message_textarea_placeholder) ?></span>
    </div>

    <div class="system_variables d-none">
        <span class="variable_message_alignment"><?php echo(Registry::load('settings')->message_alignment) ?></span>
        <span class="variable_own_message_alignment"><?php echo(Registry::load('settings')->own_message_alignment) ?></span>
        <span class="variable_video_chat"><?php echo(Registry::load('settings')->video_chat) ?></span>
        <span class="variable_audio_chat"><?php echo(Registry::load('settings')->audio_chat) ?></span>
        <span class="variable_refresh_rate"><?php echo(Registry::load('settings')->refresh_rate) ?></span>
        <span class="variable_ffmpeg"><?php echo(Registry::load('settings')->ffmpeg) ?></span>
        <span class="variable_enter_is_send"><?php echo(Registry::load('settings')->enter_is_send) ?></span>
        <span class="variable_load_group_info_on_group_load"><?php echo(Registry::load('settings')->load_group_info_on_group_load) ?></span>
        <span class="variable_show_profile_on_pm_open"><?php echo(Registry::load('settings')->show_profile_on_pm_open) ?></span>
        <span class="variable_people_nearby_feature"><?php echo(Registry::load('settings')->people_nearby_feature) ?></span>
        <span class="variable_search_on_change_of_input"><?php echo(Registry::load('settings')->search_on_change_of_input) ?></span>
        <span class="variable_show_side_navigation_on_load"><?php echo(Registry::load('settings')->show_side_navigation_on_load) ?></span>
        <span class="variable_allowed_file_types"><?php echo(Registry::load('current_user')->allowed_file_types) ?></span>
        <span class="variable_current_title"></span>
        <?php
        if (isset($_GET['embed_url']) && !empty($_GET['embed_url'])) {
            if (isset(Registry::load('config')->load_group_conversation) && !empty(Registry::load('config')->load_group_conversation)) {
                $embed_url = Registry::load('config')->site_url.'group/'.Registry::load('config')->load_group_conversation.'/?embed_url=yes';
                ?>
                <span class="variable_embed_url"><?php echo $embed_url; ?></span>
                <?php
            }
        }
        if (!isset($_GET['login_session_id']) && !isset($_GET['session_time_stamp'])) {
            if (isset(Registry::load('config')->samesite_cookies_current) && strtolower(Registry::load('config')->samesite_cookies_current) === 'none') {
                ?>
                <span class="variable_login_from_storage">true</span>
                <?php
            }
        }
        ?>
    </div>

    <div class="site_sound_notification">
        <div>
            <audio controls>
                <source src="<?php echo(Registry::load('settings')->notification_tone) ?>" type="audio/mpeg">
            </audio>
        </div>
    </div>

    <?php include 'layouts/chat_page/web_push_service_variables.php'; ?>
</body>