<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extraservice_m extends MY_Model {

    protected $_table_name = 'extraservice';
    protected $_primary_key = 'esID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "esID asc";

    function __construct() {
        parent::__construct();
    }

    function get_extraservice($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_extraservice($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_extraservice($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_extraservice($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_extraservice($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_extraservice($id){
        parent::delete($id);
    }
}
