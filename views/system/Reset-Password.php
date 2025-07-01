<?php
/**
 * Account Reset Password View
 *
 * UserCandy PHP Framework
 * @author David (DaVaR) Sargent <davar@usercandy.com>
 * @version uc 2.0.0.0
 */

use Core\{Header, Footer};
use Helpers\{Csrf, Request, Popups, Url, Lang};

if ($authHelper->isLogged())
	Url::redirect();

/** Get data from URL **/
(empty($urlParams[0])) ? $val1 = null : $val1 = $urlParams[0];
(empty($urlParams[1])) ? $username = "" : $username = $urlParams[1];
(empty($urlParams[2])) ? $val3 = null : $val3 = $urlParams[2];
(empty($urlParams[3])) ? $resetkey = "" : $resetkey = $urlParams[3];

if ($authHelper->checkResetKey($username, $resetkey)) {
	if (isset($_POST['submit'])) {
		if (Csrf::isTokenValid('resetpassword')) {
			$password = Request::post('password');
			$confirm_password = Request::post('confirmPassword');

			if ($authHelper->resetPass('', $username, $resetkey, $password, $confirm_password)) {
				/** Success Message Display **/
				Popups::pushSuccess(Lang::get("", 'resetpass_success'), 'Login');
			} else {
				/** Error Message Display **/
				Popups::pushError(Lang::get("", 'resetpass_error'), 'Forgot-Password');
			}
		}
	}
} else {
	$data['message'] = "Some Error Occurred";
	/** Error Message Display **/
	Popups::pushError($data['message'], 'Forgot-Password');
}

$data['csrfToken'] = Csrf::makeToken('resetpassword');
$data['title'] = Lang::get("", 'resetpass_title');
$data['welcomeMessage'] = Lang::get("", 'resetpass_welcomemessage');

/** Setup Breadcrumbs **/
$data['breadcrumbs'] = "<li class='breadcrumb-item active'>" . $data['title'] . "</li>";

// Set the shared data for this page.
$metaData['title'] = Lang::get($userLocale, 'SITE_TITLE');
$metaData['description'] = Lang::get($userLocale, 'SITE_DESCRIPTION');
$metaData['keywords'] = Lang::get($userLocale, 'SITE_KEYWORDS');
$metaData['image'] = SITE_URL . "templates/default/assets/images/logo-large.jpg";

// Load the Header
Header::load($metaData);

?>

<main class="container-fluid">
	<div class="form-signin col-sm-12">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card mb-3">
				<div class="card-header h4">
					<?= $data['title']; ?>
				</div>
				<div class="card-body">
					<p>
						<?= $data['welcomeMessage']; ?>
					</p>
					<div class="row">
						<form class="form" method="post">
							<div class="col-xs-12">
								<div class="form-group">
									<div class="input-group mb-3">
										<div class='input-group-prepend'>
											<span class='input-group-text'>
												<?= Lang::get("", 'new_password_label'); ?>
											</span>
										</div>
										<input class="form-control" type="password" id="password" name="password"
											placeholder="<?= Lang::get("", 'new_password_label'); ?>">
									</div>
								</div>
								<div class="form-group">
									<div class="input-group mb-3">
										<div class='input-group-prepend'>
											<span class='input-group-text'>
												<?= Lang::get("", 'confirm_new_password_label'); ?>
											</span>
										</div>
										<input class="form-control" type="password" id="confirmPassword" name="confirmPassword"
											placeholder="<?= Lang::get("", 'confirm_new_password_label'); ?>">
									</div>
								</div>

								<input type="hidden" name="resetpassword" value="<?= $data['csrfToken']; ?>" />
								<button class="btn btn-primary" type="submit" name="submit">
									<?= Lang::get("", 'change_my_password_button'); ?>
								</button>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php

// Load the Footer
Footer::load();