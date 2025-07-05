<?php

defined('BASEPATH') or exit('No direct script access allowed');



class M_package extends CI_Model
{



	private $table	= 'packages';

	public function get_package($limit = null, $start = null, $search = null, $jenis_barang = null)

	{



		$this->db->select('*');

		$this->db->from($this->table);


		$this->db->limit($limit, $start);

		$this->db->order_by('package_name', 'ASC');

		$d = $this->db->get()->result_array();


		foreach ($d as $key => $d1) {
			$d[$key]['data_item'] = [];
			$d[$key]['data_item_text'] = [];

			$itm = explode(',', $d1['item_package']);
			foreach ($itm as $key2 => $d2) {

				$this->db->select('*');

				$this->db->join('stok_barang', 'stok_barang.id_barang = barang.id_barang', 'left');


				$this->db->where('barang.id_barang', $d2);
				$d3 = $this->db->get('barang')->result_array();

				if ($d3) {
					$d[$key]['data_item'][] = $d3;
					$d[$key]['data_item_text'][] .=  '<b>' . $d3[0]['nama_barang'] . '</b> - STOK: ' .  $d3[0]['stok'] . '<br>';
				}
			}
		}

		return $d;
		// if ($jenis_barang !== null && $jenis_barang !== 'All') {

		// 	$this->db->where('jenis_barang', $jenis_barang);
		// }

		// if ($search !== null && $search !== 'all') {

		// 	$this->db->group_start();

		// 	$this->db->or_like('nama_barang', $search);

		// 	$this->db->or_like('kode_barang', $search);

		// 	$this->db->or_like('satuan_barang', $search);

		// 	$this->db->or_like('harga_barang', $search);

		// 	$this->db->or_like('jenis_barang', $search);

		// 	$this->db->group_end();
		// }

		// $this->db->limit($limit, $start);

		// $this->db->order_by('package_name', 'ASC');

		// return $this->db->get();
	}


	public function count_data($search = null, $jenis_barang = null)

	{


		$this->db->select('*');

		$this->db->from($this->table);

		$this->db->order_by('package_name', 'ASC');

		return $this->db->count_all_results();

		// $this->db->from($this->table);

		// if ($jenis_barang === 'Fabrikasi') {

		// 	$this->db->where('jenis_barang', 'Fabrikasi');
		// } else if ($jenis_barang === 'Cleaning Supply') {

		// 	$this->db->where('jenis_barang', 'Cleaning Supply');
		// } else if ($jenis_barang === 'General Supply') {

		// 	$this->db->where('jenis_barang', 'General Supply');
		// }

		// if ($search !== null && $search !== 'all') {

		// 	$this->db->group_start();

		// 	$this->db->or_like('nama_barang', $search);

		// 	$this->db->or_like('kode_barang', $search);

		// 	$this->db->or_like('satuan_barang', $search);

		// 	$this->db->or_like('harga_barang', $search);

		// 	$this->db->or_like('jenis_barang', $search);

		// 	$this->db->group_end();
		// }

		// return $this->db->count_all_results();
	}
	public function get_package_by_id($id)
	{
		$this->db->select('*');

		$this->db->from($this->table);


		$this->db->where('id_package', $id);

		return $this->db->get()->row_array();
	}


	public function insert_package($data)

	{

		$this->db->insert('packages', $data);
	}

	public function update_package($data)

	{

		$this->db->where('id_package', $data['id_package']);

		$this->db->update('packages', $data);
	}



	public function delete_package($id_package)

	{

		$this->db->delete('packages', ['id_package' => $id_package]);
	}
}
