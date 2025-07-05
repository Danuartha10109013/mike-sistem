<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Biodata extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('M_biodata');
		$this->load->library('upload');
	}
	
	public function index($id_mitra = null)
	{
        $data['title']		= 'Biodata';
        $data['title2']		= 'Biodata';
        if(!is_null($id_mitra)){
        	$data['b']	= $this->M_biodata->get_by_email($id_mitra);
			$this->load->view('biodata/detail', $data);
        }else{
        	$data['biodata']	= $this->M_biodata->get()->result_array();
			$this->load->view('biodata/data', $data);
        }
		
	}

	public function detail($id_mitra)
	{
        $data['title']		= 'Biodata';
        $data['title2']		= 'Biodata';
		$data['b']	= $this->M_biodata->get_by_email($id_mitra);
		$this->load->view('biodata/detail', $data);
	}

	public function tambah()
	{
		$this->validation();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Biodata';
        	$data['title2']		= 'Biodata';
			$this->load->view('biodata/tambah', $data);
		} else {
			$data		= $this->input->post(null, true);
			$foto_ktp	= $this->upload_ktp();
			$foto_pendaftaran	= $this->upload_pendaftaran();
			$foto_tempat= $this->upload_tempat();
			$data_user	= [
				'id_mitra'		=> $data['id_mitra'],
				'nama_mitra'	=> $data['nama_mitra'],
				'nama_owner'	=> $data['nama_owner'],
				'jenis_kelamin'	=> $data['jenis_kelamin'],
				'no_hp'			=> $data['no_hp'],
				'no_hp_2'		=> $data['no_hp_2'],
				'link_maps'		=> $data['link_maps'],
				'foto_ktp'		=> $foto_ktp,
				'foto_pendaftaran'		=> $foto_pendaftaran,
				'info_tambahan'		=> $data['info_tambahan'],
				'foto_tempat'	=> $foto_tempat,
				'link'			=> base_url('detail-biodata/'.$data['id_mitra'])
			];

			
			if ($this->M_biodata->insert($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-biodata');
			} else {
				$this->session->set_flashdata('msg', 'success');
				redirect('biodata');
			}
		}
	}

	public function edit($id_biodata)
	{
		$this->validation();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Biodata';
        	$data['title2']		= 'Biodata';
			$data['q']	= $this->M_biodata->get_by_id($id_biodata);
			$this->load->view('biodata/edit', $data);
		} else {
			$data		= $this->input->post(null, true);
			$data_user	= [
				'id_biodata'	=> $id_biodata,
				'id_mitra'		=> $data['id_mitra'],
				'nama_mitra'	=> $data['nama_mitra'],
				'nama_owner'	=> $data['nama_owner'],
				'jenis_kelamin'	=> $data['jenis_kelamin'],
				'no_hp'			=> $data['no_hp'],
				'no_hp_2'		=> $data['no_hp_2'],
				'link_maps'		=> $data['link_maps'],
				'foto_ktp'		=> $foto_ktp,
				'foto_tempat'	=> $foto_tempat,
				'link'			=> base_url('detail-biodata/'.$data['id_mitra'])
			];

			
			if ($this->M_biodata->update($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-biodata/'.$id_biodata);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('biodata');
			}
		}
	}

	public function hapus($id_quotation)
	{
		$this->M_biodata->delete($id_quotation);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('biodata');
	}

	private function upload_ktp()
	{
	    $config['upload_path'] = './assets/img/biodata';
	    $config['allowed_types'] = 'jpg|png|jpeg';
	    $config['max_size'] = 10100;
	    $this->upload->initialize($config);
	    $this->load->library('upload', $config);

	    if(! $this->upload->do_upload('foto_ktp'))
	    {
	    	return 'ktp.png';
	    }

	    return $this->upload->data('file_name');
	}

	private function upload_pendaftaran()
	{
	    $config['upload_path'] = './assets/img/biodata';
	    $config['allowed_types'] = 'jpg|png|jpeg';
	    $config['max_size'] = 10100;
	    $this->upload->initialize($config);
	    $this->load->library('upload', $config);

	    if(! $this->upload->do_upload('foto_pendaftaran'))
	    {
	    	return 'pendaftaran.png';
	    }

	    return $this->upload->data('file_name');
	}

	private function upload_tempat()
	{
	    $config['upload_path'] = './assets/img/biodata';
	    $config['allowed_types'] = 'jpg|png|jpeg';
	    $config['max_size'] = 10100;
	    $this->upload->initialize($config);
	    $this->load->library('upload', $config);

	    if(! $this->upload->do_upload('foto_tempat'))
	    {
	    	return 'tempat_tinggal.png';
	    }

	    return $this->upload->data('file_name');
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
		$this->form_validation->set_rules('id_mitra', 'id_mitra', 'required|trim');
		$this->form_validation->set_rules('nama_mitra', 'nama_lengkap', 'required|trim');
		$this->form_validation->set_rules('nama_owner', 'nama_lengkap', 'required|trim');
		$this->form_validation->set_rules('jenis_kelamin', 'jenis_kelamin', 'required|trim');
		$this->form_validation->set_rules('no_hp', 'no_hp', 'required|trim');
		$this->form_validation->set_rules('no_hp_2', 'no_hp_2', 'required|trim');
		$this->form_validation->set_rules('link_maps', 'link_maps', 'required|trim');
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
