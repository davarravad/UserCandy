<?php
/**
* Success Messages Plguin
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

namespace Helpers;

/**
 * collection of methods for working with success messages.
 */
class Popups
{
  /**
   * Get and display recent success message from success session
   * @return string
   */
  public static function displaySuccess(){
    // Check to see if session success_message exists
    if(isset($_SESSION['success_message'])){
      // Get data from session then display it
  		$success_msg = $_SESSION['success_message'];
  		$display_msg = "
        <div class='modal hide fade' id='alertModal' role='dialog'>
          <div class='modal-dialog modal-lg'>
            <div class='modal-content'>
              <div class='modal-header bg-success'>
                <h5 class='modal-title' id='DeleteLabel'><strong>Success!</strong></h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
              </div>
              <div class='modal-body'>
                <p>$success_msg</p>
              </div>
            </div>
          </div>
        </div>
      ";
  		unset($_SESSION['success_message']);
      return $display_msg;
  	}
  }

  /**
  * Push Success Message to Session for display on page user is redirected to
  * @param $error_msg  string  Message Text
  * @param $redirect_to_page  string  URL Page Name for Redirect
  */
  public static function pushSuccess($success_msg, $redirect_to_page = null){
    // Check to see if there is already a success message session
    if(isset($_SESSION['success_message'])){
      // Clean success message Session
      unset($_SESSION['success_message']);
    }
    // Send success message to session
    $_SESSION['success_message'] = $success_msg;
    // Check to see if a redirect to page is supplied
    if(isset($redirect_to_page)){
      // Redirect User to Given Page
      Url::redirect($redirect_to_page);
    }
  }

  /**
  * Displays Message without sessions to keep form data for retry
  * @param $e_msg  string  Message Text
  * @return string
  */
  public static function displayRawSuccess($s_msg = null){
    // Make sure an Error Message should be displayed
    if(isset($s_msg)){
      // Check to see if we are displaying an array of messages
      if(is_array($s_msg)){
        // Setup Array for display
        $success_msg = "";
        foreach($s_msg as $sm){
          $success_msg .= "<br>$sm";
        }
      }else{
        $success_msg = $s_msg;
      }
        // Not an array, display single error
      $display_msg = "
        <div class='modal hide fade' id='alertModal' role='dialog'>
          <div class='modal-dialog modal-lg'>
            <div class='modal-content'>
              <div class='modal-header bg-success'>
                <h5 class='modal-title' id='DeleteLabel'><strong>Success!</strong></h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
              </div>
              <div class='modal-body'>
                <p>$success_msg</p>
              </div>
            </div>
          </div>
        </div>
      ";
      return $display_msg;
    }
  }

  /**
  * Displays Message without sessions to keep form data for retry
  * @param $e_msg  string  Message Text
  * @return string
  */
  public static function displayRawInfo($s_msg = null){
    // Make sure an Error Message should be displayed
    if(isset($s_msg)){
      // Check to see if we are displaying an array of messages
      if(is_array($s_msg)){
        // Setup Array for display
        $success_msg = "";
        foreach($s_msg as $sm){
          $success_msg .= "<br>$sm";
        }
      }else{
        $success_msg = $s_msg;
      }
        // Not an array, display single error
      $display_msg = "
        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
          <div class='alert alert-info alert-dismissible' role='alert'>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            $success_msg
          </div>
        </div>";
      return $display_msg;
    }
  }

  /**
   * Get and display recent success message from success session
   * Temp usage without lang file
   * @return string
   */
  public static function displayNolangSuccess(){
    // Check to see if session success_message exists
    if(isset($_SESSION['success_message'])){
      // Get data from session then display it
  		$success_msg = $_SESSION['success_message'];
  		$display_msg = "
        <div class='modal hide fade' id='alertModal' role='dialog'>
          <div class='modal-dialog modal-lg'>
            <div class='modal-content'>
              <div class='modal-header bg-success'>
                <h5 class='modal-title' id='DeleteLabel'><strong>Success!</strong></h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
              </div>
              <div class='modal-body'>
                <p>$success_msg</p>
              </div>
            </div>
          </div>
        </div>
      ";
  		unset($_SESSION['success_message']);
      return $display_msg;
  	}
  }

  /**
   * Get and display recent error message from error session
   * @return string
   */
  public static function displayError(){
    // Check to see if session error_message exists
    if(isset($_SESSION['error_message'])){
      // Get data from session then display it
  		$error_msg = $_SESSION['error_message'];
  		$display_msg = "
        <div class='modal hide fade' id='alertModal' role='dialog'>
          <div class='modal-dialog modal-lg'>
            <div class='modal-content'>
              <div class='modal-header bg-danger'>
                <h5 class='modal-title' id='DeleteLabel'><strong>Error!</strong></h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
              </div>
              <div class='modal-body'>
                <p>$error_msg</p>
              </div>
            </div>
          </div>
        </div>
      ";
  		unset($_SESSION['error_message']);
      return $display_msg;
  	}
  }

  /**
  * Push Error Message to Session for display on page user is redirected to
  * @param $error_msg  string  Message Text
  * @param $redirect_to_page  string  URL Page Name for Redirect
  */
  public static function pushError($error_msg, $redirect_to_page = null){
    // Check to see if there is already a error message session
    if(isset($_SESSION['error_message'])){
      // Clean error message Session
      unset($_SESSION['error_message']);
    }
    // Send error message to session
    $_SESSION['error_message'] = $error_msg;
    // Check to see if a redirect to page is supplied
    if(isset($redirect_to_page)){
      // Redirect User to Given Page
      Url::redirect($redirect_to_page);
    }
  }

  /**
  * Displays Message without sessions to keep form data for retry
  * @param $e_msg  string  Message Text
  * @return string
  */
  public static function displayRawError($e_msg = null){
    // Make sure an Error Message should be displayed
    if(isset($e_msg)){
      // Check to see if we are displaying an array of errors
      if(is_array($e_msg)){
        // Not an array, display single error
        $error_msg = "";
        foreach($e_msg as $em){
          $error_msg .= "<br>$em";
        }
      }else{
        $error_msg = $e_msg;
      }
        // Not an array, display single error
        $display_msg = "
          <div class='modal hide fade' id='alertModal' role='dialog'>
            <div class='modal-dialog modal-lg'>
              <div class='modal-content'>
                <div class='modal-header alert-danger'>
                  <h5 class='modal-title' id='DeleteLabel'><strong>Error!</strong></h5>
                  <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                  <p>$error_msg</p>
                </div>
              </div>
            </div>
          </div>
        ";
        return $display_msg;
    }
  }

}
