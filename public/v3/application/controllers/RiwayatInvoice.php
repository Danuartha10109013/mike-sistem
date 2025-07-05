<?php

defined('BASEPATH') or exit('No direct script access allowed');

class RiwayatInvoice extends CI_Controller
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
        $data['title']                = 'Riwayat';
        $data['title2']                = 'Riwayat Invoice';
        $this->load->view('invoice/riwayat', $data);
    }

    public function server_side()
    {
        $gs = array();
        $limit          = html_escape($this->input->post('length'));
        $start          = html_escape($this->input->post('start'));
        $totalData         = $this->M_order->get_all_detail()->num_rows();
        $totalFiltered  = $totalData;

        if (!empty($this->input->post('search')['value'])) {
            $search = $this->input->post('search')['value'];
            $gs =  $this->M_order->get_all_detail($limit, $start, $search)->result_array();
            $totalFiltered = $this->M_order->count_all_detail($search);
        } else {
            $gs = $this->M_order->get_all_detail($limit, $start)->result_array();
        }


        $data = array();
        if (!empty($gs)) {
            foreach ($gs as $key => $row) {


                $satuan =  $row['tipe_item'] === 'Diskon' ? '-' : $row['satuan_barang'];
                if (!empty($row['id_package'])) {
                    $satuan = $row['type_po'];
                }
                $nestedData['no'] = $key + 1;
                $nestedData['no_invoice'] = $row['no_invoice'];
                $nestedData['tanggal'] = $row['tanggal'];
                $nestedData['nama_barang'] = $row['nama_barang'];
                $nestedData['status_gs'] = $row['status_gs'];
                $nestedData['jumlah_barang'] = $row['tipe_item'] === 'Diskon' ? '-' : $row['jumlah_barang'];
                $nestedData['satuan_barang'] = $satuan;
                $nestedData['harga_barang'] = $row['tipe_item'] === 'Diskon' ? '-' : 'Rp ' . number_format($row['harga_barang'], 2, ',', '.');
                $nestedData['total_harga_barang'] = 'Rp ' . number_format($row['total_harga_barang'], 2, ',', '.');
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
}
