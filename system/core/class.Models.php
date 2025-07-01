<?php
/**
* System Models Class
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

namespace Core;

use Helpers\Database;

class Models {

    protected $db;

    function __construct(){
        /** Connect to PDO for all models. */
        $this->db = Database::get();
    }
}
