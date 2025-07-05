<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_pembelian extends CI_Model
{

	private $table	= 'pembelians';

	public function get_all($limit = null, $start = null, $search = null, $where = null)
	{
		$this->db->select('id_pembelian, SUM(jumlah_beli_barang) as jumlah_beli_barang_sum, SUM(total_harga_beli) as total_harga_beli_sum');
		$this->db->from('detail_pembelian');
		$this->db->group_by('id_pembelian');
		$subquery = $this->db->get_compiled_select();

		$this->db->select('id_pembelian, SUM(nominal_bayar) as nominal_bayar_sum');
		$this->db->from('detail_pembelian_bayar');
		$this->db->group_by('id_pembelian');
		$subquery2 = $this->db->get_compiled_select();

		$this->db->select('pembelians.*, subquery.jumlah_beli_barang_sum, subquery.total_harga_beli_sum, subquery2.nominal_bayar_sum');

		$this->db->from('pembelians');
		$this->db->join("($subquery) as subquery", 'subquery.id_pembelian = pembelians.id_pembelian', 'left');
		$this->db->join("($subquery2) as subquery2", 'subquery2.id_pembelian = pembelians.id_pembelian', 'left');

		if ($search) {
			$this->db->group_start();
			$this->db->like('pembelians.no_pembelian', $search);
			$this->db->or_like('pembelians.nama_pabrik', $search);
			$this->db->or_like('pembelians.no_ref', $search);
			$this->db->or_like('pembelians.tanggal_pembelian', $search);
			$this->db->or_like('pembelians.jatuh_tempo', $search);
			$this->db->or_like('subquery2.nominal_bayar_sum', $search);
			$this->db->or_like('subquery.jumlah_beli_barang_sum', $search);
			$this->db->or_like('subquery.total_harga_beli_sum', $search);
			$this->db->group_end();
		}
		if ($where) {
			$this->db->where($where);
		}
		if ($limit) {
			$this->db->limit($limit, $start);
		}

		$this->db->order_by('pembelians.id_pembelian', 'DESC');
		$query = $this->db->get();
		return $query;
	}

	public function count_all($search = null)
	{
		$this->db->select('id_pembelian, SUM(jumlah_beli_barang) as jumlah_beli_barang_sum, SUM(total_harga_beli) as total_harga_beli_sum');
		$this->db->from('detail_pembelian');
		$this->db->group_by('id_pembelian');
		$subquery = $this->db->get_compiled_select();

		$this->db->select('id_pembelian, SUM(nominal_bayar) as nominal_bayar_sum');
		$this->db->from('detail_pembelian_bayar');
		$this->db->group_by('id_pembelian');
		$subquery2 = $this->db->get_compiled_select();

		$this->db->select('pembelians.*, subquery.jumlah_beli_barang_sum, subquery.total_harga_beli_sum, subquery2.nominal_bayar_sum');

		$this->db->from('pembelians');
		$this->db->join("($subquery) as subquery", 'subquery.id_pembelian = pembelians.id_pembelian', 'left');
		$this->db->join("($subquery2) as subquery2", 'subquery2.id_pembelian = pembelians.id_pembelian', 'left');

		if ($search) {
			$this->db->group_start();
			$this->db->like('pembelians.no_pembelian', $search);
			$this->db->or_like('pembelians.nama_pabrik', $search);
			$this->db->or_like('pembelians.no_ref', $search);
			$this->db->or_like('pembelians.tanggal_pembelian', $search);
			$this->db->or_like('pembelians.jatuh_tempo', $search);
			$this->db->or_like('subquery2.nominal_bayar_sum', $search);
			$this->db->or_like('subquery.jumlah_beli_barang_sum', $search);
			$this->db->or_like('subquery.total_harga_beli_sum', $search);
			$this->db->group_end();
		}

		$this->db->order_by('pembelians.id_pembelian', 'DESC');
		$query = $this->db->get();

		return $query->num_rows();
	}

	public function update($data)
	{
		$this->db->where('id_pembelian', $data['id_pembelian']);
		$this->db->update('pembelians', $data);
	}

	public function detail_pembelian($id_pembelian)
	{
		$this->db->select('id_pembelian, SUM(jumlah_beli_barang) as jumlah_beli_barang_sum, SUM(total_harga_beli) as total_harga_beli_sum');
		$this->db->from('detail_pembelian');
		$this->db->where('detail_pembelian.id_pembelian', $id_pembelian);
		$this->db->group_by('id_pembelian');
		$subquery = $this->db->get_compiled_select();

		$this->db->select('id_pembelian, SUM(nominal_bayar) as nominal_bayar_sum');
		$this->db->from('detail_pembelian_bayar');
		$this->db->where('detail_pembelian_bayar.id_pembelian', $id_pembelian);
		$this->db->group_by('id_pembelian');
		$subquery2 = $this->db->get_compiled_select();

		$this->db->select('pembelians.*, subquery.jumlah_beli_barang_sum, subquery.total_harga_beli_sum, subquery2.nominal_bayar_sum');

		$this->db->from('pembelians');
		$this->db->where('pembelians.id_pembelian', $id_pembelian);
		$this->db->join("($subquery) as subquery", 'subquery.id_pembelian = pembelians.id_pembelian', 'left');
		$this->db->join("($subquery2) as subquery2", 'subquery2.id_pembelian = pembelians.id_pembelian', 'left');

		$this->db->order_by('pembelians.id_pembelian', 'DESC');
		$query = $this->db->get();
		return $query;
	}

	public function beli_bayar()
	{
		$this->db->select('id_pembelian, SUM(jumlah_beli_barang) as jumlah_beli_barang_sum, SUM(total_harga_beli) as total_harga_beli_sum');
		$this->db->from('detail_pembelian');
		$this->db->group_by('id_pembelian');
		$subquery = $this->db->get_compiled_select();

		$this->db->select('id_pembelian, SUM(nominal_bayar) as nominal_bayar_sum');
		$this->db->from('detail_pembelian_bayar');
		$this->db->group_by('id_pembelian');
		$subquery2 = $this->db->get_compiled_select();

		$this->db->select('sum(subquery.total_harga_beli_sum) as total_hutang, sum(subquery2.nominal_bayar_sum)as total_bayar_hutang');

		$this->db->from('pembelians');
		$this->db->join("($subquery) as subquery", 'subquery.id_pembelian = pembelians.id_pembelian', 'left');
		$this->db->join("($subquery2) as subquery2", 'subquery2.id_pembelian = pembelians.id_pembelian', 'left');

		$this->db->order_by('pembelians.id_pembelian', 'DESC');
		$query = $this->db->get();
		return $query;
	}
}
