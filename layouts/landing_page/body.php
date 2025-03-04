<body class="landing_page<?php echo ' '.Registry::load('appearance')->body_class ?>">

    <?php include 'assets/headers_footers/landing_page/body.php'; ?>
    <main class="main_window">

        <?php
        include('layouts/landing_page/navigation.php');
        ?>

        <?php
        if (isset(Registry::load('config')->load_page) && !empty(Registry::load('config')->load_page)) {
            include('layouts/landing_page/custom_page.php');
        } else {
            include('layouts/landing_page/hero.php');

            if (isset(Registry::load('settings')->groups_section_status) && Registry::load('settings')->groups_section_status === 'enable') {
                include('layouts/landing_page/groups_section.php');
            }

            if (isset(Registry::load('config')->pro_version) && !empty(Registry::load('config')->pro_version)) {
                if (Registry::load('settings')->memberships === 'enable') {
                    if (isset(Registry::load('settings')->packages_section_status) && Registry::load('settings')->packages_section_status === 'enable') {
                        include('layouts/landing_page/packages.php');
                    }
                }
            }

            if (isset(Registry::load('settings')->faq_section_status) && Registry::load('settings')->faq_section_status === 'enable') {
                include('layouts/landing_page/faq.php');
            }
        }
        ?>

        <?php
        if (isset(Registry::load('settings')->footer_section_status) && Registry::load('settings')->footer_section_status === 'enable') {
            include('layouts/landing_page/bottom.php');
        }
        ?>

    </main>
    <?php
    if (Registry::load('settings')->adblock_detector === 'enable') {
        include 'layouts/chat_page/ad_block_detector.php';
    }
    ?>
</body>