<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Departemen extends CI_Controller
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
		$data['title']		= 'Kelola Departemen';
		$data['title2']		= 'Kelola Departemen';
		$data['departemen']		= $this->M_departemen->get_data()->result_array();
		$this->load->view('departemen/data', $data);
	}

	public function tambah()
	{
		$this->validation();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Kelola Departemen';
			$data['title2']		= 'Tambah Departemen';
			$this->load->view('departemen/tambah', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_departemen	= [
				'nama_departemen'	=> $data['nama_departemen']
			];
			if ($this->M_departemen->insert($data_departemen)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-departemen');
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('departemen');
			}
		}
	}

	public function edit($id_departemen)
	{
		$this->validation($id_departemen);
		if (!$this->form_validation->run()) {
			$data['title']		= 'Kelola Departemen';
			$data['title2']		= 'Edit Departemen';
			$data['departemen']	= $this->M_departemen->get_by_id($id_departemen);
			$this->load->view('departemen/edit', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_departemen	= [
				'id_departemen'		=> $id_departemen,
				'nama_departemen'	=> $data['nama_departemen']
			];

			if ($this->M_departemen->update($data_departemen)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-departemen/' . $id_departemen);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('departemen');
			}
		}
	}

	private function validation($id_departemen = null)
	{
		if (is_null($id_departemen)) {
			$this->form_validation->set_rules('nama_departemen', 'Nama departemen', 'required|trim');
		} else {
			$this->form_validation->set_rules('nama_departemen', 'Nama departemen', 'required|trim');
		}
	}

	public function hapus($id_departemen)
	{
		$this->M_departemen->delete($id_departemen);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('departemen');
	}
}
