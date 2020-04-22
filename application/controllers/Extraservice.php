<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extraservice extends System_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model("extraservice_m");
        $this->load->model("service_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('extraservice', $language);
        $this->route_access();
    }

    public function index() {
        $this->data['extraservices'] = $this->extraservice_m->get_order_by_extraservice();
        $this->data["subview"] = "/extraservice/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("extraservice_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            )
        );
        return $rules;
    }

    public function add() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js'
            )
        );
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"] = "/extraservice/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "title" => $this->input->post("title"),
                );
                $this->extraservice_m->insert_extraservice($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("extraservice/index"));
            }
        } else {
            $this->data["subview"] = "/extraservice/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js'
            )
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['extraservice'] = $this->extraservice_m->get_single_extraservice(array('esID' => $id));
            if($this->data['extraservice']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/extraservice/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "title" => $this->input->post("title")
                        );

                        $this->extraservice_m->update_extraservice($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("extraservice/index"));
                    }
                } else {
                    $this->data["subview"] = "/extraservice/edit";
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function view() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['extraservice'] = $this->extraservice_m->get_single_extraservice(array('esID' => $id));
            if($this->data['extraservice']) {
                $this->data["subview"] = "/extraservice/view";
                $this->load->view('_layout_main', $this->data);
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function delete() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['extraservice'] = $this->extraservice_m->get_single_extraservice(array('esID' => $id));
            if($this->data['extraservice']) {
                $this->extraservice_m->delete_extraservice($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("extraservice/index"));
            } else {
                redirect(base_url("extraservice/index"));
            }
        } else {
            redirect(base_url("extraservice/index"));
        }
    }

    public function save_extra_services(){
        if($this->input->is_ajax_request()){
            $rules = array(
                array(
                    'field' => 'extservs[]',
                    'label' => "extra services",
                    'rules' => 'required'
                )
            );
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE){
                $this->exitError();
            }else{
                $extservs = $this->input->post('extservs');
                if(!is_array($extservs) && count($extservs) <= 0){
                    $this->exitError();
                }
                $serviceID = (int)$extservs[0]['serviceID'];
                if(empty($serviceID) || !is_integer($serviceID)){
                    $this->exitError();
                }
                $serviceIDs = array_column($extservs, 'serviceID');
                foreach ($serviceIDs as $vsid) {
                    if($vsid != $serviceID){
                        $this->exitError();
                    }
                }
                $costs = array_column($extservs, 'cost');
                foreach ($costs as $cost) {
                    if(!is_numeric($cost) || $cost < 0){
                        $this->exitError();
                    }
                }
                $names = array();
                $extNames = array_column($extservs, 'name');
                foreach ($extNames as $v_name) {
                    if(empty(trim($v_name))){
                        $this->exitError("Please enter a name for all extra services");
                    }
                    if(in_array($v_name, $names)){
                        $this->exitError();
                    }else{
                        array_push($names, $v_name);
                    }
                    if(count($v_name) > 25){
                        $this->exitError();
                    }
                }
                $service = $this->service_m->get_single_service(array('serviceID' => $serviceID, 'agentID' => $this->userID, 'deleted_at' => null));
                if(empty($service)){
                    $this->exitError();
                }
                foreach ($extservs as $ext_serv) {
                    $exists = $this->db->select('*')
                    ->from('extraservice')
                    ->join("service", "extraservice.serviceID = service.serviceID", "inner")
                    ->join("agent", "service.agentID = agent.agentID", "inner")
                    ->where(array('service.serviceID' => $serviceID, 'service.agentID' => $this->userID, 'extraservice.deleted_at' => null))
                    ->like('name', $ext_serv['name'], 'none')
                    ->get()->row();
                    if($exists){
                        $this->exitError("You already have an extra service with the same name for this service");
                    }
                }
                $ids = array();
                foreach ($extservs as &$ext_serv) {
                    $ext_serv['created_at'] = date('Y-m-d H:i:s');
                    $this->db->insert('extraservice', $ext_serv);
                    $ids[] = $this->db->insert_id();
                }
                if($this->dbSuccess()){
                    $this->exitSuccess("Saved extra services successfully", $ids);
                }
            }
        }
        $this->exitError();
    }

    public function delete_extra_service(){
        if($this->input->is_ajax_request()){
            $rules = array(
                array(
                    'field' => 'esID',
                    'label' => "extra service",
                    'rules' => 'trim|required|xss_clean|numeric'
                ),
            );
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE){
                $this->exitError();
            }else{
                $esID = $this->input->post('esID');
                $this->db
                    ->select("*")
                    ->from("extraservice")
                    ->join("service", "extraservice.serviceID = service.serviceID", "inner")
                    ->join("agent", "service.agentID = agent.agentID", "inner")
                    ->where(array("agent.agentID" => $this->userID, 'esID' => $esID));
                $eService = $this->db->get()->row();
                if($eService){
                    $this->extraservice_m->update_extraservice(array("deleted_at" => date('Y-m-d H:i:s')), $esID);
                    if($this->dbSuccess()){
                        $this->exitSuccess("Deleted extra service successfully");
                    }
                }
            }
        }
        $this->exitError();
    }
}
