<div class="col-md-7 col-lg-9 middle page_column" column="second">

    <div class="video_preview d-none">
        <span class="icons">
            <span class="close_player">
                <svg class="close_window_icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z" />
                </svg>
            </span>
        </span>
        <div>
        </div>
    </div>

    <div class="iframe_window d-none">
        <span class="icons">
            <span class="close_iframe_window">
                <svg class="close_window_icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z" />
                </svg>
            </span>
        </span>
        <div>
        </div>
    </div>

    <div class="group_headers d-none">
        <span class="icons">
            <span class="close_group_header">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z" />
                </svg>
            </span>
        </span>
        <div class="header_content"></div>
    </div>

    <div class="video_chat_interface d-none">
        <div class="video_chat_container">
            <div class="video_chat_grid" id="video-chat-grid"></div>
            <div class="audio_chat_grid d-none" id="audio-chat-grid"></div>
            <div class="icons">
                <span class="leave_video_call">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 1024 1024">
                        <path fill="currentColor" d="M732.387 994.624h1.349c25.482 0 49.573-10.037 67.815-28.286l130.902-130.902c8.744-8.731 14.155-20.797 14.155-34.127s-5.411-25.396-14.154-34.127l-193.068-193.068c-8.731-8.744-20.797-14.155-34.127-14.155s-25.396 5.411-34.127 14.154l-76.937 76.937c-35.668-10.619-102.226-34.752-144.417-76.936s-66.317-108.742-76.936-144.417l76.936-76.936c8.744-8.731 14.155-20.797 14.155-34.127s-5.411-25.396-14.154-34.127l-193.068-193.068c-8.881-8.397-20.898-13.563-34.127-13.563s-25.239 5.166-34.148 13.582l0.021-0.019-130.852 130.902c-18.341 18.341-28.669 43.539-28.286 69.263 1.107 68.73 19.307 307.459 207.454 495.606s426.876 206.29 495.606 207.405zM222.641 193.823l124.819 124.819-62.406 62.406c-8.73 8.702-14.126 20.743-14.126 34.039 0 3.537 0.379 6.982 1.103 10.299l-0.058-0.316c1.157 5.551 29.492 137.177 109.615 217.299s211.749 108.458 217.299 109.615c3.010 0.67 6.464 1.052 10.011 1.052 13.288 0 25.321-5.407 34.009-14.132l62.408-62.408 124.819 124.819-96.824 96.775c-60.234-1.015-266.34-17.184-428.274-179.169-162.467-162.467-178.203-369.29-179.169-428.324l96.775-96.775zM898.091 463.684h96.533c0-247.608-186.941-434.357-434.883-434.357v96.533c196.062 0 338.349 142.053 338.349 337.817z"></path>
                        <path fill="currentColor" d="M560.217 318.933c101.502 0 144.8 43.298 144.8 144.8h96.533c0-155.66-85.673-241.333-241.333-241.333v96.533z"></path>
                    </svg>
                </span>

                <?php

                if (Registry::load('settings')->screen_sharing !== 'disable') {
                    ?>
                    <span class="toggle_screen_share d-sm-none d-md-inline-flex">
                        <svg class="stop_screen_share d-none" xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 3H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2v-3" />
                            <path d="M8 21h8" />
                            <path d="M12 17v4" />
                            <path d="M22 3l-5 5" />
                            <path d="M17 3l5 5" />
                        </svg>
                        <svg class="share_user_screen" xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 3H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2v-3" />
                            <path d="M8 21h8" />
                            <path d="M12 17v4" />
                            <path d="M17 8l5-5" />
                            <path d="M17 3h5v5" />
                        </svg>

                    </span>
                    <?php
                }
                ?>
                <span class="toggle_video_call_mic">
                    <svg class="mic_muted d-none" version="1.1" xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 1024 1024">
                        <path fill="currentColor" d="M640 920h-88v-92.352c33.728-3.616 64.608-11.68 93.472-23.648l-2.24 0.832c14.4-6.24 24.288-20.352 24.288-36.768 0-22.080-17.888-39.968-39.968-39.968-5.568 0-10.88 1.152-15.712 3.2l0.256-0.096c-29.824 12.992-64.576 20.544-101.088 20.544-53.44 0-103.072-16.16-144.32-43.872l0.928 0.576c-83.904-63.36-137.6-162.944-137.6-275.072 0-9.056 0.352-18.016 1.024-26.912l-0.064 1.184c0-22.080-17.92-40-40-40s-40 17.92-40 40v0c-0.576 8.16-0.896 17.664-0.896 27.232 0 139.36 67.776 262.88 172.128 339.392l1.184 0.832c42.144 28.16 92.608 47.328 146.976 53.632l1.568 0.16v91.072h-88c-22.080 0-40 17.92-40 40s17.92 40 40 40v0h256c22.080 0 40-17.92 40-40s-17.92-40-40-40v0zM512 712c22.080 0 40-17.92 40-40s-17.92-40-40-40v0c-83.904-0.096-151.904-68.096-152-152v0c0-22.080-17.92-40-40-40s-40 17.92-40 40v0c0.128 128.064 103.936 231.872 232 232v0zM988.32 931.68l-219.136-219.136c63.648-76.672 102.304-176.096 102.304-284.544 0-7.168-0.16-14.272-0.512-21.344l0.032 0.992c0-22.080-17.92-40-40-40s-40 17.92-40 40v0c0.288 5.568 0.48 12.064 0.48 18.624 0 86.944-29.76 166.912-79.648 230.304l0.608-0.8-25.024-25.024c35.104-40.096 56.544-92.928 56.608-150.752v-224.032c0-0.16 0-0.384 0-0.576 0-127.808-103.616-231.424-231.424-231.424-117.6 0-214.688 87.712-229.472 201.248l-0.128 1.152-190.72-190.688c-7.232-7.2-17.184-11.616-28.192-11.616-22.080 0-40 17.92-40 40 0 11.008 4.448 20.96 11.616 28.192l895.968 896.032c7.232 7.232 17.248 11.712 28.288 11.712 22.112 0 40.032-17.92 40.032-40.032 0-11.040-4.48-21.056-11.712-28.288v0zM360 256c0-83.936 68.064-152 152-152s152 68.064 152 152v0 224c0 0.096 0 0.192 0 0.288 0 35.744-12.416 68.608-33.184 94.464l0.224-0.288-271.040-271.040z"></path>
                    </svg>

                    <svg class="mic_not_muted" version="1.1" xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 1024 1024">
                        <path fill="currentColor" d="M876.894 405.937c0-22.442-18.214-40.656-40.656-40.656s-40.656 18.214-40.656 40.656v0c0 240.227-147.499 349.672-284.59 349.672s-284.59-109.445-284.59-349.672c0-22.442-18.214-40.656-40.656-40.656s-40.656 18.214-40.656 40.656v0c-0.26 5.789-0.423 12.554-0.423 19.385 0 115.56 42.835 221.102 113.478 301.633l-0.455-0.52c54.544 58.089 128.83 97.086 212.093 106.29l1.529 0.13v93.801h-89.443c-22.442 0-40.656 18.214-40.656 40.656s18.214 40.656 40.656 40.656v0h260.197c22.442 0 40.656-18.214 40.656-40.656s-18.214-40.656-40.656-40.656v0h-89.443v-92.663c167.209-20.848 324.238-166.851 324.238-428.089zM512 715.279c130.163-0.13 235.673-105.64 235.803-235.803v-227.672c0-130.229-105.575-235.803-235.803-235.803s-235.803 105.575-235.803 235.803v0 227.672c0.13 130.163 105.64 235.673 235.803 235.803v0zM357.508 251.803c0-85.312 69.18-154.492 154.492-154.492s154.492 69.18 154.492 154.492v0 227.672c0 85.312-69.18 154.492-154.492 154.492s-154.492-69.18-154.492-154.492v0z"></path>
                    </svg>

                </span>
                <span class="toggle_video_call_camera">
                    <svg class="cam_not_disabled" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1024 1024">
                        <path fill="none" stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="64" stroke="currentColor" d="M877.538 445.538c0 201.882-163.658 365.538-365.538 365.538s-365.538-163.658-365.538-365.538c0-201.882 163.658-365.538 365.538-365.538s365.538 163.658 365.538 365.538z"></path>
                        <path fill="none" stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="64" stroke="currentColor" d="M678.154 445.538c0 91.764-74.39 166.154-166.154 166.154s-166.154-74.39-166.154-166.154c0-91.764 74.39-166.154 166.154-166.154s166.154 74.39 166.154 166.154z"></path>
                        <path fill="none" stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="64" stroke="currentColor" d="M545.23 445.538c0 18.353-14.879 33.23-33.23 33.23s-33.23-14.879-33.23-33.23c0-18.353 14.879-33.23 33.23-33.23s33.23 14.879 33.23 33.23z"></path>
                        <path fill="none" stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="64" stroke="currentColor" d="M704.738 754.584l79.754 73.108c46.524 39.876 16.616 116.308-43.2 116.308h-455.262c-59.816 0-89.724-73.108-43.2-116.308l79.754-73.108"></path>
                        <path fill="none" stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="64" stroke="currentColor" d="M711.384 279.384v0"></path>
                    </svg>

                    <svg class="cam_disabled d-none" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1024 1024">
                        <path fill="none" stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="64" stroke="currentColor" d="M877.538 445.538c0 201.882-163.658 365.538-365.538 365.538s-365.538-163.658-365.538-365.538c0-201.882 163.658-365.538 365.538-365.538s365.538 163.658 365.538 365.538z"></path>
                        <path fill="none" stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="64" stroke="currentColor" d="M678.154 445.538c0 91.764-74.39 166.154-166.154 166.154s-166.154-74.39-166.154-166.154c0-91.764 74.39-166.154 166.154-166.154s166.154 74.39 166.154 166.154z"></path>
                        <path fill="none" stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="64" stroke="currentColor" d="M545.23 445.538c0 18.353-14.879 33.23-33.23 33.23s-33.23-14.879-33.23-33.23c0-18.353 14.879-33.23 33.23-33.23s33.23 14.879 33.23 33.23z"></path>
                        <path fill="none" stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="64" stroke="currentColor" d="M704.738 754.584l79.754 73.108c46.524 39.876 16.616 116.308-43.2 116.308h-455.262c-59.816 0-89.724-73.108-43.2-116.308l79.754-73.108"></path>
                        <path fill="none" stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="64" stroke="currentColor" d="M80 80l864 864"></path>
                        <path fill="none" stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="64" stroke="currentColor" d="M711.384 279.384v0"></path>
                    </svg>

                </span>
                <span class="toggle_chat_window">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1024 1024">
                        <path fill="currentColor" d="M145.874 839.516l118.092-94.528h547.592c36.764 0 66.568-29.804 66.568-66.568v0-432.694c0-36.764-29.804-66.568-66.568-66.568v0h-599.116c-36.764 0-66.568 29.804-66.568 66.568v0 593.79zM287.332 811.558l-153.972 123.152c-5.639 4.532-12.884 7.274-20.77 7.274-18.365 0-33.258-14.875-33.284-33.234v-663.025c0-73.528 59.608-133.136 133.136-133.136v0h599.116c73.528 0 133.136 59.608 133.136 133.136v0 432.694c0 73.528-59.608 133.136-133.136 133.136v0h-524.226z"></path>
                        <path fill="currentColor" d="M345.58 512h332.842q33.284 0 33.284 33.284t-33.284 33.284h-332.842q-33.284 0-33.284-33.284t33.284-33.284zM345.58 312.294h332.842q33.284 0 33.284 33.284t-33.284 33.284h-332.842q-33.284 0-33.284-33.284t33.284-33.284z"></path>
                    </svg>

                </span>
            </div>
        </div>
    </div>

    <div class="confirm_box d-none animate__animated animate__flipInX">
        <div class="error">
            <span class="message"><?php echo(Registry::load('strings')->error) ?> : <span></span></span>
        </div>
        <div class="content">
            <span class="text"></span>
            <span class="btn cancel" column="second"><span></span></span>
            <span class="btn submit"><span></span></span>
        </div>
    </div>

    <div class="content">

        <div class="welcome_screen">
            <?php include 'layouts/chat_page/welcome_screen.php'; ?>
        </div>

        <div class="membership_info d-none">
            <?php include 'layouts/chat_page/membership_info.php'; ?>
        </div>

        <div class="statistics d-none">
            <?php include 'layouts/chat_page/statistics.php'; ?>
        </div>

        <div class="custom_page d-none">
            <?php include 'layouts/chat_page/custom_page.php'; ?>
        </div>

        <div class="chatbox d-none boundary">
            <?php include 'layouts/chat_page/chatbox.php'; ?>
        </div>
    </div>


</div>