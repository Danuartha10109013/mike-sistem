<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_penjualan extends CI_Model
{

	public function get_by_id_penjualan($id_departemen = null, $limit = null, $start = null, $search = null)
	{
		$this->db->select('*')
			->from('gs')
			->join('departemen', 'departemen.id_departemen=gs.departemen ')
			->where('gs.status_gs', 'Sudah Selesai');
		if ($id_departemen) {

			$this->db->where('gs.departemen', $id_departemen);
		}

		if ($search) {
			$this->db->group_start();

			$this->db->or_like('departemen.nama_departemen', $search);
			$this->db->or_like('gs.no_invoice', $search);

			$this->db->group_end();
		}
		$this->db->order_by('nama_departemen', 'asc');
		if ($limit) {
			$this->db->limit($limit, $start);
		}

		$query = $this->db->get();

		return $query;
	}

	public function get_total_tagihan_by_id_penjualan($id_departemen)
	{

		$this->db->select('sum(total_harga_barang) as total_tagihan')
			->from('gs')
			->join('detail_gs', 'detail_gs.id_gs = gs.id_gs', 'left')
			->where('gs.status_gs', 'Sudah Selesai')
			->where('gs.departemen', $id_departemen);

		$data = $this->db->get()->row_array();


		return $data['total_tagihan'];
	}

	public function get_total_tagihan_all()
	{

		$this->db->select('*')
			->from('gs')
			->join('detail_gs', 'detail_gs.id_gs = gs.id_gs', 'left')
			->where('gs.status_gs', 'Sudah Selesai');

		return $this->db->get()->row_array();
	}

	public function get_total_tagihan()
	{

		$this->db->select('sum(total_harga_barang) as total_tagihan')
			->from('gs')
			->join('detail_gs', 'detail_gs.id_gs = gs.id_gs', 'left')
			->where('gs.status_gs', 'Sudah Selesai');

		$data = $this->db->get()->row_array();


		return $data['total_tagihan'];
	}

	public function get_total_bayar()
	{

		$this->db->select_sum('bayar');
		$query = $this->db->get('penjualan_bayar');
		return $query->row()->bayar;
	}

	public function get_inv_by_id_penjualan($id_departemen = null, $limit = null, $start = null, $search = null)
	{
		$this->db->select('id_gs, SUM(total_harga_barang) as total_harga_barang_sum, MAX(CASE WHEN nama_barang IN ("PPn 10%", "PPn 11%") THEN "Ya" ELSE "Tidak" END) as ppn_status');

		$this->db->from('detail_gs');

		$this->db->group_by('id_gs');

		$subquery = $this->db->get_compiled_select();


		$this->db->select('gs.*, subquery.total_harga_barang_sum, subquery.ppn_status, po_gs.nama_user, po_gs.id_po_gs, po_gs.kontak_customer,gs.departemen,departemen.id_departemen,departemen.nama_departemen,departemen.phone_departemen,departemen.address_departemen');

		$this->db->from('gs');

		$this->db->join('po_gs', 'po_gs.id_po_gs=gs.id_po_gs', 'left');
		$this->db->join('departemen', 'departemen.id_departemen=gs.departemen', 'left');

		$this->db->join("($subquery) as subquery", 'subquery.id_gs = gs.id_gs', 'left');
		if ($id_departemen) {
			$this->db->where("departemen.id_departemen", $id_departemen);
		}
		$this->db->where('gs.status_gs', 'Sudah Selesai');


		if ($search) {

			$this->db->group_start();

			$this->db->like('gs.no_invoice', $search);

			$this->db->or_like('gs.tanggal', $search);
			$this->db->or_like('gs.jatuh_tempo', $search);
			$this->db->or_like('po_gs.nama_user', $search);

			$this->db->or_like('subquery.total_harga_barang_sum', $search);

			$this->db->group_end();
		}

		$this->db->order_by('jatuh_tempo_tagihan', 'desc');


		if ($limit) {

			$this->db->limit($limit, $start);
		}



		$this->db->order_by('gs.id_gs', 'DESC');

		$query = $this->db->get();

		return $query;
	}

	public function count_inv_by_id_penjualan($id_departemen, $search = null)
	{
		$this->db->select('id_gs, SUM(total_harga_barang) as total_harga_barang_sum, MAX(CASE WHEN nama_barang IN ("PPn 10%", "PPn 11%") THEN "Ya" ELSE "Tidak" END) as ppn_status');

		$this->db->from('detail_gs');

		$this->db->group_by('id_gs');

		$subquery = $this->db->get_compiled_select();


		$this->db->select('gs.*, subquery.total_harga_barang_sum, subquery.ppn_status, po_gs.nama_user, po_gs.id_po_gs, po_gs.kontak_customer,gs.departemen,departemen.id_departemen,departemen.nama_departemen,departemen.phone_departemen,departemen.address_departemen');

		$this->db->from('gs');

		$this->db->join('po_gs', 'po_gs.id_po_gs=gs.id_po_gs', 'left');
		$this->db->join('departemen', 'departemen.id_departemen=gs.departemen', 'left');

		$this->db->join("($subquery) as subquery", 'subquery.id_gs = gs.id_gs', 'left');
		$this->db->where("departemen.id_departemen", $id_departemen);
		$this->db->where('gs.status_gs', 'Sudah Selesai');


		if ($search) {

			$this->db->group_start();

			$this->db->like('gs.no_invoice', $search);

			$this->db->or_like('gs.tanggal', $search);
			$this->db->or_like('gs.jatuh_tempo', $search);
			$this->db->or_like('po_gs.nama_user', $search);

			$this->db->or_like('subquery.total_harga_barang_sum', $search);

			$this->db->group_end();
		}



		if ($limit) {

			$this->db->limit($limit, $start);
		}



		$this->db->order_by('gs.id_gs', 'DESC');

		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_sales_data($month, $year)
	{
		// Mengambil data penjualan berdasarkan bulan dan tahun
		$this->db->select("DATE_FORMAT(tanggal_detail_gs, '%d') as date, SUM(jumlah_barang) as total_sales");
		$this->db->from('detail_gs');
		$this->db->where('MONTH(tanggal_detail_gs)', $month);
		$this->db->where('YEAR(tanggal_detail_gs)', $year);
		$this->db->group_by('DATE(tanggal_detail_gs)');
		$query = $this->db->get();
		return $query->result();
	}
}
