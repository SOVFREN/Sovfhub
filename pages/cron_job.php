<?php
include 'fns/firewall/load.php';
include 'fns/sql/load.php';
include_once 'fns/SleekDB/Store.php';
include 'fns/variables/load.php';
include 'fns/cron_jobs/load.php';

$cron_job_parameters = explode('/', get_url(['path' => true]));

if (isset($cron_job_parameters[1]) && $cron_job_parameters[1] === 'scheduled_messages') {
    $cron_job_parameters[2] = 'scheduled_messages';
}

if (isset($cron_job_parameters[2]) && !empty($cron_job_parameters[2])) {

    set_time_limit(0);

    $cron_job = array();
    $cron_job['cron_job_id'] = $cron_job_parameters[1];
    $cron_job['access_code'] = $cron_job_parameters[2];
    $cron_job['return'] = true;

    $result = cron_job($cron_job);

    if (isset($result['success']) && $result['success']) {
        echo "[Cronjob Executed Sucessfully]";
    } else {
        echo "[Something Went Wrong]";
    }

} else {
    echo "[Invalid CronJob]";
}
?>