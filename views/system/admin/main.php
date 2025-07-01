<?php
/**
* Account Main Page
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

use Helpers\{Lang};

// Get all of this user's roles
$userRoles = $authModel->userRoles($userInformation->userId);
$totalRoles = $authModel->totalRoles();
$totalMembers = $authModel->totalMembers();

?>

  <div class="row">
    <div class="col">
        <div class="card uc-card-in">
            <div class="card-body text-center">
              <?=Lang::get($userInformation->userLocale,'ADMIN_WELCOME')?><br><Br>
              <strong><?=Lang::get($userInformation->userLocale,'ADMIN_ROLES')?></strong> - <?=$totalRoles?> - <?=Lang::get($userInformation->userLocale,'ADMIN_ROLES_INFO')?><br>
              <strong><?=Lang::get($userInformation->userLocale,'ADMIN_MEMBERS')?></strong> - <?=$totalMembers?> - <?=Lang::get($userInformation->userLocale,'ADMIN_MEMBERS_INFO')?><br>
            </div>
        </div>
    </div>
  </div>
