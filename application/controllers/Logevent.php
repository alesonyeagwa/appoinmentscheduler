<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logevent extends System_Controller {
    function __construct() {
        parent::__construct();
    }

    public function index() {
        if($this->input->is_ajax_request()){
            $rules = array(
                array(
                    'field' => 'message',
                    'label' => "timing",
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE){
                $this->exitError('OK');
            }else{
                $message = $this->input->post('message');
                log_message('error', 'CLIENT ERROR: '. $message);
                $this->exitSuccess('OK');
            }
        }
    }

}