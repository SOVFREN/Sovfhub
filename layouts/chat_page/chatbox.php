<?php
$chat_messages_box_class = '';
if (Registry::load('settings')->show_timestamp_on_mouseover === 'enable') {
    $chat_messages_box_class = ' show_timestamp_on_mouseover';
}
?>

<div class="header">

    <div class="go_back_icon">
        <span class="go_to_previous_page">
            <span class="icon_back">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                </svg>
            </span>
        </span>
    </div>

    <div class="message_selection d-none">
        <label class="selector select_all">
            <input type="checkbox" name="select_all_messages" value="1">
            <span class="checkmark"></span>
        </label>
    </div>
    <div class="image get_info" auto_find=true>
        <span class="thumbnail">
            <img class="image" />
        </span>
    </div>
    <div class="heading get_info" auto_find=true>
        <span class="title"></span>
        <span class="subtitle"></span>
        <span class="view_info"><?php echo(Registry::load('strings')->click_to_view_info); ?></span>
        <div class="whos_typing" last_logged_user_id=0>
            <ul></ul>
        </div>
    </div>
    <div class="icons">
        <?php
        if (Registry::load('current_user')->logged_in) {
            ?>
            <span class="d-md-none toggle_side_navigation">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 1024 1024">
                    <path fill="currentColor" d="M127.999 271.999c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0zM127.999 512c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0zM127.999 752.001c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0z"></path>
                </svg>
                <span class="total_unread_notifications"></span>
            </span>

            <span class="ask_confirmation delete_multiple_messages d-none" column="second" data-chat_messages=true multi_select="message_id" submit_button="<?php echo(Registry::load('strings')->yes); ?>" cancel_button="<?php echo(Registry::load('strings')->no); ?>" confirmation="<?php echo(Registry::load('strings')->confirm_delete); ?>">
                <svg width="19" height="19" viewBox="-3 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                        <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-259.000000, -203.000000)" fill="currentColor">
                            <path d="M282,211 L262,211 C261.448,211 261,210.553 261,210 C261,209.448 261.448,209 262,209 L282,209 C282.552,209 283,209.448 283,210 C283,210.553 282.552,211 282,211 L282,211 Z M281,231 C281,232.104 280.104,233 279,233 L265,233 C263.896,233 263,232.104 263,231 L263,213 L281,213 L281,231 L281,231 Z M269,206 C269,205.447 269.448,205 270,205 L274,205 C274.552,205 275,205.447 275,206 L275,207 L269,207 L269,206 L269,206 Z M283,207 L277,207 L277,205 C277,203.896 276.104,203 275,203 L269,203 C267.896,203 267,203.896 267,205 L267,207 L261,207 C259.896,207 259,207.896 259,209 L259,211 C259,212.104 259.896,213 261,213 L261,231 C261,233.209 262.791,235 265,235 L279,235 C281.209,235 283,233.209 283,231 L283,213 C284.104,213 285,212.104 285,211 L285,209 C285,207.896 284.104,207 283,207 L283,207 Z M272,231 C272.552,231 273,230.553 273,230 L273,218 C273,217.448 272.552,217 272,217 C271.448,217 271,217.448 271,218 L271,230 C271,230.553 271.448,231 272,231 L272,231 Z M267,231 C267.552,231 268,230.553 268,230 L268,218 C268,217.448 267.552,217 267,217 C266.448,217 266,217.448 266,218 L266,230 C266,230.553 266.448,231 267,231 L267,231 Z M277,231 C277.552,231 278,230.553 278,230 L278,218 C278,217.448 277.552,217 277,217 C276.448,217 276,217.448 276,218 L276,230 C276,230.553 276.448,231 277,231 L277,231 Z" id="trash" sketch:type="MSShapeGroup">

                            </path>
                        </g>
                    </g>
                </svg>
            </span>

            <?php

            if (Registry::load('settings')->video_chat !== 'disable') {
                ?>
                <span class="join_audio_call_icon join_video_call" audio_only="yes">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.97 18.33C21.97 18.69 21.89 19.06 21.72 19.42C21.55 19.78 21.33 20.12 21.04 20.44C20.55 20.98 20.01 21.37 19.4 21.62C18.8 21.87 18.15 22 17.45 22C16.43 22 15.34 21.76 14.19 21.27C13.04 20.78 11.89 20.12 10.75 19.29C9.6 18.45 8.51 17.52 7.47 16.49C6.44 15.45 5.51 14.36 4.68 13.22C3.86 12.08 3.2 10.94 2.72 9.81C2.24 8.67 2 7.58 2 6.54C2 5.86 2.12 5.21 2.36 4.61C2.6 4 2.98 3.44 3.51 2.94C4.15 2.31 4.85 2 5.59 2C5.87 2 6.15 2.06 6.4 2.18C6.66 2.3 6.89 2.48 7.07 2.74L9.39 6.01C9.57 6.26 9.7 6.49 9.79 6.71C9.88 6.92 9.93 7.13 9.93 7.32C9.93 7.56 9.86 7.8 9.72 8.03C9.59 8.26 9.4 8.5 9.16 8.74L8.4 9.53C8.29 9.64 8.24 9.77 8.24 9.93C8.24 10.01 8.25 10.08 8.27 10.16C8.3 10.24 8.33 10.3 8.35 10.36C8.53 10.69 8.84 11.12 9.28 11.64C9.73 12.16 10.21 12.69 10.73 13.22C11.27 13.75 11.79 14.24 12.32 14.69C12.84 15.13 13.27 15.43 13.61 15.61C13.66 15.63 13.72 15.66 13.79 15.69C13.87 15.72 13.95 15.73 14.04 15.73C14.21 15.73 14.34 15.67 14.45 15.56L15.21 14.81C15.46 14.56 15.7 14.37 15.93 14.25C16.16 14.11 16.39 14.04 16.64 14.04C16.83 14.04 17.03 14.08 17.25 14.17C17.47 14.26 17.7 14.39 17.95 14.56L21.26 16.91C21.52 17.09 21.7 17.3 21.81 17.55C21.91 17.8 21.97 18.05 21.97 18.33Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"></path>
                        <path d="M18.5 9C18.5 8.4 18.03 7.48 17.33 6.73C16.69 6.04 15.84 5.5 15 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M22 9C22 5.13 18.87 2 15 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
                <?php
            }

            if (Registry::load('settings')->video_chat !== 'disable') {
                ?>
                <span class="join_video_call_icon join_video_call d-none">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 1024 1024">
                        <path fill="currentColor" d="M713.6 780.8v-537.6h-604.8v537.6h604.8zM780.8 344l201.6-100.8v537.6l-201.6-100.8v134.4c0 18.557-15.043 33.6-33.6 33.6v0h-672c-18.557 0-33.6-15.043-33.6-33.6v0-604.8c0-18.557 15.043-33.6 33.6-33.6v0h672c18.557 0 33.6 15.043 33.6 33.6v0 134.4zM780.8 419.13v185.74l134.4 67.2v-320.14l-134.4 67.2zM176 310.4h201.6v67.2h-201.6v-67.2z"></path>
                    </svg>

                </span>
                <?php
            }
        }
        ?>
        <span class="reload_conversation d-none">
            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 20.75C10.0772 20.75 8.23311 19.9862 6.87348 18.6265C5.51384 17.2669 4.75 15.4228 4.75 13.5C4.75 11.5772 5.51384 9.73311 6.87348 8.37348C8.23311 7.01384 10.0772 6.25 12 6.25H14.5C14.6989 6.25 14.8897 6.32902 15.0303 6.46967C15.171 6.61032 15.25 6.80109 15.25 7C15.25 7.19891 15.171 7.38968 15.0303 7.53033C14.8897 7.67098 14.6989 7.75 14.5 7.75H12C10.8628 7.75 9.75105 8.08723 8.80547 8.71905C7.85989 9.35087 7.1229 10.2489 6.68769 11.2996C6.25249 12.3502 6.13862 13.5064 6.36048 14.6218C6.58235 15.7372 7.12998 16.7617 7.93414 17.5659C8.73829 18.37 9.76284 18.9177 10.8782 19.1395C11.9936 19.3614 13.1498 19.2475 14.2004 18.8123C15.2511 18.3771 16.1491 17.6401 16.781 16.6945C17.4128 15.7489 17.75 14.6372 17.75 13.5C17.75 13.3011 17.829 13.1103 17.9697 12.9697C18.1103 12.829 18.3011 12.75 18.5 12.75C18.6989 12.75 18.8897 12.829 19.0303 12.9697C19.171 13.1103 19.25 13.3011 19.25 13.5C19.2474 15.422 18.4827 17.2645 17.1236 18.6236C15.7645 19.9827 13.922 20.7474 12 20.75Z" fill="currentColor"></path>
                <path d="M12 10.75C11.9015 10.7505 11.8038 10.7313 11.7128 10.6935C11.6218 10.6557 11.5392 10.6001 11.47 10.53C11.3296 10.3894 11.2507 10.1988 11.2507 10C11.2507 9.80128 11.3296 9.61066 11.47 9.47003L13.94 7.00003L11.47 4.53003C11.3963 4.46137 11.3372 4.37857 11.2962 4.28657C11.2552 4.19457 11.2332 4.09526 11.2314 3.99455C11.2296 3.89385 11.2482 3.79382 11.2859 3.70043C11.3236 3.60705 11.3797 3.52221 11.451 3.45099C11.5222 3.37977 11.607 3.32363 11.7004 3.28591C11.7938 3.24819 11.8938 3.22966 11.9945 3.23144C12.0952 3.23322 12.1945 3.25526 12.2865 3.29625C12.3785 3.33724 12.4613 3.39634 12.53 3.47003L15.53 6.47003C15.6705 6.61066 15.7493 6.80128 15.7493 7.00003C15.7493 7.19878 15.6705 7.38941 15.53 7.53003L12.53 10.53C12.4608 10.6001 12.3782 10.6557 12.2872 10.6935C12.1962 10.7313 12.0985 10.7505 12 10.75Z" fill="currentColor"></path>
            </svg>
        </span>
        <div class="dropdown_button toggle_conversation_options icon">
            <div class="svg_icon">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 8H16M16 12H8M8 16H12M20 12V6C20 4.89543 19.1046 4 18 4H6C4.89543 4 4 4.89543 4 6V18C4 19.1046 4.89543 20 6 20H12" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    <path d="M18 15V18M18 21V18M18 18H21M18 18H15" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
            </div>
            <div class="dropdown_list">
                <ul>
                    <li class="toggle_search_messages hide_onClick">
                        <span class="icon">
                            <span class="icon">
                                <svg width="19" height="19" viewBox="0 -1 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">

                                    <g id="Free-Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                                        <g transform="translate(-1042.000000, -83.000000)" id="Group" stroke="currentColor" stroke-width="2">
                                            <g transform="translate(1041.000000, 80.000000)" id="Shape">
                                                <line x1="19.001707" y1="19.000116" x2="21.001266" y2="21.0001056">

                                                </line>
                                                <circle cx="16.5" cy="16.5" r="3.5">

                                                </circle>
                                                <polyline points="3.99663853 5 12 10 19.995112 5">

                                                </polyline>
                                                <path d="M9,18 C7.23084308,18 7.69638925,18 4.39663853,18 C3.62343988,18 2.99663853,17.4428432 2.99663853,16.7555556 L2.99663853,5.24444444 C2.99663853,4.55715676 3.62343988,4 4.39663853,4 L19.595112,4 C20.3683106,4 20.995112,4.55715676 20.995112,5.24444444 C20.9992147,7.80802349 21.001266,9.72654201 21.001266,11">

                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                        </span><?php echo Registry::load('strings')->search ?>
                    </li>
                    <li class="toggle_checkbox hide_onClick d-none">
                        <span class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M111.531 87.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM580.864 130.432c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177c-3.157 5.931-3.797 9.856-3.797 23.339 0 14.123 0.555 17.195 4.267 23.808 4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177c3.712-6.656 4.267-9.643 4.224-23.808-0.043-18.475-3.456-27.52-12.843-34.176-12.075-8.619-9.771-8.491-178.944-8.363-124.715 0.085-159.531 0.555-165.12 2.304zM384 277.333v106.667h-213.333v-213.333h213.333v106.667zM580.864 343.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177c-3.157 5.931-3.797 9.856-3.797 23.339 0 14.123 0.555 17.195 4.267 23.808 4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177c3.712-6.656 4.267-9.643 4.224-23.808-0.043-18.475-3.456-27.52-12.843-34.176-12.075-8.619-9.771-8.491-178.944-8.363-124.715 0.085-159.531 0.555-165.12 2.304zM111.531 557.099c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM580.864 599.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177c-3.157 5.931-3.797 9.856-3.797 23.339 0 14.123 0.555 17.195 4.267 23.808 4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177c3.712-6.656 4.267-9.643 4.224-23.808-0.043-18.475-3.456-27.52-12.843-34.176-12.075-8.619-9.771-8.491-178.944-8.363-124.715 0.085-159.531 0.555-165.12 2.304zM384 746.667v106.667h-213.333v-213.333h213.333v106.667zM580.864 813.099c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177c-3.157 5.931-3.797 9.856-3.797 23.339 0 14.123 0.555 17.195 4.267 23.808 4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177c3.712-6.656 4.267-9.643 4.224-23.808-0.043-18.475-3.456-27.52-12.843-34.176-12.075-8.619-9.771-8.491-178.944-8.363-124.715 0.085-159.531 0.555-165.12 2.304z"></path>
                            </svg>
                        </span><?php echo Registry::load('strings')->selection ?>
                    </li>

                    <?php
                    if (role(['permissions' => ['groups' => 'send_as_another_user']])) {
                        ?>
                        <li class="toggle_switch_user hide_onClick">
                            <span class="icon">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 1024 1024">
                                    <path fill="currentColor" d="M689.1 512.7c-17.1 0-34.3-12.9-42.9-30-8.6-21.4 8.6-47.1 30-51.4 47.1-12.9 81.4-51.4 94.3-94.3 17.1-68.6-25.7-141.4-94.3-158.6-21.4-4.3-38.6-30-30-51.4 4.3-21.4 30-38.6 51.4-30 111.4 25.7 184.3 145.7 154.3 261.5-21.4 77.2-81.4 132.9-154.3 154.3h-8.5zM374 89.1c-117.7 0-213.1 95.4-213.1 213.1s95.4 213 213.1 213 213.1-95.4 213.1-213.1-95.4-213-213.1-213zM374 423.1c-66.8 0-121-54.1-121-120.9s54.2-121 121-121 120.9 54.1 120.9 120.9-54.1 121-120.9 121zM552.8 600.9h-345.5c-114 0-207.3 93.3-207.3 207.3v80.6c0 25.3 20.7 46.1 46.1 46.1s46.1-20.6 46.1-46.1v-92.1c0-57.2 46.4-103.7 103.7-103.7h368.6c57.2 0 103.7 46.4 103.7 103.7v92.1c0 25.4 20.6 46.1 46.1 46.1 25.3 0 46.1-20.7 46.1-46.1v-80.6c-0.2-114-93.5-207.3-207.6-207.3zM825.1 601.1c-1.4-0.1-2.7-0.2-4.1-0.2-25.4 0-46.1 20.6-46.1 46.1 0 25.4 20.6 46.1 46.1 46.1h7.2c57.2 0 103.7 46.4 103.7 103.7v92.1c0 25.4 20.6 46.1 46.1 46.1 25.3 0 46.1-20.7 46.1-46.1v-80.6c-0.1-111.3-88.8-202.7-199-207.2z"></path>
                                </svg>
                            </span><?php echo Registry::load('strings')->switch_user ?>
                        </li>

                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <?php
    if (role(['permissions' => ['groups' => 'send_as_another_user']])) {
        ?>
        <div class="switch_user">
            <div class="current_selected_user">
                <span class="user_image toggle_switch_user"></span>
            </div>
            <span class="user_id d-none"><input type="text" /></span>
            <span class="username d-none"></span>
            <div class="search_users">
                <div class="search">
                    <div>
                        <i class="search_svg">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M689.1 512.7c-17.1 0-34.3-12.9-42.9-30-8.6-21.4 8.6-47.1 30-51.4 47.1-12.9 81.4-51.4 94.3-94.3 17.1-68.6-25.7-141.4-94.3-158.6-21.4-4.3-38.6-30-30-51.4 4.3-21.4 30-38.6 51.4-30 111.4 25.7 184.3 145.7 154.3 261.5-21.4 77.2-81.4 132.9-154.3 154.3h-8.5zM374 89.1c-117.7 0-213.1 95.4-213.1 213.1s95.4 213 213.1 213 213.1-95.4 213.1-213.1-95.4-213-213.1-213zM374 423.1c-66.8 0-121-54.1-121-120.9s54.2-121 121-121 120.9 54.1 120.9 120.9-54.1 121-120.9 121zM552.8 600.9h-345.5c-114 0-207.3 93.3-207.3 207.3v80.6c0 25.3 20.7 46.1 46.1 46.1s46.1-20.6 46.1-46.1v-92.1c0-57.2 46.4-103.7 103.7-103.7h368.6c57.2 0 103.7 46.4 103.7 103.7v92.1c0 25.4 20.6 46.1 46.1 46.1 25.3 0 46.1-20.7 46.1-46.1v-80.6c-0.2-114-93.5-207.3-207.6-207.3zM825.1 601.1c-1.4-0.1-2.7-0.2-4.1-0.2-25.4 0-46.1 20.6-46.1 46.1 0 25.4 20.6 46.1 46.1 46.1h7.2c57.2 0 103.7 46.4 103.7 103.7v92.1c0 25.4 20.6 46.1 46.1 46.1 25.3 0 46.1-20.7 46.1-46.1v-80.6c-0.1-111.3-88.8-202.7-199-207.2z"></path>
                            </svg>
                        </i>
                        <input type="search" placeholder="<?php echo(Registry::load('strings')->search_here) ?>">
                    </div>
                </div>
                <div class="list">
                    <ul></ul>
                </div>
            </div>
        </div>

        <?php
    }
    ?>

</div>

<div class="search_messages">
    <div>
        <div class="search">
            <div>
                <i class="search_svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                    </svg>
                </i>
                <input type="search" name="search_messages" placeholder="<?php echo(Registry::load('strings')->search_here) ?>">
            </div>
        </div>
    </div>
</div>

<div class="alert_message">
    <div>
        <div class="message">
            <span></span>
        </div>
    </div>
</div>

<div class="contents" read_more_criteria="<?php echo(Registry::load('settings')->read_more_criteria) ?>">
    <span class="date timestamp">
        <span></span>
    </span>
    <div class="chat_messages<?php echo $chat_messages_box_class; ?>">
        <ul></ul>
    </div>

    <div class="loader conversation_loader">
        <ul></ul>
    </div>
    <div class="error_message">
        <div>
            <div>
                <div class="image"></div>
                <div class="text">
                    <span class="title"></span>
                    <span class="subtitle"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="info_box">
    <div>
        <div class="content"></div>
    </div>
</div>

<div class="footer">

    <?php
    if (Registry::load('current_user')->logged_in) {
        ?>

        <div class="grid_list module hidden">
            <div class="gif_module d-none" load="gifs">
                <span class="data_attributes d-none"></span>

                <div class="search">
                    <div>
                        <input type="search" placeholder="<?php echo(Registry::load('strings')->search_here) ?>" />
                    </div>
                </div>

                <div class="subtabs">
                    <ul></ul>
                </div>


                <div class="results">
                    <div>
                        <ul id="grid_list"></ul>
                    </div>
                </div>
            </div>

            <div class="video_search_module d-none" load="video_search">
                <span class="data_attributes d-none"></span>

                <div class="search">
                    <div>
                        <input type="search" placeholder="<?php echo(Registry::load('strings')->search_here) ?>" />
                    </div>
                </div>

                <div class="subtabs">
                    <ul></ul>
                </div>


                <div class="results">
                    <div>
                        <ul id="grid_list"></ul>
                    </div>
                </div>
            </div>

            <div class="stickers_module d-none" load="stickers">
                <span class="data_attributes d-none"></span>
                <div class="subtabs">
                    <ul></ul>
                </div>

                <div class="results">
                    <div>
                        <ul id="grid_list"></ul>
                    </div>
                </div>
            </div>

            <div class="emojis_module d-none" load="emojis">
                <span class="data_attributes d-none"></span>

                <div class="search">
                    <div>
                        <input type="search" placeholder="<?php echo(Registry::load('strings')->search_here) ?>" />
                    </div>
                </div>

                <div class="subtabs">
                    <ul></ul>
                </div>


                <div class="results">
                    <div>
                        <ul id="grid_list"></ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="attached_message">
            <div class="attached_message_preview">
                <div class="content">
                    <div class="left">
                        <span class="send_by"></span>
                        <span class="text"></span>
                    </div>
                    <div class="right">
                        <span class="thumbnail"></span>
                    </div>
                </div>
                <div class="detach_message">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                    </svg>
                </div>
            </div>
            <span class="attached_message_id"><input type="hidden" name="attached_msg_id" value="0" /></span>
        </div>


        <div class="editor" min_message_length="<?php echo(Registry::load('settings')->minimum_message_length) ?>" max_message_length="<?php echo(Registry::load('settings')->maximum_message_length) ?>">
            <div>
                <div class="attached_media d-none">
                    <span class="media_image">
                        <img src='' />
                        <span class="deattach_media">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                            </svg>
                        </span>
                    </span>
                    <span class="media_url">
                        <input type="text" class="attached_media_url" name="media_url" value='' />
                        <input type="text" class="attached_media_type" name="media_type" value='' />
                    </span>
                </div>

                <div class="audio_recorder_box d-none">
                    <div>
                        <div class="timestamp">
                            00:00
                        </div>
                        <div class="icons">
                            <div class="send_audio_message">
                                <svg width="22px" height="22px" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <rect width="16" height="16" id="icon-bound" fill="none" />
                                    <path fill="white" d="M0,9.014L1.414,7.6L5.004,11.189L14.593,1.6L16.007,3.014L5.003,14.017L0,9.014Z" />
                                </svg>
                            </div>
                            <div class="cancel_recording">
                                <svg width="22px" height="22px" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <rect width="16" height="16" id="icon-bound" fill="none" />
                                    <polygon fill="white" points="14.707,2.707 13.293,1.293 8,6.586 2.707,1.293 1.293,2.707 6.586,8 1.293,13.293 2.707,14.707 8,9.414
                                        13.293,14.707 14.707,13.293 9.414,8 " />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="toggle_message_toolbar">
                    <?php if (Registry::load('settings')->message_text_formatting !== 'disable') {
                        ?>
                        <div class="msg_text_format_options format_text_message">
                            <div class="toggle_toolbar_button">
                                <span>
                                    <svg width="22" height="22" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="48" height="48" fill="white" fill-opacity="0.01" />
                                        <path d="M4 42L8.94118 30M32 42L27.0588 30M27.0588 30L25 25L18 8L11 25L8.94118 30M27.0588 30H8.94118" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M28 10L44 10" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M32 20L44 20" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M36 30L44 30" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M40 40H44" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                </span>
                            </div>
                        </div>
                        <?php
                    } ?>
                    <div class="msg_attach_options dropdown_button">
                        <div class="dropdown_list">
                            <ul>
                                <li class="load_grid_list toggle_grid_module messenger_features gifs_feature hide_onClick" load="gif_module">
                                    <span class="icon">
                                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3 7h6v2H3v6h4v-2H5v-2h4v6H1V7h2zm14 0h6v2h-6v2h4v2h-4v4h-2V7h2zm-4 0h-2v10h2V7z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <?php echo Registry::load('strings')->gifs ?>
                                </li>
                                <li class="load_grid_list toggle_grid_module messenger_features stickers_feature hide_onClick" load="stickers_module">
                                    <span class="icon">
                                        <svg fill="currentColor" width="21" height="21" viewBox="0 0 256 256" id="Flat" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M227.69653,138.65039c.0354-.15674.06714-.314.09644-.47266.04126-.22363.07422-.44775.10278-.67334.0166-.1333.03613-.26562.04859-.40039.03393-.3667.05566-.73486.05566-1.104V88a60.06812,60.06812,0,0,0-60-60H88A60.06812,60.06812,0,0,0,28,88v80a60.06812,60.06812,0,0,0,60,60h48c.36938,0,.7373-.022,1.10425-.05566.13452-.0127.2666-.03223.3999-.04883.22559-.02832.45-.06153.67358-.10254.1587-.0293.31592-.061.47217-.09619.19336-.04395.38452-.09473.57569-.14795.16308-.0459.32666-.08887.48681-.14112.02735-.00878.05542-.01464.08252-.02392,27.627-9.209,78.37988-59.96191,87.58887-87.58887.009-.02734.01489-.05517.02392-.08252.052-.16015.09546-.32373.14112-.4873C227.60205,139.03418,227.65283,138.84326,227.69653,138.65039ZM52,168V88A36.04061,36.04061,0,0,1,88,52h80a36.04061,36.04061,0,0,1,36,36v36H184a60.06812,60.06812,0,0,0-60,60v20H88A36.04061,36.04061,0,0,1,52,168Zm96,27.62549V184a36.04061,36.04061,0,0,1,36-36h11.62573C183.96875,164.10791,164.10791,183.96875,148,195.62549Z"
                                                />
                                        </svg>
                                    </span>
                                    <?php echo Registry::load('strings')->stickers ?>
                                </li>
                                <li class="load_grid_list toggle_grid_module messenger_features search_videos_feature hide_onClick" load="video_search_module">
                                    <span class="icon">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.5 9V15M6.5 12H12.5M16 10L18.5768 8.45392C19.3699 7.97803 19.7665 7.74009 20.0928 7.77051C20.3773 7.79703 20.6369 7.944 20.806 8.17433C21 8.43848 21 8.90095 21 9.8259V14.1741C21 15.099 21 15.5615 20.806 15.8257C20.6369 16.056 20.3773 16.203 20.0928 16.2295C19.7665 16.2599 19.3699 16.022 18.5768 15.5461L16 14M6.2 18H12.8C13.9201 18 14.4802 18 14.908 17.782C15.2843 17.5903 15.5903 17.2843 15.782 16.908C16 16.4802 16 15.9201 16 14.8V9.2C16 8.0799 16 7.51984 15.782 7.09202C15.5903 6.71569 15.2843 6.40973 14.908 6.21799C14.4802 6 13.9201 6 12.8 6H6.2C5.0799 6 4.51984 6 4.09202 6.21799C3.71569 6.40973 3.40973 6.71569 3.21799 7.09202C3 7.51984 3 8.07989 3 9.2V14.8C3 15.9201 3 16.4802 3.21799 16.908C3.40973 17.2843 3.71569 17.5903 4.09202 17.782C4.51984 18 5.07989 18 6.2 18Z"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    <?php echo Registry::load('strings')->videos ?>
                                </li>
                                <li class="share_user_location messenger_features share_location_feature hide_onClick">
                                    <span class="icon">
                                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_16_357)">
                                                <path d="M17.084 15.812C18.0244 14.8184 18.653 13.571 18.8922 12.224C19.1314 10.877 18.9706 9.4894 18.4297 8.23278C17.8888 6.97616 16.9916 5.90558 15.8489 5.15336C14.7062 4.40113 13.3681 4.00025 12 4.00025C10.6319 4.00025 9.29384 4.40113 8.15112 5.15336C7.00841 5.90558 6.11117 6.97616 5.5703 8.23278C5.02943 9.4894 4.86863 10.877 5.1078 12.224C5.34696 13.571 5.9756 14.8184 6.916 15.812C7.45567 14.9508 8.20536 14.241 9.09467 13.7491C9.98398 13.2572 10.9837 12.9994 12 13C13.0163 12.9994 14.016 13.2572 14.9053 13.7491C15.7946 14.241 16.5443 14.9508 17.084 15.812ZM8.385 17.285L12 20.899L15.615 17.285C15.2908 16.6006 14.779 16.0222 14.139 15.6172C13.4991 15.2122 12.7573 14.9972 12 14.9972C11.2427 14.9972 10.5009 15.2122 9.86096 15.6172C9.22101 16.0222 8.70919 16.6006 8.385 17.285ZM12 23.728L5.636 17.364C4.37734 16.1053 3.52019 14.5017 3.17293 12.7558C2.82567 11.01 3.00391 9.20043 3.6851 7.5559C4.36629 5.91137 5.51984 4.50577 6.99988 3.51684C8.47992 2.52791 10.22 2.00008 12 2.00008C13.78 2.00008 15.5201 2.52791 17.0001 3.51684C18.4802 4.50577 19.6337 5.91137 20.3149 7.5559C20.9961 9.20043 21.1743 11.01 20.8271 12.7558C20.4798 14.5017 19.6227 16.1053 18.364 17.364L12 23.728ZM12 10C12.2652 10 12.5196 9.89464 12.7071 9.7071C12.8946 9.51957 13 9.26521 13 9C13 8.73478 12.8946 8.48043 12.7071 8.29289C12.5196 8.10536 12.2652 8 12 8C11.7348 8 11.4804 8.10536 11.2929 8.29289C11.1054 8.48043 11 8.73478 11 9C11 9.26521 11.1054 9.51957 11.2929 9.7071C11.4804 9.89464 11.7348 10 12 10ZM12 12C11.2044 12 10.4413 11.6839 9.87868 11.1213C9.31607 10.5587 9 9.79565 9 9C9 8.20435 9.31607 7.44129 9.87868 6.87868C10.4413 6.31607 11.2044 6 12 6C12.7957 6 13.5587 6.31607 14.1213 6.87868C14.6839 7.44129 15 8.20435 15 9C15 9.79565 14.6839 10.5587 14.1213 11.1213C13.5587 11.6839 12.7957 12 12 12Z" fill="currentColor">
                                                </path>
                                            </g>
                                        </svg>
                                    </span>
                                    <?php echo Registry::load('strings')->location ?>
                                </li>
                                <li class="record_audio_message messenger_features voice_message_feature hide_onClick">
                                    <span class="icon">
                                        <svg width="21" height="21" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g id="Mic">
                                                    <rect id="Rectangle" fill-rule="nonzero" x="0" y="0" width="24" height="24">

                                                    </rect>
                                                    <rect id="Rectangle" stroke="currentColor" stroke-width="2" stroke-linecap="round" x="9" y="3" width="6" height="11" rx="3">

                                                    </rect>
                                                    <line x1="12" y1="18" x2="12" y2="21" id="Path" stroke="currentColor" stroke-width="2" stroke-linecap="round">

                                                    </line>
                                                    <line x1="8" y1="21" x2="16" y2="21" id="Path" stroke="currentColor" stroke-width="2" stroke-linecap="round">

                                                    </line>
                                                    <path d="M19,11 C19,14.866 15.866,18 12,18 C8.13401,18 5,14.866 5,11" id="Path" stroke="currentColor" stroke-width="2" stroke-linecap="round">

                                                    </path>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <?php echo Registry::load('strings')->audio_message ?>
                                </li>
                                <li class="trigger_attach_files messenger_features attach_files_feature hide_onClick">
                                    <span class="icon">
                                        <svg fill="currentColor" width="20" height="20" viewBox="0 0 35 35" data-name="Layer 2" id="Layer_2" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18,34.75A11.32,11.32,0,0,1,6.69,23.45V8A7.78,7.78,0,0,1,22.25,8V22.49a4.58,4.58,0,1,1-9.15,0V9.29a1.25,1.25,0,0,1,2.5,0v13.2a2.08,2.08,0,1,0,4.15,0V8A5.28,5.28,0,0,0,9.19,8V23.45A8.82,8.82,0,0,0,18,32.25c4.6,0,7.81-3.62,7.81-8.8V9.66a1.25,1.25,0,0,1,2.5,0V23.45C28.31,30,24,34.75,18,34.75Z"
                                                />
                                        </svg>
                                    </span>
                                    <?php echo Registry::load('strings')->attach_files ?>
                                </li>

                                <li class="attach_from_storage editor_icons load_aside messenger_features attach_from_storage_feature hide_onClick" data-share_files=true load="site_user_files">
                                    <span class="icon">
                                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 14V12C19 10.1144 19 9.17157 18.4142 8.58579C17.8284 8 16.8856 8 15 8H13.6569C12.8394 8 12.4306 8 12.0631 7.84776C11.6955 7.69552 11.4065 7.40649 10.8284 6.82843L10.1716 6.17157C9.59351 5.59351 9.30448 5.30448 8.93694 5.15224C8.5694 5 8.16065 5 7.34315 5H7C5.11438 5 4.17157 5 3.58579 5.58579C3 6.17157 3 7.11438 3 9V15C3 16.8856 3 17.8284 3.58579 18.4142C4.17157 19 5.11438 19 7 19H14"
                                                stroke="currentColor" stroke-width="2" />
                                            <path d="M16 19H19M19 19H22M19 19V22M19 19V16" stroke="currentColor" stroke-width="2" />
                                        </svg>
                                    </span>
                                    <?php echo Registry::load('strings')->storage ?>
                                </li>

                                <li class="load_grid_list toggle_grid_module messenger_features emojis_feature hide_onClick" load="emojis_module">
                                    <span class="icon">
                                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3 12C3 4.5885 4.5885 3 12 3C19.4115 3 21 4.5885 21 12C21 19.4115 19.4115 21 12 21C4.5885 21 3 19.4115 3 12Z" stroke="currentColor" stroke-width="2" />
                                            <path d="M8 14C8.91221 15.2144 10.3645 16 12.0004 16C13.6362 16 15.0885 15.2144 16.0007 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                            <path d="M9 10.0112V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                            <path d="M15 10.0112V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                    </span><?php echo Registry::load('strings')->emojis ?>
                                </li>
                            </ul>
                        </div>
                        <div class="toggle_toolbar_button">
                            <span>
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 1024 1024">
                                    <path fill="currentColor" d="M469.333 469.333v-256h85.333v256h256v85.333h-256v256h-85.333v-256h-256v-85.333z"></path>
                                </svg>

                            </span>
                        </div>
                    </div>
                </div>
                <div class="message_editor">
                    <div id="message_editor"></div>
                </div>
                <div class="send_message_button">
                    <div>
                        <span class="send_message">
                            <svg fill="currentColor" width="23px" height="23px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.975 17.504l14.287.001-6.367 6.366L16.021 26l10.004-10.003L16.029 6l-2.128 2.129 6.367 6.366H5.977z" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="attachments module hidden">
            <div>
                <div class="files">
                    <ul></ul>
                </div>
                <div class="attached_files">
                    <form class="attach_files_form" enctype="multipart/form-data">
                    </form>
                </div>
            </div>
        </div>

        <?php
    }
    ?>

    <audio id="audio_message_preview" class="d-none" controls preload="none">
        <source src="" type="" />
    </audio>
</div>

<span class="background_image"></span>