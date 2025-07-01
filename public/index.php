<?php
/**
* Site Index File
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

date_default_timezone_set('America/Chicago');

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

/* Start the Session */
session_start();

/* Error Settings */
error_reporting(E_ALL);

/* Load the Site Config */
require(SYSTEMDIR.'Config.php');
new Config();

/* Load the Page Router */
new Core\Router();

