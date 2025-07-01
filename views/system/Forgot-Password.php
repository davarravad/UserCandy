<?php
/**
 * Account Forgot Password Page
 *
 * UserCandy PHP Framework
 * @author David (DaVaR) Sargent <davar@usercandy.com>
 * @version uc 2.0.0.0
 */

use Core\{Header, Footer};
use Helpers\{Csrf, Request, Popups, Url, Lang};

if ($authHelper->isLogged())
	Url::redirect();

if (isset($_POST['submit'])) {

	if (Csrf::isTokenValid('forgotpassword')) {
		$email = trim(Request::post('email'));

		if ($authHelper->resetPass($email)) {
			/** Success Message Display **/
			Popups::pushSuccess(Lang::get("", 'resetpass_email_sent'), 'Forgot-Password');
		} else {
			/** Error Message Display **/
			Popups::pushError(Lang::get("", 'resetpass_email_error'), 'Forgot-Password');
		}
	}
}

$data['csrfToken'] = Csrf::makeToken('forgotpassword');
$data['title'] = Lang::get("", 'forgotpass_title');
$data['welcomeMessage'] = Lang::get("", 'forgotpass_welcomemessage');

/** Check to see if user is logged in **/
$data['isLoggedIn'] = $authHelper->isLogged();

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
					<form class="form" method="post">
						<div class="col-xs-12">
							<div class="form-group">
								<div class="input-group mb-3">
									<div class='input-group-prepend'>
										<span class='input-group-text'>
											<?= Lang::get("", 'register_field_email'); ?>
										</span>
									</div>
									<input class="form-control" type="email" id="email" name="email"
										placeholder="<?= Lang::get("", 'register_field_email'); ?>">
								</div>
							</div>
							<input type="hidden" name="forgotpassword" value="<?= $data['csrfToken']; ?>" />
							<button class="btn btn-primary" type="submit" name="submit">
								<?= Lang::get("", 'forgotpass_button') ?>
							</button>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</main>
	<?php

// Load the Footer
Footer::load();