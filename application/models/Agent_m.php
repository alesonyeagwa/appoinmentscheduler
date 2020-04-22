<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agent_m extends MY_Model {

    protected $_table_name = 'agent';
    protected $_primary_key = 'agentID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "agentID asc";

    function __construct() {
        parent::__construct();
    }

    function get_agent($array=NULL, $signal=FALSE, $cols = '*') {
        $query = parent::get($array, $signal, $cols);
        return $query;
    }

    function get_single_agent($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_agent($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_agent($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_agent($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_agent($id){
        parent::delete($id);
    }
}
