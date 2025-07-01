<?php
/**
 * Auth Database Models
 *
 * UserCandy PHP Framework
 * @author David (DaVaR) Sargent <davar@usercandy.com>
 * @version UC 2.0.0.0
 */

namespace Models;

use Core\Models;

class AuthModel extends Models
{

    // Discord user login
    // Check to see if user has logged in before, update their info.
    // If new user add them to the database
    public function userLogin($userData=null){
        // Check to see if user is already in the database as a member.
        $userCheck = $this->db->selectCount(PREFIX."users", array('userDiscordId'=>$userData['id']));
        if($userCheck > 0){
            // Update the user data
            $this->db->update(PREFIX."users", 
                array('userName'=>$userData['username'], 'userAvatar'=>$userData['avatar'], 'userEmail'=>$userData['email'], 'userDiscriminator'=>$userData['discriminator'], 'userLocale'=>$userData['locale']), 
                array('userDiscordId'=>$userData['id'])
            );
        }else{
            // Add new user to database
            $userId = $this->db->insert(PREFIX."users",
                array('userDiscordId'=>$userData['id'], 'userName'=>$userData['username'], 'userAvatar'=>$userData['avatar'], 'userEmail'=>$userData['email'], 'userDiscriminator'=>$userData['discriminator'], 'userLocale'=>$userData['locale']), 
            );
            // Add user as a member to the database
            $roleId = DEFAULT_ROLE_ID;
            SELF::addRoleToUser($roleId, $userId);
        }
    }

    // Add role to user
    public function addRoleToUser($roleId, $userId){
        return $this->db->insert(PREFIX."usersRoles", array('roleId'=>$roleId, 'userId'=>$userId));
    }

    // Remove role to user
    public function removeRoleFromUser($roleId, $userId){
        return $this->db->delete(PREFIX."usersRoles", array('roleId'=>$roleId, 'userId'=>$userId));
    }

    // Get user data
    public function userInformation($userDiscordId=null){
        // Return the user's data
        $data = $this->db->select("SELECT * FROM ".PREFIX."users WHERE userId = :userDiscordId LIMIT 1", array('userDiscordId'=>$userDiscordId));
        return $data[0];
    }

    // Get user's site roles
    public function userRoles($userId=null){
        // Get all role ids that user currently has
        $userRoles = $this->db->select("SELECT roleId FROM ".PREFIX."usersRoles WHERE userId = :userId", array('userId'=>$userId));
        // Loop through this user's roles and build an array of them.
        $rolesArray = array();
        if(!empty($userRoles)){
            foreach($userRoles AS $role){
                $roleName = $this->db->select("SELECT roleName, roleColor FROM ".PREFIX."roles WHERE id = :roleId", array('roleId'=>$role->roleId));
                array_push($rolesArray, $roleName[0]);
            }
        }
        if(!empty($rolesArray)){
            return $rolesArray;
        }else{
            return false;
        }
    }

    // Check to see if user has admin role in memberRoles table
    public function isAdmin($userDiscordId=null){
        // Get user's id from their discord id
        $userData = $this->db->select("SELECT userId FROM ".PREFIX."users WHERE userId = :userDiscordId", array('userDiscordId'=>$userDiscordId));
        if(!empty($userData) && !empty($userData[0]->userId)){
            // Get count of rows for user id and role id
            $adminCheck = $this->db->selectCount(PREFIX."usersRoles", array('userId'=>$userData[0]->userId, 'roleId'=>ADMIN_ROLE_ID));
            if(!empty($adminCheck) && $adminCheck > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    // Get all roles from database
    public function getRoles(){
        return $this->db->select("SELECT * FROM ".PREFIX."roles");
    }

    // Get role by id
    public function getRole($id){
        $data = $this->db->select("SELECT * FROM ".PREFIX."roles WHERE id = :id", array('id'=>$id));
        return $data[0];
    }

    // Update role by id
    public function updateRole($roleId, $roleName, $roleDescription, $roleColor){
        return $this->db->update(PREFIX."roles", array('roleName'=>$roleName, 'roleDescription'=>$roleDescription, 'roleColor'=>$roleColor), array('id'=>$roleId));
    }

    // Create new role
    public function createRole($roleName, $roleDescription, $roleColor){
        return $this->db->insert(PREFIX."roles", array('roleName'=>$roleName, 'roleDescription'=>$roleDescription, 'roleColor'=>$roleColor));
    }

    // Delete Role
    public function deleteRole($roleId){
        return $this->db->delete(PREFIX."roles", array('id'=>$roleId));
    }

    // Get total count of members in role group
    public function getRoleMembersCount($roleId){
        return $this->db->selectCount(PREFIX."usersRoles", array('roleId'=>$roleId));
    }

    // Get all site members
    public function getMembers($orderby=null){
        if($orderby == 'fullname'){
            $sort = 'ORDER BY userLastName ASC, userFirstName ASC';
        }else{
            $sort = '';
        }
        return $this->db->select("SELECT * FROM ".PREFIX."users $sort");
    }

    // Get all site members
    public function getMembersAssign($orderby=null){
        if($orderby == 'fullname'){
            $sort = 'ORDER BY userLastName ASC, userFirstName ASC';
        }else{
            $sort = '';
        }
        return $this->db->select("SELECT * FROM ".PREFIX."users WHERE assignTrailers='1' $sort");
    }

    // Get all site members
    public function getMember($id){
        $data = $this->db->select("SELECT * FROM ".PREFIX."users WHERE userId = :id LIMIT 1", array('id'=>$id));
        return $data[0];
    }

    // get total count of members
    public function totalMembers(){
        return $this->db->selectCount(PREFIX."users");
    }

    // get total count of roles
    public function totalRoles(){
        return $this->db->selectCount(PREFIX."roles");
    }

    // Get all site members by role
    public function getMembersByRole($roleId){
        return $this->db->select("SELECT us.* FROM ".PREFIX."users us LEFT JOIN ".PREFIX."usersRoles ur ON us.id = ur.userId WHERE ur.roleId = :roleId  GROUP BY us.id ORDER BY us.userName, us.userDiscriminator", array('roleId'=>$roleId));
    }

    // Get all site members by search
    public function getMembersBySearch($search){
        return $this->db->select("SELECT * FROM ".PREFIX."users WHERE userName LIKE :search GROUP BY userId ORDER BY userName", array('search'=>"%".$search."%"));
    }

    // Check if member has role
    public function userRoleCheck($roleId, $userId){
        $roleCheck = $this->db->selectCount(PREFIX."usersRoles", array('roleId'=>$roleId, 'userId'=>$userId));
        if($roleCheck > 0){
            return true;
        }else{
            return false;
        }
    }

    // Update member roles
    public function updateUserRoles($userRoles, $userId){
        // Get all roles from site
        $allRoles = SELF::getRoles();
        $userRoles = array_keys($userRoles);
        $updatedRoles = false;
        if(!empty($allRoles)){
            // Loop through all roles and check is user has it or not and compare to checked roles
            foreach($allRoles AS $role){
                // Check to see if role is checked
                if(in_array($role->id, $userRoles)){
                    //var_dump($role->id);
                    // Check if role is already set
                    if(!SELF::userRoleCheck($role->id, $userId)){
                        // Role not set, add it
                        if(SELF::addRoleToUser($role->id, $userId)){
                            $updatedRoles = true;
                        }
                    }
                }else{
                    // Role not selected, check if user had the role, if so remove it.
                    if(SELF::removeRoleFromUser($role->id, $userId)){
                        $updatedRoles = true;
                    }
                }
            }
        }
        if($updatedRoles){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Gets user account info by username
     * @param $username
     * @return array dataset
     */
    public function getAccountInfo($username)
    {
        return $this->db->select("SELECT * FROM ".PREFIX."users WHERE userName=:username", array(":username" => $username));
    }

    /**
     * Gets user account info by email
     * @param $email
     * @return array dataset
     */
    public function getAccountInfoEmail($email)
    {
        return $this->db->select("SELECT * FROM ".PREFIX."users WHERE userEmail=:email", array(":email" => $email));
    }

    /**
     * Delete user by username
     * @param $username
     * @return int : rows deleted
     */
    public function deleteUser($username)
    {
        return $this->db->delete(PREFIX."users", array("userName" => $username));
    }

    /**
     * Gets session info by the hash
     * @param $hash
     * @return array dataset
     */
    public function sessionInfo($hash)
    {
        return $this->db->select("SELECT uid, userName, expiredate, ip FROM ".PREFIX."sessions WHERE hash=:hash", array(':hash' => $hash));
    }

    /**
     * Delete session by username
     * @param $username
     * @return int : rows deleted
     */
    public function deleteSession($username)
    {
        return $this->db->delete(PREFIX."sessions", array('userName' => $username));
    }

    /**
     * Gets all attempts to login all accounts
     * @return array dataset
     */
    public function getAttempts()
    {
        return $this->db->select("SELECT ip, expiredate FROM ".PREFIX."attempts");
    }

    /**
     * Gets login attempt by ip address
     * @param $ip
     * @return array dataset
     */
    public function getAttempt($ip)
    {
        return $this->db->select("SELECT count FROM ".PREFIX."attempts WHERE ip=:ip", array(":ip" => $ip));
    }

    /**
     * Delete attempts of logging in
     * @param $where
     * @return int : deleted rows
     */
    public function deleteAttempt($where)
    {
        return $this->db->delete(PREFIX."attempts", $where);
    }

    /**
     * Add into DB
     * @param $table
     * @param $info
     * @return int : row id
     */
    public function addIntoDB($table,$info)
    {
        return $this->db->insert(PREFIX.$table,$info);
    }

    /**
     * Update in DB
     * @param $table
     * @param $info
     * @param $where
     * @return int
     */
    public function updateInDB($table,$info,$where)
    {
        return $this->db->update(PREFIX.$table,$info,$where);
    }

    /**
     * Get the user id by username
     * @param $username
     * @return array dataset
     */
    public function getUserID($username)
    {
        return $this->db->select("SELECT userId FROM ".PREFIX."users WHERE userName=:username", array(":username" => $username));
    }

    /**
     * Check is user is a New Member (groupID = 1)
     * @param $userId
     * @return array dataset
     */
    public function getUserGroups($userId)
    {
        return $this->db->select("SELECT roleId FROM ".PREFIX."usersRoles WHERE userId = :userId",array(':userId' => $userId));
    }

    /**
     * Get device status
     * @return int data
     */
    public function getDeviceStatus($userId,$os,$device,$browser,$city,$state,$country,$useragent)
    {
      return $this->db->select("SELECT * FROM ".PREFIX."users_devices WHERE userId = :userId AND os = :os AND device = :device AND browser = :browser AND city = :city AND state = :state AND country = :country AND useragent = :useragent",
                          array('userId'=>$userId,'os'=>$os,'device'=>$device,'browser'=>$browser,'city'=>$city,'state'=>$state,'country'=>$country,'useragent'=>$useragent));
    }

    /**
     * Get device status
     * @return int data
     */
    public function getDeviceExists($userId,$os,$device,$browser,$city,$state,$country,$useragent)
    {
      $data = $this->db->selectCount(PREFIX."users_devices",array('userId'=>$userId,'os'=>$os,'device'=>$device,'browser'=>$browser,'city'=>$city,'state'=>$state,'country'=>$country,'useragent'=>$useragent));
      if($data > 0){
        return true;
      }else{
        return false;
      }
    }

    /**
     * Get the user dark mode by userId
     * @param $userId
     * @return string darkMode
     */
    public function getUserDarkMode($userId)
    {
        $data = $this->db->select("SELECT darkMode FROM ".PREFIX."users WHERE userId=:userId", array("userId" => $userId));
        if(!empty($data[0]->darkMode)){
          return true;
        }else{
          return false;
        }
    }

}
