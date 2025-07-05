<?php

defined('BASEPATH') or exit('No direct script access allowed');



class M_barang extends CI_Model
{



	private $table	= 'barang';



	public function count_data($search = null, $jenis_barang = null)

	{

		$this->db->from($this->table);

		// if ($jenis_barang === 'Fabrikasi') {

		// 	$this->db->where('jenis_barang', 'Fabrikasi');
		// } else if ($jenis_barang === 'Cleaning Supply') {

		// 	$this->db->where('jenis_barang', 'Cleaning Supply');
		// } else if ($jenis_barang === 'General Supply') {

		// 	$this->db->where('jenis_barang', 'General Supply');
		// }

		if ($jenis_barang !== null && $jenis_barang !== 'All') {

			$this->db->where('jenis_barang', $jenis_barang);
		}

		if ($search !== null && $search !== 'all') {

			$this->db->group_start();

			$this->db->or_like('nama_barang', $search);

			$this->db->or_like('kode_barang', $search);

			$this->db->or_like('satuan_barang', $search);

			$this->db->or_like('harga_barang', $search);

			$this->db->or_like('poin_barang', $search);

			$this->db->or_like('jenis_barang', $search);

			$this->db->group_end();
		}

		return $this->db->count_all_results();
	}



	public function get_barang2($limit = null, $start = null, $search = null, $jenis_barang = null)

	{

		$this->db->select('*');

		$this->db->from($this->table);

		// if ($jenis_barang === 'Fabrikasi') {

		// 	$this->db->where('jenis_barang', 'Fabrikasi');
		// } else if ($jenis_barang === 'Cleaning Supply') {

		// 	$this->db->where('jenis_barang', 'Cleaning Supply');
		// } else if ($jenis_barang === 'General Supply') {

		// 	$this->db->where('jenis_barang', 'General Supply');
		// }

		if ($jenis_barang !== null && $jenis_barang !== 'All') {

			$this->db->where('jenis_barang', $jenis_barang);
		}

		if ($search !== null && $search !== 'all') {

			$this->db->group_start();

			$this->db->or_like('nama_barang', $search);

			$this->db->or_like('kode_barang', $search);

			$this->db->or_like('satuan_barang', $search);

			$this->db->or_like('harga_barang', $search);

			$this->db->or_like('poin_barang', $search);

			$this->db->or_like('jenis_barang', $search);

			$this->db->group_end();
		}

		$this->db->limit($limit, $start);

		$this->db->order_by('nama_barang', 'ASC');

		return $this->db->get();
	}



	public function get_barang()

	{

		$this->db->select('*');

		$this->db->from('barang');

		$this->db->order_by('nama_barang', 'ASC');

		return $this->db->get();
	}

	public function count_all_barang_masuk($search = null)
	{
		$this->db->select('*');
		$this->db->from('barang_masuk');
		$this->db->join('barang', 'barang.id_barang=barang_masuk.id_barang');

		if ($search) {
			$this->db->group_start();
			$this->db->like('barang_masuk.tanggal_masuk', $search);
			$this->db->or_like('barang_masuk.jumlah', $search);
			$this->db->or_like('barang_masuk.harga_beli', $search);
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

	public function get_all_barang_masuk($limit = null, $start = null, $search = null)
	{
		$this->db->select('*');
		$this->db->from('barang_masuk');
		$this->db->join('barang', 'barang.id_barang=barang_masuk.id_barang');

		if ($search) {
			$this->db->group_start();
			$this->db->like('barang_masuk.tanggal_masuk', $search);
			$this->db->or_like('barang_masuk.jumlah', $search);
			$this->db->or_like('barang_masuk.harga_beli', $search);
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

		$this->db->order_by('barang_masuk.tanggal_masuk', 'DESC');
		$query = $this->db->get();
		return $query;
	}



	public function get_barang_fabrikasi()

	{

		$this->db->select('*');

		$this->db->from('barang');

		$this->db->where('jenis_barang', 'Fabrikasi');

		$this->db->order_by('nama_barang', 'ASC');

		return $this->db->get();
	}



	public function get_barang_supplier()

	{

		$this->db->select('*');

		$this->db->from('barang');

		$this->db->where('jenis_barang', 'Cleaning Supply');

		$this->db->order_by('nama_barang', 'ASC');

		return $this->db->get();
	}



	public function get_barang_general()

	{

		$this->db->select('*');

		$this->db->from('barang');

		$this->db->where('jenis_barang', 'General Supply');

		$this->db->order_by('nama_barang', 'ASC');

		return $this->db->get();
	}

	public function get_barang_new()
	{
		$this->db->select('*');
		$this->db->from('barang');
		$this->db->order_by('nama_barang', 'ASC');
		return $this->db->get();
	}



	public function get_barang_general_supplier()

	{

		$this->db->select('*');

		$this->db->from('barang');

		$this->db->where('jenis_barang', 'General Supply');

		$this->db->order_by('nama_barang', 'ASC');

		return $this->db->get();
	}



	public function get_stok_barang($jenis_barang = null)

	{

		// $this->db->select('*');

		// $this->db->select_sum('stok');

		// $this->db->from('stok_barang');

		// $this->db->join('barang', 'barang.id_barang=stok_barang.id_barang');

		// if ($jenis_barang != null) {

		// 	$this->db->where('barang.jenis_barang', $jenis_barang);
		// }

		// $this->db->group_by(['stok_barang.id_barang', 'stok_barang.status_indent']);

		// $this->db->order_by('barang.nama_barang', 'ASC');
		$this->db->select('*');

		$this->db->select_sum('stok');
		$this->db->select('SUM(CASE WHEN status_indent = "Normal" THEN stok ELSE 0 END) AS stok_normal');
		$this->db->select('SUM(CASE WHEN status_indent = "Indent Masuk" THEN stok ELSE 0 END) AS stok_indent');

		$this->db->from('stok_barang');

		$this->db->join('barang', 'barang.id_barang=stok_barang.id_barang');

		if ($jenis_barang != null) {

			$this->db->where('barang.jenis_barang', $jenis_barang);
		}

		$this->db->group_by(['stok_barang.id_barang']);

		$this->db->order_by('barang.nama_barang', 'ASC');

		return $this->db->get();
	}

	public function get_stok_barang3($jenis_barang = null)

	{

		$this->db->select('*');

		$this->db->select_sum('stok');

		$this->db->from('stok_barang');

		$this->db->join('barang', 'barang.id_barang=stok_barang.id_barang');

		if ($jenis_barang != null) {

			$this->db->where('barang.jenis_barang', $jenis_barang);
		}

		$this->db->group_by(['stok_barang.id_barang', 'stok_barang.status_indent']);

		$this->db->order_by('barang.nama_barang', 'ASC');


		return $this->db->get();
	}

	public function get_stok_barang2($jenis_barang = null)

	{

		$this->db->select('*');

		$this->db->select_sum('stok');

		$this->db->from('stok_barang');

		$this->db->join('barang', 'barang.id_barang=stok_barang.id_barang', 'left');

		if ($jenis_barang != null) {

			$this->db->where('barang.jenis_barang', $jenis_barang);
		}

		$this->db->group_by('stok_barang.id_barang');

		$this->db->order_by('barang.nama_barang', 'ASC');

		return $this->db->get();
	}



	public function get_all_stok_barang()

	{

		$this->db->select('*');

		$this->db->from('stok_barang');

		$this->db->join('barang', 'barang.id_barang=stok_barang.id_barang');

		$this->db->order_by('barang.nama_barang', 'ASC');

		return $this->db->get();
	}



	public function get_available_stok_barang()

	{

		$this->db->select('*');

		$this->db->from('stok_barang');

		$this->db->join('barang', 'barang.id_barang=stok_barang.id_barang');

		$this->db->where('stok_barang.stok >', 0);

		$this->db->order_by('barang.nama_barang', 'ASC');

		return $this->db->get();
	}



	public function get_barang_masuk($jenis_barang = null)
	{
		$this->db->select('*');
		$this->db->from('barang_masuk');
		$this->db->join('barang', 'barang.id_barang=barang_masuk.id_barang');
		if ($jenis_barang != null) {
			$this->db->where('barang.jenis_barang', $jenis_barang);
		}
		$this->db->order_by('barang_masuk.tanggal_masuk', 'DESC');
		return $this->db->get();
	}

	public function report_barang_masuk($dari_tanggal, $sampai_tanggal)
	{
		$this->db->select('*');
		$this->db->from('barang_masuk');
		$this->db->join('barang', 'barang.id_barang=barang_masuk.id_barang');
		$this->db->where('barang_masuk.tanggal_masuk >=', $dari_tanggal);
		$this->db->where('barang_masuk.tanggal_masuk <=', $sampai_tanggal);
		$this->db->order_by('barang_masuk.tanggal_masuk', 'DESC');
		return $this->db->get();
	}



	public function get_stok_opname($jenis_barang = null)

	{

		$this->db->select('*');

		$this->db->from('stok_opname');

		$this->db->join('stok_barang', 'stok_opname.id_stok_barang=stok_barang.id_stok_barang');

		$this->db->join('barang', 'barang.id_barang=stok_barang.id_barang');

		if ($jenis_barang != null) {

			$this->db->where('barang.jenis_barang', $jenis_barang);
		}

		$this->db->order_by('barang.nama_barang', 'ASC');

		return $this->db->get();
	}



	public function get_nilai_asset($jenis_barang = null)
	{
		if ($jenis_barang != null) {
			return $this->db->query("SELECT SUM(stok_barang.stok*stok_barang.harga_beli) AS nilai_asset FROM stok_barang JOIN barang ON (barang.id_barang=stok_barang.id_barang) WHERE barang.jenis_barang = '$jenis_barang'")->row_array();
		}
		return $this->db->query('SELECT SUM(stok*harga_beli) AS nilai_asset FROM stok_barang')->row_array();
	}

	public function get_detail($id_barang)
	{
		return $this->db->get_where('barang', ['id_barang' => $id_barang])->row_array();
	}

	public function get_stok($id_barang)
	{
		return $this->db->get_where('stok_barang', ['id_barang' => $id_barang])->result_array();
	}

	public function get_tracking_barang_by_id($id_barang)
	{
		return $this->db->query("
SELECT tanggal_masuk as tanggal, jumlah as masuk, 0 as keluar, '' as ref , '' as nama_user, '' as driver  , '' as no_po  
FROM barang_masuk 
WHERE id_barang = '" . $id_barang . "'

UNION SELECT gs.tanggal as tanggal, 0 as masuk, detail_gs.jumlah_barang as keluar, gs.no_invoice as ref, po_gs.nama_user as nama_user, gs.driver as driver, po_gs.no_po as no_po
FROM gs, detail_gs, packages, po_gs
WHERE (gs.id_gs = detail_gs.id_gs OR detail_gs.id_package = packages.id_package) AND po_gs.id_po_gs = gs.id_po_gs AND (detail_gs.id_barang='" . $id_barang . "' OR FIND_IN_SET('" . $id_barang . "', packages.item_package) > 0)
ORDER BY tanggal DESC;
		");


		// 		SELECT tanggal_masuk as tanggal, jumlah as masuk, 0 as keluar, '' as ref ,'' as driver ,'' as nama_user, '' as departemen 
		// FROM barang_masuk 
		// WHERE id_barang = ' 2 '
		// UNION SELECT gs.tanggal as tanggal,  0 as masuk, detail_gs.jumlah_barang as keluar, gs.no_invoice as ref, gs.driver as driver, po_gs.nama_user, detail_gs.departemen as departemen
		// FROM gs, detail_gs, packages, departemen, po_gs
		// WHERE gs.id_gs = detail_gs.id_gs AND packages.item_package = detail_gs.id_barang AND (detail_gs.id_barang='2' OR packages.item_package IN (1)) ORDER BY tanggal DESC;

		// return $this->db->query("

		// 	SELECT tanggal_masuk as tanggal, jumlah as masuk, 0 as keluar, '' as ref FROM barang_masuk WHERE id_barang='$id_barang'

		// 	UNION

		// 	SELECT gs.tanggal as tanggal, 0 as masuk, detail_gs.jumlah_barang as keluar, gs.no_invoice as ref FROM gs, detail_gs WHERE gs.id_gs = detail_gs.id_gs AND detail_gs.id_barang='$id_barang' 

		// 	ORDER BY tanggal DESC

		// ");

	}



	public function insert_barang($data)

	{

		$this->db->insert('barang', $data);
	}



	public function insert_barang_masuk($data)

	{

		$this->db->insert('barang_masuk', $data);
	}



	public function insert_stok_barang($data)

	{

		$this->db->insert('stok_barang', $data);
	}



	public function insert_stok_opname($data)

	{

		$this->db->insert('stok_opname', $data);
	}



	public function get_barang_by_id($id_barang)

	{

		return $this->db->get_where('barang', ['id_barang' => $id_barang])->row_array();
	}



	public function get_stok_barang_by_id($id_barang)
	{
		$this->db->select('*');
		$this->db->select_sum('stok');
		$this->db->from('stok_barang');
		$this->db->join('barang', 'barang.id_barang=stok_barang.id_barang');
		$this->db->where('stok_barang.id_barang', $id_barang);
		// $this->db->group_by('stok_barang.id_barang');
		$this->db->group_by(['stok_barang.id_barang', 'stok_barang.status_indent']);
		$this->db->order_by('stok_barang.id_barang', 'DESC');
		return $this->db->get()->row_array();
	}

	public function get_stok_barang_by_id_status($id_barang, $status_indent = null)
	{
		$this->db->select('*');
		$this->db->select_sum('stok');
		$this->db->select('SUM(CASE WHEN status_indent = "Normal" THEN stok ELSE 0 END) AS stok_normal');
		$this->db->select('SUM(CASE WHEN status_indent = "Indent Masuk" THEN stok ELSE 0 END) AS stok_indent');
		$this->db->from('stok_barang');
		$this->db->join('barang', 'barang.id_barang=stok_barang.id_barang');
		$this->db->where('stok_barang.id_barang', $id_barang);
		if ($status_indent != null) {

			$this->db->where('stok_barang.status_indent', $status_indent);
		}
		// $this->db->where('stok_barang.status_indent', $status_indent);
		// $this->db->group_by('stok_barang.id_barang');
		// $this->db->group_by(['stok_barang.id_barang', 'stok_barang.status_indent']);
		$this->db->group_by(['stok_barang.id_barang']);
		$this->db->order_by('stok_barang.id_barang', 'DESC');
		return $this->db->get()->row_array();
	}



	public function get_barang_masuk_by_id($id_barang_masuk)

	{

		return $this->db->get_where('barang_masuk', ['id_barang_masuk' => $id_barang_masuk])->row_array();
	}



	public function get_all_stok_barang_by_id($id_barang)

	{

		$this->db->select('*');

		$this->db->from('stok_barang');

		$this->db->join('barang', 'barang.id_barang=stok_barang.id_barang');

		$this->db->where('stok_barang.id_barang', $id_barang);

		$this->db->where_not_in('stok_barang.stok', 0);

		$this->db->order_by('stok_barang.id_barang', 'DESC');

		return $this->db->get();
	}

	public function get_all_stok_barang_by_id_status($id_barang, $status_indent = null)

	{

		$this->db->select('*');

		$this->db->from('stok_barang');

		$this->db->join('barang', 'barang.id_barang=stok_barang.id_barang', 'left');

		$this->db->where('stok_barang.id_barang', $id_barang);
		if ($status_indent != null) {

			$this->db->where('stok_barang.status_indent', $status_indent);
		}
		// $this->db->where('stok_barang.status_indent', $status_indent);

		// $this->db->where_not_in('stok_barang.stok', 0);

		$this->db->order_by('stok_barang.id_barang', 'DESC');

		return $this->db->get();
	}



	public function get_stok_opname_by_id($id_stok_opname)

	{

		return $this->db->get_where('stok_opname', ['id_stok_opname' => $id_stok_opname])->row_array();
	}


	public function get_stok_barang_by_id_stok($id_stok_barang)

	{

		return $this->db->get_where('stok_barang', ['id_stok_barang' => $id_stok_barang])->row_array();
	}


	public function update_barang($data)

	{

		$this->db->where('id_barang', $data['id_barang']);

		$this->db->update('barang', $data);
	}



	public function update_barang_masuk($data)

	{

		$this->db->where('id_barang_masuk', $data['id_barang_masuk']);

		$this->db->update('barang_masuk', $data);
	}



	public function update_stok_barang($data)

	{

		if (isset($data['id_stok_barang']) && $data['id_stok_barang'] != NULL) {

			$this->db->where('id_stok_barang', $data['id_stok_barang']);

			$this->db->update('stok_barang', $data);
		} else {
			$this->db->insert('stok_barang', $data);
		}
	}



	public function update_stok_opname($data)

	{

		$this->db->where('id_stok_opname', $data['id_stok_opname']);

		$this->db->update('stok_opname', $data);
	}



	public function delete_barang($id_barang)

	{

		$this->db->delete('barang', ['id_barang' => $id_barang]);
	}



	public function delete_barang_masuk($id_barang_masuk)

	{

		$this->db->delete('barang_masuk', ['id_barang_masuk' => $id_barang_masuk]);
	}



	public function delete_stok_opname($id_stok_opname)

	{

		$this->db->delete('stok_opname', ['id_stok_opname' => $id_stok_opname]);
	}

	public function get_jenis_barang()

	{

		return [
			'Skincare',
			'Parfum',
			'Lipstick',
			'Lipbalm',
		];
	}
}
