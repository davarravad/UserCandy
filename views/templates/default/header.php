<?php
/**
* Default Header
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

use Helpers\{Request,Popups,Lang,Csrf,AuthHelper};
use Models\{AuthModel,DispatchModel};

// Check to see if user is logged in
$authHelper = new AuthHelper();

// Load user data if exists
if($authHelper->isLogged()){
  extract($authHelper->currentSessionInfo());
  // Load the auth model
  $authModel = new AuthModel();
  $userLocale = "";
  $dispatchModel = new DispatchModel();
}else{
  $userLocale = "";
}

// Check for meta data from view
$metaTitle = (!empty($metaData['title'])) ? $metaData['title'] : "";
$metaDescription = (!empty($metaData['description'])) ? $metaData['description'] : "";
$metaKeywords = (!empty($metaData['keywords'])) ? $metaData['keywords'] : "";
$metaImage = (!empty($metaData['image'])) ? $metaData['image'] : "";

// Check if user has dark theme enabled
if(!empty($uid)){
  $userDarkTheme = $authHelper->getUserDarkTheme($uid);
}
$darkTheme = (!empty($userDarkTheme) && $userDarkTheme === true) ? "data-bs-theme=\"dark\"" : "";

?>

<!doctype html>
<html lang="en" <?=$darkTheme?>>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title><?=$metaData['title']?></title>
    <link rel="canonical" href="<?=SITE_URL?><?=Request::get("url")?>" />
    <meta name="keywords" content="<?=$metaKeywords?>">
    <meta name="description" content="<?=$metaDescription?>">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?=$metaTitle?>" />
    <meta property="og:description" content="<?=$metaDescription?>" />
    <meta property="og:url" content="<?=SITE_URL?><?=Request::get("url")?>" />
    <meta property="og:site_name" content="<?=$metaTitle?>" />
    <meta property="og:image" content="<?=$metaImage?>"/>
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="<?=$metaDescription?>" />
    <meta name="twitter:title" content="<?=$metaTitle?>" />

    <link href="<?=SITE_URL?>templates/default/assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="<?=SITE_URL?>templates/default/assets/css/style.css" rel="stylesheet">
    <link href="<?=SITE_URL?>templates/default/assets/css/lib/select2.min.css" rel="stylesheet" />
    <link rel='shortcut icon' href='<?=SITE_URL?>templates/default/assets/images/favicon.ico'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  </head>
  <body>

  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?=SITE_URL?>">  
        <!-- <img src="<?=SITE_URL?>templates/default/assets/images/logo-small.png" height="50px" /> -->
        UserCandy
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <?php if(!empty($uid) && ($authHelper->hasRole($uid,"1") || $authHelper->hasRole($uid,"3") || $authHelper->hasRole($uid,"4"))){ ?>
          <li class="nav-item">
            <a class="nav-link" href="<?=SITE_URL?>Drivers">Drivers</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?=SITE_URL?>Planning">Planning</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?=SITE_URL?>Orders">Orders</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?=SITE_URL?>Routes">Routes</a>
          </li>

        <?php } ?>
      </ul>
      <?php 
            if(!empty($userName)){
              $searchData = (!empty($_POST['searchData'])) ? $_POST['searchData'] : "";
      ?>
          <form class="d-flex p-2" role="search" action="<?=SITE_URL?>Search/" method="post">
            <input type="search" class="form-control" placeholder="Search" aria-label="Search" id="search" name="searchData" value="<?=$searchData?>">
          </form>
            <div class="flex-shrink-0 dropdown text-end">
              <a href="#" class="d-block link-light text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="true">
                <i class="bi bi-person-circle text-white h3"></i>
              </a>
              <ul class="dropdown-menu text-small shadow" data-popper-placement="bottom-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 34px);">
                <li><div class="text-center p-3"><strong><?=$userName?></strong></div></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?=SITE_URL?>Account"><?=Lang::get($userLocale,'NAV_ACCOUNT')?></a></li>
                <?php 
                  // Check to see if user is an admin
                  if($authModel->isAdmin($uid)){
                    echo "<li><a class=\"dropdown-item\" href=\"".SITE_URL."Admin\">".Lang::get($userLocale,'NAV_ADMIN_PANEL')."</a></li>";
                  }
                ?>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?=SITE_URL?>Logout"><?=Lang::get($userLocale,'NAV_SIGN_OUT')?></a></li>
              </ul>
            </div>
          <?php
            }else{
              echo "<form action='".SITE_URL."Login' method='post'>";
                echo "<input type='hidden' id='csrfToken' name='csrfToken' value='".Csrf::makeToken()."'>";
                echo "<button type='submit' class='btn btn-outline-light me-2'>Login</button>";
              echo "</form>";
            }
          ?>
    </div>
  </div>
</nav>


<?php
  // Setup the Error and Success Messages Libs
  // Display Success and Error Messages if any
  echo Popups::displayError();
  echo Popups::displaySuccess();
  if(isset($error)) { echo Popups::displayRawError($error); }
  if(isset($success)) { echo Popups::displayRawSuccess($success); }
  if(isset($info_alert)) { echo Popups::displayRawInfo($info_alert); }

?>

