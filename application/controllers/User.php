<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends System_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model("user_m");
        $this->load->model("role_m");
        $this->load->model("role_user_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('user', $language);
        $this->route_access();
    }

    public function index() {
        $this->data["loadcss"] = array('assets/css/bootstrap-table.min.css');
        $this->data["loadjs"] = array('assets/js/bootstrap-table.min.js', 'assets/js/bootstrap-table-vue.min.js');
        $this->data["title"] = "Users";
        $this->data["subview"] = "/user/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("name"),
                'rules' => 'trim|required|xss_clean|min_length[3]|max_length[128]'
            ),array(
                'field' => 'email',
                'label' => $this->lang->line("email"),
                'rules' => 'trim|required|xss_clean|valid_email|max_length[128]'
            ),array(
                'field' => 'phone',
                'label' => $this->lang->line("phone"),
                'rules' => 'trim|required|xss_clean|numeric'
            )
        );
        return $rules;
    }

    public function add() {
        $this->data["title"] = "Add User";
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
                $this->data["subview"] = "/user/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "name" => $this->input->post("name"),
                    "email" => $this->input->post("email"),
                    "phone" => $this->input->post("phone"),
                    "usertypeID" => USERS,
                    "created" => date('Y-m-d H:i:s'),
                );
                $uid = $this->user_m->insert_user($array);
                if($uid){
                    $this->role_user_m->insert_role_user(array(
                        "user_id" => $uid,
                        "role_id" => $this->role_m->roleID('user'),
                        "usertypeID" => USERS,
                    ));
                }
                $this->session->set_flashdata('success', 'User created successfully');
                redirect(base_url("user/index"));
            }
        } else {
            $this->data["subview"] = "/user/add";
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
            $this->data['user'] = $this->user_m->get_single_user(array('userID' => $id));
            if($this->data['user']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/user/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "title" => $this->input->post("title")
                        );

                        $this->user_m->update_user($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("user/index"));
                    }
                } else {
                    $this->data["subview"] = "/user/edit";
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
            $user = $this->user_m->get_single_user(array('userID' => (int)$id));
            if(!empty($user)){
                $this->data["title"] = "View user";
                $this->data["subview"] = "user/view";
                $this->data["user"] = $user; 
                $this->data["userID"] = $tempId; 
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
            $user = $this->user_m->get_user(array('userID' => $id), true);
            if(!empty($user)){
                $this->user_m->update_user(array('active' => 0), $id);
                $this->session->set_flashdata("success", 'Blocked user successfully');
                redirect('/user/view/' . $tempId);
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
            $user = $this->user_m->get_user(array('userID' => $id), true);
            if(!empty($user)){
                $this->user_m->update_user(array('active' => 1), $id);
                $this->session->set_flashdata("success", 'Unblocked user successfully');
                redirect('/user/view/' . $tempId);
            }else{
                redirect('/');
            }
        }else{
            redirect('/');
        }
    }

    public function get_users(){
        if($this->input->is_ajax_request()){
            //$this->dt_limiter();
            $users = $this->user_m->get_user(null, null, 'userID, name, email, phone, active');
            foreach ($users as &$user) {
                $user->view = btn_view('user/view/'. $this->crypter($user->userID, 'e'), 'View');
                $user->stat = process_status($user->active);
            }
            die(json_encode($users));
        }
    }

    public function delete() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['user'] = $this->user_m->get_single_user(array('userID' => $id));
            if($this->data['user']) {
                $this->user_m->delete_user($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("user/index"));
            } else {
                redirect(base_url("user/index"));
            }
        } else {
            redirect(base_url("user/index"));
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
                'rules' => 'trim|required|xss_clean|max_length[12]'
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
            $checkuserPhone = $this->user_m->get_single_user(array("phone" => $phone));
            if($checkuserPhone && $checkuserPhone->userID != $this->userID){
                $this->session->set_flashdata("errors", "The phone number entered is already in use.");
            }else{
                $this->user_m->update_user(array('name' => $name, 'phone' => $phone, 'address' => $address));
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

    // public function print_preview() {
    //     $id = htmlentities(escapeString($this->uri->segment(3)));
    //     if((int)$id) {
    //         $this->data['user'] = $this->user_m->get_single_user(array('userID' => $id));
    //         if($this->data['user']) {
    //             $this->data['panel_title'] = $this->lang->line('panel_title');
    //             $this->printView($this->data, '/user/print_preview');
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
    //         $this->data['user'] = $this->user_m->get_single_user(array('userID' => $id));
    //         if($this->data['user']) {
    //             $email = $this->input->post('to');
    //             $subject = $this->input->post('subject');
    //             $message = $this->input->post('message');

    //             $this->viewsendtomail($this->data['user'], '/user/print_preview', $email, $subject, $message);
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
