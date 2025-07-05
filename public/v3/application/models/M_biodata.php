<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_biodata extends CI_Model {

	public $table	= 'biodata';

	public function get()
	{
		$this->db->select('*');
		$this->db->from($this->table);
        return $this->db->get();
	}

	public function insert($data)
	{
		$this->db->insert($this->table, $data);
	}

	public function get_by_id($id_biodata)
	{
		return $this->db->get_where($this->table, ['id_biodata' => $id_biodata])->row_array();
	}

	public function get_by_email($id_mitra)
	{
		return $this->db->get_where($this->table, ['id_mitra' => $id_mitra])->row_array();
	}

	public function get_by_role($role)
	{
		return $this->db->get_where($this->table, ['role' => $role])->result_array();
	}

	public function update($data)
	{
		$this->db->where('id_biodata', $data['id_biodata']);
		$this->db->update($this->table, $data);
	}

	public function delete($id_biodata)
	{
		$this->db->delete($this->table, ['id_biodata' => $id_biodata]);
	}

}
