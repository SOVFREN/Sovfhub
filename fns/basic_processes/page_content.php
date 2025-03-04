<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_content['title'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="<?php echo Registry::load('config')->site_url ?>assets/css/basic_processes.css" rel="stylesheet">

    <?php if (isset($page_content['redirect'])) {
        if (!isset($page_content['wait_seconds'])) {
            $page_content['wait_seconds'] = 5;
        }
        ?>
        <meta http-equiv="refresh" content="<?php echo $page_content['wait_seconds'] ?>;url=<?php echo $page_content['redirect'] ?>" />
        <?php
    }
    ?>
</head>
<body class="<?php echo $body_class; ?>">

    <?php if (isset($page_content['loading_text'])) {
        ?>
        <div class="preloader">
            <div class="ripple-background">
                <div class="circle xxlarge shade1"></div>
                <div class="circle xlarge shade2"></div>
                <div class="circle large shade3"></div>
                <div class="circle medium shade4"></div>
                <div class="circle small shade5"></div>
            </div>
            <div class="subtitle">
                <?php echo $page_content['subtitle'] ?>
            </div>
            <div class="loading-text">
                <?php echo $page_content['loading_text'] ?>
            </div>
        </div>

        <?php
    } else if (isset($page_content['heading'])) {
        ?>

        <div class="body_content container">
            <h1 class="animated"><?php echo $page_content['heading'] ?></h1>
            <p>
                <?php echo $page_content['page_content'] ?>
            </p>
            <form action="/">
                <a class="button" href="<?php echo $page_content['button_link'] ?>"><?php echo $page_content['button_text'] ?></a>
            </form>
        </div>
        <?php
    } ?>

</body>
</html>