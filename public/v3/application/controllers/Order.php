<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
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
    $this->db->select_sum('total_harga_barang');
    $this->db->from('po_gs');
    $this->db->join('detail_po_gs', 'po_gs.id_po_gs=detail_po_gs.id_po_gs');
    $get_po_gs = $this->db->get()->row_array();
    $data['total_outstanding'] = $get_po_gs['total_harga_barang'];
    $data['title']    = 'Order';
    $data['title2']    = 'Data Order';
    if (is_admin()) {
      $data['outstanding']  = $this->M_order->get_outstanding()->result_array();
    } else {
      $data['outstanding']  = $this->M_order->get_outstanding($this->session->userdata('id_user'))->result_array();
    }
    $tahun       = date('Y');
    $bulan       = $this->bulan_romawi(date('F'));
    $last_order  =   $this->db->select('*')
      ->from('po_gs')
      ->order_by('id_po_gs', 'DESC')
      ->get()->row_array();

    if (empty($last_order)) {
      $data['no_po'] = '1';
    } else {
      $no  = intval($last_order['no_po']) + 1;
      $data['no_po'] = $no;
    }
    $data['departemen'] = $this->M_departemen->get_data()->result_array();

    $this->load->view('order/index', $data);
  }

  public function get_by_id()
  {
    $id_po_gs = $this->input->post('id_po_gs');
    $data = $this->db->get_where('po_gs', ['id_po_gs' => $id_po_gs])->row_array();
    echo json_encode($data);
  }

  public function tambah()
  {
    $data    = $this->input->post(null, true);
    $data_order  = [
      'no_po'    => $data['no_po'],
      'tanggal'    => $data['tanggal'],
      'nama_user'    => $data['nama_user'],
      'kontak_customer'    => $data['kontak_customer'],
      'address_customer'    => $data['address_customer'],
      'id_user' => $this->session->userdata('id_user'),
      'departemen' => $data['departemen']
    ];

    $cek_po = $this->db->get_where('po_gs', ['no_po' => $data['no_po']])->num_rows();
    if ($cek_po > 0) {
      set_pesan('Error! Data dengan nomor order yang diinput sudah ada!', false);
      redirect('data-order');
    }

    if ($this->M_order->insert_outstanding($data_order)) {
      set_pesan('Terjadi Error...', false);
      redirect('data-order');
    } else {
      log_activity($this->session->userdata('id_user'), 'Tambah Order', 'Order berhasil ditambahkan', $data_order);
      set_pesan('Data Berhasil Ditambahkan', true);
      redirect('data-order');
    }
  }

  public function edit($id_po_gs)
  {
    $id_po_gs = $this->input->post('id_po_gs');
    $data    = $this->input->post(null, true);
    $data_order  = [
      'id_po_gs'      => $id_po_gs,
      'no_po'          => $data['no_po'],
      'tanggal'    => $data['tanggal'],
      'nama_user'    => $data['nama_user'],
      'kontak_customer'    => $data['kontak_customer'],
      'address_customer'    => $data['address_customer'],

    ];

    if ($this->M_order->update_outstanding($data_order)) {
      set_pesan('Terjadi Error...', false);
      redirect('data-order');
    } else {
      log_activity($this->session->userdata('id_user'), 'Edit Order', 'Order berhasil diedit', $data_order);

      set_pesan('Data Berhasil Diupdate', true);
      redirect('data-order');
    }
  }

  public function edit_status_order_invoice()
  {
    $id_po_gs = $this->input->post('id_po_gs');
    $data    = $this->input->post(null, true);
    $data_order  = [
      'id_po_gs'                    => $id_po_gs,
      'status_order_invoice'         => $data['status_order_invoice'],
    ];

    if ($this->M_order->update_outstanding($data_order)) {
      set_pesan('Terjadi Error...', false);
      redirect('data-order');
    } else {
      log_activity($this->session->userdata('id_user'), 'Edit Status Order', 'Status Order berhasil diedit', $data_order);

      set_pesan('Data Status Order Di Invoice Berhasil Diupdate', true);
      redirect('data-order');
    }
  }

  public function hapus($id_po_gs)
  {
    $get_po = $this->M_order->get_outstanding_by_id($id_po_gs);
    $no_po = $get_po['no_po'];
    $cek_invoice = $this->db->get_where('detail_gs', ['no_po' => $no_po])->num_rows();
    if ($cek_invoice === 0) {
      $detail = $this->M_order->get_detail_outstanding($id_po_gs)->result_array();

      foreach ($detail as $item) {
        if ($item['type_po'] == 'Satuan') {
          $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $item['id_barang'])->get()->result_array();
          foreach ($get_all_stok as $s) {

            $stok_barang = $this->db->select('*')->from('stok_barang')->where('id_stok_barang', $s['id_stok_barang'])->where('id_barang', $s['id_barang'])->where('harga_beli', $s['harga_beli'])->get()->row_array();

            if ($stok_barang) {
              $data_stok  = [
                'id_stok_barang' => $stok_barang['id_stok_barang'],
                'id_barang'      => $stok_barang['id_barang'],
                'stok'          => $stok_barang['stok'] + $item['jumlah_barang'],
                'harga_beli'    => $stok_barang['harga_beli'],
              ];
              $this->M_barang->update_stok_barang($data_stok);
            }
          }
        } else {
          $paket       = $this->M_package->get_package_by_id($item['id_package']);
          $barangPackage = explode(',', $paket['item_package']);
          foreach ($barangPackage as $bp) {
            $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $bp)->get()->result_array();

            foreach ($get_all_stok as $s) {
              $stok_barang = $this->db->select('*')->from('stok_barang')->where('id_stok_barang', $s['id_stok_barang'])->where('id_barang', $s['id_barang'])->where('harga_beli', $s['harga_beli'])->get()->row_array();

              if ($stok_barang) {
                $jumlah_barang_update = $stok_barang['stok'] + $item['jumlah_barang'];
                $id_stok_barang = $stok_barang['id_stok_barang'];
                $data_stok  = [
                  'id_stok_barang' => $id_stok_barang,
                  'id_barang'      => $stok_barang['id_barang'],
                  'stok'          => $jumlah_barang_update,
                  'harga_beli'    => $stok_barang['harga_beli'],
                ];
                $this->M_barang->update_stok_barang($data_stok);
              }
            }
          }
        }

        $this->M_order->delete_detail_outstanding($item['id_detail_po_gs']);
      }

      log_activity($this->session->userdata('id_user'), 'Hapus Order', 'Order berhasil dihapus', $get_po);

      $this->M_order->delete_outstanding($id_po_gs);
      set_pesan('Data Berhasil Dihapus', true);
    } else {
      set_pesan('Terjadi Error...', false);
    }
    redirect('data-order');
  }

  public function detail($id_po_gs)
  {
    if (is_admin()) {
      $this->db->where('id_po_gs', $id_po_gs);
      $this->db->update('po_gs', ['status' => 1]);
    }
    $data['title']    = 'Order';
    $data['title2']    = 'Detail Order';
    $data['id_po_gs']   = $id_po_gs;
    // $data['barang']    = $this->M_barang->get_barang_new()->result_array();

    $data['barang']    = $this->M_barang->get_stok_barang3()->result_array();
    $data['paket']    = $this->M_package->get_package();

    // $data['departemen'] = $this->M_departemen->get_data()->result_array();

    $data['o']      = $this->M_order->get_outstanding_by_id($id_po_gs);
    $pch_tgl      = explode('-', date('d-F-Y', strtotime($data['o']['tanggal'])));
    $bulan         = $this->bulan($pch_tgl[1]);
    $data['tanggal']  = date('d', strtotime($data['o']['tanggal'])) . ' ' . $bulan . ' ' . date('Y', strtotime($data['o']['tanggal']));
    $get_po_gs = $this->M_order->get_detail_outstanding($id_po_gs)->result_array();
    $total_po_gs = '0';
    foreach ($get_po_gs as $i) {
      $total_po_gs += $i['total_harga_barang'];
    }

    $data['total_outstanding'] = $total_po_gs;
    $data['o_detail']  = $this->M_order->get_detail_outstanding($id_po_gs)->result_array();

    $data['departemen_selected'] = $data['o']['departemen'];
    $this->load->view('order/detail', $data);
  }

  public function get_by_id_detail()
  {
    $id_detail_po_gs = $this->input->post('id_detail_po_gs');
    $data = $this->db->get_where('detail_po_gs', ['id_detail_po_gs' => $id_detail_po_gs])->row_array();
    echo json_encode($data);
  }

  public function tambah_detail()
  {
    $this->db->trans_start();
    try {

      $data    = $this->input->post(null, true);
      $id_po_gs   = $data['id_po_gs'];

      $id_barang = null;
      $id_package = null;
      if ($data['type_po'] == 'Satuan') {
        $id_barang  = $data['id_barang'];
        $b       = $this->M_barang->get_barang_by_id($id_barang);

        $nama_barang = $b['nama_barang'];
        $kode_barang = $b['kode_barang'];
        $satuan_barang = $b['satuan_barang'];
      } else {
        $id_package  = $data['id_package'];
        $b       = $this->M_package->get_package_by_id($id_package);

        $nama_barang = $b['package_name'];
        $kode_barang = null;
        $satuan_barang = null;
      }

      $data_detail_order  = [
        'id_po_gs'        => $id_po_gs,
        'id_barang'      => $id_barang,
        'id_package'      => $id_package,
        'nama_barang'    => $nama_barang,
        'kode_barang'    => $kode_barang,
        'satuan_barang'    => $satuan_barang,
        'type_po'    => $data['type_po'],
        'jumlah_barang'    => $data['jumlah_barang'],
        'harga_barang'    => $data['harga_barang'],
        'total_harga_barang' => $data['jumlah_barang'] * $data['harga_barang'],
        'departemen'    => $data['departemen'],
        'status_indent'    => $data['status_indent'] ? $data['status_indent'] : 'Normal'
      ];
      $this->M_order->insert_detail_outstanding($data_detail_order);
      log_activity($this->session->userdata('id_user'), 'Tambah Detail Order', 'Detail Order berhasil ditambah', $data_detail_order);

      $last_order_detail = $this->db->select('*')->from('detail_po_gs')->order_by('id_detail_po_gs', 'DESC')->limit(1)->get()->row_array();

      if ($data['type_po'] == 'Satuan') {
        $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $id_barang)->where('status_indent', $data['status_indent'])->order_by('cast(stok as unsigned)', 'ASC')->get()->result_array();

        $jumlah_barang = $data['jumlah_barang'];

        foreach ($get_all_stok as $s) {
          if ($jumlah_barang <= 0) {
            break;
          }
          $id_stok_barang = $s['id_stok_barang'];

          $data_detail_po_gs_stok = [
            'id_detail_po_gs'   => $last_order_detail['id_detail_po_gs'],
            'id_stok_barang'    => $id_stok_barang,
            'jumlah_stok_temp'  => $jumlah_barang
          ];
          $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);

          $jumlah_barang_update = $s['stok'] - $jumlah_barang;

          $data_stok  = [
            'id_stok_barang' => $id_stok_barang,
            'id_barang'      => $s['id_barang'],
            'stok'          => $jumlah_barang_update,
            'harga_beli'    => $s['harga_beli'],
          ];
          $this->M_barang->update_stok_barang($data_stok);
          $jumlah_barang = $jumlah_barang - $jumlah_barang;
        }

        $userAgen = $this->db->get_where('departemen', ['id_departemen' => $data['departemen']])->row_array();

        $data_user_agen = [
          'point' => $userAgen['point'] + $b['poin_barang']
        ];

        $this->db->where('id_departemen', $data['departemen']);
        $this->db->update('departemen', $data_user_agen);
      } else {
        $barangPackage = explode(',', $b['item_package']);
        foreach ($barangPackage as $item) {
          $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $item)->where('status_indent', 'Indent Masuk')->where_not_in('stok', 0)->order_by('cast(stok as unsigned)', 'ASC')->order_by('status_indent', 'DESC')->get()->result_array();
          $jumlah_barang = $data['jumlah_barang'];
          foreach ($get_all_stok as $s) {
            if ($jumlah_barang <= 0) {
              break;
            }
            if ($jumlah_barang > $s['stok']) {
              $jumlah_barang = $jumlah_barang - $s['stok'];
              $id_stok_barang = $s['id_stok_barang'];
              $stok_barang_by_id = $this->db->get_where('stok_barang', ['id_stok_barang' => $id_stok_barang])->row_array();
              $data_stok  = [
                'id_stok_barang'  => $id_stok_barang,
                'id_barang'        => $s['id_barang'],
                'stok'            => 0,
                'harga_beli'      => $s['harga_beli'],
              ];
              $this->M_barang->update_stok_barang($data_stok);

              $data_detail_po_gs_stok = [
                'id_detail_po_gs'   => $last_order_detail['id_detail_po_gs'],
                'id_stok_barang'    => $id_stok_barang,
                'jumlah_stok_temp'  => $stok_barang_by_id['stok']
              ];
              $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);
            } else {
              $id_stok_barang = $s['id_stok_barang'];
              $data_detail_po_gs_stok = [
                'id_detail_po_gs'   => $last_order_detail['id_detail_po_gs'],
                'id_stok_barang'    => $id_stok_barang,
                'jumlah_stok_temp'  => $jumlah_barang
              ];
              $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);

              $sisa_barang_dikirim = $s['stok'] - $jumlah_barang;

              $data_stok  = [
                'id_stok_barang' => $id_stok_barang,
                'id_barang'      => $s['id_barang'],
                'stok'          => $sisa_barang_dikirim,
                'harga_beli'    => $s['harga_beli'],
              ];
              $this->M_barang->update_stok_barang($data_stok);
              $jumlah_barang = $jumlah_barang - $jumlah_barang;
            }
          }
          if ($jumlah_barang > 0) {
            $get_all_stok_normal = $this->db->select('*')->from('stok_barang')->where('id_barang', $item)->where('status_indent', 'Normal')->order_by('cast(stok as unsigned)', 'ASC')->order_by('status_indent', 'DESC')->get()->result_array();
            foreach ($get_all_stok_normal as $s) {
              if ($jumlah_barang <= 0) {
                break;
              }
              $id_stok_barang = $s['id_stok_barang'];
              $data_detail_po_gs_stok = [
                'id_detail_po_gs'   => $last_order_detail['id_detail_po_gs'],
                'id_stok_barang'    => $id_stok_barang,
                'jumlah_stok_temp'  => $jumlah_barang
              ];
              $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);

              $sisa_barang_dikirim = $s['stok'] - $jumlah_barang;

              $data_stok  = [
                'id_stok_barang' => $id_stok_barang,
                'id_barang'      => $s['id_barang'],
                'stok'          => $sisa_barang_dikirim,
                'harga_beli'    => $s['harga_beli'],
              ];
              $this->M_barang->update_stok_barang($data_stok);
              $jumlah_barang = $jumlah_barang - $jumlah_barang;
            }
          }
        }

        $userAgen = $this->db->get_where('departemen', ['id_departemen' => $data['departemen']])->row_array();

        $data_user_agen = [
          'point' => $userAgen['point'] + $b['point_package']
        ];

        $this->db->where('id_departemen', $data['departemen']);
        $this->db->update('departemen', $data_user_agen);
      }

      $this->db->trans_commit();
      set_pesan('Data Berhasil Ditambahkan', true);
    } catch (Exception $e) {
      $this->db->trans_rollback();
      set_pesan($e->getMessage(), false);
    }
    redirect('detail-order/' . $id_po_gs);
  }
  // public function tambah_detail()
  // {
  //   $this->db->trans_start();
  //   try {

  //     $data    = $this->input->post(null, true);
  //     $id_po_gs   = $data['id_po_gs'];

  //     $id_barang = null;
  //     $id_package = null;
  //     if ($data['type_po'] == 'Satuan') {
  //       $id_barang  = $data['id_barang'];
  //       $b       = $this->M_barang->get_barang_by_id($id_barang);

  //       $nama_barang = $b['nama_barang'];
  //       $kode_barang = $b['kode_barang'];
  //       $satuan_barang = $b['satuan_barang'];
  //     } else {
  //       $id_package  = $data['id_package'];
  //       $b       = $this->M_package->get_package_by_id($id_package);

  //       $nama_barang = $b['package_name'];
  //       $kode_barang = null;
  //       $satuan_barang = null;
  //     }

  //     $data_detail_order  = [
  //       'id_po_gs'        => $id_po_gs,
  //       'id_barang'      => $id_barang,
  //       'id_package'      => $id_package,
  //       'nama_barang'    => $nama_barang,
  //       'kode_barang'    => $kode_barang,
  //       'satuan_barang'    => $satuan_barang,
  //       'type_po'    => $data['type_po'],
  //       'jumlah_barang'    => $data['jumlah_barang'],
  //       'harga_barang'    => $data['harga_barang'],
  //       'total_harga_barang' => $data['jumlah_barang'] * $data['harga_barang'],
  //       'departemen'    => $data['departemen'],
  //       'status_indent'    => $data['status_indent'] ? $data['status_indent'] : 'Normal'
  //     ];
  //     $this->M_order->insert_detail_outstanding($data_detail_order);

  //     $last_order_detail = $this->db->select('*')->from('detail_po_gs')->order_by('id_detail_po_gs', 'DESC')->limit(1)->get()->row_array();

  //     if ($data['type_po'] == 'Satuan') {
  //       $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $id_barang)->where('status_indent', $data['status_indent'])->where_not_in('stok', 0)->order_by('cast(stok as unsigned)', 'ASC')->get()->result_array();

  //       $jumlah_barang = $data['jumlah_barang'];

  //       foreach ($get_all_stok as $s) {
  //         if ($jumlah_barang <= 0) {
  //           break;
  //         }
  //         if ($jumlah_barang > $s['stok']) {
  //           $jumlah_barang = $jumlah_barang - $s['stok'];
  //           $id_stok_barang = $s['id_stok_barang'];
  //           $stok_barang_by_id = $this->db->get_where('stok_barang', ['id_stok_barang' => $id_stok_barang])->row_array();

  //           $data_stok  = [
  //             'id_stok_barang'  => $id_stok_barang,
  //             'id_barang'        => $s['id_barang'],
  //             'stok'            => 0,
  //             'harga_beli'      => $s['harga_beli'],
  //           ];
  //           $this->M_barang->update_stok_barang($data_stok);

  //           $data_detail_po_gs_stok = [
  //             'id_detail_po_gs'   => $last_order_detail['id_detail_po_gs'],
  //             'id_stok_barang'    => $id_stok_barang,
  //             'jumlah_stok_temp'  => $stok_barang_by_id['stok']
  //           ];
  //           $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);
  //         } else {
  //           $id_stok_barang = $s['id_stok_barang'];

  //           $data_detail_po_gs_stok = [
  //             'id_detail_po_gs'   => $last_order_detail['id_detail_po_gs'],
  //             'id_stok_barang'    => $id_stok_barang,
  //             'jumlah_stok_temp'  => $jumlah_barang
  //           ];
  //           $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);

  //           $jumlah_barang_update = $s['stok'] - $jumlah_barang;

  //           $data_stok  = [
  //             'id_stok_barang' => $id_stok_barang,
  //             'id_barang'      => $s['id_barang'],
  //             'stok'          => $jumlah_barang_update,
  //             'harga_beli'    => $s['harga_beli'],
  //           ];
  //           $this->M_barang->update_stok_barang($data_stok);
  //           $jumlah_barang = $jumlah_barang - $jumlah_barang;
  //         }
  //       }

  //       $userAgen = $this->db->get_where('departemen', ['id_departemen' => $data['departemen']])->row_array();

  //       $data_user_agen = [
  //         'point' => $userAgen['point'] + $b['poin_barang']
  //       ];

  //       $this->db->where('id_departemen', $data['departemen']);
  //       $this->db->update('departemen', $data_user_agen);
  //     } else {
  //       $barangPackage = explode(',', $b['item_package']);
  //       foreach ($barangPackage as $item) {
  //         // $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $item)->where('status_indent', 'Normal')->where_not_in('stok', 0)->order_by('cast(stok as unsigned)', 'ASC')->get()->result_array();
  //         $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $item)->where_not_in('stok', 0)->order_by('cast(stok as unsigned)', 'ASC')->order_by('status_indent', 'DESC')->get()->result_array();
  //         $jumlah_barang = $data['jumlah_barang'];
  //         foreach ($get_all_stok as $s) {
  //           if ($jumlah_barang <= 0) {
  //             break;
  //           }
  //           if ($jumlah_barang > $s['stok']) {
  //             $jumlah_barang = $jumlah_barang - $s['stok'];
  //             $id_stok_barang = $s['id_stok_barang'];
  //             $stok_barang_by_id = $this->db->get_where('stok_barang', ['id_stok_barang' => $id_stok_barang])->row_array();
  //             $data_stok  = [
  //               'id_stok_barang'  => $id_stok_barang,
  //               'id_barang'        => $s['id_barang'],
  //               'stok'            => 0,
  //               'harga_beli'      => $s['harga_beli'],
  //             ];
  //             $this->M_barang->update_stok_barang($data_stok);

  //             $data_detail_po_gs_stok = [
  //               'id_detail_po_gs'   => $last_order_detail['id_detail_po_gs'],
  //               'id_stok_barang'    => $id_stok_barang,
  //               'jumlah_stok_temp'  => $stok_barang_by_id['stok']
  //             ];
  //             $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);
  //           } else {
  //             $id_stok_barang = $s['id_stok_barang'];
  //             $data_detail_po_gs_stok = [
  //               'id_detail_po_gs'   => $last_order_detail['id_detail_po_gs'],
  //               'id_stok_barang'    => $id_stok_barang,
  //               'jumlah_stok_temp'  => $jumlah_barang
  //             ];
  //             $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);

  //             $sisa_barang_dikirim = $s['stok'] - $jumlah_barang;

  //             $data_stok  = [
  //               'id_stok_barang' => $id_stok_barang,
  //               'id_barang'      => $s['id_barang'],
  //               'stok'          => $sisa_barang_dikirim,
  //               'harga_beli'    => $s['harga_beli'],
  //             ];
  //             $this->M_barang->update_stok_barang($data_stok);
  //             $jumlah_barang = $jumlah_barang - $jumlah_barang;
  //           }
  //         }
  //       }

  //       $userAgen = $this->db->get_where('departemen', ['id_departemen' => $data['departemen']])->row_array();

  //       $data_user_agen = [
  //         'point' => $userAgen['point'] + $b['point_package']
  //       ];

  //       $this->db->where('id_departemen', $data['departemen']);
  //       $this->db->update('departemen', $data_user_agen);
  //     }

  //     $this->db->trans_commit();
  //     set_pesan('Data Berhasil Ditambahkan', true);
  //   } catch (Exception $e) {
  //     $this->db->trans_rollback();
  //     set_pesan($e->getMessage(), false);
  //   }
  //   redirect('detail-order/' . $id_po_gs);
  // }

  public function edit_detail()
  {
    try {
      $data    = $this->input->post(null, true);
      $id_po_gs   = $data['id_po_gs'];
      $id_detail_po_gs   = $data['id_detail_po_gs'];

      $detail_po_gs = $this->M_order->get_detail_outstanding_by_id($id_detail_po_gs);

      if ($detail_po_gs['type_po'] == 'Satuan') {
        $get_all_stok = $this->db->select('*')->from('detail_po_gs_stok')->where('id_detail_po_gs', $detail_po_gs['id_detail_po_gs'])->get()->result_array();

        foreach ($get_all_stok as $gas) {

          $stok_barang = $this->db->select('*')->from('stok_barang')->where('id_stok_barang', $gas['id_stok_barang'])->get()->row_array();

          if ($stok_barang) {
            $data_stok  = [
              'id_stok_barang' => $stok_barang['id_stok_barang'],
              'id_barang'      => $stok_barang['id_barang'],
              'stok'          => $stok_barang['stok'] + $gas['jumlah_stok_temp'],
              'harga_beli'    => $stok_barang['harga_beli'],
            ];
            $this->M_barang->update_stok_barang($data_stok);
          }
        }

        $userAgen = $this->db->get_where('departemen', ['id_departemen' => $detail_po_gs['departemen']])->row_array();
        $b = $this->db->get_where('barang', ['id_barang' => $detail_po_gs['id_barang']])->row_array();

        $data_user_agen = [
          'point' => $userAgen['point'] - $b['poin_barang']
        ];

        $this->db->where('id_departemen', $detail_po_gs['departemen']);
        $this->db->update('departemen', $data_user_agen);
      } else {
        $paket       = $this->M_package->get_package_by_id($detail_po_gs['id_package']);
        $get_all_stok = $this->db->select('*')->from('detail_po_gs_stok')->where('id_detail_po_gs', $detail_po_gs['id_detail_po_gs'])->get()->result_array();

        foreach ($get_all_stok as $gas) {
          $stok_barang = $this->db->select('*')->from('stok_barang')->where('id_stok_barang', $gas['id_stok_barang'])->get()->row_array();

          if ($stok_barang) {
            $jumlah_barang_update = $stok_barang['stok'] + $gas['jumlah_stok_temp'];
            $id_stok_barang = $stok_barang['id_stok_barang'];
            $data_stok  = [
              'id_stok_barang' => $id_stok_barang,
              'id_barang'      => $stok_barang['id_barang'],
              'stok'          => $jumlah_barang_update,
              'harga_beli'    => $stok_barang['harga_beli'],
            ];
            $this->M_barang->update_stok_barang($data_stok);
          }
        }

        $userAgen = $this->db->get_where('departemen', ['id_departemen' => $detail_po_gs['departemen']])->row_array();

        $data_user_agen = [
          'point' => $userAgen['point'] - $paket['point_package']
        ];

        $this->db->where('id_departemen', $detail_po_gs['departemen']);
        $this->db->update('departemen', $data_user_agen);
      }

      $id_barang = null;
      $id_package = null;
      if ($data['type_po'] == 'Satuan') {
        $id_barang  = !empty($data['id_barang']) ? $data['id_barang'] : $data['id_barang_asli'];
        $b       = $this->M_barang->get_barang_by_id($id_barang);
        $id_barang = $b['id_barang'];
        $nama_barang = $b['nama_barang'];
        $kode_barang = $b['kode_barang'];
        $satuan_barang = $b['satuan_barang'];
      } else {
        $id_package  = $data['id_package'];
        $b       = $this->M_package->get_package_by_id($id_package);
        $nama_barang = $b['package_name'];
        $kode_barang = null;
        $satuan_barang = null;
      }

      $data_user  = [
        'id_detail_po_gs'   => $id_detail_po_gs,
        'id_po_gs'        => $id_po_gs,
        'id_barang'      => $id_barang,
        'id_package'      => $id_package,
        'nama_barang'    => $nama_barang,
        'kode_barang'    => $kode_barang,
        'satuan_barang'    => $satuan_barang,
        'type_po'    => $data['type_po'],
        'jumlah_barang'    => $data['jumlah_barang'],
        'harga_barang'    => $data['harga_barang'],
        'total_harga_barang' => $data['jumlah_barang'] * $data['harga_barang'],
        'departemen'    => $data['departemen'],
        'status_indent'    => $data['status_indent'] ? $data['status_indent'] : 'Normal'
      ];
      $this->M_order->update_detail_outstanding($data_user);

      if ($data['type_po'] == 'Satuan') {
        $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $id_barang)->order_by('cast(stok as unsigned)', 'ASC')->get()->result_array();

        $jumlah_barang = $data['jumlah_barang'];

        foreach ($get_all_stok as $s) {
          if ($jumlah_barang <= 0) {
            break;
          }
          $id_stok_barang = $s['id_stok_barang'];

          $data_detail_po_gs_stok = [
            'id_detail_po_gs'   => $id_detail_po_gs,
            'id_stok_barang'    => $id_stok_barang,
            'jumlah_stok_temp'  => $jumlah_barang
          ];
          $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);

          $jumlah_barang_update = $s['stok'] - $jumlah_barang;

          $data_stok  = [
            'id_stok_barang' => $id_stok_barang,
            'id_barang'      => $s['id_barang'],
            'stok'          => $jumlah_barang_update,
            'harga_beli'    => $s['harga_beli'],
          ];
          $this->M_barang->update_stok_barang($data_stok);
          $jumlah_barang = $jumlah_barang - $jumlah_barang;
        }

        $userAgen = $this->db->get_where('departemen', ['id_departemen' => $data['departemen']])->row_array();

        $data_user_agen = [
          'point' => $userAgen['point'] + $b['poin_barang']
        ];

        $this->db->where('id_departemen', $data['departemen']);
        $this->db->update('departemen', $data_user_agen);
      } else {
        $barangPackage = explode(',', $b['item_package']);
        foreach ($barangPackage as $item) {
          $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $item)->order_by('cast(stok as unsigned)', 'ASC')->get()->result_array();
          $jumlah_barang = $data['jumlah_barang'];
          foreach ($get_all_stok as $s) {
            if ($jumlah_barang <= 0) {
              break;
            }
            $id_stok_barang = $s['id_stok_barang'];
            $data_detail_po_gs_stok = [
              'id_detail_po_gs'   => $id_detail_po_gs,
              'id_stok_barang'    => $id_stok_barang,
              'jumlah_stok_temp'  => $jumlah_barang
            ];
            $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);

            $sisa_barang_dikirim = $s['stok'] - $jumlah_barang;
            $data_stok  = [
              'id_stok_barang' => $id_stok_barang,
              'id_barang'      => $s['id_barang'],
              'stok'          => $sisa_barang_dikirim,
              'harga_beli'    => $s['harga_beli'],
            ];
            $this->M_barang->update_stok_barang($data_stok);
            $jumlah_barang = $jumlah_barang - $jumlah_barang;
          }
        }

        $userAgen = $this->db->get_where('departemen', ['id_departemen' => $data['departemen']])->row_array();

        $data_user_agen = [
          'point' => $userAgen['point'] + $b['point_package']
        ];

        $this->db->where('id_departemen', $data['departemen']);
        $this->db->update('departemen', $data_user_agen);
      }
      log_activity($this->session->userdata('id_user'), 'edit Detail Order', 'Detail Order berhasil diedit ', $data);

      $this->db->trans_commit();
      set_pesan('Data Berhasil Diupdate', true);
    } catch (Exception $e) {
      $this->db->trans_rollback();
      set_pesan($e->getMessage(), false);
    }
    redirect('detail-order/' . $id_po_gs);
  }
  // public function edit_detail()
  // {
  //   try {
  //     $data    = $this->input->post(null, true);
  //     $id_po_gs   = $data['id_po_gs'];
  //     $id_detail_po_gs   = $data['id_detail_po_gs'];

  //     $detail_po_gs = $this->M_order->get_detail_outstanding_by_id($id_detail_po_gs);

  //     if ($detail_po_gs['type_po'] == 'Satuan') {
  //       $get_all_stok = $this->db->select('*')->from('detail_po_gs_stok')->where('id_detail_po_gs', $detail_po_gs['id_detail_po_gs'])->get()->result_array();

  //       foreach ($get_all_stok as $gas) {

  //         $stok_barang = $this->db->select('*')->from('stok_barang')->where('id_stok_barang', $gas['id_stok_barang'])->get()->row_array();

  //         if ($stok_barang) {
  //           $data_stok  = [
  //             'id_stok_barang' => $stok_barang['id_stok_barang'],
  //             'id_barang'      => $stok_barang['id_barang'],
  //             'stok'          => $stok_barang['stok'] + $gas['jumlah_stok_temp'],
  //             'harga_beli'    => $stok_barang['harga_beli'],
  //           ];
  //           $this->M_barang->update_stok_barang($data_stok);
  //         }
  //       }

  //       $userAgen = $this->db->get_where('departemen', ['id_departemen' => $detail_po_gs['departemen']])->row_array();
  //       $b = $this->db->get_where('barang', ['id_barang' => $detail_po_gs['id_barang']])->row_array();

  //       $data_user_agen = [
  //         'point' => $userAgen['point'] - $b['poin_barang']
  //       ];

  //       $this->db->where('id_departemen', $detail_po_gs['departemen']);
  //       $this->db->update('departemen', $data_user_agen);
  //     } else {
  //       $paket       = $this->M_package->get_package_by_id($detail_po_gs['id_package']);
  //       $get_all_stok = $this->db->select('*')->from('detail_po_gs_stok')->where('id_detail_po_gs', $detail_po_gs['id_detail_po_gs'])->get()->result_array();

  //       foreach ($get_all_stok as $gas) {
  //         $stok_barang = $this->db->select('*')->from('stok_barang')->where('id_stok_barang', $gas['id_stok_barang'])->get()->row_array();

  //         if ($stok_barang) {
  //           $jumlah_barang_update = $stok_barang['stok'] + $gas['jumlah_stok_temp'];
  //           $id_stok_barang = $stok_barang['id_stok_barang'];
  //           $data_stok  = [
  //             'id_stok_barang' => $id_stok_barang,
  //             'id_barang'      => $stok_barang['id_barang'],
  //             'stok'          => $jumlah_barang_update,
  //             'harga_beli'    => $stok_barang['harga_beli'],
  //           ];
  //           $this->M_barang->update_stok_barang($data_stok);
  //         }
  //       }

  //       $userAgen = $this->db->get_where('departemen', ['id_departemen' => $detail_po_gs['departemen']])->row_array();

  //       $data_user_agen = [
  //         'point' => $userAgen['point'] - $paket['point_package']
  //       ];

  //       $this->db->where('id_departemen', $detail_po_gs['departemen']);
  //       $this->db->update('departemen', $data_user_agen);
  //     }

  //     $id_barang = null;
  //     $id_package = null;
  //     if ($data['type_po'] == 'Satuan') {
  //       $id_barang  = !empty($data['id_barang']) ? $data['id_barang'] : $data['id_barang_asli'];
  //       $b       = $this->M_barang->get_barang_by_id($id_barang);
  //       $id_barang = $b['id_barang'];
  //       $nama_barang = $b['nama_barang'];
  //       $kode_barang = $b['kode_barang'];
  //       $satuan_barang = $b['satuan_barang'];
  //     } else {
  //       $id_package  = $data['id_package'];
  //       $b       = $this->M_package->get_package_by_id($id_package);
  //       $nama_barang = $b['package_name'];
  //       $kode_barang = null;
  //       $satuan_barang = null;
  //     }

  //     $data_user  = [
  //       'id_detail_po_gs'   => $id_detail_po_gs,
  //       'id_po_gs'        => $id_po_gs,
  //       'id_barang'      => $id_barang,
  //       'id_package'      => $id_package,
  //       'nama_barang'    => $nama_barang,
  //       'kode_barang'    => $kode_barang,
  //       'satuan_barang'    => $satuan_barang,
  //       'type_po'    => $data['type_po'],
  //       'jumlah_barang'    => $data['jumlah_barang'],
  //       'harga_barang'    => $data['harga_barang'],
  //       'total_harga_barang' => $data['jumlah_barang'] * $data['harga_barang'],
  //       'departemen'    => $data['departemen'],
  //       'status_indent'    => $data['status_indent'] ? $data['status_indent'] : 'Normal'
  //     ];
  //     $this->M_order->update_detail_outstanding($data_user);

  //     if ($data['type_po'] == 'Satuan') {
  //       $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $id_barang)->where_not_in('stok', 0)->order_by('cast(stok as unsigned)', 'ASC')->get()->result_array();

  //       $jumlah_barang = $data['jumlah_barang'];

  //       foreach ($get_all_stok as $s) {
  //         if ($jumlah_barang <= 0) {
  //           break;
  //         }
  //         if ($jumlah_barang > $s['stok']) {
  //           $jumlah_barang = $jumlah_barang - $s['stok'];
  //           $id_stok_barang = $s['id_stok_barang'];
  //           $stok_barang_by_id = $this->db->get_where('stok_barang', ['id_stok_barang' => $id_stok_barang])->row_array();
  //           $data_stok  = [
  //             'id_stok_barang'  => $id_stok_barang,
  //             'id_barang'        => $s['id_barang'],
  //             'stok'            => 0,
  //             'harga_beli'      => $s['harga_beli'],
  //           ];
  //           $this->M_barang->update_stok_barang($data_stok);

  //           $data_detail_po_gs_stok = [
  //             'id_detail_po_gs'   => $id_detail_po_gs,
  //             'id_stok_barang'    => $id_stok_barang,
  //             'jumlah_stok_temp'  => $stok_barang_by_id['stok']
  //           ];
  //           $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);
  //         } else {
  //           $id_stok_barang = $s['id_stok_barang'];

  //           $data_detail_po_gs_stok = [
  //             'id_detail_po_gs'   => $id_detail_po_gs,
  //             'id_stok_barang'    => $id_stok_barang,
  //             'jumlah_stok_temp'  => $jumlah_barang
  //           ];
  //           $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);

  //           $jumlah_barang_update = $s['stok'] - $jumlah_barang;

  //           $data_stok  = [
  //             'id_stok_barang' => $id_stok_barang,
  //             'id_barang'      => $s['id_barang'],
  //             'stok'          => $jumlah_barang_update,
  //             'harga_beli'    => $s['harga_beli'],
  //           ];
  //           $this->M_barang->update_stok_barang($data_stok);
  //           $jumlah_barang = $jumlah_barang - $jumlah_barang;
  //         }
  //       }

  //       $userAgen = $this->db->get_where('departemen', ['id_departemen' => $data['departemen']])->row_array();

  //       $data_user_agen = [
  //         'point' => $userAgen['point'] + $b['poin_barang']
  //       ];

  //       $this->db->where('id_departemen', $data['departemen']);
  //       $this->db->update('departemen', $data_user_agen);
  //     } else {
  //       $barangPackage = explode(',', $b['item_package']);
  //       foreach ($barangPackage as $item) {
  //         $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $item)->where_not_in('stok', 0)->order_by('cast(stok as unsigned)', 'ASC')->get()->result_array();
  //         $jumlah_barang = $data['jumlah_barang'];
  //         foreach ($get_all_stok as $s) {
  //           if ($jumlah_barang <= 0) {
  //             break;
  //           }
  //           if ($jumlah_barang > $s['stok']) {
  //             $id_stok_barang = $s['id_stok_barang'];
  //             $stok_barang_by_id = $this->db->get_where('stok_barang', ['id_stok_barang' => $id_stok_barang])->row_array();
  //             $jumlah_barang = $jumlah_barang - $s['stok'];
  //             $data_stok  = [
  //               'id_stok_barang'  => $id_stok_barang,
  //               'id_barang'        => $s['id_barang'],
  //               'stok'            => 0,
  //               'harga_beli'      => $s['harga_beli'],
  //             ];
  //             $this->M_barang->update_stok_barang($data_stok);

  //             $data_detail_po_gs_stok = [
  //               'id_detail_po_gs'   => $id_detail_po_gs,
  //               'id_stok_barang'    => $id_stok_barang,
  //               'jumlah_stok_temp'  => $stok_barang_by_id['stok']
  //             ];
  //             $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);
  //           } else {
  //             $id_stok_barang = $s['id_stok_barang'];
  //             $data_detail_po_gs_stok = [
  //               'id_detail_po_gs'   => $id_detail_po_gs,
  //               'id_stok_barang'    => $id_stok_barang,
  //               'jumlah_stok_temp'  => $jumlah_barang
  //             ];
  //             $this->db->insert('detail_po_gs_stok', $data_detail_po_gs_stok);

  //             $sisa_barang_dikirim = $s['stok'] - $jumlah_barang;
  //             $data_stok  = [
  //               'id_stok_barang' => $id_stok_barang,
  //               'id_barang'      => $s['id_barang'],
  //               'stok'          => $sisa_barang_dikirim,
  //               'harga_beli'    => $s['harga_beli'],
  //             ];
  //             $this->M_barang->update_stok_barang($data_stok);
  //             $jumlah_barang = $jumlah_barang - $jumlah_barang;
  //           }
  //         }
  //       }

  //       $userAgen = $this->db->get_where('departemen', ['id_departemen' => $data['departemen']])->row_array();

  //       $data_user_agen = [
  //         'point' => $userAgen['point'] + $b['point_package']
  //       ];

  //       $this->db->where('id_departemen', $data['departemen']);
  //       $this->db->update('departemen', $data_user_agen);
  //     }

  //     $this->db->trans_commit();
  //     set_pesan('Data Berhasil Diupdate', true);
  //   } catch (Exception $e) {
  //     $this->db->trans_rollback();
  //     set_pesan($e->getMessage(), false);
  //   }
  //   redirect('detail-order/' . $id_po_gs);
  // }

  public function hapus_detail($id_detail_po_gs, $id_po_gs)
  {
    $detail_po_gs = $this->M_order->get_detail_outstanding_by_id($id_detail_po_gs);

    if ($detail_po_gs['type_po'] == 'Satuan') {
      $get_all_stok = $this->db->select('*')->from('detail_po_gs_stok')->where('id_detail_po_gs', $detail_po_gs['id_detail_po_gs'])->get()->result_array();

      foreach ($get_all_stok as $gas) {

        $stok_barang = $this->db->select('*')->from('stok_barang')->where('id_stok_barang', $gas['id_stok_barang'])->get()->row_array();

        if ($stok_barang) {
          $data_stok  = [
            'id_stok_barang' => $stok_barang['id_stok_barang'],
            'id_barang'      => $stok_barang['id_barang'],
            'stok'          => $stok_barang['stok'] + $gas['jumlah_stok_temp'],
            'harga_beli'    => $stok_barang['harga_beli'],
          ];
          $this->M_barang->update_stok_barang($data_stok);
        }
      }

      $userAgen = $this->db->get_where('departemen', ['id_departemen' => $detail_po_gs['departemen']])->row_array();
      $b = $this->db->get_where('barang', ['id_barang' => $detail_po_gs['id_barang']])->row_array();

      $data_user_agen = [
        'point' => $userAgen['point'] - $b['poin_barang']
      ];

      $this->db->where('id_departemen', $detail_po_gs['departemen']);
      $this->db->update('departemen', $data_user_agen);
    } else {
      $paket       = $this->M_package->get_package_by_id($detail_po_gs['id_package']);
      $get_all_stok = $this->db->select('*')->from('detail_po_gs_stok')->where('id_detail_po_gs', $detail_po_gs['id_detail_po_gs'])->get()->result_array();


      foreach ($get_all_stok as $gas) {
        $stok_barang = $this->db->select('*')->from('stok_barang')->where('id_stok_barang', $gas['id_stok_barang'])->get()->row_array();

        if ($stok_barang) {
          $jumlah_barang_update = $stok_barang['stok'] + $gas['jumlah_stok_temp'];
          $id_stok_barang = $stok_barang['id_stok_barang'];
          $data_stok  = [
            'id_stok_barang' => $id_stok_barang,
            'id_barang'      => $stok_barang['id_barang'],
            'stok'          => $jumlah_barang_update,
            'harga_beli'    => $stok_barang['harga_beli'],
          ];
          $this->M_barang->update_stok_barang($data_stok);
        }
      }

      $userAgen = $this->db->get_where('departemen', ['id_departemen' => $detail_po_gs['departemen']])->row_array();

      $data_user_agen = [
        'point' => $userAgen['point'] - $paket['point_package']
      ];

      $this->db->where('id_departemen', $detail_po_gs['departemen']);
      $this->db->update('departemen', $data_user_agen);
    }

    $this->M_order->delete_detail_outstanding($id_detail_po_gs);
    $this->db->delete('detail_po_gs_stok', ['id_detail_po_gs' => $id_detail_po_gs]);
    log_activity($this->session->userdata('id_user'), 'Hapus Detail Order', 'Detail Order berhasil dihapus ', $detail_po_gs);

    set_pesan('Data Berhasil Dihapus', true);
    redirect('detail-order/' . $id_po_gs);
  }

  // public function hapus_detail($id_detail_po_gs, $id_po_gs)
  // {
  //   $detail_po_gs = $this->M_order->get_detail_outstanding_by_id($id_detail_po_gs);

  //   if ($detail_po_gs['type_po'] == 'Satuan') {
  //     $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $detail_po_gs['id_barang'])->where('status_indent', $detail_po_gs['status_indent'])->get()->result_array();

  //     foreach ($get_all_stok as $s) {

  //       $stok_barang = $this->db->select('*')->from('stok_barang')->where('id_stok_barang', $s['id_stok_barang'])->where('id_barang', $s['id_barang'])->where('harga_beli', $s['harga_beli'])->get()->row_array();

  //       if ($stok_barang) {
  //         $data_stok  = [
  //           'id_stok_barang' => $stok_barang['id_stok_barang'],
  //           'id_barang'      => $stok_barang['id_barang'],
  //           'stok'          => $stok_barang['stok'] + $detail_po_gs['jumlah_barang'],
  //           'harga_beli'    => $stok_barang['harga_beli'],
  //         ];
  //         $this->M_barang->update_stok_barang($data_stok);
  //       }
  //     }

  //     $userAgen = $this->db->get_where('departemen', ['id_departemen' => $detail_po_gs['departemen']])->row_array();
  //     $b = $this->db->get_where('barang', ['id_barang' => $detail_po_gs['id_barang']])->row_array();

  //     $data_user_agen = [
  //       'point' => $userAgen['point'] - $b['poin_barang']
  //     ];

  //     $this->db->where('id_departemen', $detail_po_gs['departemen']);
  //     $this->db->update('departemen', $data_user_agen);
  //   } else {
  //     $paket       = $this->M_package->get_package_by_id($detail_po_gs['id_package']);
  //     $barangPackage = explode(',', $paket['item_package']);
  //     foreach($barangPackage as $bp) {
  //       $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $bp)->where('status_indent', 'Normal')->get()->result_array();

  //       foreach ($get_all_stok as $s) {
  //         $stok_barang = $this->db->select('*')->from('stok_barang')->where('id_stok_barang', $s['id_stok_barang'])->where('id_barang', $s['id_barang'])->where('harga_beli', $s['harga_beli'])->get()->row_array();

  //         if ($stok_barang) {
  //           $jumlah_barang_update = $stok_barang['stok'] + $detail_po_gs['jumlah_barang'];
  //           $id_stok_barang = $stok_barang['id_stok_barang'];
  //           $data_stok  = [
  //             'id_stok_barang' => $id_stok_barang,
  //             'id_barang'      => $stok_barang['id_barang'],
  //             'stok'          => $jumlah_barang_update,
  //             'harga_beli'    => $stok_barang['harga_beli'],
  //           ];
  //           $this->M_barang->update_stok_barang($data_stok);
  //         }
  //       }
  //     }

  //     $userAgen = $this->db->get_where('departemen', ['id_departemen' => $detail_po_gs['departemen']])->row_array();

  //     $data_user_agen = [
  //       'point' => $userAgen['point'] - $paket['point_package']
  //     ];

  //     $this->db->where('id_departemen', $detail_po_gs['departemen']);
  //     $this->db->update('departemen', $data_user_agen);
  //   }

  //   $this->M_order->delete_detail_outstanding($id_detail_po_gs);
  //   set_pesan('Data Berhasil Dihapus', true);
  //   redirect('detail-order/' . $id_po_gs);
  // }

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

  public function get_harga()
  {
    $id_barang = $this->input->post('id_barang');
    //var_dump($id_barang);
    //die();
    $data = $this->db->get_where('barang', ['id_barang' => $id_barang])->row();

    // Buat variabel untuk menampung tag-tag option nya
    // Set defaultnya dengan tag option Pilih
    if (empty($data)) {
      $list = "<input type='number' name='harga_barang' value='0' class='form-control' required/>";
    } else {
      $list = "<input type='number' name='harga_barang' value='" . $data->harga_barang . "' class='form-control' required/>";
    }
    // Tambahkan tag option ke variabel $lists


    $a = array('harga' => $list); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
    echo json_encode($a); // konversi varibael $callback menjadi JSON
  }

  private function bulan_romawi($bulan)
  {
    $bulan = $bulan;
    switch ($bulan) {
      case 'January':
        $bulan = "1";
        break;
      case 'February':
        $bulan = "2";
        break;
      case 'March':
        $bulan = "3";
        break;
      case 'April':
        $bulan = "4";
        break;
      case 'May':
        $bulan = "5";
        break;
      case 'June':
        $bulan = "6";
        break;
      case 'July':
        $bulan = "7";
        break;
      case 'August':
        $bulan = "8";
        break;
      case 'September':
        $bulan = "9";
        break;
      case 'October':
        $bulan = "10";
        break;
      case 'November':
        $bulan = "11";
        break;
      case 'December':
        $bulan = "12";
        break;
      default:
        $bulan = "Isi variabel tidak di temukan";
        break;
    }

    return $bulan;
  }

  public function get_detail_stok()
  {
    $id_detail_po_gs = $this->input->post('id_detail_po_gs');
    $id_package = $this->input->post('id_package');
    if ($id_detail_po_gs) {
      $detail_stok = $this->M_order->get_detail_stok($id_detail_po_gs, $id_package)->result_array();

      if ($detail_stok) {
        $order['detail_stok'] = $detail_stok;
        echo json_encode($order);
      } else {
        echo json_encode(['error' => 'Data barang tidak ditemukan']);
      }
    } else {
      echo json_encode(['error' => 'ID Barang tidak valid']);
    }
  }
}
