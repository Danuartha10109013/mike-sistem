<?php

defined('BASEPATH') or exit('No direct script access allowed');



class M_order extends CI_Model
{



	public function count_all($search = null)

	{

		$this->db->select('id_gs, SUM(total_harga_barang) as total_harga_barang_sum, MAX(CASE WHEN nama_barang IN ("PPn 10%", "PPn 11%") THEN "Ya" ELSE "Tidak" END) as ppn_status');

		$this->db->from('detail_gs');

		$this->db->group_by('id_gs');

		$subquery = $this->db->get_compiled_select();



		$this->db->select('gs.*, subquery.total_harga_barang_sum, subquery.ppn_status, po_gs.nama_user, po_gs.id_po_gs, po_gs.kontak_customer');

		$this->db->from('gs');

		$this->db->join('po_gs', 'po_gs.id_po_gs=gs.id_po_gs', 'left');

		$this->db->join("($subquery) as subquery", 'subquery.id_gs = gs.id_gs', 'left');

		$this->db->where('gs.mode', 'sintesa');



		if ($search) {

			$this->db->group_start();

			$this->db->like('gs.no_invoice', $search);

			$this->db->or_like('gs.tanggal', $search);
			$this->db->or_like('gs.status_gs', $search);
			$this->db->or_like('gs.status_kirim', $search);
			$this->db->or_like('po_gs.nama_user', $search);
			$this->db->or_like('po_gs.kontak_customer', $search);

			$this->db->or_like('subquery.total_harga_barang_sum', $search);

			$this->db->or_like('subquery.ppn_status', $search);

			$this->db->group_end();
		}



		$query = $this->db->get();

		return $query->num_rows();
	}



	public function get_all($limit = null, $start = null, $search = null)

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

		$this->db->where('gs.mode', 'merlinstore');



		if ($search) {

			$this->db->group_start();

			$this->db->like('gs.no_invoice', $search);

			$this->db->or_like('gs.tanggal', $search);
			$this->db->or_like('gs.status_gs', $search);
			$this->db->or_like('gs.status_kirim', $search);
			$this->db->or_like('po_gs.nama_user', $search);
			$this->db->or_like('po_gs.kontak_customer', $search);

			$this->db->or_like('subquery.total_harga_barang_sum', $search);

			$this->db->or_like('subquery.ppn_status', $search);

			$this->db->group_end();
		}



		if ($limit) {

			$this->db->limit($limit, $start);
		}



		$this->db->order_by('gs.id_gs', 'DESC');

		$query = $this->db->get();

		return $query;
	}

	public function count_all_detail($search = null)
	{
		$this->db->select('*');
		$this->db->from('detail_gs');
		$this->db->join('gs', 'gs.id_gs=detail_gs.id_gs', 'left');
		$this->db->where('gs.status_gs', 'Sudah Selesai');

		if ($search) {
			$this->db->group_start();
			$this->db->like('gs.no_invoice', $search);
			$this->db->or_like('gs.tanggal', $search);
			$this->db->or_like('gs.status_gs', $search);
			$this->db->or_like('detail_gs.nama_barang', $search);
			$this->db->or_like('detail_gs.jumlah_barang', $search);
			$this->db->or_like('detail_gs.satuan_barang', $search);
			$this->db->or_like('detail_gs.harga_barang', $search);
			$this->db->or_like('detail_gs.total_harga_barang', $search);
			$this->db->group_end();
		}
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_all_detail($limit = null, $start = null, $search = null)
	{
		$this->db->select('*');
		$this->db->from('detail_gs');
		$this->db->join('gs', 'gs.id_gs=detail_gs.id_gs', 'left');
		$this->db->where('gs.status_gs', 'Sudah Selesai');

		if ($search) {
			$this->db->group_start();
			$this->db->like('gs.no_invoice', $search);
			$this->db->or_like('gs.tanggal', $search);
			$this->db->or_like('gs.status_gs', $search);
			$this->db->or_like('detail_gs.nama_barang', $search);
			$this->db->or_like('detail_gs.jumlah_barang', $search);
			$this->db->or_like('detail_gs.satuan_barang', $search);
			$this->db->or_like('detail_gs.harga_barang', $search);
			$this->db->or_like('detail_gs.total_harga_barang', $search);
			$this->db->group_end();
		}

		if ($limit) {
			$this->db->limit($limit, $start);
		}

		$this->db->order_by('detail_gs.id_detail_gs', 'DESC');
		$query = $this->db->get();
		return $query;
	}

	public function count_all_stok_keluar($search = null)
	{
		$this->db->select('*');
		$this->db->from('detail_gs');
		$this->db->join('barang', 'barang.id_barang=detail_gs.id_barang');

		if ($search) {
			$this->db->group_start();
			$this->db->like('detail_gs.tanggal_detail_gs', $search);
			$this->db->or_like('detail_gs.jumlah_barang', $search);
			$this->db->or_like('detail_gs.harga_beli', $search);
			$this->db->or_like('barang.nama_barang', $search);
			$this->db->or_like('barang.kode_barang', $search);
			$this->db->or_like('barang.satuan_barang', $search);
			$this->db->or_like('barang.harga_barang', $search);
			$this->db->or_like('barang.jenis_barang', $search);
			$this->db->group_end();
		}
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_all_stok_keluar($limit = null, $start = null, $search = null)
	{
		$this->db->select('*');
		$this->db->from('detail_gs');
		$this->db->join('barang', 'barang.id_barang=detail_gs.id_barang');

		if ($search) {
			$this->db->group_start();
			$this->db->like('detail_gs.tanggal_detail_gs', $search);
			$this->db->or_like('detail_gs.jumlah_barang', $search);
			$this->db->or_like('detail_gs.harga_beli', $search);
			$this->db->or_like('barang.nama_barang', $search);
			$this->db->or_like('barang.kode_barang', $search);
			$this->db->or_like('barang.satuan_barang', $search);
			$this->db->or_like('barang.harga_barang', $search);
			$this->db->or_like('barang.jenis_barang', $search);
			$this->db->group_end();
		}

		if ($limit) {
			$this->db->limit($limit, $start);
		}

		$this->db->order_by('detail_gs.tanggal_detail_gs', 'DESC');
		$query = $this->db->get();
		return $query;
	}

	public function report_stok_keluar($dari_tanggal, $sampai_tanggal)
	{
		$this->db->select('*,detail_gs.*');
		$this->db->from('detail_gs');
		$this->db->join('barang', 'barang.id_barang=detail_gs.id_barang', 'left');
		$this->db->where('detail_gs.tanggal_detail_gs >=', $dari_tanggal);
		$this->db->where('detail_gs.tanggal_detail_gs <=', $sampai_tanggal);
		$this->db->order_by('detail_gs.tanggal_detail_gs', 'DESC');
		$query = $this->db->get();
		return $query;
	}

	public function get($mode = null)

	{

		$this->db->select('*');

		$this->db->from('gs');

		if ($mode != null) {

			$this->db->where('mode', $mode);
		}

		$this->db->order_by('id_gs', 'DESC');

		return $this->db->get();
	}



	public function get_outstanding($id_user = null)
	{
		$this->db->select('*');
		$this->db->from('po_gs');
		$this->db->join('user', 'user.id_user=po_gs.id_user', 'left');
		if ($id_user != null) {
			$this->db->where('po_gs.id_user', $id_user);
		}
		$this->db->order_by('id_po_gs', 'ASC');
		return $this->db->get();
	}



	public function get_riwayat_outstanding()

	{
		$this->db->select('*');

		$this->db->from('riwayat_po_gs');

		$this->db->order_by('id_riwayat', 'DESC');

		return $this->db->get();
	}



	public function get_detail($id_gs)

	{

		// $this->db->select('
		// 	detail_po_gs.*,
		// 	departemen.nama_departemen,
		// 	sum(case when stok_barang.status_indent = "Normal" then detail_po_gs_stok.jumlah_stok_temp else 0 end) as stok_normal,
		// 	sum(case when stok_barang.status_indent = "Indent Masuk" then detail_po_gs_stok.jumlah_stok_temp else 0 end) as stok_indent
		// ');
		// $this->db->from('detail_po_gs');
		// $this->db->join('departemen', 'departemen.id_departemen = detail_po_gs.departemen', 'left');
		// $this->db->join('detail_po_gs_stok', 'detail_po_gs_stok.id_detail_po_gs = detail_po_gs.id_detail_po_gs', 'left');
		// $this->db->join('stok_barang', 'stok_barang.id_stok_barang = detail_po_gs_stok.id_stok_barang', 'left');
		
		// if ($id_po_gs != null) {
		// 	$this->db->where('detail_po_gs.id_po_gs', $id_po_gs);
		// }

		// $this->db->group_by('detail_po_gs.id_detail_po_gs'); // Pastikan grouping sesuai dengan kebutuhan
		// $this->db->order_by('detail_po_gs.id_detail_po_gs', 'ASC');
		
		// return $this->db->get();

		// $this->db->select('*,po_gs.nama_user,po_gs.kontak_customer');

		$this->db->select('
			detail_gs.*,
			departemen.*,
			po_gs.*,
			sum(case when stok_barang.status_indent = "Normal" then detail_gs_stok.jumlah_detail_gs_stok_temp else 0 end) as stok_normal,
			sum(case when stok_barang.status_indent = "Indent Masuk" then detail_gs_stok.jumlah_detail_gs_stok_temp else 0 end) as stok_indent
		');

		$this->db->from('detail_gs');

		$this->db->join('departemen', 'departemen.id_departemen=detail_gs.departemen', 'left');
		$this->db->join('gs', 'gs.id_gs=detail_gs.id_gs', 'left');
		$this->db->join('po_gs', 'po_gs.id_po_gs = gs.id_po_gs', 'left');
		$this->db->join('detail_gs_stok', 'detail_gs_stok.id_detail_po_gs = detail_gs.id_detail_po_gs', 'left');
		$this->db->join('stok_barang', 'stok_barang.id_stok_barang = detail_gs_stok.id_stok_barang', 'left');

		$this->db->where('detail_gs.id_gs', $id_gs);

		$this->db->group_by('detail_gs.id_detail_gs');
		$this->db->order_by('detail_gs.id_detail_gs', 'ASC');
		// $this->db->order_by('id_detail_gs', 'ASC');

		return $this->db->get();
	}

	public function get_detail_stok($id_detail_po_gs, $id_package)
	{
		$this->db->select('
			detail_po_gs_stok.*,
			stok_barang.*,
			barang.*,
			packages.package_name,
			detail_po_gs.total_harga_barang,
			sum(case when stok_barang.status_indent = "Normal" then detail_po_gs_stok.jumlah_stok_temp else 0 end) as stok_normal,
			sum(case when stok_barang.status_indent = "Indent Masuk" then detail_po_gs_stok.jumlah_stok_temp else 0 end) as stok_indent,
			sum(detail_po_gs_stok.jumlah_stok_temp) as total_stok
		');

		$this->db->from('detail_po_gs_stok');
		$this->db->join('detail_po_gs', 'detail_po_gs.id_detail_po_gs=detail_po_gs_stok.id_detail_po_gs', 'left');
		$this->db->join('packages', 'packages.id_package=detail_po_gs.id_package', 'left');
		$this->db->join('stok_barang', 'stok_barang.id_stok_barang = detail_po_gs_stok.id_stok_barang', 'left');
		$this->db->join('barang', 'barang.id_barang = stok_barang.id_barang', 'left');

		$this->db->where('detail_po_gs_stok.id_detail_po_gs', $id_detail_po_gs);
		$this->db->where('detail_po_gs.id_package', $id_package);

		$this->db->group_by('stok_barang.id_barang');
		$this->db->order_by('detail_po_gs_stok.id_detail_po_gs_stok', 'ASC');

		return $this->db->get();
	}

	public function get_detail_stok_invoice($id_detail_gs, $id_package)
	{
		$this->db->select('
			detail_gs_stok.*,
			stok_barang.*,
			barang.*,
			packages.package_name,
			detail_gs.total_harga_barang,
			sum(case when stok_barang.status_indent = "Normal" then detail_gs_stok.jumlah_detail_gs_stok_temp else 0 end) as stok_normal,
			sum(case when stok_barang.status_indent = "Indent Masuk" then detail_gs_stok.jumlah_detail_gs_stok_temp else 0 end) as stok_indent,
			sum(detail_gs_stok.jumlah_detail_gs_stok_temp) as total_stok
		');

		$this->db->from('detail_gs_stok');
		$this->db->join('detail_gs', 'detail_gs.id_detail_gs=detail_gs_stok.id_detail_gs', 'left');
		$this->db->join('packages', 'packages.id_package=detail_gs.id_package', 'left');
		$this->db->join('stok_barang', 'stok_barang.id_stok_barang = detail_gs_stok.id_stok_barang', 'left');
		$this->db->join('barang', 'barang.id_barang = stok_barang.id_barang', 'left');

		$this->db->where('detail_gs_stok.id_detail_gs', $id_detail_gs);
		$this->db->where('detail_gs.id_package', $id_package);

		$this->db->group_by('stok_barang.id_barang');
		$this->db->order_by('detail_gs_stok.id_detail_gs_stok', 'ASC');

		return $this->db->get();
	}



	// public function get_detail_outstanding($id_po_gs = null)
	// {
	// 	$this->db->select('*');
	// 	$this->db->from('detail_po_gs');
	// 	$this->db->join('departemen', 'departemen.id_departemen=detail_po_gs.departemen', 'left');
	// 	if ($id_po_gs != null) {
	// 		$this->db->where('id_po_gs', $id_po_gs);
	// 	}

	// 	$this->db->order_by('id_detail_po_gs', 'ASC');
	// 	return $this->db->get();
	// }
 
	public function get_detail_outstanding($id_po_gs = null)
	{
		$this->db->select('
			detail_po_gs.*,
			departemen.nama_departemen,
			sum(case when stok_barang.status_indent = "Normal" then detail_po_gs_stok.jumlah_stok_temp else 0 end) as stok_normal,
			sum(case when stok_barang.status_indent = "Indent Masuk" then detail_po_gs_stok.jumlah_stok_temp else 0 end) as stok_indent
		');
		$this->db->from('detail_po_gs');
		$this->db->join('departemen', 'departemen.id_departemen = detail_po_gs.departemen', 'left');
		$this->db->join('detail_po_gs_stok', 'detail_po_gs_stok.id_detail_po_gs = detail_po_gs.id_detail_po_gs', 'left');
		$this->db->join('stok_barang', 'stok_barang.id_stok_barang = detail_po_gs_stok.id_stok_barang', 'left');
		
		if ($id_po_gs != null) {
			$this->db->where('detail_po_gs.id_po_gs', $id_po_gs);
		}

		$this->db->group_by('detail_po_gs.id_detail_po_gs'); // Pastikan grouping sesuai dengan kebutuhan
		$this->db->order_by('detail_po_gs.id_detail_po_gs', 'ASC');
		
		return $this->db->get();
	}



	public function insert($data)

	{

		$this->db->insert('gs', $data);
	}



	public function insert_outstanding($data)

	{

		$this->db->insert('po_gs', $data);
	}



	public function insert_detail($data)

	{

		$this->db->insert('detail_gs', $data);
	}



	public function insert_detail_outstanding($data)

	{

		$this->db->insert('detail_po_gs', $data);
	}



	public function get_by_id($id_gs)

	{

		return $this->db->get_where('gs', ['id_gs' => $id_gs])->row_array();
	}



	public function get_outstanding_by_id($id_po_gs)
	{
		return $this->db->select('po_gs.*, departemen.*')->join('departemen', 'departemen.id_departemen = po_gs.departemen')->get_where('po_gs', ['id_po_gs' => $id_po_gs])->row_array();
	}



	public function get_detail_outstanding_by_id($id_detail_po_gs)

	{

		return $this->db->get_where('detail_po_gs', ['id_detail_po_gs' => $id_detail_po_gs])->row_array();
	}



	public function update($data)

	{

		$this->db->where('id_gs', $data['id_gs']);

		if ($this->db->update('gs', $data)) {
			return true;
		} else {
			return false;
		}
	}



	public function update_outstanding($data)

	{

		$this->db->where('id_po_gs', $data['id_po_gs']);

		$this->db->update('po_gs', $data);
	}



	public function update_detail_outstanding($data)

	{

		$this->db->where('id_detail_po_gs', $data['id_detail_po_gs']);

		$this->db->update('detail_po_gs', $data);
	}



	public function delete($id_gs)

	{

		$this->db->delete('gs', ['id_gs' => $id_gs]);
	}



	public function get_detail_by_id($id_detail_gs)

	{

		return $this->db->get_where('detail_gs', ['id_detail_gs' => $id_detail_gs])->row_array();
	}



	public function update_detail($data)

	{

		$this->db->where('id_detail_gs', $data['id_detail_gs']);

		$this->db->update('detail_gs', $data);
	}



	public function delete_detail($id_detail_gs)

	{

		$this->db->delete('detail_gs', ['id_detail_gs' => $id_detail_gs]);
	}



	public function delete_outstanding($id_po_gs)

	{

		$this->db->delete('po_gs', ['id_po_gs' => $id_po_gs]);
	}



	public function delete_detail_outstanding($id_detail_po_gs)

	{

		$this->db->delete('detail_po_gs', ['id_detail_po_gs' => $id_detail_po_gs]);
	}



	public function get_bc()

	{

		$this->db->select('*');

		$this->db->from('bc_gs');

		$this->db->order_by('id_bc_gs', 'DESC');

		return $this->db->get();
	}



	public function insert_bc($data)

	{

		$this->db->insert('bc_gs', $data);
	}



	public function update_bc($data)

	{

		$this->db->where('id_bc_gs', $data['id_bc_gs']);

		$this->db->update('bc_gs', $data);
	}



	public function get_bc_by_id($id_bc_gs)

	{

		return $this->db->get_where('bc_gs', ['id_bc_gs' => $id_bc_gs])->row_array();
	}



	public function delete_bc($id_bc_gs)

	{

		$this->db->delete('bc_gs', ['id_bc_gs' => $id_bc_gs]);
	}



	public function get_detail_bc($id_bc_gs)

	{

		$this->db->select('*');

		$this->db->from('detail_bc_gs');

		$this->db->where('id_bc_gs', $id_bc_gs);

		$this->db->order_by('id_detail_bc_gs', 'ASC');

		return $this->db->get();
	}



	public function insert_bc_detail($data)

	{

		$this->db->insert('detail_bc_gs', $data);
	}



	public function get_bc_detail_by_id($id_detail_bc_gs)

	{

		return $this->db->get_where('detail_bc_gs', ['id_detail_bc_gs' => $id_detail_bc_gs])->row_array();
	}



	public function update_bc_detail($data)

	{

		$this->db->where('id_detail_bc_gs', $data['id_detail_bc_gs']);

		$this->db->update('detail_bc_gs', $data);
	}



	public function delete_bc_detail($id_detail_bc_gs)

	{

		$this->db->delete('detail_bc_gs', ['id_detail_bc_gs' => $id_detail_bc_gs]);
	}



	public function get_invoice_by_range($dari_tanggal, $sampai_tanggal)

	{

		$this->db->select('*');

		$this->db->from('gs');

		$this->db->join('detail_gs', 'gs.id_gs=detail_gs.id_gs');

		$this->db->where('gs.tanggal >=', $dari_tanggal);

		$this->db->where('gs.tanggal <=', $sampai_tanggal);

		return $this->db->get();
	}



	public function get_po_by_range($dari_tanggal, $sampai_tanggal)

	{

		$this->db->select('*');

		$this->db->from('po_gs');

		$this->db->join('detail_po_gs', 'po_gs.id_po_gs=detail_po_gs.id_po_gs');

		// $this->db->where('po_gs.tanggal >=', $dari_tanggal);

		// $this->db->where('po_gs.tanggal <=', $sampai_tanggal);

		return $this->db->get();
	}



	public function get_po_by_range_filter($dari_tanggal, $sampai_tanggal)

	{

		$this->db->select('*');

		$this->db->from('po_gs');

		$this->db->join('detail_po_gs', 'po_gs.id_po_gs=detail_po_gs.id_po_gs');

		$this->db->where('po_gs.tanggal >=', $dari_tanggal);

		$this->db->where('po_gs.tanggal <=', $sampai_tanggal);

		return $this->db->get();
	}



	public function get_omset_departemen($dariTanggal, $sampaiTanggal, $departemen)

	{

		return $this->db->query(
			"SELECT po_gs.no_po as no_po, po_gs.tanggal as tanggal, SUM(detail_po_gs.total_harga_barang) as total, departemen.nama_departemen as departemen 

			FROM po_gs 

			JOIN detail_po_gs ON(po_gs.id_po_gs=detail_po_gs.id_po_gs)

			JOIN departemen ON(detail_po_gs.departemen=departemen.id_departemen)

			WHERE detail_po_gs.departemen = '$departemen' AND po_gs.tanggal >= '$dariTanggal' AND po_gs.tanggal <= '$sampaiTanggal'

			GROUP by no_po, departemen

			UNION

			SELECT po_gs.no_po as no_po, po_gs.tanggal as tanggal, SUM(riwayat_po_gs.total_harga_barang) as total, departemen.nama_departemen as departemen 

			FROM po_gs 

			JOIN riwayat_po_gs ON(po_gs.no_po=riwayat_po_gs.no_po)

			JOIN departemen ON(riwayat_po_gs.departemen=departemen.id_departemen)

			WHERE riwayat_po_gs.departemen = '$departemen' AND po_gs.tanggal >= '$dariTanggal' AND po_gs.tanggal <= '$sampaiTanggal'

			GROUP by no_po, departemen

			ORDER BY tanggal DESC"

		);
	}



	public function get_omset_user($dari_tanggal, $sampai_tanggal)

	{

		return $this->db->query(
			"SELECT po_gs.no_po as no_po, po_gs.tanggal as tanggal, SUM(detail_po_gs.total_harga_barang) as total, user.nama as nama 
			FROM po_gs 
			JOIN detail_po_gs ON(po_gs.id_po_gs=detail_po_gs.id_po_gs) 
			JOIN user ON(po_gs.id_user=user.id_user)
			WHERE po_gs.tanggal >= '$dari_tanggal' AND po_gs.tanggal <= '$sampai_tanggal'
			GROUP by no_po
			UNION
			SELECT po_gs.no_po as no_po, po_gs.tanggal as tanggal, SUM(riwayat_po_gs.total_harga_barang) as total, user.nama as nama 
			FROM po_gs 
			JOIN riwayat_po_gs ON(riwayat_po_gs.no_po=po_gs.no_po) 
			JOIN user ON(po_gs.id_user=user.id_user)
			WHERE po_gs.tanggal >= '$dari_tanggal' AND po_gs.tanggal <= '$sampai_tanggal'
			GROUP by no_po
			ORDER by tanggal DESC"

		);
	}



	public function get_user()

	{

		$this->db->select('user.id_user, user.nama');

		$this->db->distinct();

		$this->db->from('po_gs');

		$this->db->join('user', 'user.id_user = po_gs.id_user', 'left');

		$this->db->order_by('id_po_gs', 'ASC');

		return $this->db->get();
	}



	public function get_departemen()

	{

		$this->db->select('departemen.id_departemen, departemen.nama_departemen');

		$this->db->distinct();

		$this->db->from('detail_po_gs');

		$this->db->join('departemen', 'departemen.id_departemen = detail_po_gs.departemen', 'left');

		$this->db->order_by('id_detail_po_gs', 'ASC');

		return $this->db->get();
	}


	public function get_pendapatan_finance($dari_tanggal = null, $sampai_tanggal = null)

	{


		$this->db->select('*');
		$this->db->from('barang');
		$barang =  $this->db->get()->result_array();

		foreach ($barang as $key => $br) {


			$this->db->select('*,(jumlah*harga_beli) as harga_beli_total');
			$this->db->from('barang_masuk');
			$this->db->where('id_barang', $br['id_barang']);

			$this->db->where('barang_masuk.tanggal_masuk >=', $dari_tanggal);
			$this->db->where('barang_masuk.tanggal_masuk <=', $sampai_tanggal);
			$this->db->order_by('barang_masuk.tanggal_masuk', 'DESC');
			$barang[$key]['masuk'] = $this->db->get()->result_array();

			// var_dump($this->db->last_query());
			// die;
			$this->db->select('*');
			$this->db->from('detail_gs');
			$this->db->join('barang', 'barang.id_barang = detail_gs.id_barang', 'left');
			$this->db->join('packages', 'packages.id_package = detail_gs.id_package', 'left');
			$this->db->where("(detail_gs.id_barang = '" . $br['id_barang'] . "' OR FIND_IN_SET('" . $br['id_barang'] . "', packages.item_package) > 0)");
			$this->db->where('detail_gs.tanggal_detail_gs >=', $dari_tanggal);
			$this->db->where('detail_gs.tanggal_detail_gs <=', $sampai_tanggal);
			$this->db->order_by('detail_gs.tanggal_detail_gs', 'DESC');
			$barang[$key]['keluar'] =  $this->db->get()->result_array();

			// var_dump($this->db->last_query());
			// die;

			foreach ($barang[$key]['keluar'] as $key2 => $value) {
				$barang[$key]['keluar'][$key2]['id_barang'] =  $br['id_barang'];
			}
		}

		return $barang;
		// $this->db->select('*');
		// $this->db->from('barang');
		// $this->db->join('detail_gs', 'detail_gs.id_barang=detail_gs.id_barang', 'left');
		// $this->db->join('barang_masuk', 'barang_masuk.id_barang=detail_gs.id_barang', 'left');
		// $this->db->where('detail_gs.tanggal_detail_gs >=', $dari_tanggal);
		// $this->db->where('detail_gs.tanggal_detail_gs <=', $sampai_tanggal);
		// $query = $this->db->get();
	}
}
