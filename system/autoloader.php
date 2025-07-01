<?php
/**
* Auto Loader File - Loads all classes into the system
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

/**
* UC Autoloader loads all Classes within specified folders
*/

spl_autoload_register(function($class_name) {

    // Define an array of directories in the order of their priority to iterate through.
    $dirs = array(
        SYSTEMDIR.'helpers/', // Helpers Classes
        SYSTEMDIR.'models/', // Database Models Classes
        SYSTEMDIR.'core/', // Core Classes
    );

    // Looping through each directory to load all the class files. It will only require a file once.
    // If it finds the same class in a directory later on, IT WILL IGNORE IT! Because of that require once!
    foreach( $dirs as $dir ) {
        // Get the File Name based on the class by taking out the namespace
        $class_name = explode('\\', $class_name);
        $class_name = end($class_name);
        if (file_exists($dir.'class.'.$class_name.'.php')) {
            require_once($dir.'class.'.$class_name.'.php');
            return;
        }
        if (file_exists($dir.'model.'.$class_name.'.php')) {
            require_once($dir.'model.'.$class_name.'.php');
            return;
        }
        if (file_exists($dir.'helper.'.$class_name.'.php')) {
            require_once($dir.'helper.'.$class_name.'.php');
            return;
        }
    }
});