<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Package extends CI_Controller
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
		$data['title'] = 'Data Paket';
		$data['title2'] = 'Data Paket';
		$data['jenis_barang'] = $this->input->post('jenis_barang') ?? 'All';
		$data['barang'] = $this->M_barang->get_barang2()->result_array();

		$this->load->view('package/data', $data);
	}

	public function ajax_datatable()
	{
		$package = array();
		$limit          = html_escape($this->input->post('length'));
		$start          = html_escape($this->input->post('start'));
		$totalData 		= $this->M_package->count_data();
		$totalFiltered  = $totalData;

		if (is_admin() || (is_fabrikasi() && is_supplier())) {
			if (!empty($this->input->post('search')['value'])) {
				$search = $this->input->post('search')['value'];
				$package =  $this->M_package->get_package($limit, $start, $search);
				$totalFiltered = $this->M_package->count_data($search);
			} else if (!empty($this->input->post('jenis_barang'))) {

				$jenis_barang = $this->input->post('jenis_barang');
				$package =  $this->M_package->get_package($limit, $start, null, $jenis_barang);
				$totalFiltered = $this->M_package->count_data(null, $jenis_barang);
			} else {
				$package = $this->M_package->get_package($limit, $start);
			}
		}
		$data = array();

		if (!empty($package)) {
			foreach ($package as $key => $row) {
				$idBarang = $row['id_package'];
				$link = "'" . base_url('hapus-package/' . $row['id_package']) . "'";
				$mid = "document.location.href=$link";
				$a = 'data-confirm-yes="' . $mid . ';"';

				$action = "
				<button type='button' data-id='$idBarang' class='btn btn-success btn-edit' data-tippy-content='Edit Data'><i class='fa fa-edit'></i></button>
				<button class='btn btn-danger' data-confirm='Apakah Anda yakin akan hapus data ini?' $a onclick='del($(this))' data-tippy-content='Hapus Data'><i class='fa fa-trash'></i></button>";

				$status =  $row['status_package'] == '1' ? 'Aktif' : 'Tidak Aktif';

				$nestedData['#'] = $key + 1;
				$nestedData['package_name'] = $row['package_name'];
				$nestedData['harga_package'] = 'Rp' . number_format($row['harga_package'], 2, ',', '.');;
				$nestedData['item_package'] = $row['data_item_text'];
				$nestedData['status_package'] = $status;
				$nestedData['point_package'] = $row['point_package'];
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


	public function get_package()
	{
		$id_package = $_POST['id_package'];
		$this->db->where('id_package', $id_package);
		$query = $this->db->get('packages');
		$data = $query->row();
		echo json_encode($data);
	}

	public function tambah()

	{

		$this->validation();

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata('msg', 'error');
			redirect('package');
		} else {

			$data		= $this->input->post(null, true);

			$name = $this->db->get_where('packages', ['package_name' => $data['package_name']])->num_rows();

			if ($name > 0) {

				$this->session->set_flashdata('msg', 'error');

				redirect('package');
			}

			$item = implode(',', $data['item_package']);


			$data_package	= [

				'package_name' => $data['package_name'],
				'harga_package' => $data['harga_package'],
				'point_package' => $data['point_package'],
				'item_package' => $item,
				'status_package' => $data['status_package'],

			];
			log_activity($this->session->userdata('id_user'), 'Tambah Paket', 'Paket berhasil ditambahkan', $data_package);

			if ($this->M_package->insert_package($data_package)) {

				$this->session->set_flashdata('msg', 'error');

				redirect('package');
			} else {

				$this->session->set_flashdata('msg', 'edit');

				redirect('package');
			}
		}
	}



	public function edit()

	{

		$this->validation();

		if (!$this->form_validation->run()) {

			$this->session->set_flashdata('msg', 'error');

			redirect('package');
		} else {


			$data		= $this->input->post(null, true);

			$name = $this->db->get_where('packages', ['id_package != ' => $data['id_package'], 'package_name' => $data['package_name']])->num_rows();


			if ($name > 0) {

				$this->session->set_flashdata('msg', 'error');

				redirect('package');
			}

			$item = implode(',', $data['item_package']);

			$data_package	= [

				'id_package' => $data['id_package'],
				'package_name' => $data['package_name'],
				'harga_package' => $data['harga_package'],
				'point_package' => $data['point_package'],
				'item_package' => $item,
				'status_package' => $data['status_package'],

			];
			log_activity($this->session->userdata('id_user'), 'Edit Paket', 'Paket berhasil diedit', $data_package);

			if ($this->M_package->update_package($data_package)) {

				$this->session->set_flashdata('msg', 'error');

				redirect('package');
			} else {

				$this->session->set_flashdata('msg', 'edit');

				redirect('package');
			}
		}
	}


	private function validation()

	{

		$this->form_validation->set_rules('package_name', 'Nama Paket', 'required|trim');

		$this->form_validation->set_rules('harga_package', 'Harga Paket', 'required|trim');

		$this->form_validation->set_rules('point_package', 'Point', 'required|trim');

		$this->form_validation->set_rules('item_package[]', 'Barang', 'required|trim');

		$this->form_validation->set_rules('status_package', 'Status Paket', 'required|trim');
	}


	public function hapus($id_package)

	{
		$data = $this->db->get_where('packages', ['id_package' => $id_package])->row_array();

		log_activity($this->session->userdata('id_user'), 'Hapus Paket', 'Paket berhasil dihapus', $data);

		$this->M_package->delete_package($id_package);

		$this->session->set_flashdata('msg', 'hapus');

		redirect('package');
	}
}
