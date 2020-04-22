<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_m extends MY_Model {

    protected $_table_name = 'service';
    protected $_primary_key = 'serviceID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "serviceID asc";

    function __construct() {
        parent::__construct();
    }

    function get_service($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_service($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_service($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_service($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_service($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_service($id){
        parent::delete($id);
    }

    public function get_grouped_services_and_agents($catid){
        $requiredServiceKeys = "service.serviceID, service.serviceName, service.agentID, cost, ";
        $requiredAgentKeys = "agent.agentName, agent.description, agent.email, agent.phone, agent.address, agent.profession, ";
        $res = $this->db
        ->select($requiredServiceKeys . $requiredAgentKeys . "IF(agent.photo='', '', CONCAT('" . base_url()."', agent.photo)) AS pp")
        ->from("service")
        ->join("agent", "service.agentID = agent.agentID", "inner")
        ->where(array("service.serviceCategoryID" => $catid, "service.deleted_at" => null, "service.status" => 1))
        ->order_by("service.serviceName")
        ->get()->result();
        foreach ($res as &$value) {
            $extservs = $this->db->select('*')
            ->from('extraservice')
            ->where(array('extraservice.serviceID' => $value->serviceID))->get()->result();
            if(!empty($extservs)){
                $value->extservs = $extservs;
            }
            $timings = groupby_key($this->db->select('timingID, appday, starttime, endtime, slots')
            ->from('servicetiming')
            ->where(array('servicetiming.serviceID' => $value->serviceID))->get()->result(), 'appday'); 
            if($timings){
                $value->serviceTiming = $timings;
            }
        }
        return $res;
    }
    public function get_agent_services($agentID){
        $res = $this->db
        ->select("*, servicecategory.categoryName as categoryName")
        ->from("service")
        ->join("agent", "service.agentID = agent.agentID", "inner")
        ->join("servicecategory", "service.serviceCategoryID = servicecategory.serviceCategoryID", "inner")
        ->where(array("service.agentID" => $agentID, "service.deleted_at" => null))
        ->order_by("service.serviceName")
        ->get()->result();
        foreach ($res as &$value) {
            $extservs = $this->db->select('esID, serviceID, name, cost')
            ->from('extraservice')
            ->where(array('extraservice.serviceID' => $value->serviceID, "deleted_at" => null))->get()->result();
            if(!empty($extservs)){
                $value->extservs = $extservs;
            }
            $timings = groupby_key($this->db->select('timingID, serviceID, appday, starttime, endtime, status, slots')
            ->from('servicetiming')
            ->where(array('servicetiming.serviceID' => $value->serviceID, "deleted_at" => null))->get()->result(), 'appday'); 
            if($timings){
                $value->serviceTiming = $timings;
            }else{
                $value->serviceTiming = [];
            }
        }
        return $res;
    }
}
