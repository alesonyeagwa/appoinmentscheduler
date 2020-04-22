<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//require_once APPPATH . '/libraries/moment/Moment.php';
use \Moment\Moment;

class System_Controller extends MY_Controller
{
    /*
    |--------------------------------------------------------------------------
    | Auth Library
    |--------------------------------------------------------------------------
    |
    | This Library handles authenticating users for the application and
    | redirecting them to your home screen.
    |
    */

    public $user = null;
    public $userID = null;
    public $usertypeID = null;
    public $userName = null;
    public $name = null;
    public $password = null;
    public $roles = 0;  // [ public $roles = null ] codeIgniter where_in() omitted for null.
    public $permissions = null;
    public $loginStatus = false;
    public $error = array();
    public $email = null;
    public $phone = null;

    public function __construct()
    {
        parent::__construct();
        
        \Moment\Moment::setDefaultTimezone(date_default_timezone_get());

        $this->load->model("signin_m");
		$this->load->model("permission_m");
		$this->load->model("role_m");
		$this->load->model("site_m");
		$this->data["siteinfos"] = $this->site_m->get_site(1);

        $this->load->library("session");
		$this->load->helper('language');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library("email", null, "emailer");

		
		$usertype = $this->session->userdata('usertype');

		$language = $this->session->userdata('lang');

        $exception_uris = array(
			"signin/index",
            "signin/signout",
            "register/agent",
            "register/user",
            "verification/emailsent",
            "verification/pending",
            "verification/sendemail",
            "verification/verify",
            "resetpass"
        );
        if(strpos(uri_string(), 'verification/verify/') !== false){
        }elseif(strpos(uri_string(), 'resetpass') !== false){
        }elseif(in_array(uri_string(), $exception_uris) == FALSE) {
			if($this->signin_m->loggedin() == FALSE) {
				redirect(base_url("signin/index"));
			}
		}
        $this->init();
    }

    /**
     * Initialization the Auth class
     */
    protected function init()
    {
        if ($this->session->has_userdata("userID") && $this->session->userdata('loggedin')) {
            $this->userID = $this->session->userdata('userID');
            $this->usertypeID = $this->session->userdata('usertypeID');
            $this->userName = $this->session->userdata('username');
            $this->name = $this->session->userdata('name');
            $this->roles = $this->session->userdata('roles');
            $this->email = $this->session->userdata('email');
            $this->phone = $this->session->userdata('phone');
            $this->loginStatus = true;
            //die(var_dump($this->roles));
        }

        return;
    }

    /**
     * Show The Login Form
     *
     * @param array $data
     * @return mixed
     */
    public function showLoginForm($data = array())
    {
        return $this->load->view("auth/login", $data);
    }

    /**
     * Handle Login
     *
     * @param $request
     * @return array|bool|void
     */
    public function login($request)
    {
        if ($this->validate($request)) {
            $this->user = $this->credentials($this->userName, $this->password);
            if ($this->user) {
                return $this->setUser();
            } else {
                return $this->failedLogin($request);
            }
        }

        return false;
    }

    /**
     * Validate the login form
     *
     * @param $request
     * @return bool
     */
    protected function validate($request)
    {
        $this->form_validation->set_rules('username', 'User Name', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == TRUE) {
            /*$this->userName = $request["username"];
            $this->password = $request["password"];*/
            $this->userName = $this->input->post("username", TRUE);
            $this->password = $this->input->post("password", TRUE);
            return true;
        }

        return false;
    }

    /**
     * Check the credentials
     *
     * @param $username
     * @param $password
     * @return mixed
     */
    protected function credentials($username, $password)
    {
        $user = $this->db->get_where("users", array("username" => $username, "status" => 1, "deleted_at" => null))->row(0);
        if($user && password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }

    /**
     * Setting session for authenticated user
     */
    protected function setUser()
    {
        $this->userID = $this->user->id;

        $this->session->set_userdata(array(
            "userID" => $this->user->id,
            "username" => $this->user->username,
            "roles" => $this->userWiseRoles(),
            "loginStatus" => true
        ));

        return redirect("home");
    }

    /**
     * Get the error message for failed login
     *
     * @param $request
     * @return array
     */
    protected function failedLogin($request)
    {
        $this->error["failed"] = "Username or Password Incorrect.";

        return $this->error;
    }

    /**
     * Check login status
     *
     * @return bool
     */
    public function loginStatus()
    {
        return $this->loginStatus;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function authenticate()
    {
        if (!$this->loginStatus()) {
            return redirect('login');
        }

        return true;
    }

    /**
     * Determine if the current user is authenticated. Identical of authenticate()
     *
     * @return bool
     */
    public function check($methods = 0)
    {
        if (is_array($methods) && count(is_array($methods))) {
            foreach ($methods as $method) {
                if ($method == (is_null($this->uri->segment(2)) ? "index" : $this->uri->segment(2))) {
                    return $this->authenticate();
                }
            }
        }
        return $this->authenticate();
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return !$this->loginStatus();
    }

    /**
     * Read authenticated user ID
     *
     * @return int
     */
    public function userID()
    {
        return $this->userID;
    }

    /**
     * Read authenticated user Name
     *
     * @return string
     */
    public function userName()
    {
        return $this->userName;
    }

    /**
     * Read authenticated user roles
     *
     * @return array
     */
    public function roles()
    {
        return $this->roles;
    }

    /**
     * Read authenticated user permissions
     *
     * @return array
     */
    public function permissions()
    {
        return $this->permissions;
    }

    /**
     * Read the current user roles ID
     *
     * @param $userID
     * @return string
     */
    protected function userWiseRoles()
    {
        return array_map(function ($item) {
            return $item["role_id"];
        }, $this->db->get_where("roles_users", array("user_id" => $this->userID(), "usertypeID" => $this->usertypeID))->result_array());
    }

    /**
     * Read the current user roles name
     *
     * @return array
     */
    public function userRoles()
    {
        return array_map(function ($item) {
            return $item["name"];
        }, $this->db
            ->select("roles.*")
            ->from("roles")
            ->join("roles_users", "roles.id = roles_users.role_id", "inner")
            ->where(array("roles_users.user_id" => $this->userID(),"roles.status" => 1, "deleted_at" => null))
            ->get()->result_array());
    }

    /**
     * Read current user permissions name
     *
     * @return mixed
     */
    public function userPermissions()
    {
        return array_map(function ($item) {
            return $item["name"];
        }, $this->db
        ->select("permissions.*")
        ->from("permissions")
        ->join("permission_roles", "permissions.id = permission_roles.permission_id", "inner")
        ->where_in("permission_roles.role_id", $this->roles())
        ->where(array("permissions.status" => 1, "deleted_at" => null))
        ->group_by("permission_roles.permission_id")
        ->get()->result_array());
    }

    /**
     * Determine if the current user is authenticated for specific methods.
     *
     * @param array $methods
     * @return bool
     */
    public function only($methods = array())
    {
        if (is_array($methods) && count(is_array($methods))) {
            foreach ($methods as $method) {
                if ($method == (is_null($this->uri->segment(2)) ? "index" : $this->uri->segment(2))) {
                    return $this->route_access();
                }
            }
        }

        return true;
    }

    /**
     * Determine if the current user is authenticated except specific methods.
     *
     * @param array $methods
     * @return bool
     */
    public function except($methods = array())
    {
        if (is_array($methods) && count(is_array($methods))) {
            foreach ($methods as $method) {
                if ($method == (is_null($this->uri->segment(2)) ? "index" : $this->uri->segment(2))) {
                    return true;
                }
            }
        }

        return $this->route_access();
    }

    /**
     * Determine if the current user is authenticated to view the route/url
     *
     * @return bool|void
     */
    public function route_access()
    {
        $this->check();

        $routeName = (is_null($this->uri->segment(2)) ? "index" : $this->uri->segment(2)) . "-" . $this->uri->segment(1);
        // /die($routeName);
        if ($this->uri->segment(1) == 'home')
            return true;

        if($this->can($routeName))
            return true;

        return redirect('exceptions/custom_404', 'refresh');
    }

    /**
     * Checks if the current user has a role by its name.
     *
     * @param $roles
     * @param bool $requireAll
     * @return bool
     */
    public function hasRole($roles, $requireAll = false)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->checkRole($role) && !$requireAll)
                    return true;
                elseif (!$this->checkRole($role) && $requireAll) {
                    return false;
                }
            }
        }
        else {
            return $this->checkRole($roles);
        }
        // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
        // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
        // Return the value of $requireAll;
        return $requireAll;
    }

    /**
     * Check current user has specific role
     *
     * @param $role
     * @return bool
     */
    public function checkRole($role)
    {
        return in_array($role, $this->userRoles());
    }

    /**
     * Check if current user has a permission by its name.
     *
     * @param $permissions
     * @param bool $requireAll
     * @return bool
     */
    public function can($permissions, $requireAll = false)
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if ($this->checkPermission($permission) && !$requireAll)
                    return true;
                elseif (!$this->checkPermission($permission) && $requireAll) {
                    return false;
                }
            }
        }
        else {
            return $this->checkPermission($permissions);
        }
        // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
        // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
        // Return the value of $requireAll;
        return $requireAll;
    }

    /**
     * Check current user has specific permission
     *
     * @param $permission
     * @return bool
     */
    public function checkPermission($permission)
    {
        //die($permission);
        //die(var_dump($this->userPermissions()));
        return in_array($permission, $this->userPermissions());
    }

    /**
     * Logout
     *
     * @return bool
     */
    public function logout()
    {
        $this->session->unset_userdata(array("userID", "username", "loginStatus"));
        $this->session->sess_destroy();

        return true;
    }

    public function dt_limiter(){
        $offset = $this->input->post("offset");
        $limit = $this->input->post("limit");
        if(empty($limit)){
            $limit = 10;
        }
        if(!empty($offset)){
            $this->db->limit($limit, $offset);
        }else{
            $this->db->limit($limit);
        }
    }

    public function crypter( $string, $action = 'e' ) {
        // you may change these values to your own
        $secret_key = 'my_simple_secret_key';
        $secret_iv = 'my_simple_secret_iv';
     
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
     
        if( $action == 'e' ) {
            $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        else if( $action == 'd' ){
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }
        return $output;
    }

    protected function exitSuccess($message = "Operation was successful", $extra = ""){
        die(json_encode(array('error' => false, 'response' => $message, 'extra' => $extra)));
    }
    protected function exitError($message = "Sorry an unexpected error occurred."){
        die(json_encode(array('error' => true, 'response' => $message)));
    }
    protected function giveMeRandNumber($count)
    {
        $a = "232";
        for ($i = 0; $i<$count; $i++) 
        {
            $a .= mt_rand(0,9);
        }
        return $a;
    }

    protected function changePassword($redirect = "dashboard/index"){
        $rules = array(
            array(
                'field' => 'cpass',
                'label' => "current password",
                'rules' => 'trim|required|xss_clean|min_length[8]|max_length[128]'
            ),
            array(
                'field' => 'npass',
                'label' => "new password",
                'rules' => 'trim|required|xss_clean|min_length[8]|max_length[128]'
            ),
            array(
                'field' => 'emailtoken',
                'label' => "token",
                'rules' => 'trim|xss_clean'
            ),
        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == FALSE){
            $this->session->set_flashdata("errors", validation_errors());
            redirect(base_url($redirect));
        }else{
            $table = "";
            if($this->usertypeID == USERS){
                $table = "user";
            }elseif($this->usertypeID == AGENT){
                $table = "agent";
            }elseif ($this->usertypeID == ADMIN) {
                $table = "systemadmin";
            }else{
                $this->session->set_flashdata("errors", "Sorry, an unexpected error occured.");
                redirect(base_url($redirect));
            }
            $colName = $table . "ID";
            $emailtoken = $this->input->post('emailtoken');
            $cpass = $this->input->post('cpass');
            $npass = $this->input->post('npass');
            $checkuser = $this->db->select('*')
                            ->from($table)
                            ->where(array($colName => $this->userID))->get()->row();
            if($checkuser){
                $pHash = $checkuser->password;
                if (password_verify($cpass, $pHash)) {
                    $options = array('cost' => 11);
                    $newHash = password_hash($npass, PASSWORD_DEFAULT, $options);
                    $checkToken = $this->db->select('*')
                                    ->from('emailtoken')
                                    ->where(array('userID' => $this->userID, 'purpose' => PURPOSE_PASSWORD_CHANGE, 'usertypeID' => $this->usertypeID))->get()->row();
                    if($checkToken){
                        $m = new \Moment\Moment($checkToken->generated_at);
                        if($m){
                            $minutesAgo = ($m->fromNow()->getMinutes());
                            if($emailtoken != $checkToken->token || $minutesAgo > 5){
                                $this->session->set_flashdata("errors", "Expired/Invalid token");
                                redirect(base_url($redirect));
                            }else{
                                //Proceed to change password
                                //Delete Token
                                $this->db->delete("emailtoken", array('tokenID' => $checkToken->tokenID));
                                //update account
                                $this->db->update($table, array("password" => $newHash), array($colName => $this->userID));
                                $this->session->set_flashdata("success", "Changed password successfully.");
                                redirect(base_url($redirect));
                            }
                        }
                    }
                }else{
                    $this->session->set_flashdata("errors", "Incorrect current password.");
                    redirect(base_url($redirect));
                }
            }
        }
        $this->session->set_flashdata("errors", "Sorry, an unexpected error occured.");
        redirect(base_url($redirect));
    }

    protected function sendVerificationEmail(){
        $gReturn = array("return" => false, "message" => "Sorry, an unexpected error occurred.");

        $email = $this->session->userdata('email');
        $usertypeID = $this->session->userdata('usertypeID');
        $userID = $this->session->userdata('userID');
        $isPendingVerification = $this->session->userdata('isPendingVerification');

        if(!empty($email) && !empty($usertypeID) && $isPendingVerification){
            $prevToken = $this->emailtoken_m->get_single_emailtoken(array('userID' => $userID, 'usertypeID' => $usertypeID, 'purpose' => PURPOSE_EMAIL_VERIFY));
            $generate_date = date('Y-m-d H:i:s');
            $tk = $this->giveMeRandNumber(10);
            $tokenDetail = array($userID, $usertypeID, $email, $generate_date, $tk);
            $tokenToSend = $this->crypter(implode('|', $tokenDetail));
            if($prevToken){
                $m = new \Moment\Moment($prevToken->generated_at);
                if($m){
                    $minutesAgo = ($m->fromNow()->getMinutes());
                    if($minutesAgo < 2){
                        return array("return" => false, "message" => "You must wait for 2 minutes before requesting for another email verification.");
                    }
                }
                $this->emailtoken_m->update_emailtoken(array("token" => $tokenToSend, "generated_at" => $generate_date), $prevToken->tokenID);
            }else{
                $this->emailtoken_m->insert_emailtoken(array("userID" => $this->userID, "usertypeID" => $this->usertypeID, "purpose" => PURPOSE_EMAIL_VERIFY, "token" => $tokenToSend, "generated_at" => $generate_date));
            }
            $this->emailer->from($this->config->item("system_email"), $this->config->item("system_name"));
            $this->emailer->to($this->session->userdata('email'));
            $this->emailer->subject('Email Verification');
            $message = "To verify your email with us, click this link:  " . base_url('verification/verify/'.$tokenToSend) . " Please ignore if you did not initiate this.";
            $this->emailer->message($message);
            if($this->emailer->send()) {
                return array("return" => true, "message" => "Verification email sent");
            } else {
                return array("return" => false, "message" => "Could not send verification email");
            }
        }else{
            return $gReturn;
        }
    }

}
