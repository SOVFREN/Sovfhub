<?php

$columns = $where = null;
$columns = [
    'languages.language_id', 'languages.name'
];

$where["languages.disabled"] = 0;
$where["ORDER"] = ["languages.language_id" => "ASC"];

$languages = DB::connect()->select('languages', $columns, $where);
$language_icon = get_image(['from' => 'languages', 'search' => Registry::load('settings')->default_language]);
?>

<li class="has_child">
    <div class="menu_item">
        <span class="icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                <path fill="currentColor" d="M907.090 115.2h-790.185c-64.466 0-116.905 52.444-116.905 116.905v559.785c0 64.461 52.444 116.905 116.905 116.905h790.185c64.461 0 116.91-52.444 116.91-116.905v-559.785c0-64.461-52.449-116.905-116.91-116.905zM512 847.36h-395.095c-30.582 0-55.465-24.878-55.465-55.465v-559.79c0-30.587 24.883-55.465 55.465-55.465h395.095v670.72zM934.81 389.78c-2.801 46.822-29.020 139.576-106.849 204.006 23.629 5.233 49.3 8.054 76.39 8.054v61.44c-54.021 0-103.619-9.825-145.761-27.264-39.368 16.901-86.881 27.264-144 27.264v-61.44c26.276 0 50.811-2.565 73.554-7.67-43.069-35.922-68.516-83.512-68.516-137.114h61.435c0 45.348 28.979 84.567 76.206 110.71 23.762-13.619 44.554-31.293 62.28-52.997 28.815-35.256 43.53-74.348 49.94-102.615h-254.899v-61.44h114.161v-61.368h61.44v61.373h107.802c10.168 0 19.978 4.255 26.921 11.674 6.856 7.265 10.496 17.28 9.897 27.387z"></path>
                <path fill="currentColor" d="M220.339 605.501l-22.784 57.641h-81.772l134.938-312.781h81.772l131.82 312.781h-84.905l-22.339-57.641h-136.73zM289.152 429.445l-45.578 115.732h90.26l-44.682-115.732z"></path>
            </svg>

        </span>
        <span class="title">
            <?php echo(Registry::load('strings')->languages) ?>
        </span>
    </div>
    <div class="child_menu">
        <ul>
            <li class='api_request' data-update="site_users_settings" data-language_id='0'>
                <span class="image"><img src="<?php echo $language_icon; ?>" /></span>
                <span class="text"><?php echo Registry::load('strings')->default ?></span>
                </li>
                <?php
                foreach ($languages as $language) {
                    $language_icon = get_image(['from' => 'languages', 'search' => $language['language_id']]);
                    ?>
                    <li class='api_request' data-update="site_users_settings" data-language_id='<?php echo $language['language_id'] ?>'>
                        <span class="image"><img src="<?php echo $language_icon; ?>" /></span>
                        <span class="text"><?php echo $language['name'] ?></span>
                    </li>
                    <?php
                } ?>
            </ul>
        </div>
    </li>