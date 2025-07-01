<?php
/**
* Error Page
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

use Core\{Header,Footer};

// Set the shared data for this page.
$metaData['title'] = "UserCandy";
$metaData['description'] = "Welcome to UserCandy!";

// Load the Header
Header::load($metaData);

// Start Page Content/
?>


<main class="container-fluid">
  <div class="uc-card p-5 rounded">
    <h1>Page Not Found</h1>
    <p class="lead">The page you requested was not found or is no longer available.  Please check your link and try again.</p>
  </div>
</main>


<?php
// End Page Content

// Load the Footer
Footer::load();