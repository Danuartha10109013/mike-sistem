<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StokBarang extends CI_Controller
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
        $data['title2']        = 'Stok Barang';

        $data['jenis'] = $this->M_barang->get_jenis_barang();
        $data['jenis_barang'] = '';
        $post_jb = $this->input->post('jenis_barang');
        $barang = $this->M_barang->get_stok_barang()->result_array();
        $nab = $this->M_barang->get_nilai_asset();
        if (empty($post_jb)) {
            $data['jenis_barang'] = 'All';
        } else if ($post_jb === 'All') {
            $data['jenis_barang'] = 'All';
            $barang = $this->M_barang->get_stok_barang()->result_array();
            $nab = $this->M_barang->get_nilai_asset();
        } else {
            $data['jenis_barang'] = $post_jb;
            $barang = $this->M_barang->get_stok_barang($post_jb)->result_array();
            $nab = $this->M_barang->get_nilai_asset($post_jb);
        }


        $data['barang']        = $barang;
        $data['nab']        = $nab;

        $this->load->view('stokBarang/data', $data);
    }

    public function detail_stok_barang($id_barang)
    {
        $data['title']        = 'Data Barang';
        $data['title2']        = 'Stok Barang';
        $data['b']        = $this->M_barang->get_stok_barang_by_id($id_barang);
        $data['barang']        = $this->M_barang->get_all_stok_barang_by_id($id_barang)->result_array();

        $this->load->view('stokBarang/data_detail_stok', $data);
    }

    public function getBarangById()
    {
        $id_stok_barang = $this->input->post('id_stok_barang');
        // $status_indent = $this->input->post('status_indent');
        if ($id_stok_barang) {
            $this->load->model('M_barang');
            $barang = $this->M_barang->get_stok_barang_by_id_stok($id_stok_barang);

            if ($barang) {
                echo json_encode($barang);
            } else {
                echo json_encode(['error' => 'Data barang tidak ditemukan']);
            }
        } else {
            echo json_encode(['error' => 'ID Barang tidak valid']);
        }
    }

    public function getDetailBarang()
    {
        $id_barang = $this->input->post('id_barang');
        // $status_indent = $this->input->post('status_indent');
        if ($id_barang) {
            $this->load->model('M_barang');
            $barang = $this->M_barang->get_stok_barang_by_id_status($id_barang);

            if ($barang) {
                $stok_barang = $this->M_barang->get_all_stok_barang_by_id_status($id_barang)->result_array();

                $barang['stok_barang'] = $stok_barang;
                echo json_encode($barang);
            } else {
                echo json_encode(['error' => 'Data barang tidak ditemukan']);
            }
        } else {
            echo json_encode(['error' => 'ID Barang tidak valid']);
        }
    }

    public function export_excel_stok_barang()
    {
        include_once APPPATH . 'third_party/PHPExcel.php';
        $barang     = $this->M_barang->get_stok_barang()->result_array();
        $excel = new PHPExcel();
        $excel1 = new PHPExcel_Worksheet($excel, 'Data Stok Barang');
        // Attach the "My Data" worksheet as the first worksheet in the PHPExcel object
        $excel->addSheet($excel1, 0);
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $excel->setActiveSheetIndex(0);
        $excel->getProperties()
            ->setCreator('MerlinStore')
            ->setLastModifiedBy('MerlinStore')
            ->setTitle('Data Stok Barang')
            ->setSubject('Data Stok Barang')
            ->setDescription('Data Stok Barang')
            ->setKeyWords('Data Stok Barang');
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

        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'LAPORAN STOK BARANG');
        $excel->getActiveSheet()->mergeCells('B2:F2');
        $excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(0)->setCellValue('B3', date('d F Y'));
        $excel->getActiveSheet()->mergeCells('B3:F3');
        $excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(0)->setCellValue('B5', 'NO');
        $excel->setActiveSheetIndex(0)->setCellValue('C5', 'KODE BARANG');
        $excel->setActiveSheetIndex(0)->setCellValue('D5', 'NAMA BARANG');
        $excel->setActiveSheetIndex(0)->setCellValue('E5', 'STOK');
        $excel->setActiveSheetIndex(0)->setCellValue('F5', 'HARGA');

        $excel->getActiveSheet()->getStyle('B5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F5')->applyFromArray($style_col);

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
            $excel->getActiveSheet()->mergeCells('B' . $numrow . ':B' . intval($numrow + 1));
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $i['kode_barang']);
            $excel->getActiveSheet()->mergeCells('C' . $numrow . ':C' . intval($numrow + 1));
            $excel->getActiveSheet()->getStyle('C' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $i['nama_barang']);
            $excel->getActiveSheet()->mergeCells('D' . $numrow . ':D' . intval($numrow + 1));
            $excel->getActiveSheet()->getStyle('D' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $i['stok']);
            $excel->getActiveSheet()->mergeCells('E' . $numrow . ':E' . intval($numrow + 1));
            $excel->getActiveSheet()->getStyle('E' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $i['harga_beli']);
            $excel->getActiveSheet()->mergeCells('F' . $numrow . ':F' . intval($numrow + 1));
            $excel->getActiveSheet()->getStyle('F' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('B' . $numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numroww)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numroww)->applyFromArray($style_row);

            // $excel->getActiveSheet()->getStyle('B'.$numrow2)->applyFromArray($style_row);
            // $excel->getActiveSheet()->getStyle('C'.$numrow2)->applyFromArray($style_row);
            // $excel->getActiveSheet()->getStyle('D'.$numrow2)->applyFromArray($style_row);
            // $excel->getActiveSheet()->getStyle('E'.$numrow2)->applyFromArray($style_row);
            // $excel->getActiveSheet()->getStyle('F'.$numrow2)->applyFromArray($style_row);

            $numrow = $numrow + 2;
        }

        $numrow_terbilang = $numrow + 1;
        $excel->getActiveSheet()->getStyle('B' . $numrow . ':F' . $numrow)->applyFromArray($style_row_bottom);
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(43);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(26);
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Data Stok Barang ' . date('d F Y') . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
    public function updateStokBarang()
    {

        $this->validation_stock();

        if (!$this->form_validation->run()) {


            echo json_encode(['status' => 'error', 'message' => 'Isi semua data']);
        } else {

            $data        = $this->input->post(null, true);
            $fbarang = $this->M_barang->get_stok_barang_by_id_stok($data['id_stok_barang']);

            if ($fbarang == 0) {

                echo json_encode(['status' => 'error', 'message' => 'Data tidak di temukan']);
            }
            $data_stok    = [

                'stok' => $data['stok']
            ];

            $this->db->where('id_stok_barang', $data['id_stok_barang']);

            $update    = $this->db->update('stok_barang', $data_stok);

            if ($update) {
                log_activity($this->session->userdata('id_user'), 'Edit Stock Barang', 'Stock Barang berhasil diedit', $data);

                echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'data gagal di update']);
            }
        }
    }
    private function validation_stock()

    {

        $this->form_validation->set_rules('id_stok_barang', 'ID Stok Barang', 'required|trim');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric');
    }
}
