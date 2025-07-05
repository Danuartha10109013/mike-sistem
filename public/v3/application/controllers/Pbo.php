<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pbo extends CI_Controller
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

	//Barang
	public function index()
	{
		$this->db->select_sum('total');
		$this->db->from('project_non_po');
		$get_non_po = $this->db->get()->row_array();
		$data['total_project_non_po'] 	= $get_non_po['total'];
		$data['title']					= 'Data Project Non PO';
		$data['title2']					= 'Data Project Non PO';
		$data['pbo']					= $this->M_pbo->get_data()->result_array();

		$this->load->view('pbo/data', $data);
	}

	public function tambah()
	{
		$this->validation();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Data Project Non PO';
			$data['title2']		= 'Data Project Non PO';
			$data['barang']		= $this->M_barang->get_barang_fabrikasi()->result_array();
			$this->load->view('pbo/tambah', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_user	= [
				'id_barang'		=> $data['id_barang'],
				'deskripsi'		=> $data['deskripsi'],
				'qty'		=> $data['qty'],
				'po'		=> $data['po'],
				'uom'		=> $data['uom'],
				'amount'		=> $data['amount'],
				'total'		=> $data['qty'] * $data['amount'],
				'department'		=> $data['department'],
				'status'		=> $data['status']

			];


			if ($this->M_pbo->insert($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-pbo');
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('pbo');
			}
		}
	}

	public function edit($id_pbo)
	{
		$this->validation();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Data Project Non PO';
			$data['title2']		= 'Data Project Non PO';
			$data['barang']		= $this->M_barang->get_barang_fabrikasi()->result_array();
			$data['p']	= $this->M_pbo->get_by_id($id_pbo);
			$this->load->view('pbo/edit', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_user	= [
				'id_pbo'			=> $id_pbo,
				'id_barang'		=> $data['id_barang'],
				'deskripsi'		=> $data['deskripsi'],
				'qty'		=> $data['qty'],
				'po'		=> $data['po'],
				'uom'		=> $data['uom'],
				'amount'		=> $data['amount'],
				'total'		=> $data['qty'] * $data['amount'],
				'department'		=> $data['department'],
				'status'		=> $data['status']
			];


			if ($this->M_pbo->update($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-pbo/' . $id_pbo);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('pbo');
			}
		}
	}

	public function generate_po($id_pbo)
	{
		$this->validation_generate_po();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Data Project Non PO';
			$data['title2']		= 'Data Project Non PO';
			$data['po']		= $this->M_fabrikasi->get_outstanding()->result_array();
			$data['p']	= $this->M_pbo->get_by_id($id_pbo);
			$this->load->view('pbo/generate_po', $data);
		} else {
			$data		= $this->input->post(null, true);

			$this->db->trans_start();
			$p	= $this->M_pbo->get_by_id($id_pbo);
			$b = null;
			if ($p['id_barang'] == 0) {
				$data_barang	= [
					'nama_barang'		=> $data['deskripsi'],
					'kode_barang'		=> $data['kode_barang'],
					'satuan_barang'		=> $data['uom'],
					'harga_barang'		=> $data['amount'],
					'jenis_barang'		=> 'Fabrikasi'
				];

				$this->db->insert('barang', $data_barang);
				$insert_id = $this->db->insert_id();

				$b = $this->M_barang->get_barang_by_id($insert_id);
			} else {
				$b = $this->M_barang->get_barang_by_id($p['id_barang']);
			}

			$po = null;
			if ($data['no_po']) {
				$get_po = $this->db->get_where('po_fabrikasi', ['no_po' => $data['no_po']]);
				if ($get_po->num_rows() > 0) {
					$po = $get_po->row_array();
				} else {
					$data_po = [
						'no_po' => $data['no_po'],
						'tanggal' => date('Y-m-d'),
						'nama_user' => $data['department'],
						'id_user' => $this->session->userdata('id_user'),
					];

					$this->db->insert('po_fabrikasi', $data_po);
					$insert_id = $this->db->insert_id();
					$po = $this->M_fabrikasi->get_outstanding_by_id($insert_id);
				}
			}

			$data_user	= [
				'id_po_fabrikasi'		=> $po['id_po_fabrikasi'],
				'id_barang'			=> $b['id_barang'],
				'nama_barang'		=> $b['nama_barang'],
				'kode_barang'		=> $b['kode_barang'],
				'satuan_barang'		=> $b['satuan_barang'],
				'harga_unit'		=> $data['harga_unit'],
				'harga_instalasi'	=> $data['harga_instalasi'],
				'jumlah_barang'		=> $data['qty'],
				'harga_barang'		=> $data['amount'],
				'total_harga_barang' => $data['qty'] * $data['amount'],
				'departemen'		=> $data['department']
			];

			$this->M_fabrikasi->insert_detail_outstanding($data_user);
			$this->M_pbo->delete($id_pbo);
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->session->set_flashdata('msg', 'error');
				redirect('generate-po/' . $id_pbo);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('pbo');
			}
		}
	}

	public function hapus($id_pbo)
	{
		$this->M_pbo->delete($id_pbo);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('pbo');
	}

	private function validation()
	{
		$this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');
		$this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required|trim');
		$this->form_validation->set_rules('uom', 'UOM', 'required|trim');
		$this->form_validation->set_rules('qty', 'Quantity', 'required|numeric');
		$this->form_validation->set_rules('po', 'PO', 'required|trim');
		$this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
		$this->form_validation->set_rules('department', 'Department', 'required|trim');
		$this->form_validation->set_rules('status', 'Status', 'required|trim');
	}

	private function validation_generate_po()
	{
		$this->form_validation->set_rules('no_po', 'PO', 'required|trim');
		$this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required|trim');
		$this->form_validation->set_rules('uom', 'UOM', 'required|trim');
		$this->form_validation->set_rules('qty', 'Quantity', 'required|numeric');
		$this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
		$this->form_validation->set_rules('department', 'Department', 'required|trim');
	}
}
