<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agent extends System_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model("agent_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('agent', $language);
        $this->route_access();
    }

    public function index() {
        $this->data["loadcss"] = array('assets/css/bootstrap-table.min.css');
        $this->data["loadjs"] = array('assets/js/bootstrap-table.min.js', 'assets/js/bootstrap-table-vue.min.js');
        $this->data["title"] = "Agents";
        $this->data["subview"] = "agent/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("agent_title"),
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
                $this->data["subview"] = "/agent/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "title" => $this->input->post("title"),
                );
                $this->agent_m->insert_agent($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("agent/index"));
            }
        } else {
            $this->data["subview"] = "/agent/add";
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
            $this->data['agent'] = $this->agent_m->get_single_agent(array('agentID' => $id));
            if($this->data['agent']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/agent/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "title" => $this->input->post("title")
                        );

                        $this->agent_m->update_agent($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("agent/index"));
                    }
                } else {
                    $this->data["subview"] = "/agent/edit";
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
        $tempId = $id;
        $id = $this->crypter($id, 'd');
        if(!empty($id) && (int)$id){
            $agent = $this->agent_m->get_single_agent(array('agentID' => $id));
            if(!empty($agent)){
                $this->data["title"] = "View Agent";
                $this->data["subview"] = "agent/view";
                $this->data["agent"] = $agent; 
                $this->data["agentID"] = $tempId; 
                $this->load->view('_layout_main', $this->data);
            }else{
                redirect('/');
            }
        }else{
            redirect('/');
        }
    }

    public function block($id){
        $tempId = $id;
        $id = $this->crypter($id, 'd');
        if(!empty($id) && (int)$id){
            $agent = $this->agent_m->get_agent(array('agentID' => $id), true);
            if(!empty($agent)){
                $this->agent_m->update_agent(array('active' => 0), $id);
                $this->session->set_flashdata("success", 'Blocked agent successfully');
                redirect('/agent/view/' . $tempId);
            }else{
                redirect('/');
            }
        }else{
            redirect('/');
        }
    }
    public function unblock($id){
        $tempId = $id;
        $id = $this->crypter($id, 'd');
        if(!empty($id) && (int)$id){
            $agent = $this->agent_m->get_agent(array('agentID' => $id), true);
            if(!empty($agent)){
                $this->agent_m->update_agent(array('active' => 1), $id);
                $this->session->set_flashdata("success", 'Unblocked agent successfully');
                redirect('/agent/view/' . $tempId);
            }else{
                redirect('/');
            }
        }else{
            redirect('/');
        }
    }

    public function get_agents(){
        if($this->input->is_ajax_request()){
            //$this->dt_limiter();
            $agents = $this->agent_m->get_agent(null, null, 'agentID, agentName, email, profession, active');
            foreach ($agents as &$agent) {
                $agent->view = btn_view('agent/view/'. $this->crypter($agent->agentID, 'e'), 'View');
                $agent->stat = process_status($agent->active);
            }
            die(json_encode($agents));
        }
    }

    public function update_profile(){
        $rules = array(
            array(
                'field' => 'name',
                'label' => "name",
                'rules' => 'trim|required|xss_clean|min_length[3]|max_length[50]'
            ),
            array(
                'field' => 'phone',
                'label' => "timing",
                'rules' => 'trim|required|xss_clean|numeric|max_length[12]'
            ),
            array(
                'field' => 'address',
                'label' => "timing",
                'rules' => 'trim|xss_clean|max_length[255]'
            ),
        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == FALSE){
            $this->session->set_flashdata("errors", validation_errors());
        }else{
            $phone = $this->input->post('phone');
            $name = $this->input->post('name');
            $address = $this->input->post('address');
            $checkAgentPhone = $this->agent_m->get_single_agent(array("phone" => $phone));
            if($checkAgentPhone && $checkAgentPhone->agentID != $this->userID){
                $this->session->set_flashdata("errors", "The phone number entered is already in use.");
            }else{
                $this->agent_m->update_agent(array('agentName' => $name, 'phone' => $phone, 'address' => $address));
                if($this->dbSuccess()){
                    $this->session->set_flashdata("success", "Updated information successfully");
                    $this->session->set_userdata(array('name' => $name, 'phone' => $phone, 'address' => $address));
                }
            }
        }
        redirect(base_url('dashboard/index'));
    }

    public function change_password(){
        $this->changePassword();
    }
}
