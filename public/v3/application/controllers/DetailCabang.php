<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DetailCabang extends CI_Controller
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

	public function index($id_cabang)
	{
		$data['title']			= 'Kelola Cabang';
		$data['title2']			= 'Detail Cabang';
		$data['cabang']			= $this->M_cabang->get_by_id($id_cabang);
		$data['detail_cabang']	= $this->M_detail_cabang->get_data($id_cabang)->result_array();
		$this->load->view('detail-cabang/data', $data);
	}

	public function tambah($id_cabang)
	{
		$this->validation();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Kelola Cabang';
			$data['title2']		= 'Tambah Detail Cabang';
			$data['user']		= $this->M_user->get_data()->result_array();
			$data['cabang']		= $this->M_cabang->get_by_id($id_cabang);
			$this->load->view('detail-cabang/tambah', $data);
		} else {
			$data	= $this->input->post(null, true);
			$data_detail_cabang	= [
				'id_cabang'	=> $id_cabang,
				'id_user'	=> $data['id_user']
			];
			if ($this->M_detail_cabang->insert($data_detail_cabang)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-detail-cabang/'.$id_cabang);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('detail-cabang/'.$id_cabang);
			}
		}
	}

	private function validation()
	{
		$this->form_validation->set_rules('id_user', 'Nama', 'required|trim');
	}

	public function hapus($id_cabang, $id_detail_cabang)
	{
		$this->M_detail_cabang->delete($id_detail_cabang);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('detail-cabang/'.$id_cabang);
	}
}
