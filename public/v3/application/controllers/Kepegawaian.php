<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kepegawaian extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
    }

    public function index()
    {
        $data['title']    = 'Informasi Kepegawaian';
        $data['title2']    = 'Informasi Kepegawaian';

        $awal = 24;
        $akhir = 23;
        $tanggal_sekarang = date('d');
        $tanggal = date('d-m-Y');

        if ($tanggal_sekarang >= 1 && $tanggal_sekarang <= 23) {
            $bulan_awal = date('Y-m', strtotime('-1 month', strtotime($tanggal))) . '-' . $awal;
            $bulan_akhir = date('Y-m', strtotime($tanggal)) . '-' . $akhir;
        } elseif ($tanggal_sekarang > 23) {
            $bulan_awal = date('Y-m', strtotime($tanggal)) . '-' . $awal;
            $bulan_akhir = date('Y-m', strtotime('+1 month', strtotime($tanggal))) . '-' . $akhir;
        }


        $dari_tanggal = $bulan_awal;
        $sampai_tanggal = $bulan_akhir;

        if ($this->input->post('filter')) {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');
        }

        $db_kasbon = $this->load->database('sintesa_kasbon', TRUE);

        $db_kasbon->select('*');
        $db_kasbon->from('pinjaman_kasbon');
        $db_kasbon->where('tanggal_pinjaman >=', $dari_tanggal);
        $db_kasbon->where('tanggal_pinjaman <=', $sampai_tanggal);
        $pinjaman_kasbon         = $db_kasbon->get()->result_array();

        $total_pinjaman_filter = '0';
        foreach ($pinjaman_kasbon as $i) {
            $total_pinjaman_filter += $i['total_pinjam'];
        }

        $db_kasbon->select('kasbon.id, kasbon.total_kasbon, kasbon.jenis_potongan, kasbon.tanggal_terakhir_potongan, users.name, potongan_kasbon.total_potongan, potongan_kasbon.sisa_kasbon');
        $db_kasbon->from('kasbon');
        $db_kasbon->join('(SELECT id_kasbon, MAX(id) as max_id FROM potongan_kasbon GROUP BY id_kasbon) pmax', 'kasbon.id = pmax.id_kasbon');
        $db_kasbon->join('potongan_kasbon', 'pmax.max_id = potongan_kasbon.id');
        $db_kasbon->join('users', 'users.id=kasbon.id_user');
        $db_kasbon->where('kasbon.tanggal_terakhir_potongan >=', $dari_tanggal);
        $db_kasbon->where('kasbon.tanggal_terakhir_potongan <=', $sampai_tanggal);
        $potongan_kasbon         = $db_kasbon->get()->result_array();

        $total_potongan_filter = '0';
        foreach ($potongan_kasbon as $i) {
            $total_potongan_filter += $i['total_potongan'];
        }

        $db_kasbon->from('kasbon');
        $db_kasbon->where('updated_at >=', $dari_tanggal);
        $db_kasbon->where('updated_at <=', $sampai_tanggal);
        $total_sisa_pinjaman         = $db_kasbon->get()->result_array();

        $total_sisa_pinjaman_filter = '0';
        foreach ($total_sisa_pinjaman as $i) {
            $total_sisa_pinjaman_filter += $i['total_kasbon'];
        }

        $data['awal']   = $bulan_awal;
        $data['akhir']  = $bulan_akhir;

        $data['dari_tanggal']               = $dari_tanggal;
        $data['sampai_tanggal']             = $sampai_tanggal;
        $data['total_pinjaman_filter']      = $total_pinjaman_filter;
        $data['total_potongan_filter']      = $total_potongan_filter;
        $data['total_sisa_pinjaman_filter'] = $total_sisa_pinjaman_filter;

        $this->load->view('kepegawaian/dashboard', $data);
    }

    public function data_kasbon()
    {
        $this->validation();

        $data['title']        = 'Data Kasbon';
        $data['title2']       = 'Data Kasbon';

        $db_kasbon = $this->load->database('sintesa_kasbon', TRUE);

        $db_kasbon->select('*');
        $db_kasbon->from('kasbon');
        $db_kasbon->join('users', 'users.id=kasbon.id_user');
        $db_kasbon->order_by('kasbon.updated_at', 'DESC');
        $data['kasbon']         = $db_kasbon->get()->result_array();

        $this->load->view('kepegawaian/data_kasbon', $data);
    }

    public function laporan_pinjaman_kasbon()
    {
        $this->validation();
        if (!$this->form_validation->run()) {

            $data['title']    = 'Laporan Kasbon';
            $data['title2']   = 'Pinjaman';
            $this->load->view('kepegawaian/laporan_pinjaman_kasbon', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');

            if ($this->input->post('filter')) {
                $data['title']        = 'Laporan Kasbon';
                $data['title2']       = 'Pinjaman';

                $db_kasbon = $this->load->database('sintesa_kasbon', TRUE);

                $db_kasbon->select('*');
                $db_kasbon->from('pinjaman_kasbon');
                $db_kasbon->join('users', 'users.id=pinjaman_kasbon.id_user');
                $db_kasbon->where('pinjaman_kasbon.tanggal_pinjaman >=', $dari_tanggal);
                $db_kasbon->where('pinjaman_kasbon.tanggal_pinjaman <=', $sampai_tanggal);
                $db_kasbon->order_by('pinjaman_kasbon.tanggal_pinjaman', 'DESC');
                $data['pinjaman_kasbon']         = $db_kasbon->get()->result_array();
                $data['dari_tanggal']   = $dari_tanggal;
                $data['sampai_tanggal'] = $sampai_tanggal;

                $this->load->view('kepegawaian/filter_laporan_pinjaman_kasbon', $data);
            }
        }
    }

    public function laporan_potongan_kasbon()
    {
        $this->validation();
        if (!$this->form_validation->run()) {

            $data['title']    = 'Laporan Kasbon';
            $data['title2']   = 'Potongan';
            $this->load->view('kepegawaian/laporan_potongan_kasbon', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');

            if ($this->input->post('filter')) {
                $data['title']        = 'Laporan Kasbon';
                $data['title2']       = 'Pinjaman';

                $db_kasbon = $this->load->database('sintesa_kasbon', TRUE);

                $db_kasbon->select('kasbon.id, kasbon.total_kasbon, kasbon.jenis_potongan, kasbon.tanggal_terakhir_potongan, users.name, potongan_kasbon.total_potongan, potongan_kasbon.sisa_kasbon');
                $db_kasbon->from('kasbon');
                $db_kasbon->join('(SELECT id_kasbon, MAX(id) as max_id FROM potongan_kasbon GROUP BY id_kasbon) pmax', 'kasbon.id = pmax.id_kasbon');
                $db_kasbon->join('potongan_kasbon', 'pmax.max_id = potongan_kasbon.id');
                $db_kasbon->join('users', 'users.id=kasbon.id_user');
                $db_kasbon->where('kasbon.tanggal_terakhir_potongan >=', $dari_tanggal);
                $db_kasbon->where('kasbon.tanggal_terakhir_potongan <=', $sampai_tanggal);
                $db_kasbon->order_by('kasbon.tanggal_terakhir_potongan', 'DESC');

                $data['potongan_kasbon']    = $db_kasbon->get()->result_array();
                $data['dari_tanggal']       = $dari_tanggal;
                $data['sampai_tanggal']     = $sampai_tanggal;

                $this->load->view('kepegawaian/filter_laporan_potongan_kasbon', $data);
            }
        }
    }


    private function validation()
    {
        $this->form_validation->set_rules('dari_tanggal', 'Dari Tanggal', 'required|trim');
        $this->form_validation->set_rules('sampai_tanggal', 'Sampai Tanggal', 'required|trim');
    }
}
