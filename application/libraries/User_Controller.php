<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Controller extends System_Controller {
/*
 Constants are declared in constants.php
*/

	function __construct() {
        parent::__construct();
        if($this->usertypeID != USERS){
            redirect('/');
        }
        $this->load->model('appointment_m');

		$language = $this->session->userdata('lang');
        $this->lang->load('dashboard', $language);
    }
}