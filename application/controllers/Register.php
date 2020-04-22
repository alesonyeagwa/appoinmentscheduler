<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends System_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("signin_m");
        $this->load->model("register_m");


        
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'name',
                'label' => "name",
                'rules' => 'trim|required|xss_clean|min_length[3]|max_length[50]'
            ),
            array(
                'field' => 'email',
                'label' => "email",
                'rules' => 'trim|required|xss_clean|valid_email|max_length[128]'
            ),
            array(
                'field' => 'phone',
                'label' => "phone",
                'rules' => 'trim|required|xss_clean|numeric|max_length[12]'
            ),
            array(
                'field' => 'address',
                'label' => "address",
                'rules' => 'trim|xss_clean|max_length[255]'
            ),
            array(
                'field' => 'pass',
                'label' => "password",
                'rules' => 'trim|required|xss_clean|min_length[8]|max_length[128]'
            ),
            array(
                'field' => 'rpass',
                'label' => "password repeat",
                'rules' => 'trim|required|xss_clean|min_length[8]|max_length[128]'
            ),
            array(
                'field' => 'secq',
                'label' => "security question",
                'rules' => 'trim|required|xss_clean|numeric'
            ),
            array(
                'field' => 'seca',
                'label' => "security answer",
                'rules' => 'trim|required|xss_clean'
            )
        );
        return $rules;
    }

    private function getsecQuestions(){
        $this->db->order_by('rand()');
        $this->db->limit(6);
        $query = $this->db->get('securityquestion');
        return $query->result();
    }

    public function agent(){
        $rules = $this->rules();

        if($_POST){
            $extraRules = array(
                'field' => 'payment',
                'label' => "payment plan",
                'rules' => 'trim|required|xss_clean|in_list[1,2,3]'
            );
            array_push($rules, $extraRules);
            $this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->load->view('reg/agent', $this->data);
			} else {
                $secQs = array_keys($this->session->userdata("secqs"));
                if($secQs){
                    $selsecq = (int)$this->input->post("secq");
                    if(!in_array($selsecq, $secQs)){
                        $this->session->set_flashdata("errors", "Invalid security question.");
                        $this->data['form_validation'] = "Invalid security question.";
                        $this->load->view('reg/agent', $this->data);
                        return;
                    }
                    $checkArray = $this->register_m->register(AGENT);
                    if($checkArray['return'] == TRUE) {
                        $this->session->sess_destroy();
                        redirect(base_url('verification/emailsent'));
                    } else {
                        $this->session->set_flashdata("errors", $checkArray['message']);
                        $this->data['form_validation'] = $checkArray['message'];
                        $this->load->view('reg/agent', $this->data);
                    }
                }else{

                }
            }
        }else{
            if(empty($this->session->userdata("secqs"))){
                $this->session->set_userdata(array("secqs" => pluck($this->getsecQuestions(), "question", "questionID")));
            }
            $this->load->view('reg/agent');
        }
    }

    public function user(){
        $rules = $this->rules();
        if($_POST){

            $this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->load->view('reg/user', $this->data);
			} else {
                $secQs = array_keys($this->session->userdata("secqs"));
                if($secQs){
                    $selsecq = (int)$this->input->post("secq");
                    if(!in_array($selsecq, $secQs)){
                        $this->session->set_flashdata("errors", "Invalid security question.");
                        $this->data['form_validation'] = "Invalid security question.";
                        $this->load->view('reg/user', $this->data);
                        return;
                    }
                    $checkArray = $this->register_m->register(USERS);
                    if($checkArray['return'] == TRUE) {
                        $this->session->sess_destroy();
                        redirect(base_url('verification/emailsent'));
                    } else {
                        $this->session->set_flashdata("errors", $checkArray['message']);
                        $this->data['form_validation'] = $checkArray['message'];
                        $this->load->view('reg/user', $this->data);
                    }
                }else{

                }
            }
        }else{
            if(empty($this->session->userdata("secqs"))){
                $this->session->set_userdata(array("secqs" => pluck($this->getsecQuestions(), "question", "questionID")));
            }
            $this->load->view('reg/user');
        }
    }
}