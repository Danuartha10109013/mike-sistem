<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
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
	public function get_harga()
	{
		$barang = explode("|", $this->input->post('id_barang'));
		$id_barang = $barang[0];
		//var_dump($id_barang);
		//die();
		$data = $this->db->get_where('barang', ['id_barang' => $id_barang])->row();
		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		if (empty($data)) {
			$list = "<input type='number' name='harga_barang' value='0' class='form-control' required/>";
		} else {
			$list = "<input type='number' name='harga_barang' value='" . $data->harga_barang . "' class='form-control' required/>";
		}
		// Tambahkan tag option ke variabel $lists

		$a = array('harga' => $list); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
		echo json_encode($a); // konversi varibael $callback menjadi JSON
	}

	public function index2()
	{
		$data['title']				= 'Data Barang';
		$data['title2']				= 'Master Barang';
		$data['jenis_barang'] 		= $this->input->post('jenis_barang') ?? 'All';
		$data['jenis'] = $this->M_barang->get_jenis_barang();

		$this->load->view('barang/data2', $data);
	}

	public function ajax_datatable()
	{
		$barang = array();
		$limit          = html_escape($this->input->post('length'));
		$start          = html_escape($this->input->post('start'));
		$totalData 		= $this->M_barang->get_barang2()->num_rows();
		$totalFiltered  = $totalData;

		if (is_admin() || (is_fabrikasi() && is_supplier())) {
			if (!empty($this->input->post('search')['value'])) {
				$search = $this->input->post('search')['value'];
				$barang =  $this->M_barang->get_barang2($limit, $start, $search)->result_array();
				$totalFiltered = $this->M_barang->count_data($search);
			} else if (!empty($this->input->post('jenis_barang'))) {

				$jenis_barang = $this->input->post('jenis_barang');
				$barang =  $this->M_barang->get_barang2($limit, $start, null, $jenis_barang)->result_array();
				$totalFiltered = $this->M_barang->count_data(null, $jenis_barang);
			} else {
				$barang = $this->M_barang->get_barang2($limit, $start)->result_array();
			}
		}
		$data = array();

		if (!empty($barang)) {
			foreach ($barang as $key => $row) {
				$idBarang = $row['id_barang'];
				$link = "'" . base_url('hapus-barang/' . $row['id_barang']) . "'";
				$mid = "document.location.href=$link";
				$a = 'data-confirm-yes="' . $mid . ';"';

				$action = "
				<button type='button' data-id='$idBarang' class='btn btn-success btn-edit' data-tippy-content='Edit Data'><i class='fa fa-edit'></i></button>
				<button class='btn btn-danger' data-confirm='Apakah Anda yakin akan hapus data ini?' $a onclick='del($(this))' data-tippy-content='Hapus Data'><i class='fa fa-trash'></i></button>";

				$nestedData['#'] = $key + 1;
				$nestedData['nama_barang'] = $row['nama_barang'];
				$nestedData['kode_barang'] = $row['kode_barang'];
				$nestedData['satuan_barang'] = $row['satuan_barang'];
				$nestedData['harga_barang'] = 'Rp' . number_format($row['harga_barang'], 2, ',', '.');;
				$nestedData['poin_barang'] = $row['poin_barang'];
				$nestedData['jenis_barang'] = $row['jenis_barang'];
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



	public function get_barang()
	{
		$id_barang = $_POST['id_barang'];
		$this->db->where('id_barang', $id_barang);
		$query = $this->db->get('barang');
		$data = $query->row();
		echo json_encode($data);
	}

	public function index()

	{
		$data['title']		= 'Data Barang';

		$data['title2']		= 'Data Barang';

		$data['jenis_barang'] = '';

		if (is_admin() || (is_fabrikasi() && is_supplier())) {

			$post_jb = $this->input->post('jenis_barang');

			$barang = $this->M_barang->get_barang()->result_array();

			if (empty($post_jb)) {

				$data['jenis_barang'] = 'All';
			} else {

				$data['jenis_barang'] = $post_jb;

				if ($post_jb == 'Fabrikasi') {

					$barang = $this->M_barang->get_barang_fabrikasi()->result_array();
				} elseif ($post_jb == 'Cleaning Supply') {

					$barang = $this->M_barang->get_barang_supplier()->result_array();
				} elseif ($post_jb == 'General Supply') {

					$barang = $this->M_barang->get_barang_general()->result_array();
				}
			}

			$data['barang']		= $barang;
		} elseif (is_fabrikasi()) {

			$data['barang']		= $this->M_barang->get_barang_fabrikasi()->result_array();
		} elseif (is_supplier()) {

			$data['barang']		= $this->M_barang->get_barang_general_supplier()->result_array();
		}



		$this->load->view('barang/data', $data);
	}



	public function tambah()

	{

		$this->validation();

		if (!$this->form_validation->run()) {

			$data['title']		= 'Data Barang';

			$data['title2']		= 'Data Barang';

			$this->load->view('barang/tambah', $data);
		} else {

			$data		= $this->input->post(null, true);

			$code = $this->db->get_where('barang', ['kode_barang' => $data['kode_barang']])->num_rows();

			if ($code > 0) {

				$this->session->set_flashdata('msg', 'existing-item');

				redirect('tambah-barang');
			}



			$data_user	= [

				'nama_barang'		=> $data['nama_barang'],

				'kode_barang'		=> $data['kode_barang'],

				'satuan_barang'		=> $data['satuan_barang'],

				'harga_barang'		=> $data['harga_barang'],

				'jenis_barang'		=> $data['jenis_barang'],

				'poin_barang'		=> $data['poin_barang']

			];





			if ($this->M_barang->insert_barang($data_user)) {

				set_pesan('Error!! Kesalahan di database...', false);

				redirect('tambah-barang');
			} else {
				log_activity($this->session->userdata('id_user'), 'Tambah Barang', 'barang berhasil ditambahkan', $data_user);

				set_pesan('Data Berhasil Ditambahkan', true);

				redirect('barang');
			}
		}
	}



	public function edit()

	{

		$this->validation();

		if (!$this->form_validation->run()) {

			// $data['title']		= 'Data Barang';

			// $data['title2']		= 'Data Barang';

			// $data['b']	= $this->M_barang->get_barang_by_id($id_barang);

			// $this->load->view('barang/edit', $data);

		} else {

			$data		= $this->input->post(null, true);

			$data_user	= [

				'id_barang'			=> $data['id_barang'],
				'nama_barang'		=> $data['nama_barang'],

				'kode_barang'		=> $data['kode_barang'],

				'satuan_barang'		=> $data['satuan_barang'],

				'harga_barang'		=> $data['harga_barang'],

				'jenis_barang'		=> $data['jenis_barang'],

				'poin_barang'		=> $data['poin_barang']

			];





			if ($this->M_barang->update_barang($data_user)) {

				set_pesan('Error!! Kesalahan di database...', false);

				redirect('barang');
			} else {
				log_activity($this->session->userdata('id_user'), 'Edit Barang', 'barang berhasil diedit', $data_user);

				set_pesan('Data Berhasil Diupdate', true);

				redirect('barang');
			}
		}
	}



	public function hapus($id_barang)

	{
		$data = $this->db->get_where('barang', ['id_barang' => $id_barang])->row_array();

		log_activity($this->session->userdata('id_user'), 'Hapus Barang', 'barang berhasil dihapus', $data);

		$this->M_barang->delete_barang($id_barang);

		set_pesan('Data Berhasil Dihapus', true);

		redirect('barang');
	}



	public function stok_barang()

	{

		$data['title']		= 'Data Barang';

		$data['title2']		= 'Stok Barang';

		$data['jenis_barang'] = '';

		$post_jb = $this->input->post('jenis_barang');

		$barang = $this->M_barang->get_stok_barang()->result_array();

		$nab = $this->M_barang->get_nilai_asset();

		if (empty($post_jb)) {

			$data['jenis_barang'] = 'All';
		} else {

			$data['jenis_barang'] = $post_jb;

			$barang = $this->M_barang->get_stok_barang($post_jb)->result_array();

			$nab = $this->M_barang->get_nilai_asset($post_jb);
		}

		$data['barang']		= $barang;

		$data['nab']		= $nab;



		$this->load->view('barang/data_stok', $data);
	}



	public function detail_stok_barang($id_barang)

	{

		$data['title']		= 'Data Barang';

		$data['title2']		= 'Stok Barang';

		$data['b']		= $this->M_barang->get_stok_barang_by_id($id_barang);

		$data['barang']		= $this->M_barang->get_all_stok_barang_by_id($id_barang)->result_array();





		$this->load->view('barang/data_detail_stok', $data);
	}



	//Barang

	public function barang_masuk()

	{

		$data['title']		= 'Data Barang';

		$data['title2']		= 'Barang Masuk';

		$data['jenis_barang'] = '';

		$post_jb = $this->input->post('jenis_barang');

		$barang = $this->M_barang->get_barang_masuk()->result_array();

		if (empty($post_jb)) {

			$data['jenis_barang'] = 'All';
		} else {

			$data['jenis_barang'] = $post_jb;

			$barang = $this->M_barang->get_barang_masuk($post_jb)->result_array();
		}

		$data['barang']		= $barang;



		$this->load->view('barang/data_barang_masuk', $data);
	}



	public function tambah_barang_masuk()

	{

		$this->validation_barang_masuk();

		if (!$this->form_validation->run()) {

			$data['title']		= 'Data Barang';

			$data['title2']		= 'Barang Masuk';

			$data['barang']		= $this->M_barang->get_barang_general_supplier()->result_array();

			$this->load->view('barang/tambah_barang_masuk', $data);
		} else {

			$data		= $this->input->post(null, true);

			$data_user	= [

				'id_barang'		=> $data['id_barang'],

				'tanggal_masuk'		=> $data['tanggal_masuk'],

				'jumlah'		=> $data['jumlah'],

				'harga_beli'		=> $data['harga_beli'],

			];



			$insert = $this->M_barang->insert_barang_masuk($data_user);

			log_activity($this->session->userdata('id_user'), 'Tambah Barang Masuk', 'barang masuk berhasil ditambahkan', $data_user);


			$stok = $this->db->get_where('stok_barang', ['id_barang' => $data['id_barang'], 'harga_beli' => $data['harga_beli']]);



			if ($stok->num_rows() == 0) {

				$data_stok	= [

					'id_barang'		=> $data['id_barang'],

					'stok'		=> $data['jumlah'],

					'harga_beli'		=> $data['harga_beli'],

				];



				$this->M_barang->insert_stok_barang($data_stok);
			} elseif ($stok->num_rows() > 0) {

				$get_stok = $stok->row_array();

				$id_stok_barang = $get_stok['id_stok_barang'];

				$stok_akhir = $get_stok['stok'] + $data['jumlah'];

				$data_stok	= [

					'id_stok_barang' => $id_stok_barang,

					'id_barang'		=> $data['id_barang'],

					'stok'		=> $stok_akhir,

					'harga_beli'		=> $data['harga_beli'],

				];



				$this->M_barang->update_stok_barang($data_stok);
			}



			if ($insert) {

				$this->session->set_flashdata('msg', 'error');

				redirect('tambah-barang-masuk');
			} else {

				$this->session->set_flashdata('msg', 'edit');

				redirect('barang-masuk');
			}
		}
	}



	public function edit_barang_masuk($id_barang_masuk)

	{

		$this->validation_barang_masuk();

		if (!$this->form_validation->run()) {

			$data['title']		= 'Data Barang';

			$data['title2']		= 'Barang Masuk';

			$data['barang']		= $this->M_barang->get_barang_general_supplier()->result_array();

			$data['b']	= $this->M_barang->get_barang_masuk_by_id($id_barang_masuk);

			$this->load->view('barang/edit_barang_masuk', $data);
		} else {

			$data		= $this->input->post(null, true);

			$data_user	= [

				'id_barang_masuk'			=> $id_barang_masuk,

				'id_barang'		=> $data['id_barang'],

				'tanggal_masuk'		=> $data['tanggal_masuk'],

				'jumlah'		=> $data['jumlah'],

				'harga_beli'		=> $data['harga_beli'],

			];



			$update = $this->M_barang->update_barang_masuk($data_user);

			log_activity($this->session->userdata('id_user'), 'Edit Barang Masuk', 'barang masuk berhasil diedit', $data_user);


			//UPDATE TRANSAKSI LAMA

			$stok_lama = $this->db->get_where('stok_barang', ['id_barang' => $data['id_barang_lama'], 'harga_beli' => $data['harga_beli_lama']]);



			$get_stok = $stok_lama->row_array();

			$id_stok_barang = $get_stok['id_stok_barang'];

			$stok_akhir = $get_stok['stok'] - $data['jumlah_lama'];

			$data_stok	= [

				'id_stok_barang' => $id_stok_barang,

				'stok'		=> $stok_akhir,

			];



			$this->M_barang->update_stok_barang($data_stok);



			//UDPATE TRANSAKSI BARU

			$stok = $this->db->get_where('stok_barang', ['id_barang' => $data['id_barang'], 'harga_beli' => $data['harga_beli']]);

			if ($stok->num_rows() == 0) {

				$data_stok	= [

					'id_barang'		=> $data['id_barang'],

					'stok'		=> $data['jumlah'],

					'harga_beli'		=> $data['harga_beli'],

				];



				$this->M_barang->insert_stok_barang($data_stok);
			} elseif ($stok->num_rows() > 0) {

				$get_stok = $stok->row_array();

				$id_stok_barang = $get_stok['id_stok_barang'];

				$stok_akhir = $get_stok['stok'] + $data['jumlah'];

				$data_stok	= [

					'id_stok_barang' => $id_stok_barang,

					'id_barang'		=> $data['id_barang'],

					'stok'		=> $stok_akhir,

					'harga_beli'		=> $data['harga_beli'],

				];



				$this->M_barang->update_stok_barang($data_stok);
			}



			if ($update) {

				$this->session->set_flashdata('msg', 'error');

				redirect('edit-barang-masuk/' . $id_barang_masuk);
			} else {

				$this->session->set_flashdata('msg', 'edit');

				redirect('barang-masuk');
			}
		}
	}



	public function hapus_barang_masuk($id_barang_masuk)

	{

		$bm = $this->M_barang->get_barang_masuk_by_id($id_barang_masuk);

		$id_barang = $bm['id_barang'];

		$jumlah = $bm['jumlah'];

		$harga_beli = $bm['harga_beli'];



		//UPDATE TRANSAKSI LAMA

		$stok_lama = $this->db->get_where('stok_barang', ['id_barang' => $id_barang, 'harga_beli' => $harga_beli]);

		log_activity($this->session->userdata('id_user'), 'Hapus Barang Masuk', 'barang masuk berhasil dihapus', $bm);


		$get_stok = $stok_lama->row_array();

		$id_stok_barang = $get_stok['id_stok_barang'];

		$stok_akhir = $get_stok['stok'] - $jumlah;

		$data_stok	= [

			'id_stok_barang' => $id_stok_barang,

			'stok'		=> $stok_akhir,

		];



		$this->M_barang->update_stok_barang($data_stok);



		$this->M_barang->delete_barang_masuk($id_barang_masuk);

		$this->session->set_flashdata('msg', 'hapus');

		redirect('barang-masuk');
	}



	public function tracking_barang()

	{

		$this->validation_tracking_barang();


		if (!$this->form_validation->run()) {

			$data['title']		= 'Data Barang';

			$data['title2']		= 'Tracking Barang';

			$data['barang']		=  $this->M_barang->get_barang2()->result_array();

			$this->load->view('barang/tracking_barang', $data);
		} else {

			$id_barang = $this->input->post('id_barang');

			if ($this->input->post('filter')) {

				$data['title']		= 'Data Barang';

				$data['title2']		= 'Tracking Barang';
				$data['barang']		=  $this->M_barang->get_barang2()->result_array();
				$data['b']			= $this->M_barang->get_barang_by_id($id_barang);
				$data['stok']		= $this->M_barang->get_stok_barang_by_id($id_barang);

				$data['baranglists']		= $this->M_barang->get_tracking_barang_by_id($id_barang)->result_array();

				$this->load->view('barang/tracking_barang', $data);
			} else {

				$data['title']		= 'Data Barang';

				$data['title2']		= 'Tracking Barang';

				$data['b']			= $this->M_barang->get_barang_by_id($id_barang);

				$data['stok']		= $this->M_barang->get_stok_barang_by_id($id_barang);

				$data['barang']		= $this->M_barang->get_tracking_barang_by_id($id_barang)->result_array();

				$this->load->view('barang/cetak_tracking_barang', $data);
			}
		}
	}



	public function stok_opname()

	{

		$data['title']		= 'Data Barang';

		$data['title2']		= 'Stok Opname';

		$data['jenis_barang'] = '';

		$post_jb = $this->input->post('jenis_barang');

		$barang = $this->M_barang->get_stok_opname()->result_array();

		if (empty($post_jb)) {

			$data['jenis_barang'] = 'All';
		} else {

			$data['jenis_barang'] = $post_jb;

			$barang = $this->M_barang->get_stok_opname($post_jb)->result_array();
		}

		$data['barang']		= $barang;



		$this->load->view('barang/data_stok_opname', $data);
	}



	public function tambah_stok_opname()

	{

		$this->validation_stok_opname();

		if (!$this->form_validation->run()) {

			$data['title']		= 'Data Barang';

			$data['title2']		= 'Stok Opname';

			$data['barang']		= $this->M_barang->get_available_stok_barang()->result_array();

			$this->load->view('barang/tambah_stok_opname', $data);
		} else {

			$data		= $this->input->post(null, true);

			$stok = $this->db->get_where('stok_barang', ['id_stok_barang' => $data['id_stok_barang']])->row_array();



			// if ($data['jumlah'] > $stok['stok']) {

			// 	$this->session->set_flashdata('msg', 'error-stok');

			// 	redirect('tambah-stok-opname');

			// }



			$data_user	= [

				'id_stok_barang'		=> $data['id_stok_barang'],

				'tanggal'		=> $data['tanggal'],

				'jumlah'		=> $data['jumlah'],

				'keterangan'		=> $data['keterangan'],

			];



			$insert = $this->M_barang->insert_stok_opname($data_user);

			log_activity($this->session->userdata('id_user'), 'Tambah Stock Opname', 'Stock Opname berhasil ditambahkan', $data_user);


			$stok_akhir = $stok['stok'] + $data['jumlah'];

			$data_stok	= [

				'id_stok_barang' => $data['id_stok_barang'],

				'stok'		=> $stok_akhir,

			];



			// $this->M_barang->update_stok_barang($data_stok);



			if ($insert) {

				$this->session->set_flashdata('msg', 'error');

				redirect('tambah-stok-opname');
			} else {

				$this->session->set_flashdata('msg', 'edit');

				redirect('stok-opname');
			}
		}
	}



	public function edit_stok_opname($id_stok_opname)

	{

		$this->validation_stok_opname();

		if (!$this->form_validation->run()) {

			$data['title']		= 'Data Barang';

			$data['title2']		= 'Stok Opname';

			$data['barang']		= $this->M_barang->get_available_stok_barang()->result_array();

			$data['so']	= $this->M_barang->get_stok_opname_by_id($id_stok_opname);

			$this->load->view('barang/edit_stok_opname', $data);
		} else {

			$data		= $this->input->post(null, true);

			$stok = $this->db->get_where('stok_barang', ['id_stok_barang' => $data['id_stok_barang']])->row_array();



			// if ($data['id_stok_barang_old'] == $data['id_stok_barang']) {

			// 	if ($data['jumlah'] > ($stok['stok'] - $data['jumlah_old'])) {

			// 		$this->session->set_flashdata('msg', 'error-stok');

			// 		redirect('edit-stok-opname/'.$id_stok_opname);

			// 	}

			// } else {

			// 	if ($data['jumlah'] > $stok['stok']) {

			// 		$this->session->set_flashdata('msg', 'error-stok');

			// 		redirect('edit-stok-opname/'.$id_stok_opname);

			// 	}

			// }



			$data_user	= [

				'id_stok_opname'			=> $id_stok_opname,

				'id_stok_barang'		=> $data['id_stok_barang'],

				'tanggal'		=> $data['tanggal'],

				'jumlah'		=> $data['jumlah'],

				'keterangan'		=> $data['keterangan'],

			];



			$update = $this->M_barang->update_stok_opname($data_user);


			log_activity($this->session->userdata('id_user'), 'Edit Stock Opname', 'Stock Opname berhasil diedit', $data_user);

			//UPDATE TRANSAKSI LAMA

			$stok_lama = $this->db->get_where('stok_barang', ['id_stok_barang' => $data['id_stok_barang_old']])->row_array();



			$id_stok_barang_lama = $data['id_stok_barang_old'];

			$stok_akhir_lama = $stok_lama['stok'] - $data['jumlah_old'];

			$data_stok_lama	= [

				'id_stok_barang' => $id_stok_barang_lama,

				'stok'		=> $stok_akhir_lama,

			];



			// $this->M_barang->update_stok_barang($data_stok_lama);



			//UDPATE TRANSAKSI BARU

			$stok = $this->db->get_where('stok_barang', ['id_stok_barang' => $data['id_stok_barang']])->row_array();

			$id_stok_barang = $data['id_stok_barang'];

			$stok_akhir = $stok['stok'] + $data['jumlah'];

			$data_stok	= [

				'id_stok_barang' => $id_stok_barang,

				'stok'		=> $stok_akhir,

			];





			// $this->M_barang->update_stok_barang($data_stok);



			if ($update) {

				$this->session->set_flashdata('msg', 'error');

				redirect('edit-stok-opname/' . $id_stok_opname);
			} else {

				$this->session->set_flashdata('msg', 'edit');

				redirect('stok-opname');
			}
		}
	}



	public function hapus_stok_opname($id_stok_opname)

	{

		$so = $this->M_barang->get_stok_opname_by_id($id_stok_opname);

		$id_stok_barang = $so['id_stok_barang'];

		$jumlah = $so['jumlah'];



		//UPDATE TRANSAKSI LAMA

		$stok = $this->db->get_where('stok_barang', ['id_stok_barang' => $id_stok_barang])->row_array();

		log_activity($this->session->userdata('id_user'), 'Edit Stock Opname', 'Stock Opname berhasil diedit', $so);


		$stok_akhir = $stok['stok'] - $jumlah;

		$data_stok	= [

			'id_stok_barang' => $id_stok_barang,

			'stok'		=> $stok_akhir,

		];



		// $this->M_barang->update_stok_barang($data_stok);



		$this->M_barang->delete_stok_opname($id_stok_opname);

		$this->session->set_flashdata('msg', 'hapus');

		redirect('stok-opname');
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
}
