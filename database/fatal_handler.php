<?php
if(defined('SENTRY_DSN')){
    $client = new Raven_Client(SENTRY_DSN);
    $error_handler = new Raven_ErrorHandler($client);
    $client->install();
}
