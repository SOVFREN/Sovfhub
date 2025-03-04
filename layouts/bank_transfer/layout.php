<?php
include 'layouts/bank_transfer/on_load.php';
include 'layouts/bank_transfer/upload_receipt.php';
?>
<!DOCTYPE html>
<html lang="<?php echo Registry::load('strings')->iso_code ?>" dir="<?php echo Registry::load('strings')->text_direction ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo Registry::load('settings')->site_name.' - '.Registry::load('settings')->site_slogan; ?></title>
    <meta name="description" content="<?php echo Registry::load('settings')->site_description; ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo Registry::load('config')->site_url.'assets/files/defaults/favicon.png'; ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no shrink-to-fit=no">
    <link rel="preload" href="<?php echo Registry::load('config')->site_url.'assets/fonts/'.Registry::load('settings')->default_font.'/font.css'; ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link href="<?php echo Registry::load('config')->site_url.'assets/css/common/bank_transfer.css' ?>" rel="stylesheet">

</head>
<body class='<?php echo(Registry::load('appearance')->body_class) ?>'>
    <div class="container">
        <div class="header">
            <img class="logo" src="<?php echo Registry::load('config')->site_url.'assets/files/defaults/bank_transfer.png' ?>" />
            <div class="order-info">
                <p class="order-id">
                    <?php echo Registry::load('strings')->order_id.': '.$order_id; ?>
                </p>
                <p>
                    <?php echo Registry::load('strings')->amount.': '.Registry::load('settings')->default_currency_symbol.$membership_order['pricing']; ?>
                </p>
            </div>
        </div>

        <?php if ($file_upload_failed) {
            ?>
            <div class="status">
                <?php echo Registry::load('strings')->file_upload_failed ?>
            </div>
            <?php
        } else {
            if (isset($bank_receipt[0])) {
                ?>
                <div class="status">
                    <?php echo Registry::load('strings')->bank_transfer_reciept_pending_approval ?>
                </div>
                <?php
            }
        }
        ?>



        <h2 class="heading"><?php echo Registry::load('strings')->bank_account_details ?></h2>
        <div class="subheading">
            <?php echo Registry::load('strings')->bank_transfer_details_subheading ?>
        </div>

        <div class="bank-details">
            <p>
                <?php if (isset($gateway['credentials'])) {
                    $gateway['credentials'] = json_decode($gateway['credentials']);
                    if (!empty($gateway['credentials'])) {
                        if (isset($gateway['credentials']->bank_account_details)) {
                            echo $gateway['credentials']->bank_account_details;
                        }
                    }
                }
                ?>
            </p>
        </div>

        <div class="upload-receipt">
            <form class="receipt_upload" action="" method="post" enctype="multipart/form-data">
                <label for="receipt"><?php echo Registry::load('strings')->bank_transfer_choose_file ?></label>
                <input type="file" id="receipt" name="bank_receipt" accept=".jpg, .jpeg, .png, .pdf">
                <button type="submit" class="submit_receipt"><?php echo Registry::load('strings')->upload_receipt ?></button>
                <a href="<?php echo Registry::load('config')->site_url; ?>" class="back_to_web"><span><?php echo Registry::load('strings')->go_homepage ?></span></a>
            </form>
        </div>
    </div>
</body>
</html>