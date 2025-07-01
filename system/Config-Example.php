<?php
/**
* Site Config File
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

class Config {

    public function __construct() {
      /* Enable output buffering */
      ob_start();
  
      /********************
       *                  *
       *     BASICS       *
       *                  *
       ********************/
      /* Define Site Url Address */
      define('SITE_URL', 'https://www.website.com/');
  
      /* Site Title */
      define('SITE_TITLE', 'Website Title');

      /* Default Template */
      define('DEFAULT_TEMPLATE', 'Default');
  
      /* Default Language Code */
      define('LANGUAGE_CODE', 'En');
  
      /* Default Session Prefix */
      define('SESSION_PREFIX', 'uc_');

      /* Default User Role Assigned to all new members. */
      define('DEFAULT_ROLE_ID', '2');

      /* Admin Role Id for Admin Panel access. */
      define('ADMIN_ROLE_ID', '1');

      /* Site User Invite Code. */
      define('SITE_USER_INVITE_CODE', '123456');

      /********************
       *                  *
       *     DATABASE     *
       *                  *
       ********************/
      /**
       * Database engine default is mysql.
       */
      define('DB_TYPE', 'mysql');
      /**
       * Database host default is localhost.
       */
      define('DB_HOST', 'localhost');
      /**
       * Database name.
       */
      define('DB_NAME', 'db_name');
      /**
       * Database username.
       */
      define('DB_USER', 'db_user');
      /**
       * Database password.
       */
      define('DB_PASS', 'db_password');
      /**
       * PREFIX to be used in database calls default is uc_main_
       */
      define('PREFIX', 'uc_');

      /*****************
       *                *
       *     Account    *
       *                *
       *****************/
      // Account activation route
      define("ACTIVATION_ROUTE", 'Activate');
      // Account password reset route
      define("RESET_PASSWORD_ROUTE", 'Reset-Password');
      //INT cost of BCRYPT algorithm
      define("COST", 10);
      //INT hash length of BCRYPT algorithm
      define("HASH_LENGTH", 22);

          /*****************
         *                *
         *     Accounts    *
         *                *
         *****************/
        // Account needs email activation, false=no true=yes
        define("ACCOUNT_ACTIVATION", 'true');
        // Max attempts for login before user is locked out
        define("MAX_ATTEMPTS", '5');
        // How long a user is locked out after they reach the max attempts
        define("SECURITY_DURATION", "+5 minutes");
		    // this is the same as SECURITY_DURATION but in number format
		    $waittime = preg_replace("/[^0-9]/", "", SECURITY_DURATION); //DO NOT MODIFY
		    define('WAIT_TIME', $waittime); //DO NOT MODIFY
        //How long a session lasts : Default = +1 day
        define("SESSION_DURATION", "+1 day");
        //How long a REMEMBER ME SESSION lasts : Default = +1 month
        define("SESSION_DURATION_RM", "+1 month");
        // min length of username
        define('MIN_USERNAME_LENGTH', "5");
        // max length of username
        define('MAX_USERNAME_LENGTH', "100");
        // min length of password
        define('MIN_PASSWORD_LENGTH', "5");
        // max length of password
        define('MAX_PASSWORD_LENGTH', "30");
        // min length of email
        define('MIN_EMAIL_LENGTH', "5");
        //max length of email
        define('MAX_EMAIL_LENGTH', "100");
        //random key used for password reset or account activation
        define('RANDOM_KEY_LENGTH', "15");
        //enable disable online bubble
        define('ONLINE_BUBBLE', "true");

        /********************
         *                  *
         *      EMAIL       *
         *     uses SMTP    *
         ********************/
         /**
         * SMTP Email Username
         */
        define('EMAIL_USERNAME', 'email_username');

        /**
        * SMTP Email Password
        */
        define('EMAIL_PASSWORD', 'email_password');

        /**
        * SMTP Email sent from whom? a name
        */
        define('EMAIL_FROM_NAME', 'email_from_name');

        /**
         * SMTP Email host
         * Example : Google (smtp.gmail.com), Yahoo (smtp.mail.yahoo.com)
         */
        define('EMAIL_HOST', 'email_host');

        /**
         * SMTP Email port
         * default : 25 (https://www.arclab.com/en/kb/email/list-of-smtp-and-pop3-servers-mailserver-list.html)
         */
        define('EMAIL_PORT', '587');

        /**
         * SMTP Email authentication
         * default : ssl
         * choices : ssl, tls, (leave it empty)
         */
        define('EMAIL_STMP_SECURE', 'tls');

        /**
         * Optional set a site email address.
         */
        define('SITE_EMAIL', 'email_site');

        /********************
         *                  *
         *     RECAPTCHA    *
         *                  *
         ********************/
        // reCAPCHA site key provided by google
        define("RECAP_PUBLIC_KEY", '');
        // reCAPCHA secret key provided by google
        define("RECAP_PRIVATE_KEY", '');

      /**
       * Turn on custom error handling.
       */
      set_exception_handler('Core\ErrorLogger::ExceptionHandler');
      set_error_handler('Core\ErrorLogger::ErrorHandler');

      $GLOBALS["instances"] = array();

    }
}
