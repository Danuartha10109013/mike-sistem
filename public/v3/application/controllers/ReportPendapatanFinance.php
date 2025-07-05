<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ReportPendapatanFinance extends CI_Controller
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

    // public function index()
    // {
    // 	$data['title']				= 'Laporan';
    // 	$data['title2']				= 'Laporan Pengeluaran Stok';
    // 	$this->load->view('report/stokKeluar', $data);
    // }

    // public function server_side()
    // {
    //     $gs = array();
    //     $limit          = html_escape($this->input->post('length'));
    //     $start          = html_escape($this->input->post('start'));
    //     $totalData 		= $this->M_order->get_all_stok_keluar()->num_rows();
    //     $totalFiltered  = $totalData;

    //     if (!empty($this->input->post('search')['value'])) {
    //         $search = $this->input->post('search')['value'];
    //         $gs =  $this->M_order->get_all_stok_keluar($limit, $start, $search)->result_array();
    //         $totalFiltered = $this->M_order->count_all_stok_keluar($search);
    //     } else {
    //         $gs = $this->M_order->get_all_stok_keluar($limit, $start)->result_array();
    //     }

    //     $data = array();
    //     if (!empty($gs)) {
    //         foreach ($gs as $key => $row) {
    //             $nestedData['no'] = $key + 1;
    //             $nestedData['nama_barang'] = $row['nama_barang'];
    //             $nestedData['kode_barang'] = $row['kode_barang'];
    //             $nestedData['tanggal_detail_gs'] = date('d F Y', strtotime($row['tanggal_detail_gs']));
    //             $nestedData['jumlah_barang'] = $row['jumlah_barang'];
    //             $nestedData['harga_beli'] = 'Rp '. number_format($row['harga_beli'], 2,',','.');
    //             $nestedData['jenis_barang'] = $row['jenis_barang'];
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
    public function index()
    {
        $this->validation();
        if (!$this->form_validation->run()) {
            $data['title']    = 'Laporan';
            $data['title2']    = 'Laporan Pendapatan Finance';
            $data['omset']    = null;
            $data['dari_tanggal']   = null;
            $data['sampai_tanggal']   = null;
            $this->load->view('report/pendapatanFinance', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');
            $pch_dari_tgl = explode('-', $dari_tanggal);
            $pch_sampai_tgl = explode('-', $sampai_tanggal);
            $dari_bulan = $this->bulan($pch_dari_tgl[1]);
            $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
            if ($this->input->post('filter')) {
                $data['title']        = 'Laporan';
                $data['title2']       = 'Laporan Pendapatan Finance';
                // $data['omset']        = $this->M_order->get_omset_user($dari_tanggal, $sampai_tanggal)->result_array();
                $data['omset']        = $this->M_order->get_pendapatan_finance($dari_tanggal, $sampai_tanggal);
                $data['dari_tanggal']     = $dari_tanggal;
                $data['sampai_tanggal']   = $sampai_tanggal;
                $total = 0;
                // foreach ($data['omset'] as $i) {
                //     $total += $i['total'];
                // }
                $data['total'] = $total;

                $this->load->view('report/pendapatanFinance', $data);
            } else {
                $this->load->library('pdf');
                $data['title']        = 'Laporan';
                $data['title2']        = 'Laporan Pendapatan Finance';
                $data['dari_tanggal'] = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
                $data['sampai_tanggal'] = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
                $data['omset']        = $this->M_order->get_pendapatan_finance($dari_tanggal, $sampai_tanggal);
                $this->load->view('report/cetakPendapatanFinance', $data);
            }
        }
    }
    public function index2()
    {
        $this->validation();
        if (!$this->form_validation->run()) {
            $data['title']    = 'Laporan';
            $data['title2']    = 'Laporan Pendapatan Finance';
            $data['omset']    = null;
            $data['dari_tanggal']   = null;
            $data['sampai_tanggal']   = null;
            $this->load->view('report/pendapatanFinance', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');
            $pch_dari_tgl = explode('-', $dari_tanggal);
            $pch_sampai_tgl = explode('-', $sampai_tanggal);
            $dari_bulan = $this->bulan($pch_dari_tgl[1]);
            $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
            if ($this->input->post('filter')) {
                $data['title']        = 'Laporan';
                $data['title2']       = 'Laporan Pendapatan Finance';
                $data['omset']        = $this->M_order->get_omset_user($dari_tanggal, $sampai_tanggal)->result_array();
                $data['dari_tanggal']     = $dari_tanggal;
                $data['sampai_tanggal']   = $sampai_tanggal;
                $total = 0;
                foreach ($data['omset'] as $i) {
                    $total += $i['total'];
                }
                $data['total'] = $total;

                $this->load->view('report/pendapatanFinance', $data);
            } else {
                $this->load->library('pdf');
                $data['title']        = 'Laporan';
                $data['title2']        = 'Laporan Pendapatan Finance';
                $data['dari_tanggal'] = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
                $data['sampai_tanggal'] = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
                $data['omset']        = $this->M_order->get_omset_user($dari_tanggal, $sampai_tanggal)->result_array();
                $this->load->view('report/cetakPendapatanFinance', $data);
            }
        }
    }

    private function validation()
    {
        $this->form_validation->set_rules('dari_tanggal', 'Dari Tanggal', 'required|trim');
        $this->form_validation->set_rules('sampai_tanggal', 'Sampai Tanggal', 'required|trim');
    }

    private function bulan($bulan)
    {
        $bulan = $bulan;
        switch ($bulan) {
            case '01':
                $bulan = "Januari";
                break;
            case '02':
                $bulan = "Februari";
                break;
            case '03':
                $bulan = "Maret";
                break;
            case '04':
                $bulan = "April";
                break;
            case '05':
                $bulan = "Mei";
                break;
            case '06':
                $bulan = "Juni";
                break;
            case '07':
                $bulan = "Juli";
                break;
            case '08':
                $bulan = "Agustus";
                break;
            case '09':
                $bulan = "September";
                break;
            case '10':
                $bulan = "Oktober";
                break;
            case '11':
                $bulan = "November";
                break;
            case '12':
                $bulan = "Desember";
                break;
            default:
                $bulan = "Isi variabel tidak di temukan";
                break;
        }

        return $bulan;
    }
}
