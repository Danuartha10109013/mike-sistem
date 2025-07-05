<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Olshop extends CI_Controller {

	

	public function index()
	{
		$data['title'] = '';
		$data['title2'] = '';
		$this->load->view('olshop/input', $data);
	}

	public function print()
	{
		$data		= $this->input->post(null, true);
		$data['isi']	= [
			'nama_pengirim'	=> $data['nama_pengirim'],
			'no_hp_pengirim'		=> $data['no_hp_pengirim'],
			'alamat_pengirim'		=> $data['alamat_pengirim'],
			'nama_penerima'	=> $data['nama_penerima'],
			'no_hp_penerima'		=> $data['no_hp_penerima'],
			'alamat_penerima'		=> $data['alamat_penerima'],
			'isi_paket'		=> $data['isi_paket'],
		];
		$this->load->view('olshop/print', $data);
	}
}
