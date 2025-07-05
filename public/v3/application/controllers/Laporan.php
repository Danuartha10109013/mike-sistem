<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
    }

    public function fabrikasi()
    {
        $this->validation();
        if (!$this->form_validation->run()) {

            $data['title']    = 'Laporan Invoice Fabrikasi';
            $data['title2']    = 'c';
            $this->load->view('laporan/fabrikasi', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');
            $pch_dari_tgl = explode('-', $dari_tanggal);
            $pch_sampai_tgl = explode('-', $sampai_tanggal);
            $dari_bulan = $this->bulan($pch_dari_tgl[1]);
            $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
            if ($this->input->post('filter')) {
                $data['title']        = 'Laporan Invoice Fabrikasi';
                $data['title2']        = 'c';
                $data['invoice']        = $this->M_fabrikasi->get_invoice_by_range($dari_tanggal, $sampai_tanggal)->result_array();
                $total = 0;
                foreach ($data['invoice'] as $i) {
                    $total += $i['total_harga_barang'];
                }
                $data['total'] = $total;

                $this->load->view('laporan/filter_fabrikasi', $data);
            } else {
                $this->load->library('pdf');
                $data['title']        = 'Laporan Invoice Fabrikasi';
                $data['title2']        = 'c';
                $data['dari_tanggal'] = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
                $data['sampai_tanggal'] = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
                $data['invoice']        = $this->M_fabrikasi->get_invoice_by_range($dari_tanggal, $sampai_tanggal)->result_array();
                $this->load->view('laporan/cetak_fabrikasi', $data);

                //  $html_content = $this->load->view('laporan/cetak_fabrikasi', $data, true);
                //  $filename = 'Laporan Invoice ' .$dari_tanggal.' - '.$sampai_tanggal.'.pdf';

                //  $this->pdf->loadHtml($html_content);

                //  $this->pdf->set_paper('a4','potrait');

                //  $this->pdf->render();
                //  $this->pdf->stream($filename, ['Attachment' => 1]);
            }
        }
    }

    public function harga_jual_beli()
    {
        $this->validation();
        if (!$this->form_validation->run()) {

            $data['title']    = 'Laporan Harga Jual-Beli';
            $data['title2']    = 'c';
            $this->load->view('laporan/harga_jual_beli', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');
            $pch_dari_tgl = explode('-', $dari_tanggal);
            $pch_sampai_tgl = explode('-', $sampai_tanggal);
            $dari_bulan = $this->bulan($pch_dari_tgl[1]);
            $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
            if ($this->input->post('filter')) {
                $data['title']        = 'Laporan Harga Jual-Beli';
                $data['title2']        = 'c';
                $this->db->select('*');
                $this->db->from('detail_cs');
                $this->db->join('po_cs', 'po_cs.no_po=detail_cs.no_po');
                $this->db->where('po_cs.tanggal >=', $dari_tanggal);
                $this->db->where('po_cs.tanggal <=', $sampai_tanggal);
                $this->db->group_by('detail_cs.no_po');
                $this->db->order_by('detail_cs.no_po', 'DESC');
                $data['jual_beli']        = $this->db->get()->result_array();

                $this->load->view('laporan/filter_harga_jual_beli', $data);
            } elseif ($this->input->post('cetak')) {
                $this->load->library('pdf');
                $data['title']        = 'Laporan Harga Jual-Beli';
                $data['title2']        = 'c';
                $data['dari_tanggal'] = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
                $data['sampai_tanggal'] = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
                $this->db->select('*');
                $this->db->from('detail_cs');
                $this->db->join('po_cs', 'po_cs.no_po=detail_cs.no_po');
                $this->db->where('po_cs.tanggal >=', $dari_tanggal);
                $this->db->where('po_cs.tanggal <=', $sampai_tanggal);
                $this->db->group_by('detail_cs.no_po');
                $this->db->order_by('detail_cs.no_po', 'DESC');
                $data['jual_beli']        = $this->db->get()->result_array();
                $this->db->select('*');
                $this->db->select_sum('detail_cs.jumlah_barang');
                $this->db->from('detail_cs');
                $this->db->join('po_cs', 'po_cs.no_po=detail_cs.no_po');
                $this->db->where('po_cs.tanggal >=', $dari_tanggal);
                $this->db->where('po_cs.tanggal <=', $sampai_tanggal);
                $this->db->group_by(['detail_cs.id_barang', 'detail_cs.harga_beli', 'detail_cs.harga_barang']);
                $this->db->order_by('detail_cs.nama_barang', 'ASC');
                $data['detail_jb'] = $this->db->get()->result_array();
                $this->load->view('laporan/cetak_harga_jual_beli', $data);

                //  $html_content = $this->load->view('laporan/cetak_fabrikasi', $data, true);
                //  $filename = 'Laporan Invoice ' .$dari_tanggal.' - '.$sampai_tanggal.'.pdf';

                //  $this->pdf->loadHtml($html_content);

                //  $this->pdf->set_paper('a4','potrait');

                //  $this->pdf->render();
                //  $this->pdf->stream($filename, ['Attachment' => 1]);
            } else {
                $this->export_excel_jual_beli($dari_tanggal, $sampai_tanggal);
            }
        }
    }

    public function export_excel_jual_beli($dari_tanggal, $sampai_tanggal)
    {
        $pch_dari_tgl = explode('-', $dari_tanggal);
        $pch_sampai_tgl = explode('-', $sampai_tanggal);
        $dari_bulan = $this->bulan($pch_dari_tgl[1]);
        $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
        $dari_tanggal_format = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
        $sampai_tanggal_format = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
        $this->db->select('*');
        $this->db->from('detail_cs');
        $this->db->join('po_cs', 'po_cs.no_po=detail_cs.no_po');
        $this->db->where('po_cs.tanggal >=', $dari_tanggal);
        $this->db->where('po_cs.tanggal <=', $sampai_tanggal);
        $this->db->group_by('detail_cs.no_po');
        $this->db->order_by('detail_cs.no_po', 'DESC');
        $jual_beli        = $this->db->get()->result_array();
        $this->db->select('*');
        $this->db->select_sum('detail_cs.jumlah_barang');
        $this->db->from('detail_cs');
        $this->db->join('po_cs', 'po_cs.no_po=detail_cs.no_po');
        $this->db->where('po_cs.tanggal >=', $dari_tanggal);
        $this->db->where('po_cs.tanggal <=', $sampai_tanggal);
        $this->db->group_by(['detail_cs.id_barang', 'detail_cs.harga_beli', 'detail_cs.harga_barang']);
        //$this->db->where('detail_cs.no_po', $i['no_po']);
        $this->db->order_by('detail_cs.nama_barang', 'ASC');
        $detail_jb = $this->db->get()->result_array();

        include_once APPPATH . 'third_party/PHPExcel.php';

        $excel = new PHPExcel();

        $excel1 = new PHPExcel_Worksheet($excel, 'Laporan Harga Jual-Beli');

        // Attach the "My Data" worksheet as the first worksheet in the PHPExcel object
        $excel->addSheet($excel1, 0);

        $excel->getProperties()
            ->setCreator('IndoExpress')
            ->setLastModifiedBy('IndoExpress')
            ->setTitle('Data Laporan Harga Jual & Beli')
            ->setSubject('Laporan Harga Jual & Beli')
            ->setDescription('Laporan Harga Jual & Beli')
            ->setKeyWords('Laporan Harga Jual & Beli');

        $style_title = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ]
        ];
        $style_title_sec = [
            'font' => ['bold' => true],
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ]
        ];

        $style_col = [
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFB609']
            ],
            'font' => ['bold' => true],
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

        $style_row_num = [
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row_num_minus = [
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ],
            'font' => [
                'color' => ['rgb' => 'ff0000']
            ],
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

        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'LAPORAN JUAL-BELI PERIODE ' . $dari_tanggal_format . ' - ' . $sampai_tanggal_format);
        $excel->getActiveSheet()->mergeCells('B2:G2');
        $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_title);

        $excel->setActiveSheetIndex(0)->setCellValue('B4', 'NO');
        $excel->setActiveSheetIndex(0)->setCellValue('C4', 'NAMA BARANG');
        $excel->setActiveSheetIndex(0)->setCellValue('D4', 'QTY');
        $excel->setActiveSheetIndex(0)->setCellValue('E4', 'Harga Beli (PCS)');
        $excel->setActiveSheetIndex(0)->setCellValue('F4', 'Harga Jual (PCS)');
        $excel->setActiveSheetIndex(0)->setCellValue('G4', 'Laba Total');

        $excel->getActiveSheet()->getStyle('B4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G4')->applyFromArray($style_col);

        $numrow = 5;
        $numrow_last = 5;
        $no = 1;
        $total = 0;
        foreach ($detail_jb as $i) {
            $numroww = $numrow + 1;
            $numrow_last += $numrow_last + 3;
            $margin_total = $i['harga_barang'] * $i['jumlah_barang'] - $i['harga_beli'] * $i['jumlah_barang'];
            $total += $margin_total;
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, ($no++));
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $i['nama_barang']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $i['jumlah_barang']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, number_format($i['harga_beli'], 0, '.', ','));
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, number_format($i['harga_barang'], 0, '.', ','));
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, number_format($margin_total, 0, '.', ','));


            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row_num);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row_num);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($margin_total < 0 ? $style_row_num_minus : $style_row_num);

            $numrow = $numrow + 1;
        }

        $new_row = $numrow + 1;

        $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, 'TOTAL');
        $excel->getActiveSheet()->getStyle('B' . $numrow)->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('B' . $numrow)->getFont()->setSize(12);
        $excel->getActiveSheet()->getStyle('B' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row_full);
        $excel->getActiveSheet()->getStyle('B' . $numrow . ':F' . $numrow)->applyFromArray($style_row_full);
        $excel->getActiveSheet()->mergeCells('B' . intval($numrow) . ':F' . intval($numrow));
        $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, number_format($total, 0, '.', ','));
        $excel->getActiveSheet()->getStyle('G' . $numrow)->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('G' . $numrow)->getFont()->setSize(12);
        $excel->getActiveSheet()->getStyle('G' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row_full);
        $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row_full);

        $new_row = $new_row + 2;
        // $new_roww = $new_row;
        // var_dump($jual_beli);
        foreach ($jual_beli as $k) {
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $new_row, 'PO : ' . $k['no_po']);
            $excel->getActiveSheet()->mergeCells('B' . intval($new_row) . ':C' . intval($new_row));
            $excel->getActiveSheet()->getStyle('B' . $new_row)->applyFromArray($style_title_sec);

            $excel->setActiveSheetIndex(0)->setCellValue('B' . intval($new_row + 1), 'TANGGAL : ' . date('d F Y', strtotime($k['tanggal'])));
            $excel->getActiveSheet()->mergeCells('B' . intval($new_row + 1) . ':C' . intval($new_row + 1));
            $excel->getActiveSheet()->getStyle('B' . intval($new_row + 1))->applyFromArray($style_title_sec);

            $excel->setActiveSheetIndex(0)->setCellValue('B' . intval($new_row + 2), 'DEPARTEMEN : ' . $k['nama_user']);
            $excel->getActiveSheet()->mergeCells('B' . intval($new_row + 2) . ':C' . intval($new_row + 2));
            $excel->getActiveSheet()->getStyle('B' . intval($new_row + 2))->applyFromArray($style_title_sec);

            $new_row = $new_row + 3;

            $excel->setActiveSheetIndex(0)->setCellValue('B' . $new_row, 'NO');
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $new_row, 'NAMA BARANG');
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $new_row, 'QTY');
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $new_row, 'Harga Beli (PCS)');
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $new_row, 'Harga Jual (PCS)');
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $new_row, 'Laba Total');

            $excel->getActiveSheet()->getStyle('B' . $new_row)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C' . $new_row)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D' . $new_row)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('E' . $new_row)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('F' . $new_row)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('G' . $new_row)->applyFromArray($style_col);

            $new_row = $new_row + 1;
            $no = 1;
            $this->db->select('*');
            $this->db->from('detail_cs');
            $this->db->where('detail_cs.no_po', $k['no_po']);
            $this->db->order_by('detail_cs.nama_barang', 'ASC');
            $detail_jual_beli = $this->db->get()->result_array();
            $total = 0;
            foreach ($detail_jual_beli as $u) {
                $margin_total = $u['harga_barang'] * $u['jumlah_barang'] - $u['harga_beli'] * $u['jumlah_barang'];
                $total += $margin_total;
                $excel->setActiveSheetIndex(0)->setCellValue('B' . $new_row, ($no++));
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $new_row, $u['nama_barang']);
                $excel->setActiveSheetIndex(0)->setCellValue('D' . $new_row, $u['jumlah_barang']);
                $excel->setActiveSheetIndex(0)->setCellValue('E' . $new_row, number_format($u['harga_beli'], 0, '.', ','));
                $excel->setActiveSheetIndex(0)->setCellValue('F' . $new_row, number_format($u['harga_barang'], 0, '.', ','));
                $excel->setActiveSheetIndex(0)->setCellValue('G' . $new_row, number_format($margin_total, 0, '.', ','));

                $excel->getActiveSheet()->getStyle('B' . $new_row)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('C' . $new_row)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('D' . $new_row)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('E' . $new_row)->applyFromArray($style_row_num);
                $excel->getActiveSheet()->getStyle('F' . $new_row)->applyFromArray($style_row_num);
                $excel->getActiveSheet()->getStyle('G' . $new_row)->applyFromArray($margin_total < 0 ? $style_row_num_minus : $style_row_num);

                $new_row = $new_row + 1;
            }

            $excel->setActiveSheetIndex(0)->setCellValue('B' . $new_row, 'TOTAL');
            $excel->getActiveSheet()->getStyle('B' . $new_row)->getFont()->setBold(true);
            $excel->getActiveSheet()->getStyle('B' . $new_row)->getFont()->setSize(12);
            $excel->getActiveSheet()->getStyle('B' . $new_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('B' . $new_row)->applyFromArray($style_row_full);
            $excel->getActiveSheet()->getStyle('B' . $new_row . ':F' . $new_row)->applyFromArray($style_row_full);
            $excel->getActiveSheet()->mergeCells('B' . intval($new_row) . ':F' . intval($new_row));
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $new_row, number_format($total, 0, '.', ','));
            $excel->getActiveSheet()->getStyle('G' . $new_row)->getFont()->setBold(true);
            $excel->getActiveSheet()->getStyle('G' . $new_row)->getFont()->setSize(12);
            $excel->getActiveSheet()->getStyle('G' . $new_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $excel->getActiveSheet()->getStyle('G' . $new_row)->applyFromArray($style_row_full);
            $excel->getActiveSheet()->getStyle('G' . $new_row)->applyFromArray($style_row_full);

            $new_row = $new_row + 2;
        }

        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(43);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(17);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan Jual-Beli Periode ' . $dari_tanggal_format . '-' . $sampai_tanggal_format . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    public function harga_jual_beli_gs()
    {
        $this->validation();
        if (!$this->form_validation->run()) {

            $data['title']    = 'Laporan Harga Jual-Beli';
            $data['title2']    = 'gs';
            $this->load->view('laporan/harga_jual_beli_gs', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');
            $pch_dari_tgl = explode('-', $dari_tanggal);
            $pch_sampai_tgl = explode('-', $sampai_tanggal);
            $dari_bulan = $this->bulan($pch_dari_tgl[1]);
            $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
            if ($this->input->post('filter')) {
                $data['title']        = 'Laporan Harga Jual-Beli';
                $data['title2']        = 'gs';
                $this->db->select('*');
                $this->db->from('detail_gs');
                $this->db->join('po_gs', 'po_gs.no_po=detail_gs.no_po');
                $this->db->where('po_gs.tanggal >=', $dari_tanggal);
                $this->db->where('po_gs.tanggal <=', $sampai_tanggal);
                $this->db->group_by('detail_gs.no_po');
                $this->db->order_by('detail_gs.no_po', 'DESC');
                $data['jual_beli']        = $this->db->get()->result_array();

                $this->load->view('laporan/filter_harga_jual_beli_gs', $data);
            } elseif ($this->input->post('cetak')) {
                $this->load->library('pdf');
                $data['title']        = 'Laporan Harga Jual-Beli';
                $data['title2']        = 'gs';
                $data['dari_tanggal'] = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
                $data['sampai_tanggal'] = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
                $this->db->select('*');
                $this->db->from('detail_gs');
                $this->db->join('po_gs', 'po_gs.no_po=detail_gs.no_po');
                $this->db->where('po_gs.tanggal >=', $dari_tanggal);
                $this->db->where('po_gs.tanggal <=', $sampai_tanggal);
                $this->db->group_by('detail_gs.no_po');
                $this->db->order_by('detail_gs.no_po', 'DESC');
                $data['jual_beli']        = $this->db->get()->result_array();
                $this->db->select('*');
                $this->db->select_sum('detail_gs.jumlah_barang');
                $this->db->from('detail_gs');
                $this->db->join('po_gs', 'po_gs.no_po=detail_gs.no_po');
                $this->db->where('po_gs.tanggal >=', $dari_tanggal);
                $this->db->where('po_gs.tanggal <=', $sampai_tanggal);
                $this->db->group_by(['detail_gs.id_barang', 'detail_gs.harga_beli', 'detail_gs.harga_barang']);
                //$this->db->where('detail_gs.no_po', $i['no_po']);
                $this->db->order_by('detail_gs.nama_barang', 'ASC');
                $data['detail_jb'] = $this->db->get()->result_array();
                $this->load->view('laporan/cetak_harga_jual_beli_gs', $data);

                //  $html_content = $this->load->view('laporan/cetak_fabrikasi', $data, true);
                //  $filename = 'Laporan Invoice ' .$dari_tanggal.' - '.$sampai_tanggal.'.pdf';

                //  $this->pdf->loadHtml($html_content);

                //  $this->pdf->set_paper('a4','potrait');

                //  $this->pdf->render();
                //  $this->pdf->stream($filename, ['Attachment' => 1]);
            } else {
                $this->export_excel_jual_beli_gs($dari_tanggal, $sampai_tanggal);
            }
        }
    }

    public function export_excel_jual_beli_gs($dari_tanggal, $sampai_tanggal)
    {
        $pch_dari_tgl = explode('-', $dari_tanggal);
        $pch_sampai_tgl = explode('-', $sampai_tanggal);
        $dari_bulan = $this->bulan($pch_dari_tgl[1]);
        $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
        $dari_tanggal_format = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
        $sampai_tanggal_format = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
        $this->db->select('*');
        $this->db->from('detail_gs');
        $this->db->join('po_gs', 'po_gs.no_po=detail_gs.no_po');
        $this->db->where('po_gs.tanggal >=', $dari_tanggal);
        $this->db->where('po_gs.tanggal <=', $sampai_tanggal);
        $this->db->group_by('detail_gs.no_po');
        $this->db->order_by('detail_gs.no_po', 'DESC');
        $jual_beli        = $this->db->get()->result_array();
        $this->db->select('*');
        $this->db->select_sum('detail_gs.jumlah_barang');
        $this->db->from('detail_gs');
        $this->db->join('po_gs', 'po_gs.no_po=detail_gs.no_po');
        $this->db->where('po_gs.tanggal >=', $dari_tanggal);
        $this->db->where('po_gs.tanggal <=', $sampai_tanggal);
        $this->db->group_by(['detail_gs.id_barang', 'detail_gs.harga_beli', 'detail_gs.harga_barang']);
        //$this->db->where('detail_gs.no_po', $i['no_po']);
        $this->db->order_by('detail_gs.nama_barang', 'ASC');
        $detail_jb = $this->db->get()->result_array();

        include_once APPPATH . 'third_party/PHPExcel.php';

        $excel = new PHPExcel();

        $excel1 = new PHPExcel_Worksheet($excel, 'Laporan Harga Jual-Beli');

        // Attach the "My Data" worksheet as the first worksheet in the PHPExcel object
        $excel->addSheet($excel1, 0);

        $excel->getProperties()
            ->setCreator('IndoExpress')
            ->setLastModifiedBy('IndoExpress')
            ->setTitle('Data Laporan Harga Jual & Beli')
            ->setSubject('Laporan Harga Jual & Beli')
            ->setDescription('Laporan Harga Jual & Beli')
            ->setKeyWords('Laporan Harga Jual & Beli');

        $style_title = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ]
        ];
        $style_title_sec = [
            'font' => ['bold' => true],
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ]
        ];

        $style_col = [
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFB609']
            ],
            'font' => ['bold' => true],
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

        $style_row_num = [
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row_num_minus = [
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ],
            'font' => [
                'color' => ['rgb' => 'ff0000']
            ],
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

        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'LAPORAN JUAL-BELI PERIODE ' . $dari_tanggal_format . ' - ' . $sampai_tanggal_format);
        $excel->getActiveSheet()->mergeCells('B2:G2');
        $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_title);

        $excel->setActiveSheetIndex(0)->setCellValue('B4', 'NO');
        $excel->setActiveSheetIndex(0)->setCellValue('C4', 'NAMA BARANG');
        $excel->setActiveSheetIndex(0)->setCellValue('D4', 'QTY');
        $excel->setActiveSheetIndex(0)->setCellValue('E4', 'Harga Beli (PCS)');
        $excel->setActiveSheetIndex(0)->setCellValue('F4', 'Harga Jual (PCS)');
        $excel->setActiveSheetIndex(0)->setCellValue('G4', 'Laba Total');

        $excel->getActiveSheet()->getStyle('B4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G4')->applyFromArray($style_col);

        $numrow = 5;
        $numrow_last = 5;
        $no = 1;
        $total = 0;
        foreach ($detail_jb as $i) {
            $numroww = $numrow + 1;
            $numrow_last += $numrow_last + 3;
            $margin_total = $i['harga_barang'] * $i['jumlah_barang'] - $i['harga_beli'] * $i['jumlah_barang'];
            $total += $margin_total;
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, ($no++));
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $i['nama_barang']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $i['jumlah_barang']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, number_format($i['harga_beli'], 0, '.', ','));
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, number_format($i['harga_barang'], 0, '.', ','));
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, number_format($margin_total, 0, '.', ','));


            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row_num);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row_num);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($margin_total < 0 ? $style_row_num_minus : $style_row_num);

            $numrow = $numrow + 1;
        }

        $new_row = $numrow + 1;

        $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, 'TOTAL');
        $excel->getActiveSheet()->getStyle('B' . $numrow)->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('B' . $numrow)->getFont()->setSize(12);
        $excel->getActiveSheet()->getStyle('B' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row_full);
        $excel->getActiveSheet()->getStyle('B' . $numrow . ':F' . $numrow)->applyFromArray($style_row_full);
        $excel->getActiveSheet()->mergeCells('B' . intval($numrow) . ':F' . intval($numrow));
        $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, number_format($total, 0, '.', ','));
        $excel->getActiveSheet()->getStyle('G' . $numrow)->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('G' . $numrow)->getFont()->setSize(12);
        $excel->getActiveSheet()->getStyle('G' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row_full);
        $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row_full);

        $new_row = $new_row + 2;
        // $new_roww = $new_row;
        // var_dump($jual_beli);
        foreach ($jual_beli as $k) {
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $new_row, 'PO : ' . $k['no_po']);
            $excel->getActiveSheet()->mergeCells('B' . intval($new_row) . ':C' . intval($new_row));
            $excel->getActiveSheet()->getStyle('B' . $new_row)->applyFromArray($style_title_sec);

            $excel->setActiveSheetIndex(0)->setCellValue('B' . intval($new_row + 1), 'TANGGAL : ' . date('d F Y', strtotime($k['tanggal'])));
            $excel->getActiveSheet()->mergeCells('B' . intval($new_row + 1) . ':C' . intval($new_row + 1));
            $excel->getActiveSheet()->getStyle('B' . intval($new_row + 1))->applyFromArray($style_title_sec);

            $excel->setActiveSheetIndex(0)->setCellValue('B' . intval($new_row + 2), 'DEPARTEMEN : ' . $k['nama_user']);
            $excel->getActiveSheet()->mergeCells('B' . intval($new_row + 2) . ':C' . intval($new_row + 2));
            $excel->getActiveSheet()->getStyle('B' . intval($new_row + 2))->applyFromArray($style_title_sec);

            $new_row = $new_row + 3;

            $excel->setActiveSheetIndex(0)->setCellValue('B' . $new_row, 'NO');
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $new_row, 'NAMA BARANG');
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $new_row, 'QTY');
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $new_row, 'Harga Beli (PCS)');
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $new_row, 'Harga Jual (PCS)');
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $new_row, 'Laba Total');

            $excel->getActiveSheet()->getStyle('B' . $new_row)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C' . $new_row)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D' . $new_row)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('E' . $new_row)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('F' . $new_row)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('G' . $new_row)->applyFromArray($style_col);

            $new_row = $new_row + 1;
            $no = 1;
            $this->db->select('*');
            $this->db->from('detail_gs');
            $this->db->where('detail_gs.no_po', $k['no_po']);
            $this->db->order_by('detail_gs.nama_barang', 'ASC');
            $detail_jual_beli = $this->db->get()->result_array();
            $total = 0;
            foreach ($detail_jual_beli as $u) {
                $margin_total = $u['harga_barang'] * $u['jumlah_barang'] - $u['harga_beli'] * $u['jumlah_barang'];
                $total += $margin_total;
                $excel->setActiveSheetIndex(0)->setCellValue('B' . $new_row, ($no++));
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $new_row, $u['nama_barang']);
                $excel->setActiveSheetIndex(0)->setCellValue('D' . $new_row, $u['jumlah_barang']);
                $excel->setActiveSheetIndex(0)->setCellValue('E' . $new_row, number_format($u['harga_beli'], 0, '.', ','));
                $excel->setActiveSheetIndex(0)->setCellValue('F' . $new_row, number_format($u['harga_barang'], 0, '.', ','));
                $excel->setActiveSheetIndex(0)->setCellValue('G' . $new_row, number_format($margin_total, 0, '.', ','));

                $excel->getActiveSheet()->getStyle('B' . $new_row)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('C' . $new_row)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('D' . $new_row)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('E' . $new_row)->applyFromArray($style_row_num);
                $excel->getActiveSheet()->getStyle('F' . $new_row)->applyFromArray($style_row_num);
                $excel->getActiveSheet()->getStyle('G' . $new_row)->applyFromArray($margin_total < 0 ? $style_row_num_minus : $style_row_num);

                $new_row = $new_row + 1;
            }

            $excel->setActiveSheetIndex(0)->setCellValue('B' . $new_row, 'TOTAL');
            $excel->getActiveSheet()->getStyle('B' . $new_row)->getFont()->setBold(true);
            $excel->getActiveSheet()->getStyle('B' . $new_row)->getFont()->setSize(12);
            $excel->getActiveSheet()->getStyle('B' . $new_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('B' . $new_row)->applyFromArray($style_row_full);
            $excel->getActiveSheet()->getStyle('B' . $new_row . ':F' . $new_row)->applyFromArray($style_row_full);
            $excel->getActiveSheet()->mergeCells('B' . intval($new_row) . ':F' . intval($new_row));
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $new_row, number_format($total, 0, '.', ','));
            $excel->getActiveSheet()->getStyle('G' . $new_row)->getFont()->setBold(true);
            $excel->getActiveSheet()->getStyle('G' . $new_row)->getFont()->setSize(12);
            $excel->getActiveSheet()->getStyle('G' . $new_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $excel->getActiveSheet()->getStyle('G' . $new_row)->applyFromArray($style_row_full);
            $excel->getActiveSheet()->getStyle('G' . $new_row)->applyFromArray($style_row_full);

            $new_row = $new_row + 2;
        }

        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(43);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(17);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan Jual-Beli Periode ' . $dari_tanggal_format . '-' . $sampai_tanggal_format . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    public function all()
    {
        $this->validation();
        if (!$this->form_validation->run()) {

            $data['title']    = 'Laporan All Invoice';
            $data['title2']    = 'e';
            $this->load->view('laporan/all', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');
            $pch_dari_tgl = explode('-', $dari_tanggal);
            $pch_sampai_tgl = explode('-', $sampai_tanggal);
            $dari_bulan = $this->bulan($pch_dari_tgl[1]);
            $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
            if ($this->input->post('filter')) {
                $data['title']        = 'Laporan All Invoice';
                $data['title2']        = 'e';
                $data['invoice']        = $this->M_fabrikasi->get_all_invoice_by_range($dari_tanggal, $sampai_tanggal)->result_array();
                $total = 0;
                foreach ($data['invoice'] as $i) {
                    $total += $i['total_harga_barang'];
                }
                $data['total'] = $total;

                $this->load->view('laporan/filter_all', $data);
            } else {
                $this->load->library('pdf');
                $data['title']        = 'Laporan All Invoice';
                $data['title2']        = 'e';
                $data['dari_tanggal'] = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
                $data['sampai_tanggal'] = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
                $data['invoice']        = $this->M_fabrikasi->get_all_invoice_by_range($dari_tanggal, $sampai_tanggal)->result_array();
                $this->load->view('laporan/cetak_all', $data);

                //  $html_content = $this->load->view('laporan/cetak_fabrikasi', $data, true);
                //  $filename = 'Laporan Invoice ' .$dari_tanggal.' - '.$sampai_tanggal.'.pdf';

                //  $this->pdf->loadHtml($html_content);

                //  $this->pdf->set_paper('a4','potrait');

                //  $this->pdf->render();
                //  $this->pdf->stream($filename, ['Attachment' => 1]);
            }
        }
    }

    public function cs()
    {
        $this->validation();
        if (!$this->form_validation->run()) {

            $data['title']    = 'Laporan Invoice Cleanig Supply';
            $data['title2']    = 'd';
            $this->load->view('laporan/cs', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');
            $pch_dari_tgl = explode('-', $dari_tanggal);
            $pch_sampai_tgl = explode('-', $sampai_tanggal);
            $dari_bulan = $this->bulan($pch_dari_tgl[1]);
            $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
            if ($this->input->post('filter')) {
                $data['title']        = 'Laporan Invoice Cleanig Supply';
                $data['title2']        = 'cd';
                $data['invoice']        = $this->M_cs->get_invoice_by_range($dari_tanggal, $sampai_tanggal)->result_array();
                $total = 0;
                foreach ($data['invoice'] as $i) {
                    $total += $i['total_harga_barang'];
                }
                $data['total'] = $total;
                $this->load->view('laporan/filter_cs', $data);
            } else {
                $this->load->library('pdf');
                $data['title']        = 'Laporan Invoice Cleanig Supply';
                $data['title2']        = 'cd';
                $data['dari_tanggal'] = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
                $data['sampai_tanggal'] = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
                $data['invoice']        = $this->M_cs->get_invoice_by_range($dari_tanggal, $sampai_tanggal)->result_array();

                $html_content = $this->load->view('laporan/cetak_cs', $data, true);
                $filename = 'Laporan Invoice ' . $dari_tanggal . ' - ' . $sampai_tanggal . '.pdf';

                $this->pdf->loadHtml($html_content);

                $this->pdf->set_paper('a4', 'potrait');

                $this->pdf->render();
                $this->pdf->stream($filename, ['Attachment' => 1]);
            }
        }
    }

    public function gs()
    {
        $this->validation();
        if (!$this->form_validation->run()) {

            $data['title']    = 'Laporan Invoice General Supply';
            $data['title2']    = 'f';
            $this->load->view('laporan/gs', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');
            $pch_dari_tgl = explode('-', $dari_tanggal);
            $pch_sampai_tgl = explode('-', $sampai_tanggal);
            $dari_bulan = $this->bulan($pch_dari_tgl[1]);
            $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
            if ($this->input->post('filter')) {
                $data['title']        = 'Laporan Invoice General Supply';
                $data['title2']        = 'cd';
                $data['invoice']        = $this->M_gs->get_invoice_by_range($dari_tanggal, $sampai_tanggal)->result_array();
                $total = 0;
                foreach ($data['invoice'] as $i) {
                    $total += $i['total_harga_barang'];
                }
                $data['total'] = $total;
                $this->load->view('laporan/filter_gs', $data);
            } else {
                $this->load->library('pdf');
                $data['title']        = 'Laporan Invoice General Supply';
                $data['title2']        = 'cd';
                $data['dari_tanggal'] = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
                $data['sampai_tanggal'] = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
                $data['invoice']        = $this->M_gs->get_invoice_by_range($dari_tanggal, $sampai_tanggal)->result_array();

                $html_content = $this->load->view('laporan/cetak_gs', $data, true);
                $filename = 'Laporan Invoice ' . $dari_tanggal . ' - ' . $sampai_tanggal . '.pdf';

                $this->pdf->loadHtml($html_content);

                $this->pdf->set_paper('a4', 'potrait');

                $this->pdf->render();
                $this->pdf->stream($filename, ['Attachment' => 1]);
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

    public function omset_fabrikasi_by_departemen()
    {
        $this->validation();
        if (!$this->form_validation->run()) {

            $data['title']    = 'Omset Fabrikasi By Departemen';
            $data['title2']    = 'By Departemen';
            $data['departemen'] = $this->M_departemen->get_data()->result_array();
            $this->load->view('laporan/omset_fabrikasi_by_departemen', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');
            $departemen = $this->input->post('departemen');
            $pch_dari_tgl = explode('-', $dari_tanggal);
            $pch_sampai_tgl = explode('-', $sampai_tanggal);
            $dari_bulan = $this->bulan($pch_dari_tgl[1]);
            $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
            if ($this->input->post('filter')) {
                $data['title']        = 'Omset Fabrikasi By Departemen';
                $data['title2']        = 'By Departemen';
                $data['omset']        = $this->M_fabrikasi->get_omset_departemen($dari_tanggal, $sampai_tanggal, $departemen)->result_array();
                $total = 0;
                foreach ($data['omset'] as $i) {
                    $total += $i['total'];
                }
                $data['total'] = $total;

                $this->load->view('laporan/filter_omset_fabrikasi_by_departemen', $data);
            } else {
                $this->load->library('pdf');
                $data['title']        = 'Omset Fabrikai By Departemen';
                $data['title2']        = 'By Departemen';
                $detailDepartemen = $this->M_departemen->get_by_id($departemen);
                $data['dari_tanggal'] = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
                $data['sampai_tanggal'] = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
                $data['departemen'] = $detailDepartemen['nama_departemen'];
                $data['omset']        = $this->M_fabrikasi->get_omset_departemen($dari_tanggal, $sampai_tanggal, $departemen)->result_array();
                $this->load->view('laporan/cetak_omset_fabrikasi_by_departemen', $data);
            }
        }
    }

    public function omset_fabrikasi_by_user()
    {
        $this->validation();
        if (!$this->form_validation->run()) {

            $data['title']    = 'Omset Fabrikasi By User';
            $data['title2']    = 'By User';
            $data['user'] = $this->M_user->get_data()->result_array();
            $this->load->view('laporan/omset_fabrikasi_by_user', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');
            $id_user = $this->input->post('id_user');
            $pch_dari_tgl = explode('-', $dari_tanggal);
            $pch_sampai_tgl = explode('-', $sampai_tanggal);
            $dari_bulan = $this->bulan($pch_dari_tgl[1]);
            $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
            if ($this->input->post('filter')) {
                $data['title']        = 'Omset Fabrikasi By User';
                $data['title2']        = 'By User';
                $data['omset']        = $this->M_fabrikasi->get_omset_user($dari_tanggal, $sampai_tanggal, $id_user)->result_array();
                $total = 0;
                foreach ($data['omset'] as $i) {
                    $total += $i['total'];
                }
                $data['total'] = $total;

                $this->load->view('laporan/filter_omset_fabrikasi_by_user', $data);
            } else {
                $this->load->library('pdf');
                $data['title']        = 'Omset Fabrikai By User';
                $data['title2']        = 'By User';
                $detailUser = $this->M_user->get_by_id($id_user);
                $data['dari_tanggal'] = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
                $data['sampai_tanggal'] = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
                $data['user'] = $detailUser['nama'];
                $data['omset']        = $this->M_fabrikasi->get_omset_user($dari_tanggal, $sampai_tanggal, $id_user)->result_array();
                $this->load->view('laporan/cetak_omset_fabrikasi_by_user', $data);
            }
        }
    }

    public function omset_gs_by_departemen()
    {
        $this->validation();
        if (!$this->form_validation->run()) {

            $data['title']    = 'Omset General Supply By Departemen';
            $data['title2']    = 'By Departemen';
            $data['departemen'] = $this->M_departemen->get_data()->result_array();
            $this->load->view('laporan/omset_gs_by_departemen', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');
            $departemen = $this->input->post('departemen');
            $pch_dari_tgl = explode('-', $dari_tanggal);
            $pch_sampai_tgl = explode('-', $sampai_tanggal);
            $dari_bulan = $this->bulan($pch_dari_tgl[1]);
            $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
            if ($this->input->post('filter')) {
                $data['title']        = 'Omset General Supply By Departemen';
                $data['title2']        = 'By Departemen';
                $data['omset']        = $this->M_gs->get_omset_departemen($dari_tanggal, $sampai_tanggal, $departemen)->result_array();
                $total = 0;
                foreach ($data['omset'] as $i) {
                    $total += $i['total'];
                }
                $data['total'] = $total;

                $this->load->view('laporan/filter_omset_gs_by_departemen', $data);
            } else {
                $this->load->library('pdf');
                $data['title']        = 'Omset General Supply By Departemen';
                $data['title2']        = 'By Departemen';
                $detailDepartemen = $this->M_departemen->get_by_id($departemen);
                $data['dari_tanggal'] = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
                $data['sampai_tanggal'] = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
                $data['departemen'] = $detailDepartemen['nama_departemen'];
                $data['omset']        = $this->M_gs->get_omset_departemen($dari_tanggal, $sampai_tanggal, $departemen)->result_array();
                $this->load->view('laporan/cetak_omset_gs_by_departemen', $data);
            }
        }
    }

    public function omset_gs_by_user()
    {
        $this->validation();
        if (!$this->form_validation->run()) {

            $data['title']    = 'Omset General Supply By User';
            $data['title2']    = 'By User';
            $data['user'] = $this->M_user->get_data()->result_array();
            $this->load->view('laporan/omset_gs_by_user', $data);
        } else {
            $dari_tanggal = $this->input->post('dari_tanggal');
            $sampai_tanggal = $this->input->post('sampai_tanggal');
            $id_user = $this->input->post('id_user');
            $pch_dari_tgl = explode('-', $dari_tanggal);
            $pch_sampai_tgl = explode('-', $sampai_tanggal);
            $dari_bulan = $this->bulan($pch_dari_tgl[1]);
            $sampai_bulan = $this->bulan($pch_sampai_tgl[1]);
            if ($this->input->post('filter')) {
                $data['title']        = 'Omset General Supply By User';
                $data['title2']        = 'By User';
                $data['omset']        = $this->M_gs->get_omset_user($dari_tanggal, $sampai_tanggal, $id_user)->result_array();
                $total = 0;
                foreach ($data['omset'] as $i) {
                    $total += $i['total'];
                }
                $data['total'] = $total;

                $this->load->view('laporan/filter_omset_gs_by_user', $data);
            } else {
                $this->load->library('pdf');
                $data['title']        = 'Omset General Supply By User';
                $data['title2']        = 'By User';
                $detailUser         = $this->M_user->get_by_id($id_user);
                $data['dari_tanggal'] = $pch_dari_tgl[2] . ' ' . $dari_bulan . ' ' . $pch_dari_tgl[0];
                $data['sampai_tanggal'] = $pch_sampai_tgl[2] . ' ' . $sampai_bulan . ' ' . $pch_sampai_tgl[0];
                $data['user'] = $detailUser['nama'];
                $data['omset']        = $this->M_gs->get_omset_user($dari_tanggal, $sampai_tanggal, $id_user)->result_array();
                $this->load->view('laporan/cetak_omset_gs_by_user', $data);
            }
        }
    }
}
