<?php

/**
 * Cross Site Request Forgery Helper.
 *
 * UserCandy PHP Framework
 * @author David (DaVaR) Sargent <davar@usercandy.com>
 * @version UC 2.0.0.0
 */

/**
 * Instructions:
 * At the top of the controller where the other "use" statements are, place:
 * use Helpers\Csrf;
 *
 * Just prior to creating a form, create the CSRF token:
 * $data['csrfToken'] = Csrf::makeToken();
 *
 * At the bottom of your form, before the submit button put:
 * <input type="hidden" name="token_csrfToken" value="<?= $data['csrfToken']; ?>" />
 *
 * These lines need to be placed in the controller action to validate CSRF token submitted with the form:
 * if (!Csrf::isTokenValid()) {
 *   // Error Action
 * }
 */

namespace Helpers;

class Csrf {
    /**
     * Retrieve the CSRF token and generate a new one if expired.
     *
     * @access public
     * @static static method
     * @return string
     */
    public static function makeToken($name = 'csrfToken') {
        $max_time = 60 * 60; // token is valid for 1 hour.
        $csrfToken = Session::get($name);
        $stored_time = Session::get($name . '_time');

        if ($max_time + $stored_time <= time() || empty($csrfToken)) {
            if (function_exists('random_bytes')) {
                $token_hash = bin2hex(random_bytes(32));
            } else {
                $token_hash = bin2hex(openssl_random_pseudo_bytes(32));
            }
            Session::set($name, $token_hash);
            Session::set($name . '_time', time());
        }

        return Session::get($name);
    }

    /**
     * Check to see if the CSRF token in session is the same as submitted form.
     *
     * @access public
     * @static static method
     * @return bool
     */
    public static function isTokenValid($name = 'csrfToken') {
      $post_name = $name;
      if ((isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))) {
        if (strtolower(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)) != strtolower($_SERVER['HTTP_HOST'])) {
          /* referer not from the same domain */
          return false;
        }else{
          // Get data from request and session to see if valid
          //var_dump(Request::post($post_name), Session::get($name));
          if(!empty(Request::post($post_name)) && !empty(Session::get($name))){
            return hash_equals(Request::post($post_name), Session::get($name));
          }
        }
      }else{
        return false;
      }

    }
    /**
     * Generate a random number using any available function on the system.
     *
     * @access public
     * @static static method
     * @return integer
     */

    public static function genRandomNumber($size = 32) {
        if (extension_loaded('openssl')) {
            return openssl_random_pseudo_bytes($size);
        }
        if (extension_loaded('mcrypt')) {
            return mcrypt_create_iv($size, MCRYPT_DEV_URANDOM);
        }
        if (function_exists('random_bytes')) {
            return random_bytes($size);
        }
        return mt_rand(0,mt_getrandmax());

    }

}
