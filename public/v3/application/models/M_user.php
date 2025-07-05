<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {

	public $table	= 'user';

	public function get_data()
	{
		$this->db->select('*');
		$this->db->from($this->table);
        return $this->db->get();
	}

	public function insert($data)
	{
		$this->db->insert($this->table, $data);
	}

	public function get_by_id($id_user)
	{
		return $this->db->get_where($this->table, ['id_user' => $id_user])->row_array();
	}

	public function get_by_role($role)
	{
		return $this->db->get_where($this->table, ['role' => $role])->result_array();
	}

	public function update($data)
	{
		$this->db->where('id_user', $data['id_user']);
		$this->db->update($this->table, $data);
	}

	public function delete($id_user)
	{
		$this->db->delete($this->table, ['id_user' => $id_user]);
	}
}
