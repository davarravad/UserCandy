<?php
/**
* Account Activate Page
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 2.0.0.0
*/

use Helpers\{Csrf,Request,Popups,Url,Lang};

if ($authHelper->isLogged())
		Url::redirect();

/** Get data from URL **/
(empty($urlParams[0])) ? $val1 = null : $val1 = $urlParams[0];
(empty($urlParams[1])) ? $username = "" : $username = $urlParams[1];
(empty($urlParams[2])) ? $val3 = null : $val3 = $urlParams[2];
(empty($urlParams[3])) ? $activekey = "" : $activekey = $urlParams[3];

$activekey = trim( $activekey );

if($authHelper->activateAccount($username, $activekey)) {
		/** Success Message Display **/
		Popups::pushSuccess(Lang::get($userLocale,'activate_success' ), 'Login');
}
else{
		/** Error Message Display **/
		Popups::pushError(Lang::get($userLocale,'activate_fail' ), 'Resend-Activation-Email');
}

$data['title'] = Lang::get($userLocale,'activate_title' );
$data['welcomeMessage'] = Lang::get($userLocale,'activate_welcomemessage' );

/** Setup Breadcrumbs **/
$data['breadcrumbs'] = "<li class='breadcrumb-item active'>".$data['title']."</li>";


?>

<div class="col-lg-12">
	<div class="card mb-3">
		<div class="card-header h4">
        <h1><?php echo $data['title']; ?></h1>
    </div>
  </div>
</div>
