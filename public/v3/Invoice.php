<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('login') != TRUE)
		{
			set_pesan('Silahkan login terlebih dahulu', false);
			redirect('');
		}
		date_default_timezone_set("Asia/Jakarta");
	}

	public function get_detail_po_gs()
  {
    $id_detail_po_gs = $this->input->post('id_detail_po_gs');
    $data = $this->M_order->get_detail_outstanding_by_id($id_detail_po_gs);
    $get_po = $this->M_order->get_outstanding_by_id($data['id_gs']);
    if (empty($data)){
        $list1 = "<input type='number' name='jumlah_po' value='0' class='form-control' required/>";
        $list2 = "<input type='number' name='harga_barang' value='0' class='form-control' required/>";
        $list3 = "<input type='text' name='no_po' value='' class='form-control' required/>";
    } else {
      $list1 = "<input type='number' name='jumlah_po' value='".$data['jumlah_barang']."' class='form-control' readonly/>";
      $list2 = "<input type='number' name='harga_barang' value='".$data['harga_barang']."' class='form-control' required/>";
      $list3 = "<input type='text' name='no_po' value='".$get_po['no_po']."' class='form-control' readonly/>";
    }
    $a = array('jumlah_po'=>$list1, 'harga'=>$list2, 'no_po'=>$list3); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
    echo json_encode($a); // konversi varibael $callback menjadi JSON
	}

	public function index()
	{
		$data['title']				= 'Invoice & Surat Jalan';
		$data['title2']				= 'Data Invoice & Surat Jalan';
        $tahun 			= date('Y');
		$bulan 			= $this->bulan_romawi(date('F'));
        $last_invoice	= 	$this->db->select('*')
            ->from('gs')
            ->order_by('id_gs', 'DESC')
            ->where("DATE_FORMAT(tanggal, '%Y') =", $tahun)
            ->get()->row_array();
        
        if(empty($last_invoice)){
            $data['no_invoice'] = 'INVSJ/'.$bulan.'/'.$tahun.'/1';
        } else {
          $pch_invoice= explode('/', $last_invoice['no_invoice']);
            $no	= intval($pch_invoice[3]) + 1;
            $data['no_invoice'] = 'INVSJ/'.$bulan.'/'.$tahun.'/'.$no;
        }
		$this->load->view('invoice/index', $data);
	}

    public function server_side()
    {
      $gs = array();
          $limit          = html_escape($this->input->post('length'));
          $start          = html_escape($this->input->post('start'));
          $totalData 		= $this->M_order->get_all()->num_rows();
          $totalFiltered  = $totalData;

      if (!empty($this->input->post('search')['value'])) {
        $search = $this->input->post('search')['value'];
        $gs =  $this->M_order->get_all($limit, $start, $search)->result_array();
        $totalFiltered = $this->M_order->count_all($search);
      } else {
        $gs = $this->M_order->get_all($limit, $start)->result_array();
      }

          $data = array();
          if (!empty($gs)) {
              foreach ($gs as $key => $row) {
          $link = "'".base_url('hapus-invoice/'.$row['id_gs'])."'";
          $mid = "document.location.href=$link";
          $a = 'data-confirm-yes="'.$mid.';"';

          if ($row['status_gs'] === 'Belum Selesai') {
            $delete = "<button class='btn btn-danger' data-confirm='Apakah Anda yakin akan hapus data ini?' $a onclick='del($(this))'><i class='fa fa-trash'></i></button></center>";
          } else {
            $delete = "";
          }

                  $action = "<center>
          <a href=".base_url('detail-invoice/'.$row['id_gs'])." class='btn btn-light'><i class='fa fa-list'></i></a>
          <button type='button' class='btn btn-success btn-edit' data-id=".$row['id_gs']."><i class='fa fa-edit'></i></button>
          $delete";

          $nestedData['no'] = $key + 1;
                  $nestedData['no_invoice'] = $row['no_invoice'];
                  $nestedData['tanggal'] = $row['tanggal'];
                  $nestedData['total_harga_barang_sum'] = 'Rp '. number_format($row['total_harga_barang_sum'], 2,',','.');
                  $nestedData['status_gs'] = $row['status_gs'];
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
          $data		    = $this->input->post(null, true);
          $bulan 			= $this->bulan_romawi(date('F', strtotime($data['tanggal'])));
          $tahun 			= date('Y', strtotime($data['tanggal']));
          $pch_no_invoice = explode('/', $data['no_invoice']);
          $no_invoice = 'INVSJ/'.$bulan.'/'.$tahun.'/'.$pch_no_invoice[3];
          $data_user	= [
              'no_invoice'	=> $no_invoice,
              'tanggal'		=> $data['tanggal'],
              'mode'			=> 'merlinstore'
          ];

          if ($this->M_order->insert($data_user)) {
              set_pesan('Terjadi Error..', false);
              redirect('invoice');
          } else {
              set_pesan('Data Berhasil Ditambahkan', true);
              redirect('invoice');
          }
    }

    public function edit()
    {
          $data           = $this->input->post(null, true);
          $id_gs          = $data['id_gs'];
          $bulan 			= $this->bulan_romawi(date('F', strtotime($data['tanggal'])));
          $tahun 			= date('Y', strtotime($data['tanggal']));
          $pch_no_invoice = explode('/', $data['no_invoice']);
          $no_invoice     = 'INVSJ/'.$bulan.'/'.$tahun.'/'.$pch_no_invoice[3];
          $data_user	= [
              'id_gs'	        => $id_gs,
              'no_invoice'	=> $no_invoice,
              'tanggal'		=> $data['tanggal'],
          ];

          if ($this->M_order->update($data_user)) {
              set_pesan('Terjadi Error...', false);
              redirect('invoice');
          } else {
              set_pesan('Data Berhasil Diupdate', true);
              redirect('invoice');
          }
    }

    public function hapus($id_gs)
    {
      $this->M_order->delete($id_gs);
      set_pesan('Data Berhasil Dihapus', true);
      redirect('invoice');
    }

    private function bulan_romawi($bulan)
    {
        $bulan=$bulan;
        switch ($bulan) {
          case 'January':
            $bulan= "1";
            break;
          case 'February':
            $bulan= "2";
            break;
          case 'March':
            $bulan= "3";
            break;
          case 'April':
            $bulan= "4";
            break;
          case 'May':
            $bulan= "5";
            break;
          case 'June':
            $bulan= "6";
            break;
          case 'July':
            $bulan= "7";
            break;
          case 'August':
            $bulan= "8";
            break;
          case 'September':
            $bulan= "9";
            break;
          case 'October':
            $bulan= "10";
            break;
          case 'November':
            $bulan= "11";
            break;
          case 'December':
            $bulan= "12";
            break;
          default:
            $bulan= "Isi variabel tidak di temukan";
            break;
        }

        return $bulan;
    }

    public function get_by_id() 
    {
        $id_gs = $this->input->post('id_gs');
        $data = $this->db->get_where('gs', ['id_gs' => $id_gs])->row_array();
        echo json_encode($data);
    }

    public function detail($id_gs)
    {
      $data['title']		= 'Invoice & Surat Jalan';
      $data['title2']		= 'Detail Invoice & Surat Jalan';
      $data['f']			  = $this->M_order->get_by_id($id_gs);
      $pch_tgl			    = explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));
      $bulan 				    = $this->bulan($pch_tgl[1]);
      $data['id_gs']    = $id_gs;
      $data['tanggal']	= date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));
      $data['f_detail']	= $this->M_order->get_detail($id_gs)->result_array();
      $data['barang']		= $this->db->get_where('detail_po_gs', ['jumlah_barang !=' => 0])->result_array();
      $this->load->view('invoice/detail', $data);
    }

    public function get_by_id_detail() 
    {
        $id_detail_gs = $this->input->post('id_detail_gs');
        $data = $this->db->get_where('detail_gs', ['id_detail_gs' => $id_detail_gs])->row_array();
        echo json_encode($data);
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

    public function tambah_detail()
    {
      $data		= $this->input->post(null, true);
      $id_gs  = $data['id_gs'];
      $id_detail_po_gs	= $data['id_detail_po_gs'];
      $o = $this->M_order->get_detail_outstanding_by_id($id_detail_po_gs);
      $get_po = $this->M_order->get_outstanding_by_id($o['id_po_gs']);

      if($data['jumlah_barang'] < 1 || $data['jumlah_barang'] > $o['jumlah_barang']){
        set_pesan('Error!! Jumlah Barang Yang Diinput Melebihi Yang Di Order.', false);
        redirect('detail-invoice/'.$id_gs);
      }

      $get_stok = $this->db->select_sum('stok')->from('stok_barang')->where('id_barang', $o['id_barang'])->get()->row_array();

      if ($data['jumlah_barang'] > $get_stok['stok']) {
        set_pesan('Error!! Jumlah Barang Melebihi Stok Yang Ada.', false);
        redirect('detail-invoice/'.$id_gs);
      }

      $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $o['id_barang'])->where_not_in('stok', 0)->order_by('cast(stok as unsigned)', 'ASC')->get()->result_array();
      $sisa_barang_dikirim = $data['jumlah_barang'];

      foreach ($get_all_stok as $s) {
        if($sisa_barang_dikirim == 0){
          break;
        }
        if($sisa_barang_dikirim > $s['stok']){
          $sisa_barang_dikirim = $sisa_barang_dikirim - $s['stok'];
          $data_user	= [
            'id_gs'		          => $id_gs,
            'id_detail_po_gs'		=> $id_detail_po_gs,
            'id_barang'			    => $o['id_barang'],
            'nama_barang'		    => $o['nama_barang'],
            'kode_barang'		    => $o['kode_barang'],
            'satuan_barang'		  => $o['satuan_barang'],
            'jumlah_barang'		  => $s['stok'],
            'harga_beli'		    => $s['harga_beli'],
            'harga_barang'		  => $data['harga_barang'],
            'total_harga_barang'=> $s['stok']*$data['harga_barang'],
            'no_po'				      => $data['no_po'],
            'departemen'		    => $o['departemen'],
          ];

          $insert = $this->M_order->insert_detail($data_user);
          $id_stok_barang = $s['id_stok_barang'];

          $data_stok	= [
            'id_stok_barang'  => $id_stok_barang,
            'id_barang'		    => $s['id_barang'],
            'stok'		        => 0,
            'harga_beli'		  => $s['harga_beli'],
          ];
          $this->M_barang->update_stok_barang($data_stok);
        }else{
          $data_user	= [
            'id_gs'		          => $id_gs,
            'id_detail_po_gs'		=> $id_detail_po_gs,
            'id_barang'			    => $o['id_barang'],
            'nama_barang'		    => $o['nama_barang'],
            'kode_barang'		    => $o['kode_barang'],
            'satuan_barang'		  => $o['satuan_barang'],
            'jumlah_barang'		  => $sisa_barang_dikirim,
            'harga_beli'		    => $s['harga_beli'],
            'harga_barang'		  => $data['harga_barang'],
            'total_harga_barang'=> $sisa_barang_dikirim*$data['harga_barang'],
            'no_po'				      => $data['no_po'],
            'departemen'		    => $o['departemen'],
          ];

          $insert = $this->M_order->insert_detail($data_user);
          $sisa_barang_dikirim = $s['stok'] - $sisa_barang_dikirim;
          $id_stok_barang = $s['id_stok_barang'];

          $data_stok	= [
            'id_stok_barang' => $id_stok_barang,
            'id_barang'		  => $s['id_barang'],
            'stok'			    => $sisa_barang_dikirim,
            'harga_beli'		=> $s['harga_beli'],
          ];
          $this->M_barang->update_stok_barang($data_stok);
          $sisa_barang_dikirim = 0;
        }
      }
      $sisa_barang = $o['jumlah_barang'] - $data['jumlah_barang'];

      if($sisa_barang == 0){
        $detail_po = $this->db->get_where('detail_po_gs', ['id_detail_po_gs' => $id_detail_po_gs])->row_array();
        $po = $this->db->get_where('po_gs', ['id_po_gs' => $detail_po['id_po_gs']])->row_array();
        $jml_barang = $this->db->query("SELECT sum(jumlah_barang) as jml_barang FROM detail_gs WHERE id_detail_po_gs=".$id_detail_po_gs."")->row_array();
        $jml_barang = $jml_barang['jml_barang'];

        $data_riwayat = [
          'id_detail_po_gs' => $id_detail_po_gs,
          'no_po' => $po['no_po'],
          'tanggal_po' => $po['tanggal'],
          'id_barang' => $detail_po['id_barang'],
          'nama_barang' => $detail_po['nama_barang'],
          'kode_barang' => $detail_po['kode_barang'],
          'satuan_barang' => $detail_po['satuan_barang'],
          'jumlah_barang' => $jml_barang,
          'harga_barang' => $detail_po['harga_barang'],
          'total_harga_barang' => $detail_po['total_harga_barang'],
          'departemen' => $detail_po['departemen'],
        ];
        $this->db->insert('riwayat_po_gs', $data_riwayat);
        $this->db->delete('detail_po_gs', ['id_detail_po_gs' => $id_detail_po_gs]);
      } else {
        $data_po = [
          'id_detail_po_gs' => $id_detail_po_gs,
          'jumlah_barang' => $sisa_barang,
          'total_harga_barang' => $sisa_barang * $o['harga_barang'],
        ];
        $this->M_order->update_detail_outstanding($data_po);
      }

      if ($insert) {
        set_pesan('Error!! Terjadi error di database.', false);
        redirect('detail-invoice/'.$id_gs);
      } else {
        set_pesan('Data Berhasil Ditambahkan.', true);
        redirect('detail-invoice/'.$id_gs);
      }
    }

    public function tambah_diskon()
    {
      $data		= $this->input->post(null, true);
      $id_gs  = $data['id_gs'];
      
      $data_user	= [
        'id_gs'		          => $id_gs,
        'id_detail_po_gs'		=> 0,
        'id_barang'			    => 0,
        'nama_barang'		    => 'Diskon',
        'kode_barang'		    => 0,
        'satuan_barang'		  => 0,
        'jumlah_barang'		  => 1,
        'harga_beli'		    => 0,
        'harga_barang'		  => 0,
        'total_harga_barang'=> -$data['harga_barang'],
        'no_po'				      => 0,
        'departemen'		    => null,
        'tipe_item'		      => 'Diskon',
      ];

      $insert = $this->M_order->insert_detail($data_user);

      if ($insert) {
        set_pesan('Error!! Terjadi error di database.', false);
        redirect('detail-invoice/'.$id_gs);
      } else {
        set_pesan('Anda Berhasil Menambahkan Diskon.', true);
        redirect('detail-invoice/'.$id_gs);
      }
    }

    // public function edit_detail($id_detail_gs, $id_gs)
    // {
    //   $data		= $this->input->post(null, true);
    //   $id_gs = $data['id_gs'];
    //   $id_detail_gs = $data['id_detail_gs'];
    //   $id_detail_po_gs	= $data['id_detail_po_gs'];
    //   $o = $this->M_order->get_detail_outstanding_by_id($id_detail_po_gs);
    //   $get_po = $this->M_order->get_outstanding_by_id($o['id_po_gs']);
    //   $fd	= $this->M_order->get_detail_by_id($id_detail_gs);

    //   if($data['jumlah_barang'] < 1 || $data['jumlah_barang'] > ($data['jumlah_barang_lama']+$o['jumlah_barang'])){
    //     set_pesan('Error!! Jumlah Barang Yang Diinput Kurang.', false);
    //     redirect('detail-invoice/'.$id_gs);
    //   }

    //   if($data['jumlah_po'] != 0){
    //     $data_outstanding = [
    //       'id_detail_po_gs' => $id_detail_po_gs,
    //       'jumlah_barang' => $o['jumlah_barang'] + $data['jumlah_barang_lama']
    //     ];
    //     $this->M_order->update_detail_outstanding($data_outstanding);
    //     $get_stok_lama = $this->db->select('*')->from('stok_barang')->where('id_barang', $fd['id_barang'])->where('harga_beli', $fd['harga_beli'])->get()->row_array();

    //     $stok_lama = [
    //       'id_stok_barang' => $get_stok_lama['id_stok_barang'],
    //       'stok' => $get_stok_lama['stok'] + $data['jumlah_barang_lama']
    //     ];
    //     $this->M_barang->update_stok_barang($stok_lama);
    //   }else{
    //     $get_riwayat_by_id = $this->db->get_where('riwayat_po_gs', ['id_detail_po_gs' => $fd['id_detail_po_gs']])->row_array();
    //     $get_po = $this->db->get_where('po_gs', ['no_po' => $get_riwayat_by_id['no_po'], 'tanggal' => $get_riwayat_by_id['tanggal_po']])->row_array();

    //     $data_po = [
    //       'id_detail_po_gs' => $id_detail_po_gs,
    //       'id_po_gs' => $get_po['id_po_gs'],
    //       'id_barang' => $get_riwayat_by_id['id_barang'],
    //       'nama_barang' => $get_riwayat_by_id['nama_barang'],
    //       'kode_barang' => $get_riwayat_by_id['kode_barang'],
    //       'satuan_barang' => $get_riwayat_by_id['satuan_barang'],
    //       'jumlah_barang' => $fd['jumlah_barang'],
    //       'harga_barang' => $get_riwayat_by_id['harga_barang'],
    //       'harga_beli' => 0,
    //       'total_harga_barang' => $get_riwayat_by_id['total_harga_barang'],
    //       'departemen' => $get_riwayat_by_id['departemen'],
    //     ];
    //     $this->M_order->insert_detail_outstanding($data_po);
    //     $this->db->delete('riwayat_po_gs', ['id_detail_po_gs' => $get_riwayat_by_id['id_detail_po_gs']]);
    //     $get_stok_lama = $this->db->select('*')->from('stok_barang')->where('id_barang', $fd['id_barang'])->where('harga_beli', $fd['harga_beli'])->get()->row_array();

    //     $stok_lama = [
    //       'id_stok_barang' => $get_stok_lama['id_stok_barang'],
    //       'stok' => $get_stok_lama['stok'] + $data['jumlah_barang_lama']
    //     ];
    //     $this->M_barang->update_stok_barang($stok_lama);
    //   }
    //   $this->M_order->delete_detail($id_detail_gs);
    //   $o = $this->M_order->get_detail_outstanding_by_id($id_detail_po_gs);
    //   $get_po = $this->M_order->get_outstanding_by_id($o['id_po_gs']);
    //   $get_stok = $this->db->select_sum('stok')->from('stok_barang')->where('id_barang', $o['id_barang'])->get()->row_array();

    //   if ($data['jumlah_barang'] > $get_stok['stok']) {
    //     set_pesan('Error!! Jumlah Barang Melebihi Stok Yang Ada.', false);
    //     redirect('detail-invoice/'.$id_gs);
    //   }

    //   $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $o['id_barang'])->where_not_in('stok', 0)->order_by('cast(stok as unsigned)', 'ASC')->get()->result_array();
    //   $sisa_barang_dikirim = $data['jumlah_barang'];

    //   foreach ($get_all_stok as $s) {
    //     if($sisa_barang_dikirim == 0){
    //       break;
    //     }
    //     if($sisa_barang_dikirim > $s['stok']){
    //       $sisa_barang_dikirim = $sisa_barang_dikirim - $s['stok'];
    //       $data_user	= [
    //         'id_gs'		=> $id_gs,
    //         'id_detail_po_gs'		=> $id_detail_po_gs,
    //         'id_barang'			=> $o['id_barang'],
    //         'nama_barang'		=> $o['nama_barang'],
    //         'kode_barang'		=> $o['kode_barang'],
    //         'satuan_barang'		=> $o['satuan_barang'],
    //         'jumlah_barang'		=> $s['stok'],
    //         'harga_beli'		=> $s['harga_beli'],
    //         'harga_barang'		=> $data['harga_barang'],
    //         'total_harga_barang'=> $s['stok']*$data['harga_barang'],
    //         'no_po'				=> $data['no_po'],
    //         'departemen'		=> $o['departemen'],
    //       ];
    //       $insert = $this->M_order->insert_detail($data_user);
    //       $id_stok_barang = $s['id_stok_barang'];

    //       $data_stok	= [
    //         'id_stok_barang' => $id_stok_barang,
    //         'id_barang'		=> $s['id_barang'],
    //         'stok'		=> 0,
    //         'harga_beli'		=> $s['harga_beli'],
    //       ];
    //       $this->M_barang->update_stok_barang($data_stok);
    //     }else{
    //       $data_user	= [
    //         'id_gs'		=> $id_gs,
    //         'id_detail_po_gs'		=> $id_detail_po_gs,
    //         'id_barang'			=> $o['id_barang'],
    //         'nama_barang'		=> $o['nama_barang'],
    //         'kode_barang'		=> $o['kode_barang'],
    //         'satuan_barang'		=> $o['satuan_barang'],
    //         'jumlah_barang'		=> $sisa_barang_dikirim,
    //         'harga_beli'		=> $s['harga_beli'],
    //         'harga_barang'		=> $data['harga_barang'],
    //         'total_harga_barang'=> $sisa_barang_dikirim*$data['harga_barang'],
    //         'no_po'				=> $data['no_po'],
    //         'departemen'		=> $o['departemen'],
    //       ];
    //       $insert = $this->M_order->insert_detail($data_user);
    //       $sisa_barang_dikirim = $s['stok'] - $sisa_barang_dikirim;
    //       $id_stok_barang = $s['id_stok_barang'];

    //       $data_stok	= [
    //         'id_stok_barang' => $id_stok_barang,
    //         'id_barang'		=> $s['id_barang'],
    //         'stok'			=> $sisa_barang_dikirim,
    //         'harga_beli'		=> $s['harga_beli'],
    //       ];
    //       $this->M_barang->update_stok_barang($data_stok);
    //       $sisa_barang_dikirim = 0;
    //     }
    //   }
    //   $sisa_barang = $o['jumlah_barang'] - $data['jumlah_barang'];

    //   if($sisa_barang == 0){
    //     $detail_po = $this->db->get_where('detail_po_gs', ['id_detail_po_gs' => $id_detail_po_gs])->row_array();
    //     $po = $this->db->get_where('po_gs', ['id_po_gs' => $detail_po['id_po_gs']])->row_array();
    //     $jml_barang = $this->db->query("SELECT sum(jumlah_barang) as jml_barang FROM detail_gs WHERE id_detail_po_gs=".$id_detail_po_gs."")->row_array();
    //     $jml_barang = $jml_barang['jml_barang'];

    //     $data_riwayat = [
    //       'id_detail_po_gs' => $id_detail_po_gs,
    //       'no_po' => $po['no_po'],
    //       'tanggal_po' => $po['tanggal'],
    //       'id_barang' => $detail_po['id_barang'],
    //       'nama_barang' => $detail_po['nama_barang'],
    //       'kode_barang' => $detail_po['kode_barang'],
    //       'satuan_barang' => $detail_po['satuan_barang'],
    //       'jumlah_barang' => $jml_barang,
    //       'harga_barang' => $detail_po['harga_barang'],
    //       'total_harga_barang' => $detail_po['total_harga_barang'],
    //       'departemen' => $detail_po['departemen'],
    //     ];
    //     $this->db->insert('riwayat_po_gs', $data_riwayat);
    //     $this->db->delete('detail_po_gs', ['id_detail_po_gs' => $id_detail_po_gs]);
    //   } else {
    //     $data_po = [
    //       'id_detail_po_gs' => $id_detail_po_gs,
    //       'jumlah_barang' => $sisa_barang,
    //       'total_harga_barang' => $sisa_barang * $o['harga_barang'],
    //     ];
    //     $this->M_order->update_detail_outstanding($data_po);
    //   }

    //   if ($update) {
    //     set_pesan('Error!! Terjadi Error Di Database.', false);
    //     redirect('detail-invoice/'.$id_gs);
    //   } else {
    //     set_pesan('Data Berhasil Ditambahkan..', true);
    //     redirect('detail-invoice/'.$id_gs);
    //   }
    // }

    public function edit_detail($id_detail_gs, $id_gs)
    {
      $this->validation_detail();
      if (!$this->form_validation->run()) {
        $data['title']		  = 'Invoice & Surat Jalan';
        $data['title2']		  = 'Detail Invoice & Surat Jalan';
        $data['id_gs']      = $id_gs;
        $data['barang']		  = $this->M_order->get_detail_outstanding()->result_array();
        $data['fd']	        = $this->M_order->get_detail_by_id($id_detail_gs);
        $data['detail_po']  = $this->M_order->get_detail_outstanding_by_id($data['fd']['id_detail_po_gs']);
        $this->load->view('invoice/edit_detail', $data);
      } else {
        $data		= $this->input->post(null, true);
        $id_detail_po_gs	= $data['id_detail_po_gs'];
        $o = $this->M_order->get_detail_outstanding_by_id($id_detail_po_gs);
        $get_po = $this->M_order->get_outstanding_by_id($o['id_po_gs']);
        $fd	= $this->M_order->get_detail_by_id($id_detail_gs);

        if($data['jumlah_barang'] < 1 || $data['jumlah_barang'] > ($data['jumlah_barang_lama']+$o['jumlah_barang'])){
          set_pesan('Error!! Jumlah Barang Yang Diinput Kurang.', false);
          redirect('edit-detail-invoice/'.$id_detail_gs.'/'.$id_gs);
        }
        
        //Balikin Stok
        if($data['jumlah_po'] != 0){
          $data_outstanding = [
            'id_detail_po_gs' => $id_detail_po_gs,
            'jumlah_barang' => $o['jumlah_barang'] + $data['jumlah_barang_lama']
          ];
          //var_dump($data_outstanding);
          
          $this->M_order->update_detail_outstanding($data_outstanding);

          $get_stok_lama = $this->db->select('*')->from('stok_barang')->where('id_barang', $fd['id_barang'])->where('harga_beli', $fd['harga_beli'])->get()->row_array();

          $stok_lama = [
            'id_stok_barang' => $get_stok_lama['id_stok_barang'],
            'stok' => $get_stok_lama['stok'] + $data['jumlah_barang_lama']
          ];

          $this->M_barang->update_stok_barang($stok_lama);
        }else{
          $get_riwayat_by_id = $this->db->get_where('riwayat_po_gs', ['id_detail_po_gs' => $fd['id_detail_po_gs']])->row_array();
          $get_po = $this->db->get_where('po_gs', ['no_po' => $get_riwayat_by_id['no_po'], 'tanggal' => $get_riwayat_by_id['tanggal_po']])->row_array();

          $data_po = [
            'id_detail_po_gs' => $id_detail_po_gs,
            'id_po_gs' => $get_po['id_po_gs'],
            'id_barang' => $get_riwayat_by_id['id_barang'],
            'nama_barang' => $get_riwayat_by_id['nama_barang'],
            'kode_barang' => $get_riwayat_by_id['kode_barang'],
            'satuan_barang' => $get_riwayat_by_id['satuan_barang'],
            'jumlah_barang' => $fd['jumlah_barang'],
            'harga_barang' => $get_riwayat_by_id['harga_barang'],
            'harga_beli' => 0,
            'total_harga_barang' => $get_riwayat_by_id['total_harga_barang'],
            'departemen' => $get_riwayat_by_id['departemen'],
          ];

          $this->M_order->insert_detail_outstanding($data_po);
          $this->db->delete('riwayat_po_gs', ['id_detail_po_gs' => $get_riwayat_by_id['id_detail_po_gs']]);

          $get_stok_lama = $this->db->select('*')->from('stok_barang')->where('id_barang', $fd['id_barang'])->where('harga_beli', $fd['harga_beli'])->get()->row_array();

          $stok_lama = [
            'id_stok_barang' => $get_stok_lama['id_stok_barang'],
            'stok' => $get_stok_lama['stok'] + $data['jumlah_barang_lama']
          ];

          $this->M_barang->update_stok_barang($stok_lama);
        }
        $this->M_order->delete_detail($id_detail_gs);
        //Balikin Stok

        $o = $this->M_order->get_detail_outstanding_by_id($id_detail_po_gs);
        $get_po = $this->M_order->get_outstanding_by_id($o['id_po_gs']);

        $get_stok = $this->db->select_sum('stok')->from('stok_barang')->where('id_barang', $o['id_barang'])->get()->row_array();

        if ($data['jumlah_barang'] > $get_stok['stok']) {
          set_pesan('Error!! Jumlah Barang Yang Diinput Kurang.', false);
          redirect('edit-detail-invoice/'.$id_detail_gs.'/'.$id_gs);
        }

        $get_all_stok = $this->db->select('*')->from('stok_barang')->where('id_barang', $o['id_barang'])->where_not_in('stok', 0)->order_by('cast(stok as unsigned)', 'ASC')->get()->result_array();

        $sisa_barang_dikirim = $data['jumlah_barang'];
        foreach ($get_all_stok as $s) {
          if($sisa_barang_dikirim == 0){
            break;
          }
          if($sisa_barang_dikirim > $s['stok']){
            $sisa_barang_dikirim = $sisa_barang_dikirim - $s['stok'];
            $data_user	= [
              'id_gs'		=> $id_gs,
              'id_detail_po_gs'		=> $id_detail_po_gs,
              'id_barang'			=> $o['id_barang'],
              'nama_barang'		=> $o['nama_barang'],
              'kode_barang'		=> $o['kode_barang'],
              'satuan_barang'		=> $o['satuan_barang'],
              'jumlah_barang'		=> $s['stok'],
              'harga_beli'		=> $s['harga_beli'],
              'harga_barang'		=> $data['harga_barang'],
              'total_harga_barang'=> $s['stok']*$data['harga_barang'],
              'no_po'				=> $data['no_po'],
              'departemen'		=> $o['departemen'],
            ];
            // var_dump($data_user);
            // echo "<br/>";
            // echo "<br/>";
            $insert = $this->M_order->insert_detail($data_user);

            $id_stok_barang = $s['id_stok_barang'];
            $data_stok	= [
              'id_stok_barang' => $id_stok_barang,
              'id_barang'		=> $s['id_barang'],
              'stok'		=> 0,
              'harga_beli'		=> $s['harga_beli'],
            ];
            // var_dump($data_stok);
            // echo "<br/>";
            // echo "<br/>";
            $this->M_barang->update_stok_barang($data_stok);
          }else{
            $data_user	= [
              'id_gs'		=> $id_gs,
              'id_detail_po_gs'		=> $id_detail_po_gs,
              'id_barang'			=> $o['id_barang'],
              'nama_barang'		=> $o['nama_barang'],
              'kode_barang'		=> $o['kode_barang'],
              'satuan_barang'		=> $o['satuan_barang'],
              'jumlah_barang'		=> $sisa_barang_dikirim,
              'harga_beli'		=> $s['harga_beli'],
              'harga_barang'		=> $data['harga_barang'],
              'total_harga_barang'=> $sisa_barang_dikirim*$data['harga_barang'],
              'no_po'				=> $data['no_po'],
              'departemen'		=> $o['departemen'],
            ];
            // var_dump($data_user);
            // echo "<br/>";
            // echo "<br/>";
            $insert = $this->M_order->insert_detail($data_user);

            $sisa_barang_dikirim = $s['stok'] - $sisa_barang_dikirim;

            $id_stok_barang = $s['id_stok_barang'];
            $data_stok	= [
              'id_stok_barang' => $id_stok_barang,
              'id_barang'		=> $s['id_barang'],
              'stok'			=> $sisa_barang_dikirim,
              'harga_beli'		=> $s['harga_beli'],
            ];
            // var_dump($data_stok);
            // echo "<br/>";
            // echo "<br/>";
            $this->M_barang->update_stok_barang($data_stok);

            $sisa_barang_dikirim = 0;

          }
        }

        $sisa_barang = $o['jumlah_barang'] - $data['jumlah_barang'];

        // var_dump($sisa_barang);
        //   	echo "<br/>";
        // 		echo "<br/>";

        if($sisa_barang == 0){
          $detail_po = $this->db->get_where('detail_po_gs', ['id_detail_po_gs' => $id_detail_po_gs])->row_array();
          $po = $this->db->get_where('po_gs', ['id_po_gs' => $detail_po['id_po_gs']])->row_array();
          $jml_barang = $this->db->query("SELECT sum(jumlah_barang) as jml_barang FROM detail_gs WHERE id_detail_po_gs=".$id_detail_po_gs."")->row_array();
          // var_dump($id_detail_po_gs);
          $jml_barang = $jml_barang['jml_barang'];
          //var_dump($jml_barang);
          //die();
          $data_riwayat = [
            'id_detail_po_gs' => $id_detail_po_gs,
            'no_po' => $po['no_po'],
            'tanggal_po' => $po['tanggal'],
            'id_barang' => $detail_po['id_barang'],
            'nama_barang' => $detail_po['nama_barang'],
            'kode_barang' => $detail_po['kode_barang'],
            'satuan_barang' => $detail_po['satuan_barang'],
            'jumlah_barang' => $jml_barang,
            'harga_barang' => $detail_po['harga_barang'],
            'total_harga_barang' => $detail_po['total_harga_barang'],
            'departemen' => $detail_po['departemen'],
          ];

          // var_dump($data_riwayat);
          // echo "<br/>";
          // echo "<br/>";
          $this->db->insert('riwayat_po_gs', $data_riwayat);
          $this->db->delete('detail_po_gs', ['id_detail_po_gs' => $id_detail_po_gs]);
        } else {
          $data_po = [
            'id_detail_po_gs' => $id_detail_po_gs,
            'jumlah_barang' => $sisa_barang,
            'total_harga_barang' => $sisa_barang * $o['harga_barang'],
          ];
          // var_dump($data_po);
          $this->M_order->update_detail_outstanding($data_po);
        }
        
        if ($update) {
          set_pesan('Error!! Terjadi Kesalahan Di Database.', false);
          redirect('edit-detail-invoice/'.$id_detail_gs.'/'.$id_gs);
        } else {
          set_pesan('Data Berhasil Diedit.', true);
          redirect('detail-invoice/'.$id_gs);
        }
      }
    }

    public function edit_status($id_gs, $status)
    {
      if ($status === 'selesai') {
        $status_gs = 'Sudah Selesai';
      } else {
        $status_gs = 'Belum Selesai';
      }
      $data_user	= [
        'id_gs'	        => $id_gs,
        'status_gs'		  => $status_gs,
      ];
        
      if ($this->M_order->update($data_user)) {
        set_pesan('Error!! Terjadi Kesalahan Di Database.', false);
        redirect('detail-invoice/'.$id_gs);
      } else {
        set_pesan('Status Berhasil Diedit.', true);
        redirect('detail-invoice/'.$id_gs);
      }
    }

    public function hapus_detail($id_detail_gs, $id_gs, $diskon)
    {
      $this->db->trans_start();
      if ($diskon === 'bukan') {
        $fd	= $this->M_order->get_detail_by_id($id_detail_gs);
        $detail_po = $this->M_order->get_detail_outstanding_by_id($fd['id_detail_po_gs']);
        $get_stok = $this->db->get_where('stok_barang', ['id_barang' => $fd['id_barang'], 'harga_beli' => $fd['harga_beli']])->row_array();
        $id_stok_barang = $get_stok['id_stok_barang'];
        $stok = $get_stok['stok'];
        $stok_up = $stok + $fd['jumlah_barang'];
  
        if(!empty($detail_po)){
          $sisa_barang = $fd['jumlah_barang'] + $detail_po['jumlah_barang'];
          $data_po = [
            'id_detail_po_gs' => $fd['id_detail_po_gs'],
            'jumlah_barang' => $sisa_barang,
            'total_harga_barang' => $sisa_barang * $detail_po['harga_barang'],
          ];
          $this->M_order->update_detail_outstanding($data_po);
        } else {
          $cek_riwayat = $this->db->get_where('riwayat_po_gs', ['id_detail_po_gs' => $fd['id_detail_po_gs'], 'no_po' => $fd['no_po']])->num_rows();
          if ($cek_riwayat == 0) {
            $get_po = $this->db->get_where('po_gs', ['no_po' => $fd['no_po']])->row_array();
            $data_riwayat = [
              'id_detail_po_gs' => $fd['id_detail_po_gs'],
              'no_po' => $fd['no_po'],
              'tanggal_po' => $get_po['tanggal'],
              'id_barang' => $fd['id_barang'],
              'nama_barang' => $fd['nama_barang'],
              'kode_barang' => $fd['kode_barang'],
              'satuan_barang' => $fd['satuan_barang'],
              'jumlah_barang' => $fd['jumlah_barang'],
              'harga_barang' => $fd['harga_barang'],
              'harga_beli' => $fd['harga_beli'],
              'total_harga_barang' => $fd['total_harga_barang'],
              'departemen' => $fd['departemen'],
            ];
            
            $this->db->insert('riwayat_po_gs', $data_riwayat);
          }
          $get_riwayat_by_id = $this->db->get_where('riwayat_po_gs', ['id_detail_po_gs' => $fd['id_detail_po_gs'], 'no_po' => $fd['no_po']])->row_array();
          $get_po = $this->db->get_where('po_gs', ['no_po' => $get_riwayat_by_id['no_po'], 'tanggal' => $get_riwayat_by_id['tanggal_po']])->row_array();
  
          $data_po = [
            'id_detail_po_gs' => $fd['id_detail_po_gs'],
            'id_po_gs' => $get_po['id_po_gs'],
            'id_barang' => $get_riwayat_by_id['id_barang'],
            'nama_barang' => $get_riwayat_by_id['nama_barang'],
            'kode_barang' => $get_riwayat_by_id['kode_barang'],
            'satuan_barang' => $get_riwayat_by_id['satuan_barang'],
            'jumlah_barang' => $fd['jumlah_barang'],
            'harga_barang' => $get_riwayat_by_id['harga_barang'],
            'harga_beli' => 0,
            'total_harga_barang' => $get_riwayat_by_id['total_harga_barang'],
            'departemen' => $get_riwayat_by_id['departemen'],
          ];
  
          $this->M_order->insert_detail_outstanding($data_po);
          $this->db->delete('riwayat_po_gs', ['id_detail_po_gs' => $get_riwayat_by_id['id_detail_po_gs'], 'no_po' => $get_riwayat_by_id['no_po']]);
        }
        $this->db->where('id_stok_barang', $id_stok_barang);
        $this->db->update('stok_barang', ['stok' => $stok_up]);
      }
      $this->M_order->delete_detail($id_detail_gs);
      $this->db->trans_complete();
      if ($this->db->trans_status() === FALSE) {
        $this->session->set_flashdata('msg', 'error');
        set_pesan('Error!! Terjadi Error Di Database.', false);
      } else {
        set_pesan('Data Berhasil Dihapus.', true);
      }
      redirect('detail-invoice/'.$id_gs);
    }

    private function validation_detail()
    {
      $this->form_validation->set_rules('id_detail_po_gs', 'Pilih PO', 'required|trim');
      $this->form_validation->set_rules('jumlah_barang', 'Jumlah', 'required|numeric');
      $this->form_validation->set_rules('harga_barang', 'Harga', 'required|numeric');
    }

    public function cetak_detail_invoice($id_gs) {
        $data['title']		= 'Invoice & Surat Jalan';
      $data['title2']		= 'Detail Invoice & Surat Jalan';
      $data['f']			  = $this->M_order->get_by_id($id_gs);
      $pch_tgl			    = explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));
      $bulan 				    = $this->bulan($pch_tgl[1]);
      $data['id_gs']    = $id_gs;
      $data['tanggal']	= date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));
      $data['f_detail']	= $this->M_order->get_detail($id_gs)->result_array();
      $data['barang']		= $this->db->get_where('detail_po_gs', ['jumlah_barang !=' => 0])->result_array();
      $this->load->view('invoice/cetak_invoice', $data);
 
    }
}