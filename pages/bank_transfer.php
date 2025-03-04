<?php
include 'fns/firewall/load.php';
include_once 'fns/sql/load.php';
include_once 'fns/SleekDB/Store.php';
include 'fns/variables/load.php';

if (Registry::load('current_user')->logged_in) {
    include 'layouts/bank_transfer/layout.php';
} else {
    redirect('404');
}

?>