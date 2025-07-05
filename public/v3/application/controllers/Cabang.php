<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cabang extends CI_Controller
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
		$data['title']		= 'Kelola Cabang';
		$data['title2']		= 'Kelola Cabang';
		$data['cabang']		= $this->M_cabang->get_data()->result_array();
		$this->load->view('cabang/data', $data);
	}

	public function tambah()
	{
		$this->validation();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Kelola Cabang';
			$data['title2']		= 'Tambah Cabang';
			$this->load->view('cabang/tambah', $data);
		} else {
			$data	= $this->input->post(null, true);
			$slug = strtolower(str_replace(' ', '-', $data['nama_cabang']));
			$data_cabang	= [
				'nama_cabang'	=> $data['nama_cabang'],
				'slug'			=> $slug
			];
			if ($this->M_cabang->insert($data_cabang)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-cabang');
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('cabang');
			}
		}
	}

	public function edit($id_cabang)
	{
		$this->validation($id_cabang);
		if (!$this->form_validation->run()) {
			$data['title']		= 'Kelola Cabang';
			$data['title2']		= 'Edit Cabang';
			$data['cabang']	= $this->M_cabang->get_by_id($id_cabang);
			$this->load->view('cabang/edit', $data);
		} else {
			$data		= $this->input->post(null, true);
			$slug = strtolower(str_replace(' ', '-', $data['nama_cabang']));
			$data_cabang	= [
				'id_cabang'		=> $id_cabang,
				'nama_cabang'	=> $data['nama_cabang'],
				'slug'			=> $slug
			];

			if ($this->M_cabang->update($data_cabang)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-cabang/' . $id_cabang);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('cabang');
			}
		}
	}

	private function validation($id_cabang = null)
	{
		if (is_null($id_cabang)) {
			$this->form_validation->set_rules('nama_cabang', 'Nama Cabang', 'required|trim|is_unique[cabang.nama_cabang]', ['is_unique'	=> 'Nama cabang sudah ada']);
		} else {
			$this->form_validation->set_rules('nama_cabang', 'Nama Cabang', 'required|trim');
		}
	}

	public function hapus($id_cabang)
	{
		$this->M_cabang->delete($id_cabang);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('cabang');
	}
}
