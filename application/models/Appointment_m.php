<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appointment_m extends MY_Model {

    protected $_table_name = 'appointments';
    protected $_primary_key = 'appointmentID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "appointmentID asc";

    function __construct() {
        parent::__construct();
    }

    function get_appointment($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_appointment($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_appointment($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_appointment($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_appointment($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_appointment($id){
        parent::delete($id);
    }

    public function get_appointments(){
        $requiredAppointKeys = "appointments.appointmentID, appointments.serviceID, appointments.extraServiceIDs, appointments.userID, appointments.servName, appointments.totalCost, appointments.timingID, appointments.status, appointments.appointmentDate, appointments.created_date, ";
        $requiredServiceKeys = "service.serviceID, service.serviceName, service.agentID, cost, ";
        $requiredAgentKeys = "agent.agentName, agent.description, agent.email, agent.phone, agent.address, agent.profession, ";
        $requiredTimingKeys = "servicetiming.appday, servicetiming.starttime, servicetiming.endtime, servicetiming.slots, ";
        $this->db
        ->select($requiredAppointKeys . $requiredServiceKeys . $requiredAgentKeys . $requiredTimingKeys . "user.name as clientName, user.phone as clientPhone, user.email as clientEmail, IF(agent.photo='', '', CONCAT('" . base_url()."', agent.photo)) AS pp")
        ->from("appointments")
        ->join("service", "appointments.serviceID = service.serviceID", "inner")
        ->join("agent", "service.agentID = agent.agentID", "inner")
        ->join("user", "appointments.userID = user.userID", "inner")
        ->join("servicetiming", "appointments.timingID = servicetiming.timingID", "inner");
        if($this->usertypeID == USERS){
            $this->db->where(array("appointments.userID" => $this->userID, "appointments.deleted_at" => null));
        }elseif($this->usertypeID == AGENT){
            $this->db->where(array("agent.agentID" => $this->userID, "appointments.deleted_at" => null));
        }
        $this->db->order_by("appointments.created_at", 'desc');

        $res = $this->db->get()->result();
        foreach ($res as &$value) {
            $extservs = array();
            $ext_services = json_decode($value->extraServiceIDs);
            if($ext_services && is_array($ext_services) && count($ext_services) > 0){
                $this->db->select('esID, serviceID, name, cost')
                ->from('extraservice')
                ->where(array('extraservice.serviceID' => $value->serviceID));
                $this->db->group_start();
                foreach ($ext_services as $v_ext) {
                    $this->db->or_where(array('esID' => $v_ext));
                }
                $this->db->group_end();
                $extservs = $this->db->get()->result();
            }
            if(!empty($extservs)){
                $value->extservs = $extservs;
            }
            unset($value->password);
            unset($value->agentID);
            unset($value->description);
            unset($value->created_at);
            unset($value->deleted_at);
            unset($value->userTypeID);
            unset($value->active);
            unset($value->timingID);

        }
        return $res;
    }
}
