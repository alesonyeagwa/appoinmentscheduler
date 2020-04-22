<?php




if(! function_exists("check")) {

    /**
     * Check if current user is logged in.
     *
     * @return bool
     */
    function check()
    {
        $auth = new System_Controller();
        return $auth->loginStatus();
    }
}

if(! function_exists("can")) {

    /**
     * Check if current user has a permission by its name.
     *
     * @param $permissions
     * @return bool
     */
    function can($permissions, $requireAll = false)
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if (checkPermission($permission) && !$requireAll)
                    return true;
                elseif (!checkPermission($permission) && $requireAll) {
                    return false;
                }
            }
        }
        else {
            return checkPermission($permissions);
        }
        // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
        // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
        // Return the value of $requireAll;
        return $requireAll;
    }
}
if(!function_exists("checkPermission")){
    function checkPermission($permission)
    {
        //die($permission);
        //die(var_dump($this->userPermissions()));
        return in_array($permission, userPermissions());
    }
}
if(!function_exists("userWiseRoles")){
    function userWiseRoles()
    {
        $CI = & get_instance();
        return $CI->session->userdata('roles');
    }
}

if(!function_exists("userPermissions")){
    function userPermissions(){
        $CI = & get_instance();
        return array_map(function ($item) {
            return $item["name"];
        }, $CI->db
        ->select("permissions.*")
        ->from("permissions")
        ->join("permission_roles", "permissions.id = permission_roles.permission_id", "inner")
        ->where_in("permission_roles.role_id", userWiseRoles())
        ->where(array("permissions.status" => 1, "deleted_at" => null))
        ->group_by("permission_roles.permission_id")
        ->get()->result_array());
    }
}


if(! function_exists("hasRole")) {

    /**
     * Checks if the current user has a role by its name.
     *
     * @param $roles
     * @return bool
     */
    function hasRole($roles)
    {
        $auth = new System_Controller();
        return $auth->hasRole($roles);
    }
}