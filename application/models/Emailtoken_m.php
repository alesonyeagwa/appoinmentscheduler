<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emailtoken_m extends MY_Model {

    protected $_table_name = 'emailtoken';
    protected $_primary_key = 'tokenID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "tokenID asc";

    function __construct() {
        parent::__construct();
    }

    function get_emailtoken($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_emailtoken($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_emailtoken($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_emailtoken($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_emailtoken($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_emailtoken($id){
        parent::delete($id);
    }
}
