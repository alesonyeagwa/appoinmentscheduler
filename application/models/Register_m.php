<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register_m extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->load->model("setting_m");
		$this->load->model('usertype_m');
		$this->load->model('loginlog_m');
    }

    public function register($userTypeID){
        $gReturn = array("return" => false, "message" => "Sorry, an unexpected error occurred.");
        $table = "";
        $nameField = "name";
        $roleID = 0;
        if($userTypeID == USERS){
            $table = 'user';
        }else if($userTypeID == AGENT){
            $table = 'agent';
            $nameField = "agentName";
        }else{
            return $gReturn;
        }
        $colName = $table . "ID";

        $email = $this->input->post('email');
        $pass = $this->input->post("pass");
        $rpass = $this->input->post("rpass");
        $name = $this->input->post("name");
        $address = $this->input->post("address");
        $phone = $this->input->post("phone");
        $secq =  $this->input->post("secq");
        $seca =  $this->input->post("seca");
        $payment = $this->input->post("payment");

        $checkSecQuestion = $this->db->select('*')
                                ->from("securityquestion")
                                ->where(array('questionID' => $secq))->get()->row();
        if(empty($checkSecQuestion)){
            return array("return" => false, "message" => "Invalid security question.");
        }
        if($pass != $rpass){
            return array("return" => false, "message" => "Passwords do not match.");
        }
        $tables = array("user", "agent", "systemadmin");
        foreach ($tables as $v_table) {
            $checkEmail = $this->db->select('*')
            ->from($v_table)
            ->where(array('email' => $email))->get()->row();
            if($checkEmail){
                return array("return" => false, "message" => "The email you entered is already in use.");
            }
        }
        foreach ($tables as $v_table) {
            $checkPhone = $this->db->select('*')
            ->from($v_table)
            ->where(array('phone' => $phone))->get()->row();
            if($checkPhone){
                return array("return" => false, "message" => "The phone you entered is already in use.");
            }
        }
        
        //All checks done?
        $options = array('cost' => 11);
        $pHash = password_hash($pass, PASSWORD_DEFAULT, $options);
        $created_at = date('Y-m-d H:i:s');
        $dbVals = array($nameField => $name, "email" => $email, "phone" => $phone, "address" => $address, "password" => $pHash, "created_at" => $created_at, "usertypeID" => $userTypeID);
        if($userTypeID == AGENT){
            $dbVals = array($nameField => $name, "email" => $email, "phone" => $phone, "address" => $address, "password" => $pHash, "created_at" => $created_at, "usertypeID" => $userTypeID, "payment_plan" => $payment);
        }
        $this->db->insert($table, $dbVals);
        $userID = $this->db->insert_id();
        //role setup
        $this->db->insert("roles_users", array("user_id" => $userID, "role_id" => $userTypeID, "usertypeID" => $userTypeID));
        //Security answer
        $this->db->insert("securityanswer", array("userID" => $userID, "questionID" => $userTypeID, "usertypeID" => $userTypeID, "created_at" => $created_at, "answer" => $seca));
        //pending verification
        $this->db->insert("emailtoken", array("userID" => $userID, "purpose" => PURPOSE_EMAIL_VERIFY, "usertypeID" => $userTypeID, "generated_at" => $created_at));

        return array("return" => true, "message" => "Registration successful.");
    }
}