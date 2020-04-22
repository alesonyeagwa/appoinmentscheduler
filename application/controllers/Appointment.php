<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require_once APPPATH . '/libraries/moment/Moment.php';
//require_once APPPATH . '/libraries/moment/CustomFormats/MomentJs.php';
use \Moment\Moment;
use \Moment\CustomFormats\MomentJs;

class Appointment extends System_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model("appointment_m");
        $this->load->model("service_m");
        $this->load->model("extraservice_m");
        $this->load->model("servicetiming_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('appointment', $language);
        $this->route_access();
    }

    public function index() {
        $this->data['appointments'] = $this->appointment_m->get_order_by_appointment();
        $this->data["subview"] = "/appointment/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("appointment_title"),
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
                $this->data["subview"] = "/appointment/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "title" => $this->input->post("title"),
                );
                $this->appointment_m->insert_appointment($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("appointment/index"));
            }
        } else {
            $this->data["subview"] = "/appointment/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function get_appointments(){
        if($this->input->is_ajax_request()){
            $apps = $this->appointment_m->get_appointments();
            die(json_encode($apps));
        }
    }

    public function book_appointment(){
        if($this->input->is_ajax_request()){
            $rules = array(
                array(
                    'field' => 'serviceID',
                    'label' => "service",
                    'rules' => 'trim|required|xss_clean|numeric'
                ),
                array(
                    'field' => 'payMethod',
                    'label' => "payment method",
                    'rules' => 'trim|required|xss_clean|numeric'
                ),
                array(
                    'field' => 'selectedDate',
                    'label' => "appointment date",
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'selectedTime',
                    'label' => "appointment time",
                    'rules' => 'trim|required|xss_clean|numeric'
                )
            );
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE){
                $this->exitError();
            }else{
                $ext_servs = $this->input->post('extServs');
                $paymethod = $this->input->post("payMethod");
                $serviceID = $this->input->post('serviceID');
                $selectedDate = $this->input->post('selectedDate');
                $selectedTime = $this->input->post('selectedTime');
                if(($ext_servs && is_array($ext_servs) || !$ext_servs) && (bool)strtotime($selectedDate)){
                    $service = $this->service_m->get_single_service(array("serviceID" => $serviceID));
                    if($service && ($paymethod == CARD || $paymethod == LOCALLY)){
                        $totalCost = $service->cost;
                        $_exts = array();
                        if($ext_servs){
                            foreach ($ext_servs as $value) {
                                $ser = $this->extraservice_m->get_single_extraservice(array("esID" => $value, "serviceID" => $serviceID));
                                if(count($ser) > 0){
                                    array_push($_exts, $value);
                                    $totalCost += $ser->cost;
                                }else{
                                    $this->exitError();
                                }
                            }
                        }
                        $ad = new Moment($selectedDate);
                        $appDateDay = strtolower($ad->format('ddd', new MomentJs())); //mon,tue....

                        $appTime = $this->servicetiming_m->get_single_servicetiming(array("timingID" => $selectedTime, "appday" => $appDateDay));
                        if($appTime){
                            $checkAppointmentDateAndTime = $this->appointment_m->get_single_appointment(array("serviceID" => $serviceID, "appointmentDate" => $selectedDate, "timingID" => $selectedTime, "status" => 2));
                            if($checkAppointmentDateAndTime){
                                $this->exitError("You already have an appointment, for this service, pending approval for this day and time.");
                            }
                            $this->appointment_m->insert_appointment(array("userID" => $this->userID, "serviceID" => $service->serviceID, "extraServiceIDs" => json_encode($_exts), "appointmentDate" => $selectedDate, "totalCost" => $totalCost, "servName" => $service->serviceName, "timingID" => $selectedTime, "created_date" => date('c'), "created_at" => date('Y-m-d H:i:s')));
                            if($this->dbSuccess()){
                                $this->exitSuccess("Successfully added appointment");
                            }
                        }
                    }
                }
            }
            //die(var_dump($this->input->post()));
        }
        $this->exitError();
    }

    public function cancel_appointment(){
        if($this->input->is_ajax_request()){
            $rules = array(
                array(
                    'field' => 'appID',
                    'label' => "appointment",
                    'rules' => 'trim|required|xss_clean|numeric'
                ),
            );
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE){
                $this->exitError();
            }else{
                $appID = $this->input->post('appID');
                $this->db
                ->select("*")
                ->from("appointments")
                ->join("service", "appointments.serviceID = service.serviceID", "inner")
                ->join("agent", "service.agentID = agent.agentID", "inner");
                if($this->usertypeID == USERS){
                    $this->db->where(array('userID' => $this->userID, 'appointmentID' => $appID));
                }elseif($this->usertypeID == AGENT){
                    $this->db->where(array("agent.agentID" => $this->userID, 'appointmentID' => $appID));
                }else{
                    $this->exitError();
                }
                
                $appointment = $this->db->group_start()
                                ->where(array("appointments.status" => 2))
                                ->or_where(array("appointments.status" => 3))
                                ->group_end()
                                ->get()->row();
                if($appointment){
                    $status = 1;
                    if($this->usertypeID == AGENT){
                        $status = 0;
                    }
                    $this->appointment_m->update_appointment(array("status" => $status), $appointment->appointmentID);
                    if($this->dbSuccess()){
                        $this->exitSuccess();
                    }
                }
            }
        }
        $this->exitError();
    }

    public function approve_appointment(){
        if($this->input->is_ajax_request()){
            $rules = array(
                array(
                    'field' => 'appID',
                    'label' => "appointment",
                    'rules' => 'trim|required|xss_clean|numeric'
                ),
            );
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE){
                $this->exitError();
            }else{
                $appID = $this->input->post('appID');
                $appointment = $this->db
                ->select("*")
                ->from("appointments")
                ->join("service", "appointments.serviceID = service.serviceID", "inner")
                ->join("agent", "service.agentID = agent.agentID", "inner")
                ->where(array("agent.agentID" => $this->userID, 'appointmentID' => $appID, "appointments.status" => 2))->get()->row();
                if($appointment){
                    $this->appointment_m->update_appointment(array("status" => 3), $appointment->appointmentID);
                    if($this->dbSuccess()){
                        $this->exitSuccess();
                    }
                }
            }
        }
        $this->exitError();
    }

    public function complete_appointment(){
        if($this->input->is_ajax_request()){
            $rules = array(
                array(
                    'field' => 'appID',
                    'label' => "appointment",
                    'rules' => 'trim|required|xss_clean|numeric'
                ),
            );
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE){
                $this->exitError();
            }else{
                $appID = $this->input->post('appID');
                $appointment = $this->db
                ->select("*")
                ->from("appointments")
                ->join("service", "appointments.serviceID = service.serviceID", "inner")
                ->join("agent", "service.agentID = agent.agentID", "inner")
                ->where(array("agent.agentID" => $this->userID, 'appointmentID' => $appID, "appointments.status" => 3))->get()->row();
                if($appointment){
                    $this->appointment_m->update_appointment(array("status" => 4), $appointment->appointmentID);
                    if($this->dbSuccess()){
                        $this->exitSuccess();
                    }
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
            $this->data['appointment'] = $this->appointment_m->get_single_appointment(array('appointmentID' => $id));
            if($this->data['appointment']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/appointment/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "title" => $this->input->post("title")
                        );

                        $this->appointment_m->update_appointment($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("appointment/index"));
                    }
                } else {
                    $this->data["subview"] = "/appointment/edit";
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
            $this->data['appointment'] = $this->appointment_m->get_single_appointment(array('appointmentID' => $id));
            if($this->data['appointment']) {
                $this->data["subview"] = "/appointment/view";
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
            $this->data['appointment'] = $this->appointment_m->get_single_appointment(array('appointmentID' => $id));
            if($this->data['appointment']) {
                $this->appointment_m->delete_appointment($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("appointment/index"));
            } else {
                redirect(base_url("appointment/index"));
            }
        } else {
            redirect(base_url("appointment/index"));
        }
    }
}
