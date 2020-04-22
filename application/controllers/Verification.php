<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verification extends System_Controller {
    function __construct() {
		parent::__construct();
		$this->load->model("signin_m");
        $this->load->model("register_m");
        
        if($this->signin_m->loggedin() == TRUE){
            redirect(base_url('dashboard/index'));
            return;
        }
    }

    public function pending(){
        if($this->signin_m->loggedin() == TRUE){
            redirect(base_url('dashboard/index'));
            return;
        }
        $data = array();
        $data['title'] = "Pending";
        $data['message'] = "Your email has not been verified. Click the link sent to your email to verify.";
        $data['canResendEmail'] = true;

        $this->load->view('verification/index',  $data);
    }

    public function emailsent(){
        $data = array();
        $data['title'] = "Email sent";
        $data['message'] = "A link has been sent to your email to verify your account. Click on it to continue.";

        $this->load->view('verification/index',  $data);
    }

    public function verify(){
        $_token = $this->uri->segment(3);
        // if($this->signin_m->loggedin() == TRUE){
        //     $this->session->set_flashdata("errors", "Logout first to a verify different email.");
        //     redirect(base_url('dashboard/index'));
        //     return;
        // }
        if(empty($_token)){
            redirect(base_url("signin/index"));
            return;
        }
        $tempId = $_token;
        $token = $this->crypter($_token, 'd');
        if(!empty($token)){
            $tokenDetails = explode('|', $token);
            if(count($tokenDetails) == 5){
                $email = $tokenDetails[2];
                $usertypeID = $tokenDetails[1];
                $userID = $tokenDetails[0];
                $generate_date = $tokenDetails[3];
                $checkToken = $this->db->select('*')
                                    ->from('emailtoken')
                                    ->where(array('userID' => $userID, 'purpose' => PURPOSE_EMAIL_VERIFY, 'usertypeID' => $usertypeID))->get()->row();
                if($checkToken){
                    $m = new \Moment\Moment($checkToken->generated_at);
                    if($m){
                        $minutesAgo = ($m->fromNow()->getMinutes());
                        if($_token != $checkToken->token || $minutesAgo > 5){
                            $data = array(
								"userID" => $userID,
								"usertypeID" => $usertypeID,
								"isPendingVerification" => true,
								"email" => $email,
							);
							$this->session->set_userdata($data);
                            $this->session->set_flashdata("errors", "Expired/Invalid verification");
                            $data = array();
                            $data['title'] = "Verification failed";
                            $data['message'] = "The verification failed. ". anchor(base_url('verification/sendemail'), 'Click here') . " to resend email.";
                            $this->load->view('verification/index',  $data);
                            return;
                        }else{
                            $this->db->delete("emailtoken", array("tokenID" => $checkToken->tokenID));
                            $data = array();
                            $data['title'] = "Verification Successful";
                            $data['message'] = "Your email has been verified. ". anchor(base_url('signin/index'), 'Click here') . " to sign in.";
                            $this->load->view('verification/index',  $data);
                            return;
                        }
                    }
                }else{
                    redirect(base_url("signin/index"));
                    return;
                }
            }
        }else{
            redirect(base_url("signin/index"));
            return;
        }
        redirect(base_url("signin/index"));
        return;
    }

    public function sendemail(){
        if($this->signin_m->loggedin() == TRUE){
            redirect(base_url('dashboard/index'));
            return;
        }
        $email = $this->session->userdata('email');
        $usertypeID = $this->session->userdata('usertypeID');
        $userID = $this->session->userdata('userID');
        $isPendingVerification = $this->session->userdata('isPendingVerification');

        if(!empty($email) && !empty($usertypeID) && $isPendingVerification){
            $returnArray = $this->sendVerificationEmail();
            if(!empty($returnArray)){
                if($returnArray["return"]){
                    redirect(base_url("verification/emailsent"));
                }else{
                    $this->session->set_flashdata("errors", $returnArray['message']);
                    redirect(base_url("verification/pending"));
                    return;
                }
            }
        }
    }
}
