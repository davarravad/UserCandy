<?php
/**
* Account Page
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

use Core\{Header,Footer};
use Helpers\{Lang,Popups};

// Check if logged in.  If not then send to home page.
if(empty($uid) || empty($userName)){
  /* Error Message Display */
  Popups::pushError(Lang::get($userLocale,'NOT_LOGGED_IN'), '');
  exit();
}else{
  // Get the user's data
  $userInformation = $authModel->userInformation($uid);
}

// Set the shared data for this page.
$metaData['title'] = Lang::get($userLocale,'ACCOUNT_ACCOUNT');
$metaData['description'] = Lang::get($userLocale,'SITE_DESCRIPTION');
$metaData['keywords'] = Lang::get($userLocale,'SITE_KEYWORDS');
$metaData['image'] = SITE_URL."templates/default/assets/images/logo-large.jpg";

// Load the Header
Header::load($metaData);

// Set defaults for actives
$active['noti'] = "";
$activeAria['noti'] = "";
$active['main'] = "";
$activeAria['main'] = "";

// Get url
$urlPage = (!empty($urlParams[0])) ? strtolower($urlParams[0]) : "";

// Check to see which page is active
if(!empty($urlPage)  && $urlPage === "notifications"){
  $active['noti'] = "active uc-active";
  $activeAria['noti'] = "aria-current=\"true\"";
}else{
  $active['main'] = "active uc-active";
  $activeAria['main'] = "aria-current=\"true\"";
}

// Start Page Content
?>

<div class="container-fluid">
  <div class="card text-center uc-card">
    <div class="card-header uc-card-head">
      <ul class="nav nav-tabs card-header-tabs">
        <li class="nav-item">
          <a class="nav-link <?=$active['main']?>" <?=$activeAria['main']?> href="<?=SITE_URL?>Account/"><?=Lang::get($userLocale,'ACCOUNT_ACCOUNT')?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?=$active['noti']?>" <?=$activeAria['noti']?> href="<?=SITE_URL?>Account/Notifications"><?=Lang::get($userLocale,'ACCOUNT_NOTIFICATIONS')?></a>
        </li>
      </ul>
    </div>
    <div class="card-body">

<?php 
    // Check to see if page is requested
    if(!empty($urlPage)  && $urlPage === "notifications"){
      require(VIEWSDIR."system/account/notifications.php");
    }else{
      // load the main page
      require(VIEWSDIR."system/account/main.php");
    }
?>

    </div>
  </div>
</div>


<?php
// End Page Content

// Load the Footer
Footer::load();