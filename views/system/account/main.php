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

?>

  <div class="row">
    <div class="col-lg-3 col-md-4">
        <div class="card uc-card-in">
            <div class="card-body text-center">
                <strong><?=$userName?></strong>
                <?php 
                    // Check to see if user is verified
                    if($userInformation->userVerified === 1){
                        echo "<span><i class='fa-solid fa-circle-check uc-verified'></i></span>";
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-8">
        <div class="card uc-card-in" style="">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <strong><?=Lang::get($userInformation->userLocale,'ACCOUNT_USERNAME')?></strong>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?=$userName?>
                        <?php 
                            // Check to see if user is verified
                            if($userInformation->userVerified === 1){
                                echo "<span><i class='fa-solid fa-circle-check uc-verified'></i></span>";
                            }
                        ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <strong><?=Lang::get($userInformation->userLocale,'ACCOUNT_LOCALE')?></strong>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?=$userLocale?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <strong><?=Lang::get($userInformation->userLocale,'ACCOUNT_ROLES')?></strong>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php 
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
                        <strong><?=Lang::get($userInformation->userLocale,'ACCOUNT_SIGN_UP_DATE')?></strong>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?=$userInformation->signupTimestamp?>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
