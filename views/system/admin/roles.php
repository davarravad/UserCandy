<?php
/**
* Account Main Page
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

use Helpers\{Lang, Csrf, Request, Popups};

// Check to see if user is updating Role or creating Role
if (Csrf::isTokenValid()) {
    // Token Valid process the form
    if (Request::isPost()) {
        // Collect all data from post
        $post_roleName = (!empty(Request::post('roleName'))) ? Request::post('roleName') : "";
        $post_roleDescription = (!empty(Request::post('roleDescription'))) ? Request::post('roleDescription') : "";
        $post_roleColor = (!empty(Request::post('roleColor'))) ? Request::post('roleColor') : "";
        $post_roleNew = (!empty(Request::post('roleNew'))) ? Request::post('roleNew') : "false";
        $post_roleId = (!empty(Request::post('roleId'))) ? Request::post('roleId') : "";
        $post_roleEdit = (!empty(Request::post('roleEdit'))) ? Request::post('roleEdit') : "false";
        $post_roleDelete = (!empty(Request::post('roleDelete'))) ? Request::post('roleDelete') : "false";
        // Check to see if user is updating an existing role or creating a new one.
        if(!empty($post_roleId) && $post_roleEdit === "true"){
            // User is updating a role
            if($authModel->updateRole($post_roleId, $post_roleName, $post_roleDescription, $post_roleColor)){
                /* Success Message Display */
                Popups::pushSuccess(Lang::get($userInformation->userLocale,'ADMIN_UPDATE_ROLE_SUCCESS', array($post_roleName, $post_roleColor)), 'Admin/Roles');
                exit();
            }else{
                /* Error Message Display */
                Popups::pushError(Lang::get($userInformation->userLocale,'ADMIN_UPDATE_ROLE_ERROR', array($post_roleName, $post_roleColor)), 'Admin/Roles');
                exit();
            }
        }else if($post_roleNew === "true"){
            // User is creating a new role
            if($authModel->createRole($post_roleName, $post_roleDescription, $post_roleColor)){
                /* Success Message Display */
                Popups::pushSuccess(Lang::get($userInformation->userLocale,'ADMIN_CREATE_ROLE_SUCCESS', array($post_roleName, $post_roleColor)), 'Admin/Roles');
                exit();
            }else{
                /* Error Message Display */
                Popups::pushError(Lang::get($userInformation->userLocale,'ADMIN_CREATE_ROLE_ERROR', array($post_roleName, $post_roleColor)), 'Admin/Roles');
                exit();
            }
        }else if($post_roleDelete === "true" && $post_roleId != "1" && $post_roleId != "2"){
            // User is creating a new role
            if($authModel->deleteRole($post_roleId)){
                /* Success Message Display */
                Popups::pushSuccess(Lang::get($userInformation->userLocale,'ADMIN_DELETE_ROLE_SUCCESS', array($post_roleName, $post_roleColor)), 'Admin/Roles');
                exit();
            }else{
                /* Error Message Display */
                Popups::pushError(Lang::get($userInformation->userLocale,'ADMIN_DELETE_ROLE_ERROR', array($post_roleName, $post_roleColor)), 'Admin/Roles');
                exit();
            }
        }else{
            /* Error Message Display */
            Popups::pushError(Lang::get($userInformation->userLocale,'ADMIN_ROLE_ERROR', array($post_roleName, $post_roleColor)), 'Admin/Roles');
            exit();
        }

    }
}

// Get all roles in this server
$roles = $authModel->getRoles();

?>

<div class="row">
    <div class="col-lg-9 mb-3">
        <div class="card uc-card-in">
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th scope="col" class="text-start"><?=Lang::get($userInformation->userLocale,'ADMIN_ROLE_NAME')?></th>
                        <th scope="col">Members</th>
                        <th scope="col">&nbsp;</th>
                    </tr>
                    <?php
                        if(!empty($roles)){
                            foreach($roles AS $role){
                                echo "<tr>";
                                    echo "<td class='text-start'>";
                                    echo "<font color='{$role->roleColor}'><strong>{$role->roleName}</strong></font>";
                                    echo "<br><p class='text-muted'>{$role->roleDescription}</p>";
                                    if($role->id == "1"){
                                        echo "<div class='text-muted text-end'>".Lang::get($userInformation->userLocale,'ADMIN_ROLE_DEFAULT_ADMIN')."</span>";
                                    }else if($role->id == "2"){
                                        echo "<div class='text-muted text-end'>".Lang::get($userInformation->userLocale,'ADMIN_ROLE_DEFAULT_MEMBER')."</span>";
                                    }
                                    echo "</td>";
                                    echo "<td>";
                                    echo "<a href='".SITE_URL."Admin/Members/Role/$role->id' class='link-dark'>".$authModel->getRoleMembersCount($role->id)." <i class='fa-solid fa-user'></i></a>";
                                    echo "</td>";
                                    echo "<td class='text-end'>";
                                        echo "<a href='".SITE_URL."Admin/Roles/Edit/{$role->id}#edit' class='btn btn-success'><i class='fa-solid fa-pen-to-square'></i></a> ";
                                        // Check to see if role one or two.  Don't allow delete
                                        if($role->id != 1 && $role->id != 2){
                                            echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteRoleModal{$role->id}'><i class='fa-solid fa-trash'></i></button> ";
                                        }
                                    echo "</td>";
                                echo "</tr>";
                                
                                // Modal data for this role to confirm delete
                                echo "
                                    <div class='modal fade' id='deleteRoleModal{$role->id}' tabindex='-1' aria-labelledby='deleteModalModalLabel{$role->id}' aria-hidden='true'>
                                        <div class='modal-dialog'>
                                            <div class='modal-content'>
                                                <div class='modal-header bg-danger'>
                                                    <h1 class='modal-title fs-5' id='deleteModalModalLabel{$role->id}'>".Lang::get($userInformation->userLocale,'ADMIN_DELETE_ROLE')."</h1>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                </div>
                                                <div class='modal-body'>
                                                    ".Lang::get($userInformation->userLocale,'ADMIN_DELETE_ROLE_QUESTION', array($role->roleName, $role->roleColor))."
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>".Lang::get($userInformation->userLocale,'BUTTON_CANCEL')."</button>
                                                    <form method='post'>
                                                        <input type='hidden' id='csrfToken' name='csrfToken' value='".Csrf::makeToken()."'>
                                                        <input type='hidden' id='roleName' name='roleName' value='{$role->roleName}'>
                                                        <input type='hidden' id='roleColor' name='roleColor' value='{$role->roleColor}'>
                                                        <input type='hidden' id='roleId' name='roleId' value='{$role->id}'>
                                                        <input type='hidden' id='roleEdit' name='roleDelete' value='true'>
                                                        <button type='submit' class='btn btn-danger'>".Lang::get($userInformation->userLocale,'BUTTON_DELETE')."</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ";
                            }
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card uc-card-in">
            <div class="card-header h4" id="edit">
                <?php
                    // Check to see if user is editing an existing role or creating a new one.
                    if(!empty($urlParams[1]) && $urlParams[1] == "edit" && !empty($urlParams[2])){
                        echo Lang::get($userInformation->userLocale,'ADMIN_EDIT_ROLE');
                        // Load role
                        $getRole = $authModel->getRole($urlParams[2]);
                    }else{
                        echo Lang::get($userInformation->userLocale,'ADMIN_CREATE_ROLE');
                    }
                    // Put the role data together if any exist
                    $roleName = (!empty($getRole) && !empty($getRole->roleName)) ? $getRole->roleName : "";
                    $roleDescription = (!empty($getRole) && !empty($getRole->roleDescription)) ? $getRole->roleDescription : "";
                    $roleColor = (!empty($getRole) && !empty($getRole->roleColor)) ? $getRole->roleColor : "";
                ?>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3 text-start">
                        <label for="roleName" class="form-label"><?=Lang::get($userInformation->userLocale,'ADMIN_ROLE_NAME')?></label>
                        <input type="text" class="form-control" id="roleName" name="roleName" value="<?=$roleName?>" placeholder="<?=Lang::get($userInformation->userLocale,'ADMIN_ROLE_NAME')?>">
                    </div>
                    <div class="mb-3 text-start">
                        <label for="roleDescription" class="form-label"><?=Lang::get($userInformation->userLocale,'ADMIN_ROLE_DESCRIPTION')?></label>
                        <textarea class="form-control" id="roleDescription" name="roleDescription" rows="3"><?=$roleDescription?></textarea>
                    </div>
                    <div class="mb-3 text-start">
                        <label for="roleColor" class="form-label"><?=Lang::get($userInformation->userLocale,'ADMIN_ROLE_COLOR')?></label>
                        <input type="color" class="form-control" id="roleColor" name="roleColor" value="<?=$roleColor?>" placeholder="#000000">
                    </div>
                    <div class="mb-3 text-start">
                        <?php 
                            // Setup submit button to be create role or update role
                            if(!empty($getRole) && !empty($getRole->id)){
                                // Editing role
                                echo "<input type='hidden' id='csrfToken' name='csrfToken' value='".Csrf::makeToken()."'>";
                                echo "<input type='hidden' id='roleId' name='roleId' value='{$getRole->id}'>";
                                echo "<input type='hidden' id='roleEdit' name='roleEdit' value='true'>";
                                echo "<button type='submit' class='btn btn-primary'>".Lang::get($userInformation->userLocale,'ADMIN_ROLE_UPDATE')."</button>";
                            }else{
                                // New Role
                                echo "<input type='hidden' id='csrfToken' name='csrfToken' value='".Csrf::makeToken()."'>";
                                echo "<input type='hidden' id='roleNew' name='roleNew' value='true'>";
                                echo "<button type='submit' class='btn btn-primary'>".Lang::get($userInformation->userLocale,'ADMIN_ROLE_CREATE')."</button>";
                            }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


