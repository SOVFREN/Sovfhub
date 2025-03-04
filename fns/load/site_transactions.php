<?php

if (role(['permissions' => ['memberships' => 'view_site_transactions']])) {
    $private_data["site_transactions"] = true;
    include('fns/load/transactions.php');
}
?>