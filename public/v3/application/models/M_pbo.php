<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pbo extends CI_Model {

	public $table	= 'project_non_po';

	public function get_data()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->order_by('id_pbo', 'DESC');
    return $this->db->get();
	}

	public function insert($data)
	{
		$this->db->insert($this->table, $data);
	}

	public function get_by_id($id_pbo)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join('barang', 'barang.id_barang=project_non_po.id_barang', 'LEFT');
		$this->db->where('id_pbo', $id_pbo);
    return $this->db->get()->row_array();
	}

	public function get_by_role($role)
	{
		return $this->db->get_where($this->table, ['role' => $role])->result_array();
	}

	public function update($data)
	{
		$this->db->where('id_pbo', $data['id_pbo']);
		$this->db->update($this->table, $data);
	}

	public function delete($id_pbo)
	{
		$this->db->delete($this->table, ['id_pbo' => $id_pbo]);
	}
}
