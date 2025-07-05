<?php

defined('BASEPATH') or exit('No direct script access allowed');



class M_retur extends CI_Model
{



	public function count_all($search = null)

	{

		$this->db->select('*');

		$this->db->from('retur');

		$this->db->join('barang', 'barang.id_barang=retur.id_barang', 'left');

		if ($search) {

			$this->db->group_start();

			$this->db->like('retur.no_invoice', $search);
			$this->db->or_like('retur.nama_barang', $search);
			$this->db->or_like('retur.stock_barang', $search);
			$this->db->or_like('retur.no_invoice', $search);
			$this->db->or_like('retur.desc', $search);
			$this->db->or_like('retur.tanggal_retur', $search);

			$this->db->group_end();
		}

		$query = $this->db->get();

		return $query->num_rows();
	}



	public function get_all($limit = null, $start = null, $search = null)

	{

		$this->db->select('*');

		$this->db->from('retur');

		$this->db->join('barang', 'barang.id_barang=retur.id_barang', 'left');

		if ($search) {

			$this->db->group_start();

			$this->db->like('retur.no_invoice', $search);
			$this->db->or_like('retur.nama_barang', $search);
			$this->db->or_like('retur.stock_barang', $search);
			$this->db->or_like('retur.no_invoice', $search);
			$this->db->or_like('retur.desc', $search);
			$this->db->or_like('retur.tanggal_retur', $search);

			$this->db->group_end();
		}

		if ($limit) {
			$this->db->limit($limit, $start);
		}

		$this->db->order_by('retur.id_retur', 'DESC');

		$query = $this->db->get();

		return $query;
	}

    public function insert($data)

	{

		$this->db->insert('retur', $data);
	}

    public function update($data)

	{

		$this->db->where('id_retur', $data['id_retur']);

		$this->db->update('retur', $data);
	}

    public function delete($id_retur)

	{

		$this->db->delete('retur', ['id_retur' => $id_retur]);
	}
}