<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Retur extends CI_Controller
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
    $data['title']        = 'Return';
    $data['title2']       = 'Data Return';
    $data['barang']      = $this->M_barang->get_barang()->result_array();
    $this->load->view('retur/index', $data);
  }

  public function server_side()
  {
    $retur = array();
    $limit          = html_escape($this->input->post('length'));
    $start          = html_escape($this->input->post('start'));
    $totalData     = $this->M_retur->get_all()->num_rows();
    $totalFiltered  = $totalData;

    if (!empty($this->input->post('search')['value'])) {
      $search = $this->input->post('search')['value'];
      $retur =  $this->M_retur->get_all($limit, $start, $search)->result_array();
      $totalFiltered = $this->M_retur->count_all($search);
    } else {
      $retur = $this->M_retur->get_all($limit, $start)->result_array();
    }

    $data = array();
    if (!empty($retur)) {
      foreach ($retur as $key => $row) {

        $link = "'" . base_url('hapus-return/' . $row['id_retur']) . "'";
        $mid = "document.location.href=$link";
        $a = 'data-confirm-yes="' . $mid . ';"';

        $delete = "<button class='btn btn-danger' data-confirm='Apakah Anda yakin akan hapus data ini?' $a onclick='del($(this))'><i class='fa fa-trash'></i></button></center>";

        $action = "<center>
          <button type='button' class='btn btn-success btn-edit' data-id=" . $row['id_retur'] . "><i class='fa fa-edit'></i></button>
          $delete";

        $nestedData['no'] = $key + 1;
        $nestedData['no_invoice'] = $row['no_invoice'];
        $nestedData['tanggal_retur'] = $row['tanggal_retur'];
        $nestedData['nama_barang'] = $row['nama_barang'];
        $nestedData['stock_barang'] = $row['stock_barang'];
        $nestedData['desc'] = $row['desc'];

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

  public function tambah()
  {
    $data        = $this->input->post(null, true);
    $data_retur  = [
      'no_invoice'        => $data['no_invoice'],
      'tanggal_retur'     => $data['tanggal_retur'],
      'id_barang'         => $data['id_barang'],
      'desc'              => $data['desc'],
      'stock_barang'      => $data['stock_barang']
    ];

    if ($this->M_retur->insert($data_retur)) {
      set_pesan('Terjadi Error..', false);
      redirect('return');
    } else {
      log_activity($this->session->userdata('id_user'), 'Tambah Return', 'Return berhasil diTambah ', $data_retur);

      set_pesan('Data Berhasil Ditambahkan', true);
      redirect('return');
    }
  }

  public function edit()
  {
    $data           = $this->input->post(null, true);
    $id_retur          = $data['id_retur'];
    $data_retur  = [
      'id_retur'          => $id_retur,
      'no_invoice'        => $data['no_invoice'],
      'id_barang'         => $data['id_barang'],
      'tanggal_retur'     => $data['tanggal_retur'],
      'stock_barang'      => $data['stock_barang'],
      'desc'              => $data['desc'],
    ];

    if ($this->M_retur->update($data_retur)) {
      set_pesan('Terjadi Error...', false);
      redirect('return');
    } else {
      log_activity($this->session->userdata('id_user'), 'Edit Return', 'Return berhasil diedit ', $data_retur);

      set_pesan('Data Berhasil Diupdate', true);
      redirect('return');
    }
  }

  public function hapus($id_retur)
  {
    $this->db->trans_start();
    $data = $this->db->get_where('retur', ['id_retur' => $id_retur])->row_array();

    log_activity($this->session->userdata('id_user'), 'Hapus Return', 'Return berhasil dihapus ', $data);

    $this->M_retur->delete($id_retur);
    $this->db->trans_complete();
    set_pesan('Data Berhasil Dihapus', true);
    redirect('return');
  }

  public function get_by_id()
  {
    $id_retur = $this->input->post('id_retur');
    $data = $this->db->get_where('retur', ['id_retur' => $id_retur])->row_array();
    echo json_encode($data);
  }
}
