<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Dashboard extends CI_Controller

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



    public function xx()

    {

        $judul = "Notifikasi";

        $konten = "PO xxx harus ditagih besok";

        $url = "https://andikaemasabadi.xyz/admin/laporan_penjualan/pertransaksi";

        $response = $this->send_message($judul, $konten, $url);

        $config = [

            'mailtype'  => 'html',

            'charset'   => 'utf-8',

            'protocol'  => 'smtp',

            'smtp_host' => 'smtp.gmail.com',

            'smtp_user' => 'uzisteven18@gmail.com',  // Email gmail

            'smtp_pass'   => 'capricorn031999',  // Password gmail

            'smtp_crypto' => 'ssl',

            'smtp_port'   => 465,

            'crlf'    => "\r\n",

            'newline' => "\r\n"

        ];



        // Load library email dan konfigurasinya

        $this->load->library('email', $config);



        // Email dan nama pengirim

        $this->email->from('no-reply@sintesa.com', 'Notifikasi Sintesa');



        // Email penerima

        $this->email->to('ramadhan070thea@gmail.com'); // Ganti dengan email tujuan



        // Lampiran email, isi dengan url/path file

        //$this->email->attach('https://images.pexels.com/photos/169573/pexels-photo-169573.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940');



        // Subject email

        $this->email->subject('Kirim Email dengan SMTP Gmail CodeIgniter | MasRud.com');



        // Isi email

        $this->email->message("contoh");



        // Tampilkan pesan sukses atau error

        if ($this->email->send()) {

            echo 'Sukses! email berhasil dikirim.';
        } else {

            echo 'Error! email tidak dapat dikirim.';
        }
    }



    private function send_message($judul, $konten, $url)

    {

        $content      = array(

            "en" => $konten

        );

        $heading = array(

            "en" => $judul

        );



        $fields = array(

            'app_id' => "aaef20d6-8324-46ef-8339-6006b7573729",

            'included_segments' => array(

                'All'

            ),

            'contents' => $content,

            'headings' => $heading,

            'url' => $url

        );



        $fields = json_encode($fields);



        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(

            "Content-Type: application/json; charset=utf-8",

            "Authorization: Basic YzY3OTIxMmUtOWU4NS00YmMwLTk2NGItM2RmZTUwOTFlNDQ0"

        ));

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_VERBOSE, TRUE);

        curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));



        $response = curl_exec($ch);

        curl_close($ch);



        return $response;
    }



    public function index()

    {



        $data['title']    = 'Dashboard';

        $data['title2']    = 'Dashboard';

        //Asset

        $data['current_month'] = isset($_GET['monthFilter']) && $_GET['monthFilter'] != '' ? $_GET['monthFilter'] : date('m');

        $data['current_year'] = isset($_GET['yearFilter']) && $_GET['yearFilter'] != '' ? $_GET['yearFilter'] : date('Y');







        $data['barang'] = $this->M_barang->count_data();



        $data['stok'] =  $this->M_barang->get_stok_barang()->result_array();



        $data['inv'] =  $this->db->get('gs')->num_rows();

        $data['invSelesai'] =  $this->db->where('status_gs', 'Sudah Selesai')->get('gs')->num_rows();

        $data['invBlmKirim'] =  $this->db->where(['status_kirim' => 'Belum Kirim', 'status_gs !=' => 'Sudah Selesai'])->get('gs')->num_rows();

        $data['invKirim'] =  $this->db->where(['status_kirim != ' => 'Belum Kirim', 'status_gs !=' => 'Sudah Selesai'])->get('gs')->num_rows();



        // $data['top5'] =  $this->db->select('SUM(total_harga_barang) as total')->join('detail_gs', 'detail_gs.id_gs = gs.id_gs')->where('gs.status_gs !=', 'Sudah Selesai')->get('gs')->result_array();

        $this->db->select('id_barang, nama_barang');

        $this->db->from('barang');

        $barang = $this->db->get()->result_array();



        $results = [];

        $data['recently'] = [];

        foreach ($barang as $key => $br) {

            $this->db->select('SUM(detail_gs.jumlah_barang) as barang_keluar');

            $this->db->from('detail_gs');

            $this->db->join('barang', 'barang.id_barang = detail_gs.id_barang', 'left');

            $this->db->join('packages', 'packages.id_package = detail_gs.id_package', 'left');

            $this->db->where("(detail_gs.id_barang = '" . $br['id_barang'] . "' OR FIND_IN_SET('" . $br['id_barang'] . "', packages.item_package) > 0)");

            $this->db->having("barang_keluar >", 0);

            $this->db->order_by('barang_keluar', 'DESC');

            $barang1 = $this->db->get()->result_array();



            if (!empty($barang1)) {

                $br['keluar'] = $barang1[0]['barang_keluar'];

                $results[] = $br;
            }



            $this->db->select('tanggal_detail_gs,SUM(detail_gs.jumlah_barang) as barang_keluar');

            $this->db->from('detail_gs');

            $this->db->join('barang', 'barang.id_barang = detail_gs.id_barang', 'left');

            $this->db->join('packages', 'packages.id_package = detail_gs.id_package', 'left');

            $this->db->where("(detail_gs.id_barang = '" . $br['id_barang'] . "' OR FIND_IN_SET('" . $br['id_barang'] . "', packages.item_package) > 0)");

            $this->db->having("barang_keluar >", 0);

            $this->db->order_by('tanggal_detail_gs', 'DESC');

            $barang2 = $this->db->get()->result_array();



            if (!empty($barang2)) {

                $recently[$key]['nama_barang'] = $br['nama_barang'];

                $recently[$key]['barang_keluar'] = $barang2[0]['barang_keluar'];
            }
        }



        if (!empty($data['recently'])) {

            $data['recently'] = array_slice($recently, 0, 5);

            // Sort the results by 'keluar' in descending order and get the top 5

            usort($results, function ($a, $b) {

                return $b['keluar'] - $a['keluar'];
            });
        }

        $data['top5'] = array_slice($results, 0, 5);



        $data['total_tagihan'] = $this->M_penjualan->get_total_tagihan();



        $data['total_bayar'] = $this->M_penjualan->get_total_bayar();

        $data['total_sisa'] =  $data['total_tagihan'] - $data['total_bayar'];



        $pembelian =  $this->M_pembelian->get_all();

        $data['allpembelian'] =  $this->M_pembelian->beli_bayar()->result_array();



        //distributor;

        $departemen =  $this->M_departemen->get_penjualan(5, 0)->result_array();

        $nestedData = [];
        $data['distri'] = [];
        foreach ($departemen as $key => $row) {



            $action = "

              <a href=" . base_url('detail-penjualan/' . $row['idd']) . " class='btn btn-light btn-sm'><i class='fa fa-list'></i></a> ";



            $invoice =  count($this->M_penjualan->get_by_id_penjualan($row['idd'])->result_array());





            $tagihan =  $this->M_penjualan->get_total_tagihan_by_id_penjualan($row['idd']);



            $this->db->select_sum('bayar');

            $this->db->join('gs', 'gs.id_gs=penjualan_bayar.id_gs');

            $this->db->where('gs.departemen',  $row['idd']);

            $query = $this->db->get('penjualan_bayar');

            $bayar_penjualan_sum = $query->row()->bayar;



            $sisa = $tagihan - $bayar_penjualan_sum;





            $nestedData['no'] = $key + 1;

            $nestedData['nama_departemen'] = $row['nama_departemen'];

            $nestedData['banyak_invoice'] = $invoice;

            $nestedData['sisa_tagihan'] = 'Rp.' . number_format($sisa, 2, ',', '.');



            $nestedData['actions'] = $action;

            $data['distri'][$key] = $nestedData;
        }





        $pembelian = $this->M_pembelian->get_all(5, 0, null)->result_array();



        $data['pembelian'] = array();

        foreach ($pembelian as $key => $row) {

            $action = "<a href=" . base_url('detail-pembelian/' . $row['id_pembelian']) . " class='btn btn-light btn-sm'><i class='fa fa-list'></i></a>  ";

            $sisa = $row['total_harga_beli_sum'] - $row['nominal_bayar_sum'];



            if ($sisa == 0) {

                continue;
            }



            $tanggal_sekarang = new DateTime();

            $tanggal_jatuh_tempo = new DateTime($row['jatuh_tempo']);

            $selisih_hari = $tanggal_jatuh_tempo->diff($tanggal_sekarang)->days;

            // Tentukan apakah jatuh tempo di masa depan atau sudah lewat

            $jatuh_tempo_text =  $row['jatuh_tempo'];



            if ($sisa > 0 && $selisih_hari < 7 && $row['jatuh_tempo'] != NULL) {

                if ($selisih_hari == 0) {

                    $jatuh_tempo_text .= '<br><b class="text-danger"> Hari Ini Jatuh Tempo</b>';
                } elseif ($selisih_hari > 0) {

                    $jatuh_tempo_text .= '<br><b class="text-danger">' . $selisih_hari . ' Hari Lebih dari Jatuh Tempo</b>';
                } else {

                    $jatuh_tempo_text .= '<br><b class="text-danger">' . $selisih_hari . ' Hari Menuju Jatuh Tempo</b>';
                }
            } elseif ($row['jatuh_tempo'] == NULL) {

                $jatuh_tempo_text =  '<span class="text-warning">Belum di ubah</span>';
            }

            if ($sisa == 0) {

                $jatuh_tempo_text .=  '<br><span class="text-success">Lunas</span>';
            }





            $nestedData['jatuh_tempo'] = $jatuh_tempo_text;

            $nestedData['nama_pabrik'] = $row['nama_pabrik'];

            $nestedData['sisa_hutang'] = 'Rp.' . number_format($sisa, 2, ',', '.');

            $nestedData['actions'] = $action;

            $data['pembelian'][$key] = $nestedData;
        }





        $data['piutang_tempo'] = $this->M_penjualan->get_inv_by_id_penjualan(null, 5, 0)->result_array();



        $this->load->view('dashboard', $data);
    }



    public function get_sales_data()

    {

        $month = $this->input->get('month');

        $year = $this->input->get('year');

        $sales_data = $this->M_penjualan->get_sales_data($month, $year);

        echo json_encode($sales_data);
    }
}
