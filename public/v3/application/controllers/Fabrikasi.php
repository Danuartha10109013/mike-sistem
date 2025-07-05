<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fabrikasi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('login') != TRUE)
		{
			set_pesan('Silahkan login terlebih dahulu', false);
			redirect('');
		}
		date_default_timezone_set("Asia/Jakarta");
	}

	public function get_detail_po_fabrikasi(){
	    $id_detail_po_fabrikasi = $this->input->post('id_detail_po_fabrikasi');
	    //var_dump($id_barang);
	    //die();
	    $data = $this->M_fabrikasi->get_detail_outstanding_by_id($id_detail_po_fabrikasi);
	    $get_po = $this->M_fabrikasi->get_outstanding_by_id($data['id_po_fabrikasi']);
	    
	    // Buat variabel untuk menampung tag-tag option nya
	    // Set defaultnya dengan tag option Pilih
	    if (empty($data)){
	    	$list1 = "<input type='number' name='jumlah_po' value='0' class='form-control' required/>";
	      $list2 = "<input type='number' name='harga_barang' value='0' class='form-control' required/>";
	      $list3 = "<input type='text' name='no_po' value='' class='form-control' required/>";
	    }else{

	      $list1 = "<input type='number' name='jumlah_po' value='".$data['jumlah_barang']."' class='form-control' readonly/>";
	      $list2 = "<input type='number' name='harga_barang' value='".$data['harga_barang']."' class='form-control' required/>";
	      $list3 = "<input type='text' name='no_po' value='".$get_po['no_po']."' class='form-control' readonly/>";
	    }
	    
	     // Tambahkan tag option ke variabel $lists
	    
	    
	    $a = array('jumlah_po'=>$list1, 'harga'=>$list2, 'no_po'=>$list3); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
	    echo json_encode($a); // konversi varibael $callback menjadi JSON
	}

	public function get_harga(){
	    $id_barang = $this->input->post('id_barang');
	    //var_dump($id_barang);
	    //die();
	    $data = $this->db->get_where('barang', ['id_barang' => $id_barang])->row();
	    
	    // Buat variabel untuk menampung tag-tag option nya
	    // Set defaultnya dengan tag option Pilih
	    if (empty($data)){
	      $list = "<input type='number' name='harga_barang' value='0' class='form-control' required/>";
	    }else{
	      $list = "<input type='number' name='harga_barang' value='".$data->harga_barang."' class='form-control' required/>";
	    }
	     // Tambahkan tag option ke variabel $lists
	    
	    
	    $a = array('harga'=>$list); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
	    echo json_encode($a); // konversi varibael $callback menjadi JSON
	}

	public function get_harga_for_pbo(){
		$id_barang = $this->input->post('id_barang');
		//var_dump($id_barang);
		//die();
		$data = $this->db->get_where('barang', ['id_barang' => $id_barang])->row();
		
		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		if (empty($data)){
			$desc = '';
			$uom = '';
			$amount = 0;
			$list = "<input type='number' name='amount' value='0' class='form-control' required/>";
		}else{
			$desc = $data->nama_barang;
			$uom = $data->satuan_barang;
			$amount = $data->harga_barang;
			$list = "<input type='number' name='amount' value='".$data->harga_barang."' class='form-control' required/>";
		}
		 // Tambahkan tag option ke variabel $lists
		
		
		$a = [
			'deskripsi' => $desc,
			'uom' => $uom,
			'value_amount' => $amount,
			'amount' => $list
		]; // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
		echo json_encode($a); // konversi varibael $callback menjadi JSON
}

	//Barang
	
	// public function index()
	// {
    //     $data['title']		= 'Fabrikasi';
    //     $data['title2']		= 'Invoice & Surat Jalan';
	// 	$data['invoice']	= $this->M_fabrikasi->get('sintesa')->result_array();
	// 	$this->load->view('fabrikasi/data', $data);
	// }

	// public function index()
	// {
	// 	$data['title']				= 'Fabrikasi';
	// 	$data['title2']				= 'Invoice & Surat Jalan';
	// 	$this->load->view('fabrikasi/data', $data);
	// }

	public function index($cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$data['title']				= 'Fabrikasi '.$cab['nama_cabang'];
		$data['title2']				= 'Invoice & Surat Jalan '.$cab['nama_cabang'];
		$data['cabang']				= $cabang;
		$this->load->view('fabrikasi/data', $data);
	}

	// public function server_side()
	// {
	// 	$fabrikasi = array();
    //     $limit          = html_escape($this->input->post('length'));
    //     $start          = html_escape($this->input->post('start'));
    //     $totalData 		= $this->M_fabrikasi->get_all()->num_rows();
    //     $totalFiltered  = $totalData;

	// 	if (!empty($this->input->post('search')['value'])) {
	// 		$search = $this->input->post('search')['value'];
	// 		$fabrikasi =  $this->M_fabrikasi->get_all($limit, $start, $search)->result_array();
	// 		$totalFiltered = $this->M_fabrikasi->count_all($search);
	// 	} else {
	// 		$fabrikasi = $this->M_fabrikasi->get_all($limit, $start)->result_array();
	// 	}

    //     $data = array();
    //     if (!empty($fabrikasi)) {
    //         foreach ($fabrikasi as $key => $row) {
	// 			$link = "'".base_url('hapus-invoice-fabrikasi/'.$row['id_fabrikasi'])."'";
	// 			$mid = "document.location.href=$link";
	// 			$a = 'data-confirm-yes="'.$mid.';"';

    //             $action = "
	// 			<a href=".base_url('detail-invoice-fabrikasi/'.$row['id_fabrikasi'])." class='btn btn-light'><i class='fa fa-list'></i> Detail</a>
	// 			<a href=".base_url('edit-invoice-fabrikasi/'.$row['id_fabrikasi'])." class='btn btn-info'><i class='fa fa-edit'></i> Edit</a>
	// 			<button class='btn btn-danger' data-confirm='Anda yakin ingin menghapus data ini?|Data yang sudah dihapus tidak akan kembali.' $a onclick='del($(this))'><i class='fa fa-trash'></i> Delete</button>";

	// 			$nestedData['#'] = $key + 1;
    //             $nestedData['no_invoice'] = $row['no_invoice'];
    //             $nestedData['tanggal'] = $row['tanggal'];
    //             $nestedData['total_harga_barang_sum'] = 'Rp '. number_format($row['total_harga_barang_sum'], 2,',','.');
    //             $nestedData['ppn_status'] = $row['ppn_status'];
    //             $nestedData['actions'] = $action;
    //             $data[] = $nestedData;
    //         }
    //     }
    //     $json_data = array(
    //         "draw"            => intval($this->input->post('draw')),
    //         "recordsTotal"    => intval($totalData),
    //         "recordsFiltered" => intval($totalFiltered),
    //         "data"            => $data
    //     );
    //     echo json_encode($json_data);
	// }

	public function server_side($cabang)
	{
		$id_cabang = $this->id_cabang($cabang);
		$fabrikasi = array();
        $limit          = html_escape($this->input->post('length'));
        $start          = html_escape($this->input->post('start'));
        $totalData 		= $this->M_fabrikasi->get_all(null, null, null, $id_cabang)->num_rows();
        $totalFiltered  = $totalData;

		if (!empty($this->input->post('search')['value'])) {
			$search = $this->input->post('search')['value'];
			$fabrikasi =  $this->M_fabrikasi->get_all($limit, $start, $search, $id_cabang)->result_array();
			$totalFiltered = $this->M_fabrikasi->count_all($search, $id_cabang);
		} else {
			$fabrikasi = $this->M_fabrikasi->get_all($limit, $start, null, $id_cabang)->result_array();
		}

        $data = array();
        if (!empty($fabrikasi)) {
            foreach ($fabrikasi as $key => $row) {
				$link = "'".base_url('hapus-invoice-fabrikasi/'.$row['id_fabrikasi'].'/'.$cabang)."'";
				$mid = "document.location.href=$link";
				$a = 'data-confirm-yes="'.$mid.';"';

                $action = "
				<a href=".base_url('detail-invoice-fabrikasi/'.$row['id_fabrikasi'].'/'.$cabang)." class='btn btn-light'><i class='fa fa-list'></i> Detail</a>
				<a href=".base_url('edit-invoice-fabrikasi/'.$row['id_fabrikasi'].'/'.$cabang)." class='btn btn-info'><i class='fa fa-edit'></i> Edit</a>
				<button class='btn btn-danger' data-confirm='Anda yakin ingin menghapus data ini?|Data yang sudah dihapus tidak akan kembali.' $a onclick='del($(this))'><i class='fa fa-trash'></i> Delete</button>";

				$nestedData['#'] = $key + 1;
                $nestedData['no_invoice'] = $row['no_invoice'];
                $nestedData['tanggal'] = $row['tanggal'];
                $nestedData['total_harga_barang_sum'] = 'Rp '. number_format($row['total_harga_barang_sum'], 2,',','.');
                $nestedData['ppn_status'] = $row['ppn_status'];
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

	public function aj()
	{
        $data['title']		= 'Fabrikasi';
        $data['title2']		= 'Invoice & Surat Jalan (AJ)';
		$data['invoice']	= $this->M_fabrikasi->get('aj')->result_array();
		$this->load->view('fabrikasi/data_aj', $data);
	}

	// public function tambah()
	// {
	// 	$this->validation();
	// 	if (!$this->form_validation->run()) {
	// 		$tahun 			= date('Y');
	// 		$bulan 			= $this->bulan_romawi(date('F'));
	// 		$data['title']	= 'Fabrikasi';
	// 		$data['title2']	= 'Invoice & Surat Jalan';
	// 		$last_invoice	= $this->db->select('*')
	// 			->from('fabrikasi')
	// 			->order_by('id_fabrikasi', 'DESC')
	// 			->where("mode", 'sintesa')
	// 			->where("DATE_FORMAT(tanggal, '%Y') =", $tahun)
	// 			->get()->row_array();
	// 		// $last_invoice_cs	= 	$this->db->select('*')
	// 		// 	->from('cs')
	// 		// 	->order_by('id_cs', 'DESC')
	// 		// 	->where("DATE_FORMAT(tanggal, '%Y') =", $tahun)
	// 		// 	->get()->row_array();
	// 		// $last_invoice_gs	= 	$this->db->select('*')
	// 		// 	->from('gs')
	// 		// 	->order_by('id_gs', 'DESC')
	// 		// 	->where("mode", 'sintesa')
	// 		// 	->where("DATE_FORMAT(tanggal, '%Y') =", $tahun)
	// 		// 	->get()->row_array();
	// 		if(empty($last_invoice)){
	// 			$data['no_invoice'] = '1/FAB/TKG/'.$bulan.'/'.$tahun;
	// 		} else {
	// 			$pch_invoice= explode('/', $last_invoice['no_invoice']);
	// 			// $pch_invoice_cs= explode('/', $last_invoice_cs['no_invoice']);
	// 			// $pch_invoice_gs= explode('/', $last_invoice_gs['no_invoice']);

	// 			$no	= intval($pch_invoice[0]) + 1;
	// 			$data['no_invoice'] = $no.'/FAB/TKG/'.$bulan.'/'.$tahun;
	// 			// $no_cs = intval($pch_invoice_cs[0]) + 1;
	// 			// $no_gs = intval($pch_invoice_gs[0]) + 1;
	// 			// if($no > $no_cs && $no > $no_gs){
	// 			// 	$data['no_invoice'] = $no.'/FAB/TKG/'.$bulan.'/'.$tahun;
	// 			// }elseif($no_cs > $no && $no_cs > $no_gs){
	// 			// 	$data['no_invoice'] = $no_cs.'/FAB/TKG/'.$bulan.'/'.$tahun;
	// 			// }else{
	// 			// 	$data['no_invoice'] = $no_gs.'/FAB/TKG/'.$bulan.'/'.$tahun;
	// 			// }
        		
	// 		}
	// 		$this->load->view('fabrikasi/tambah', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$bulan 			= $this->bulan_romawi(date('F', strtotime($data['tanggal'])));
	// 		$tahun 			= date('Y', strtotime($data['tanggal']));
	// 		$pch_no_invoice = explode('/', $data['no_invoice']);
	// 		$no_invoice = $pch_no_invoice[0].'/FAB/TKG/'.$bulan.'/'.$tahun;
	// 		$data_user	= [
	// 			'no_invoice'	=> $no_invoice,
	// 			'tanggal'		=> $data['tanggal'],
	// 			'mode'			=> 'sintesa'
	// 		];

			
	// 		if ($this->M_fabrikasi->insert($data_user)) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('tambah-invoice-fabrikasi');
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'success');
	// 			redirect('invoice-fabrikasi');
	// 		}
	// 	}
	// }

	public function tambah($cabang)
	{
		$id_cabang = $this->id_cabang($cabang);
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->validation();
		if (!$this->form_validation->run()) {
			$tahun 			= date('Y');
			$bulan 			= $this->bulan_romawi(date('F'));
			$data['title']	= 'Fabrikasi '.$cab['nama_cabang'];
			$data['title2']	= 'Invoice & Surat Jalan '.$cab['nama_cabang'];
			$data['cabang']	= $cabang;
			$last_invoice	= $this->db->select('*')
				->from('fabrikasi')
				->where('id_cabang', $id_cabang)
				->order_by('id_fabrikasi', 'DESC')
				->where("mode", 'sintesa')
				->where("DATE_FORMAT(tanggal, '%Y') =", $tahun)
				->get()->row_array();
			if(empty($last_invoice)){
				$data['no_invoice'] = '1/FAB/TKG/'.$bulan.'/'.$tahun;
			} else {
				$pch_invoice= explode('/', $last_invoice['no_invoice']);
				$no	= intval($pch_invoice[0]) + 1;
				$data['no_invoice'] = $no.'/FAB/TKG/'.$bulan.'/'.$tahun;
			}
			$this->load->view('fabrikasi/tambah', $data);
		} else {
			$data			= $this->input->post(null, true);
			$bulan 			= $this->bulan_romawi(date('F', strtotime($data['tanggal'])));
			$tahun 			= date('Y', strtotime($data['tanggal']));
			$pch_no_invoice = explode('/', $data['no_invoice']);
			$no_invoice = $pch_no_invoice[0].'/FAB/TKG/'.$bulan.'/'.$tahun;
			$data_user	= [
				'id_cabang'		=> $id_cabang,
				'no_invoice'	=> $no_invoice,
				'tanggal'		=> $data['tanggal'],
				'mode'			=> 'sintesa'
			];

			if ($this->M_fabrikasi->insert($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-invoice-fabrikasi/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('invoice-fabrikasi/'.$cabang);
			}
		}
	}

	public function tambah_aj()
	{
		$this->validation();
		if (!$this->form_validation->run()) {
			$tahun 			= date('Y');
			$bulan 			= $this->bulan_romawi(date('F'));
			$data['title']	= 'Fabrikasi';
        	$data['title2']	= 'Invoice & Surat Jalan (AJ)';
        	$last_invoice	= $this->db->select('*')
								->from('fabrikasi')
								->order_by('id_fabrikasi', 'DESC')
								->where("mode", 'aj')
								->where("DATE_FORMAT(tanggal, '%Y') =", $tahun)
								->get()->row_array();
			$last_invoice_gs	= 	$this->db->select('*')
								->from('gs')
								->order_by('id_gs', 'DESC')
								->where("mode", 'aj')
								->where("DATE_FORMAT(tanggal, '%Y') =", $tahun)
								->get()->row_array();
			if(empty($last_invoice)){
				$data['no_invoice'] = '1/FAB/TKG/'.$bulan.'/'.$tahun;
			} else {
				$pch_invoice= explode('/', $last_invoice['no_invoice']);
				$pch_invoice_gs= explode('/', $last_invoice_gs['no_invoice']);

        		$no	= intval($pch_invoice[0]) + 1;
        		$no_gs = intval($pch_invoice_gs[0]) + 1;
        		if($no > $no_gs){
        			$data['no_invoice'] = $no.'/FAB/TKG/'.$bulan.'/'.$tahun;
        		}else{
        			$data['no_invoice'] = $no_gs.'/FAB/TKG/'.$bulan.'/'.$tahun;
        		}
        		
			}
			$this->load->view('fabrikasi/tambah_aj', $data);
		} else {
			$data		= $this->input->post(null, true);
			$bulan 			= $this->bulan_romawi(date('F', strtotime($data['tanggal'])));
			$tahun 			= date('Y', strtotime($data['tanggal']));
			$pch_no_invoice = explode('/', $data['no_invoice']);
			$no_invoice = $pch_no_invoice[0].'/TKG/'.$bulan.'/'.$tahun;
			$data_user	= [
				'no_invoice'	=> $no_invoice,
				'tanggal'		=> $data['tanggal'],
				'mode'			=> 'aj'
			];

			
			if ($this->M_fabrikasi->insert($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-invoice-fabrikasi-aj');
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('invoice-fabrikasi-aj');
			}
		}
	}

	// public function edit($id_fabrikasi)
	// {
	// 	$this->validation();
	// 	if (!$this->form_validation->run()) {
	// 		$data['title']		= 'Fabrikasi';
    //     	$data['title2']		= 'Invoice & Surat Jalan';
	// 		$data['f']	= $this->M_fabrikasi->get_by_id($id_fabrikasi);
	// 		$this->load->view('fabrikasi/edit', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$bulan 			= $this->bulan_romawi(date('F', strtotime($data['tanggal'])));
	// 		$tahun 			= date('Y', strtotime($data['tanggal']));
	// 		$pch_no_invoice = explode('/', $data['no_invoice']);
	// 		$no_invoice = $pch_no_invoice[0].'/FAB/TKG/'.$bulan.'/'.$tahun;
	// 		$data_user	= [
	// 			'id_fabrikasi'	=> $id_fabrikasi,
	// 			'no_invoice'	=> $no_invoice,
	// 			'tanggal'		=> $data['tanggal'],
	// 		];

			
	// 		if ($this->M_fabrikasi->update($data_user)) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('edit-invoice-fabrikasi/'.$id_fabrikasi);
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'edit');
	// 			redirect('invoice-fabrikasi');
	// 		}
	// 	}
	// }

	public function edit($id_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->validation();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Fabrikasi '.$cab['nama_cabang'];
        	$data['title2']		= 'Invoice & Surat Jalan '.$cab['nama_cabang'];
			$data['cabang']		= $cabang;
			$data['f']			= $this->M_fabrikasi->get_by_id($id_fabrikasi);
			$this->load->view('fabrikasi/edit', $data);
		} else {
			$data			= $this->input->post(null, true);
			$bulan 			= $this->bulan_romawi(date('F', strtotime($data['tanggal'])));
			$tahun 			= date('Y', strtotime($data['tanggal']));
			$pch_no_invoice = explode('/', $data['no_invoice']);
			$no_invoice = $pch_no_invoice[0].'/FAB/TKG/'.$bulan.'/'.$tahun;
			$data_user	= [
				'id_fabrikasi'	=> $id_fabrikasi,
				'no_invoice'	=> $no_invoice,
				'tanggal'		=> $data['tanggal'],
			];
			
			if ($this->M_fabrikasi->update($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-invoice-fabrikasi/'.$id_fabrikasi.'/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('invoice-fabrikasi/'.$cabang);
			}
		}
	}

	// public function hapus($id_fabrikasi)
	// {
	// 	$this->M_fabrikasi->delete($id_fabrikasi);
	// 	$this->session->set_flashdata('msg', 'hapus');
	// 	redirect('invoice-fabrikasi');
	// }

	public function hapus($id_fabrikasi, $cabang)
	{
		$this->M_fabrikasi->delete($id_fabrikasi);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('invoice-fabrikasi/'.$cabang);
	}

	public function edit_aj($id_fabrikasi)
	{
		$this->validation();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Fabrikasi';
        	$data['title2']		= 'Invoice & Surat Jalan (AJ)';
			$data['f']	= $this->M_fabrikasi->get_by_id($id_fabrikasi);
			$this->load->view('fabrikasi/edit_aj', $data);
		} else {
			$data		= $this->input->post(null, true);
			$bulan 			= $this->bulan_romawi(date('F', strtotime($data['tanggal'])));
			$tahun 			= date('Y', strtotime($data['tanggal']));
			$pch_no_invoice = explode('/', $data['no_invoice']);
			$no_invoice = $pch_no_invoice[0].'/TKG/'.$bulan.'/'.$tahun;
			$data_user	= [
				'id_fabrikasi'	=> $id_fabrikasi,
				'no_invoice'	=> $no_invoice,
				'tanggal'		=> $data['tanggal'],
			];

			
			if ($this->M_fabrikasi->update($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-invoice-fabrikasi-aj/'.$id_fabrikasi);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('invoice-fabrikasi-aj');
			}
		}
	}

	public function hapus_aj($id_fabrikasi)
	{
		$this->M_fabrikasi->delete($id_fabrikasi);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('invoice-fabrikasi-aj');
	}

	//DETAIL
	// public function detail($id_fabrikasi)
	// {
	// 	$data['title']		= 'Fabrikasi';
    // 	$data['title2']		= 'Invoice & Surat Jalan';
	// 	$data['f']			= $this->M_fabrikasi->get_by_id($id_fabrikasi);
	// 	$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));
	// 	$bulan 				= $this->bulan($pch_tgl[1]);
	// 	$data['tanggal']	= date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));
	// 	$data['f_detail']	= $this->M_fabrikasi->get_detail($id_fabrikasi)->result_array();
	// 	$this->load->view('fabrikasi/detail', $data);
	// }

	public function detail($id_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$data['title']		= 'Fabrikasi '.$cab['nama_cabang'];
    	$data['title2']		= 'Invoice & Surat Jalan '.$cab['nama_cabang'];
		$data['f']			= $this->M_fabrikasi->get_by_id($id_fabrikasi);
		$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));
		$bulan 				= $this->bulan($pch_tgl[1]);
		$data['cabang']		= $cabang;
		$data['tanggal']	= date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));
		$data['f_detail']	= $this->M_fabrikasi->get_detail($id_fabrikasi, $cab['id_cabang'])->result_array();
		$this->load->view('fabrikasi/detail', $data);
	}

	public function tambah_detail($id_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->validation_detail();
		if (!$this->form_validation->run()) {
			$data['title']			= 'Fabrikasi '.$cab['nama_cabang'];
			$data['title2']			= 'Invoice & Surat Jalan '.$cab['nama_cabang'];
			$data['cabang']			= $cabang;
			$data['id_fabrikasi'] 	= $id_fabrikasi;
			$this->db->select('*');
			$this->db->from('detail_po_fabrikasi');
			$this->db->join('po_fabrikasi', 'po_fabrikasi.id_po_fabrikasi=detail_po_fabrikasi.id_po_fabrikasi');
			$this->db->where('detail_po_fabrikasi.id_cabang', $cab['id_cabang']);
			$this->db->where('detail_po_fabrikasi.jumlah_barang !=', 0);
			$this->db->where('po_fabrikasi.no_po !=', '-');
			$data['barang']		= $this->db->get()->result_array();
			$this->load->view('fabrikasi/tambah_detail', $data);
		} else {
			$data		= $this->input->post(null, true);
			$id_detail_po_fabrikasi	= $data['id_detail_po_fabrikasi'];
			$o = $this->M_fabrikasi->get_detail_outstanding_by_id($id_detail_po_fabrikasi);
	    	$get_po = $this->M_fabrikasi->get_outstanding_by_id($o['id_po_fabrikasi']);

	    	if($data['jumlah_barang'] < 1 || $data['jumlah_barang'] > $o['jumlah_barang']){
	    		$this->session->set_flashdata('msg', 'error_jml_barang');
				redirect('tambah-detail-invoice-fabrikasi/'.$id_fabrikasi.'/'.$cabang);
	    	}
		
			$data_user	= [
				'id_fabrikasi'				=> $id_fabrikasi,
				'id_detail_po_fabrikasi'	=> $id_detail_po_fabrikasi,
				'id_barang'					=> $o['id_barang'],
				'nama_barang'				=> $o['nama_barang'],
				'kode_barang'				=> $o['kode_barang'],
				'satuan_barang'				=> $o['satuan_barang'],
				'jumlah_barang'				=> $data['jumlah_barang'],
				'harga_unit'				=> $o['harga_unit'],
				'harga_instalasi'			=> $o['harga_instalasi'],
				'harga_barang'				=> $data['harga_barang'],
				'total_harga_barang'		=> $data['jumlah_barang']*$data['harga_barang'],
				'no_po'						=> $data['no_po'],
				'departemen'				=> $o['departemen'],
				'id_cabang'					=> $cab['id_cabang']
			];
			$insert = $this->M_fabrikasi->insert_detail($data_user);

			$sisa_barang = $o['jumlah_barang'] - $data['jumlah_barang'];

			if($sisa_barang == 0){
				$detail_po = $this->db->get_where('detail_po_fabrikasi', ['id_detail_po_fabrikasi' => $id_detail_po_fabrikasi])->row_array();
				$po = $this->db->get_where('po_fabrikasi', ['id_po_fabrikasi' => $detail_po['id_po_fabrikasi']])->row_array();
				$jml = $this->db->query("SELECT sum(jumlah_barang) as jml_barang FROM detail_fabrikasi WHERE id_detail_po_fabrikasi=".$id_detail_po_fabrikasi."")->row_array();
				$jml_barang = $jml['jml_barang'];
				//var_dump($jml_barang);
				$data_riwayat = [
					'id_detail_po_fabrikasi' 	=> $id_detail_po_fabrikasi,
					'no_po' 					=> $po['no_po'],
					'tanggal_po' 				=> $po['tanggal'],
					'id_barang' 				=> $detail_po['id_barang'],
					'nama_barang' 				=> $detail_po['nama_barang'],
					'kode_barang' 				=> $detail_po['kode_barang'],
					'satuan_barang' 			=> $detail_po['satuan_barang'],
					'jumlah_barang' 			=> $jml_barang,
					'harga_unit'				=> $detail_po['harga_unit'],
					'harga_instalasi'			=> $detail_po['harga_instalasi'],
					'harga_barang' 				=> $detail_po['harga_barang'],
					'total_harga_barang' 		=> $detail_po['total_harga_barang'],
					'departemen' 				=> $detail_po['departemen'],
					'id_cabang'					=> $cab['id_cabang']
				];

				$this->db->insert('riwayat_po_fabrikasi', $data_riwayat);
				$this->db->delete('detail_po_fabrikasi', ['id_detail_po_fabrikasi' => $id_detail_po_fabrikasi]);
			} else {
				$data_po = [
					'id_detail_po_fabrikasi' => $id_detail_po_fabrikasi,
					'jumlah_barang' => $sisa_barang,
					'total_harga_barang' => $sisa_barang * $o['harga_barang'],
				];

				$this->M_fabrikasi->update_detail_outstanding($data_po);
			}

			if ($insert) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-detail-invoice-fabrikasi/'.$id_fabrikasi.'/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('detail-invoice-fabrikasi/'.$id_fabrikasi.'/'.$cabang);
			}
		}
	}

	// public function edit_detail($id_detail_fabrikasi, $id_fabrikasi)
	// {
	// 	$this->validation_detail();
	// 	if (!$this->form_validation->run()) {
	// 		$data['title']		= 'Fabrikasi';
    //     	$data['title2']		= 'Invoice & Surat Jalan';
    //     	$data['id_fabrikasi'] = $id_fabrikasi;
    //     	$data['barang']		= $this->M_fabrikasi->get_detail_outstanding()->result_array();
	// 		$data['fd']	= $this->M_fabrikasi->get_detail_by_id($id_detail_fabrikasi);
	// 		$data['detail_po'] = $this->M_fabrikasi->get_detail_outstanding_by_id($data['fd']['id_detail_po_fabrikasi']);
	// 		$this->load->view('fabrikasi/edit_detail', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$id_detail_po_fabrikasi	= $data['id_detail_po_fabrikasi'];
	// 		$o = $this->M_fabrikasi->get_detail_outstanding_by_id($id_detail_po_fabrikasi);
	//     	$get_po = $this->M_fabrikasi->get_outstanding_by_id($o['id_po_fabrikasi']);

	//     	if($data['jumlah_barang'] < 1 || $data['jumlah_barang'] > ($data['jumlah_barang_lama']+$o['jumlah_barang'])){
	//     		$this->session->set_flashdata('msg', 'error_jml_barang');
	// 			redirect('edit-detail-invoice-fabrikasi/'.$id_detail_fabrikasi.'/'.$id_fabrikasi);
	//     	}

	// 		$data_user	= [
	// 			'id_detail_fabrikasi'		=> $id_detail_fabrikasi,
	// 			'id_fabrikasi'		=> $id_fabrikasi,
	// 			'id_detail_po_fabrikasi'		=> $id_detail_po_fabrikasi,
	// 			'id_barang'			=> $o['id_barang'],
	// 			'nama_barang'		=> $o['nama_barang'],
	// 			'kode_barang'		=> $o['kode_barang'],
	// 			'satuan_barang'		=> $o['satuan_barang'],
	// 			'jumlah_barang'		=> $data['jumlah_barang'],
	// 			'harga_unit'		=> $o['harga_unit'],
	// 			'harga_instalasi'		=> $o['harga_instalasi'],
	// 			'harga_barang'		=> $data['harga_barang'],
	// 			'total_harga_barang'=> $data['jumlah_barang']*$data['harga_barang'],
	// 			'no_po'				=> $data['no_po'],
	// 			'departemen'		=> $o['departemen'],
	// 		];

	// 		$update = $this->M_fabrikasi->update_detail($data_user);

	// 		$sisa_barang = $data['jumlah_po'] + $data['jumlah_barang_lama'] - $data['jumlah_barang'];

	// 		if($sisa_barang == 0){
	// 			$detail_po = $this->db->get_where('detail_po_fabrikasi', ['id_detail_po_fabrikasi' => $id_detail_po_fabrikasi])->row_array();
	// 			$po = $this->db->get_where('po_fabrikasi', ['id_po_fabrikasi' => $detail_po['id_po_fabrikasi']])->row_array();
	// 			$jml_barang = $this->db->query("SELECT sum(jumlah_barang) as jml_barang FROM detail_fabrikasi WHERE id_detail_po_fabrikasi=".$id_detail_po_fabrikasi."")->row_array();
	// 			$jml_barang = $jml_barang['jml_barang'];
	// 			//var_dump($jml_barang);
	// 			$data_riwayat = [
	// 				'id_detail_po_fabrikasi' => $id_detail_po_fabrikasi,
	// 				'no_po' => $po['no_po'],
	// 				'tanggal_po' => $po['tanggal'],
	// 				'id_barang' => $detail_po['id_barang'],
	// 				'nama_barang' => $detail_po['nama_barang'],
	// 				'kode_barang' => $detail_po['kode_barang'],
	// 				'satuan_barang' => $detail_po['satuan_barang'],
	// 				'jumlah_barang' => $jml_barang,
	// 				'harga_unit' => $detail_po['harga_unit'],
	// 				'harga_instalasi' => $detail_po['harga_instalasi'],
	// 				'harga_barang' => $detail_po['harga_barang'],
	// 				'total_harga_barang' => $detail_po['total_harga_barang'],
	// 				'departemen' => $detail_po['departemen'],
	// 			];

	// 			$this->db->insert('riwayat_po_fabrikasi', $data_riwayat);
	// 			$this->db->delete('detail_po_fabrikasi', ['id_detail_po_fabrikasi' => $id_detail_po_fabrikasi]);
	// 		} else {
	// 			$data_po = [
	// 				'id_detail_po_fabrikasi' => $id_detail_po_fabrikasi,
	// 				'jumlah_barang' => $sisa_barang,
	// 				'total_harga_barang' => $sisa_barang * $o['harga_barang'],
	// 			];

	// 			$this->M_fabrikasi->update_detail_outstanding($data_po);
	// 		}
			
	// 		if ($update) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('edit-detail-invoice-fabrikasi/'.$id_detail_fabrikasi.'/'.$id_fabrikasi);
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'edit');
	// 			redirect('detail-invoice-fabrikasi/'.$id_fabrikasi);
	// 		}
	// 	}
	// }

	public function edit_detail($id_detail_fabrikasi, $id_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->validation_detail();
		if (!$this->form_validation->run()) {
			$data['title']			= 'Fabrikasi '.$cab['nama_cabang'];
        	$data['title2']			= 'Invoice & Surat Jalan '.$cab['nama_cabang'];
			$data['cabang']			= $cabang;
        	$data['id_fabrikasi'] 	= $id_fabrikasi;
        	$data['barang']			= $this->M_fabrikasi->get_detail_outstanding(null, $cab['id_cabang'])->result_array();
			$data['fd']				= $this->M_fabrikasi->get_detail_by_id($id_detail_fabrikasi);
			$data['detail_po'] 		= $this->M_fabrikasi->get_detail_outstanding_by_id($data['fd']['id_detail_po_fabrikasi']);
			$this->load->view('fabrikasi/edit_detail', $data);
		} else {
			$data		= $this->input->post(null, true);
			$id_detail_po_fabrikasi	= $data['id_detail_po_fabrikasi'];
			$o = $this->M_fabrikasi->get_detail_outstanding_by_id($id_detail_po_fabrikasi);

	    	if($data['jumlah_barang'] < 1 || $data['jumlah_barang'] > ($data['jumlah_barang_lama']+$o['jumlah_barang'])){
	    		$this->session->set_flashdata('msg', 'error_jml_barang');
				redirect('edit-detail-invoice-fabrikasi/'.$id_detail_fabrikasi.'/'.$id_fabrikasi.'/'.$cabang);
	    	}

			$data_user	= [
				'id_detail_fabrikasi'		=> $id_detail_fabrikasi,
				'id_fabrikasi'				=> $id_fabrikasi,
				'id_detail_po_fabrikasi'	=> $id_detail_po_fabrikasi,
				'id_barang'					=> $o['id_barang'],
				'nama_barang'				=> $o['nama_barang'],
				'kode_barang'				=> $o['kode_barang'],
				'satuan_barang'				=> $o['satuan_barang'],
				'jumlah_barang'				=> $data['jumlah_barang'],
				'harga_unit'				=> $o['harga_unit'],
				'harga_instalasi'			=> $o['harga_instalasi'],
				'harga_barang'				=> $data['harga_barang'],
				'total_harga_barang'		=> $data['jumlah_barang']*$data['harga_barang'],
				'no_po'						=> $data['no_po'],
				'departemen'				=> $o['departemen'],
				'id_cabang'					=> $cab['id_cabang']
			];

			$update = $this->M_fabrikasi->update_detail($data_user);
			$sisa_barang = $data['jumlah_po'] + $data['jumlah_barang_lama'] - $data['jumlah_barang'];

			if($sisa_barang == 0){
				$detail_po = $this->db->get_where('detail_po_fabrikasi', ['id_detail_po_fabrikasi' => $id_detail_po_fabrikasi])->row_array();
				$po = $this->db->get_where('po_fabrikasi', ['id_po_fabrikasi' => $detail_po['id_po_fabrikasi']])->row_array();
				$jml_barang = $this->db->query("SELECT sum(jumlah_barang) as jml_barang FROM detail_fabrikasi WHERE id_detail_po_fabrikasi=".$id_detail_po_fabrikasi."")->row_array();
				$jml_barang = $jml_barang['jml_barang'];
				//var_dump($jml_barang);
				$data_riwayat = [
					'id_detail_po_fabrikasi' => $id_detail_po_fabrikasi,
					'no_po' => $po['no_po'],
					'tanggal_po' => $po['tanggal'],
					'id_barang' => $detail_po['id_barang'],
					'nama_barang' => $detail_po['nama_barang'],
					'kode_barang' => $detail_po['kode_barang'],
					'satuan_barang' => $detail_po['satuan_barang'],
					'jumlah_barang' => $jml_barang,
					'harga_unit' => $detail_po['harga_unit'],
					'harga_instalasi' => $detail_po['harga_instalasi'],
					'harga_barang' => $detail_po['harga_barang'],
					'total_harga_barang' => $detail_po['total_harga_barang'],
					'departemen' => $detail_po['departemen'],
					'id_cabang'	=> $cab['id_cabang']
				];

				$this->db->insert('riwayat_po_fabrikasi', $data_riwayat);
				$this->db->delete('detail_po_fabrikasi', ['id_detail_po_fabrikasi' => $id_detail_po_fabrikasi]);
			} else {
				$data_po = [
					'id_detail_po_fabrikasi' => $id_detail_po_fabrikasi,
					'jumlah_barang' => $sisa_barang,
					'total_harga_barang' => $sisa_barang * $o['harga_barang'],
				];

				$this->M_fabrikasi->update_detail_outstanding($data_po);
			}
			
			if ($update) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-detail-invoice-fabrikasi/'.$id_detail_fabrikasi.'/'.$id_fabrikasi.'/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('detail-invoice-fabrikasi/'.$id_fabrikasi.'/'.$cabang);
			}
		}
	}

	// public function hapus_detail($id_detail_fabrikasi, $id_fabrikasi)
	// {
	// 	$this->db->trans_start();
	// 	$fd	= $this->M_fabrikasi->get_detail_by_id($id_detail_fabrikasi);
	// 	$detail_po = $this->M_fabrikasi->get_detail_outstanding_by_id($fd['id_detail_po_fabrikasi']);
	// 	if(!empty($detail_po)){
	// 		$sisa_barang = $fd['jumlah_barang'] + $detail_po['jumlah_barang'];
	// 		$data_po = [
	// 			'id_detail_po_fabrikasi' => $fd['id_detail_po_fabrikasi'],
	// 			'jumlah_barang' => $sisa_barang,
	// 			'total_harga_barang' => $sisa_barang * $detail_po['harga_barang'],
	// 		];

	// 		$this->M_fabrikasi->update_detail_outstanding($data_po);
	// 	} else {
	// 		$cek_riwayat = $this->db->get_where('riwayat_po_fabrikasi', ['id_detail_po_fabrikasi' => $fd['id_detail_po_fabrikasi'], 'no_po' => $fd['no_po']])->num_rows();
	// 		if ($cek_riwayat == 0) {
	// 			$get_po = $this->db->get_where('po_fabrikasi', ['no_po' => $fd['no_po']])->row_array();
	// 			$data_riwayat = [
	// 				'id_detail_po_fabrikasi' => $fd['id_detail_po_fabrikasi'],
	// 				'no_po' => $fd['no_po'],
	// 				'tanggal_po' => $get_po['tanggal'],
	// 				'id_barang' => $fd['id_barang'],
	// 				'nama_barang' => $fd['nama_barang'],
	// 				'kode_barang' => $fd['kode_barang'],
	// 				'satuan_barang' => $fd['satuan_barang'],
	// 				'jumlah_barang' => $fd['jumlah_barang'],
	// 				'harga_barang' => $fd['harga_barang'],
	// 				'harga_beli' => $fd['harga_beli'],
	// 				'total_harga_barang' => $fd['total_harga_barang'],
	// 			];
				
	// 			$this->db->insert('riwayat_po_fabrikasi', $data_riwayat);
	// 		}
	// 		$get_riwayat_by_id = $this->db->get_where('riwayat_po_fabrikasi', ['id_detail_po_fabrikasi' => $fd['id_detail_po_fabrikasi'], 'no_po' => $fd['no_po']])->row_array();
	// 		$get_po = $this->db->get_where('po_fabrikasi', ['no_po' => $get_riwayat_by_id['no_po'], 'tanggal' => $get_riwayat_by_id['tanggal_po']])->row_array();
	// 		$data_po = [
	// 			'id_detail_po_fabrikasi' => $fd['id_detail_po_fabrikasi'],
	// 			'id_po_fabrikasi' => $get_po['id_po_fabrikasi'],
	// 			'id_barang' => $get_riwayat_by_id['id_barang'],
	// 			'nama_barang' => $get_riwayat_by_id['nama_barang'],
	// 			'kode_barang' => $get_riwayat_by_id['kode_barang'],
	// 			'satuan_barang' => $get_riwayat_by_id['satuan_barang'],
	// 			'jumlah_barang' => $fd['jumlah_barang'],
	// 			'harga_unit' => $get_riwayat_by_id['harga_unit'],
	// 			'harga_instalasi' => $get_riwayat_by_id['harga_instalasi'],
	// 			'harga_barang' => $get_riwayat_by_id['harga_barang'],
	// 			'total_harga_barang' => $get_riwayat_by_id['total_harga_barang'],
	// 			'departemen' => $get_riwayat_by_id['departemen'],
	// 		];
			
	// 		$this->M_fabrikasi->insert_detail_outstanding($data_po);
	// 		$this->db->delete('riwayat_po_fabrikasi', ['id_detail_po_fabrikasi' => $get_riwayat_by_id['id_detail_po_fabrikasi'], 'no_po' => $get_riwayat_by_id['no_po']]);
	// 	}
		
	// 	$this->M_fabrikasi->delete_detail($id_detail_fabrikasi);
	// 	$this->db->trans_complete();
	// 	if ($this->db->trans_status() === FALSE) {
	// 		$this->session->set_flashdata('msg', 'error');
	// 	} else {
	// 		$this->session->set_flashdata('msg', 'hapus');
	// 	}
	// 	redirect('detail-invoice-fabrikasi/'.$id_fabrikasi);
	// }

	public function hapus_detail($id_detail_fabrikasi, $id_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->db->trans_start();
		$fd	= $this->M_fabrikasi->get_detail_by_id($id_detail_fabrikasi);
		$detail_po = $this->M_fabrikasi->get_detail_outstanding_by_id($fd['id_detail_po_fabrikasi']);
		if(!empty($detail_po)){
			$sisa_barang = $fd['jumlah_barang'] + $detail_po['jumlah_barang'];
			$data_po = [
				'id_detail_po_fabrikasi' => $fd['id_detail_po_fabrikasi'],
				'jumlah_barang' => $sisa_barang,
				'total_harga_barang' => $sisa_barang * $detail_po['harga_barang'],
			];

			$this->M_fabrikasi->update_detail_outstanding($data_po);
		} else {
			$cek_riwayat = $this->db->get_where('riwayat_po_fabrikasi', ['id_detail_po_fabrikasi' => $fd['id_detail_po_fabrikasi'], 'no_po' => $fd['no_po']])->num_rows();
			if ($cek_riwayat == 0) {
				$get_po = $this->db->get_where('po_fabrikasi', ['no_po' => $fd['no_po']])->row_array();
				$data_riwayat = [
					'id_detail_po_fabrikasi' => $fd['id_detail_po_fabrikasi'],
					'no_po' => $fd['no_po'],
					'tanggal_po' => $get_po['tanggal'],
					'id_barang' => $fd['id_barang'],
					'nama_barang' => $fd['nama_barang'],
					'kode_barang' => $fd['kode_barang'],
					'satuan_barang' => $fd['satuan_barang'],
					'jumlah_barang' => $fd['jumlah_barang'],
					'harga_barang' => $fd['harga_barang'],
					'harga_beli' => $fd['harga_beli'],
					'total_harga_barang' => $fd['total_harga_barang'],
					'id_cabang'	=> $cab['id_cabang']
				];
				
				$this->db->insert('riwayat_po_fabrikasi', $data_riwayat);
			}
			$get_riwayat_by_id = $this->db->get_where('riwayat_po_fabrikasi', ['id_detail_po_fabrikasi' => $fd['id_detail_po_fabrikasi'], 'no_po' => $fd['no_po']])->row_array();
			$get_po = $this->db->get_where('po_fabrikasi', ['no_po' => $get_riwayat_by_id['no_po'], 'tanggal' => $get_riwayat_by_id['tanggal_po']])->row_array();
			$data_po = [
				'id_detail_po_fabrikasi' => $fd['id_detail_po_fabrikasi'],
				'id_po_fabrikasi' => $get_po['id_po_fabrikasi'],
				'id_barang' => $get_riwayat_by_id['id_barang'],
				'nama_barang' => $get_riwayat_by_id['nama_barang'],
				'kode_barang' => $get_riwayat_by_id['kode_barang'],
				'satuan_barang' => $get_riwayat_by_id['satuan_barang'],
				'jumlah_barang' => $fd['jumlah_barang'],
				'harga_unit' => $get_riwayat_by_id['harga_unit'],
				'harga_instalasi' => $get_riwayat_by_id['harga_instalasi'],
				'harga_barang' => $get_riwayat_by_id['harga_barang'],
				'total_harga_barang' => $get_riwayat_by_id['total_harga_barang'],
				'departemen' => $get_riwayat_by_id['departemen'],
				'id_cabang' => $cab['id_cabang']
			];
			
			$this->M_fabrikasi->insert_detail_outstanding($data_po);
			$this->db->delete('riwayat_po_fabrikasi', ['id_detail_po_fabrikasi' => $get_riwayat_by_id['id_detail_po_fabrikasi'], 'no_po' => $get_riwayat_by_id['no_po']]);
		}
		
		$this->M_fabrikasi->delete_detail($id_detail_fabrikasi);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('msg', 'error');
		} else {
			$this->session->set_flashdata('msg', 'hapus');
		}
		redirect('detail-invoice-fabrikasi/'.$id_fabrikasi.'/'.$cabang);
	}

	public function include_ppn($id_fabrikasi)
	{
		$query = $this->db->query('SELECT * FROM detail_fabrikasi WHERE id_fabrikasi="'.$id_fabrikasi.'" AND (nama_barang="PPn 10%" OR nama_barang="PPn 11%")');
		$cek_ppn = $query->num_rows();
		//var_dump($cek_ppn);die();
		if($cek_ppn > 0){
			$this->session->set_flashdata('msg', 'hapus');
			redirect('detail-invoice-fabrikasi/'.$id_fabrikasi);
		}
		$this->db->select_sum('total_harga_barang');
		$this->db->from('detail_fabrikasi');
		$this->db->where('id_fabrikasi', $id_fabrikasi);
		$total = $this->db->get()->row_array();
		$ppn = 11/100*$total['total_harga_barang'];
		$data_user	= [
			'id_fabrikasi'		=> $id_fabrikasi,
			'id_detail_po_fabrikasi'		=> 0,
			'id_barang'			=> 0,
			'nama_barang'		=> 'PPn 11%',
			'kode_barang'		=> '-',
			'satuan_barang'		=> '-',
			'jumlah_barang'		=> 1,
			'harga_barang'		=> $ppn,
			'total_harga_barang'=> $ppn,
			'no_po'				=> '-',
			'departemen'		=> '-'
		];

		$insert = $this->M_fabrikasi->insert_detail($data_user);

		$this->session->set_flashdata('msg', 'success');
		redirect('detail-invoice-fabrikasi/'.$id_fabrikasi);
	}

	public function delete_ppn($id_fabrikasi)
	{
		$query = $this->db->query('SELECT * FROM detail_fabrikasi WHERE id_fabrikasi="'.$id_fabrikasi.'" AND nama_barang="PPn 10%"');
		$cek_ppn = $query->num_rows();
		$this->db->where('id_fabrikasi', $id_fabrikasi);
		if($cek_ppn > 0){
			$this->db->where('nama_barang', 'PPn 10%');
		}else{
			$this->db->where('nama_barang', 'PPn 11%');
		}
		
		$this->db->delete('detail_fabrikasi');

		$this->session->set_flashdata('msg', 'success');
		redirect('detail-invoice-fabrikasi/'.$id_fabrikasi);
	}

	public function cetak_invoice($id_fabrikasi)
	{
		$this->load->library('pdf');
		$data['title']		= 'Fabrikasi';
        $data['title2']		= 'Invoice';
		$data['f']			= $this->M_fabrikasi->get_by_id($id_fabrikasi);
		$inv				= explode('/', $data['f']['no_invoice']);
		$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));
		$bulan 				= $this->bulan($pch_tgl[1]);
		$data['tanggal']	= date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));
		$data['f_detail']	= $this->M_fabrikasi->get_detail($id_fabrikasi)->result_array();
		
		if($data['f_detail'][0]['harga_unit'] == NULL || $data['f_detail'][0]['harga_unit'] == 0){
			$html_content = $this->load->view('fabrikasi/cetak_invoice', $data, true);
		} else {
			$html_content = $this->load->view('fabrikasi/cetak_invoice_instalasi', $data, true);
		}
        
        $filename = 'Invoice - '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3].'.pdf';

        $this->pdf->loadHtml($html_content);

        $this->pdf->set_paper('a4','potrait');
        
        $this->pdf->render();
        $this->pdf->stream($filename, ['Attachment' => 1]);
	}

	public function cetak_surat_jalan($id_fabrikasi)
	{
		$this->load->library('pdf');
		$data['title']		= 'Fabrikasi';
        $data['title2']		= 'Surat Jalan';
		$data['f']			= $this->M_fabrikasi->get_by_id($id_fabrikasi);
		$inv				= explode('/', $data['f']['no_invoice']);
		$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));
		$bulan 				= $this->bulan($pch_tgl[1]);
		$data['tanggal']	= date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));
		$data['f_detail']	= $this->M_fabrikasi->get_detail($id_fabrikasi)->result_array();
		
        $html_content = $this->load->view('fabrikasi/cetak_surat_jalan', $data, true);
        $filename = 'Surat Jalan - '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3].'.pdf';

        $this->pdf->loadHtml($html_content);

        $this->pdf->set_paper('a4','potrait');
        
        $this->pdf->render();
        $this->pdf->stream($filename, ['Attachment' => 1]);
	}

	public function export_excel_invoice($id_fabrikasi)
    {
        include_once APPPATH . 'third_party/PHPExcel.php';
        $f 					= $this->M_fabrikasi->get_by_id($id_fabrikasi);
		$inv				= explode('/', $f['no_invoice']);
		$pch_tgl			= explode('-', date('d-F-Y', strtotime($f['tanggal'])));
		$bulan 				= $this->bulan($pch_tgl[1]);
		$tanggal 			= date('d', strtotime($f['tanggal'])).' '.$bulan.' '.date('Y', strtotime($f['tanggal']));
		$f_detail 			= $this->M_fabrikasi->get_detail($id_fabrikasi)->result_array();

        $excel = new PHPExcel();

        $excel1 = new PHPExcel_Worksheet($excel, 'Invoice');

		// Attach the "My Data" worksheet as the first worksheet in the PHPExcel object
		$excel->addSheet($excel1, 0);

		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$excel->setActiveSheetIndex(0);
        $objDrawing->setWorksheet($excel->getActiveSheet());
        $objDrawing->setCoordinates('B1');
        $objDrawing->setName('Header Invoice');
        $objDrawing->setDescription('Sintesa');
        $objDrawing->setPath('assets/img/header_invoice.png');
        $objDrawing->setWidth(160)->setHeight(160);

        $excel->getProperties()
                ->setCreator('IndoExpress')
                ->setLastModifiedBy('IndoExpress')
                ->setTitle('Data Invoice & SJ')
                ->setSubject('Invoice & SJ')
                ->setDescription('Invoice & SJ '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3])
                ->setKeyWords('Invoice & SJ');

        $style_col = [
        	'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'd9e1f2']
            ],
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row_full = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $excel->setActiveSheetIndex(0)->setCellValue('G9', $f['no_invoice']);
        $excel->getActiveSheet()->mergeCells('G9:H9');
        $excel->getActiveSheet()->getStyle('G9')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('G9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(0)->setCellValue('G10', $tanggal);
        $excel->getActiveSheet()->mergeCells('G10:H10');
        $excel->getActiveSheet()->getStyle('G10')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('G10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(0)->setCellValue('B11', 'NO');
        $excel->setActiveSheetIndex(0)->setCellValue('C11', 'NAMA BARANG');
        $excel->setActiveSheetIndex(0)->setCellValue('D11', 'UOM');
        $excel->setActiveSheetIndex(0)->setCellValue('E11', 'QTY');
        $excel->setActiveSheetIndex(0)->setCellValue('F11', 'HARGA');
        $excel->setActiveSheetIndex(0)->setCellValue('G11', 'TOTAL');
        $excel->setActiveSheetIndex(0)->setCellValue('H11', 'PO');
        
        $excel->getActiveSheet()->getStyle('B11')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C11')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D11')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E11')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F11')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G11')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H11')->applyFromArray($style_col);

        $numrow = 12;
        $numrow_last = 12;
        $no = 1;
        $total = 0;
        foreach ($f_detail as $i) {
        	$numroww = $numrow+1;
        	$numrow2 = $numrow+2;
        	$numrow_last += $numrow_last+3;
        	$total += $i['total_harga_barang'];
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ($no++));
            $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $i['nama_barang']);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numroww, $i['kode_barang']);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $i['satuan_barang']);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.$numroww);
            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $i['jumlah_barang']);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->mergeCells('E'.$numrow.':E'.$numroww);
            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, 'Rp '.number_format($i['harga_barang'], 2,',','.'));
            $excel->getActiveSheet()->mergeCells('F'.$numrow.':F'.$numroww);
            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, 'Rp '.number_format($i['total_harga_barang'], 2,',','.'));
            $excel->getActiveSheet()->mergeCells('G'.$numrow.':G'.$numroww);
            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $i['no_po']);
            $excel->getActiveSheet()->getStyle('H'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->mergeCells('H'.$numrow.':H'.$numroww);

            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('B'.$numrow2)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow2)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow2)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numrow2)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numrow2)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G'.$numrow2)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H'.$numrow2)->applyFromArray($style_row);
            
            $numrow=$numrow+3;
            
        }

        $numrow_terbilang = $numrow+1;
        $terbilang = $this->terbilang($total);

        $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, 'TOTAL');
        $excel->getActiveSheet()->mergeCells('B'.$numrow.':F'.$numrow);
        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setSize(12);
        $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $excel->getActiveSheet()->getStyle('B'.$numrow.':F'.$numrow)->applyFromArray($style_row_full);

        $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, 'Rp '.number_format($total, 2,',','.'));
        $excel->getActiveSheet()->getStyle('G'.$numrow)->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('G'.$numrow)->getFont()->setSize(12);
        $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row_full);
        $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row_full);

        $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow_terbilang, 'TERBILANG : '.strtoupper($terbilang));
        $excel->getActiveSheet()->mergeCells('B'.$numrow_terbilang.':H'.$numrow_terbilang);
        $excel->getActiveSheet()->getStyle('B'.$numrow_terbilang)->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('B'.$numrow_terbilang)->getFont()->setSize(12);
        $excel->getActiveSheet()->getStyle('B'.$numrow_terbilang)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('B'.$numrow_terbilang.':H'.$numrow_terbilang)->applyFromArray($style_row_full);

        $excel->setActiveSheetIndex(0)->setCellValue('E'.intval($numrow_terbilang+2), 'HORMAT KAMI');
        $excel->getActiveSheet()->getStyle('E'.intval($numrow_terbilang+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('E'.intval($numrow_terbilang+2))->getFont()->setBold(true);
        $excel->getActiveSheet()->mergeCells('E'.intval($numrow_terbilang+2).':G'.intval($numrow_terbilang+2));

        $excel->setActiveSheetIndex(0)->setCellValue('E'.intval($numrow_terbilang+9), 'FAJAR ALVIANA');
        $excel->getActiveSheet()->getStyle('E'.intval($numrow_terbilang+9))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('E'.intval($numrow_terbilang+9).':G'.intval($numrow_terbilang+9));

        $objDrawing2 = new PHPExcel_Worksheet_Drawing();
        $excel->setActiveSheetIndex(0);
        $objDrawing2->setWorksheet($excel->getActiveSheet());
        $objDrawing2->setCoordinates('B'.intval($numrow_terbilang+10));
        $objDrawing2->setName('Footer Invoice');
        $objDrawing2->setDescription('Sintesa');
        $objDrawing2->setPath('assets/img/footer_surat.png');
        $objDrawing2->setWidth(155)->setHeight(155);

        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(55);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(5.5);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(11);
        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(26);
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        $excel2 = new PHPExcel_Worksheet($excel, 'Surat Jalan');

		// Attach the "My Data" worksheet as the first worksheet in the PHPExcel object
		$excel->addSheet($excel2, 1);

		$excel->getProperties()
                ->setCreator('IndoExpress')
                ->setLastModifiedBy('IndoExpress')
                ->setTitle('Data Invoice & SJ')
                ->setSubject('Invoice & SJ')
                ->setDescription('Invoice & SJ '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3])
                ->setKeyWords('Invoice & SJ');

        $objDrawing3 = new PHPExcel_Worksheet_Drawing();
        $excel->setActiveSheetIndex(1);
        $objDrawing3->setWorksheet($excel->getActiveSheet());
        $objDrawing3->setCoordinates('B1');
        $objDrawing3->setName('Footer Invoice');
        $objDrawing3->setDescription('Sintesa');
        $objDrawing3->setPath('assets/img/header_surat_jalan.png');
        $objDrawing3->setWidth(195)->setHeight(195);

        $style_col = [
        	'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'd9e1f2']
            ],
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row_top = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row_left = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row_right = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row_full = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $excel->setActiveSheetIndex(1)->setCellValue('E10', $f['no_invoice']);
        $excel->getActiveSheet()->mergeCells('E10:F10');
        $excel->getActiveSheet()->getStyle('E10')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('E10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(1)->setCellValue('E11', $tanggal);
        $excel->getActiveSheet()->mergeCells('E11:F11');
        $excel->getActiveSheet()->getStyle('E11')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('E11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(1)->setCellValue('B12', 'NO');
        $excel->setActiveSheetIndex(1)->setCellValue('C12', 'NAMA BARANG');
        $excel->setActiveSheetIndex(1)->setCellValue('D12', 'UOM');
        $excel->setActiveSheetIndex(1)->setCellValue('E12', 'QTY');
        $excel->setActiveSheetIndex(1)->setCellValue('F12', 'PO');
        
        $excel->getActiveSheet()->getStyle('B12')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C12')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D12')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E12')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F12')->applyFromArray($style_col);

        $numrow = 13;
        $numrow_last = 13;
        $no = 1;
        $total = 0;
        foreach ($f_detail as $i) {
        	$numroww = $numrow+1;
        	$numrow2 = $numrow+2;
        	$numrow_last += $numrow_last+3;
        	$total += $i['total_harga_barang'];
            $excel->setActiveSheetIndex(1)->setCellValue('B'.$numrow, ($no++));
            $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->setActiveSheetIndex(1)->setCellValue('C'.$numrow, $i['nama_barang']);
            $excel->setActiveSheetIndex(1)->setCellValue('C'.$numroww, $i['kode_barang']);
            $excel->setActiveSheetIndex(1)->setCellValue('D'.$numrow, $i['satuan_barang']);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.$numroww);
            
            
            $excel->setActiveSheetIndex(1)->setCellValue('E'.$numrow, $i['jumlah_barang']);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->mergeCells('E'.$numrow.':E'.$numroww);
            
            $excel->setActiveSheetIndex(1)->setCellValue('F'.$numrow, $i['no_po']);
            $excel->getActiveSheet()->getStyle('F'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->mergeCells('F'.$numrow.':F'.$numroww);

            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('B'.$numrow2)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow2)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow2)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numrow2)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numrow2)->applyFromArray($style_row);
            
            $numrow=$numrow+3;
            
        }

        $numrow_ttd = $numrow+1;


        $excel->setActiveSheetIndex(1)->setCellValue('B'.$numrow);
        $excel->getActiveSheet()->mergeCells('B'.$numrow.':F'.$numrow);
        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setSize(12);
        $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $excel->getActiveSheet()->getStyle('B'.$numrow.':F'.$numrow)->applyFromArray($style_row_top);

        $excel->setActiveSheetIndex(1)->setCellValue('B'.$numrow_ttd, 'HORMAT KAMI');
        $excel->setActiveSheetIndex(1)->setCellValue('D'.$numrow_ttd, 'PENERIMA');
        $excel->getActiveSheet()->mergeCells('B'.$numrow_ttd.':C'.$numrow_ttd);
        $excel->getActiveSheet()->mergeCells('D'.$numrow_ttd.':F'.$numrow_ttd);
        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getFont()->setSize(12);
        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd.':C'.$numrow_ttd)->applyFromArray($style_row_top);
        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd.':C'.$numrow_ttd)->applyFromArray($style_row_left);
        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd.':C'.$numrow_ttd)->applyFromArray($style_row_right);
        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd.':F'.$numrow_ttd)->applyFromArray($style_row_top);
        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd.':F'.$numrow_ttd)->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+1))->applyFromArray($style_row_left);
        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+1))->applyFromArray($style_row_right);
        $excel->getActiveSheet()->getStyle('F'.intval($numrow_ttd+1))->applyFromArray($style_row_right);
        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+2))->applyFromArray($style_row_left);
        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+2))->applyFromArray($style_row_right);
        $excel->getActiveSheet()->getStyle('F'.intval($numrow_ttd+2))->applyFromArray($style_row_right);
        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+3))->applyFromArray($style_row_left);
        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+3))->applyFromArray($style_row_right);
        $excel->getActiveSheet()->getStyle('F'.intval($numrow_ttd+3))->applyFromArray($style_row_right);
        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+4))->applyFromArray($style_row_left);
        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+4))->applyFromArray($style_row_right);
        $excel->getActiveSheet()->getStyle('F'.intval($numrow_ttd+4))->applyFromArray($style_row_right);
        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+5))->applyFromArray($style_row_left);
        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+5))->applyFromArray($style_row_right);
        $excel->getActiveSheet()->getStyle('F'.intval($numrow_ttd+5))->applyFromArray($style_row_right);
        $excel->getActiveSheet()->mergeCells('B'.intval($numrow_ttd+6).':C'.intval($numrow_ttd+6));
        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+7).':C'.intval($numrow_ttd+7))->applyFromArray($style_row_top);
        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+6).':C'.intval($numrow_ttd+6))->applyFromArray($style_row_left);
        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+6).':C'.intval($numrow_ttd+6))->applyFromArray($style_row_right);
        $excel->getActiveSheet()->getStyle('D'.intval($numrow_ttd+7).':F'.intval($numrow_ttd+7))->applyFromArray($style_row_top);
        $excel->getActiveSheet()->getStyle('D'.intval($numrow_ttd+6).':F'.intval($numrow_ttd+6))->applyFromArray($style_row_right);
        $excel->setActiveSheetIndex(1)->setCellValue('B'.intval($numrow_ttd+6), 'FAJAR ALVIANA');
        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+6))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objDrawing3 = new PHPExcel_Worksheet_Drawing();
        $excel->setActiveSheetIndex(1);
        $objDrawing3->setWorksheet($excel->getActiveSheet());
        $objDrawing3->setCoordinates('B'.intval($numrow_ttd+9));
        $objDrawing3->setName('Footer Invoice');
        $objDrawing3->setDescription('Sintesa');
        $objDrawing3->setPath('assets/img/footer_surat.png');
        $objDrawing3->setWidth(150)->setHeight(150);

        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(1);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(4);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(60);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(26);
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Invoice $ SJ ' .$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3]. '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    // public function bc()
	// {
    //     $data['title']		= 'Fabrikasi';
    //     $data['title2']		= 'BC';
	// 	$data['bc']	= $this->M_fabrikasi->get_bc()->result_array();
	// 	$this->load->view('bc-fabrikasi/data', $data);
	// }

    public function bc($cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
        $data['title']		= 'Fabrikasi '.$cab['nama_cabang'];
        $data['title2']		= 'BC '.$cab['nama_cabang'];
		$data['cabang']		= $cabang;
		$data['bc']			= $this->M_fabrikasi->get_bc($cab['id_cabang'])->result_array();
		$this->load->view('bc-fabrikasi/data', $data);
	}

	// public function tambah_bc()
	// {
	// 	$this->validation_bc();
	// 	if (!$this->form_validation->run()) {
	// 		$tahun 			= date('Y');
	// 		$bulan 			= $this->bulan_romawi(date('F'));
	// 		$data['title']	= 'Fabrikasi';
    //     	$data['title2']	= 'BC';
	// 		$this->load->view('bc-fabrikasi/tambah', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$data_user	= [
	// 			'nama_vendor'	=> $data['nama_vendor'],
	// 			'tanggal'		=> $data['tanggal'],
	// 			'npwp'		=> $data['npwp'],
	// 		];

			
	// 		if ($this->M_fabrikasi->insert_bc($data_user)) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('tambah-bc-fabrikasi');
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'success');
	// 			redirect('bc-fabrikasi');
	// 		}
	// 	}
	// }
	public function tambah_bc($cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->validation_bc();
		if (!$this->form_validation->run()) {
			$tahun 			= date('Y');
			$bulan 			= $this->bulan_romawi(date('F'));
			$data['title']	= 'Fabrikasi '.$cab['nama_cabang'];
        	$data['title2']	= 'BC '.$cab['nama_cabang'];
			$data['cabang']	= $cabang;
			$this->load->view('bc-fabrikasi/tambah', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_bc	= [
				'nama_vendor'	=> $data['nama_vendor'],
				'tanggal'		=> $data['tanggal'],
				'npwp'			=> $data['npwp'],
				'id_cabang'		=> $cab['id_cabang']
			];
			
			if ($this->M_fabrikasi->insert_bc($data_bc)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-bc-fabrikasi/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('bc-fabrikasi/'.$cabang);
			}
		}
	}

	public function edit_bc($id_bc_fabrikasi)
	{
		$this->validation_bc();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Fabrikasi';
        	$data['title2']		= 'BC';
			$data['bc']	= $this->M_fabrikasi->get_bc_by_id($id_bc_fabrikasi);
			$this->load->view('bc-fabrikasi/edit', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_user	= [
				'id_bc_fabrikasi'	=> $id_bc_fabrikasi,
				'nama_vendor'	=> $data['nama_vendor'],
				'tanggal'		=> $data['tanggal'],
				'npwp'		=> $data['npwp'],
			];

			
			if ($this->M_fabrikasi->update_bc($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-bc-fabrikasi/'.$id_bc_fabrikasi);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('bc-fabrikasi');
			}
		}
	}

	public function hapus_bc($id_bc_fabrikasi)
	{
		$this->M_fabrikasi->delete_bc($id_bc_fabrikasi);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('bc-fabrikasi');
	}

	// public function detail_bc($id_bc_fabrikasi)
	// {
	// 	$data['title']		= 'Fabrikasi';
    //     $data['title2']		= 'BC';
	// 	$data['bc']			= $this->M_fabrikasi->get_bc_by_id($id_bc_fabrikasi);
	// 	$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['bc']['tanggal'])));
	// 	$bulan 				= $this->bulan($pch_tgl[1]);
	// 	$data['tanggal']	= date('d', strtotime($data['bc']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['bc']['tanggal']));
	// 	$data['bc_detail']	= $this->M_fabrikasi->get_detail_bc($id_bc_fabrikasi)->result_array();
	// 	$this->load->view('bc-fabrikasi/detail', $data);
	// }

	public function detail_bc($id_bc_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$data['title']		= 'Fabrikasi '.$cab['nama_cabang'];
        $data['title2']		= 'BC '.$cab['nama_cabang'];
		$data['bc']			= $this->M_fabrikasi->get_bc_by_id($id_bc_fabrikasi);
		$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['bc']['tanggal'])));
		$bulan 				= $this->bulan($pch_tgl[1]);
		$data['tanggal']	= date('d', strtotime($data['bc']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['bc']['tanggal']));
		$data['bc_detail']	= $this->M_fabrikasi->get_detail_bc($id_bc_fabrikasi, $cab['id_cabang'])->result_array();
		$data['cabang']		= $cabang;
		$this->load->view('bc-fabrikasi/detail', $data);
	}

	// public function tambah_detail_bc($id_bc_fabrikasi)
	// {
	// 	$this->validation_bc_detail();
	// 	if (!$this->form_validation->run()) {
	// 		$data['title']	= 'Fabrikasi';
    //     	$data['title2']	= 'BC';
    //     	$data['id_bc_fabrikasi'] = $id_bc_fabrikasi;
    //     	$data['invoice']	= $this->M_fabrikasi->get()->result_array();
	// 		$this->load->view('bc-fabrikasi/tambah_detail', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$data_user	= [
	// 			'id_bc_fabrikasi'	=> $id_bc_fabrikasi,
	// 			'id_fabrikasi'	=> $data['id_fabrikasi'],
	// 		];

			
	// 		if ($this->M_fabrikasi->insert_bc_detail($data_user)) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('tambah-detail-bc-fabrikasi/'.$id_bc_fabrikasi);
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'success');
	// 			redirect('detail-bc-fabrikasi/'.$id_bc_fabrikasi);
	// 		}
	// 	}
	// }

	public function tambah_detail_bc($id_bc_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->validation_bc_detail();
		if (!$this->form_validation->run()) {
			$data['title']				= 'Fabrikasi '.$cab['nama_cabang'];
        	$data['title2']				= 'BC '.$cab['nama_cabang'];
        	$data['id_bc_fabrikasi'] 	= $id_bc_fabrikasi;
        	$data['invoice']			= $this->M_fabrikasi->get(null, $cab['id_cabang'])->result_array();
			$data['cabang']				= $cabang;
			$this->load->view('bc-fabrikasi/tambah_detail', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_bc	= [
				'id_bc_fabrikasi'	=> $id_bc_fabrikasi,
				'id_fabrikasi'		=> $data['id_fabrikasi'],
				'id_cabang'			=> $cab['id_cabang']
			];

			
			if ($this->M_fabrikasi->insert_bc_detail($data_bc)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-detail-bc-fabrikasi/'.$id_bc_fabrikasi.'/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('detail-bc-fabrikasi/'.$id_bc_fabrikasi.'/'.$cabang);
			}
		}
	}

	// public function edit_detail_bc($id_detail_bc_fabrikasi, $id_bc_fabrikasi)
	// {
	// 	$this->validation_bc_detail();
	// 	if (!$this->form_validation->run()) {
	// 		$data['title']	= 'Fabrikasi';
    //     	$data['title2']	= 'BC';
    //     	$data['id_detail_bc_fabrikasi'] = $id_detail_bc_fabrikasi;
    //     	$data['id_bc_fabrikasi'] = $id_bc_fabrikasi;
    //     	$data['bc']	= $this->M_fabrikasi->get_bc_detail_by_id($id_detail_bc_fabrikasi);
    //     	$data['invoice']	= $this->M_fabrikasi->get()->result_array();
	// 		$this->load->view('bc-fabrikasi/edit_detail', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$data_user	= [
	// 			'id_detail_bc_fabrikasi' => $id_detail_bc_fabrikasi,
	// 			'id_bc_fabrikasi'	=> $id_bc_fabrikasi,
	// 			'id_fabrikasi'	=> $data['id_fabrikasi'],
	// 		];

			
	// 		if ($this->M_fabrikasi->update_bc_detail($data_user)) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('edit-detail-bc-fabrikasi/'.$id_detail_bc_fabrikasi.'/'.$id_bc_fabrikasi);
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'success');
	// 			redirect('detail-bc-fabrikasi/'.$id_bc_fabrikasi);
	// 		}
	// 	}
	// }

	public function edit_detail_bc($id_detail_bc_fabrikasi, $id_bc_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->validation_bc_detail();
		if (!$this->form_validation->run()) {
			$data['title']					= 'Fabrikasi '.$cab['nama_cabang'];
        	$data['title2']					= 'BC '.$cab['nama_cabang'];
        	$data['id_detail_bc_fabrikasi'] = $id_detail_bc_fabrikasi;
        	$data['id_bc_fabrikasi'] 		= $id_bc_fabrikasi;
        	$data['bc']						= $this->M_fabrikasi->get_bc_detail_by_id($id_detail_bc_fabrikasi);
        	$data['invoice']				= $this->M_fabrikasi->get(null, $cab['id_cabang'])->result_array();
			$data['cabang']					= $cabang;
			$this->load->view('bc-fabrikasi/edit_detail', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_bc	= [
				'id_detail_bc_fabrikasi' 	=> $id_detail_bc_fabrikasi,
				'id_bc_fabrikasi'			=> $id_bc_fabrikasi,
				'id_fabrikasi'				=> $data['id_fabrikasi'],
				'id_cabang'					=> $cab['id_cabang']
			];

			
			if ($this->M_fabrikasi->update_bc_detail($data_bc)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-detail-bc-fabrikasi/'.$id_detail_bc_fabrikasi.'/'.$id_bc_fabrikasi.'/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('detail-bc-fabrikasi/'.$id_bc_fabrikasi.'/'.$cabang);
			}
		}
	}

	// public function hapus_detail_bc($id_detail_bc_fabrikasi, $id_bc_fabrikasi)
	// {
	// 	$this->M_fabrikasi->delete_bc_detail($id_detail_bc_fabrikasi);
	// 	$this->session->set_flashdata('msg', 'hapus');
	// 	redirect('detail-bc-fabrikasi/'.$id_bc_fabrikasi);
	// }

	public function hapus_detail_bc($id_detail_bc_fabrikasi, $id_bc_fabrikasi, $cabang)
	{
		$this->M_fabrikasi->delete_bc_detail($id_detail_bc_fabrikasi);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('detail-bc-fabrikasi/'.$id_bc_fabrikasi.'/'.$cabang);
	}

	public function export_excel_bc($id_bc_fabrikasi)
    {
        include_once APPPATH . 'third_party/PHPExcel.php';
        $bc 				= $this->M_fabrikasi->get_bc_by_id($id_bc_fabrikasi);
		$pch_tgl			= explode('-', date('d-F-Y', strtotime($bc['tanggal'])));
		$bulan 				= $this->bulan($pch_tgl[1]);
		$tanggal 			= date('d', strtotime($bc['tanggal'])).' '.$bulan.' '.date('Y', strtotime($bc['tanggal']));
		$bc_detail			= $this->M_fabrikasi->get_detail_bc($id_bc_fabrikasi)->result_array();

        $excel = new PHPExcel();

        $excel->getProperties()
                ->setCreator('IndoExpress')
                ->setLastModifiedBy('IndoExpress')
                ->setTitle('Data BC')
                ->setSubject('BC')
                ->setDescription('BC '.$tanggal)
                ->setKeyWords('BC');

        $style_col = [
        	'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'ffff00']
            ],
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row_j = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row_full = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $excel->setActiveSheetIndex(0)->setCellValue('A2', 'NO');
        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'NAMA VENDOR');
        $excel->setActiveSheetIndex(0)->setCellValue('C2', 'TGL SJ');
        $excel->setActiveSheetIndex(0)->setCellValue('D2', 'NO SJ');
        $excel->setActiveSheetIndex(0)->setCellValue('E2', 'TGL PO');
        $excel->setActiveSheetIndex(0)->setCellValue('F2', 'NO PO');
        $excel->setActiveSheetIndex(0)->setCellValue('G2', 'NO PACKING LIST');
        $excel->setActiveSheetIndex(0)->setCellValue('H2', 'NO INVOICE');
        $excel->setActiveSheetIndex(0)->setCellValue('I2', 'ITEM CODE');
        $excel->setActiveSheetIndex(0)->setCellValue('J2', 'DESCRIPTION');
        $excel->setActiveSheetIndex(0)->setCellValue('K2', 'Kode Satuan');
        $excel->setActiveSheetIndex(0)->setCellValue('L2', 'QTY');
        $excel->setActiveSheetIndex(0)->setCellValue('M2', 'PACKAGING');
        $excel->setActiveSheetIndex(0)->setCellValue('N2', 'NPWP');
        
        $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('N2')->applyFromArray($style_col);

        $numrow = 3;
        $numrow_last = 3;
        $no = 1;
        $total = 0;
        foreach ($bc_detail as $i) {
        	$invoice = $this->db->get_where('fabrikasi', ['id_fabrikasi' => $i['id_fabrikasi']])->row_array();
        	$d_invoice = $this->db->get_where('detail_fabrikasi', ['id_fabrikasi' => $i['id_fabrikasi']])->result_array();
        	$no_sub = 3;
        	foreach ($d_invoice as $inv) {
        		$no_sub += $no_sub+1;
        		$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ($no++));
	            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $bc['nama_vendor']);
	            $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.intval($numrow+2));
	            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $bc['tanggal']);
	            $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numrow+2));
	            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $invoice['no_invoice']);
	            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $invoice['tanggal']);
	            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $inv['no_po']);
	            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $invoice['no_invoice']);
	            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $invoice['no_invoice']);
	            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $inv['kode_barang']);
	            $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $inv['nama_barang']);
	            $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $inv['satuan_barang']);
	            $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $inv['jumlah_barang']);
	            $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, 'Pack');
	            $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $bc['npwp']);
	            $excel->getActiveSheet()->mergeCells('N'.$numrow.':N'.intval($numrow+2));

	            $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
	            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
	            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
	            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
	            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
	            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
	            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
	            $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
	            $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
	            $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row_j);
	            $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);
	            $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);
	            $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);
	            $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);
	            
	            $numrow=$numrow+1;
        	}
            
            
        }

        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(3.14);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(26.14);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17.71);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(12.41);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20.71);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(16.43);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(16.43);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(12.57); 
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(75.86);
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(11.14);
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(11.14);
        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(20.86);

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="BC ' .$tanggal.'.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    private function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = $this->penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . $this->penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . $this->penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
 
	private function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim($this->penyebut($nilai))." RUPIAH";
		} else {
			$hasil = trim($this->penyebut($nilai))." RUPIAH";
		}     		
		return $hasil;
	}

	private function validation()
	{
		$this->form_validation->set_rules('no_invoice', 'No Invoice', 'required|trim');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
	}

	private function validation_outstanding()
	{
		$this->form_validation->set_rules('no_po', 'No PO', 'required|trim');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
		$this->form_validation->set_rules('nama_user', 'Nama User', 'required|trim');
	}

	private function validation_detail_outstanding()
	{
		$this->form_validation->set_rules('id_barang', 'No Invoice', 'required|trim');
		$this->form_validation->set_rules('jumlah_barang', 'Jumlah', 'required|numeric');
		$this->form_validation->set_rules('harga_barang', 'Harga', 'required|numeric');
	}
	
	private function validation_detail()
	{
		$this->form_validation->set_rules('id_detail_po_fabrikasi', 'Pilih PO', 'required|trim');
		$this->form_validation->set_rules('jumlah_barang', 'Jumlah', 'required|numeric');
		$this->form_validation->set_rules('harga_barang', 'Harga', 'required|numeric');
		$this->form_validation->set_rules('no_po', 'No PO', 'required');
	}

	private function validation_bc()
	{
		$this->form_validation->set_rules('nama_vendor', 'Nama Vendor', 'required|trim');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
		$this->form_validation->set_rules('npwp', 'NPWP', 'required|trim');
	}

	private function validation_bc_detail()
	{
		$this->form_validation->set_rules('id_fabrikasi', 'ID Fabrikasi', 'required|trim');
	}

	private function validation_bap()
	{
		$this->form_validation->set_rules('tanggal_bap', 'Tanggal BAP', 'required|trim');
		$this->form_validation->set_rules('tanggal_finish', 'Tanggal Finish', 'required|trim');
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'required|trim');
		$this->form_validation->set_rules('departemen', 'Departemen', 'required|trim');
	}

	private function validation_bap_detail()
	{
		$this->form_validation->set_rules('id_detail_fabrikasi', 'ID Fabrikasi', 'required|trim');
	}

	private function bulan($bulan)
    {
        $bulan=$bulan;
        switch ($bulan) {
          case 'January':
            $bulan= "Januari";
            break;
          case 'February':
            $bulan= "Februari";
            break;
          case 'March':
            $bulan= "Maret";
            break;
          case 'April':
            $bulan= "April";
            break;
          case 'May':
            $bulan= "Mei";
            break;
          case 'June':
            $bulan= "Juni";
            break;
          case 'July':
            $bulan= "Juli";
            break;
          case 'August':
            $bulan= "Agustus";
            break;
          case 'September':
            $bulan= "September";
            break;
          case 'October':
            $bulan= "Oktober";
            break;
          case 'November':
            $bulan= "November";
            break;
          case 'December':
            $bulan= "Desember";
            break;
          default:
            $bulan= "Isi variabel tidak di temukan";
            break;
        }

        return $bulan;
    }

    private function bulan_romawi($bulan)
    {
        $bulan=$bulan;
        switch ($bulan) {
          case 'January':
            $bulan= "I";
            break;
          case 'February':
            $bulan= "II";
            break;
          case 'March':
            $bulan= "III";
            break;
          case 'April':
            $bulan= "IV";
            break;
          case 'May':
            $bulan= "V";
            break;
          case 'June':
            $bulan= "VI";
            break;
          case 'July':
            $bulan= "VII";
            break;
          case 'August':
            $bulan= "VIII";
            break;
          case 'September':
            $bulan= "IX";
            break;
          case 'October':
            $bulan= "X";
            break;
          case 'November':
            $bulan= "XI";
            break;
          case 'December':
            $bulan= "XII";
            break;
          default:
            $bulan= "Isi variabel tidak di temukan";
            break;
        }

        return $bulan;
    }

	// public function outstanding()
	// {
	// 	$this->db->select_sum('total_harga_barang');
	// 	$this->db->from('po_fabrikasi');
	// 	$this->db->join('detail_po_fabrikasi', 'po_fabrikasi.id_po_fabrikasi=detail_po_fabrikasi.id_po_fabrikasi');
	// 	$get_po_fabrikasi = $this->db->get()->row_array();
	// 	$data['title']		= 'Outstanding Fabrikasi';
	// 	$data['total_outstanding'] = $get_po_fabrikasi['total_harga_barang'];
		
	// 	$this->db->select('*');
	// 	$this->db->from('user');
	// 	$this->db->order_by('nama', 'ASC');
	// 	$data['user_outstanding'] = $this->db->get()->result_array();
	// 	$data['title']		= 'Outstanding Fabrikasi';
	// 	$data['title2']		= '';
	// 	if(is_admin()) {
	// 		$post_user = $this->input->post('id_user');
	// 	} else {
	// 		$post_user = $this->session->userdata('id_user');
	// 	}
	// 	$outstanding = $this->M_fabrikasi->get_outstanding()->result_array();
	// 	if(!empty($post_user)){
	// 		$data['id_user'] = $post_user;
	// 		$outstanding = $this->M_fabrikasi->get_outstanding($post_user)->result_array();
	// 		if ($post_user == 'All') {
	// 			$outstanding = $this->M_fabrikasi->get_outstanding()->result_array();
	// 		}
	// 	} else {
	// 		$data['id_user'] = 'All';
	// 	}
	// 	$data['outstanding']	= $outstanding;
	// 	$this->load->view('outstanding-fabrikasi/data', $data);
	// }

	public function id_cabang($cabang) 
	{
		$cabangAll = $this->M_cabang->get_data()->result_array();
		foreach ($cabangAll as $item) {
			if ($item['slug'] === $cabang) {
				return $item['id_cabang'];
			}
		}
	}

	public function outstanding($cabang)
	{
		$id_cabang = $this->id_cabang($cabang);
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->db->select_sum('total_harga_barang');
		$this->db->from('po_fabrikasi');
		$this->db->where('po_fabrikasi.id_cabang', $id_cabang);
		$this->db->join('detail_po_fabrikasi', 'po_fabrikasi.id_po_fabrikasi=detail_po_fabrikasi.id_po_fabrikasi');
		$get_po_fabrikasi = $this->db->get()->row_array();
		$data['title']		= 'Outstanding Fabrikasi '.$cab['nama_cabang'];
		$data['total_outstanding'] = $get_po_fabrikasi['total_harga_barang'];
		
		$this->db->select('*');
		$this->db->from('user');
		$this->db->order_by('nama', 'ASC');
		$data['user_outstanding'] = $this->db->get()->result_array();
		$data['title']		= 'Outstanding Fabrikasi '.$cab['nama_cabang'];
		$data['title2']		= '';
		if(is_admin()) {
			$post_user = $this->input->post('id_user');
		} else {
			$post_user = $this->session->userdata('id_user');
		}
		$outstanding = $this->M_fabrikasi->get_outstanding(null, $id_cabang)->result_array();
		if(!empty($post_user)){
			$data['id_user'] = $post_user;
			$outstanding = $this->M_fabrikasi->get_outstanding($post_user, $id_cabang)->result_array();
			if ($post_user == 'All') {
				$outstanding = $this->M_fabrikasi->get_outstanding(null, $id_cabang)->result_array();
			}
		} else {
			$data['id_user'] = 'All';
		}
		$data['cabang']	= $cabang;
		$data['outstanding']	= $outstanding;
		$this->load->view('outstanding-fabrikasi/data', $data);
	}

	// public function tambah_outstanding()
	// {
	// 	$this->validation_outstanding();
	// 	if (!$this->form_validation->run()) {
	// 		$data['title']		= 'Outstanding Fabrikasi';
	// 		$data['title2']		= '';
        
	// 		$this->load->view('outstanding-fabrikasi/tambah', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$data_user	= [
	// 			'no_po'		=> $data['no_po'],
	// 			'tanggal'		=> $data['tanggal'],
	// 			'nama_user'		=> $data['nama_user'],
	// 			'id_user' => $this->session->userdata('id_user'),
	// 		];

	// 		$cek_po = $this->db->get_where('po_fabrikasi', ['no_po' => $data['no_po']])->num_rows();
	// 		if($data['no_po'] != '-'){
	// 			if($cek_po > 0){
	// 				$this->session->set_flashdata('msg', 'duplicate-po');
	// 				redirect('tambah-outstanding-fabrikasi');
	// 			}
	// 		}
			
	// 		if ($this->M_fabrikasi->insert_outstanding($data_user)) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('tambah-outstanding-fabrikasi');
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'success');
	// 			redirect('outstanding-fabrikasi');
	// 		}
	// 	}
	// }

	public function tambah_outstanding($cabang)
	{
		$id_cabang = $this->id_cabang($cabang);
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->validation_outstanding();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Outstanding Fabrikasi '.$cab['nama_cabang'];
			$data['title2']		= '';
			$data['cabang']		= $cabang;
        
			$this->load->view('outstanding-fabrikasi/tambah', $data);
		} else {
			$data = $this->input->post(null, true);
			$data_fabrikasi	= [
				'id_cabang'		=> $id_cabang,
				'no_po'			=> $data['no_po'],
				'tanggal'		=> $data['tanggal'],
				'nama_user'		=> $data['nama_user'],
				'id_user' 		=> $this->session->userdata('id_user'),
			];

			$cek_po = $this->db->get_where('po_fabrikasi', ['no_po' => $data['no_po']])->num_rows();
			if($data['no_po'] != '-'){
				if($cek_po > 0){
					$this->session->set_flashdata('msg', 'duplicate-po');
					redirect('tambah-outstanding-fabrikasi/'.$cabang);
				}
			}
			
			if ($this->M_fabrikasi->insert_outstanding($data_fabrikasi)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-outstanding-fabrikasi/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('outstanding-fabrikasi/'.$cabang);
			}
		}
	}

	// public function edit_outstanding($id_po_fabrikasi)
	// {
	// 	$this->validation_outstanding();
	// 	if (!$this->form_validation->run()) {
	// 		$data['title']		= 'Outstanding Fabrikasi';
    //     	$data['title2']		= '';
	// 		$data['o']	= $this->M_fabrikasi->get_outstanding_by_id($id_po_fabrikasi);
	// 		$this->load->view('outstanding-fabrikasi/edit', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$data_user	= [
	// 			'id_po_fabrikasi'	=> $id_po_fabrikasi,
	// 			'no_po'	=> $data['no_po'],
	// 			'tanggal'		=> $data['tanggal'],
	// 			'nama_user'		=> $data['nama_user'],
	// 			'id_user' => $this->session->userdata('id_user'),
	// 		];

			
	// 		if ($this->M_fabrikasi->update_outstanding($data_user)) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('edit-outstanding-fabrikasi/'.$id_po_fabrikasi);
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'edit');
	// 			redirect('outstanding-fabrikasi');
	// 		}
	// 	}
	// }

	public function edit_outstanding($id_po_fabrikasi, $cabang)
	{
		$this->validation_outstanding();
		$cab = $this->M_cabang->get_by('slug', $cabang);
		if (!$this->form_validation->run()) {
			$data['title']		= 'Outstanding Fabrikasi '.$cab['nama_cabang'];
        	$data['title2']		= '';
			$data['o']			= $this->M_fabrikasi->get_outstanding_by_id($id_po_fabrikasi);
			$data['cabang']		= $cabang;
			$this->load->view('outstanding-fabrikasi/edit', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_user	= [
				'id_po_fabrikasi'	=> $id_po_fabrikasi,
				'no_po'				=> $data['no_po'],
				'tanggal'			=> $data['tanggal'],
				'nama_user'			=> $data['nama_user'],
				'id_user' 			=> $this->session->userdata('id_user'),
			];

			
			if ($this->M_fabrikasi->update_outstanding($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-outstanding-fabrikasi/'.$id_po_fabrikasi.'/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('outstanding-fabrikasi/'.$cabang);
			}
		}
	}

	// public function hapus_outstanding($id_po_fabrikasi)
	// {
	// 	$get_po = $this->M_fabrikasi->get_outstanding_by_id($id_po_fabrikasi);
	// 	$no_po = $get_po['no_po'];
	// 	$cek_invoice = $this->db->get_where('detail_fabrikasi', ['no_po' => $no_po])->num_rows();
	// 	if ($cek_invoice === 0){
	// 		$this->M_fabrikasi->delete_outstanding($id_po_fabrikasi);
	// 		$this->session->set_flashdata('msg', 'hapus');
	// 	} else {
	// 		$this->session->set_flashdata('msg', 'error');
	// 	}
	// 	redirect('outstanding-fabrikasi');
	// }

	public function hapus_outstanding($id_po_fabrikasi, $cabang)
	{
		$get_po = $this->M_fabrikasi->get_outstanding_by_id($id_po_fabrikasi);
		$no_po = $get_po['no_po'];
		$cek_invoice = $this->db->get_where('detail_fabrikasi', ['no_po' => $no_po])->num_rows();
		if ($cek_invoice === 0){
			$this->M_fabrikasi->delete_outstanding($id_po_fabrikasi);
			$this->session->set_flashdata('msg', 'hapus');
		} else {
			$this->session->set_flashdata('msg', 'error');
		}
		redirect('outstanding-fabrikasi/'.$cabang);
	}

	public function riwayat_outstanding()
	{
        $data['title']		= 'Riwayat Outstanding Fabrikasi';
        $data['title2']		= 'a';
		$data['outstanding']	= $this->M_fabrikasi->get_riwayat_outstanding()->result_array();
		$this->load->view('outstanding-fabrikasi/riwayat', $data);
	}

	// public function detail_outstanding($id_po_fabrikasi)
	// {
	// 	if (is_admin()) {
	// 		$this->db->where('id_po_fabrikasi', $id_po_fabrikasi);
	// 		$this->db->update('po_fabrikasi', ['status' => 1]);
    //     }
    //     $data['title']		= 'Outstanding Fabrikasi';
    //     $data['title2']		= '';
    //     $data['o']			= $this->M_fabrikasi->get_outstanding_by_id($id_po_fabrikasi);
	// 	$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['o']['tanggal'])));
	// 	$bulan 				= $this->bulan($pch_tgl[1]);
	// 	$data['tanggal']	= date('d', strtotime($data['o']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['o']['tanggal']));
	// 	$get_po_fabrikasi = $this->M_fabrikasi->get_detail_outstanding($id_po_fabrikasi)->result_array();
	// 	$total_po_fabrikasi = '0';
	// 	foreach ($get_po_fabrikasi as $i) {
	// 		$total_po_fabrikasi += $i['total_harga_barang'];
	// 	}

    //     $data['total_outstanding'] 	= $total_po_fabrikasi;
	// 	$data['o_detail']			= $this->M_fabrikasi->get_detail_outstanding($id_po_fabrikasi)->result_array();
	// 	$this->load->view('outstanding-fabrikasi/detail', $data);
	// }

	public function detail_outstanding($id_po_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		if (is_admin()) {
			$this->db->where('id_po_fabrikasi', $id_po_fabrikasi);
			$this->db->update('po_fabrikasi', ['status' => 1]);
        }
        $data['title']		= 'Outstanding Fabrikasi '.$cab['nama_cabang'];
        $data['title2']		= '';
        $data['o']			= $this->M_fabrikasi->get_outstanding_by_id($id_po_fabrikasi);
		$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['o']['tanggal'])));
		$bulan 				= $this->bulan($pch_tgl[1]);
		$data['tanggal']	= date('d', strtotime($data['o']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['o']['tanggal']));
		$get_po_fabrikasi = $this->M_fabrikasi->get_detail_outstanding($id_po_fabrikasi, $cab['id_cabang'])->result_array();
		$total_po_fabrikasi = '0';
		foreach ($get_po_fabrikasi as $i) {
			$total_po_fabrikasi += $i['total_harga_barang'];
		}

        $data['total_outstanding'] 	= $total_po_fabrikasi;
		$data['o_detail']			= $this->M_fabrikasi->get_detail_outstanding($id_po_fabrikasi, $cab['id_cabang'])->result_array();
		$data['cabang']				= $cabang;
		$this->load->view('outstanding-fabrikasi/detail', $data);
	}

	// public function tambah_detail_outstanding($id_po_fabrikasi)
	// {
	// 	$this->validation_detail_outstanding();
	// 	if (!$this->form_validation->run()) {
	// 		$data['title']				= 'Outstanding Fabrikasi';
	// 		$data['title2']				= '';
	// 		$data['o']					= $this->M_fabrikasi->get_outstanding_by_id($id_po_fabrikasi);
	// 		$data['id_po_fabrikasi'] 	= $id_po_fabrikasi;
	// 		$data['barang']				= $this->M_barang->get_barang_fabrikasi()->result_array();
	// 		$data['departemen'] 		= $this->M_departemen->get_data()->result_array();
	// 		$this->load->view('outstanding-fabrikasi/tambah_detail', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$id_barang	= $data['id_barang'];
	// 		$b 			= $this->M_barang->get_barang_by_id($id_barang);
	// 		$data_user	= [
	// 			'id_po_fabrikasi'		=> $id_po_fabrikasi,
	// 			'id_barang'				=> $id_barang,
	// 			'nama_barang'			=> $b['nama_barang'],
	// 			'kode_barang'			=> $b['kode_barang'],
	// 			'satuan_barang'			=> $b['satuan_barang'],
	// 			'harga_unit'			=> $data['harga_unit'],
	// 			'harga_instalasi'		=> $data['harga_instalasi'],
	// 			'jumlah_barang'			=> $data['jumlah_barang'],
	// 			'harga_barang'			=> $data['harga_barang'],
	// 			'total_harga_barang'	=> $data['jumlah_barang']*$data['harga_barang'],
	// 			'departemen'			=> $data['departemen']
	// 		];

			
	// 		if ($this->M_fabrikasi->insert_detail_outstanding($data_user)) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('tambah-detail-outstanding-fabrikasi/'.$id_po_fabrikasi);
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'success');
	// 			redirect('detail-outstanding-fabrikasi/'.$id_po_fabrikasi);
	// 		}
	// 	}
	// }

	public function tambah_detail_outstanding($id_po_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->validation_detail_outstanding();
		if (!$this->form_validation->run()) {
			$data['title']				= 'Outstanding Fabrikasi '.$cab['nama_cabang'];
			$data['title2']				= '';
			$data['o']					= $this->M_fabrikasi->get_outstanding_by_id($id_po_fabrikasi);
			$data['id_po_fabrikasi'] 	= $id_po_fabrikasi;
			$data['barang']				= $this->M_barang->get_barang_fabrikasi()->result_array();
			$data['departemen'] 		= $this->M_departemen->get_data()->result_array();
			$data['cabang']				= $cabang;
			$this->load->view('outstanding-fabrikasi/tambah_detail', $data);
		} else {
			$data		= $this->input->post(null, true);
			$id_barang	= $data['id_barang'];
			$b 			= $this->M_barang->get_barang_by_id($id_barang);
			$data_user	= [
				'id_po_fabrikasi'		=> $id_po_fabrikasi,
				'id_barang'				=> $id_barang,
				'nama_barang'			=> $b['nama_barang'],
				'kode_barang'			=> $b['kode_barang'],
				'satuan_barang'			=> $b['satuan_barang'],
				'harga_unit'			=> $data['harga_unit'],
				'harga_instalasi'		=> $data['harga_instalasi'],
				'jumlah_barang'			=> $data['jumlah_barang'],
				'harga_barang'			=> $data['harga_barang'],
				'total_harga_barang'	=> $data['jumlah_barang']*$data['harga_barang'],
				'departemen'			=> $data['departemen'],
				'id_cabang'				=> $cab['id_cabang']
			];

			
			if ($this->M_fabrikasi->insert_detail_outstanding($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-detail-outstanding-fabrikasi/'.$id_po_fabrikasi.'/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('detail-outstanding-fabrikasi/'.$id_po_fabrikasi.'/'.$cabang);
			}
		}
	}

	// public function edit_detail_outstanding($id_detail_po_fabrikasi, $id_po_fabrikasi)
	// {
	// 	$this->validation_detail_outstanding();
	// 	if (!$this->form_validation->run()) {
	// 		$data['title']		= 'Outstanding Fabrikasi';
	// 		$data['title2']		= '';
	// 		$data['id_po_fabrikasi'] = $id_po_fabrikasi;
	// 		$data['barang'] = $this->M_barang->get_barang_fabrikasi()->result_array();
	// 		$data['departemen'] = $this->M_departemen->get_data()->result_array();
	// 		$data['0'] = $this->M_fabrikasi->get_outstanding_by_id($id_po_fabrikasi);
	// 		$data['o_detail']	= $this->M_fabrikasi->get_detail_outstanding_by_id($id_detail_po_fabrikasi);
	// 		$this->load->view('outstanding-fabrikasi/edit_detail', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$id_barang	= $data['id_barang'];
	// 		$b 			= $this->M_barang->get_barang_by_id($id_barang);
	// 		$data_user	= [
	// 			'id_detail_po_fabrikasi' => $id_detail_po_fabrikasi,
	// 			'id_po_fabrikasi'		=> $id_po_fabrikasi,
	// 			'id_barang'			=> $id_barang,
	// 			'nama_barang'		=> $b['nama_barang'],
	// 			'kode_barang'		=> $b['kode_barang'],
	// 			'satuan_barang'		=> $b['satuan_barang'],
	// 			'harga_unit'		=> $data['harga_unit'],
	// 			'harga_instalasi'	=> $data['harga_instalasi'],
	// 			'jumlah_barang'		=> $data['jumlah_barang'],
	// 			'harga_barang'		=> $data['harga_barang'],
	// 			'total_harga_barang'=> $data['jumlah_barang']*$data['harga_barang'],
	// 			'departemen'		=> $data['departemen']
	// 		];

			
	// 		if ($this->M_fabrikasi->update_detail_outstanding($data_user)) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('edit-detail-outstanding-fabrikasi/'.$id_detail_po_fabrikasi.'/'.$id_po_fabrikasi);
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'edit');
	// 			redirect('detail-outstanding-fabrikasi/'.$id_po_fabrikasi);
	// 		}
	// 	}
	// }

	public function edit_detail_outstanding($id_detail_po_fabrikasi, $id_po_fabrikasi, $cabang)
	{
		$this->validation_detail_outstanding();
		$cab = $this->M_cabang->get_by('slug', $cabang);
		if (!$this->form_validation->run()) {
			$data['title']					= 'Outstanding Fabrikasi '.$cab['nama_cabang'];
			$data['title2']					= '';
			$data['id_po_fabrikasi'] 		= $id_po_fabrikasi;
			$data['barang'] 				= $this->M_barang->get_barang_fabrikasi()->result_array();
			$data['departemen'] 			= $this->M_departemen->get_data()->result_array();
			$data['0'] 						= $this->M_fabrikasi->get_outstanding_by_id($id_po_fabrikasi);
			$data['o_detail']				= $this->M_fabrikasi->get_detail_outstanding_by_id($id_detail_po_fabrikasi);
			$data['cabang']					= $cabang;
			$this->load->view('outstanding-fabrikasi/edit_detail', $data);
		} else {
			$data		= $this->input->post(null, true);
			$id_barang	= $data['id_barang'];
			$b 			= $this->M_barang->get_barang_by_id($id_barang);
			$data_user	= [
				'id_detail_po_fabrikasi' => $id_detail_po_fabrikasi,
				'id_po_fabrikasi'		=> $id_po_fabrikasi,
				'id_barang'			=> $id_barang,
				'nama_barang'		=> $b['nama_barang'],
				'kode_barang'		=> $b['kode_barang'],
				'satuan_barang'		=> $b['satuan_barang'],
				'harga_unit'		=> $data['harga_unit'],
				'harga_instalasi'	=> $data['harga_instalasi'],
				'jumlah_barang'		=> $data['jumlah_barang'],
				'harga_barang'		=> $data['harga_barang'],
				'total_harga_barang'=> $data['jumlah_barang']*$data['harga_barang'],
				'departemen'		=> $data['departemen'],
				'id_cabang'			=> $cab['id_cabang']
			];

			
			if ($this->M_fabrikasi->update_detail_outstanding($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-detail-outstanding-fabrikasi/'.$id_detail_po_fabrikasi.'/'.$id_po_fabrikasi.'/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('detail-outstanding-fabrikasi/'.$id_po_fabrikasi.'/'.$cabang);
			}
		}
	}

	// public function hapus_detail_outstanding($id_detail_po_fabrikasi, $id_po_fabrikasi)
	// {
	// 	$this->M_fabrikasi->delete_detail_outstanding($id_detail_po_fabrikasi);
	// 	$this->session->set_flashdata('msg', 'hapus');
	// 	redirect('detail-outstanding-fabrikasi/'.$id_po_fabrikasi);
	// }

	public function hapus_detail_outstanding($id_detail_po_fabrikasi, $id_po_fabrikasi, $cabang)
	{
		$this->M_fabrikasi->delete_detail_outstanding($id_detail_po_fabrikasi);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('detail-outstanding-fabrikasi/'.$id_po_fabrikasi.'/'.$cabang);
	}

	// public function bap()
	// {
    //     $data['title']		= 'Fabrikasi';
    //     $data['title2']		= 'BAP';
	// 	$data['bap']	= $this->M_fabrikasi->get_bap()->result_array();
	// 	$this->load->view('bap-fabrikasi/data', $data);
	// }

	public function bap($cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
        $data['title']		= 'Fabrikasi '.$cab['nama_cabang'];
        $data['title2']		= 'BAP '.$cab['nama_cabang'];
		$data['bap']		= $this->M_fabrikasi->get_bap($cab['id_cabang'])->result_array();
		$data['cabang']		= $cabang;
		$this->load->view('bap-fabrikasi/data', $data);
	}

	// public function tambah_bap()
	// {
	// 	$this->validation_bap();
	// 	if (!$this->form_validation->run()) {
	// 		$data['title']	= 'Fabrikasi';
    //   $data['title2']	= 'BAP';
	// 		$data['departemen'] = $this->M_departemen->get_data()->result_array();
	// 		$this->load->view('bap-fabrikasi/tambah', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$data_user	= [
	// 			'tanggal_bap'	=> $data['tanggal_bap'],
	// 			'tanggal_finish'		=> $data['tanggal_finish'],
	// 			'lokasi'		=> $data['lokasi'],
	// 			'departemen'		=> $data['departemen'],
	// 		];

			
	// 		if ($this->M_fabrikasi->insert_bap($data_user)) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('tambah-bap-fabrikasi');
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'success');
	// 			redirect('bap-fabrikasi');
	// 		}
	// 	}
	// }

	public function tambah_bap($cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->validation_bap();
		if (!$this->form_validation->run()) {
			$data['title']			= 'Fabrikasi '.$cab['nama_cabang'];
      		$data['title2']			= 'BAP '.$cab['nama_cabang'];
			$data['departemen'] 	= $this->M_departemen->get_data()->result_array();
			$data['cabang']			= $cabang;
			$this->load->view('bap-fabrikasi/tambah', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_bap	= [
				'tanggal_bap'		=> $data['tanggal_bap'],
				'tanggal_finish'	=> $data['tanggal_finish'],
				'lokasi'			=> $data['lokasi'],
				'departemen'		=> $data['departemen'],
				'id_cabang'			=> $cab['id_cabang']
			];

			
			if ($this->M_fabrikasi->insert_bap($data_bap)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-bap-fabrikasi/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('bap-fabrikasi/'.$cabang);
			}
		}
	}

	// public function edit_bap($id_bap_fabrikasi)
	// {
	// 	$this->validation_bap();
	// 	if (!$this->form_validation->run()) {
	// 		$data['title']		= 'Fabrikasi';
    //   $data['title2']		= 'BAP';
	// 		$data['departemen'] = $this->M_departemen->get_data()->result_array();
	// 		$data['bap']	= $this->M_fabrikasi->get_bap_by_id($id_bap_fabrikasi);
	// 		$this->load->view('bap-fabrikasi/edit', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$data_user	= [
	// 			'id_bap_fabrikasi'	=> $id_bap_fabrikasi,
	// 			'tanggal_bap'	=> $data['tanggal_bap'],
	// 			'tanggal_finish'		=> $data['tanggal_finish'],
	// 			'lokasi'		=> $data['lokasi'],
	// 			'departemen'		=> $data['departemen'],
	// 		];

			
	// 		if ($this->M_fabrikasi->update_bap($data_user)) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('edit-bap-fabrikasi/'.$id_bap_fabrikasi);
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'edit');
	// 			redirect('bap-fabrikasi');
	// 		}
	// 	}
	// }

	public function edit_bap($id_bap_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->validation_bap();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Fabrikasi '.$cab['nama_cabang'];
      		$data['title2']		= 'BAP '.$cab['nama_cabang'];
			$data['departemen'] = $this->M_departemen->get_data()->result_array();
			$data['bap']		= $this->M_fabrikasi->get_bap_by_id($id_bap_fabrikasi);
			$data['cabang']		= $cabang;
			$this->load->view('bap-fabrikasi/edit', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_user	= [
				'id_bap_fabrikasi'	=> $id_bap_fabrikasi,
				'tanggal_bap'		=> $data['tanggal_bap'],
				'tanggal_finish'	=> $data['tanggal_finish'],
				'lokasi'			=> $data['lokasi'],
				'departemen'		=> $data['departemen'],
				'id_cabang'			=> $cab['id_cabang']
			];

			
			if ($this->M_fabrikasi->update_bap($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-bap-fabrikasi/'.$id_bap_fabrikasi.'/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('bap-fabrikasi/'.$cabang);
			}
		}
	}

	// public function hapus_bap($id_bap_fabrikasi)
	// {
	// 	$this->M_fabrikasi->delete_bap($id_bap_fabrikasi);
	// 	$this->db->delete('detail_bap_fabrikasi', ['id_bap_fabrikasi' => $id_bap_fabrikasi]);
	// 	$this->session->set_flashdata('msg', 'hapus');
	// 	redirect('bap-fabrikasi');
	// }

	public function hapus_bap($id_bap_fabrikasi, $cabang)
	{
		$this->M_fabrikasi->delete_bap($id_bap_fabrikasi);
		$this->db->delete('detail_bap_fabrikasi', ['id_bap_fabrikasi' => $id_bap_fabrikasi]);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('bap-fabrikasi/'.$cabang);
	}

	// public function detail_bap($id_bap_fabrikasi)
	// {
	// 	$data['title']		= 'Fabrikasi';
    //     $data['title2']		= 'BAP';
	// 	$data['bap']			= $this->M_fabrikasi->get_bap_by_id($id_bap_fabrikasi);
	// 	$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['bap']['tanggal_bap'])));
	// 	$bulan 				= $this->bulan($pch_tgl[1]);
	// 	$data['tanggal_bap']	= date('d', strtotime($data['bap']['tanggal_bap'])).' '.$bulan.' '.date('Y', strtotime($data['bap']['tanggal_bap']));
	// 	$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['bap']['tanggal_finish'])));
	// 	$bulan 				= $this->bulan($pch_tgl[1]);
	// 	$data['tanggal_finish']	= date('d', strtotime($data['bap']['tanggal_finish'])).' '.$bulan.' '.date('Y', strtotime($data['bap']['tanggal_finish']));
	// 	$data['bap_detail']	= $this->M_fabrikasi->get_detail_bap($id_bap_fabrikasi)->result_array();
	// 	$this->load->view('bap-fabrikasi/detail', $data);
	// }

	public function detail_bap($id_bap_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$data['title']			= 'Fabrikasi '.$cab['nama_cabang'];
        $data['title2']			= 'BAP '.$cab['nama_cabang'];
		$data['bap']			= $this->M_fabrikasi->get_bap_by_id($id_bap_fabrikasi);
		$pch_tgl				= explode('-', date('d-F-Y', strtotime($data['bap']['tanggal_bap'])));
		$bulan 					= $this->bulan($pch_tgl[1]);
		$data['tanggal_bap']	= date('d', strtotime($data['bap']['tanggal_bap'])).' '.$bulan.' '.date('Y', strtotime($data['bap']['tanggal_bap']));
		$pch_tgl				= explode('-', date('d-F-Y', strtotime($data['bap']['tanggal_finish'])));
		$bulan 					= $this->bulan($pch_tgl[1]);
		$data['tanggal_finish']	= date('d', strtotime($data['bap']['tanggal_finish'])).' '.$bulan.' '.date('Y', strtotime($data['bap']['tanggal_finish']));
		$data['bap_detail']		= $this->M_fabrikasi->get_detail_bap($id_bap_fabrikasi, $cab['id_cabang'])->result_array();
		$data['cabang']			= $cabang;
		$this->load->view('bap-fabrikasi/detail', $data);
	}

	// public function tambah_detail_bap($id_bap_fabrikasi)
	// {
	// 	$this->validation_bap_detail();
	// 	if (!$this->form_validation->run()) {
	// 		$data['title']	= 'Fabrikasi';
    //     	$data['title2']	= 'BAP';
    //     	$data['id_bap_fabrikasi'] = $id_bap_fabrikasi;
    //     	$this->db->select('*');
	// 		$this->db->from('detail_bap_fabrikasi');
	// 		$this->db->join('detail_fabrikasi', 'detail_fabrikasi.id_detail_fabrikasi=detail_bap_fabrikasi.id_detail_fabrikasi', 'right');
	// 		$this->db->where('detail_bap_fabrikasi.id_detail_fabrikasi', null);
	// 		$this->db->order_by('id_detail_bap_fabrikasi', 'ASC');
    //     	$data['invoice']	= $this->db->get()->result_array();
	// 		$this->load->view('bap-fabrikasi/tambah_detail', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$data_user	= [
	// 			'id_bap_fabrikasi'	=> $id_bap_fabrikasi,
	// 			'id_detail_fabrikasi'	=> $data['id_detail_fabrikasi'],
	// 		];

			
	// 		if ($this->M_fabrikasi->insert_bap_detail($data_user)) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('tambah-detail-bap-fabrikasi/'.$id_bap_fabrikasi);
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'success');
	// 			redirect('detail-bap-fabrikasi/'.$id_bap_fabrikasi);
	// 		}
	// 	}
	// }

	public function tambah_detail_bap($id_bap_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->validation_bap_detail();
		if (!$this->form_validation->run()) {
			$data['title']	= 'Fabrikasi '.$cab['nama_cabang'];
        	$data['title2']	= 'BAP '.$cab['nama_cabang'];
        	$data['id_bap_fabrikasi'] = $id_bap_fabrikasi;
			$data['cabang']	= $cabang;
        	$this->db->select('*');
			$this->db->from('detail_bap_fabrikasi');
			$this->db->join('detail_fabrikasi', 'detail_fabrikasi.id_detail_fabrikasi=detail_bap_fabrikasi.id_detail_fabrikasi', 'right');
			$this->db->where('detail_fabrikasi.id_cabang', $cab['id_cabang']);
			$this->db->where('detail_bap_fabrikasi.id_detail_fabrikasi', null);
			$this->db->order_by('id_detail_bap_fabrikasi', 'ASC');
        	$data['invoice']	= $this->db->get()->result_array();
			$this->load->view('bap-fabrikasi/tambah_detail', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_user	= [
				'id_bap_fabrikasi'		=> $id_bap_fabrikasi,
				'id_detail_fabrikasi'	=> $data['id_detail_fabrikasi'],
				'id_cabang'				=> $cab['id_cabang']
			];

			
			if ($this->M_fabrikasi->insert_bap_detail($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-detail-bap-fabrikasi/'.$id_bap_fabrikasi.'/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('detail-bap-fabrikasi/'.$id_bap_fabrikasi.'/'.$cabang);
			}
		}
	}

	// public function edit_detail_bap($id_detail_bap_fabrikasi, $id_bap_fabrikasi)
	// {
	// 	$this->validation_bap_detail();
	// 	if (!$this->form_validation->run()) {
	// 		$data['title']	= 'Fabrikasi';
    //     	$data['title2']	= 'BAP';
    //     	$data['id_detail_bap_fabrikasi'] = $id_detail_bap_fabrikasi;
    //     	$data['id_bap_fabrikasi'] = $id_bap_fabrikasi;
    //     	$data['bap']	= $this->M_fabrikasi->get_bap_detail_by_id($id_detail_bap_fabrikasi);
    //     	$this->db->select('*');
	// 		$this->db->from('detail_bap_fabrikasi');
	// 		$this->db->join('detail_fabrikasi', 'detail_fabrikasi.id_detail_fabrikasi=detail_bap_fabrikasi.id_detail_fabrikasi', 'right');
	// 		//$this->db->where('detail_bap_fabrikasi.id_detail_fabrikasi', null);
	// 		$this->db->order_by('id_detail_bap_fabrikasi', 'ASC');
    //     	$data['invoice']	= $this->db->get()->result_array();
	// 		$this->load->view('bap-fabrikasi/edit_detail', $data);
	// 	} else {
	// 		$data		= $this->input->post(null, true);
	// 		$data_user	= [
	// 			'id_detail_bap_fabrikasi' => $id_detail_bap_fabrikasi,
	// 			'id_bap_fabrikasi'	=> $id_bap_fabrikasi,
	// 			'id_detail_fabrikasi'	=> $data['id_detail_fabrikasi'],
	// 		];

			
	// 		if ($this->M_fabrikasi->update_bap_detail($data_user)) {
	// 			$this->session->set_flashdata('msg', 'error');
	// 			redirect('edit-detail-bap-fabrikasi/'.$id_detail_bap_fabrikasi.'/'.$id_bap_fabrikasi);
	// 		} else {
	// 			$this->session->set_flashdata('msg', 'success');
	// 			redirect('detail-bap-fabrikasi/'.$id_bap_fabrikasi);
	// 		}
	// 	}
	// }

	public function edit_detail_bap($id_detail_bap_fabrikasi, $id_bap_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->validation_bap_detail();
		if (!$this->form_validation->run()) {
			$data['title']	= 'Fabrikasi '.$cab['nama_cabang'];
        	$data['title2']	= 'BAP '.$cab['nama_cabang'];
        	$data['id_detail_bap_fabrikasi'] = $id_detail_bap_fabrikasi;
        	$data['id_bap_fabrikasi'] = $id_bap_fabrikasi;
        	$data['bap']	= $this->M_fabrikasi->get_bap_detail_by_id($id_detail_bap_fabrikasi);
			$data['cabang']	= $cabang;
        	$this->db->select('*');
			$this->db->from('detail_bap_fabrikasi');
			$this->db->join('detail_fabrikasi', 'detail_fabrikasi.id_detail_fabrikasi=detail_bap_fabrikasi.id_detail_fabrikasi', 'right');
			$this->db->where('detail_fabrikasi.id_cabang', $cab['id_cabang']);
			//$this->db->where('detail_bap_fabrikasi.id_detail_fabrikasi', null);
			$this->db->order_by('id_detail_bap_fabrikasi', 'ASC');
        	$data['invoice']	= $this->db->get()->result_array();
			$this->load->view('bap-fabrikasi/edit_detail', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_user	= [
				'id_detail_bap_fabrikasi' 	=> $id_detail_bap_fabrikasi,
				'id_bap_fabrikasi'			=> $id_bap_fabrikasi,
				'id_detail_fabrikasi'		=> $data['id_detail_fabrikasi'],
				'id_cabang'					=> $cab['id_cabang']
			];

			
			if ($this->M_fabrikasi->update_bap_detail($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-detail-bap-fabrikasi/'.$id_detail_bap_fabrikasi.'/'.$id_bap_fabrikasi.'/'.$cabang);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('detail-bap-fabrikasi/'.$id_bap_fabrikasi.'/'.$cabang);
			}
		}
	}

	// public function hapus_detail_bap($id_detail_bap_fabrikasi, $id_bap_fabrikasi)
	// {
	// 	$this->M_fabrikasi->delete_bap_detail($id_detail_bap_fabrikasi);
	// 	$this->session->set_flashdata('msg', 'hapus');
	// 	redirect('detail-bap-fabrikasi/'.$id_bap_fabrikasi);
	// }

	public function hapus_detail_bap($id_detail_bap_fabrikasi, $id_bap_fabrikasi, $cabang)
	{
		$this->M_fabrikasi->delete_bap_detail($id_detail_bap_fabrikasi);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('detail-bap-fabrikasi/'.$id_bap_fabrikasi.'/'.$cabang);
	}

	// public function cetak_bap($id_bap_fabrikasi)
	// {
	// 	$this->load->library('pdf');
	// 	$data['title']		= 'Fabrikasi';
    //     $data['title2']		= 'BAP';
	// 	$data['bap']			= $this->M_fabrikasi->get_bap_by_id($id_bap_fabrikasi);
	// 	$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['bap']['tanggal_bap'])));
	// 	$bulan 				= $this->bulan($pch_tgl[1]);
	// 	$data['tanggal_bap']	= date('d', strtotime($data['bap']['tanggal_bap'])).' '.$bulan.' '.date('Y', strtotime($data['bap']['tanggal_bap']));
	// 	$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['bap']['tanggal_finish'])));
	// 	$bulan 				= $this->bulan($pch_tgl[1]);
	// 	$data['tanggal_finish']	= date('d', strtotime($data['bap']['tanggal_finish'])).' '.$bulan.' '.date('Y', strtotime($data['bap']['tanggal_finish']));
	// 	$data['bap_detail']	= $this->M_fabrikasi->get_detail_bap($id_bap_fabrikasi)->result_array();
	// 	$data['jum_bap_detail']	= $this->M_fabrikasi->get_detail_bap($id_bap_fabrikasi)->num_rows();

    //     $html_content = $this->load->view('bap-fabrikasi/cetak_bap', $data, true);
    //     $filename = 'BAP - '.$data['bap']['departemen'].' - '.$data['tanggal_bap'].'.pdf';

    //     $this->pdf->loadHtml($html_content);

    //     $this->pdf->set_paper('a4','potrait');
        
    //     $this->pdf->render();
    //     $this->pdf->stream($filename, ['Attachment' => 1]);
	// }

	public function cetak_bap($id_bap_fabrikasi, $cabang)
	{
		$cab = $this->M_cabang->get_by('slug', $cabang);
		$this->load->library('pdf');
		$data['title']					= 'Fabrikasi '.$cab['nama_cabang'];
        $data['title2']					= 'BAP '.$cab['nama_cabang'];
		$data['bap']					= $this->M_fabrikasi->get_bap_by_id($id_bap_fabrikasi);
		$pch_tgl						= explode('-', date('d-F-Y', strtotime($data['bap']['tanggal_bap'])));
		$bulan 							= $this->bulan($pch_tgl[1]);
		$data['tanggal_bap']			= date('d', strtotime($data['bap']['tanggal_bap'])).' '.$bulan.' '.date('Y', strtotime($data['bap']['tanggal_bap']));
		$pch_tgl						= explode('-', date('d-F-Y', strtotime($data['bap']['tanggal_finish'])));
		$bulan 							= $this->bulan($pch_tgl[1]);
		$data['tanggal_finish']			= date('d', strtotime($data['bap']['tanggal_finish'])).' '.$bulan.' '.date('Y', strtotime($data['bap']['tanggal_finish']));
		$data['bap_detail']				= $this->M_fabrikasi->get_detail_bap($id_bap_fabrikasi, $cab['id_cabang'])->result_array();
		$data['jum_bap_detail']			= $this->M_fabrikasi->get_detail_bap($id_bap_fabrikasi, $cab['id_cabang'])->num_rows();

        $html_content = $this->load->view('bap-fabrikasi/cetak_bap', $data, true);
        $filename = 'BAP - '.$data['bap']['departemen'].' - '.$data['tanggal_bap'].'.pdf';

        $this->pdf->loadHtml($html_content);

        $this->pdf->set_paper('a4','potrait');
        
        $this->pdf->render();
        $this->pdf->stream($filename, ['Attachment' => 1]);
	}

}
