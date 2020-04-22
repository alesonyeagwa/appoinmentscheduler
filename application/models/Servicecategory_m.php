<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servicecategory_m extends MY_Model {

    protected $_table_name = 'servicecategory';
    protected $_primary_key = 'serviceCategoryID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "serviceCategoryID asc";

    function __construct() {
        parent::__construct();
    }

    function get_servicecategory($array=NULL, $signal=FALSE, $cols = '*') {
        $query = parent::get($array, $signal, $cols);
        return $query;
    }

    function get_single_servicecategory($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_servicecategory($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_servicecategory($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_servicecategory($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_servicecategory($id){
        parent::delete($id);
    }
}
