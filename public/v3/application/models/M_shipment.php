<?php

defined('BASEPATH') or exit('No direct script access allowed');



class M_shipment extends CI_Model
{



	private $table	= 'shipments';

	public function get_shipment($limit = null, $start = null, $search = null, $jenis_barang = null)

	{



		$this->db->select('*,shipments.driver as driver');

		$this->db->from($this->table);
		$this->db->join('gs', 'gs.id_gs = shipments.id_gs');
		$this->db->limit($limit, $start);
		$this->db->order_by('shipments.id_gs', 'ASC');

		return  $this->db->get()->result_array();
	}


	public function get($mode = null)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		return $this->db->get();
	}

	public function count_data($search = null, $jenis_barang = null)

	{


		$this->db->select('*');

		$this->db->from($this->table);

		return $this->db->count_all_results();
	}


	public function get_shipment_by_id($id)
	{
		$this->db->select('*');

		$this->db->from($this->table);


		$this->db->where('id_shipment', $id);

		return $this->db->get()->row_array();
	}


	public function insert_shipment($data)

	{

		$this->db->insert('shipments', $data);
	}

	public function update_shipment($data)

	{

		$this->db->where('id_shipment', $data['id_shipment']);

		$this->db->update('shipments', $data);
	}



	public function delete_shipment($id_shipment)

	{

		$this->db->delete('shipments', ['id_shipment' => $id_shipment]);
	}
}
