<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service extends System_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model("service_m");
        $this->load->model("servicetiming_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('service', $language);
        $this->route_access();
    }

    public function index() {
        $this->data['services'] = $this->service_m->get_order_by_service();
        $this->data["subview"] = "/service/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("service_title"),
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
                $this->data["subview"] = "/service/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "title" => $this->input->post("title"),
                );
                $this->service_m->insert_service($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("service/index"));
            }
        } else {
            $this->data["subview"] = "/service/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function get_agent_services(){
        if($this->input->is_ajax_request()){
            $services = groupby_key($this->service_m->get_agent_services($this->userID), 'categoryName');
            die(json_encode($services));
        }
    }
    public function delete_timing(){
        if($this->input->is_ajax_request()){
            $rules = array(
                array(
                    'field' => 'timingID',
                    'label' => "timing",
                    'rules' => 'trim|required|xss_clean|numeric'
                ),
            );
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE){
                $this->exitError();
            }else{
                $timingID = $this->input->post('timingID');
                $this->db
                    ->select("*")
                    ->from("servicetiming")
                    ->join("service", "servicetiming.serviceID = service.serviceID", "inner")
                    ->join("agent", "service.agentID = agent.agentID", "inner")
                    ->where(array("agent.agentID" => $this->userID, 'timingID' => $timingID));
                $timing = $this->db->get()->row();
                if($timing){
                    $this->servicetiming_m->update_servicetiming(array("deleted_at" => date('Y-m-d H:i:s')), $timingID);
                    if($this->dbSuccess()){
                        $this->exitSuccess();
                    }
                }
            }
        }
        $this->exitError();
    }

    public function save_timing(){
        if($this->input->is_ajax_request()){
            $rules = array(
                array(
                    'field' => 'timingID',
                    'label' => "timing",
                    'rules' => 'trim|xss_clean|numeric'
                ),
                array(
                    'field' => 'serviceID',
                    'label' => "service",
                    'rules' => 'trim|required|xss_clean|numeric'
                ),
                array(
                    'field' => 'appday',
                    'label' => "day",
                    'rules' => 'trim|required|xss_clean|min_length[3]|max_length[3]'
                ),
                array(
                    'field' => 'status',
                    'label' => "status",
                    'rules' => 'trim|required|xss_clean|numeric'
                ),
                array(
                    'field' => 'starttime',
                    'label' => "start time",
                    'rules' => 'trim|required|xss_clean|max_length[5]'
                ),
                array(
                    'field' => 'endtime',
                    'label' => "finish time",
                    'rules' => 'trim|required|xss_clean|max_length[5]'
                ),
            );
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE){
                $this->exitError();
            }else{
                $timingID = $this->input->post('timingID');
                $serviceID = $this->input->post('serviceID');
                $appday = $this->input->post('appday');
                $status = $this->input->post('status');
                $starttime = $this->input->post('starttime');
                $endtime = $this->input->post('endtime');
                $timing = null;
                if($timingID){
                    $timing = $this->servicetiming_m->get_single_servicetiming(array("timingID" => $timingID, "deleted_at" => null));
                    if(empty($timing)){
                        $this->exitError();
                    }
                }
                $service = $this->service_m->get_single_service(array("serviceID" => $serviceID, "agentID" => $this->userID, "deleted_at" => null));
                if(empty($service)){
                    $this->exitError();
                }else if($timing){
                    if($timing->serviceID != $serviceID){
                        $this->exitError();
                    }
                }
                if(!(bool)in_array($appday, _WEEKDAYS)){
                    $this->exitError();
                }
                if(!(bool)strtotime($starttime) || !(bool)strtotime($endtime)){
                    $this->exitError();
                }
                if($status != 0 && $status != 1){
                    $this->exitError();
                }
                if((strtotime($endtime) - strtotime($starttime)) <= 0){
                    $this->exitError('Finish time cannot be the same or come before the start time');
                }
                if((strtotime($endtime) - strtotime($starttime)) <= (60*5)){
                    $this->exitError('Appointments must have a minimum duration of 5 minutes.');
                }
                $existing = $this->servicetiming_m->get_single_servicetiming(array("serviceID" => $serviceID, "appday" => $appday, "starttime" => $starttime, "endtime" => $endtime));
                if($timing){
                    if($timing->timingID != $existing->timingID){
                        $this->exitError("A schedule with the selected start time, finish time and day for this service already exists.");
                    }
                    $this->servicetiming_m->update_servicetiming(array("starttime" => $starttime, "endtime" => $endtime, "status" => $status), $timing->timingID);
                    if($this->dbSuccess()){
                        $this->exitSuccess("Saved schedule successfully.");
                    }
                }else{
                    if($existing){
                        $this->exitError("A schedule with the selected start time, finish time and day for this service already exists.");
                    }
                    $id = $this->servicetiming_m->insert_servicetiming(array("serviceID" => $serviceID, "appday" => $appday, "status" => $status, "starttime" => $starttime, "endtime" => $endtime, "created_at" => date('Y-m-d H:i:s')));
                    if($this->dbSuccess()){
                        $this->exitSuccess("Saved schedule successfully.", $id);
                    }
                }
            }
        }
        $this->exitError();
    }

    public function update_service_information(){
        if($this->input->is_ajax_request()){
            $rules = array(
                array(
                    'field' => 'serviceID',
                    'label' => "service",
                    'rules' => 'trim|required|xss_clean|numeric'
                ),
                array(
                    'field' => 'serviceName',
                    'label' => "service name",
                    'rules' => 'trim|required|xss_clean|min_length[3]|max_length[25]'
                ),
                array(
                    'field' => 'cost',
                    'label' => "cost",
                    'rules' => 'trim|required|xss_clean|numeric'
                ),
                array(
                    'field' => 'status',
                    'label' => "status",
                    'rules' => 'trim|required|xss_clean|numeric'
                )
            );
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE){
                $this->exitError();
            }else{
                $serviceID = $this->input->post('serviceID');
                $serviceName = $this->input->post('serviceName');
                $status = $this->input->post('status');
                $cost = $this->input->post('cost');
                
                if($cost < 0){
                    $this->exitError();
                }
                if($status != 0 && $status != 1){
                    $this->exitError();
                }
                $service = $this->service_m->get_single_service(array("serviceID" => $serviceID, "agentID" => $this->userID, "deleted_at" => null));
                if(empty($service)){
                    $this->exitError();
                }
                $exists = $this->db->select('*')
                    ->from('service')
                    ->where(array('serviceCategoryID' => $service->serviceCategoryID, 'agentID' => $this->userID, 'deleted_at' => null))
                    ->like('serviceName', $serviceName, 'none')
                    ->get()->row();
                if($exists && $exists->serviceID != $service->serviceID){
                    $this->exitError('A service you own for this category already has this name');
                }
                $this->service_m->update_service(array('serviceName' => $serviceName, 'status' => $status, 'cost' => $cost), $serviceID);
                if($this->dbSuccess()){
                    $this->exitSuccess('Updated service information successfully');
                }
            }
        }
        $this->exitError();
    }

    public function delete_service(){
        if($this->input->is_ajax_request()){
            $rules = array(
                array(
                    'field' => 'serviceID',
                    'label' => "service",
                    'rules' => 'trim|required|xss_clean|numeric'
                ),
            );
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE){
                $this->exitError();
            }else{
                $serviceID = $this->input->post('serviceID');
                $this->db
                    ->select("*")
                    ->from("service")
                    ->where(array("service.agentID" => $this->userID, 'serviceID' => $serviceID));
                $service = $this->db->get()->row();
                if($service){
                    $this->service_m->update_service(array("deleted_at" => date('Y-m-d H:i:s')), $serviceID);
                    if($this->dbSuccess()){
                        $this->exitSuccess();
                    }
                }
            }
        }
        $this->exitError();
    }

    public function add_service(){
        $rules = array(
            array(
                'field' => 'serviceCategoryID',
                'label' => "service",
                'rules' => 'trim|required|xss_clean|numeric'
            ),
            array(
                'field' => 'serviceName',
                'label' => "service name",
                'rules' => 'trim|required|xss_clean|min_length[3]|max_length[25]'
            ),
            array(
                'field' => 'cost',
                'label' => "cost",
                'rules' => 'trim|required|xss_clean|numeric'
            ),
        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == FALSE){
            $this->exitError();
        }else{
            $serviceCategoryID = $this->input->post('serviceCategoryID');
            $serviceName = $this->input->post('serviceName');
            $cost = $this->input->post('cost');
            $category = $this->db->select('*')
                        ->from('servicecategory')
                        ->where(array("serviceCategoryID" => $serviceCategoryID))->get()->row();
            
            if($category){
                if($cost < 0){
                    $this->exitError();
                }
                $exists = $this->db->select('*')
                    ->from('service')
                    ->where(array('serviceCategoryID' => $category->serviceCategoryID, 'agentID' => $this->userID, "deleted_at" => null))
                    ->like('serviceName', $serviceName, 'none')
                    ->get()->row();
                if($exists){
                    $this->exitError("You already have a service with the same name and in the same category");
                }
                $id = $this->service_m->insert_service(array("serviceCategoryID" => $serviceCategoryID, "serviceName" => $serviceName, "cost"  => $cost, "agentID" => $this->userID, "created_at" => date('Y-m-d H:i:s')));
                if($this->dbSuccess()){
                    $this->exitSuccess("Saved service successfully.", $id);
                }
            }
        }
        $this->exitError();
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
            $this->data['service'] = $this->service_m->get_single_service(array('serviceID' => $id));
            if($this->data['service']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/service/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "title" => $this->input->post("title")
                        );

                        $this->service_m->update_service($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("service/index"));
                    }
                } else {
                    $this->data["subview"] = "/service/edit";
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
            $this->data['service'] = $this->service_m->get_single_service(array('serviceID' => $id));
            if($this->data['service']) {
                $this->data["subview"] = "/service/view";
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
            $this->data['service'] = $this->service_m->get_single_service(array('serviceID' => $id));
            if($this->data['service']) {
                $this->service_m->delete_service($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("service/index"));
            } else {
                redirect(base_url("service/index"));
            }
        } else {
            redirect(base_url("service/index"));
        }
    }

    // public function print_preview() {
    //     $id = htmlentities(escapeString($this->uri->segment(3)));
    //     if((int)$id) {
    //         $this->data['service'] = $this->service_m->get_single_service(array('serviceID' => $id));
    //         if($this->data['service']) {
    //             $this->data['panel_title'] = $this->lang->line('panel_title');
    //             $this->printView($this->data, '/service/print_preview');
    //         } else {
    //             $this->data["subview"] = "error";
    //             $this->load->view('_layout_main', $this->data);
    //         }
    //     } else {
    //         $this->data["subview"] = "error";
    //         $this->load->view('_layout_main', $this->data);
    //     }
    // }
    // public function send_mail() {
    //     $id = $this->input->post('id');
    //     if ((int)$id) {
    //         $this->data['service'] = $this->service_m->get_single_service(array('serviceID' => $id));
    //         if($this->data['service']) {
    //             $email = $this->input->post('to');
    //             $subject = $this->input->post('subject');
    //             $message = $this->input->post('message');

    //             $this->viewsendtomail($this->data['service'], '/service/print_preview', $email, $subject, $message);
    //         } else {
    //             $this->data["subview"] = "error";
    //             $this->load->view('_layout_main', $this->data);
    //         }
    //     } else {
    //         $this->data["subview"] = "error";
    //         $this->load->view('_layout_main', $this->data);
    //     }

    // }
}
