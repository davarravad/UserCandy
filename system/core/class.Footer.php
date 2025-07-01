<?php
/**
* System Footer Loader
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

namespace Core;

class Footer {

    public static function load($template = "default"){
        require(VIEWSDIR."templates/".$template."/footer.php");
    }

}