<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resetpass extends System_Controller {
    var $datax = array("resetStep" => 1);

    function __construct() {
		parent::__construct();
		$this->load->model("signin_m");
        $this->load->model("register_m");
		$this->load->library("email", null, "emailer");
        $data['resetStep'] = "1";

        if($this->signin_m->loggedin() == TRUE){
            redirect(base_url('dashboard/index'));
            return;
        }
    }

    public function index(){
        //$this->session->unset_userdata('resetUser');
        //$this->session->unset_userdata("resetToken");
        $data = array();
        $this->datax['resetStep'] = "1";
        $this->load->view('reset/index',  $this->datax);
    }

    public function email(){
        $rules = array(
            array(
                'field' => 'email',
                'label' => "email",
                'rules' => 'trim|required|xss_clean|valid_email|max_length[128]'
            )
        );
        if($_POST){
            $this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->datax['form_validation'] = validation_errors();
				$this->load->view('reset/index', $this->datax);
			} else {
                $email = $this->input->post("email");
                $tables = array("user", "agent");
                $account = null;
                $table = "user";
                foreach ($tables as $v_table) {
                    $checkEmail = $this->db->select('*')
                    ->from($v_table)
                    ->where(array('email' => $email))->get()->row();
                    if($checkEmail){
                        $account = $checkEmail;
                        $table = $v_table;
                        break;
                    }
                }
                $usertypeID = USERS;
                $colName = $table . "ID";
                if($table == "agent"){
                    $usertypeID = AGENT;
                }
                if(!empty($account)){
                    $seca = $this->db->select('*')
                        ->from("securityanswer")
                        ->join("securityquestion", "securityanswer.questionID = securityquestion.questionID", "left")
                        ->where(array("userID" => $account->$colName, "usertypeID" => $usertypeID))->get()->row();
                    if(!empty($seca)){
                        $data = array(
                            "email" => $email,
                            "userID" => $account->$colName,
                            "usertypeID" => $usertypeID,
                            "seca" => $seca->answer,
                            "time" => date('Y-m-d H:i:s')
                        );
                        $this->session->set_userdata('resetUser', $data);
                        $this->datax['secq'] = $seca->question;
                        $this->datax['resetStep'] = "2";
                        $this->load->view('reset/index', $this->datax);
                        return;
                    }else{
                        $this->session->set_flashdata("errors", "Sorry, invalid data.");
                        $this->datax['form_validation'] = "Sorry, invalid data.";
                        $this->load->view('reset/index', $this->datax);
                        return;
                    }
                }else{
                    $this->session->set_flashdata("errors", "Sorry, invalid data.");
                    $this->datax['form_validation'] = "Sorry, invalid data.";
                    $this->load->view('reset/index', $this->datax);
                    return;
                }
            }
        }
        
        redirect(base_url('resetpass'));
    }

    public function secq(){
        $rules = array(
            array(
                'field' => 'seca',
                'label' => "security answer",
                'rules' => 'trim|required|xss_clean'
            )
        );
        if($_POST){
            $this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
                $this->datax['form_validation'] = validation_errors();
                $this->datax['resetStep'] = "2";
				$this->load->view('reset/index', $this->datax);
			} else {
                $seca = $this->input->post('seca');
                $userAccount = $this->getTempUserAccount();
                //die(var_dump($userAccount));
                if(!empty($userAccount)){
                    if($userAccount['seca'] == $seca){
                        $res = $this->sendResetToken($userAccount['email']);
                        if($res['return']){
                            $this->session->set_flashdata("success", $res["message"]);
                            $this->datax['resetStep'] = "3";
                            $this->load->view('reset/index', $this->datax);
                            return;
                        }else{
                            $this->session->set_flashdata("errors", $res["message"]);
                            $this->datax['resetStep'] = "3";
                            $this->load->view('reset/index', $this->datax);
                            return;
                        }
                    }else{
                        $this->session->set_flashdata("errors", "Sorry, incorrect answer.");
                        $this->datax['form_validation'] = "Sorry, incorrect answer.";
                        $this->datax['resetStep'] = "2";
                        $this->load->view('reset/index', $this->datax);
                        log_message('error', "SECURITY ANSWER FAILURE: for " . $userAccount['email']);
                        return;
                    }
                }
            }
        }
        
        redirect(base_url('resetpass'));
    }

    public function resendToken(){
        $userAccount = $this->getTempUserAccount();
        if(!empty($userAccount)){
            $tokenToSend = $this->giveMeRandNumber(6);
            $res = $this->sendResetToken($userAccount['email']);
            if($res['return']){
                $this->session->set_flashdata("success", $res["message"]);
                $this->datax['resetStep'] = "3";
                $this->load->view('reset/index', $this->datax);
                return;
            }else{
                $this->session->set_flashdata("errors", $res["message"]);
                $this->datax['resetStep'] = "3";
                $this->load->view('reset/index', $this->datax);
                return;
            }
        }
        
        redirect(base_url('resetpass'));
    }

    public function change_password(){
        $rules = array(
            array(
                'field' => 'pass',
                'label' => "password",
                'rules' => 'trim|required|xss_clean|min_length[8]|max_length[128]'
            ),
            array(
                'field' => 'rpass',
                'label' => "repeat password",
                'rules' => 'trim|required|xss_clean|min_length[8]|max_length[128]'
            ),
            array(
                'field' => 'emailtoken',
                'label' => "token",
                'rules' => 'trim|xss_clean'
            ),
        );
        if($_POST){
            $this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
                $this->datax['form_validation'] = validation_errors();
                $this->session->set_flashdata("errors", validation_errors());
                $this->datax['resetStep'] = "3";
                $this->load->view('reset/index', $this->datax);
                return;
			} else {
                $pass = $this->input->post('pass');
                $rpass = $this->input->post('rpass');
                $emailtoken = $this->input->post('emailtoken');
                $userAccount = $this->getTempUserAccount();
                if(!empty($userAccount)){
                    $token = $this->session->userdata("resetToken");
                    if($emailtoken == $token['token']){
                        //Check token expiry
                        $m = new \Moment\Moment($token['generated_at']);
                        if($m){
                            $minutesAgo = ($m->fromNow()->getMinutes());
                            if($minutesAgo > 5){
                                $this->session->set_flashdata("errors", "Sorry, token has expired.");
                                $this->datax['form_validation'] = "Sorry, token has expired.";
                                $this->datax['resetStep'] = "3";
                                $this->load->view('reset/index', $this->datax);
                                return;
                            }
                        }

                        $table = "";
                        if($userAccount['usertypeID'] == USERS){
                            $table = "user";
                        }elseif($userAccount['usertypeID'] == AGENT){
                            $table = "agent";
                        }else{
                            $this->session->set_flashdata("errors", "Sorry, an unexpected error occured.");
                            $this->datax['resetStep'] = "3";
                            $this->load->view('reset/index', $this->datax);
                            return;
                        }
                        $colName = $table . "ID";
                        $checkuser = $this->db->select('*')
                            ->from($table)
                            ->where(array($colName => $userAccount['userID']))->get()->row();
                        if($checkuser){
                            $options = array('cost' => 11);
                            $newHash = password_hash($pass, PASSWORD_DEFAULT, $options);
                            
                            //Proceed to change password
                            //Delete Token
                            $this->session->unset_userdata('resetUser');
                            $this->session->unset_userdata("resetToken");
                            //update account
                            $this->db->update($table, array("password" => $newHash), array($colName => $userAccount['userID']));
                            $this->session->set_flashdata("success", "Changed password successfully. You must log in now.");
                            redirect(base_url("signin/index"));
                            return;
                        }
                    }else{
                        $this->session->set_flashdata("errors", "Sorry, incorrect token.");
                        $this->datax['form_validation'] = "Sorry, incorrect token.";
                        $this->datax['resetStep'] = "3";
                        $this->load->view('reset/index', $this->datax);
                        log_message('error', "RESET PASSWORD TOKEN FAILURE: for " . $userAccount['email']);
                        return;
                    }
                }
            }
        }
        redirect(base_url('resetpass'));
    }

    private function sendResetToken($email){
        if($email){
            $prevToken = $this->session->userdata('resetToken');
            $createNewTokenData = false;
            $tokenToSend = $this->giveMeRandNumber(6);
            if($prevToken && is_array($prevToken)){
                $m = new \Moment\Moment($prevToken['generated_at']);
                if($m){
                    $minutesAgo = ($m->fromNow()->getMinutes());
                    if($minutesAgo < 5){
                        return array("return" => false, "message" => "You must wait for 5 minutes before requesting for another token.");
                    }else{
                        $createNewTokenData = true;
                    }
                }
            }else{
                $createNewTokenData = true;
            }
            if($createNewTokenData){
                $newTokenData = array("token" => $tokenToSend, "generated_at" => date('Y-m-d H:i:s'), "for_user" => $email);
                $this->emailer->from($this->config->item("system_email"), $this->config->item("system_name"));
                $this->emailer->to($email);
                $this->emailer->subject('Password Reset Token');
                $message = "Enter " .$tokenToSend . " to reset your password. This token expires in 5 minutes. Please ignore if you did not initiate this.";
                $this->emailer->message($message);
                if($this->emailer->send()) {
                    $this->session->set_userdata('resetToken', $newTokenData);
                    return array("return" => true, "message" => "Token sent");
                } else {
                    return array("return" => false, "message" => "Could not send token");
                }
            }
        }
        return array("return" => false, "message" => "Could not send token.");
    }

    private function getTempUserAccount(){
        $user = $this->session->userdata("resetUser");
        if($user){
            $m = new \Moment\Moment($user['time']);
            if($m){
                $minutesAgo = ($m->fromNow()->getMinutes());
                if($minutesAgo <= 30){
                    return $user;
                }else{
                    $this->session->unset_userdata("resetUser");
                }
            }
        }
        return null;
    }

    private function clearResetValues(){
        die("here");
        $this->session->unset_tempdata('resetUser');
        $this->session->unset_userdata("resetToken");
    }
}