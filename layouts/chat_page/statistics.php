<div class="header">
    <div class="go_back_icon">
        <span class="go_to_previous_page">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"></path>
            </svg>
        </span>
    </div>
    <div class="left">
        <span class="title">
            <span><?php echo Registry::load('strings')->statistics ?></span>
        </span>
    </div>
    <div class="icons">
        <span class="show_statistics">
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"></path>
                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"></path>
            </svg>
        </span>
    </div>
</div>

<?php if (isset(Registry::load('config')->pro_version) && !empty(Registry::load('config')->pro_version)) {
    if (role(['permissions' => ['memberships' => 'view_site_transactions']])) {
        ?>
        <div class="stats_tabs">
            <ul>
                <li class="stat_menu_item active show_statistics"><?php echo Registry::load('strings')->site_users ?></li>
                <li class="stat_menu_item show_statistics" statistics="memberships"><?php echo Registry::load('strings')->memberships ?></li>
            </ul>
        </div>
        <?php
    }
} ?>
<div class="contents"></div>
<div class="loader">
    <div>
        <div class="loading">
            <div class="grid">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
</div>