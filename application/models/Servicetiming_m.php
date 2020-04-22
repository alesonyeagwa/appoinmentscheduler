<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servicetiming_m extends MY_Model {

    protected $_table_name = 'servicetiming';
    protected $_primary_key = 'timingID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "timingID asc";

    function __construct() {
        parent::__construct();
    }

    function get_servicetiming($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_servicetiming($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_servicetiming($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_servicetiming($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_servicetiming($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_servicetiming($id){
        parent::delete($id);
    }
}
