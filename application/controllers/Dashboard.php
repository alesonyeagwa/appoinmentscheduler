<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Dashboard extends System_Controller {
/*
 Constants are declared in constants.php
*/

	function __construct() {
        parent::__construct();
        $this->load->model('appointment_m');
        $this->load->model('servicecategory_m');
        $this->load->model('service_m');
        $this->load->model('emailtoken_m');
		$this->load->library("email", null, "emailer");

		$language = $this->session->userdata('lang');
        $this->lang->load('dashboard', $language);
        $this->route_access();
    }

    public function index() {
        if($this->uri->segment(2) != 'index'){
            redirect(base_url('dashboard/index'));
            return;
        }
        $this->data["subview"] = "dashboard/index";
        $this->data["title"] = "Dashboard";
        $this->data["loadcss"] = array('assets/css/mini-event-calendar.css', 'assets/css/card.css', 'assets/css/toastr.min.css', 'assets/css/gray4.css');
        $this->data["loadjs"] = array('assets/js/mini-event-calendar.js', 'assets/js/jquery.card.js', 'assets/js/jquery.bvalidator.min.js', 'assets/js/default.min.js', 'assets/js/gray4.js');
        if($this->usertypeID == USERS){
            $this->data['servcats'] = $this->servicecategory_m->get_servicecategory(null, null, 'serviceCategoryID, categoryName');
        }
        
        if($this->usertypeID == ADMIN){
            $this->data["subview"] = "admin/dashboard/index";
            $this->load->model('agent_m');
            $this->load->model('user_m');
            $this->load->model('service_m');
            $this->data["agentCount"] = count($this->agent_m->get_agent());
            $this->data["userCount"] = count($this->user_m->get_user());
            $this->data["appointCount"] = count($this->appointment_m->get_appointment());
            $this->data["serviceCount"] = count($this->service_m->get_service());
        }
        if($this->usertypeID == AGENT){
            $this->data["subview"] = "dashboard/agent/index";
            array_push($this->data["loadjs"], 'assets/js/jquery-clock-timepicker.min.js');
            $this->data['servcats'] = $this->servicecategory_m->get_servicecategory(null, null, 'serviceCategoryID, categoryName');
        }
        $this->load->view('_layout_main', $this->data);
    }

    public function get_grouped_services($categoryID){
        if($this->input->is_ajax_request()){
            $id = escapeString($categoryID);
            if($id){
                //die(json_encode($this->service_m->get_grouped_services_and_agents($id)));
                $groupedServices = groupby_key($this->service_m->get_grouped_services_and_agents($id), 'serviceName');
                die(json_encode($groupedServices));
            }
        }
    }

    public function pass_change_token(){
        if($this->input->is_ajax_request()){
            $prevToken = $this->emailtoken_m->get_single_emailtoken(array('userID' => $this->userID, 'usertypeID' => $this->usertypeID, 'purpose' => PURPOSE_PASSWORD_CHANGE));
            $tokenToSend = $this->giveMeRandNumber(5);
            if($prevToken){
                $m = new \Moment\Moment($prevToken->generated_at);
                if($m){
                    $minutesAgo = ($m->fromNow()->getMinutes());
                    if($minutesAgo < 2){
                        $this->exitError("You must wait for 2 minutes before requesting for another token.");
                    }
                }
                $this->emailtoken_m->update_emailtoken(array("token" => $tokenToSend, "generated_at" => date('Y-m-d H:i:s')), $prevToken->tokenID);
            }else{
                $this->emailtoken_m->insert_emailtoken(array("userID" => $this->userID, "usertypeID" => $this->usertypeID, "purpose" => PURPOSE_PASSWORD_CHANGE, "token" => $tokenToSend, "generated_at" => date('Y-m-d H:i:s')));
            }
            $this->emailer->from($this->config->item("system_email"), $this->config->item("system_name"));
            $this->emailer->to($this->session->userdata('email'));
            $this->emailer->subject('Change Password token');
            $message = "Enter " . $tokenToSend . " to change your password. Please ignore if you did not initiate this.";
            $this->emailer->message($message);

            if($this->emailer->send()) {
                $this->exitSuccess('Token Sent!');
            } else {
                $this->exitError("Could not send token");
            }
        }
    }

    public function sendMessage(){
        $rules = array(
            array(
                'field' => 'subject',
                'label' => "subject",
                'rules' => 'trim|required|xss_clean|min_length[3]|max_length[50]'
            ),
            array(
                'field' => 'message',
                'label' => "message",
                'rules' => 'trim|xss_clean|max_length[200]'
            ),
        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == FALSE){
            $this->session->set_flashdata("errors", validation_errors());
        }else{
            $this->session->set_flashdata("success", "Message sent successfully");
        }
        redirect(base_url('dashboard/index'));
    }
}