<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tracking extends CI_Controller
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

		$this->validation_tracking_barang();

		if (!$this->form_validation->run()) {

			$data['title']		= 'Tracking Barang';

			$data['title2']		= 'Tracking Barang';

			$data['barang']		=  $this->M_barang->get_barang2()->result_array();

			$this->load->view('barang/tracking_barang', $data);
		} else {

			$id_barang = $this->input->post('id_barang');

			if ($this->input->post('filter')) {

				$data['title']		= 'Tracking Barang';

				$data['title2']		= 'Tracking Barang';
				$data['barang']		=  $this->M_barang->get_barang2()->result_array();
				$data['b']			= $this->M_barang->get_barang_by_id($id_barang);
				$data['stok']		= $this->M_barang->get_stok_barang_by_id($id_barang);

				$data['baranglists']		= $this->M_barang->get_tracking_barang_by_id($id_barang)->result_array();

				$this->load->view('barang/tracking_barang', $data);
			} else {

				$data['title']		= 'Tracking Barang';

				$data['title2']		= 'Tracking Barang';

				$data['b']			= $this->M_barang->get_barang_by_id($id_barang);

				$data['stok']		= $this->M_barang->get_stok_barang_by_id($id_barang);

				$data['barang']		= $this->M_barang->get_tracking_barang_by_id($id_barang)->result_array();

				$this->load->view('barang/cetak_tracking_barang', $data);
			}
		}
	}




	private function validation_tracking_barang()

	{

		$this->form_validation->set_rules('id_barang', 'ID Barang', 'required|trim');
	}
}
