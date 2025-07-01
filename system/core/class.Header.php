<?php
/**
* System Header Loader
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

namespace Core;

class Header {

    public static function load($metaData = null, $template = "default"){
        require(VIEWSDIR."templates/".$template."/header.php");
    }

}