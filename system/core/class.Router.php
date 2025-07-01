<?php
/**
* System Router
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

namespace Core;

use Helpers\{Request,AuthHelper};
use Models\{AuthModel,AdminPanelModel,UsersModel};

class Router {

    private $routes;

    function __construct(){
        // Loads all routes
        $this->routes = Routes::all();
        // Check if route exists in routes
        $route = $this->findRoute();
        // Check if route is not empty
        if(!empty($route)){
            // Setup current page folder
            $pagefolder = VIEWSDIR.$route['pagefolder']."/";
            // Check if page file exists and include it
            if(is_dir($pagefolder)){
                $loadFile = $pagefolder.$route["pagefile"].".php";
                if(file_exists($loadFile)){
                    // Get page arguments
                    if(isset($route["arguments"])){
                        /** Split up the arguments from routes **/
                        $arguments = array();
                        $arg_paths = array();
                        $arg = rtrim($route["arguments"],'/');
                        $arguments = explode("/", $arg);
                        /** For each argument we get data from url **/
                        $params = array_slice(SELF::extendedRoutes(), 1);
                        foreach ($arguments as $key => $value) {
                            /** Check to see if argument is any **/
                            if($value == "(:any)"){
                                if(isset($params[$key])){
                                    if(preg_match('#^[^/]+(?:\?.*)?$#i', $params[$key])){
                                        $urlParams[] = $params[$key];
                                    }
                                }
                            }
                            /** Check to see if argument is a number **/
                            if($value == "(:num)"){
                                if(isset($params[$key])){
                                    if(preg_match('#^-?[0-9]+(?:\?.*)?$#i', $params[$key])){
                                        $urlParams[] = $params[$key];
                                    }
                                }
                            }
                            /** Check to see if argument is all **/
                            if($value == "(:all)"){
                                if(isset($params[$key])){
                                    if(preg_match('#^.*(?:\?.*)?$#i', $params[$key])){
                                        $urlParams[] = $params[$key];
                                    }
                                }
                            }
                        }
                    }
                    // Load the auth model
                    $authModel = new AuthModel();
                    $authHelper = new AuthHelper();
                    $AdminPanelModel = new AdminPanelModel();
                    // Load user data if exists
                    if($authHelper->isLogged()){
                        extract($authHelper->currentSessionInfo());
                        // Load the auth model
                        $usersModel = new UsersModel();
                        $userLocale = "";
                    }else{
                        $userLocale = "";
                    }
                    // Load the page files
                    require($loadFile);
                }else{
                    require(VIEWSDIR."system/error.php");
                }
            }else{
                require(VIEWSDIR."system/error.php");
            }
        }else{
            require(VIEWSDIR."system/error.php");
        }
    }

    private function routePart($route){
        if(is_array($route)){
            $route = strtolower($route['url']);
        }
        $parts = explode("/", $route);
        return $parts;
    }

    static function uri($part){
        $routes = Routes::all();
        if(Request::get("url") !== null){
            $url = Request::get('url');
		    $url = strtolower(rtrim($url,'/'));
            $parts = explode("/", $url);
            if($parts[0] == $routes){
                $part++;
            }
            return (isset($parts[$part])) ? $parts[$part] : "";
        }else{
            return "";
        }
    }

    private function findRoute(){
        $uri = Router::uri(0);
        if(empty($uri) || $uri == "home"){
            $route = array(
                "url" => "",
                "pagefolder" => "custom",
                "pagefile" => "home",
            );
            return $route;
        }
        foreach ($this->routes as $route) {
            $parts = $this->routePart($route);
            $match = false;
            foreach($parts as $value){
                if($value == $uri){
                    $match = true;
                }
                if($match){
                    return $route;
                }
            }
        }
    }

    public static function extendedRoutes(){
        if(!empty(Request::get('url'))){
            $url = Request::get('url');
            $url = rtrim($url,'/');
            $parts = explode("/", $url);
            return $parts;
        }
    }

}