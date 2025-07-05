<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends CI_Controller {

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

	public function index()
	{
		$post_m = $this->input->post('month');
		if(empty($post_m)){
			$month = date('Y-m');
		} else {
			$month = $post_m;
		}
		$data['month_c'] = $month;
		$data['month']		= $this->db->query("SELECT DATE_FORMAT(tanggal, '%Y-%m') as tgl1, DATE_FORMAT(tanggal, '%M %Y') as tgl FROM no_quot GROUP BY MONTH(tanggal), YEAR(tanggal) order by tanggal DESC")->result_array();
		$data['title']		= 'Quotation';
		$data['title2']		= 'Quotation';
		$data['quotation']	= $this->M_quotation->get_no($post_m != 'All' ? $month : null)->result_array();
		$this->load->view('quotation/data_no', $data);
	}

	public function tambah_no_quot()
	{
		$this->validation_no();
		if (!$this->form_validation->run()) {
			$tahun 			= date('Y');
			$bulan 			= $this->bulan_romawi(date('F'));
			$data['title']		= 'Quotation';
        	$data['title2']		= 'Quotation';
        	$last_invoice	= $this->db->select('*')
								->from('no_quot')
								->order_by('id_no_quot', 'DESC')
								->where("DATE_FORMAT(tanggal, '%Y') =", $tahun)
								->get()->row_array();
			if(empty($last_invoice)){
				$data['no_quot'] = '1/'.$bulan.'/'.$tahun;
			} else {
				$pch_invoice= explode('/', $last_invoice['no_quot']);
        		$no	= intval($pch_invoice[0]) + 1;
        		$data['no_quot'] = $no.'/'.$bulan.'/'.$tahun;
			}
			$this->load->view('quotation/tambah_no', $data);
		} else {
			$data		= $this->input->post(null, true);
			$bulan 			= $this->bulan_romawi(date('F', strtotime($data['tanggal'])));
			$tahun 			= date('Y', strtotime($data['tanggal']));
			$pch_no_quot = explode('/', $data['no_quot']);
			$no_quot = $pch_no_quot[0].'/'.$bulan.'/'.$tahun;
			$data_user	= [
				'no_quot'	=> $no_quot,
				'tanggal'		=> $data['tanggal'],
			];

			
			if ($this->M_quotation->insert_no($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-no-quotation');
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('no-quotation');
			}
		}
	}

	public function edit_no_quot($id_no_quot)
	{
		$this->validation_no();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Quotation';
        	$data['title2']		= 'Quotation';
			$data['q']	= $this->M_quotation->get_no_by_id($id_no_quot);
			$this->load->view('quotation/edit_no', $data);
		} else {
			$data		= $this->input->post(null, true);
			$bulan 			= $this->bulan_romawi(date('F', strtotime($data['tanggal'])));
			$tahun 			= date('Y', strtotime($data['tanggal']));
			$pch_no_quot = explode('/', $data['no_quot']);
			$no_quot = $pch_no_quot[0].'/'.$bulan.'/'.$tahun;
			$data_user	= [
				'id_no_quot'	=> $id_no_quot,
				'no_quot'	=> $no_quot,
				'tanggal'		=> $data['tanggal'],
			];

			
			if ($this->M_quotation->update_no($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-no-quotation/'.$id_no_quot);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('no-quotation');
			}
		}
	}

	public function hapus_no_quot($id_no_quot)
	{
		$quotation = $this->db->get_where('quotation', ['id_no_quot' => $id_no_quot])->result_array();
		foreach ($quotation as $key) {
			$id_quotation = $key['id_quotation'];
			$this->db->where('id_quotation', $id_quotation);
			$this->db->delete('detail_quotation');
			$this->M_quotation->delete($id_quotation);
		}
		$this->M_quotation->delete_no($id_no_quot);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('no-quotation');
	}

	public function copy($id_no_quot)
	{
		$tahun 			= date('Y');
		$bulan 			= $this->bulan_romawi(date('F'));
    	$last_invoice	= $this->db->select('*')
							->from('no_quot')
							->order_by('id_no_quot', 'DESC')
							->where("DATE_FORMAT(tanggal, '%Y') =", $tahun)
							->get()->row_array();
		if(empty($last_invoice)){
			$no_quot = '1/'.$bulan.'/'.$tahun;
		} else {
			$pch_invoice= explode('/', $last_invoice['no_quot']);
    		$no	= intval($pch_invoice[0]) + 1;
    		$no_quot = $no.'/'.$bulan.'/'.$tahun;
		}
		$data_user	= [
			'no_quot'	=> $no_quot,
			'tanggal'		=> date('Y-m-d'),
		];
	
		$this->M_quotation->insert_no($data_user);

		$get_last_quot = $this->db->from('no_quot')->order_by('id_no_quot', 'DESC')->get()->row_array();
		$id_no_quot_new = $get_last_quot['id_no_quot'];

		$quotation_old = $this->db->get_where('quotation', ['id_no_quot' => $id_no_quot])->result_array();

		foreach ($quotation_old as $qo) {
			$id_quot_old = $qo['id_quotation'];
			$data_quotation_new = [
				'id_no_quot'=> $id_no_quot_new,
				'deskripsi'	=> $qo['deskripsi'],
				'qty'		=> $qo['qty'],
				'uom'		=> $qo['uom'],
				'harga'		=> $qo['harga'],
				'total'		=> $qo['total'],
			];

			$this->M_quotation->insert($data_quotation_new);

			$get_last_quot_old = $this->db->from('quotation')->order_by('id_quotation', 'DESC')->get()->row_array();
			$id_quot_new = $get_last_quot_old['id_quotation'];

			$detail_quotation_old = $this->db->get_where('detail_quotation', ['id_quotation' => $id_quot_old])->result_array();

			foreach ($detail_quotation_old as $dqo) {
				$data_detail_quotation_old	= [
					'id_quotation'	=> $id_quot_new,
					'deskripsi'	=> $dqo['deskripsi'],
					'qty'		=> $dqo['qty'],
					'uom'		=> $dqo['uom'],
					'harga'		=> $dqo['harga'],
					'total'		=> $dqo['total'],
				];
				$this->M_quotation->insert_detail($data_detail_quotation_old);
			}
		}

		$this->session->set_flashdata('msg', 'success');
		redirect('no-quotation');
	}
	
	public function quotation($id_no_quot)
	{
        $data['title']		= 'Quotation';
        $data['title2']		= 'Quotation';
        $data['id_no_quot']	= $id_no_quot;
		$data['quotation']	= $this->M_quotation->get($id_no_quot)->result_array();
		$data['nq']			= $this->M_quotation->get_no_by_id($id_no_quot);
		$this->load->view('quotation/data', $data);
	}

	public function all_quotation()
	{
		$data['title']				= 'Quotation';
		$data['title2']				= 'All Quotation';
		$this->load->view('quotation/data_all', $data);
	}

	public function ajax_datatable_all()
	{
		$quotation = array();
        $limit          = html_escape($this->input->post('length'));
        $start          = html_escape($this->input->post('start'));
        $totalData 		= $this->M_quotation->get_quotation_all()->num_rows();
        $totalFiltered  = $totalData;

		if (!empty($this->input->post('search')['value'])) {
			$search = $this->input->post('search')['value'];
			$quotation =  $this->M_quotation->get_quotation_all($limit, $start, $search)->result_array();
			$totalFiltered = $this->M_quotation->count_quotation_all($search);
		} else {
			$quotation = $this->M_quotation->get_quotation_all($limit, $start)->result_array();
		}

        $data = array();
        if (!empty($quotation)) {
            foreach ($quotation as $key => $row) {
				$link = "'".base_url('hapus-quotation/'.$row['id_quotation'].'/'.$row['id_no_quot'])."'";
				$mid = "document.location.href=$link";
				$a = 'data-confirm-yes="'.$mid.';"';

                $action = "
				<a href=".base_url('detail-quotation/'.$row['id_quotation'].'/'.$row['id_no_quot'])." class='btn btn-light'><i class='fa fa-list'></i> Detail</a>
				<a href=".base_url('edit-quotation/'.$row['id_quotation'].'/'.$row['id_no_quot'])." class='btn btn-info'><i class='fa fa-edit'></i> Edit</a>
				<button class='btn btn-danger' data-confirm='Anda yakin ingin menghapus data ini?|Data yang sudah dihapus tidak akan kembali.' $a onclick='del($(this))'><i class='fa fa-trash'></i> Delete</button>";

				$nestedData['#'] = $key + 1;
                $nestedData['no_quot'] = $row['no_quot'];
                $nestedData['deskripsi'] = $row['deskripsi'];
                $nestedData['qty'] = $row['qty'];
                $nestedData['uom'] = $row['uom'];
                $nestedData['harga'] = 'Rp '. number_format($row['harga'], 2,',','.');
                $nestedData['total'] = 'Rp '. number_format($row['total'], 2,',','.');
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

	public function tambah($id_no_quot)
	{
		$this->validation();
		if (!$this->form_validation->run()) {
			$data['id_no_quot']	= $id_no_quot;
			$data['title']		= 'Quotation';
        	$data['title2']		= 'Quotation';
			$this->load->view('quotation/tambah', $data);
		} else {
			$data		= $this->input->post(null, true);

			if(is_null($data['harga']) || $data['harga'] == 0){
				$data_user	= [
					'id_no_quot'=> $id_no_quot,
					'deskripsi'	=> $data['deskripsi'],
					'qty'		=> $data['qty'],
					'uom'		=> $data['uom'],
					'diskon'	=> $data['diskon'],
				];
			} else {
				$data_user	= [
					'id_no_quot'=> $id_no_quot,
					'deskripsi'	=> $data['deskripsi'],
					'qty'		=> $data['qty'],
					'uom'		=> $data['uom'],
					'harga'		=> $data['harga'],
					'diskon'	=> $data['diskon'],
					'total'		=> $data['qty']*$data['harga'],
				];
				
			}
			
			if ($this->M_quotation->insert($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-quotation/'.$id_no_quot);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('quotation/'.$id_no_quot);
			}
		}
	}

	public function edit($id_quotation, $id_no_quot)
	{
		$this->validation();
		if (!$this->form_validation->run()) {
			$data['id_no_quot']	= $id_no_quot;
			$data['title']		= 'Quotation';
        	$data['title2']		= 'Quotation';
			$data['q']	= $this->M_quotation->get_by_id($id_quotation);
			$this->load->view('quotation/edit', $data);
		} else {
			$data		= $this->input->post(null, true);
			if(is_null($data['harga']) || $data['harga'] == 0){
				$data_user	= [
					'id_quotation'	=> $id_quotation,
					'deskripsi'	=> $data['deskripsi'],
					'qty'		=> $data['qty'],
					'uom'		=> $data['uom'],
					'diskon'	=> $data['diskon'],
				];
			} else {
				$data_user	= [
					'id_quotation'	=> $id_quotation,
					'deskripsi'	=> $data['deskripsi'],
					'qty'		=> $data['qty'],
					'uom'		=> $data['uom'],
					'harga'		=> $data['harga'],
					'diskon'	=> $data['diskon'],
					'total'		=> $data['qty']*$data['harga'],
				];
				
			}
			
			if ($this->M_quotation->update($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-quotation/'.$id_quotation.'/'.$id_no_quot);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('quotation/'.$id_no_quot);
			}
		}
	}

	public function hapus($id_quotation, $id_no_quot)
	{
		$this->M_quotation->delete($id_quotation);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('quotation/'.$id_no_quot);
	}

	//DETAIL
	public function detail($id_quotation, $id_no_quot)
	{
		$data['title']		= 'Quotation';
		$data['title2']		= 'Quotation';
		$data['id_quotation']	= $id_quotation;
		$data['id_no_quot']	= $id_no_quot;
		$data['q']			= $this->M_quotation->get_by_id($id_quotation);
		$data['quotation']	= $this->M_quotation->get_detail($id_quotation)->result_array();
		$this->load->view('quotation/detail', $data);
	}

	public function tambah_detail($id_quotation, $id_no_quot)
	{
		$this->validation_detail();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Quotation';
        	$data['title2']		= 'Quotation';
        	$data['id_quotation'] = $id_quotation;
        	$data['id_no_quot']	= $id_no_quot;
			$this->load->view('quotation/tambah_detail', $data);
		} else {
			$data		= $this->input->post(null, true);
			$pch_deskripsi = explode('-', $data['deskripsi']);
			if($pch_deskripsi[0] == 'Profit' || $pch_deskripsi[0] == 'profit'){
				$get_total = $this->db->query("SELECT sum(total) as total FROM detail_quotation where id_quotation=".$id_quotation."")->row_array();
				$harga = $get_total['total'] * intval($pch_deskripsi[1]) / 100;
				$deskripsi = 'Profit - '.$pch_deskripsi[1].'%';
			} else {
				$harga = $data['harga'];
				$deskripsi = $data['deskripsi'];
			}

			$data_user	= [
				'id_quotation'	=> $id_quotation,
				'deskripsi'	=> $deskripsi,
				'qty'		=> $data['qty'],
				'kategori'		=> $data['kategori'],
				'uom'		=> $data['uom'],
				'harga'		=> $harga,
				'total'		=> $data['qty']*$harga,
			];
			$insert = $this->M_quotation->insert_detail($data_user);

			$get_total = $this->db->query("SELECT sum(total) as total FROM detail_quotation where id_quotation=".$id_quotation."")->row_array();
			$get_quotation = $this->db->get_where('quotation', ['id_quotation' => $id_quotation])->row_array();
			$data_quotation	= [
				'id_quotation'	=> $id_quotation,
				'harga'		=> $get_total['total'],
				'total'		=> $get_quotation['qty']*$get_total['total'],
			];
			$this->M_quotation->update($data_quotation);
			
			if ($insert) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-detail-quotation/'.$id_quotation.'/'.$id_no_quot);
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('detail-quotation/'.$id_quotation.'/'.$id_no_quot);
			}
		}
	}

	public function edit_detail($id_detail_quotation, $id_quotation, $id_no_quot)
	{
		$this->validation_detail();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Quotation';
        	$data['title2']		= 'Quotation';
        	$data['id_quotation'] = $id_quotation;
        	$data['id_no_quot']	= $id_no_quot;
        	$data['id_detail_quotation'] = $id_detail_quotation;
        	$data['q']			= $this->M_quotation->get_detail_by_id($id_detail_quotation);
			$this->load->view('quotation/edit_detail', $data);
		} else {
			$data		= $this->input->post(null, true);
			$pch_deskripsi = explode('-', $data['deskripsi']);
			if($pch_deskripsi[0] == 'Profit' || $pch_deskripsi[0] == 'profit'){
				$get_total = $this->db->query("SELECT sum(total) as total FROM detail_quotation where id_quotation=".$id_quotation."")->row_array();
				$harga = $get_total['total'] * intval($pch_deskripsi[1]) / 100;
				$deskripsi = 'Profit - '.$pch_deskripsi[1].'%';
			} else {
				$harga = $data['harga'];
				$deskripsi = $data['deskripsi'];
			}
			$data_user	= [
				'id_detail_quotation' => $id_detail_quotation,
				'id_quotation'	=> $id_quotation,
				'deskripsi'	=> $deskripsi,
				'kategori'		=> $data['kategori'],
				'qty'		=> $data['qty'],
				'uom'		=> $data['uom'],
				'harga'		=> $harga,
				'total'		=> $data['qty']*$harga,
			];

			$update = $this->M_quotation->update_detail($data_user);

			$get_total = $this->db->query("SELECT sum(total) as total FROM detail_quotation where id_quotation=".$id_quotation."")->row_array();
			$get_quotation = $this->db->get_where('quotation', ['id_quotation' => $id_quotation])->row_array();
			$data_quotation	= [
				'id_quotation'	=> $id_quotation,
				'harga'		=> $get_total['total'],
				'total'		=> $get_quotation['qty']*$get_total['total'],
			];
			$this->M_quotation->update($data_quotation);
			
			if ($update) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-detail-quotation/'.$id_detail_quotation.'/'.$id_quotation.'/'.$id_no_quot);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('detail-quotation/'.$id_quotation.'/'.$id_no_quot);
			}
		}
	}

	public function hapus_detail($id_detail_quotation, $id_quotation, $id_no_quot)
	{	
		$this->M_quotation->delete_detail($id_detail_quotation);
		$get_total = $this->db->query("SELECT sum(total) as total FROM detail_quotation where id_quotation=".$id_quotation."")->row_array();
		$get_quotation = $this->db->get_where('quotation', ['id_quotation' => $id_quotation])->row_array();
		$data_quotation	= [
			'id_quotation'	=> $id_quotation,
			'harga'		=> $get_total['total'],
			'total'		=> $get_quotation['qty']*$get_total['total'],
		];
		$this->M_quotation->update($data_quotation);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('detail-quotation/'.$id_quotation.'/'.$id_no_quot);
	}

	public function ekspor($id_no_quot)
	{
		$this->load->library('pdf');
		$data['title']		= 'Quotation';
        $data['title2']		= 'Quotation';
		$data['q']			= $this->M_quotation->get_no_by_id($id_no_quot);
		$quot				= explode('/', $data['q']['no_quot']);
		$data['quotation']	= $this->M_quotation->get($id_no_quot)->result_array();
		$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['q']['tanggal'])));
		$bulan 				= $this->bulan($pch_tgl[1]);
		$data['tanggal']	= date('d', strtotime($data['q']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['q']['tanggal']));
		
		$rincian = '';
		foreach ($data['quotation'] as $q) {
			$cek_rincian = $this->db->get_where('detail_quotation', ['id_quotation' => $q['id_quotation']])->num_rows();
			if ($cek_rincian != 0){
				$rincian .= $q['id_quotation'].',';
			}
		}

		if(!empty($rincian)){
			$rincian = substr($rincian, 0, -1);
		}
		
		$data['rincian'] = $rincian;
		
		$html_content = $this->load->view('quotation/cetak', $data, true);
        
        $filename = 'Quotation - '.$quot[0].'-'.$quot[1].'-'.$quot[2].'-'.$quot[3].'.pdf';

        $this->pdf->loadHtml($html_content);

        $this->pdf->set_paper('a4','potrait');
        
        $this->pdf->render();
        $this->pdf->stream($filename, ['Attachment' => 1]);
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

	private function validation_no()
	{
		$this->form_validation->set_rules('no_quot', 'No. Quotation', 'required|trim');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
	}

	private function validation()
	{
		$this->form_validation->set_rules('deskripsi', 'deskripsi', 'required|trim');
		$this->form_validation->set_rules('qty', 'qty', 'required|trim');
		$this->form_validation->set_rules('uom', 'uom', 'required|trim');
	}

	private function validation_detail()
	{
		$this->form_validation->set_rules('deskripsi', 'deskripsi', 'required|trim');
		$this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
		$this->form_validation->set_rules('qty', 'qty', 'required|trim');
		$this->form_validation->set_rules('uom', 'uom', 'required|trim');
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

}
