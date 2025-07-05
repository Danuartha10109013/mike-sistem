<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StokOpname extends CI_Controller
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
        $data['title']        = 'Data Barang';
        $data['title2']        = 'Data Stok Opname';
        $data['jenis'] = $this->M_barang->get_jenis_barang();
        $data['jenis_barang'] = '';
        $post_jb = $this->input->post('jenis_barang');
        $barang = $this->M_barang->get_stok_opname()->result_array();
        if (empty($post_jb)) {
            $data['jenis_barang'] = 'All';
        } else if ($post_jb === 'All') {
            $data['jenis_barang'] = 'All';
            $barang = $this->M_barang->get_stok_opname()->result_array();
        } else {
            $data['jenis_barang'] = $post_jb;
            $barang = $this->M_barang->get_stok_opname($post_jb)->result_array();
        }
        $data['barang']        = $barang;
        $data['stokBarang']        = $this->M_barang->get_available_stok_barang()->result_array();
        $this->load->view('stokOpname/data', $data);
    }

    public function get_by_id()
    {
        $id_stok_opname = $this->input->post('id_stok_opname');
        $data = $this->db->get_where('stok_opname', ['id_stok_opname' => $id_stok_opname])->row_array();
        echo json_encode($data);
    }

    public function tambah()
    {
        $data        = $this->input->post(null, true);
        $stok = $this->db->get_where('stok_barang', ['id_stok_barang' => $data['id_stok_barang']])->row_array();

        $data_user    = [
            'id_stok_barang'    => $data['id_stok_barang'],
            'tanggal'            => $data['tanggal'],
            'jumlah'            => $data['jumlah'],
            'keterangan'        => $data['keterangan'],
        ];

        $insert = $this->M_barang->insert_stok_opname($data_user);
        log_activity($this->session->userdata('id_user'), 'Tambah Barang', 'barang berhasil ditambahkan', $data_user);

        // $stok_akhir = $stok['stok'] + $data['jumlah'];
        // $data_stok    = [
        //     'id_stok_barang'    => $data['id_stok_barang'],
        //     'stok'                => $stok_akhir,
        // ];
        // $this->M_barang->update_stok_barang($data_stok);
        if ($insert) {
            set_pesan('Terjadi error...', false);
            redirect('stok-opname');
        } else {
            set_pesan('Data Berhasil Ditambahkan', true);
            redirect('stok-opname');
        }
    }

    public function edit()
    {
        $data        = $this->input->post(null, true);
        $stok = $this->db->get_where('stok_barang', ['id_stok_barang' => $data['id_stok_barang']])->row_array();

        $data_user    = [
            'id_stok_opname'    => $data['id_stok_opname'],
            'id_stok_barang'    => $data['id_stok_barang'],
            'tanggal'            => $data['tanggal'],
            'jumlah'            => $data['jumlah'],
            'keterangan'        => $data['keterangan'],
        ];

        $update = $this->M_barang->update_stok_opname($data_user);
        log_activity($this->session->userdata('id_user'), 'Edit Stock Opname', 'Stock Opname berhasil diedit', $data_user);

        // $stok_lama = $this->db->get_where('stok_barang', ['id_stok_barang' => $data['id_stok_barang_old']])->row_array();
        // $id_stok_barang_lama = $data['id_stok_barang_old'];
        // $stok_akhir_lama = $stok_lama['stok'] - $data['jumlah_old'];

        // $data_stok_lama    = [
        //     'id_stok_barang' => $id_stok_barang_lama,
        //     'stok'        => $stok_akhir_lama,
        // ];

        // $this->M_barang->update_stok_barang($data_stok_lama);
        // $stok = $this->db->get_where('stok_barang', ['id_stok_barang' => $data['id_stok_barang']])->row_array();
        // $id_stok_barang = $data['id_stok_barang'];
        // $stok_akhir = $stok['stok'] + $data['jumlah'];

        // $data_stok    = [
        //     'id_stok_barang' => $id_stok_barang,
        //     'stok'        => $stok_akhir,
        // ];

        // $this->M_barang->update_stok_barang($data_stok);
        if ($update) {
            set_pesan('Terjadi error...', false);
            redirect('stok-opname');
        } else {
            set_pesan('Data Berhasil Diupdate', true);
            redirect('stok-opname');
        }
    }

    public function hapus($id_stok_opname)
    {
        $so = $this->M_barang->get_stok_opname_by_id($id_stok_opname);
        $id_stok_barang = $so['id_stok_barang'];
        $jumlah = $so['jumlah'];

        $stok = $this->db->get_where('stok_barang', ['id_stok_barang' => $id_stok_barang])->row_array();
        $stok_akhir = $stok['stok'] - $jumlah;
        $data_stok    = [
            'id_stok_barang' => $id_stok_barang,
            'stok'        => $stok_akhir,
        ];
        log_activity($this->session->userdata('id_user'), 'Hapus Stock Opname', 'Stock Opname berhasil dihapus', $so);

        // $this->M_barang->update_stok_barang($data_stok);
        $this->M_barang->delete_stok_opname($id_stok_opname);
        set_pesan('Data Berhasil Dihapus', true);
        redirect('stok-opname');
    }


    public function export_excel_stok_opname()
    {
        include_once APPPATH . 'third_party/PHPExcel.php';

        $barang = $this->M_barang->get_stok_opname()->result_array();
        $excel = new PHPExcel();
        $excel1 = new PHPExcel_Worksheet($excel, 'Data Stok Opname');
        // Attach the "My Data" worksheet as the first worksheet in the PHPExcel object
        $excel->addSheet($excel1, 0);
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $excel->setActiveSheetIndex(0);
        $excel->getProperties()
            ->setCreator('IndoExpress')
            ->setLastModifiedBy('IndoExpress')
            ->setTitle('Data Stok Opname')
            ->setSubject('Data Stok Opname')
            ->setDescription('Data Stok Opname')
            ->setKeyWords('Data Stok Opname');
        $style_col = [
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'd00c6c']
            ],
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row_full = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row_bottom = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'LAPORAN STOK OPNAME');
        $excel->getActiveSheet()->mergeCells('B2:K2');
        $excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(0)->setCellValue('B3', date('d F Y'));
        $excel->getActiveSheet()->mergeCells('B3:K3');
        $excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(0)->setCellValue('B5', 'NO');
        $excel->setActiveSheetIndex(0)->setCellValue('C5', 'KODE BARANG');
        $excel->setActiveSheetIndex(0)->setCellValue('D5', 'STATUS INDENT');
        $excel->setActiveSheetIndex(0)->setCellValue('E5', 'NAMA BARANG');
        $excel->setActiveSheetIndex(0)->setCellValue('F5', 'JENIS BARANG');
        $excel->setActiveSheetIndex(0)->setCellValue('G5', 'TANGGAL OPNAME');
        $excel->setActiveSheetIndex(0)->setCellValue('H5', 'KETERANGAN');
        $excel->setActiveSheetIndex(0)->setCellValue('I5', 'STOK');
        $excel->setActiveSheetIndex(0)->setCellValue('J5', 'JUMLAH');
        $excel->setActiveSheetIndex(0)->setCellValue('K5', 'SELISIH');

        $excel->getActiveSheet()->getStyle('B5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K5')->applyFromArray($style_col);

        $numrow = 6;
        $numrow_last = 6;
        $no = 1;
        $total = 0;
        foreach ($barang as $i) {
            $numroww = $numrow + 1;
            $numrow2 = $numrow + 2;
            $numrow_last += $numrow_last + 3;
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, ($no++));
            $excel->getActiveSheet()->getStyle('B' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $excel->getActiveSheet()->mergeCells('B' . $numrow . ':B' . intval($numrow + 1));
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $i['kode_barang']);
            // $excel->getActiveSheet()->mergeCells('C' . $numrow . ':C' . intval($numrow + 1));
            $excel->getActiveSheet()->getStyle('C' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $i['status_indent']);
            // $excel->getActiveSheet()->mergeCells('D' . $numrow . ':D' . intval($numrow + 1));
            $excel->getActiveSheet()->getStyle('D' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $i['nama_barang']);
            // $excel->getActiveSheet()->mergeCells('E' . $numrow . ':E' . intval($numrow + 1));
            $excel->getActiveSheet()->getStyle('E' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $i['jenis_barang']);
            // $excel->getActiveSheet()->mergeCells('F' . $numrow . ':F' . intval($numrow + 1));
            $excel->getActiveSheet()->getStyle('F' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow,  date('d F Y', strtotime($i['tanggal'])));
            // $excel->getActiveSheet()->mergeCells('G' . $numrow . ':G' . intval($numrow + 1));
            $excel->getActiveSheet()->getStyle('G' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow,   $i['keterangan']);
            // $excel->getActiveSheet()->mergeCells('H' . $numrow . ':H' . intval($numrow + 1));
            $excel->getActiveSheet()->getStyle('H' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow,   $i['stok']);
            // $excel->getActiveSheet()->mergeCells('I' . $numrow . ':I' . intval($numrow + 1));
            $excel->getActiveSheet()->getStyle('I' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow,   $i['jumlah']);
            // $excel->getActiveSheet()->mergeCells('J' . $numrow . ':J' . intval($numrow + 1));
            $excel->getActiveSheet()->getStyle('J' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->setActiveSheetIndex(0)->setCellValue('K' . $numrow,   $i['stok'] - $i['jumlah']);
            // $excel->getActiveSheet()->mergeCells('K' . $numrow . ':K' . intval($numrow + 1));
            $excel->getActiveSheet()->getStyle('K' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('K' . $numrow)->applyFromArray($style_row);


            $numrow = $numrow + 1;
        }

        $numrow_terbilang = $numrow + 1;
        $excel->getActiveSheet()->getStyle('B' . $numrow . ':K' . $numrow)->applyFromArray($style_row_bottom);


        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(8);
        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(26);
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Data Stok Opname ' . date('d F Y') . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}
