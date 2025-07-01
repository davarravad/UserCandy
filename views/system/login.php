<?php
/**
* Account Login View
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 2.0.0.0
*/

use Core\{Header,Footer};
use Helpers\{Url,Request,Popups,Lang,Csrf,SiteStats};
use Models\{UsersModel};

/* Check to see if user is already logged in */
        if ($authHelper->isLogged())
            Url::redirect();

        /* Get User Bot Protection Field - Should be empty if Human */
        $ubp_name = Request::post('ubp_name');

        $usersModel = new UsersModel();

        /* Start the Login Process */
        if (isset($_POST['submit']) && Csrf::isTokenValid('login') && empty($ubp_name)) {

            $username = strip_tags( trim( Request::post('username') ) );
            $password = Request::post('password');
            $rememberMe = null !=  strip_tags( trim( Request::post('rememberMe') ) );

            $email = $authHelper->checkIfEmail($username);
            $username = $email && (count($email) != 0 ) ? $email[0]->userName : $username;

            if ($authHelper->login($username, $password, $rememberMe)) {
                $userId = $authHelper->currentSessionInfo()['uid'];

                /** Update the last login timestamp for user to now **/
                $info = array('LastLogin' => date('Y-m-d G:i:s'));
                $where = array('userId' => $userId);
                $authHelper->updateUser($info,$where);

                $usersModel->update($userId);

                /** Check if user is on new device, if so then add to database **/
                $device_data = SiteStats::updateUserDeviceInfo($userId);

                /** Check if Device is enabled for user **/
                if($device_data[0]->allow == "0"){
                  /** Check if Email Settings are set **/
                  $site_mail_setting = EMAIL_FROM_NAME;
                  if(!empty($site_mail_setting)){
                    /** Send Email letting user know someone that was blocked tried to access their account **/
                    $email = \Helpers\CurrentUserData::getUserEmail($userId);
                    $mail = new \Helpers\Mail();
                    $mail->addAddress($email);
                    $mail->setFrom(SITE_EMAIL, EMAIL_FROM_NAME);
                    $mail->subject(SITE_TITLE. " - ".Lang::get($userLocale,'login_device_email_sub' ));
                    $body = \Helpers\PageFunctions::displayEmailHeader();
                    $body .= Lang::get($userLocale,'login_blocked_device_email',array($username, SITE_TITLE));
                    $body .= "<hr><b>".Lang::get($userLocale,'device_device', 'Members')."</b>";
                    $body .= "<br>".$device_data[0]->browser." - ".$device_data[0]->os;
                    $body .= "<hr><b>".Lang::get($userLocale,'device_location', 'Members')."</b>";
                    $body .= "<br>".$device_data[0]->city.", ".$device_data[0]->state.", ".$device_data[0]->country;
                    $body .= Lang::get($userLocale,'login_device_footer_email' );
                    $body .= \Helpers\PageFunctions::displayEmailFooter();
                    $mail->body($body);
                    $mail->send();
                  }
                  /** Device is disabled.  Kick user out and show error **/
                  $usersModel->remove($u_id);
                  $authHelper->logout();
                  /* Error Message Display */
                  Popups::pushError(Lang::get($userLocale,'login_lockedout' ), 'Login');
                }

                /**
                * Login Success
                * Redirect to user
                * Check to see if user came from another page within the site
                */
                if(isset($_SESSION['login_prev_page'])){ $login_prev_page = $_SESSION['login_prev_page']; }else{ $login_prev_page = ""; }
                /**
                * Checking to see if user user was viewing anything before login
                * If they were viewing a page on this site, then after login
                * send them to that page they were on.
                */
                if(!empty($login_prev_page)){
                  /* Send member to previous page */
                  /* Clear the prev page session if set */
                  if(isset($_SESSION['login_prev_page'])){
                    unset($_SESSION['login_prev_page']);
                  }
                  $prev_page = "$login_prev_page";
                  /* Send user back to page they were at before login */
                  /* Success Message Display */
                  Popups::pushSuccess(Lang::get($userLocale,'login_success'), $prev_page);
                }else{
                  /* No previous page, send member to home page */
                  //echo " send user to home page "; // Debug

                  /* Clear the prev page session if set */
                  if(isset($_SESSION['login_prev_page'])){
                    unset($_SESSION['login_prev_page']);
                  }

                  /* Redirect member to home page */
                  /* Success Message Display */
                 Popups::pushSuccess(Lang::get($userLocale,'login_success' ), '');
                }
            }
            else{
                /* Error Message Display */
                Popups::pushError(Lang::get($userLocale,'login_incorrect' ), 'Login');
            }
        }

        $data['csrfToken'] = Csrf::makeToken('login');
        $data['title'] = Lang::get($userLocale,'login_page_title' );
        $data['welcomeMessage'] = Lang::get($userLocale,'login_page_welcomeMessage' );

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $authHelper->isLogged()){
            /** User is logged in - Get their data **/
            $u_id = $authHelper->user_info();
            $data['currentUserData'] = $usersModel->getCurrentUserData($u_id);
            $data['isAdmin'] = $usersModel->checkIsAdmin($u_id);
        }

// Set the shared data for this page.
$metaData['title'] = Lang::get($userLocale,'SITE_TITLE');
$metaData['description'] = Lang::get($userLocale,'SITE_DESCRIPTION');
$metaData['keywords'] = Lang::get($userLocale,'SITE_KEYWORDS');
$metaData['image'] = SITE_URL."templates/default/assets/images/logo-large.jpg";

// Load the Header
Header::load($metaData);

?>

<main class="container-fluid">
    <div class="form-signin col-sm-12">
        <div class="card my-3 text-center">
            <div class="card-header h4">
                <?=$data['title'];?>
            </div>
            <div class="card-body">
        <form class="" method="post">
            <div class="form-group">
                        <div class="input-group mb-3">
                                <span class='input-group-text'>
                                    <i class="bi bi-person-fill"></i>
                                </span>
                <input  class="form-control" type="text" id="username" name="username" placeholder="<?=Lang::get($userLocale,'login_field_username' )?>">
                        </div>
            </div>
                    <div class="form-group">
                        <div class="input-group mb-3">
                                <span class='input-group-text'>
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                            <input class="form-control" type="password" id="password" name="password" placeholder="<?=Lang::get($userLocale,'login_field_password' )?>">
                        </div>
                    </div>
            <label class="control-label">
            <input type="checkbox" id="rememberMe" name="rememberMe">
            <?=Lang::get($userLocale,'login_field_rememberme' )?>
            </label>
            <input type="hidden" name="login" value="<?=$data['csrfToken'];?>" />
                    <!-- UBP Name Protection -->
                    <input type="text" name="ubp_name" value="" class="hidden" />
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit"><i class="bi bi-door-open-fill"></i> <?=Lang::get($userLocale,'login_button' )?></button>
        </form>

            </div>
            <div class="card-footer text-muted">
                    <?=Lang::get($userLocale,'dont_have_an_account' )?> <a class="" name="" href="<?=SITE_URL?>Register"><?=Lang::get($userLocale,'register_button' )?></a>
            <?php $email_host = SITE_EMAIL; if($email_host != ''){ ?>
                        <br><a class="" name="" href="<?=SITE_URL?>Forgot-Password"><?=Lang::get($userLocale,'forgotpass_button' )?></a>
                        <br><a class="" name="" href="<?=SITE_URL?>Resend-Activation-Email"><?=Lang::get($userLocale,'resendactivation_button' )?></a>
            <?php } ?>
        </div>
    </div>
    </div>
</main>

<?php
// End Page Content

// Load the Footer
Footer::load();