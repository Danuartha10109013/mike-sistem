<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian extends CI_Controller
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
    $data['title']        = 'Pembelian';
    $data['title2']        = 'Data Pembelian';
    $tahun       = date('Y');
    $bulan       = $this->bulan_romawi(date('F'));
    $last_pembelian  =   $this->db->select('*')
      ->from('pembelians')
      ->order_by('id_pembelian', 'DESC')
      ->where("DATE_FORMAT(tanggal_pembelian, '%Y') =", $tahun)
      ->get()->row_array();

    if (empty($last_pembelian)) {
      $noPembelian = '1/PEM/' . $bulan . '/' . $tahun;
    } else {
      $pch_pembelian = explode('/', $last_pembelian['no_pembelian']);
      $no  = intval($pch_pembelian[0]) + 1;
      $noPembelian = "$no/PEM/$bulan/$tahun";
    }
    $data['no_pembelian'] = $noPembelian;
    $data['allpembelian'] =  $this->M_pembelian->beli_bayar()->result_array();

    $this->load->view('pembelian/index', $data);
  }

  public function server_side()
  {
    $pembelian = array();
    $limit          = html_escape($this->input->post('length'));
    $start          = html_escape($this->input->post('start'));
    $totalData     = $this->M_pembelian->get_all()->num_rows();
    $totalFiltered  = $totalData;

    if (!empty($this->input->post('search')['value'])) {
      $search = $this->input->post('search')['value'];
      $pembelian =  $this->M_pembelian->get_all($limit, $start, $search)->result_array();
      $totalFiltered = $this->M_pembelian->count_all($search);
    } else {
      $pembelian = $this->M_pembelian->get_all($limit, $start)->result_array();
    }

    $data = array();
    if (!empty($pembelian)) {
      foreach ($pembelian as $key => $row) {

        $link = "'" . base_url('hapus-pembelian/' . $row['id_pembelian']) . "'";
        $mid = "document.location.href=$link";
        $a = 'data-confirm-yes="' . $mid . ';"';

        $delete = "<button class='btn btn-danger' data-confirm='Apakah Anda yakin akan hapus data ini?' $a onclick='del($(this))'><i class='fa fa-trash'></i></button></center>";

        $action = "<center>
          <a href=" . base_url('detail-pembelian/' . $row['id_pembelian']) . " class='btn btn-light'><i class='fa fa-list'></i></a>
          <button type='button' class='btn btn-success btn-edit' data-id=" . $row['id_pembelian'] . "><i class='fa fa-edit'></i></button>
          $delete";

        $sisa = $row['total_harga_beli_sum'] - $row['nominal_bayar_sum'];

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


        $nestedData['no'] = $key + 1;
        $nestedData['no_pembelian'] = $row['no_pembelian'];
        $nestedData['nama_pabrik'] = $row['nama_pabrik'];
        $nestedData['no_ref'] = $row['no_ref'];
        $nestedData['upload_bukti'] = "<a href=" . base_url('assets/img/bukti_pembelian/' . $row['upload_bukti']) . " target='_blank'>Bukti</a>";
        $nestedData['tanggal_pembelian'] = date('d F Y', strtotime($row['tanggal_pembelian']));
        $nestedData['jatuh_tempo'] = $jatuh_tempo_text;
        $nestedData['total_hutang'] = 'Rp.' . number_format($row['total_harga_beli_sum'], 2, ',', '.');
        $nestedData['total_bayar'] = 'Rp.' . number_format($row['nominal_bayar_sum'], 2, ',', '.');
        $nestedData['sisa_hutang'] = 'Rp.' . number_format($sisa, 2, ',', '.');
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

  public function get_by_id()
  {
    $id_pembelian = $this->input->post('id_pembelian');
    $data = $this->db->get_where('pembelians', ['id_pembelian' => $id_pembelian])->row_array();
    echo json_encode($data);
  }

  public function tambah()
  {
    $data        = $this->input->post(null, true);
    $upload_bukti = $this->upload_bukti('upload_bukti');
    $pembelian = [
      'no_pembelian'  => $data['no_pembelian'],
      'nama_pabrik'    => $data['nama_pabrik'],
      'upload_bukti'  => $upload_bukti,
      'no_ref'        => $data['no_ref'],
      'tanggal_pembelian'        => $data['tanggal_pembelian'],
      'jatuh_tempo'   => $data['jatuh_tempo']
    ];
    log_activity($this->session->userdata('id_user'), 'Tambah Pembelian', 'Pembelian berhasil ditambahkan ', $pembelian);

    $this->db->insert('pembelians', $pembelian);
    set_pesan('Data Berhasil Ditambahkan', true);
    redirect('pembelian');
  }

  public function edit()
  {
    $data           = $this->input->post(null, true);
    $id_pembelian          = $data['id_pembelian'];
    $pembelianDetail = $this->db->get_where('pembelians', ['id_pembelian' => $id_pembelian])->row_array();
    $pembelian  = [
      'id_pembelian'        => $id_pembelian,
      'nama_pabrik'          => $data['nama_pabrik'],
      'no_ref'              => $data['no_ref'],
      'tanggal_pembelian'    => $data['tanggal_pembelian'],
      'jatuh_tempo'         => $data['jatuh_tempo']
    ];

    if (!empty($_FILES['upload_bukti']['name'])) {
      if (!empty($pembelianDetail['upload_bukti'])) {
        $file_path = "./assets/img/bukti_pembelian/" . $pembelianDetail['upload_bukti'];
        $this->delete_bukti($file_path);
      }
      $upload_bukti = $this->upload_bukti('upload_bukti');
      $pembelian['upload_bukti'] = $upload_bukti;
    }

    if ($this->M_pembelian->update($pembelian)) {
      set_pesan('Terjadi Error...', false);
      redirect('pembelian');
    } else {
      log_activity($this->session->userdata('id_user'), 'Edit Pembelian', 'Pembelian berhasil diedit ', $pembelian);

      set_pesan('Data Berhasil Diupdate', true);
      redirect('pembelian');
    }
  }

  public function hapus($id_pembelian)
  {
    $pembelianDetail = $this->db->get_where('pembelians', ['id_pembelian' => $id_pembelian])->row_array();
    log_activity($this->session->userdata('id_user'), 'Hapus Pembelian', 'Pembelian berhasil dihapus ', $pembelianDetail);

    if (!empty($pembelianDetail['upload_bukti'])) {
      $file_path = "./assets/img/bukti_pembelian/" . $pembelianDetail['upload_bukti'];
      $this->delete_bukti($file_path);
    }
    $this->db->delete('pembelians', ['id_pembelian' => $id_pembelian]);
    $this->db->delete('detail_pembelian', ['id_pembelian' => $id_pembelian]);
    $this->db->delete('detail_bayar_pembelian', ['id_pembelian' => $id_pembelian]);
    set_pesan('Data Berhasil Dihapus', true);
    redirect('pembelian');
  }

  public function detail($id_pembelian)
  {
    $data['title']    = 'Pembelian';
    $data['title2']    = 'Detail Pembelian';
    $data['pembelian']        = $this->M_pembelian->detail_pembelian($id_pembelian)->row_array();

    $this->db->select('detail_pembelian.*, barang.*');
    $this->db->from('detail_pembelian');
    $this->db->join('barang', 'barang.id_barang = detail_pembelian.id_barang');
    $this->db->where('detail_pembelian.id_pembelian', $id_pembelian);
    $query = $this->db->get();
    $data['detail_pembelian']  = $query->result_array();

    $data['detail_pembelian_bayar']  = $this->db->get_where('detail_pembelian_bayar', ['id_pembelian' => $id_pembelian])->result_array();
    $data['barang']      = $this->M_barang->get_barang()->result_array();
    $this->load->view('pembelian/detail', $data);
  }

  public function get_by_id_detail()
  {
    $id_detail_pembelian = $this->input->post('id_detail_pembelian');
    $data = $this->db->get_where('detail_pembelian', ['id_detail_pembelian' => $id_detail_pembelian])->row_array();
    echo json_encode($data);
  }

  public function tambah_detail()
  {
    $this->db->trans_start();
    try {
      $data    = $this->input->post(null, true);
      $id_pembelian  = $data['id_pembelian'];

      // $pembelian = $this->M_pembelian->detail_pembelian($id_pembelian)->row_array();

      // if ($pembelian['bayar_pembelian_sum'] >= $pembelian['harga_beli']) {
      //   $this->db->trans_rollback();
      //   set_pesan('Pembayaran sudah lunas', false);
      // }

      // if (($pembelian['bayar_pembelian_sum'] + $data['bayar_pembelian']) > $pembelian['harga_beli']) {
      //   $this->db->trans_rollback();
      //   set_pesan('Pembayaran yang diinput melebihi pembayaran yang harus dilunasi', false);
      // }

      // if ($pembelian['qty_bayar_sum'] >= $pembelian['jumlah']) {
      //   $this->db->trans_rollback();
      //   set_pesan('Jumlah pembelian sudah lunas', false);
      // }

      // if (($pembelian['qty_bayar_sum'] + $data['qty_bayar']) > $pembelian['jumlah']) {
      //   $this->db->trans_rollback();
      //   set_pesan('Jumlah yang diinput melebihi yang harus dilunasi', false);
      // }

      $detail_pembelian  = [
        'id_pembelian'            => $id_pembelian,
        'id_barang'               => $data['id_barang'],
        'tanggal_beli_barang'     => $data['tanggal_beli_barang'],
        'jumlah_beli_barang'      => $data['jumlah_beli_barang'],
        'harga_beli_barang'       => $data['harga_beli_barang'],
        'total_harga_beli'        => $data['jumlah_beli_barang'] * $data['harga_beli_barang'],
        'status_beli_barang'      => $data['status_beli_barang'],
      ];

      $this->db->insert('detail_pembelian', $detail_pembelian);
      log_activity($this->session->userdata('id_user'), 'Tambah Detail Pembelian', 'Detail Pembelian berhasil ditambahkan', $detail_pembelian);

      $this->db->trans_commit();
      set_pesan('Data Berhasil Ditambahkan', true);
    } catch (Exception $e) {
      $this->db->trans_rollback();
      set_pesan($e->getMessage(), false);
    }
    redirect('detail-pembelian/' . $id_pembelian);
  }

  public function tambah_detail_bayar()
  {
    $this->db->trans_start();
    try {
      $data    = $this->input->post(null, true);
      $id_pembelian  = $data['id_pembelian'];

      $bukti_bayar = $this->upload_bukti('bukti_bayar');

      $detail_pembelian  = [
        'id_pembelian'            => $id_pembelian,
        'no_ref_bayar_pembelian'  => $data['no_ref_bayar_pembelian'],
        'bukti_bayar'             => $bukti_bayar,
        'nominal_bayar'           => $data['nominal_bayar'],
        'tanggal_bayar'           => $data['tanggal_bayar']
      ];

      $this->db->insert('detail_pembelian_bayar', $detail_pembelian);
      log_activity($this->session->userdata('id_user'), 'Tambah Detail Bayar Pembelian', 'Detail Bayar Pembelian berhasil ditambahkan', $detail_pembelian);

      $this->db->trans_commit();
      set_pesan_two('Data Berhasil Ditambahkan', true);
    } catch (Exception $e) {
      $this->db->trans_rollback();
      set_pesan_two($e->getMessage(), false);
    }
    redirect('detail-pembelian/' . $id_pembelian);
  }

  public function hapus_detail($id_detail_pembelian, $id_gs)
  {
    $this->db->trans_start();
    $pembelianDetail = $this->db->get_where('detail_pembelian', ['id_detail_pembelian' => $id_detail_pembelian])->row_array();
    log_activity($this->session->userdata('id_user'), 'Hapus Detail Pembelian', 'Detail Pembelian berhasil dihapus ', $pembelianDetail);

    $this->db->delete('detail_pembelian', ['id_detail_pembelian' => $id_detail_pembelian]);
    $this->db->trans_complete();
    if ($this->db->trans_status() === FALSE) {
      set_pesan('Error!! Terjadi Error Di Database.', false);
    } else {
      set_pesan('Data Berhasil Dihapus.', true);
    }
    redirect('detail-pembelian/' . $id_gs);
  }

  public function hapus_detail_bayar($id_detail_pembelian_bayar, $id_gs)
  {
    $this->db->trans_start();
    $pembelianDetail = $this->db->get_where('detail_pembelian_bayar', ['id_detail_pembelian_bayar' => $id_detail_pembelian_bayar])->row_array();
    log_activity($this->session->userdata('id_user'), 'Hapus Detail Bayar Pembelian', 'Detail Bayar Pembelian berhasil dihapus ', $pembelianDetail);

    if (!empty($pembelianDetail['bukti_bayar'])) {
      $file_path = "./assets/img/bukti_pembelian/" . $pembelianDetail['bukti_bayar'];
      $this->delete_bukti($file_path);
    }
    $this->db->delete('detail_pembelian_bayar', ['id_detail_pembelian_bayar' => $id_detail_pembelian_bayar]);
    $this->db->trans_complete();
    if ($this->db->trans_status() === FALSE) {
      set_pesan_two('Error!! Terjadi Error Di Database.', false);
    } else {
      set_pesan_two('Data Berhasil Dihapus.', true);
    }
    redirect('detail-pembelian/' . $id_gs);
  }

  private function bulan_romawi($bulan)
  {
    $bulan = $bulan;
    switch ($bulan) {
      case 'January':
        $bulan = "I";
        break;
      case 'February':
        $bulan = "II";
        break;
      case 'March':
        $bulan = "III";
        break;
      case 'April':
        $bulan = "IV";
        break;
      case 'May':
        $bulan = "V";
        break;
      case 'June':
        $bulan = "VI";
        break;
      case 'July':
        $bulan = "VII";
        break;
      case 'August':
        $bulan = "VIII";
        break;
      case 'September':
        $bulan = "IX";
        break;
      case 'October':
        $bulan = "X";
        break;
      case 'November':
        $bulan = "XI";
        break;
      case 'December':
        $bulan = "XII";
        break;
      default:
        $bulan = "Isi variabel tidak di temukan";
        break;
    }

    return $bulan;
  }

  private function upload_bukti($field)
  {
    $config['upload_path'] = './assets/img/bukti_pembelian';
    $config['allowed_types'] = 'jpg|png|jpeg';
    $config['max_size'] = 10100;
    $this->upload->initialize($config);
    $this->load->library('upload', $config);

    if (!$this->upload->do_upload($field)) {
      return 'upload_bukti.png';
    }

    return $this->upload->data('file_name');
  }

  private function delete_bukti($file_path)
  {

    if (file_exists($file_path)) {
      unlink($file_path);
      return true;
    }
    return false;
  }
}
