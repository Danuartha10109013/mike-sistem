<?php

defined('BASEPATH') or exit('No direct script access allowed');

class RiwayatOrder extends CI_Controller
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
		$data['title']		= 'Riwayat';
		$data['title2']		= 'Riwayat Pesanan';
		$data['outstanding']	= $this->M_order->get_riwayat_outstanding()->result_array();
		$this->load->view('order/riwayat', $data);
	}
}
