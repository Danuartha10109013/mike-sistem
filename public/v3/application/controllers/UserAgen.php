<?php

defined('BASEPATH') or exit('No direct script access allowed');

class UserAgen extends CI_Controller
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
		$data['title']		= 'Data Distributor';
		$data['title2']		= 'Kelola Distributor';
		$data['departemen']	= $this->M_departemen->get_data()->result_array();
		$this->load->view('userAgen/data', $data);
	}

	public function tambah()
	{
		$namaDepartemen = $this->input->post('nama_departemen');
		$phoneDepartemen = $this->input->post('phone_departemen');
		$addressDepartemen = $this->input->post('address_departemen');

		$existing = $this->db->get_where('departemen', ['nama_departemen' => $namaDepartemen])->row_array();
		if ($existing) {
			set_pesan('Nama agen sudah digunakan. Gunakan nama agen lain.', false);
			redirect('kelola-user-agen');
			return;
		}

		$existing2 = $this->db->get_where('departemen', ['phone_departemen' => $phoneDepartemen])->row_array();
		if ($existing2) {
			set_pesan('no telepon sudah digunakan. Gunakan nomor lain.', false);
			redirect('kelola-user-agen');
			return;
		}

		$data = [
			'nama_departemen' => $namaDepartemen,
			'phone_departemen' => $phoneDepartemen,
			'address_departemen' => $addressDepartemen,
		];
		log_activity($this->session->userdata('id_user'), 'Tambah Distributor', 'Distributor berhasil ditambah ', $data);

		$this->db->insert('departemen', $data);
		set_pesan('Data Berhasil Ditambahkan', true);
		redirect('kelola-user-agen');
	}

	public function edit()
	{
		$id_departemen = $this->input->post('id_departemen');
		$namaDepartemen = $this->input->post('nama_departemen');
		$phoneDepartemen = $this->input->post('phone_departemen');
		$addressDepartemen = $this->input->post('address_departemen');

		$existing = $this->db->get_where('departemen', [
			'nama_departemen' => $namaDepartemen,
			'id_departemen !=' => $id_departemen
		])->row_array();

		if ($existing) {
			set_pesan('Nama agen sudah digunakan. Gunakan nama agen lain.', false);
			redirect('kelola-user-agen');
			return;
		}

		$existing2 = $this->db->get_where('departemen', [
			'phone_departemen' => $phoneDepartemen,
			'id_departemen !=' => $id_departemen
		])->row_array();

		if ($existing2) {
			set_pesan('No. Telepon agen sudah digunakan. Gunakan no. Telepon lain.', false);
			redirect('kelola-user-agen');
			return;
		}


		$data = [
			'nama_departemen' => $namaDepartemen,
			'phone_departemen' =>  $phoneDepartemen,
			'address_departemen' => $addressDepartemen
		];
		log_activity($this->session->userdata('id_user'), 'Tambah Distributor', 'Distributor berhasil ditambah ', $data);

		$this->db->where('id_departemen', $id_departemen);
		$this->db->update('departemen', $data);
		set_pesan('Data Berhasil Diupdate.', true);
		redirect('kelola-user-agen');
	}

	public function get_by_id()
	{
		$id_departemen = $this->input->post('id_departemen');
		$data = $this->db->get_where('departemen', ['id_departemen' => $id_departemen])->row_array();
		echo json_encode($data);
	}

	public function hapus($id_departemen)
	{
		$data = $this->db->get_where('departemen', ['id_departemen' => $id_departemen])->row_array();

		log_activity($this->session->userdata('id_user'), 'Hapus Distributor', 'Distributor berhasil dihapus ', $data);

		$this->M_departemen->delete($id_departemen);
		set_pesan('Data Berhasil Dihapus', true);
		redirect('kelola-user-agen');
	}
}
