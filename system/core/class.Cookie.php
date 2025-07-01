<?php
/**
 * Auth Cookie Class
 *
 * UserCandy
 * @author David (DaVaR) Sargent <davar@usercandy.com>
 * @version uc 1.0.4
 */

namespace Core;

class Cookie {

    public static function exists($key) {
        return isset($_COOKIE[$key]);
    }

    public static function set($key, $value, $expiry = "", $path = "/", $domain = false, $secure = false, $httponly = true, $samesite = 'Lax') {
        $retval = false;
        if (!headers_sent()) {
            if ($domain === false) {
                $domain = $_SERVER['HTTP_HOST'];
            }
            $options = [
                'expires' => $expiry,
                'path' => $path,
                'domain' => $domain,
                'secure' => $secure,
                'httponly' => $httponly,
                'samesite' => $samesite,
            ];
            $retval = setcookie($key, $value, $options);
            if ($retval) {
                $_COOKIE[$key] = $value;
            }
        }
        return $retval;
    }

    public static function get($key, $default = '') {
        return $_COOKIE[$key] ?? $default;
    }

    public static function display() {
        return $_COOKIE;
    }

    public static function destroy($key, $value = '', $path = "/", $domain = '', $secure = false, $httponly = true, $samesite = 'Lax') {
        if (isset($_COOKIE[$key])) {
            if ($domain === '') {
                $domain = $_SERVER['HTTP_HOST'];
            }
            unset($_COOKIE[$key]);
            setcookie($key, $value, [
                'expires' => time() - 3600,
                'path' => $path,
                'domain' => $domain,
                'secure' => $secure,
                'httponly' => $httponly,
                'samesite' => $samesite,
            ]);
        }
    }

}
