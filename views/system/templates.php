<?php
/**
* Templates Page
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

use Helpers\{Request};

// Get file to load
$url = strtolower(Request::get("url"));
$file = VIEWSDIR.$url;
$filename = basename($file);
$ext = strtolower(@end((explode('.', $filename))));

$mimes = array
(
    'jpg' => 'image/jpg',
    'jpeg' => 'image/jpg',
    'gif' => 'image/gif',
    'png' => 'image/png',
    'css' => 'text/css',
    'js' => 'application/javascript',
    'ico' => 'image/x-icon'
);

// Check if the file exists, then load it.
if(file_exists($file)){
    header('Content-Type: '. $mimes[$ext]);
    header('Content-Disposition: inline; filename="'.$filename.'";');
    readfile($file);
}