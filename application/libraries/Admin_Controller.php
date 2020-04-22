<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends System_Controller {
/*
 Constants are declared in constants.php
*/

    function __construct() {
        parent::__construct();
        if($this->usertypeID != ADMIN){
            redirect('/');
        }
        // $pid = $this->permission_m->add([
        //     'name' => 'dashboard',
        //     'display_name' => 'dashboard',
        //     'status' => 1,
        // ]);
        //$this->role_m->addPermissions(1, $pid);
        $language = $this->session->userdata('lang');
        $this->lang->load('dashboard', $language);
    }
}