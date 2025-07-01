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
  Popups::pushError(Lang::get($userInformation->userLocale,'NOT_LOGGED_IN'), '');
  exit();
}else{
  // Get the user's data
  $userInformation = $authModel->userInformation($uid);
}

if(!$authModel->isAdmin($uid)){
  /* Error Message Display */
  Popups::pushError(Lang::get($userInformation->userLocale,'NOT_ADMIN'), '');
  exit();
}

// Set the shared data for this page.
$metaData['title'] = Lang::get($userInformation->userLocale,'ADMIN_TITLE');
$metaData['description'] = Lang::get($userInformation->userLocale,'SITE_DESCRIPTION');
$metaData['keywords'] = Lang::get($userInformation->userLocale,'SITE_KEYWORDS');
$metaData['image'] = SITE_URL."templates/default/assets/images/logo-large.jpg";

// Load the Header
Header::load($metaData);

// Set defaults for actives
$active['roles'] = "";
$activeAria['roles'] = "";
$active['members'] = "";
$activeAria['members'] = "";
$active['main'] = "";
$activeAria['main'] = "";

// Get url
$urlPage = (!empty($urlParams[0])) ? strtolower($urlParams[0]) : "";

// Check to see which page is active
if(!empty($urlPage)  && $urlPage === "roles"){
  $active['roles'] = "active uc-active";
  $activeAria['roles'] = "aria-current=\"true\"";
}else if(!empty($urlPage)  && $urlPage === "members"){
  $active['members'] = "active uc-active";
  $activeAria['members'] = "aria-current=\"true\"";
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
          <a class="nav-link <?=$active['main']?>" <?=$activeAria['main']?> href="<?=SITE_URL?>Admin/"><?=Lang::get($userInformation->userLocale,'ADMIN_MAIN')?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?=$active['roles']?>" <?=$activeAria['roles']?> href="<?=SITE_URL?>Admin/Roles"><?=Lang::get($userInformation->userLocale,'ADMIN_ROLES')?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?=$active['members']?>" <?=$activeAria['members']?> href="<?=SITE_URL?>Admin/Members"><?=Lang::get($userInformation->userLocale,'ADMIN_MEMBERS')?></a>
        </li>
      </ul>
    </div>
    <div class="card-body">

<?php 
    // Check to see if page is requested
    if(!empty($urlPage)  && $urlPage === "roles"){
      require(VIEWSDIR."system/admin/roles.php");
    }else if(!empty($urlPage)  && $urlPage === "members"){
      require(VIEWSDIR."system/admin/members.php");
    }else{
      // load the main page
      require(VIEWSDIR."system/admin/main.php");
    }
?>

    </div>
  </div>
</div>


<?php
// End Page Content

// Load the Footer
Footer::load();