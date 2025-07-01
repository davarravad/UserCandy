<?php
/**
* System Routes
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

namespace Core;

class Routes {

    static function setRoutes(){
        // Create routes array
        $routes = array();

        // Default Routes
        $routes[] = self::add('home', 'custom', 'home');
        $routes[] = self::add('login', 'system', 'login', '(:any)');
        $routes[] = self::add('templates', 'system', 'templates');
        $routes[] = self::add('account', 'system', 'account', '(:any)');
        $routes[] = self::add('admin', 'system', 'admin', '(:any)/(:any)/(:any)');

        // Custom Routes

        // Api Stuffs

        // Auth Routing
        $routes[] = self::add('Register', 'system', 'Register');
        $routes[] = self::add('Activate', 'system', 'Activate', '(:any)/(:any)/(:any)/(:any)');
        $routes[] = self::add('Forgot-Password', 'system', 'Forgot-Password');
        $routes[] = self::add('Reset-Password', 'system', 'Reset-Password', '(:any)/(:any)/(:any)/(:any)');
        $routes[] = self::add('Resend-Activation-Email', 'system', 'Resend-Activation-Email');
        $routes[] = self::add('Login', 'system', 'Login');
        $routes[] = self::add('Logout', 'system', 'Logout');

        // Send routes out for router
        return $routes;
    }

    static function add($url, $pagefolder, $pagefile, $arguments = null){
        $routes = array(
            "url" => $url,
            "pagefolder" => $pagefolder,
            "pagefile" => $pagefile,
            "arguments" => $arguments
        );
        return $routes;
    }

    static function all(){
        $routes = self::setRoutes();
        return $routes;
    }

}