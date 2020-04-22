<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_user_m extends MY_Model {

    protected $_table_name = 'roles_users';
    protected $_primary_key = 'ruserID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "ruserID asc";

    function __construct() {
        parent::__construct();
    }

    function get_role_user($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_role_user($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_role_user($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_role_user($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_role_user($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_role_user($id){
        parent::delete($id);
    }
}
