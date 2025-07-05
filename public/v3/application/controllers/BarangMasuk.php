<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BarangMasuk extends CI_Controller
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
		$data['title']		    = 'Data Barang';
		$data['title2']		    = 'Barang Masuk';
		$data['jenis'] = $this->M_barang->get_jenis_barang();
		$data['jenis_barang']   = '';
		$post_jb = $this->input->post('jenis_barang');
		$barang = $this->M_barang->get_barang_masuk()->result_array();
		if (empty($post_jb)) {
			$data['jenis_barang'] = 'All';
		} else if ($post_jb === 'All') {
			$data['jenis_barang'] = 'All';
			$barang = $this->M_barang->get_barang_masuk()->result_array();
		} else {
			$data['jenis_barang'] = $post_jb;
			$barang = $this->M_barang->get_barang_masuk($post_jb)->result_array();
		}
		$data['barangMasuk']	= $barang;
		$data['barang']			= $this->M_barang->get_barang()->result_array();
		$this->load->view('barangMasuk/data', $data);
	}

	public function get_by_id()
	{
		$id_barang_masuk = $this->input->post('id_barang_masuk');
		$data = $this->db->get_where('barang_masuk', ['id_barang_masuk' => $id_barang_masuk])->row_array();
		echo json_encode($data);
	}

	public function tambah()
	{
		$this->db->trans_start();
		try {
			$data		= $this->input->post(null, true);
			$harga_beli = $data['harga_beli'] ? $data['harga_beli'] : 0;

			$data_user	= [
				'id_barang'		    => $data['id_barang'],
				'tanggal_masuk'		=> $data['tanggal_masuk'],
				'jumlah'		    => $data['jumlah'],
				'harga_beli'		=> $harga_beli,
				'status_indent'		=> $data['status_indent']
			];

			$this->M_barang->insert_barang_masuk($data_user);
			log_activity($this->session->userdata('id_user'), 'Tambah Barang Masuk', 'Barang Masuk berhasil ditambahkan', $data_user);

			$stok = $this->db->get_where('stok_barang', ['id_barang' => $data['id_barang'], 'status_indent' => $data['status_indent'], 'harga_beli' => $harga_beli]);

			$get_stok = $stok->row_array();

			$id_stok_barang = $get_stok['id_stok_barang'];
			$stok_akhir = $get_stok['stok'] + $data['jumlah'];

			if ($id_stok_barang != NULL) {
				$data_stok['id_stok_barang'] = $id_stok_barang;
			}

			$data_stok['id_barang'] = $data['id_barang'];
			$data_stok['stok'] = $stok_akhir;
			$data_stok['harga_beli'] = $harga_beli;
			$data_stok['status_indent'] = $data['status_indent'];


			$this->M_barang->update_stok_barang($data_stok);

			$this->db->trans_commit();
			set_pesan('Data Berhasil Ditambahkan', true);
		} catch (Exception $e) {
			$this->db->trans_rollback();
			set_pesan($e->getMessage(), false);
		}
		redirect('barang-masuk');
	}
	// public function tambah()
	// {
	// 	$this->db->trans_start();
	// 	try {
	// 		$data		= $this->input->post(null, true);
	// 		$harga_beli = $data['harga_beli'] ? $data['harga_beli'] : 0;

	// 		$data_user	= [
	// 			'id_barang'		    => $data['id_barang'],
	// 			'tanggal_masuk'		=> $data['tanggal_masuk'],
	// 			'jumlah'		    => $data['jumlah'],
	// 			'harga_beli'		=> $harga_beli,
	// 			'status_indent'		=> $data['status_indent']
	// 		];

	// 		$this->M_barang->insert_barang_masuk($data_user);
	// 		// if (!$insert) {
	// 		// 	throw new Exception('Error saat memasukkan data barang masuk.');
	// 		// }

	// 		$stok = $this->db->get_where('stok_barang', ['id_barang' => $data['id_barang'], 'status_indent' => $data['status_indent'], 'harga_beli' => $harga_beli]);

	// 		if ($stok->num_rows() == 0) {
	// 			$data_stok	= [
	// 				'id_barang'		=> $data['id_barang'],
	// 				'stok'		    => $data['jumlah'],
	// 				'harga_beli'    => $harga_beli,
	// 				'status_indent' => $data['status_indent'],
	// 			];

	// 			$this->M_barang->insert_stok_barang($data_stok);
	// 			// if (!$insert_stok) {
	// 			// 	throw new Exception('Error saat menambahkan stok barang.');
	// 			// }
	// 		} elseif ($stok->num_rows() > 0) {
	// 			$get_stok = $stok->row_array();
	// 			$id_stok_barang = $get_stok['id_stok_barang'];
	// 			$stok_akhir = $get_stok['stok'] + $data['jumlah'];
	// 			$data_stok	= [
	// 				'id_stok_barang'    => $id_stok_barang,
	// 				'id_barang'		    => $data['id_barang'],
	// 				'stok'		        => $stok_akhir,
	// 				'harga_beli'		=> $harga_beli,
	// 				'status_indent'		=> $data['status_indent'],
	// 			];

	// 			$this->M_barang->update_stok_barang($data_stok);
	// 			// if (!$update_stok) {
	// 			// 	throw new Exception('Error saat mengupdate stok barang.');
	// 			// }
	// 		}

	// 		// $bulan       = $this->bulan_romawi(date('F', strtotime($data['tanggal_masuk'])));
	// 		// $tahun       = date('Y', strtotime($data['tanggal_masuk']));

	// 		// $last_pembelian  =   $this->db->select('*')
	// 		// 	->from('pembelians')
	// 		// 	->order_by('id_pembelian', 'DESC')
	// 		// 	->where("DATE_FORMAT(tanggal_pembelian, '%Y') =", $tahun)
	// 		// 	->get()->row_array();

	// 		// if (empty($last_pembelian)) {
	// 		// 	$noPembelian = '1/PEM/' . $bulan . '/' . $tahun;
	// 		// } else {
	// 		// 	$pch_pembelian = explode('/', $last_pembelian['no_pembelian']);
	// 		// 	$no  = intval($pch_pembelian[0]) + 1;
	// 		// 	$noPembelian = "$no/PEM/$bulan/$tahun";
	// 		// }

	// 		// $barang_masuk_baru  =   $this->db->select('*')
	// 		// 	->from('barang_masuk')
	// 		// 	->order_by('id_barang_masuk', 'DESC')
	// 		// 	->get()->row_array();

	// 		// $pembelian = [
	// 		// 	'no_pembelian'		=> $noPembelian,
	// 		// 	'id_barang_masuk'	=> $barang_masuk_baru['id_barang_masuk'],
	// 		// 	'id_barang' 		=> $data['id_barang'],
	// 		// 	'tanggal_pembelian'	=> $data['tanggal_masuk'],
	// 		// 	'jatuh_tempo' 		=> null
	// 		// ];

	// 		// $this->db->insert('pembelians', $pembelian);
	// 		// if (!$insert_pembelian) {
	// 		// 	throw new Exception('Error saat memasukkan data pembelian.');
	// 		// }

	// 		$this->db->trans_commit();
	// 		set_pesan('Data Berhasil Ditambahkan', true);
	// 	} catch (Exception $e) {
	// 		$this->db->trans_rollback();
	// 		set_pesan($e->getMessage(), false);
	// 	}
	// 	redirect('barang-masuk');
	// }

	public function edit()
	{
		$this->db->trans_start();
		try {
			$id_barang_masuk = $this->input->post('id_barang_masuk');
			$barang_masuk_lama = $this->db->get_where('barang_masuk', ['id_barang_masuk' => $id_barang_masuk])->row_array();
			$data		= $this->input->post(null, true);
			$data_user	= [
				'id_barang_masuk'   => $id_barang_masuk,
				'id_barang'	    	=> $data['id_barang'],
				'tanggal_masuk'		=> $data['tanggal_masuk'],
				'jumlah'		    => $data['jumlah'],
				'harga_beli'		=> $data['harga_beli'],
				'status_indent'		=> $data['status_indent'],
			];
			$this->M_barang->update_barang_masuk($data_user);
			log_activity($this->session->userdata('id_user'), 'Edit Barang Masuk', 'Barang Masuk berhasil diedit', $data_user);

			$stok_lama = $this->db->get_where('stok_barang', ['id_barang' => $barang_masuk_lama['id_barang'], 'status_indent' => $barang_masuk_lama['status_indent'], 'harga_beli' => $barang_masuk_lama['harga_beli']]);
			$get_stok = $stok_lama->row_array();
			$id_stok_barang = $get_stok['id_stok_barang'];
			$stok_akhir = $get_stok['stok'] - $barang_masuk_lama['jumlah'];
			$data_stok	= [
				'id_stok_barang'    => $id_stok_barang,
				'stok'		        => $stok_akhir,
			];

			$this->M_barang->update_stok_barang($data_stok);
			$stok = $this->db->get_where('stok_barang', ['id_barang' => $data['id_barang'], 'status_indent' => $data['status_indent'], 'harga_beli' => $data['harga_beli']]);

			$get_stok = $stok->row_array();
			$id_stok_barang = $get_stok['id_stok_barang'];
			$stok_akhir = $get_stok['stok'] + $data['jumlah'];
			$data_stok	= [
				'id_stok_barang'    => $id_stok_barang,
				'id_barang'		    => $data['id_barang'],
				'stok'		        => $stok_akhir,
				'harga_beli'		=> $data['harga_beli'],
				'status_indent'		=> $data['status_indent'],
			];
			$this->M_barang->update_stok_barang($data_stok);

			$this->db->trans_commit();
			set_pesan('Data Berhasil Diupdate', true);
		} catch (Exception $e) {
			$this->db->trans_rollback();
			set_pesan($e->getMessage(), false);
		}
		redirect('barang-masuk');
	}

	public function hapus($id_barang_masuk)
	{
		$bm = $this->M_barang->get_barang_masuk_by_id($id_barang_masuk);
		$id_barang      = $bm['id_barang'];
		$jumlah         = $bm['jumlah'];
		$harga_beli     = $bm['harga_beli'];
		$stok_lama      = $this->db->get_where('stok_barang', ['id_barang' => $id_barang, 'status_indent' => $bm['status_indent'], 'harga_beli' => $harga_beli]);
		$get_stok       = $stok_lama->row_array();
		$id_stok_barang = $get_stok['id_stok_barang'];
		$stok_akhir     = $get_stok['stok'] - $jumlah;
		$data_stok	= [
			'id_stok_barang'    => $id_stok_barang,
			'stok'		        => $stok_akhir,
		];

		log_activity($this->session->userdata('id_user'), 'Hapus Barang Masuk', 'Barang Masuk berhasil dihapus', $bm);

		$this->M_barang->update_stok_barang($data_stok);
		$this->M_barang->delete_barang_masuk($id_barang_masuk);

		// $pembelian      = $this->db->get_where('pembelians', ['id_barang_masuk' => $id_barang_masuk])->row_array();
		// $this->db->delete('pembelians', ['id_pembelian' => $pembelian['id_pembelian']]);
		// $this->db->delete('detail_pembelian', ['id_pembelian' => $pembelian['id_pembelian']]);

		set_pesan('Data Berhasil Dihapus', true);
		redirect('barang-masuk');
	}

	private function validation()
	{
		$this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
		$this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required|trim');
		$this->form_validation->set_rules('satuan_barang', 'Satuan Barang', 'required|trim');
		$this->form_validation->set_rules('harga_barang', 'Harga Barang', 'required|numeric');
		$this->form_validation->set_rules('jenis_barang', 'Jenis Barang', 'required|trim');
	}

	private function validation_barang_masuk()
	{
		$this->form_validation->set_rules('id_barang', 'ID Barang', 'required|trim');
		$this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required|trim');
		$this->form_validation->set_rules('jumlah', 'Jumlah Barang', 'required|numeric');
		$this->form_validation->set_rules('harga_beli', 'Harga Barang', 'required|numeric');
	}

	private function validation_tracking_barang()
	{
		$this->form_validation->set_rules('id_barang', 'ID Barang', 'required|trim');
	}

	private function validation_stok_opname()
	{
		$this->form_validation->set_rules('id_stok_barang', 'ID Barang', 'required|trim');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
		$this->form_validation->set_rules('jumlah', 'Jumlah Barang', 'required|numeric');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');
	}

	private function bulan_romawi($bulan)
	{
		$bulan = $bulan;
		switch ($bulan) {
			case 'January':
				$bulan = "I";
				break;
			case 'February':
				$bulan = "II";
				break;
			case 'March':
				$bulan = "III";
				break;
			case 'April':
				$bulan = "IV";
				break;
			case 'May':
				$bulan = "V";
				break;
			case 'June':
				$bulan = "VI";
				break;
			case 'July':
				$bulan = "VII";
				break;
			case 'August':
				$bulan = "VIII";
				break;
			case 'September':
				$bulan = "IX";
				break;
			case 'October':
				$bulan = "X";
				break;
			case 'November':
				$bulan = "XI";
				break;
			case 'December':
				$bulan = "XII";
				break;
			default:
				$bulan = "Isi variabel tidak di temukan";
				break;
		}

		return $bulan;
	}
}
