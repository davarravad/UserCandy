<?php
/**
* Account Main Page
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

use Helpers\{Lang,Request,Csrf,Popups};
use Models\{TrailersModel};

$trailersModel = new TrailersModel();

$get_search = (!empty(Request::get('search'))) ? Request::get('search') : "";
$updateUser = (!empty(Request::post('updateUser'))) ? Request::post('updateUser') : "";

// Get url
$urlPage1 = (!empty($urlParams[1])) ? strtolower($urlParams[1]) : "";
$urlPage2 = (!empty($urlParams[2])) ? strtolower($urlParams[2]) : "";

$allZones = $trailersModel->getZones();
$allFleets = $trailersModel->getFleets();

// Check to see if there is any sorting for users list
if(!empty($urlPage1) && $urlPage1 == "role" && !empty($urlPage2)){
  // Get list of members that match the role id provided
  $members = $authModel->getMembersByRole($urlPage2);
}else if($get_search){
  // Check if searching for members
  $members = $authModel->getMembersBySearch($get_search);
}else if($updateUser == "true"){
  // Check to see if updating member profile
  $userRoles = (!empty(Request::post('userRoles'))) ? Request::post('userRoles') : "";
  $userId = (!empty(Request::post('userId'))) ? Request::post('userId') : "";
  $userZones = (!empty(Request::post('userZones'))) ? Request::post('userZones') : "";
  $userFleets = (!empty(Request::post('userFleets'))) ? Request::post('userFleets') : "";
  // Update user's roles
  $userUpdated = false;
  // Check to see if Roles are being altered
  if(!empty($userRoles) && $authModel->updateUserRoles($userRoles, $userId)){
    $userUpdated = true;
  }
  // Check to see if Zones are being altered
  if($trailersModel->updateZoneAssignments($allZones,$userZones,$userId)){
    $userUpdated = true;
  }
  // Check to see if Fleets are being altered
  if($trailersModel->updateFleetAssignments($allFleets,$userFleets,$userId)){
    $userUpdated = true;
  }

  if($userUpdated){
    /* Success Message Display */
    Popups::pushSuccess(Lang::get($userInformation->userLocale,'ADMIN_UPDATE_MEMBER_SUCCESS'), 'Admin/Members/Edit/'.$userId);
    exit();
  }else{
    /* Error Message Display */
    Popups::pushError(Lang::get($userInformation->userLocale,'ADMIN_UPDATE_MEMBER_ERROR'), 'Admin/Members/Edit/'.$userId);
    exit();
  }

}else{
  // Get list of all site members
  $members = $authModel->getMembers('fullname');
}

// Check if user is editing a member
if(!empty($urlPage1) && $urlPage1 == "edit" && !empty($urlPage2)){

  // Get Member Data
  $member = $authModel->getMember($urlPage2);

?>

<form method='post'>

<div class="row">
    <div class="col-lg-6 col-md-6">
        <div class="card uc-card-in mb-3" style="">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <strong><?=Lang::get($userInformation->userLocale,'ACCOUNT_USERNAME')?></strong>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?=$member->userName?>
                        <?php 
                            // Check to see if user is verified
                            if($member->userVerified === 1){
                                echo "<span><i class='fa-solid fa-circle-check uc-verified'></i></span>";
                            }
                        ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <strong><?=Lang::get($userInformation->userLocale,'ACCOUNT_EMAIL')?></strong>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?=$member->userEmail?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <strong><?=Lang::get($userInformation->userLocale,'ACCOUNT_LOCALE')?></strong>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?=$member->userLocale?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <strong><?=Lang::get($userInformation->userLocale,'ACCOUNT_ROLES')?></strong>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php 
                            $userRoles = $authModel->userRoles($member->userId);
                            if(!empty($userRoles)){
                                $i = 0;
                                foreach($userRoles as $role){
                                    if($i > 0) echo ", ";
                                    echo "<font color='{$role->roleColor}'><strong>{$role->roleName}</strong></font>";
                                    $i++;
                                }
                            }
                        ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <strong>Assigned Zones</strong>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php 
                          $userZones = $trailersModel->userZones($member->userId);
                          if(!empty($userZones)){
                            $i = 0;
                            foreach($userZones as $zone){
                                if($i > 0) echo ", ";
                                echo $zone->zone;
                                $i++;
                            }
                          }
                        ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <strong><?=Lang::get($userInformation->userLocale,'ACCOUNT_SIGN_UP_DATE')?></strong>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?=$member->signupTimestamp?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-3">
        <div class="card uc-card-in mb-3">
            <div class="card-body text-center">
                <strong><?=$member->userName?></strong>
                <?php 
                    // Check to see if user is verified
                    if($member->userVerified === 1){
                        echo "<span><i class='fa-solid fa-circle-check uc-verified'></i></span>";
                    }
                ?>
            </div>
        </div>

        <div class="card uc-card-in mb-3">
            <div class="card-header">
              <?=Lang::get($userInformation->userLocale,'ADMIN_ROLES')?>
            </div>
              <?php
                // Display all roles for website so that we can check them to be added or removed
                $allRoles = $authModel->getRoles();
                if(!empty($allRoles)){
                  echo "<table class='table table-striped'><tbody>";
                    foreach($allRoles AS $role){
                      echo "<tr class='w-100'>";
                        echo "<td class='text-start w-100'>";
                          echo "<font color='{$role->roleColor}'><strong>{$role->roleName}</strong></font>";
                        echo "</td>";
                        echo "<td class='text-end'>";
                          // Check if user has this role already or not.
                          if($authModel->userRoleCheck($role->id, $member->userId)){
                            echo "<input class='form-check-input' type='checkbox' name='userRoles[{$role->id}]' id='role{$role->id}' CHECKED>";
                          }else{
                            echo "<input class='form-check-input' type='checkbox' name='userRoles[{$role->id}]' id='role{$role->id}'>";
                          }
                        echo "</td>";
                      echo "</tr>";
                    }
                  echo "</tbody></table>";
                }
              ?>
        </div>

        <div class="card uc-card-in mb-3">
            <div class="card-header">
              Fleets
            </div>
              <?php
                // Display all roles for website so that we can check them to be added or removed LRS VANJAC VANTUP
                if(!empty($allFleets)){
                  echo "<table class='table table-striped'><tbody>";
                    foreach($allFleets AS $fleet){
                      echo "<tr class='w-100'>";
                        echo "<td class='text-start w-100'>";
                          echo "{$fleet->fleetCode} - {$fleet->fleetName}";
                        echo "</td>";
                        echo "<td class='text-end'>";
                          // Check if user has this role already or not.
                          if($trailersModel->fleetAssignCheck($fleet->id, $member->userId)){
                            echo "<input class='form-check-input' type='checkbox' name='userFleets[{$fleet->id}]' id='fleet{$fleet->id}' CHECKED>";
                          }else{
                            echo "<input class='form-check-input' type='checkbox' name='userFleets[{$fleet->id}]' id='fleet{$fleet->id}'>";
                          }
                        echo "</td>";
                      echo "</tr>";
                    }
                  echo "</tbody></table>";
                }
              ?>
        </div>

      </div>
      <div class="col-lg-3 col-md-3">
        <div class="card uc-card-in mb-3">
            <div class="card-header">
              Zones
            </div>
              <?php
                // Display all roles for website so that we can check them to be added or removed
                if(!empty($allZones)){
                  echo "<table class='table table-striped'><tbody>";
                    foreach($allZones AS $zone){
                      echo "<tr class='w-100'>";
                        echo "<td class='text-start w-100'>";
                          echo "{$zone->zone} - {$zone->state}";
                        echo "</td>";
                        echo "<td class='text-end'>";
                          // Check if user has this role already or not.
                          if($trailersModel->userZoneCheck($zone->id, $member->userId)){
                            echo "<input class='form-check-input' type='checkbox' name='userZones[{$zone->id}]' id='zone{$zone->id}' CHECKED>";
                          }else{
                            echo "<input class='form-check-input' type='checkbox' name='userZones[{$zone->id}]' id='zone{$zone->id}'>";
                          }
                        echo "</td>";
                      echo "</tr>";
                    }
                  echo "</tbody></table>";
                }
              ?>
        </div>
    </div>

</div>

<input type='hidden' id='csrfToken' name='csrfToken' value='<?=Csrf::makeToken()?>'>
<input type='hidden' id='userId' name='userId' value='<?=$member->userId?>'>
<input type='hidden' id='updateUser' name='updateUser' value='true'>
<button class='btn btn-primary' type='submit'><?=Lang::get($userInformation->userLocale,'ADMIN_UPDATE_MEMBER')?></button>

<form>

<?php
}else{
?>

<div class="row">
    <div class="col mb-3">
        <div class="card uc-card-in">
            <div class="card-body">
                  <form action="<?=SITE_URL?>Admin/Members/Search">
                    <div class="input-group mb-3">
                      <input type="text" name="search" class="form-control" placeholder="Search Members" aria-label="Search Members" aria-describedby="button-addon2">
                      <button class="btn btn-outline-secondary" type="submit" id="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                  </form>
                  <table class="table table-striped">
                    <tr>
                        <th scope="col" class="text-start"><?=Lang::get($userInformation->userLocale,'ADMIN_MEMBER')?></th>
                        <th scope="col"><?=Lang::get($userInformation->userLocale,'ADMIN_ROLES')?></th>
                        <th scope="col">Division</th>
                        <th scope="col">Assigned Zones</th>
                        <th scope="col">&nbsp;</th>
                    </tr>
                    <?php
                        if(!empty($members)){
                            foreach($members AS $member){
                                echo "<tr>";
                                    echo "<td class='text-start'>";
                                      echo "<strong>{$member->userFirstName} {$member->userLastName}</strong><br>";
                                      echo "{$member->userName}";
                                      // Check to see if user is verified
                                      if($member->userVerified === 1){
                                        echo " <span><i class='fa-solid fa-circle-check uc-verified'></i></span>";
                                      }
                                    echo "</td>";
                                    echo "<td>";
                                      $userRoles = $authModel->userRoles($member->userId);
                                      if(!empty($userRoles)){
                                        $i = 0;
                                        foreach($userRoles as $role){
                                            if($i > 0) echo ", ";
                                            echo "<font color='{$role->roleColor}'><strong>{$role->roleName}</strong></font>";
                                            $i++;
                                        }
                                      }
                                    echo "</td>";
                                    echo "<td>";
                                      $getUserFleets = $trailersModel->getUserFleets($member->userId);
                                      if(!empty($getUserFleets)){
                                        $i = 0;
                                        foreach($getUserFleets as $fleet){
                                            if($i > 0) echo ", ";
                                            echo $fleet->fleetCode;
                                            $i++;
                                        }
                                      }
                                    echo "</td>";
                                    echo "<td>";
                                      $userZones = $trailersModel->userZones($member->userId);
                                      if(!empty($userZones)){
                                        $i = 0;
                                        foreach($userZones as $zone){
                                            if($i > 0) echo ", ";
                                            echo $zone->zone;
                                            $i++;
                                        }
                                      }
                                    echo "</td>";
                                    echo "<td class='text-end'>";
                                        echo "<a href='".SITE_URL."MyTrailers/{$member->userId}' class='btn btn-primary'><i class='fa-solid fa-trailer'></i></a> ";
                                        echo "<a href='".SITE_URL."Admin/Members/Edit/{$member->userId}#edit' class='btn btn-success'><i class='fa-solid fa-pen-to-square'></i></a> ";
                                    echo "</td>";
                                echo "</tr>";
                            }
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
}
?>