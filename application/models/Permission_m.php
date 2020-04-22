<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission_m extends MY_Model {

	protected $_table_name = 'permissions';
	protected $_primary_key = 'permissionID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "permissionID asc";

	function __construct() {
		parent::__construct();
	}

	/**
     * Find data.
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->db->get_where("permissions", array("id" => $id, "deleted_at" => null))->row(0);
    }

    /**
     * Read all data.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->db->get_where("permissions", array("deleted_at" => null))->result();
    }

    /**
     * Insert Data.
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->db->insert('permissions', $data);
    }

    /**
     * Edit data.
     *
     * @param $data
     * @return mixed
     */
    public function edit($data)
    {
        return $this->db->update('permissions', $data, array('id' => $data['id']));
    }

    /**
     * Delete data.
     *
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        $data['deleted_at'] = date("Y-m-d H:i:s");

        return $this->find($id) ? $this->db->update('permissions', $data, array('id' => $id)) : 0;
    }

}

/* End of file permission_m.php */
/* Location: .//Applications/MAMP/htdocs/asheef-tsm/mvc/models/permission_m.php */