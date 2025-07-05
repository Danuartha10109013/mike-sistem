<?php

defined('BASEPATH') or exit('No direct script access allowed');



class M_departemen extends CI_Model
{



	public $table	= 'departemen';



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



	// public function get_by_id($id_departemen)

	// {

	// 	return $this->db->get_where($this->table, ['id_departemen' => $id_departemen])->row_array();
	// }



	public function update($data)

	{

		$this->db->where('id_departemen', $data['id_departemen']);

		$this->db->update($this->table, $data);
	}



	public function delete($id_departemen)

	{

		$this->db->delete($this->table, ['id_departemen' => $id_departemen]);
	}

	public function get_penjualan($limit = null, $start = null, $search = null)
	{
		// $this->db->select('gs.id_gs, SUM(po_gs.harga_barang) as total_harga_barang_sum'); // Ganti dengan nama kolom yang benar
		// $this->db->from('po_gs');
		// $this->db->join('gs', 'po_gs.id_gs = gs.id_gs', 'left');
		// $this->db->group_by('gs.id_gs');
		// $subquery = $this->db->get_compiled_select();

		// $this->db->select('departemen.*, gs.*, subquery.total_harga_barang_sum');
		// $this->db->from('departemen');
		// $this->db->join('gs', 'gs.departemen = departemen.id_departemen', 'left');
		// $this->db->join("($subquery) as subquery", 'subquery.id_gs = gs.id_gs', 'left');

		$this->db->select('departemen.*,departemen.id_departemen as idd');
		$this->db->from('departemen');

		if ($search) {
			$this->db->group_start();
			$this->db->like('departemen.nama_departemen', $search);
			$this->db->or_like('gs.no_invoice', $search);
			$this->db->group_end();
		}

		if ($limit) {
			$this->db->limit($limit, $start);
		}

		$this->db->order_by('departemen.nama_departemen', 'ASC');


		$query = $this->db->get();
		return $query;
	}

	public function count_penjaualan($search = null)
	{
		// $this->db->select('gs.id_gs, SUM(po_gs.total_harga_barang) as total_harga_barang_sum');
		// $this->db->from('po_gs');
		// $this->db->join('gs', 'po_gs.id_gs = gs.id_gs', 'left');
		// $this->db->group_by('gs.id_gs');
		// $subquery = $this->db->get_compiled_select();

		// $this->db->select('departemen.*, gs.*, subquery.total_harga_barang_sum');
		// $this->db->from('departemen');
		// $this->db->join('gs', 'gs.departemen = departemen.id_departemen', 'left');
		// $this->db->join("($subquery) as subquery", 'subquery.id_gs = gs.id_gs', 'left');

		// if ($search) {
		// 	$this->db->group_start();
		// 	$this->db->like('departemen.nama_departemen', $search);
		// 	$this->db->or_like('gs.no_invoice', $search);
		// 	$this->db->or_like('gs.tanggal', $search);
		// 	$this->db->or_like('subquery.total_harga_barang_sum', $search);
		// 	$this->db->group_end();
		// }

		$this->db->select('departemen.*,departemen.id_departemen as idd');
		$this->db->from('departemen');

		if ($search) {
			$this->db->group_start();
			$this->db->like('departemen.nama_departemen', $search);
			$this->db->or_like('gs.no_invoice', $search);
			$this->db->group_end();
		}

		$this->db->group_by('departemen.id_departemen');
		$this->db->order_by('departemen.nama_departemen', 'ASC');

		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_by_id($id_departemen)
	{
		// $this->db->select('gs.id_gs, SUM(po_gs.harga_barang) as total_harga_barang_sum'); // Ganti dengan nama kolom yang benar
		// $this->db->from('po_gs');
		// $this->db->join('gs', 'po_gs.id_gs = gs.id_gs', 'left');
		// $this->db->group_by('gs.id_gs');
		// $subquery = $this->db->get_compiled_select();

		// $this->db->select('departemen.*, gs.*, subquery.total_harga_barang_sum');
		// $this->db->from('departemen');
		// $this->db->join('gs', 'gs.departemen = departemen.id_departemen', 'left');
		// $this->db->join("($subquery) as subquery", 'subquery.id_gs = gs.id_gs', 'left');

		$this->db->select('departemen.*,departemen.id_departemen as idd');
		$this->db->from('departemen');
		$this->db->where('departemen.id_departemen', $id_departemen);

		$query = $this->db->get();
		return $query;
	}
}
