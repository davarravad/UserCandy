<?php
/**
* Site Index File
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

define('ROOTDIR', realpath(__DIR__.'/../').'/');
define('SYSTEMDIR', realpath(__DIR__.'/../system/').'/');
define('VIEWSDIR', realpath(__DIR__.'/../views/').'/');

/* load UC Autoloader */
if (file_exists(SYSTEMDIR.'autoloader.php')) {
    require SYSTEMDIR.'autoloader.php';
} else {
    echo "<h1>Error With UserCandy Auto Loader</h1>";
    echo "<p>Contact Administrator for Support</p>";
    exit;
}

/* Load the Site Config */
if (file_exists(SYSTEMDIR.'Config.php')) {
    require SYSTEMDIR.'Config.php';
    new Config();
} else {
    echo "<h1>Configuration Required</h1>";
    echo "<p>Please rename or copy " . SYSTEMDIR . "Config-Example.php to Config.php and update your settings.</p>";
    exit;
}

date_default_timezone_set(TIMEZONE);

// Enforce strict session id mode and custom name
ini_set('session.use_strict_mode', '1');
session_name(SESSION_PREFIX . 'sid');

// Determine if cookies should be marked as secure.
// If COOKIE_SECURE is enabled but HTTPS is not active, fall back to false so
// the session cookie is still sent over HTTP. This prevents silent login
// failures when the site has not yet been configured for HTTPS.
$cookieSecure = COOKIE_SECURE;
if ($cookieSecure && !Helpers\Request::isSecure()) {
    $cookieSecure = false;
}

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    // Strip any port from host to ensure a valid cookie domain
    'domain' => explode(':', $_SERVER['HTTP_HOST'])[0],
    'secure' => $cookieSecure,
    'httponly' => COOKIE_HTTPONLY,
    'samesite' => COOKIE_SAMESITE
]);

/* Start the Session */
session_start();

/* Error Settings */
error_reporting(E_ALL);

/* Load the Page Router */
new Core\Router();

