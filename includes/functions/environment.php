<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'];

/**
* Use Dotenv to set required environment variables and load .env file in root
*/
$dotenv = new Dotenv\Dotenv($root_dir);
if (file_exists($root_dir . '/.env')) {
    $dotenv->load();
    $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST']);
}

/**
 * Set up our global environment constant and load its config first
 */
define('APP_ENV', getenv('APP_ENV') ?: 'production');
define('APP_DEBUG', (getenv('APP_DEBUG') ?: '')===true);
define('APP_DOMAIN', getenv('APP_DOMAIN') ?: '');

define('DB_HOST', getenv('DB_HOST') ?: 'undefined');
define('DB_NAME', getenv('DB_NAME') ?: 'undefined');
define('DB_USER', getenv('DB_USER') ?: 'undefined');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'undefined');

define('MEMBERSHIP_API_ENDPINT', getenv('MEMBERSHIP_API_ENDPINT') ?: false);
define('MEMBERSHIP_API_USER', getenv('MEMBERSHIP_API_USER') ?: false);
define('MEMBERSHIP_API_PASSWORD', getenv('MEMBERSHIP_API_PASSWORD') ?: false);

define('SENTRY_DSN', getenv('SENTRY_DSN') ?: false);
