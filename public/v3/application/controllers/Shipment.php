<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shipment extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login') != TRUE) {
			set_pesan('Silahkan login terlebih dahulu', false);
			redirect('');
		}
		date_default_timezone_set("Asia/Jakarta");
	}



	public function index()
	{
		$data['title'] = 'invoice';
		$data['title2'] = 'Tracking Shipment';
		$data['gs'] = $this->M_gs->get_invoice_and_po()->result_array();
		$data['gsall'] = $this->M_gs->get_invoice_and_poall()->result_array();
		$data['shipment'] = $this->M_shipment->get()->result_array();
		// var_dump($data['gsall']);
		// die;
		$this->load->view('invoice/tracking_shipment', $data);
	}

	public function ajax_datatable()
	{
		$shipment = array();
		$limit          = html_escape($this->input->post('length'));
		$start          = html_escape($this->input->post('start'));
		$totalData 		= $this->M_shipment->count_data();
		$totalFiltered  = $totalData;

		if (!empty($this->input->post('search')['value'])) {
			$search = $this->input->post('search')['value'];
			$shipment =  $this->M_shipment->get_shipment($limit, $start, $search);
			$totalFiltered = $this->M_shipment->count_data($search);
		} else if (!empty($this->input->post('jenis_barang'))) {

			$jenis_barang = $this->input->post('jenis_barang');
			$shipment =  $this->M_shipment->get_shipment($limit, $start, null, $jenis_barang);
			$totalFiltered = $this->M_shipment->count_data(null, $jenis_barang);
		} else {
			$shipment = $this->M_shipment->get_shipment($limit, $start);
		}
		$data = array();

		if (!empty($shipment)) {
			foreach ($shipment as $key => $row) {
				$idShipment = $row['id_shipment'];
				$link = "'" . base_url('hapus-shipment/' . $row['id_shipment']) . "'";
				$mid = "document.location.href=$link";
				$a = 'data-confirm-yes="' . $mid . ';"';

				$action = "
				<button type='button' data-id='$idShipment' class='btn btn-success btn-edit' data-tippy-content='Edit Data'><i class='fa fa-edit'></i></button>
				<button class='btn btn-danger' data-confirm='Apakah Anda yakin akan hapus data ini?' $a onclick='del($(this))' data-tippy-content='Hapus Data'><i class='fa fa-trash'></i></button>";

				if ($row['status'] == '1') {
					$status = 'Dikemas';
				} else if ($row['status'] == '2') {
					$status = 'Diterima';
				} else {
					$status = 'Dikirim';
				}

				$type =  $row['type_shipment'] == '1' ? 'Expedisi' : 'Manual';
				$nestedData['#'] = $key + 1;
				$nestedData['no_invoice'] = $row['no_invoice'];
				$nestedData['type_shipment'] = $type;
				$nestedData['driver'] = $row['driver'];
				$nestedData['expeditor'] = $row['expeditor'];
				$nestedData['status_shipment'] = $status;
				$nestedData['actions'] = $action;
				$data[] = $nestedData;
			}
		}
		$json_data = array(
			"draw"            => intval($this->input->post('draw')),
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
		);
		echo json_encode($json_data);
	}


	public function tambah()
	{
		$this->validation();

		if (!$this->form_validation->run()) {

			$this->session->set_flashdata('msg', 'error');

			redirect('shipment');
		}

		$id_gs = $this->input->post('id_gs');
		$type_shipment = $this->input->post('type_shipment');
		$driver = $this->input->post('driver');
		$expeditor = $this->input->post('expeditor');
		$status = $this->input->post('status');

		$existing_shipment = $this->db->get_where('shipments', ['id_gs' => $id_gs])->row_array();
		if ($existing_shipment) {
			set_pesan('invoice telah dimasukan.', false);
			redirect('shipment');
			return;
		}

		$data = [
			'id_gs' => $id_gs,
			'type_shipment' => $type_shipment,
			'driver' => $driver,
			'expeditor' => $expeditor,
			'status' => $status,
		];
		$this->db->insert('shipments', $data);
		set_pesan('Data Berhasil Ditambahkan', true);
		redirect('shipment');
	}

	public function get_shipment()
	{
		$id_shipment = $_POST['id_shipment'];

		$this->db->select('*,shipments.driver as driver');
		$this->db->join('gs', 'gs.id_gs = shipments.id_gs');
		$this->db->where('id_shipment', $id_shipment);
		$this->db->order_by('shipments.id_gs', 'ASC');
		$query = $this->db->get('shipments');
		$data = $query->row();


		echo json_encode($data);
	}



	public function edit()

	{

		$this->validation();

		if (!$this->form_validation->run()) {

			$this->session->set_flashdata('msg', 'error');

			redirect('shipment');
		} else {


			$data = $this->input->post(null, true);

			$inv = $this->db->get_where('shipments', ['id_shipment != ' => $data['id_shipment'], 'id_gs' => $data['id_gs']])->num_rows();


			if ($inv > 0) {

				$this->session->set_flashdata('msg', 'error');

				redirect('shipment');
			}



			$datau = [
				'id_shipment' => $data['id_shipment'],
				'id_gs' => $data['id_gs'],
				'type_shipment' => $data['type_shipment'],
				'driver' => $data['driver'],
				'expeditor' => $data['expeditor'],
				'status' => $data['status'],
			];
			if ($this->M_shipment->update_shipment($datau)) {

				$this->session->set_flashdata('msg', 'error');

				redirect('shipment');
			} else {

				$this->session->set_flashdata('msg', 'edit');

				redirect('shipment');
			}
		}
	}


	private function validation()

	{

		$this->form_validation->set_rules('id_gs', 'invoice', 'required|trim');
		$this->form_validation->set_rules('type_shipment', 'Tipe Pengiriman', 'required|trim');

		// $this->form_validation->set_rules('driver', 'Driver', 'required|trim');

		// $this->form_validation->set_rules('expeditor', 'Expedisi', 'required|trim');

		$this->form_validation->set_rules('status', 'status', 'required|trim');
	}


	public function hapus($id_shipment)

	{

		$this->M_shipment->delete_shipment($id_shipment);

		$this->session->set_flashdata('msg', 'hapus');

		redirect('shipment');
	}
}
