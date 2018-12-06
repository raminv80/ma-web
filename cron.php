<?php
require_once __DIR__.'/vendor/autoload.php';
include "includes/functions/functions.php";

use GO\Scheduler;

if(getenv('APP_ENV')==='production') {
    // Create a new scheduler
    $scheduler = new Scheduler();

    // ... configure the scheduled jobs
    $scheduler->raw('/usr/bin/curl https://www.medicalert.org.au/admin/includes/cron/send-survey.php > /dev/null 2>&1')->at('*/10 * * * *');
    $scheduler->raw('/usr/bin/curl https://www.medicalert.org.au/admin/includes/cron/send-vouchers.php > /dev/null 2>&1')->at('30 8 * * *');
    $scheduler->raw('/usr/bin/curl https://www.medicalert.org.au/admin/includes/cron/email-queue.php > /dev/null 2>&1')->at('*/10 * * * *');
    $scheduler->raw('/usr/bin/curl https://www.medicalert.org.au/admin/includes/cron/update-product-price.php > /dev/null 2>&1')->at('2 0 1 1 *');
    $scheduler->raw('/usr/bin/curl https://www.medicalert.org.au/admin/includes/cron/notify-abandoned-cart-members.php > /dev/null 2>&1')->at('0 11 * * *');
    $scheduler->raw('/usr/bin/curl https://www.medicalert.org.au/admin/includes/cron/send-birthday-vouchers.php > /dev/null 2>&1')->at('30 11 * * *');
    $scheduler->raw('/usr/bin/curl https://www.medicalert.org.au/admin/includes/cron/web-monitor.php > /dev/null 2>&1')->at('* * * * *');
    $scheduler->raw('/usr/bin/curl https://www.medicalert.org.au/admin/includes/cron/push_renewal_payments.php > /dev/null 2>&1')->at('0 6 * * *');

    // Let the scheduler execute jobs which are due.
    $scheduler->run();

}
