<?php
$current_page_id = 0;
if (isset(Registry::load('config')->load_page)) {
    $current_page_id = Registry::load('config')->load_page;
}
?>

<header class="navigation">
    <nav class="navbar navbar-expand-md navbar-dark">
        <div class="container">
            <div class="navbar-brand">
                <a href=".">
                    <?php if (Registry::load('current_user')->color_scheme === 'dark_mode') {
                        ?>
                        <img class="logo" src="<?php echo Registry::load('config')->site_url.'assets/files/logos/landing_page_logo_dark_mode.png'.$cache_timestamp; ?>" />
                        <?php
                    } else {
                        ?>
                        <img class="logo" src="<?php echo Registry::load('config')->site_url.'assets/files/logos/landing_page_logo.png'.$cache_timestamp; ?>" />
                        <?php
                    } ?>
                </a>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link<?php if (empty($current_page_id)) { echo ' active'; } ?>" aria-current="page" href="<?php echo Registry::load('config')->site_url; ?>#home"><?php echo Registry::load('strings')->home; ?></a>
                    </li>
                    <?php
                    if (isset(Registry::load('settings')->groups_section_status) && Registry::load('settings')->groups_section_status === 'enable') {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo Registry::load('config')->site_url; ?>#groups"><?php echo Registry::load('strings')->groups; ?></a>
                        </li>
                        <?php
                    }
                    if (isset(Registry::load('config')->pro_version) && !empty(Registry::load('config')->pro_version)) {
                        if (Registry::load('settings')->memberships === 'enable') {
                            if (isset(Registry::load('settings')->packages_section_status) && Registry::load('settings')->packages_section_status === 'enable') {

                                ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo Registry::load('config')->site_url; ?>#membership_packages"><?php echo Registry::load('strings')->pricing_menu_item; ?></a>
                                </li>

                                <?php
                            }
                        }
                    }
                    if (isset(Registry::load('settings')->faq_section_status) && Registry::load('settings')->faq_section_status === 'enable') {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo Registry::load('config')->site_url; ?>#faq"><?php echo Registry::load('strings')->faq; ?></a>
                        </li>
                        <?php
                    }

                    $columns = $where = $join = null;
                    $columns = [
                        'custom_menu_items.string_constant', 'custom_menu_items.page_id', 'custom_menu_items.web_address',
                        'custom_menu_items.menu_icon_class', 'custom_menu_items.link_target', 'custom_pages.slug',
                        'custom_menu_items.menu_item_visibility', 'custom_menu_items.menu_item_id',
                    ];

                    $join["[>]custom_pages"] = ["custom_menu_items.page_id" => "page_id"];

                    $where["custom_menu_items.disabled"] = 0;
                    $where["custom_menu_items.show_on_landing_page_header"] = 1;
                    $where["ORDER"] = ["custom_menu_items.menu_item_order" => "ASC"];

                    $menu_items = DB::connect()->select('custom_menu_items', $join, $columns, $where);

                    foreach ($menu_items as $menu_item) {

                        $menu_item_title = $menu_item['string_constant'];
                        $menu_item_attributes = '';
                        $hyperlink = '';

                        if (!empty($menu_item['page_id'])) {
                            $hyperlink = Registry::load('config')->site_url;
                            $hyperlink .= $menu_item['slug'].'/';
                        } else {
                            if (substr($menu_item['web_address'], 0, 7) !== 'http://' && substr($menu_item['web_address'], 0, 8) !== 'https://') {
                                $hyperlink = Registry::load('config')->site_url;
                            }

                            $hyperlink .= $menu_item['web_address'];
                        }
                        if (!empty($menu_item['link_target'])) {
                            $menu_item_attributes .= 'target="_blank" ';
                        }
                        $menu_page_id = $menu_item['page_id'];

                        ?>
                        <li class="nav-item">
                            <a class="nav-link<?php if (!empty($menu_page_id) && (int)$current_page_id === (int)$menu_page_id) { echo ' active'; } ?>" <?php echo $menu_item_attributes ?> href="<?php echo $hyperlink ?>">
                                <?php echo Registry::load('strings')->$menu_item_title; ?>
                            </a>
                        </li>

                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>