<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('login') != TRUE) {
      set_pesan('Silahkan login terlebih dahulu', false);
      redirect('');
    }
    date_default_timezone_set("Asia/Jakarta");
    $this->load->library('upload');
  }

  public function index()
  {
    $data['title']        = 'Penjualan';
    $data['title2']        = 'Data Penjualan';
    $data['total_tagihan'] = $this->M_penjualan->get_total_tagihan();
    $data['total_bayar'] = $this->M_penjualan->get_total_bayar();
    $data['total_sisa'] =  $data['total_tagihan'] - $data['total_bayar'];

    $this->load->view('penjualan/index', $data);
  }

  public function server_side()
  {
    $departemen = array();
    $limit          = html_escape($this->input->post('length'));
    $start          = html_escape($this->input->post('start'));
    $totalData     = $this->M_departemen->get_penjualan()->num_rows();
    $totalFiltered  = $totalData;

    if (!empty($this->input->post('search')['value'])) {
      $search = $this->input->post('search')['value'];
      $departemen =  $this->M_departemen->get_penjualan($limit, $start, $search)->result_array();
      $totalFiltered = $this->M_departemen->count_penjualan($search);
    } else {
      $departemen = $this->M_departemen->get_penjualan($limit, $start)->result_array();
    }

    $data = array();
    if (!empty($departemen)) {
      foreach ($departemen as $key => $row) {

        $action = "<center>
          <a href=" . base_url('detail-penjualan/' . $row['idd']) . " class='btn btn-light'><i class='fa fa-list'></i></a>";

        $invoice =  count($this->M_penjualan->get_by_id_penjualan($row['idd'])->result_array());


        $tagihan =  $this->M_penjualan->get_total_tagihan_by_id_penjualan($row['idd']);

        $this->db->select_sum('bayar');
        $this->db->join('gs', 'gs.id_gs=penjualan_bayar.id_gs');
        $this->db->where('gs.departemen',  $row['idd']);
        $query = $this->db->get('penjualan_bayar');
        $bayar_penjualan_sum = $query->row()->bayar;

        $sisa = $tagihan - $bayar_penjualan_sum;

        $this->db->select('jatuh_tempo_tagihan');
        $this->db->where('departemen',  $row['idd']);
        $this->db->where('jatuh_tempo_tagihan != ', null);
        $this->db->order_by('jatuh_tempo_tagihan',  'asc');
        $query = $this->db->get('gs');
        $jatuh_tempo = $query->row_array()['jatuh_tempo_tagihan'];

        if (empty($jatuh_tempo)) {
          $jatuh_tempo = '';
        }

        $tanggal_sekarang = new DateTime();
        $tanggal_jatuh_tempo = new DateTime($jatuh_tempo);
        $selisih_hari = $tanggal_jatuh_tempo->diff($tanggal_sekarang)->days;

        // Tentukan apakah jatuh tempo di masa depan atau sudah lewat
        $jatuh_tempo_text = '';
        if ($sisa > 0 && $selisih_hari < 7) {
          if ($selisih_hari == 0) {
            $jatuh_tempo_text = '<br><b class="text-danger"> Hari Ini Jatuh Tempo</b>';
          } elseif ($selisih_hari > 0) {
            $jatuh_tempo_text = '<br><b class="text-danger">' . $selisih_hari . ' Hari Lebih dari Jatuh Tempo</b>';
          } else {
            $jatuh_tempo_text = '<br><b class="text-danger">' . $selisih_hari . ' Hari Menuju Jatuh Tempo</b>';
          }
        }


        $nestedData['no'] = $key + 1;
        $nestedData['nama_departemen'] = $row['nama_departemen'];
        $nestedData['jatuh_tempo'] = $jatuh_tempo . $jatuh_tempo_text;
        $nestedData['banyak_invoice'] = $invoice;
        $nestedData['total_tagihan'] = 'Rp.' . number_format($tagihan, 2, ',', '.');
        $nestedData['total_bayar'] = 'Rp.' . number_format($bayar_penjualan_sum, 2, ',', '.');
        $nestedData['sisa_tagihan'] = 'Rp.' . number_format($sisa, 2, ',', '.');

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

  public function detail($id_departemen)
  {
    $data['title']        = 'Penjualan';
    $data['title2']        = 'Detail Penjualan';
    if (empty($id_departemen)) {
      redirect('penjualan');
    }
    $data['departemen'] =  $this->M_departemen->get_by_id($id_departemen)->row_array();

    $data['invoice'] =  count($this->M_penjualan->get_by_id_penjualan($id_departemen)->result_array());

    $data['total_tagihan'] =  $this->M_penjualan->get_total_tagihan_by_id_penjualan($id_departemen);

    $this->db->select_sum('bayar');
    $this->db->join('gs', 'gs.id_gs=penjualan_bayar.id_gs');
    $this->db->where('gs.departemen',  $id_departemen);
    $query = $this->db->get('penjualan_bayar');
    $data['bayar_penjualan_sum'] = $query->row()->bayar;

    $this->load->view('penjualan/detail_penjualan', $data);
  }

  public function server_side_detail()
  {
    $id_departemen = $_GET['dep'];

    $penjualan = array();
    $limit          = html_escape($this->input->post('length'));
    $start          = html_escape($this->input->post('start'));
    $totalData     = $this->M_penjualan->get_by_id_penjualan($id_departemen)->num_rows();
    $totalFiltered  = $totalData;

    if (!empty($this->input->post('search')['value'])) {
      $search = $this->input->post('search')['value'];
      $penjualan =  $this->M_penjualan->get_by_id_penjualan($id_departemen, $limit, $start, $search)->result_array();
      $totalFiltered = $this->M_penjualan->count_inv_by_id_penjualan($id_departemen, $search);
    } else {
      $penjualan = $this->M_penjualan->get_inv_by_id_penjualan($id_departemen, $limit, $start)->result_array();
    }

    $data = array();

    if (!empty($penjualan)) {
      foreach ($penjualan as $key => $row) {

        $link = "'" . base_url('hapus-penjualan/' . $row['id_departemen']) . "'";
        $mid = "document.location.href=$link";
        $a = 'data-confirm-yes="' . $mid . ';"';

        $action = "<center>
          <a href=" . base_url('detail-pembayaran-distributor/' . $row['id_gs']) . " class='btn btn-light'><i class='fa fa-list'></i></a>
           <button type='button' class='btn btn-success btn-edit' data-id=" . $row['id_gs'] . " data-tgl=" . $row['jatuh_tempo_tagihan'] . " ><i class='fa fa-edit'></i></button>";

        $this->db->select_sum('bayar');
        $this->db->where('id_gs',  $row['id_gs']);
        $query = $this->db->get('penjualan_bayar');
        $bayar_penjualan_sum = $query->row()->bayar;


        $sisa = $row['total_harga_barang_sum'] - $bayar_penjualan_sum;

        $tanggal_sekarang = new DateTime();
        $tanggal_jatuh_tempo = new DateTime($row['jatuh_tempo_tagihan']);
        $selisih_hari = $tanggal_jatuh_tempo->diff($tanggal_sekarang)->days;

        // Tentukan apakah jatuh tempo di masa depan atau sudah lewat
        $jatuh_tempo_text =  $row['jatuh_tempo_tagihan'];

        if ($sisa > 0 && $selisih_hari < 7 && $row['jatuh_tempo_tagihan'] != NULL) {
          if ($selisih_hari == 0) {
            $jatuh_tempo_text .= '<br><b class="text-danger"> Hari Ini Jatuh Tempo</b>';
          } elseif ($selisih_hari > 0) {
            $jatuh_tempo_text .= '<br><b class="text-danger">' . $selisih_hari . ' Hari Lebih dari Jatuh Tempo</b>';
          } else {
            $jatuh_tempo_text .= '<br><b class="text-danger">' . $selisih_hari . ' Hari Menuju Jatuh Tempo</b>';
          }
        } elseif ($row['jatuh_tempo_tagihan'] == NULL) {
          $jatuh_tempo_text =  '<span class="text-warning">Belum ada</span>';
        }
        if ($sisa == 0) {
          $jatuh_tempo_text .=  '<br><span class="text-success">Lunas</span>';
        }

        $nestedData['no'] = $key + 1;
        $nestedData['invoice'] = $row['no_invoice'];
        $nestedData['jatuh_tempo'] =  $jatuh_tempo_text;
        $nestedData['total_tagihan'] = 'Rp ' . number_format($row['total_harga_barang_sum'], 2, ',', '.');
        $nestedData['total_bayar'] = 'Rp ' . number_format($bayar_penjualan_sum, 2, ',', '.');
        $nestedData['sisa_tagihan'] = 'Rp ' . number_format($sisa, 2, ',', '.');

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

  public function detail_pembayaran_distributor($id_gs)
  {
    $data['title']        = 'Penjualan';
    $data['title2']        = 'Detail Pembayaran Distributor';

    $data['f']        = $this->M_order->get_by_id($id_gs);
    $pch_tgl          = explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));
    $bulan             = $this->bulan($pch_tgl[1]);
    $data['id_gs']    = $id_gs;
    $data['tanggal']  = date('d', strtotime($data['f']['tanggal'])) . ' ' . $bulan . ' ' . date('Y', strtotime($data['f']['tanggal']));


    $data['jatuh_tempo_tagihan']  = date('d', strtotime($data['f']['jatuh_tempo_tagihan'])) . ' ' . $bulan . ' ' . date('Y', strtotime($data['f']['jatuh_tempo_tagihan']));
    $data['f_detail']  = $this->M_order->get_detail($id_gs)->result_array();
    $data['barang']    = $this->db->get_where('detail_po_gs', ['jumlah_barang !=' => 0, 'id_po_gs =' => $data['f']['id_po_gs']])->result_array();
    $data['detail_penjualan_bayar']  = $this->db->get_where('penjualan_bayar', ['id_gs' => $id_gs])->result_array();
    $this->db->select_sum('bayar');
    $this->db->where('id_gs', $id_gs);
    $query = $this->db->get('penjualan_bayar');
    $data['bayar_penjualan_sum'] = $query->row()->bayar;
    $this->load->view('penjualan/pembayaran_penjualan', $data);
  }

  public function edit_inv()
  {
    $data    = $this->input->post(null, true);

    $fdata =  $this->M_order->get_by_id($data['id_gs']);

    if (empty($fdata)) {

      $this->session->set_flashdata('msg', 'error');
      redirect('detail-penjualan/' . $fdata['departemen']);
    }

    $data_up  = [
      'id_gs'          => $data['id_gs'],
      'jatuh_tempo_tagihan'    => $data['jatuh_tempo_tagihan'],
    ];

    if ($this->M_order->update($data_up)) {

      $this->session->set_flashdata('msg', 'edit');
      redirect('detail-penjualan/' . $fdata['departemen']);
    } else {
      log_activity($this->session->userdata('id_user'), 'edit Jatuh Tempo Penjualan', 'Jatuh Tempo Penjualan berhasil diedit ', $data_up);

      $this->session->set_flashdata('msg', 'error');
      redirect('detail-penjualan/' . $fdata['departemen']);
    }
  }

  private function bulan($bulan)
  {
    $bulan = $bulan;
    switch ($bulan) {
      case 'January':
        $bulan = "Januari";
        break;
      case 'February':
        $bulan = "Februari";
        break;
      case 'March':
        $bulan = "Maret";
        break;
      case 'April':
        $bulan = "April";
        break;
      case 'May':
        $bulan = "Mei";
        break;
      case 'June':
        $bulan = "Juni";
        break;
      case 'July':
        $bulan = "Juli";
        break;
      case 'August':
        $bulan = "Agustus";
        break;
      case 'September':
        $bulan = "September";
        break;
      case 'October':
        $bulan = "Oktober";
        break;
      case 'November':
        $bulan = "November";
        break;
      case 'December':
        $bulan = "Desember";
        break;
      default:
        $bulan = "Isi variabel tidak di temukan";
        break;
    }

    return $bulan;
  }

  public function tambah_bayar()
  {
    $this->db->trans_start();
    try {
      $data    = $this->input->post(null, true);
      $id_penjualan_bayar  = $data['id_penjualan_bayar'];

      $penjualan_bayar_bukti = $this->upload_bukti('penjualan_bayar_bukti');

      $detail_penjualan  = [
        'id_penjualan_bayar'        => $id_penjualan_bayar,
        'penjualan_bayar_ref'       => $data['penjualan_bayar_ref'],
        'id_gs'                     => $data['id_gs'],
        'penjualan_bayar_bukti'     => $penjualan_bayar_bukti,
        'bayar'                     => $data['bayar'],
        'tanggal_bayar_penjualan'   => $data['tanggal_bayar_penjualan']
      ];

      $this->db->insert('penjualan_bayar', $detail_penjualan);
      log_activity($this->session->userdata('id_user'), 'Tambah Bayar Penjualan', 'Bayar Penjualan berhasil ditambah ', $detail_penjualan);

      $this->db->trans_commit();
      set_pesan_two('Data Berhasil Ditambahkan', true);
    } catch (Exception $e) {
      $this->db->trans_rollback();
      set_pesan_two($e->getMessage(), false);
    }
    redirect('detail-pembayaran-distributor/' . $data['id_gs']);
  }

  public function hapus_bayar($id_penjualan_bayar, $id_gs)
  {
    $this->db->trans_start();
    $penjualanDetail = $this->db->get_where('penjualan_bayar', ['id_penjualan_bayar' => $id_penjualan_bayar])->row_array();
    log_activity($this->session->userdata('id_user'), 'Hapus Bayar Penjualan', 'Bayar Penjualan berhasil dihapus ', $penjualanDetail);

    if (!empty($penjualanDetail['bukti_bayar'])) {
      $file_path = "./assets/img/bukti_penjualan/" . $penjualanDetail['penjualan_bayar_bukti'];
      $this->delete_bukti($file_path);
    }
    $this->db->delete('penjualan_bayar', ['id_penjualan_bayar' => $id_penjualan_bayar]);
    $this->db->trans_complete();
    if ($this->db->trans_status() === FALSE) {
      set_pesan_two('Error!! Terjadi Error Di Database.', false);
    } else {
      set_pesan_two('Data Berhasil Dihapus.', true);
    }
    redirect('detail-pembayaran-distributor/' . $id_gs);
  }

  private function delete_bukti($file_path)
  {
    if (file_exists($file_path)) {
      unlink($file_path);
      return true;
    }
    return false;
  }

  private function upload_bukti($field)
  {
    $config['upload_path'] = './assets/img/bukti_penjualan';
    $config['allowed_types'] = 'jpg|png|jpeg';
    $config['max_size'] = 10100;
    $this->upload->initialize($config);
    $this->load->library('upload', $config);

    if (!$this->upload->do_upload($field)) {
      return 'upload_bukti_penjualan.png';
    }

    return $this->upload->data('file_name');
  }
}
