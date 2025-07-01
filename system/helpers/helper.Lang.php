<?php
/**
* Language Helper
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

namespace Helpers;

class Lang {

    // Get lang data based on user's locale setting
    public static function get($locale="en-US", $name=null, $values=null) {

        // Folder where lang files are kept
        $folder = SYSTEMDIR."/lang/";
        $file = $folder.$locale.".json";
        $defaultFile = $folder."en-US.json";

        // Check to see if locale lang file exists, and if not then default to en-US
        if(is_readable($file)){
            $lang_array = file_get_contents($file);
        }else{
            $lang_array = file_get_contents($defaultFile);
        }

        // Decode the json data for output
        $lang_array = (!empty($lang_array)) ? json_decode($lang_array) : null;

        // Check to see if lang name exists
        if(!empty($lang_array->$name)){
            // Check to see if values are being added to the string
            if(!empty($values)){
                // Setup the string
                $output = $lang_array->$name;
                // Loop through each value to see if we need to replace placeholder with data
                if(is_array($values)){
                    $i = 1;
                    foreach($values AS $value){
                        $output = str_replace("{".$i."}", $value, $output);
                        $i++;
                    }
                }
                return $output;
            }else{
                return $lang_array->$name;
            }
        }else{
            /** Selected lang value not in lang file
            *   Log the value so that we know to add
            **/
            $file = SYSTEMDIR.'logs/missing-lang.log';
            $string = "\"$name\" : \"\",";
            $date = date('Y-m-d G:iA');
            $url = $_SERVER['REQUEST_URI'];
            $logMessage = "$date - $name - $url \n $string\n";
            $handle = fopen($file, 'r');
            $valid = false; // init as false
            while (($buffer = fgets($handle)) !== false) {
                if (strpos($buffer, $string) !== false) {
                    $valid = TRUE;
                    break; // Once you find the string, you should break out the loop.
                }
            }
            fclose($handle);
            if($valid == FALSE){
                file_put_contents($file, $logMessage, FILE_APPEND);
            }
            return $name;
        }

    }

}