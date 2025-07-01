<?php
/**
* Home Page
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

use Core\{Header,Footer};
use Helpers\{Lang,Trailers};
use Models\{TrailersModel};

// Set the shared data for this page.
$metaData['title'] = Lang::get($userLocale,'SITE_TITLE');
$metaData['description'] = Lang::get($userLocale,'SITE_DESCRIPTION');
$metaData['keywords'] = Lang::get($userLocale,'SITE_KEYWORDS');
$metaData['image'] = SITE_URL."templates/default/assets/images/logo-large.jpg";

// Load the Header
Header::load($metaData);



// Start Page Content
// Check if user is logged in
if(!empty($uid) && ($authHelper->hasRole($uid,"1") || $authHelper->hasRole($uid,"3") || $authHelper->hasRole($uid,"4"))){

?>

<main class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card mb-3">
				<div class="card-header h4">
					Something will go here.
				</div>
				<div class="card-body">

          Hello.  Things coming soon!

        </div>
      </div>
    </div>
  </div>
</main>


<?php
} else if(!empty($userName)){
?>

<main class="container-fluid">
	<div class="col-sm-12">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card mb-3">
				<div class="card-header h4">
					Welcome to the <?=SITE_TITLE?> Website.
				</div>
				<div class="card-body">
          Hello there!
        </div>
      </div>
    </div>
  </div>
</main>

<?php 
}else{
?>

<main class="container-fluid">
  <div class="uc-card p-5 rounded">
    <h1>Welcome to UserCandy</h1>
    <p class="lead">Please Login to continue.</p>
  </div>
</main>


<?php
}
// End Page Content

// Load the Footer
Footer::load();

?>

