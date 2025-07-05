<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_quotation extends CI_Model {

	public $table	= 'quotation';
	public $table3	= 'no_quot';
	public $table2	= 'detail_quotation';

	public function count_quotation_all($search = null)
	{
		$this->db->select('quotation.*, no_quot.no_quot');
        $this->db->from('quotation');
        $this->db->join('no_quot', 'no_quot.id_no_quot = quotation.id_no_quot', 'left');
        
        if ($search) {
            $this->db->like('quotation.deskripsi', $search);
            $this->db->or_like('quotation.qty', $search);
            $this->db->or_like('quotation.uom', $search);
            $this->db->or_like('quotation.harga', $search);
            $this->db->or_like('quotation.total', $search);
            $this->db->or_like('no_quot.no_quot', $search);
        }

        $query = $this->db->get();
        return $query->num_rows();
	}

	public function get_quotation_all($limit = null, $start = null, $search = null)
	{
		$this->db->select('quotation.*, no_quot.no_quot');
        $this->db->from('quotation');
        $this->db->join('no_quot', 'no_quot.id_no_quot = quotation.id_no_quot', 'left');
        
        if ($search) {
            $this->db->like('quotation.deskripsi', $search);
            $this->db->or_like('quotation.qty', $search);
            $this->db->or_like('quotation.uom', $search);
            $this->db->or_like('quotation.harga', $search);
            $this->db->or_like('quotation.total', $search);
            $this->db->or_like('no_quot.no_quot', $search);
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }

        $query = $this->db->get();
        return $query;
	}

	public function get_no($month = null)
	{
		$this->db->select('*');
		$this->db->from($this->table3);
		if($month != null){
			$this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $month);
		}
		$this->db->order_by('id_no_quot', 'DESC');
    return $this->db->get();
	}

	public function insert_no($data)
	{
		$this->db->insert($this->table3, $data);
	}

	public function get_no_by_id($id_no_quot)
	{
		return $this->db->get_where($this->table3, ['id_no_quot' => $id_no_quot])->row_array();
	}

	public function update_no($data)
	{
		$this->db->where('id_no_quot', $data['id_no_quot']);
		$this->db->update($this->table3, $data);
	}

	public function delete_no($id_no_quot)
	{
		$this->db->delete($this->table3, ['id_no_quot' => $id_no_quot]);
	}

	public function get($id_no_quot = null)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		if(!empty($id_no_quot)){
			$this->db->where('id_no_quot', $id_no_quot);
		}
		
        return $this->db->get();
	}

	public function insert($data)
	{
		$this->db->insert($this->table, $data);
	}

	public function get_by_id($id_quotation)
	{
		return $this->db->get_where($this->table, ['id_quotation' => $id_quotation])->row_array();
	}

	public function get_by_role($role)
	{
		return $this->db->get_where($this->table, ['role' => $role])->result_array();
	}

	public function update($data)
	{
		$this->db->where('id_quotation', $data['id_quotation']);
		$this->db->update($this->table, $data);
	}

	public function delete($id_quotation)
	{
		$this->db->delete($this->table, ['id_quotation' => $id_quotation]);
	}

	public function get_detail($id_quotation)
	{
		// $this->db->query("SELECT * FROM detail_quotation where id_quotation='$id_quotation' order by field(kategori,'MATERIAL','JASA','PROFIT')");
		$this->db->select('*');
		$this->db->from($this->table2);
		$this->db->where('id_quotation', $id_quotation);
		$order = sprintf('FIELD(kategori, %s)', "'MATERIAL','JASA','PROFIT'");
		$this->db->order_by($order);
    return $this->db->get();
	}

	public function insert_detail($data)
	{
		$this->db->insert($this->table2, $data);
	}

	public function get_detail_by_id($id_detail_quotation)
	{
		return $this->db->get_where($this->table2, ['id_detail_quotation' => $id_detail_quotation])->row_array();
	}

	public function update_detail($data)
	{
		$this->db->where('id_detail_quotation', $data['id_detail_quotation']);
		$this->db->update($this->table2, $data);
	}

	public function delete_detail($id_detail_quotation)
	{
		$this->db->delete($this->table2, ['id_detail_quotation' => $id_detail_quotation]);
	}
}
