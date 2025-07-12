<?php
/**
* Account Login View
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 2.0.0.0
*/

use Helpers\{Lang,Popups};

if ($authHelper->isLogged()) {
    $u_id = $authHelper->currentSessionInfo()['uid'];
    $usersModel->remove($u_id);
    $authHelper->logout();
}
/** Success Message Display **/
Popups::pushSuccess(Lang::get($userLocale,'logout' ), 'Login');
