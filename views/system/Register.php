<?php
/**
* Account Registration View
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 2.0.0.0
*/

use Core\{Header,Footer};
use Helpers\{Url,Request,Csrf,Popups,Lang,Form};
use Models\AdminPanelModel;

//Redirect user to home page if he is already logged in
if ($authHelper->isLogged())
		Url::redirect();

/** Get Terms and Privacy Data **/
$AdminPanelModel = new AdminPanelModel();
$site_terms = $AdminPanelModel->getSettings('site_terms_content');
$site_privacy = $AdminPanelModel->getSettings('site_privacy_content');

//The form is submmited
if (isset($_POST['submit'])) {
		// Get Post Data just in case of fail
		$data['email'] = Request::post('email');
		$data['agree_terms_policy'] = Request::post('agree_terms_policy');
		//Check the CSRF token first
		if(Csrf::isTokenValid('register')) {
				$captcha_fail = false;
				//Check the reCaptcha if the public and private keys were provided
				if (RECAP_PUBLIC_KEY != "" && RECAP_PRIVATE_KEY != "") {
					if(isset($_POST['g-recaptcha-response'])){
			    	$captcha=$_POST['g-recaptcha-response'];
			    }else{
			    	$captcha = false;
					}
			    if(!$captcha){
			      $captcha_fail = true;
			    }else{
            $secret = RECAP_PRIVATE_KEY;
            $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
						// use json_decode to extract json response
            if($response.'success'==false){
                $captcha_fail = true;
            }
			    }
				}
				/** Check if Terms and Privacy is enabled **/
				if(!empty($site_terms) || !empty($site_privacy)){
					/** Check to see if user agreed to Terms and Policy **/
					if($data['agree_terms_policy'] != "true"){
						/** Error Message Display **/
						Popups::pushError(Lang::get($userLocale,'register_error' ), 'Register');
					}
				}
				/** Check for site user invite code **/
				$site_user_invite_code = strip_tags( trim( Request::post('site_user_invite_code') ) );
				$site_user_invite_code_db = SITE_USER_INVITE_CODE;
				if(!empty($site_user_invite_code_db)){
					if($site_user_invite_code != $site_user_invite_code_db){
						/** Error Message Display **/
						Popups::pushError(Lang::get($userLocale,'register_error' ), 'Register');
					}
				}
				/* Get User Bot Protection Field - Should be empty if Human */
				$ubp_name = Request::post('ubp_name');
				/** Only continue if captcha did not fail **/
				if (!$captcha_fail && empty($ubp_name)) {
						$password = Request::post('password');
						$verifypassword = Request::post('passwordc');
						$email = trim ( Request::post('email') );
						/** Register with our without email verification **/
						$registered = $authHelper->register($email, $password, $verifypassword, $email);
						/** Check for New User Registration Success **/
						if ($registered == 'registered') {
								/** Check to see if Account Activation is required **/
								$account_activation = ACCOUNT_ACTIVATION;
								if($account_activation == "true"){
										$data['message'] = Lang::get($userLocale,'register_success' );
								}else{
										$data['message'] = Lang::get($userLocale,'register_success_noact' );
								}
								/** Success Message Display **/
								Popups::pushSuccess($data['message'], 'Login');
						}
						else{
								/** Error Message Display **/
								$data['error'] = Lang::get($userLocale,'register_error' ).$registered;
						}
				}
				else{
						/** Error Message Display **/
						$data['error'] = Lang::get($userLocale,'register_error_recap' );
				}
		}
		else{
				/** Error Message Display **/
				$data['error'] = Lang::get($userLocale,'register_error' );
		}
}

$data['csrfToken'] = Csrf::makeToken('register');
$data['title'] = Lang::get($userLocale,'register_page_title' );
$data['welcomeMessage'] = Lang::get($userLocale,'register_page_welcomeMessage' );

/** Let Site Know if Invite Code is enabled **/
$site_user_invite_code_db = SITE_USER_INVITE_CODE;
if(!empty($site_user_invite_code_db)){ $data['invite_code'] = true; }

/** needed for recaptcha **/
if (RECAP_PUBLIC_KEY != "" && RECAP_PRIVATE_KEY != "") {
		echo "
			<script src='https://www.google.com/recaptcha/api.js?render=".RECAP_PUBLIC_KEY."'></script>
			<script>
			    grecaptcha.ready(function() {
			    // do request for recaptcha token
			    // response is promise with passed token
			        grecaptcha.execute('".RECAP_PUBLIC_KEY."', {action:'validate_captcha'})
			                  .then(function(token) {
			            // add token value to form
			            document.getElementById('g-recaptcha-response').value = token;
			        });
			    });
			</script>
		";
}


/** Display Error Messages **/
if(isset($data['error'])) { echo Popups::displayRawError($data['error']); }

// Set the shared data for this page.
$metaData['title'] = Lang::get($userLocale,'SITE_TITLE');
$metaData['description'] = Lang::get($userLocale,'SITE_DESCRIPTION');
$metaData['keywords'] = Lang::get($userLocale,'SITE_KEYWORDS');
$metaData['image'] = SITE_URL."templates/default/assets/images/logo-large.jpg";

// Load the Header
Header::load($metaData);

?>

<div class="form-signin col-sm-12">
	<div class="card my-3 text-center">
		<div class="card-header h4">
			<?=$data['title'];?>
		</div>
		<div class="card-body">
			<p><?=$data['welcomeMessage'];?></p>

			<?php echo Form::open(array('method' => 'post')); ?>

				<!-- Email -->
				<div class='form-group'>
					<div class='input-group mb-3'>
						<span class='input-group-text'><i class='bi bi-envelope-at-fill'></i></span>
						<?php 
							$dataEmail = (!empty($data['email'])) ? $data['email'] : "";
							echo Form::input(array('id' => 'email', 'type' => 'text', 'name' => 'email', 'class' => 'form-control', 'placeholder' => Lang::get($userLocale,'register_field_email' ), 'value' => $dataEmail)); 
						?>
						<span id='resultemail' class='input-group-text'></span>
					</div>
				</div>

				<!-- Password 1 -->
					<div class='form-group'>
						<div class='input-group mb-3'>
							<span class='input-group-text'><i class='bi bi-lock-fill'></i></span>
							<?php echo Form::input(array('id' => 'passwordInput', 'type' => 'password', 'name' => 'password', 'class' => 'form-control', 'placeholder' => Lang::get($userLocale,'register_field_password' ))); ?>
							<span id='password01' class='input-group-text'></span>
						</div>
					</div>

				<!-- Password 2 -->
					<div class='form-group'>
						<div class='input-group mb-3'>
							<span class='input-group-text'><i class='bi bi-lock-fill'></i></span>
							<?php echo Form::input(array('id' => 'confirmPasswordInput', 'type' => 'password', 'name' => 'passwordc', 'class' => 'form-control', 'placeholder' => Lang::get($userLocale,'register_field_confpass' ))); ?>
							<span id='password02' class='input-group-text'></span>
						</div>
					</div>

				<?php if($data['invite_code']){ ?>
				<!-- Invite Code -->
				<div class='form-group'>
					<div class='input-group mb-3'>
						<span class='input-group-text'><i class='bi bi-key-fill'></i></span>
						<?php echo Form::input(array('id' => 'site_user_invite_code', 'type' => 'text', 'name' => 'site_user_invite_code', 'class' => 'form-control', 'placeholder' => Lang::get($userLocale,'register_field_invite' ))); ?>
					</div>
				</div>
				<?php } ?>

				<!-- reCAPTCHA -->
				<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
    			<input type="hidden" name="action" value="validate_captcha">

				<!-- CSRF Token -->
				<input type="hidden" name="register" value="<?=$data['csrfToken'];?>" />

				<!-- UBP Name Protection -->
				<input type="text" name="ubp_name" value="" class="hidden" />

				<!-- Error Msg Display -->
				<span id='resultun2' class='label'></span>
				<span class='label' id='passwordStrength'></span>
				<span id='resultemail2' class='label'></span>

				<?php
					/** Check to see if Terms and Privacy are enabled **/
					if(!empty($site_terms) || !empty($site_privacy)){
				?>
				<label class="control-label">
					<input type="checkbox" name="agree_terms_policy" value="true"> <?php echo Lang::get($userLocale,'agree_terms_policy',array(SITE_TITLE, SITE_URL, SITE_URL)); ?>
				</label>
				<?php } ?>

				<button class="btn btn-lg btn-success btn-block" name="submit" type="submit">
					<i class="bi bi-person-plus-fill"></i> <?php echo Lang::get($userLocale,'register_button' ); ?>
				</button>
			<?php echo Form::close(); ?>

    </div>
  </div>
</div>

<?php

// Load the Footer
Footer::load();