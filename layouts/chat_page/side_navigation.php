<div class="side_navigation boundary">
    <div class="top">
        <div class="logo refresh_page">
            <?php if (Registry::load('current_user')->color_scheme === 'dark_mode') {
                ?>
                <img src="<?php echo Registry::load('config')->site_url.'assets/files/logos/chat_page_logo_dark_mode.png'.$cache_timestamp; ?>" />
                <?php
            } else {
                ?>
                <img src="<?php echo Registry::load('config')->site_url.'assets/files/logos/chat_page_logo.png'.$cache_timestamp; ?>" />
                <?php
            } ?>
        </div>
        <div class="icon">
            <i class="toggle_side_navigation">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 1024 1024">
                    <path fill="currentColor" d="M240.932 174.52c-18.334-18.334-48.057-18.334-66.388 0s-18.335 48.057 0 66.388l271.089 271.088-271.087 271.087c-18.335 18.333-18.335 48.058 0 66.387s48.057 18.333 66.388 0l271.086-271.087 271.087 271.087c18.333 18.333 48.058 18.333 66.387 0s18.333-48.058 0-66.387l-271.087-271.087 271.087-271.088c18.338-18.334 18.338-48.057 0-66.388-18.333-18.334-48.054-18.334-66.387 0l-271.087 271.089-271.089-271.089z"></path>
                </svg>
            </i>
        </div>
    </div>
    <div class="center">
        <ul class="menu_items">

            <?php include 'layouts/chat_page/custom_menu_items_top.php'; ?>

            <?php
            if (role(['permissions' => ['site_notifications' => 'view']])) {
                ?>
                <li class="load_aside realtime_module load_site_notifications" module="site_notifications" load="site_notifications" id="alerts">
                    <div class="menu_item">
                        <span class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M866.56 604.587l-18.56-14.933c-9.783-7.879-15.991-19.854-16-33.279v-172.374c-0.054-154.721-109.903-283.769-255.863-313.464l-2.057-0.35c-7.352-27.834-32.305-48.022-61.973-48.022s-54.621 20.187-61.873 47.575l-0.101 0.447c-148.108 29.957-258.059 159.034-258.133 313.805v172.381c-0.009 13.426-6.217 25.401-15.918 33.216l-0.082 0.064-18.56 14.933c-43.954 35.352-71.902 89.058-72.107 149.299v35.448c0 47.128 38.205 85.333 85.333 85.333v0h176.213c19.939 73.742 86.245 127.104 165.013 127.104s145.074-53.362 164.737-125.909l0.276-1.195h176.427c47.128 0 85.333-38.205 85.333-85.333v0-34.987c-0.067-60.435-28.045-114.321-71.735-149.471l-0.372-0.289zM512 917.333c-31.22-0.194-58.443-17.125-73.169-42.264l-0.218-0.403h146.987c-14.979 25.602-42.297 42.553-73.584 42.667h-0.016zM853.333 789.333h-682.667v-34.987c0.049-33.582 15.61-63.52 39.899-83.039l0.208-0.161 18.56-14.933c29.348-23.638 47.974-59.561 48-99.836v-172.377c0-129.603 105.064-234.667 234.667-234.667s234.667 105.064 234.667 234.667v0 172.373c0 0.040 0 0.086 0 0.133 0 40.309 18.632 76.266 47.754 99.728l0.246 0.192 18.56 14.933c24.443 19.637 39.988 49.484 40.107 82.967v0.020z"></path>
                            </svg>

                        </span>
                        <span class="title">
                            <?php echo(Registry::load('strings')->notifications) ?>
                        </span>
                        <span class="unread"></span>
                    </div>
                </li>
                <?php
            } ?>

            <?php
            if (role(['permissions' => ['super_privileges' => ['view_statistics', 'monitor_group_chats', 'monitor_private_chats']], 'condition' => 'OR'])) {
                ?>
                <li class="has_child">
                    <div class="menu_item">
                        <span class="icon">

                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M998.243 62.083h-972.465c-14.221 0-25.778 11.568-25.778 25.778v681.385c0 14.2 11.568 25.778 25.778 25.778h972.465c14.21 0 25.768-11.579 25.768-25.778v-681.374c0.011-14.221-11.547-25.789-25.768-25.789zM946.676 717.679h-869.33v-578.239h869.33v578.239z"></path>
                                <path fill="currentColor" d="M698.936 878.138h-51.567v-47.282c0-7.121-5.789-12.889-12.9-12.889h-244.926c-7.121 0-12.889 5.768-12.889 12.889v47.282h-51.567c-7.121 0-12.889 5.768-12.889 12.878v58.012c0 7.121 5.779 12.889 12.889 12.889h373.85c7.111 0 12.878-5.768 12.878-12.889v-58.012c0-7.111-5.768-12.878-12.878-12.878z"></path>
                                <path fill="currentColor" d="M164.369 204.905c-11.858 0-21.482 9.624-21.482 21.482v179.073l200.544-200.555h-179.063z"></path>
                            </svg>

                        </span>
                        <span class="title">
                            <?php echo Registry::load('strings')->monitor ?>
                        </span>
                    </div>
                    <div class="child_menu">
                        <ul>
                            <?php
                            if (role(['permissions' => ['super_privileges' => 'view_statistics']])) {
                                ?>
                                <li class="show_statistics load_statistics"><?php echo(Registry::load('strings')->statistics) ?></li>
                                <?php
                            }
                            if (role(['permissions' => ['super_privileges' => 'monitor_group_chats']])) {
                                ?>
                                <li class="load_conversation" group_id="all"><?php echo Registry::load('strings')->group_chats ?></li>
                                <?php
                            }
                            if (role(['permissions' => ['super_privileges' => 'monitor_private_chats']])) {
                                ?>
                                <li class="load_conversation" user_id="all"><?php echo Registry::load('strings')->private_chats ?></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </li>
                <?php
            } ?>

            <?php if (Registry::load('settings')->categorize_groups === 'yes') {
                ?>
                <li class="load_aside load_group_categories" load="group_categories">
                    <div class="menu_item">
                        <span class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M111.531 87.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM580.864 87.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM384 277.333v106.667h-213.333v-213.333h213.333v106.667zM853.333 277.333v106.667h-213.333v-213.333h213.333v106.667zM111.531 557.099c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM580.864 557.099c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM384 746.667v106.667h-213.333v-213.333h213.333v106.667zM853.333 746.667v106.667h-213.333v-213.333h213.333v106.667z"></path>
                            </svg>
                        </span>
                        <span class="title">
                            <?php echo(Registry::load('strings')->group_categories) ?>
                        </span>
                        <span class="unread"></span>
                    </div>
                </li>
                <?php
            } ?>

            <?php
            $view_groups = false;

            if (!Registry::load('current_user')->logged_in) {
                $view_groups = true;
            } else if (role(['permissions' => ['groups' => ['view_public_groups', 'view_password_protected_groups', 'view_joined_groups', 'view_secret_groups']], 'condition' => 'OR'])) {
                $view_groups = true;
            }

            if (Registry::load('settings')->hide_groups_on_group_url) {
                $view_groups = false;
            }

            if ($view_groups) {
                ?>

                <li class="load_aside realtime_module load_groups" load="groups" module="groups" unread="0">
                    <div class="menu_item">
                        <span class="icon">
                            <svg fill="currentColor" width="20" height="20" viewBox="0 0 35 35" data-name="Layer 2" id="Layer_2" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.35,22h-.1a1.25,1.25,0,0,1-1.15-1.34c0-.34,0-.67,0-1A11.84,11.84,0,0,0,21.19,8.44,1.25,1.25,0,0,1,22,6.07a14.37,14.37,0,0,1,9.63,13.59c0,.4,0,.8-.05,1.2A1.24,1.24,0,0,1,30.35,22Z" />
                                <path d="M4.43,22.08A1.25,1.25,0,0,1,3.19,21c0-.42-.06-.86-.06-1.29A14.37,14.37,0,0,1,12.76,6.07a1.25,1.25,0,0,1,.82,2.37A11.84,11.84,0,0,0,5.63,19.66c0,.35,0,.7,0,1.05a1.24,1.24,0,0,1-1.12,1.36Z" />
                                <path d="M17.38,34A14,14,0,0,1,7,29.5a1.25,1.25,0,1,1,1.82-1.71,11.59,11.59,0,0,0,8.55,3.72A11.71,11.71,0,0,0,26,27.71a1.25,1.25,0,1,1,1.84,1.69A14.23,14.23,0,0,1,17.38,34Z" /><path d="M17.39,12.1a5.56,5.56,0,1,1,5.52-5.55A5.55,5.55,0,0,1,17.39,12.1Zm0-8.61a3.06,3.06,0,1,0,3,3.06A3,3,0,0,0,17.39,3.49Z" />
                                <path d="M29.23,30.48a5.56,5.56,0,1,1,5.52-5.56A5.55,5.55,0,0,1,29.23,30.48Zm0-8.62a3.06,3.06,0,1,0,3,3.06A3,3,0,0,0,29.23,21.86Z" />
                                <path d="M5.77,30.48a5.56,5.56,0,1,1,5.53-5.56A5.55,5.55,0,0,1,5.77,30.48Zm0-8.62a3.06,3.06,0,1,0,3,3.06A3,3,0,0,0,5.77,21.86Z" />
                            </svg>
                        </span>
                        <span class="title">
                            <?php echo(Registry::load('strings')->groups) ?>
                        </span>
                        <span class="unread"></span>
                    </div>
                </li>
                <?php
            }

            if (Registry::load('settings')->hide_groups_on_group_url) {
                ?>
                <li class="load_conversation" group_id="<?php echo(Registry::load('config')->load_group_conversation) ?>">
                    <div class="menu_item">
                        <span class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M111.531 87.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM580.864 87.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM384 277.333v106.667h-213.333v-213.333h213.333v106.667zM853.333 277.333v106.667h-213.333v-213.333h213.333v106.667zM111.531 557.099c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM580.864 557.099c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM384 746.667v106.667h-213.333v-213.333h213.333v106.667zM853.333 746.667v106.667h-213.333v-213.333h213.333v106.667z"></path>
                            </svg>
                        </span>
                        <span class="title">
                            <?php echo(Registry::load('strings')->group_chat) ?>
                        </span>
                    </div>
                </li>

                <li class="load_aside d-none force_trigger_onload" load="group_members" data-group_id="<?php echo(Registry::load('config')->load_group_conversation) ?>">
                    <div class="menu_item">
                        <span class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M111.531 87.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM580.864 87.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM384 277.333v106.667h-213.333v-213.333h213.333v106.667zM853.333 277.333v106.667h-213.333v-213.333h213.333v106.667zM111.531 557.099c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM580.864 557.099c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM384 746.667v106.667h-213.333v-213.333h213.333v106.667zM853.333 746.667v106.667h-213.333v-213.333h213.333v106.667z"></path>
                            </svg>
                        </span>
                        <span class="title">
                            <?php echo(Registry::load('strings')->members) ?>
                        </span>
                    </div>
                </li>
                <?php
            }
            ?>

            <?php
            if (role(['permissions' => ['private_conversations' => 'view_private_chats']])) {
                ?>
                <li class="load_aside realtime_module load_private_conversations" load="private_conversations" module="private_conversations">
                    <div class="menu_item">
                        <span class="icon">

                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M246.858 495.428c0-27.456 22.258-49.714 49.714-49.714v0h232c27.456 0 49.714 22.258 49.714 49.714s-22.258 49.714-49.714 49.714v0h-232c-27.456 0-49.714-22.258-49.714-49.714v0zM296.572 280c-27.456 0-49.714 22.258-49.714 49.714s22.258 49.714 49.714 49.714v0h430.858c27.456 0 49.714-22.258 49.714-49.714s-22.258-49.714-49.714-49.714v0h-430.858z"></path>
                                <path fill="currentColor" d="M976 197.142c0-82.369-66.773-149.142-149.142-149.142v0h-629.714c-82.369 0-149.142 66.773-149.142 149.142v0 729.142c0 0.002 0 0.004 0 0.006 0 27.456 22.258 49.714 49.714 49.714 13.070 0 24.962-5.044 33.836-13.291l-0.032 0.027 185.666-172.342c8.841-8.216 20.731-13.258 33.8-13.258 0.002 0 0.004 0 0.007 0h475.866c82.369 0 149.142-66.773 149.142-149.142v0-430.858zM826.858 147.428c27.456 0 49.714 22.258 49.714 49.714v0 430.858c0 27.456-22.258 49.714-49.714 49.714v0h-475.798c-0.025 0-0.056 0-0.085 0-39.202 0-74.871 15.125-101.491 39.857l0.094-0.085-102.146 94.788v-615.132c0-27.456 22.258-49.714 49.714-49.714v0h629.714z"></path>
                            </svg>

                        </span>
                        <span class="title">
                            <?php echo Registry::load('strings')->messages ?>
                        </span>
                        <span class="unread"></span>
                    </div>
                </li>
                <?php
            }
            if (role(['permissions' => ['site_users' => 'view_online_users']])) {
                ?>
                <li class="load_aside realtime_module load_online_users" module="online_users" load="online">
                    <div class="menu_item">
                        <span class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M521.192 119.219c80.936 0 142.831 61.892 142.831 142.831s-61.892 142.831-142.831 142.831-142.831-61.892-142.831-142.831 66.653-142.831 142.831-142.831zM521.192 24c-133.308 0-238.050 104.742-238.050 238.050s104.742 238.050 238.050 238.050 238.050-104.742 238.050-238.050-104.742-238.050-238.050-238.050zM411.689 1000h-247.57c-71.414 0-128.547-57.13-128.547-133.308v-80.936c0-119.025 95.219-214.244 214.244-214.244h452.292c28.567 0 47.611 19.044 47.611 47.611s-19.044 47.611-47.611 47.611h-452.292c-66.653 0-119.025 57.13-119.025 119.025v85.697c0 19.044 14.283 38.089 33.328 38.089h247.57c28.567 0 47.611 19.044 47.611 47.611s-19.044 42.85-47.611 42.85z"></path>
                                <path fill="currentColor" d="M973.486 719.103c-19.044-19.044-47.611-19.044-66.653 0l-166.633 166.633-104.742-99.981c-19.044-19.044-47.611-19.044-66.653 0s-19.044 47.611 0 66.653l133.308 133.308s4.761 4.761 9.522 4.761 4.761 4.761 9.522 4.761h38.089c4.761 0 4.761-4.761 9.522-4.761s4.761-4.761 9.522-4.761l199.961-199.961c14.283-19.044 14.283-47.611-4.761-66.653z"></path>
                            </svg>

                        </span>
                        <span class="title">
                            <?php echo Registry::load('strings')->online ?>
                        </span>
                    </div>
                </li>
                <?php
            }
            if (Registry::load('settings')->people_nearby_feature === 'enable') {
                if (role(['permissions' => ['site_users' => 'view_nearby_users']])) {
                    ?>
                    <li class="load_aside load_nearby_users" load="nearby_users">
                        <div class="menu_item">
                            <span class="icon">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                    <path fill="currentColor" d="M795.426 535.619c117.397 0 212.568 95.171 212.568 212.568 0 87.985-67.070 180.205-198.4 278.703-8.396 6.299-19.946 6.299-28.342 0-131.33-98.498-198.4-190.718-198.4-278.703 0-117.397 95.171-212.568 212.568-212.568zM512 39.575c260.926 0 472.455 211.527 472.455 472.452 0 18.488-1.062 36.73-3.135 54.666-19.63-20.103-42.493-37.033-67.755-49.956l0.026-4.71c0-32.564-3.881-64.226-11.198-94.546l-158.623 0.042c2.361 24.453 3.819 49.565 4.301 75.164-24.997 4.604-48.744 12.786-70.686 23.992l0.013-4.653c0-32.622-1.73-64.255-4.943-94.502h-320.929c-3.214 30.246-4.944 61.88-4.944 94.502 0 50.047 4.072 97.773 11.327 141.72h195.396c-8.695 22.28-14.41 46.062-16.639 70.845h-163.503c30.802 114.036 84.336 189.037 138.826 189.037 21.491 0 42.832-11.667 62.685-32.789 17.053 29.092 39.32 57.703 66.078 85.882-40.931 11.571-84.123 17.759-128.763 17.759-260.925 0-472.452-211.529-472.452-472.455s211.527-472.452 472.452-472.452zM299.823 724.608l-128.603-0.004c45.269 72.416 112.981 129.363 193.399 161.107-24.672-38.728-45.029-87.203-60.006-142.443l-4.789-18.659zM795.426 677.335c-39.131 0-70.858 31.727-70.858 70.858s31.727 70.858 70.858 70.858c39.131 0 70.858-31.727 70.858-70.858s-31.727-70.858-70.858-70.858zM280.226 417.517h-158.606l-0.227 0.814c-7.185 30.059-10.987 61.434-10.987 93.691 0 49.895 9.099 97.655 25.725 141.729l149.896-0.013c-6.719-44.833-10.286-92.462-10.286-141.716 0-32.31 1.535-63.923 4.484-94.505zM364.67 138.331l-1.079 0.414c-96.712 38.487-174.935 113.466-217.674 207.938l143.985 0.015c14.804-82.796 40.632-154.88 74.768-208.367zM512 110.427l-5.46 0.25c-59.779 5.467-117.523 100.034-144.343 235.997h299.608c-26.74-135.582-84.241-229.996-143.84-235.947l-5.965-0.3zM659.382 138.333l5.047 8.226c31.613 52.553 55.613 121.514 69.673 200.139l143.984-0.015c-40.848-90.289-114.11-162.777-204.954-202.617l-13.757-5.727z"></path>
                                </svg>

                            </span>
                            <span class="title">
                                <?php echo Registry::load('strings')->nearby_users ?>
                            </span>
                        </div>
                    </li>
                    <?php
                }
            }

            if (Registry::load('settings')->friend_system === 'enable') {
                if (role(['permissions' => ['friend_system' => 'view_friends']])) {
                    ?>
                    <li class="load_aside realtime_module load_friends" module="friends" load="friends">
                        <div class="menu_item">
                            <span class="icon">
                                <svg width="20" height="20" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="none">
                                    <g fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a3.5 3.5 0 100 7 3.5 3.5 0 000-7zM4 5.5a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd"></path>
                                        <path d="M4.25 10A3.75 3.75 0 00.5 13.75v.5a.75.75 0 001.5 0v-.5a2.25 2.25 0 012.25-2.25h3.5A2.25 2.25 0 0110 13.75v.5a.75.75 0 001.5 0v-.5A3.75 3.75 0 007.75 10h-3.5zM10.25 8.25A.75.75 0 0111 7.5h1v-1a.75.75 0 011.5 0v1h1a.75.75 0 010 1.5h-1v1a.75.75 0 01-1.5 0V9h-1a.75.75 0 01-.75-.75z"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="title">
                                <?php echo Registry::load('strings')->friends ?>
                            </span>
                            <span class="unread"></span>
                        </div>
                    </li>
                    <?php
                }
            }
            ?>


            <?php if (role(['permissions' => ['site_users' => ['view_site_users', 'block_users', 'ignore_users']], 'condition' => 'OR'])) {
                ?>
                <li class="has_child">
                    <div class="menu_item">
                        <span class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M689.1 512.7c-17.1 0-34.3-12.9-42.9-30-8.6-21.4 8.6-47.1 30-51.4 47.1-12.9 81.4-51.4 94.3-94.3 17.1-68.6-25.7-141.4-94.3-158.6-21.4-4.3-38.6-30-30-51.4 4.3-21.4 30-38.6 51.4-30 111.4 25.7 184.3 145.7 154.3 261.5-21.4 77.2-81.4 132.9-154.3 154.3h-8.5zM374 89.1c-117.7 0-213.1 95.4-213.1 213.1s95.4 213 213.1 213 213.1-95.4 213.1-213.1-95.4-213-213.1-213zM374 423.1c-66.8 0-121-54.1-121-120.9s54.2-121 121-121 120.9 54.1 120.9 120.9-54.1 121-120.9 121zM552.8 600.9h-345.5c-114 0-207.3 93.3-207.3 207.3v80.6c0 25.3 20.7 46.1 46.1 46.1s46.1-20.6 46.1-46.1v-92.1c0-57.2 46.4-103.7 103.7-103.7h368.6c57.2 0 103.7 46.4 103.7 103.7v92.1c0 25.4 20.6 46.1 46.1 46.1 25.3 0 46.1-20.7 46.1-46.1v-80.6c-0.2-114-93.5-207.3-207.6-207.3zM825.1 601.1c-1.4-0.1-2.7-0.2-4.1-0.2-25.4 0-46.1 20.6-46.1 46.1 0 25.4 20.6 46.1 46.1 46.1h7.2c57.2 0 103.7 46.4 103.7 103.7v92.1c0 25.4 20.6 46.1 46.1 46.1 25.3 0 46.1-20.7 46.1-46.1v-80.6c-0.1-111.3-88.8-202.7-199-207.2z"></path>
                            </svg>

                        </span>
                        <span class="title">
                            <?php echo(Registry::load('strings')->site_users) ?>
                        </span>
                    </div>
                    <div class="child_menu">
                        <ul>
                            <?php if (role(['permissions' => ['site_users' => 'view_site_users']])) {
                                ?>
                                <li class="load_aside load_site_users" load="site_users"><?php echo(Registry::load('strings')->view_all) ?></li>
                                <?php
                            }

                            if (role(['permissions' => ['site_users' => 'advanced_user_searches']])) {
                                ?>
                                <li class='load_form' form="search_users"><?php echo(Registry::load('strings')->search_users) ?></li>
                                <?php
                            }

                            if (role(['permissions' => ['site_users' => 'approve_users']])) {
                                ?>
                                <li class='load_aside' load="site_users" filter="pending_approval" skip_filter_title="true"><?php echo(Registry::load('strings')->pending_approval) ?></li>
                                <?php
                            }
                            if (role(['permissions' => ['site_users' => 'block_users']])) {
                                ?>
                                <li class='load_aside' load="blocked"><?php echo(Registry::load('strings')->blocked) ?></li>
                                <?php
                            }
                            if (role(['permissions' => ['site_users' => 'ignore_users']])) {
                                ?>
                                <li class='load_aside' load="ignored"><?php echo(Registry::load('strings')->ignored) ?></li>
                                <?php
                            }
                            if (role(['permissions' => ['site_users' => 'import_users']])) {
                                ?>
                                <li class='load_form' form="import_users"><?php echo(Registry::load('strings')->import_users) ?></li>
                                <?php
                            }
                            if (role(['permissions' => ['site_users' => 'generate_fake_users']])) {
                                ?>
                                <li class='load_form' form="generate_fake_users"><?php echo(Registry::load('strings')->generate_fake_users) ?></li>
                                <?php
                            }
                            if (role(['permissions' => ['site_users' => 'set_fake_online_users']])) {
                                ?>
                                <li class='load_aside' load="fake_online_users"><?php echo(Registry::load('strings')->fake_online_users) ?></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </li>
                <?php
            }

            if (isset(Registry::load('config')->pro_version) && !empty(Registry::load('config')->pro_version)) {
                $show_membership_menu = false;

                if (role(['permissions' => ['membership_packages' => 'view']]) || role(['permissions' => ['super_privileges' => 'manage_payment_gateways']])) {
                    $show_membership_menu = true;
                }

                if (role(['permissions' => ['memberships' => ['view_membership_info', 'view_personal_transactions', 'view_site_transactions']], 'condition' => 'OR'])) {
                    $show_membership_menu = true;
                }

                if ($show_membership_menu) {
                    ?>
                    <li class="has_child">
                        <div class="menu_item">
                            <span class="icon">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                    <path fill="currentColor" d="M1018.557 319.237c0.16-0.288 0.318-0.576 0.47-0.868 0.342-0.646 0.668-1.294 0.972-1.948 0.002-0.004 0.004-0.008 0.006-0.014 2.707-5.803 3.957-11.855 3.937-17.802 0-0.078 0.002-0.154 0.002-0.232-0.006-0.702-0.034-1.4-0.074-2.096-0.016-0.292-0.032-0.584-0.054-0.878-0.042-0.532-0.098-1.060-0.16-1.588-0.052-0.446-0.106-0.892-0.174-1.34-0.062-0.412-0.132-0.824-0.206-1.234-0.096-0.532-0.198-1.066-0.314-1.598-0.078-0.356-0.164-0.71-0.252-1.066-0.136-0.55-0.28-1.1-0.438-1.65-0.106-0.366-0.22-0.73-0.336-1.094-0.162-0.51-0.33-1.018-0.512-1.526-0.152-0.422-0.316-0.84-0.48-1.258-0.174-0.44-0.348-0.878-0.538-1.316-0.204-0.474-0.424-0.94-0.646-1.406-0.128-0.268-0.236-0.538-0.37-0.804l-127.973-255.946c-0.084-0.168-0.182-0.326-0.268-0.492-0.236-0.454-0.482-0.902-0.732-1.348-0.248-0.442-0.498-0.884-0.758-1.314-0.178-0.292-0.364-0.578-0.548-0.864-0.36-0.564-0.726-1.124-1.106-1.664-0.070-0.098-0.142-0.192-0.212-0.29-2.162-3.011-4.633-5.651-7.35-7.898-0.142-0.118-0.292-0.226-0.434-0.342-0.536-0.432-1.074-0.86-1.628-1.262-0.592-0.434-1.204-0.842-1.82-1.246-0.106-0.068-0.206-0.144-0.312-0.21-5.093-3.269-11.002-5.491-17.52-6.297-0.236-0.030-0.472-0.048-0.708-0.074-0.642-0.068-1.282-0.14-1.936-0.18-0.97-0.064-1.942-0.1-2.913-0.098h-682.344c-0.972 0-1.944 0.034-2.913 0.098-0.654 0.040-1.294 0.112-1.936 0.18-0.236 0.026-0.472 0.044-0.706 0.074-6.519 0.806-12.427 3.027-17.52 6.297-0.106 0.068-0.208 0.142-0.312 0.21-0.616 0.404-1.228 0.812-1.82 1.246-0.552 0.4-1.088 0.828-1.622 1.258-0.146 0.118-0.298 0.228-0.442 0.348-2.717 2.246-5.189 4.885-7.35 7.898-0.070 0.098-0.144 0.192-0.212 0.29-0.382 0.54-0.746 1.1-1.106 1.664-0.184 0.288-0.37 0.572-0.548 0.864-0.26 0.43-0.51 0.872-0.76 1.316s-0.496 0.892-0.73 1.344c-0.086 0.168-0.184 0.324-0.268 0.494l-127.973 255.946c-0.134 0.268-0.242 0.538-0.37 0.806-0.222 0.466-0.44 0.93-0.646 1.404-0.19 0.438-0.364 0.876-0.538 1.316-0.164 0.418-0.328 0.834-0.48 1.258-0.182 0.508-0.35 1.016-0.512 1.526-0.116 0.364-0.23 0.726-0.336 1.094-0.16 0.55-0.302 1.1-0.438 1.65-0.088 0.354-0.174 0.708-0.252 1.064-0.116 0.534-0.22 1.068-0.316 1.602-0.074 0.408-0.144 0.818-0.206 1.228-0.066 0.448-0.122 0.896-0.174 1.344-0.062 0.528-0.118 1.054-0.16 1.586-0.022 0.294-0.038 0.588-0.054 0.88-0.040 0.696-0.068 1.392-0.074 2.092 0 0.078 0.002 0.158 0.002 0.236-0.018 5.945 1.23 11.997 3.937 17.8 0.002 0.004 0.004 0.008 0.006 0.014 0.304 0.654 0.63 1.302 0.972 1.948 0.152 0.292 0.31 0.58 0.47 0.868 0.254 0.454 0.51 0.906 0.782 1.356 0.32 0.53 0.656 1.048 0.996 1.564 0.136 0.204 0.252 0.412 0.392 0.616l466.17 678.065c1.338 2.545 2.945 4.905 4.757 7.089 2.631 3.357 5.699 6.173 9.064 8.462 7.272 5.239 15.551 7.602 23.769 7.578 0.208 0.006 0.416 0.006 0.626 0.010 0.208-0.004 0.416-0.002 0.626-0.010 8.218 0.024 16.499-2.34 23.769-7.578 3.367-2.29 6.435-5.105 9.064-8.462 1.812-2.184 3.419-4.545 4.757-7.089l466.17-678.065c0.14-0.204 0.256-0.412 0.392-0.616 0.342-0.516 0.678-1.034 0.996-1.564 0.274-0.45 0.53-0.902 0.784-1.356zM512 805.246l-115.996-463.984h231.991l-115.996 463.984zM602.923 255.948h-181.848l90.925-136.387 90.923 136.387zM682.63 221.703l-90.925-136.385h181.848l-90.923 136.385zM341.368 221.703l-90.923-136.385h181.848l-90.925 136.385zM804.584 192.577l43.397-65.094 64.232 128.465h-149.876l42.247-63.371zM176.019 127.483l85.644 128.465h-149.876l64.232-128.465zM308.063 341.264l105.26 421.037-289.465-421.037h184.205zM610.677 762.301l105.26-421.037h184.203l-289.463 421.037z"></path>
                                </svg>

                            </span>
                            <span class="title">
                                <?php echo(Registry::load('strings')->membership) ?>
                            </span>
                        </div>
                        <div class="child_menu">
                            <ul>
                                <?php
                                if (Registry::load('settings')->memberships === 'enable') {
                                    if (role(['permissions' => ['memberships' => 'view_membership_info']])) {
                                        ?>
                                        <li class="load_membership_info"><?php echo(Registry::load('strings')->your_current_info) ?></li>
                                        <li class='load_form' form="billing_info"><?php echo(Registry::load('strings')->billing_info) ?></li>
                                        <?php
                                    }
                                    if (role(['permissions' => ['memberships' => 'view_personal_transactions']])) {
                                        ?>
                                        <li class='load_aside' load="transactions"><?php echo(Registry::load('strings')->transactions) ?></li>
                                        <?php
                                    }
                                }

                                if (role(['permissions' => ['memberships' => 'view_site_transactions']])) {
                                    ?>
                                    <li class='load_aside' load="site_transactions"><?php echo(Registry::load('strings')->site_transactions) ?></li>
                                    <?php
                                }

                                if (role(['permissions' => ['membership_packages' => 'view']])) {
                                    ?>
                                    <li class="load_aside" load="membership_packages"><?php echo(Registry::load('strings')->packages) ?></li>
                                    <?php

                                }
                                if (role(['permissions' => ['bank_transfer_receipts' => 'view']])) {
                                    ?>
                                    <li class="load_aside" load="bank_transfer_receipts"><?php echo(Registry::load('strings')->bank_receipts) ?></li>
                                    <?php

                                }
                                if (role(['permissions' => ['super_privileges' => 'manage_payment_gateways']])) {
                                    ?>
                                    <li class="load_aside" load="payment_methods"><?php echo(Registry::load('strings')->payment_methods) ?></li>
                                    <?php

                                }
                                ?>
                            </ul>
                        </div>
                    </li>
                    <?php
                }
            }
            ?>
            <?php
            $module_permissions = array();

            if (role(['permissions' => ['custom_menu' => 'view']])) {
                $module_permissions['custom_menu'] = true;
            }

            if (role(['permissions' => ['custom_fields' => 'view']])) {
                $module_permissions['custom_fields'] = true;
            }

            if (role(['permissions' => ['custom_pages' => 'view']])) {
                $module_permissions['custom_pages'] = true;
            }

            if (role(['permissions' => ['stickers' => 'view']])) {
                $module_permissions['sticker_packs'] = true;
            }

            if (role(['permissions' => ['avatars' => 'view']])) {
                $module_permissions['avatars'] = true;
            }

            if (role(['permissions' => ['languages' => 'view']])) {
                $module_permissions['languages'] = true;
            }

            if (role(['permissions' => ['site_roles' => 'view']])) {
                $module_permissions['site_roles'] = true;
            }

            if (role(['permissions' => ['social_login_providers' => 'view']])) {
                $module_permissions['social_login_providers'] = true;
            }

            if (role(['permissions' => ['audio_player' => 'view']])) {
                $module_permissions['audio_player'] = true;
            }

            if (role(['permissions' => ['site_adverts' => 'view']])) {
                $module_permissions['site_adverts'] = true;
            }

            if (role(['permissions' => ['group_roles' => 'view']])) {
                $module_permissions['group_roles'] = true;
            }

            if (role(['permissions' => ['badges' => 'view']])) {
                $module_permissions['badges'] = true;
            }

            if (role(['permissions' => ['super_privileges' => 'firewall']])) {
                $module_permissions['firewall'] = true;
            }

            if (role(['permissions' => ['super_privileges' => 'email_validator']])) {
                $module_permissions['email_validator'] = true;
            }

            if (role(['permissions' => ['super_privileges' => 'link_filter']])) {
                $module_permissions['link_filter'] = true;
            }

            if (role(['permissions' => ['super_privileges' => 'profanity_filter']])) {
                $module_permissions['profanity_filter'] = true;
            }

            if (role(['permissions' => ['super_privileges' => 'cron_jobs']])) {
                $module_permissions['cron_jobs'] = true;
            }

            if (role(['permissions' => ['super_privileges' => 'message_scheduler']])) {
                $module_permissions['message_scheduler'] = true;
            }

            if (!empty($module_permissions)) {
                ?>
                <li class="has_child">
                    <div class="menu_item">
                        <span class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M504.128 65.024c-8.855 2.624-16.492 5.801-23.704 9.669l0.664-0.325c-26.88 13.504-392.064 203.52-397.056 206.656-21.12 13.12-27.2 33.472-16.832 56.256 2.624 5.76 7.552 13.184 11.008 16.576 8.32 8.064 409.728 217.024 424 220.672 2.916 1.064 6.282 1.68 9.792 1.68s6.876-0.616 9.997-1.745l-0.205 0.065c12.352-3.136 416.064-212.8 425.664-220.992 7.253-6.236 12.388-14.753 14.291-24.43l0.045-0.274c2.304-12.48-6.592-34.56-17.792-44.032-5.888-5.056-399.296-210.88-414.912-217.088-5.035-2.15-10.892-3.399-17.042-3.399-2.799 0-5.537 0.259-8.193 0.754l0.275-0.043zM652.544 239.168c76.16 39.552 142.208 73.984 146.752 76.544l8.256 4.672-146.624 76.16c-80.576 41.92-147.648 76.224-148.928 76.224-2.816 0-295.68-152-294.72-152.96-18.929 4.824 77.53-45.985 174.419-96.078l120.045-56.434c1.28 0 64.64 32.32 140.8 71.872zM95.552 463.68c-17.728 6.336-36.288 38.208-33.408 57.472 1.472 9.472 8.256 19.84 17.536 26.688 7.36 5.376 403.776 212.16 416.576 217.28 4.682 1.764 10.094 2.785 15.744 2.785s11.062-1.021 16.061-2.889l-0.317 0.104c12.8-5.12 409.216-211.904 416.576-217.28 8.798-6.321 15.111-15.619 17.482-26.396l0.054-0.292c2.304-15.552-10.944-43.2-25.344-52.8-6.186-3.631-13.624-5.776-21.563-5.776-4.784 0-9.386 0.779-13.686 2.216l0.304-0.088c-4.032 1.408-93.312 47.232-198.4 101.888l-191.168 99.328-191.168-99.328c8.596 1.193-56.137-32.677-121.174-66.037l-76.394-35.595c-5.039-1.396-10.825-2.199-16.799-2.199-3.855 0-7.632 0.334-11.303 0.975l0.391-0.056zM95.936 655.808c-16.256 5.184-33.728 32-33.536 51.52 0.064 12.48 4.48 21.952 14.144 30.208 9.6 8.192 413.312 217.856 425.664 220.992 2.916 1.064 6.282 1.68 9.792 1.68s6.876-0.616 9.997-1.745l-0.205 0.065c12.352-3.136 416.064-212.8 425.664-220.992 8.848-7.12 14.462-17.948 14.462-30.087 0-3.223-0.396-6.354-1.141-9.346l0.056 0.265c-5.504-20.352-19.84-38.528-33.792-42.688-3.345-1.174-7.202-1.853-11.217-1.853-5.016 0-9.784 1.059-14.094 2.965l0.223-0.088c-4.672 1.664-93.824 47.296-198.016 101.504-104.256 54.208-190.656 98.56-191.936 98.56s-87.68-44.352-191.936-98.56c-104.192-54.208-193.344-99.84-198.016-101.504-4.251-1.702-9.178-2.69-14.336-2.69-4.204 0-8.255 0.656-12.056 1.871l0.28-0.077z"></path>
                            </svg>
                        </span>
                        <span class="title">
                            <?php echo Registry::load('strings')->modules ?>
                        </span>
                    </div>
                    <div class="child_menu">
                        <ul>
                            <?php if (isset($module_permissions['custom_menu'])) {
                                ?>
                                <li class="load_aside" load="custom_menu_items"><?php echo Registry::load('strings')->custom_menu ?></li>
                                <?php
                            } if (isset($module_permissions['custom_fields'])) {
                                ?>
                                <li class="load_aside" load="custom_fields"><?php echo(Registry::load('strings')->custom_fields) ?></li>
                                <?php
                            } if (isset($module_permissions['custom_pages'])) {
                                ?>
                                <li class="load_aside" load="custom_pages"><?php echo(Registry::load('strings')->custom_pages) ?></li>
                                <?php
                            } if (isset($module_permissions['sticker_packs'])) {
                                ?>
                                <li class="load_aside" load="sticker_packs"><?php echo(Registry::load('strings')->sticker_packs) ?></li>
                                <?php
                            } if (isset($module_permissions['avatars'])) {
                                ?>
                                <li class="load_aside" load="avatars"><?php echo(Registry::load('strings')->avatars) ?></li>
                                <?php
                            } if (isset($module_permissions['languages'])) {
                                ?>
                                <li class="load_aside" load="languages"><?php echo(Registry::load('strings')->languages) ?></li>
                                <?php
                            } if (isset($module_permissions['site_roles'])) {
                                ?>
                                <li class="load_aside" load="site_roles"><?php echo(Registry::load('strings')->site_roles) ?></li>
                                <?php
                            } if (isset($module_permissions['group_roles'])) {
                                ?>
                                <li class="load_aside" load="group_roles"><?php echo(Registry::load('strings')->group_roles) ?></li>
                                <?php
                            }  if (isset($module_permissions['social_login_providers'])) {
                                ?>
                                <li class="load_aside" load="social_login_providers"><?php echo(Registry::load('strings')->social_login) ?></li>
                                <?php
                            } if (isset($module_permissions['audio_player'])) {
                                ?>
                                <li class="load_aside" load="audio_player_contents"><?php echo(Registry::load('strings')->audio_player) ?></li>
                                <?php
                            } if (isset($module_permissions['site_adverts'])) {
                                ?>
                                <li class="load_aside" load="site_adverts"><?php echo(Registry::load('strings')->site_adverts) ?></li>
                                <?php
                            } if (isset($module_permissions['badges'])) {
                                ?>
                                <li class="load_aside" load="badges"><?php echo(Registry::load('strings')->badges) ?></li>
                                <?php
                            }
                            if (isset($module_permissions['firewall'])) {
                                ?>
                                <li class="load_form" form="firewall" todo="edit">
                                    <?php echo(Registry::load('strings')->firewall) ?>
                                </li>
                                <?php
                            }
                            if (isset($module_permissions['email_validator'])) {
                                ?>
                                <li class="load_form" form="email_validator" todo="edit">
                                    <?php echo(Registry::load('strings')->email_validator) ?>
                                </li>
                                <?php
                            }
                            if (isset($module_permissions['link_filter'])) {
                                ?>
                                <li class="load_form" form="link_filter" todo="edit">
                                    <?php echo(Registry::load('strings')->link_filter) ?>
                                </li>
                                <?php
                            }
                            if (isset($module_permissions['profanity_filter'])) {
                                ?>
                                <li class="load_form" form="profanity_filter" todo="edit">
                                    <?php echo(Registry::load('strings')->profanity_filter) ?>
                                </li>
                                <?php
                            }
                            if (isset($module_permissions['message_scheduler'])) {
                                ?>
                                <li class="load_aside" load="scheduled_messages">
                                    <?php echo Registry::load('strings')->message_scheduler ?>
                                </li>
                                <?php
                            }
                            if (isset($module_permissions['cron_jobs'])) {
                                ?>
                                <li class="load_aside" load="cron_jobs">
                                    <?php echo Registry::load('strings')->cron_jobs ?>
                                </li>
                                <?php
                            } ?>
                        </ul>
                    </div>
                </li>
                <?php
            }
            ?>


            <?php
            $settings_permissions = array();

            if (role(['permissions' => ['super_privileges' => 'core_settings']])) {
                $settings_permissions['core_settings'] = true;
            }

            if (role(['permissions' => ['super_privileges' => 'header_footer']])) {
                $settings_permissions['headers_footers'] = true;
            }

            if (role(['permissions' => ['super_privileges' => 'slideshows']])) {
                $settings_permissions['slideshows'] = true;
            }

            if (role(['permissions' => ['super_privileges' => 'customizer']])) {
                $settings_permissions['customizer'] = true;
            }

            if (!empty($settings_permissions)) {
                ?>
                <li class="has_child">
                    <div class="menu_item">
                        <span class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M983.162 385.156l-111.835-13.828c-1.838-4.702-3.768-9.364-5.784-13.986l69.296-88.855c14.454-18.534 12.824-44.928-3.798-61.546l-114.055-113.991c-16.616-16.608-43.004-18.228-61.526-3.786l-88.823 69.268c-4.624-2.016-9.288-3.948-13.988-5.788l-13.83-111.807c-2.886-23.32-22.696-40.83-46.194-40.83h-161.273c-23.496 0-43.312 17.51-46.194 40.832l-13.828 111.807c-4.698 1.838-9.362 3.768-13.976 5.78l-88.829-69.284c-18.522-14.444-44.916-12.828-61.536 3.788l-114.041 114.017c-16.62 16.614-18.246 43.012-3.792 61.542l69.288 88.833c-2.024 4.624-3.956 9.294-5.8 14.004l-111.811 13.828c-23.318 2.888-40.832 22.698-40.832 46.196v161.279c0 23.5 17.516 43.316 40.842 46.194l111.801 13.806c1.838 4.704 3.768 9.372 5.788 13.988l-69.282 88.833c-14.448 18.528-12.822 44.92 3.788 61.534l114.041 114.061c16.614 16.62 43.014 18.242 61.546 3.792l88.833-69.296c4.612 2.014 9.276 3.948 13.974 5.782l13.828 111.841c2.882 23.32 22.696 40.832 46.194 40.832h161.273c23.496 0 43.31-17.51 46.194-40.832l13.83-111.839c4.702-1.84 9.364-3.77 13.986-5.784l88.849 69.3c18.532 14.45 44.926 12.828 61.542-3.796l114.029-114.061c16.614-16.616 18.234-43.008 3.786-61.536l-69.282-88.817c2.024-4.626 3.956-9.294 5.796-14.004l111.801-13.806c23.322-2.88 40.842-22.696 40.842-46.194v-161.279c-0.006-23.496-17.52-43.306-40.838-46.192zM930.905 551.477l-99.727 12.316c-18.302 2.262-33.544 15.112-38.862 32.772-5.626 18.674-13.12 36.774-22.276 53.806-8.734 16.248-7.050 36.126 4.294 50.67l61.796 79.226-55.848 55.864-79.242-61.81c-14.538-11.336-34.404-13.026-50.654-4.3-17.026 9.144-35.13 16.638-53.81 22.268-17.654 5.322-30.5 20.558-32.762 38.854l-12.338 99.765h-78.984l-12.334-99.765c-2.262-18.292-15.102-33.526-32.75-38.85-18.734-5.65-36.834-13.142-53.798-22.264-16.244-8.738-36.122-7.054-50.672 4.294l-79.23 61.804-55.854-55.87 61.794-79.236c11.342-14.546 13.026-34.412 4.294-50.656-9.136-17.002-16.63-35.108-22.274-53.822-5.324-17.65-20.564-30.494-38.86-32.752l-99.721-12.312v-78.984l99.731-12.334c18.292-2.262 33.526-15.102 38.846-32.75 5.648-18.712 13.144-36.812 22.286-53.8 8.748-16.248 7.066-36.134-4.286-50.684l-61.806-79.24 55.848-55.836 79.24 61.804c14.546 11.344 34.418 13.026 50.67 4.292 16.986-9.13 35.082-16.62 53.792-22.264 17.65-5.324 30.494-20.558 32.756-38.854l12.332-99.733h78.988l12.338 99.737c2.266 18.296 15.114 33.534 32.768 38.854 18.656 5.622 36.758 13.114 53.804 22.27 16.244 8.728 36.11 7.044 50.65-4.298l79.232-61.788 55.854 55.824-61.794 79.236c-11.332 14.534-13.026 34.382-4.316 50.622 9.16 17.080 16.656 35.198 22.276 53.846 5.322 17.65 20.558 30.496 38.854 32.762l99.755 12.338v78.978z"></path>
                                <path fill="currentColor" d="M511.991 310.304c-111.211 0-201.689 90.487-201.689 201.711 0 111.205 90.477 201.677 201.689 201.677 111.217 0 201.703-90.471 201.703-201.677 0-111.223-90.485-201.711-201.703-201.711zM511.991 620.602c-59.882 0-108.599-48.712-108.599-108.587 0-59.894 48.718-108.621 108.599-108.621 59.888 0 108.611 48.726 108.611 108.621 0 59.876-48.724 108.587-108.611 108.587z"></path>
                            </svg>
                        </span>
                        <span class="title">
                            <?php echo Registry::load('strings')->settings ?>
                        </span>
                    </div>
                    <div class="child_menu">
                        <ul>
                            <?php
                            if (isset($settings_permissions['customizer'])) {
                                ?>
                                <li class="load_form" form="appearance" todo="edit">
                                    <?php echo(Registry::load('strings')->appearance) ?>
                                </li>
                                <?php
                            }
                            if (isset($settings_permissions['core_settings'])) {
                                ?>
                                <li class="load_form" form="settings" data-category="general_settings">
                                    <?php echo Registry::load('strings')->general_settings ?>
                                </li>

                                <li class="load_form" form="settings" data-category="email_settings">
                                    <?php echo Registry::load('strings')->email_settings ?>
                                </li>

                                <li class="load_form" form="settings" data-category="sms_settings">
                                    <?php echo Registry::load('strings')->sms_settings ?>
                                </li>

                                <li class="load_form" form="email_contents" enlarge=true todo="edit">
                                    <?php echo(Registry::load('strings')->email_contents) ?>
                                </li>

                                <li class="load_form" form="settings" data-category="login_settings">
                                    <?php echo Registry::load('strings')->login_settings ?>
                                </li>

                                <li class="load_form" form="role_attributes">
                                    <?php echo Registry::load('strings')->role_attributes ?>
                                </li>


                                <?php
                                if (isset(Registry::load('config')->pro_version) && !empty(Registry::load('config')->pro_version)) {
                                    ?>
                                    <li class="load_form" form="settings" data-category="memberships">
                                        <?php echo Registry::load('strings')->memberships ?>
                                    </li>
                                    <?php
                                } ?>
                                <li class="load_form" form="settings" data-category="message_settings">
                                    <?php echo Registry::load('strings')->message_settings ?>
                                </li>
                                <li class="load_form" form="settings" data-category="cloud_storage">
                                    <?php echo Registry::load('strings')->cloud_storage ?>
                                </li>
                                <li class="load_form" form="settings" data-category="video_audio_chat">
                                    <?php echo Registry::load('strings')->video_audio_chat ?>
                                </li>
                                <li class="load_form" form="settings" data-category="moderation_settings">
                                    <?php echo Registry::load('strings')->moderation_settings ?>
                                </li>

                                <li class="load_form" form="settings" data-category="notification_settings">
                                    <?php echo Registry::load('strings')->notifications ?>
                                </li>

                                <li class="load_form" form="settings" data-category="ip_intelligence">
                                    <?php echo Registry::load('strings')->ip_intelligence ?>
                                </li>

                                <li class="load_form" form="settings" data-category="pwa_settings">
                                    <?php echo Registry::load('strings')->pwa_settings ?>
                                </li>

                                <li class="load_form" form="settings" data-category="realtime_settings">
                                    <?php echo Registry::load('strings')->realtime_settings ?>
                                </li>

                                <li class="load_form" form="landing_page" enlarge=true todo="edit">
                                    <?php echo(Registry::load('strings')->landing_page) ?>
                                </li>

                                <li class="load_form" form="welcome_screen" todo="edit">
                                    <?php echo(Registry::load('strings')->welcome_screen) ?>
                                </li>
                                <?php
                            }
                            if (isset($settings_permissions['customizer'])) {
                                ?>
                                <li class="load_form" form="custom_css" todo="edit" enlarge=true>
                                    <?php echo(Registry::load('strings')->custom_css) ?>
                                </li>
                                <li class="load_form" form="custom_js" todo="edit" enlarge=true>
                                    <?php echo(Registry::load('strings')->custom_js) ?>
                                </li>
                                <?php
                            }

                            if (isset($settings_permissions['slideshows'])) {
                                ?>
                                <li class="load_aside" load="slideshows">
                                    <?php echo(Registry::load('strings')->slideshows) ?>
                                </li>
                                <?php
                            }

                            if (isset($settings_permissions['core_settings'])) {
                                ?>
                                <li class="load_form" form="rebuild_cache">
                                    <?php echo(Registry::load('strings')->rebuild_cache) ?>
                                </li>
                                <li class="load_form" form="license_info">
                                    <?php echo(Registry::load('strings')->license) ?>
                                </li>
                                <li class="load_form" form="system_info">
                                    <?php echo(Registry::load('strings')->system_info) ?>
                                </li>
                                <?php
                            }
                            if (isset($settings_permissions['headers_footers'])) {
                                ?>

                                <li class="load_form" form="headers_footers" todo="edit" enlarge=true>
                                    <?php echo Registry::load('strings')->headers_footers ?>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </li>
                <?php
            }
            ?>

            <?php if (role(['permissions' => ['storage' => 'super_privileges']])) {
                ?>
                <li class="load_aside" load="storage">
                    <div class="menu_item">
                        <span class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M981.303 426.653h-42.663v-127.992c0-23.562-19.101-42.663-42.663-42.663h-85.329v-42.663c0-11.315-4.496-22.167-12.495-30.168l-170.657-170.657c-8.001-8.001-18.853-12.495-30.168-12.495h-383.975c-23.562 0-42.663 19.101-42.663 42.663v85.329h-127.992c-23.562 0-42.663 19.101-42.663 42.663v809.428c-0.056 1.886 0.002 3.756 0.194 5.6 0 0.008 0.002 0.016 0.002 0.024 0.090 0.852 0.204 1.698 0.344 2.538 2.412 16.543 13.297 27.798 26.57 32.786 0.206 0.080 0.416 0.156 0.624 0.234 0.52 0.186 1.034 0.378 1.56 0.546 2.070 0.678 4.218 1.196 6.43 1.554 0.086 0.014 0.172 0.026 0.26 0.040 0.902 0.14 1.816 0.252 2.74 0.336 0.358 0.034 0.714 0.072 1.072 0.098 0.258 0.016 0.52 0.022 0.778 0.034 1.172 0.066 2.344 0.088 3.516 0.064h766.525c18.363 0 34.668-11.751 40.475-29.172l170.655-511.967c9.207-27.63-11.355-56.158-40.475-56.158zM853.311 341.324v85.329h-42.663v-85.329h42.663zM256.015 85.34h323.639l145.665 145.665v195.647h-469.304v-341.312zM85.36 213.332h85.329v249.060l-85.329 255.984v-505.043zM779.898 938.62h-678.008l142.213-426.639h678.006l-142.211 426.639z"></path>
                                <path fill="currentColor" d="M384.007 255.996h213.32c23.562 0 42.663-19.101 42.663-42.663s-19.101-42.663-42.663-42.663h-213.32c-23.562 0-42.663 19.101-42.663 42.663s19.101 42.663 42.663 42.663z"></path>
                                <path fill="currentColor" d="M384.007 383.987h213.32c23.562 0 42.663-19.101 42.663-42.663s-19.101-42.663-42.663-42.663h-213.32c-23.562 0-42.663 19.101-42.663 42.663s19.101 42.663 42.663 42.663z"></path>
                            </svg>
                        </span>
                        <span class="title">
                            <?php echo(Registry::load('strings')->storage) ?>
                        </span>
                    </div>
                </li>
                <?php
            } else if (role(['permissions' => ['storage' => 'access_storage']])) {
                ?>

                <li class="load_aside" load="site_user_files">
                    <div class="menu_item">
                        <span class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M981.303 426.653h-42.663v-127.992c0-23.562-19.101-42.663-42.663-42.663h-85.329v-42.663c0-11.315-4.496-22.167-12.495-30.168l-170.657-170.657c-8.001-8.001-18.853-12.495-30.168-12.495h-383.975c-23.562 0-42.663 19.101-42.663 42.663v85.329h-127.992c-23.562 0-42.663 19.101-42.663 42.663v809.428c-0.056 1.886 0.002 3.756 0.194 5.6 0 0.008 0.002 0.016 0.002 0.024 0.090 0.852 0.204 1.698 0.344 2.538 2.412 16.543 13.297 27.798 26.57 32.786 0.206 0.080 0.416 0.156 0.624 0.234 0.52 0.186 1.034 0.378 1.56 0.546 2.070 0.678 4.218 1.196 6.43 1.554 0.086 0.014 0.172 0.026 0.26 0.040 0.902 0.14 1.816 0.252 2.74 0.336 0.358 0.034 0.714 0.072 1.072 0.098 0.258 0.016 0.52 0.022 0.778 0.034 1.172 0.066 2.344 0.088 3.516 0.064h766.525c18.363 0 34.668-11.751 40.475-29.172l170.655-511.967c9.207-27.63-11.355-56.158-40.475-56.158zM853.311 341.324v85.329h-42.663v-85.329h42.663zM256.015 85.34h323.639l145.665 145.665v195.647h-469.304v-341.312zM85.36 213.332h85.329v249.060l-85.329 255.984v-505.043zM779.898 938.62h-678.008l142.213-426.639h678.006l-142.211 426.639z"></path>
                                <path fill="currentColor" d="M384.007 255.996h213.32c23.562 0 42.663-19.101 42.663-42.663s-19.101-42.663-42.663-42.663h-213.32c-23.562 0-42.663 19.101-42.663 42.663s19.101 42.663 42.663 42.663z"></path>
                                <path fill="currentColor" d="M384.007 383.987h213.32c23.562 0 42.663-19.101 42.663-42.663s-19.101-42.663-42.663-42.663h-213.32c-23.562 0-42.663 19.101-42.663 42.663s19.101 42.663 42.663 42.663z"></path>
                            </svg>
                        </span>
                        <span class="title">
                            <?php echo(Registry::load('strings')->storage) ?>
                        </span>
                    </div>
                </li>
                <?php
            } ?>

            <?php if (role(['permissions' => ['complaints' => ['track_status', 'review_complaints']], 'condition' => 'OR'])) {
                ?>
                <li class="load_aside realtime_module load_complaints" module="complaints" load="complaints">
                    <div class="menu_item">
                        <span class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M904.368 128.828l-26.24-5.248c-120.396-24.078-237.118-64.132-347.052-119.078-12.008-6.002-26.142-6.002-38.15 0-109.97 54.964-226.654 95.006-347.050 119.078l-26.242 5.248c-19.944 3.988-34.298 21.5-34.298 41.838v157.098c0 183.904 73.062 360.302 203.088 490.33l193.408 193.408c16.662 16.662 43.678 16.662 60.34 0l193.408-193.408c130.028-130.028 203.088-306.426 203.088-490.33v-157.098c-0.002-20.338-14.356-37.848-34.3-41.838zM853.334 327.766c0 161.272-64.072 315.966-178.096 429.99l-163.238 163.238-163.238-163.238c-114.024-114.024-178.096-268.718-178.096-429.99v-122.144c118.052-24.292 232.672-63.064 341.334-115.428 108.636 52.348 223.284 91.128 341.334 115.428v122.144z"></path>
                                <path fill="currentColor" d="M494.99 191.876c-67.496 29.36-136.896 53.892-207.646 73.384-18.51 5.1-31.334 21.934-31.334 41.134v21.376c0 139.586 54.426 270.972 153.134 369.632l72.688 72.73c26.874 26.89 72.846 7.858 72.846-30.162v-508.968c-0.002-30.73-31.51-51.384-59.688-39.126zM469.342 636.924c-80.082-80.114-125.176-185.56-127.872-298.402 43.176-12.83 85.838-27.446 127.872-43.806v342.208z"></path>
                            </svg>
                        </span>
                        <span class="title">
                            <?php echo(Registry::load('strings')->complaints) ?>
                        </span>
                        <span class="unread"></span>
                    </div>
                </li>
                <?php
            } ?>

            <?php
            if (role(['permissions' => ['audio_player' => 'listen_music']])) {
                ?>
                <li class="load_audio_player">
                    <div class="menu_item">
                        <span class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M944 419.2c-6.4-38.4-28.8-70.4-64-92.8-57.6-38.4-105.6-92.8-137.6-153.6l-12.8-25.6c-6.4-12.8-22.4-19.2-35.2-16-12.8 0-22.4 12.8-22.4 28.8v505.6c-25.6-16-60.8-25.6-96-25.6-89.6 0-160 57.6-160 128s70.4 128 160 128 160-57.6 160-128v-492.8c32 38.4 67.2 73.6 108.8 102.4 19.2 12.8 32 32 35.2 54.4s0 44.8-12.8 64c-9.6 16-6.4 35.2 9.6 44.8s35.2 6.4 44.8-9.6c22.4-35.2 32-73.6 22.4-112z"></path>
                                <path fill="currentColor" d="M320 800h-224c-19.2 0-32 12.8-32 32s12.8 32 32 32h224c19.2 0 32-12.8 32-32s-12.8-32-32-32z"></path>
                                <path fill="currentColor" d="M320 640h-224c-19.2 0-32 12.8-32 32s12.8 32 32 32h224c19.2 0 32-12.8 32-32s-12.8-32-32-32z"></path>
                                <path fill="currentColor" d="M96 544h448c19.2 0 32-12.8 32-32s-12.8-32-32-32h-448c-19.2 0-32 12.8-32 32s12.8 32 32 32z"></path>
                                <path fill="currentColor" d="M96 384h448c19.2 0 32-12.8 32-32s-12.8-32-32-32h-448c-19.2 0-32 12.8-32 32s12.8 32 32 32z"></path>
                                <path fill="currentColor" d="M96 224h448c19.2 0 32-12.8 32-32s-12.8-32-32-32h-448c-19.2 0-32 12.8-32 32s12.8 32 32 32z"></path>
                            </svg>
                        </span>
                        <span class="title">
                            <?php echo(Registry::load('strings')->audio_player) ?>
                        </span>
                    </div>
                </li>
                <?php
            }

            if (!empty(Registry::load('current_user')->site_role_attribute)) {
                if (Registry::load('current_user')->site_role_attribute === 'guest_users') {
                    if (Registry::load('settings')->allow_guest_users_create_accounts === 'yes') {
                        ?>
                        <li class="load_form" form="upgrade_guest_account">
                            <div class="menu_item">
                                <span class="icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                        <path fill="currentColor" d="M154.88 67.264c-9.662 3.408-17.386 10.259-21.851 19.044l-0.101 0.22-4.096 8.192v834.56l4.16 8.32c5.236 9.703 13.938 16.968 24.409 20.209l0.295 0.079c10.112 3.072 314.496 3.072 324.608 0 17.92-5.44 27.584-18.944 29.248-40.64 1.792-24.128-5.888-40.192-23.168-48.256l-9.664-4.544-255.168-1.024v-702.976h576.896v221.76c0 240.768-0.448 229.952 9.024 242.752 5.632 7.68 19.904 13.76 34.304 14.656 17.408 1.088 30.272-2.816 39.68-12.16 13.504-13.568 12.672 5.952 12.16-279.424l-0.448-253.312-4.096-8.192c-4.566-9.005-12.29-15.856-21.682-19.181l-0.27-0.083c-9.536-3.392-704.704-3.392-714.24 0zM351.488 258.304c-18.176 4.928-27.648 15.936-30.4 35.328-3.52 24.96 4.096 44.288 21.12 53.44l6.784 3.648h163.008c155.84 0 163.264-0.128 168.96-3.2 17.792-9.472 25.536-28.544 21.952-53.952-2.24-15.872-8.32-25.088-21.056-31.552l-10.048-5.184-156.288-0.32c-119.872-0.256-158.080 0.192-164.032 1.792zM349.824 450.432c-20.224 6.272-29.248 20.224-29.184 45.376 0.064 23.36 6.144 35.2 22.208 43.072l9.6 4.672 154.368 0.512c104.768 0.32 157.248-0.128 163.2-1.344 14.916-2.731 26.832-13.284 31.469-27.169l0.083-0.287c1.146-5.452 1.801-11.716 1.801-18.133 0-0.465-0.003-0.93-0.010-1.393l0.001 0.070c0.064-22.016-6.080-34.048-21.568-42.432l-6.784-3.648-160.448-0.32c-88.256-0.192-162.368 0.32-164.736 1.024zM351.488 642.304c-18.24 4.992-27.648 15.936-30.4 35.328-3.52 24.96 4.096 44.288 21.12 53.44 6.528 3.52 9.088 3.648 69.824 4.16 67.904 0.576 73.088 0.064 84.864-8.64 7.744-5.696 13.76-19.904 14.72-34.368 1.472-23.040-6.912-40.128-23.488-47.808-7.296-3.392-10.88-3.584-68.288-3.904-44.352-0.256-62.656 0.192-68.352 1.792zM896.64 705.536c-5.44 2.56-39.36 29.632-91.008 72.64l-82.368 68.608-36.928-36.544c-40.704-40.192-46.72-44.48-62.656-44.288-11.776 0.064-21.76 4.992-33.152 16.32-12.032 11.968-17.728 24.064-16.768 35.648 0.612 5.841 1.991 11.193 4.045 16.197l-0.141-0.389c2.112 4.48 22.656 26.304 60.48 64.192 53.76 53.824 57.984 57.6 67.584 61.184 12.288 4.544 21.312 3.84 33.024-2.56 7.936-4.352 193.664-158.016 208.768-172.736 20.032-19.52 19.712-41.728-1.088-64.832-15.040-16.768-32.512-21.44-49.792-13.44z"></path>
                                    </svg>
                                </span>
                                <span class="title">
                                    <?php echo(Registry::load('strings')->create_account) ?>
                                </span>
                            </div>
                        </li>
                        <?php
                    }
                }
            }
            if (isset(Registry::load('current_user')->log_device) && empty(Registry::load('current_user')->log_device)) {
                if (isset(Registry::load('current_user')->login_from_user_id) && !empty(Registry::load('current_user')->login_from_user_id)) {
                    ?>
                    <li class="api_request" data-add="login_session" data-login_as_admin="true">
                        <div class="menu_item">
                            <span class="icon">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                                    <path fill="currentColor" d="M477.876 1.86c-74.485 7.166-146.931 40.723-202.014 93.572-18.020 17.29-23.25 31.963-17.834 50.037 2.71 9.048 14.99 22.402 23.984 26.086 9.12 3.734 24.054 3.86 32.499 0.274 3.552-1.51 12.538-8.26 19.968-15 22.222-20.164 38.823-31.617 64.027-44.173 39.009-19.432 68.893-26.354 113.492-26.292 42.517 0.060 73.509 6.794 108.578 23.594 53.157 25.468 94.168 65.379 119.996 116.78 19.804 39.413 27.426 75.751 27.426 130.742v26.52h-323.198c-352.614 0-330.294-0.616-343.526 9.478-3.094 2.36-7.756 7.912-10.358 12.338l-4.73 8.050-0.472 246.612c-0.518 270.345-0.904 258.851 9.552 284.545 12.3 30.223 38.787 56.729 68.957 69.007 25.786 10.494 7.466 9.968 347.776 9.968 340.238 0 322.004 0.522 347.718-9.948 30.031-12.23 56.779-38.963 68.979-68.943 10.374-25.494 9.97-14.080 9.958-280.717-0.006-162.613-0.596-245.814-1.774-250.054-2.638-9.498-11.688-20.214-21.214-25.124-7.842-4.040-10.428-4.4-35.399-4.904l-26.936-0.544-0.014-31.029c-0.022-47.743-4.672-79.535-17.274-118.070-49.441-151.183-198.631-248.156-358.168-232.806zM853.333 597.332v128h-682.666v-255.998h682.666v127.998zM326.982 514.685c-9.72 3.562-17.354 9.988-22.73 19.132l-4.732 8.050-0.518 51.529c-0.568 56.521-0.026 61.041 8.688 72.465 19.92 26.118 62.983 19.468 73.483-11.348 4.184-12.274 4.184-102.086 0-114.36-7.26-21.308-32.795-33.309-54.191-25.468zM497.649 514.685c-9.72 3.562-17.354 9.988-22.73 19.132l-4.732 8.050-0.518 51.529c-0.568 56.521-0.026 61.041 8.688 72.465 19.92 26.118 62.983 19.468 73.483-11.348 4.182-12.274 4.182-102.086 0-114.36-7.26-21.308-32.795-33.309-54.191-25.468zM668.316 514.685c-9.72 3.562-17.354 9.988-22.73 19.132l-4.732 8.050-0.518 51.529c-0.568 56.521-0.028 61.041 8.686 72.465 19.922 26.118 62.985 19.468 73.485-11.348 4.182-12.274 4.182-102.086 0-114.36-7.26-21.308-32.795-33.309-54.191-25.468zM853.333 856.443c0 52.697-0.904 57.617-12.754 69.469-13.826 13.824 13.742 12.754-328.58 12.754-342.32 0-314.752 1.070-328.578-12.754-11.85-11.852-12.754-16.772-12.754-69.469v-45.777h682.666v45.777z"></path>
                                </svg>
                            </span>
                            <span class="title">
                                <?php echo(Registry::load('strings')->login_as_admin) ?>
                            </span>
                        </div>
                    </li>
                    <?php
                }
            }
            ?>

            <?php
            if (role(['permissions' => ['profile' => 'switch_languages']])) {
                include 'layouts/chat_page/languages.php';
            }
            ?>

            <?php include 'layouts/chat_page/custom_menu_items_bottom.php'; ?>
        </ul>
    </div>
    <div class="bottom has_child side_navigation_footer">
        <div class="user_info">
            <span class="left">
                <img class="logged_in_user_avatar" loading="lazy" onerror="handleImageError(this)" src="<?php echo(get_img_url(['from' => 'site_users/profile_pics', 'image' => Registry::load('current_user')->profile_picture, 'gravatar' => Registry::load('current_user')->email_address])) ?>">
            </span>
            <span class="center">
                <span class="title logged_in_user_name"><?php echo Registry::load('current_user')->name; ?></span>
                <span class="sub_title">@<?php echo Registry::load('current_user')->username; ?></span>
                <span class="logged_in_user_name_color d-none"><?php echo role(['find' => 'name_color']); ?></span>
                <span class="logged_in_user_id d-none"><?php echo Registry::load('current_user')->id; ?></span>
            </span>
            <span class="right">
                <i class="icon"><i class="chevron"></i></i>
            </span>
        </div>
        <div class="child_menu">
            <span><i><?php echo Registry::load('current_user')->name; ?></i></span>
            <ul>
                <?php
                if (role(['permissions' => ['profile' => 'edit_profile']])) {
                    ?>
                    <li class='load_form' form='site_users' data-user_id="<?php echo(Registry::load('current_user')->id); ?>"><?php echo(Registry::load('strings')->edit_profile) ?></li>
                    <?php
                }
                ?>
                <li class='get_info' user_id="<?php echo(Registry::load('current_user')->id); ?>"><?php echo(Registry::load('strings')->view_profile) ?></li>

                <?php
                if (role(['permissions' => ['profile' => 'go_offline']])) {
                    if (empty(Registry::load('current_user')->offline_mode)) {
                        ?>
                        <li class='api_request' data-update="site_users_settings" data-offline_mode='go_offline'><?php echo(Registry::load('strings')->go_offline) ?></li>
                        <?php
                    } else {
                        ?>
                        <li class='api_request' data-update="site_users_settings" data-offline_mode='go_online'><?php echo(Registry::load('strings')->go_online) ?></li>
                        <?php
                    }
                }
                if (role(['permissions' => ['profile' => 'switch_color_scheme']])) {
                    if (Registry::load('current_user')->color_scheme === 'dark_mode') {
                        ?>
                        <li class='api_request'data-update="site_users_settings" data-color_scheme='light_mode'><?php echo(Registry::load('strings')->light_mode) ?></li>
                        <?php
                    } else {
                        ?>
                        <li class='api_request' data-update="site_users_settings" data-color_scheme='dark_mode'><?php echo(Registry::load('strings')->dark_mode) ?></li>
                        <?php
                    }
                }
                ?>
<?php if (false) { ?>
                <li class="api_request" data-remove="login_session"><?php echo(Registry::load('strings')->logout) ?></li>
<?php } ?>
            </ul>
        </div>
    </div>
</div>