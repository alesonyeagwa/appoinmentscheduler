<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_m extends MY_Model {

    protected $_table_name = 'user';
    protected $_primary_key = 'userID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "userID asc";

    function __construct() {
        parent::__construct();
    }

    function get_user($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_user($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_user($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_user($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_user($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_user($id){
        parent::delete($id);
    }
}
