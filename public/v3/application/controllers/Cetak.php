<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Cetak extends CI_Controller {



	public function __construct()

	{

		parent::__construct();

		date_default_timezone_set("Asia/Jakarta");

	}



	public function cetak_invoice_cs($id_cs)

	{

		$this->load->library('pdf');

		$data['title']		= 'Cs';

        $data['title2']		= 'Invoice';

		$data['f']			= $this->M_cs->get_by_id($id_cs);

		$inv				= explode('/', $data['f']['no_invoice']);

		$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));

		$bulan 				= $this->bulan($pch_tgl[1]);

		$data['tanggal']	= date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));

		// $data['f_detail']	= $this->M_cs->get_detail($id_cs)->result_array();

        $this->db->select('*');

        $this->db->select_sum('jumlah_barang');

        $this->db->select_sum('total_harga_barang');

        $this->db->from('detail_cs');

        $this->db->where('id_cs', $id_cs);

        $this->db->group_by(['id_barang', 'no_po']);

        $this->db->order_by('id_detail_cs', 'ASC');

        $data['f_detail']   = $this->db->get()->result_array();

		

        $html_content = $this->load->view('cs/cetak_invoice', $data, true);

        $filename = 'Invoice - '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3].'.pdf';



        $this->pdf->loadHtml($html_content);



        $this->pdf->set_paper('a4','potrait');

        

        $this->pdf->render();

        $this->pdf->stream($filename, ['Attachment' => 1]);

	}



	public function cetak_surat_jalan_cs($id_cs)

	{

		$this->load->library('pdf');

		$data['title']		= 'Cs';

        $data['title2']		= 'Surat Jalan';

		$data['f']			= $this->M_cs->get_by_id($id_cs);

		$inv				= explode('/', $data['f']['no_invoice']);

		$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));

		$bulan 				= $this->bulan($pch_tgl[1]);

		$data['tanggal']	= date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));

		//$data['f_detail']	= $this->M_cs->get_detail($id_cs)->result_array();

		$this->db->select('*');

        $this->db->select_sum('jumlah_barang');

        $this->db->select_sum('total_harga_barang');

        $this->db->from('detail_cs');

        $this->db->where('id_cs', $id_cs);

        $this->db->group_by(['id_barang', 'no_po']);

        $this->db->order_by('id_detail_cs', 'ASC');

        $data['f_detail']   = $this->db->get()->result_array();

        $html_content = $this->load->view('cs/cetak_surat_jalan', $data, true);

        $filename = 'Surat Jalan - '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3].'.pdf';



        $this->pdf->loadHtml($html_content);



        $this->pdf->set_paper('a4','potrait');

        

        $this->pdf->render();

        $this->pdf->stream($filename, ['Attachment' => 1]);

	}



	public function export_excel_invoice_cs($id_cs)

    {

        include_once APPPATH . 'third_party/PHPExcel.php';

        $f 					= $this->M_cs->get_by_id($id_cs);

		$inv				= explode('/', $f['no_invoice']);

		$pch_tgl			= explode('-', date('d-F-Y', strtotime($f['tanggal'])));

		$bulan 				= $this->bulan($pch_tgl[1]);

		$tanggal 			= date('d', strtotime($f['tanggal'])).' '.$bulan.' '.date('Y', strtotime($f['tanggal']));

		$f_detail 			= $this->M_cs->get_detail($id_cs)->result_array();



        $excel = new PHPExcel();



        $excel1 = new PHPExcel_Worksheet($excel, 'Invoice');



		// Attach the "My Data" worksheet as the first worksheet in the PHPExcel object

		$excel->addSheet($excel1, 0);



		$objDrawing = new PHPExcel_Worksheet_Drawing();

		$excel->setActiveSheetIndex(0);

        $objDrawing->setWorksheet($excel->getActiveSheet());

        $objDrawing->setCoordinates('B1');

        $objDrawing->setName('Header Invoice');

        $objDrawing->setDescription('Sintesa');

        $objDrawing->setPath('assets/img/header_invoice.png');

        $objDrawing->setWidth(160)->setHeight(160);



        $excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('Data Invoice & SJ')

                ->setSubject('Invoice & SJ')

                ->setDescription('Invoice & SJ '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3])

                ->setKeyWords('Invoice & SJ');



        $style_col = [

        	'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'd9e1f2']

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



        $excel->setActiveSheetIndex(0)->setCellValue('G9', $f['no_invoice']);

        $excel->getActiveSheet()->mergeCells('G9:I9');

        $excel->getActiveSheet()->getStyle('G9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(0)->setCellValue('G10', $tanggal);

        $excel->getActiveSheet()->mergeCells('G10:I10');

        $excel->getActiveSheet()->getStyle('G10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(0)->setCellValue('G11', 'Kepada:');

        $excel->getActiveSheet()->mergeCells('G11:I11');

        $excel->getActiveSheet()->getStyle('G11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(0)->setCellValue('G12', 'PT. TKG TAEKWANG INDONESIA Dusun Belendung II RT. 17 RW.06 Desa Belendung Kec. Cibogo, Kab. Subang');

        $excel->getActiveSheet()->mergeCells('G12:I15');

        $excel->getActiveSheet()->getStyle('G12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 

        $excel->getActiveSheet()->getStyle('G12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);   

        $excel->getActiveSheet()->getStyle('G12')->getAlignment()->setWrapText(true);    



        $excel->setActiveSheetIndex(0)->setCellValue('B16', 'NO');

        $excel->setActiveSheetIndex(0)->setCellValue('C16', 'NAMA BARANG');

        $excel->setActiveSheetIndex(0)->setCellValue('D16', 'ITEM CODE');

        $excel->setActiveSheetIndex(0)->setCellValue('E16', 'UOM');

        $excel->setActiveSheetIndex(0)->setCellValue('F16', 'QTY');

        $excel->setActiveSheetIndex(0)->setCellValue('G16', 'COST');

        $excel->setActiveSheetIndex(0)->setCellValue('H16', 'TOTAL');

        $excel->setActiveSheetIndex(0)->setCellValue('I16', 'PO');

        

        $excel->getActiveSheet()->getStyle('B16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('C16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('D16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('E16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('F16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('G16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('H16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('I16')->applyFromArray($style_col);



        $numrow = 17;

        $numrow_last = 17;

        $no = 1;

        $total = 0;

        foreach ($f_detail as $i) {

            $numroww = $numrow+1;

            $numrow_last += $numrow_last+3;

            $total += $i['total_harga_barang'];

            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ($no++));

            $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $i['nama_barang']);

            $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('C'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 

            $excel->getActiveSheet()->getStyle('C'.$numrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);   

            $excel->getActiveSheet()->getStyle('C'.$numrow)->getAlignment()->setWrapText(true);

            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $i['kode_barang']);

            $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.intval($numrow+1));

            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $i['satuan_barang']);

            $excel->getActiveSheet()->mergeCells('E'.$numrow.':E'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('E'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $i['jumlah_barang']);

            $excel->getActiveSheet()->mergeCells('F'.$numrow.':F'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('F'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, number_format($i['harga_barang'], 0,'.',','));

            $excel->getActiveSheet()->mergeCells('G'.$numrow.':G'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('G'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, number_format($i['total_harga_barang'], 0,'.',','));

            $excel->getActiveSheet()->mergeCells('H'.$numrow.':H'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('H'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $i['no_po']);

            $excel->getActiveSheet()->mergeCells('I'.$numrow.':I'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('I'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);



            $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('H'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('I'.$numroww)->applyFromArray($style_row);

            

            $numrow=$numrow+2;

            

        }



        $numrow_terbilang = $numrow+1;

        $terbilang = $this->terbilang($total);



        $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, 'TOTAL');

        $excel->getActiveSheet()->getStyle('G'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('G'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('G'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row_full);

        $excel->getActiveSheet()->getStyle('B'.$numrow.':G'.$numrow)->applyFromArray($style_row_full);



        $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, number_format($total, 0,'.',','));

        $excel->getActiveSheet()->getStyle('H'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('H'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('H'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row_full);

        $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row_full);



        $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, 'TERBILANG : '.strtoupper($terbilang));

        $excel->getActiveSheet()->mergeCells('B'.$numrow.':F'.$numrow);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



        $excel->setActiveSheetIndex(0)->setCellValue('F'.intval($numrow_terbilang+2), 'HORMAT KAMI');

        $excel->getActiveSheet()->getStyle('F'.intval($numrow_terbilang+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('F'.intval($numrow_terbilang+2))->getFont()->setBold(true);

        $excel->getActiveSheet()->mergeCells('F'.intval($numrow_terbilang+2).':I'.intval($numrow_terbilang+2));



        $excel->setActiveSheetIndex(0)->setCellValue('F'.intval($numrow_terbilang+9), 'EKA IMAM MAULANA');

        $excel->getActiveSheet()->getStyle('F'.intval($numrow_terbilang+9))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->mergeCells('F'.intval($numrow_terbilang+9).':I'.intval($numrow_terbilang+9));



        $objDrawing2 = new PHPExcel_Worksheet_Drawing();

        $excel->setActiveSheetIndex(0);

        $objDrawing2->setWorksheet($excel->getActiveSheet());

        $objDrawing2->setCoordinates('B'.intval($numrow_terbilang+10));

        $objDrawing2->setName('Footer Invoice');

        $objDrawing2->setDescription('Sintesa');

        $objDrawing2->setPath('assets/img/footer_surat.png');

        $objDrawing2->setWidth(155)->setHeight(155);



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(43);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(5);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(6);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(13);

        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(26);

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        $excel2 = new PHPExcel_Worksheet($excel, 'Surat Jalan');



		// Attach the "My Data" worksheet as the first worksheet in the PHPExcel object

		$excel->addSheet($excel2, 1);



		$excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('Data Invoice & SJ')

                ->setSubject('Invoice & SJ')

                ->setDescription('Invoice & SJ '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3])

                ->setKeyWords('Invoice & SJ');



        $objDrawing3 = new PHPExcel_Worksheet_Drawing();

        $excel->setActiveSheetIndex(1);

        $objDrawing3->setWorksheet($excel->getActiveSheet());

        $objDrawing3->setCoordinates('B1');

        $objDrawing3->setName('Footer Invoice');

        $objDrawing3->setDescription('Sintesa');

        $objDrawing3->setPath('assets/img/header_surat_jalan.png');

        $objDrawing3->setWidth(195)->setHeight(195);



        $style_col = [

        	'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'd9e1f2']

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



        $style_row_top = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_left = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_right = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

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



        $excel->setActiveSheetIndex(1)->setCellValue('E10', $f['no_invoice']);

        $excel->getActiveSheet()->mergeCells('E10:G10');

        $excel->getActiveSheet()->getStyle('E10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(1)->setCellValue('E11', $tanggal);

        $excel->getActiveSheet()->mergeCells('E11:G11');

        $excel->getActiveSheet()->getStyle('E11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(1)->setCellValue('E12', 'Kepada:');

        $excel->getActiveSheet()->mergeCells('E12:G12');

        $excel->getActiveSheet()->getStyle('E12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(1   )->setCellValue('E13', 'PT. TKG TAEKWANG INDONESIA Dusun Belendung II RT. 17 RW.06 Desa Belendung Kec. Cibogo, Kab. Subang');

        $excel->getActiveSheet()->mergeCells('E13:G15');

        $excel->getActiveSheet()->getStyle('E13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 

        $excel->getActiveSheet()->getStyle('E13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);   

        $excel->getActiveSheet()->getStyle('E13')->getAlignment()->setWrapText(true);    



        $excel->setActiveSheetIndex(1)->setCellValue('B16', 'NO');

        $excel->setActiveSheetIndex(1)->setCellValue('C16', 'NAMA BARANG');

        $excel->setActiveSheetIndex(1)->setCellValue('D16', 'ITEM CODE');

        $excel->setActiveSheetIndex(1)->setCellValue('E16', 'UOM');

        $excel->setActiveSheetIndex(1)->setCellValue('F16', 'QTY');

        $excel->setActiveSheetIndex(1)->setCellValue('G16', 'PO');

        

        $excel->getActiveSheet()->getStyle('B16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('C16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('D16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('E16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('F16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('G16')->applyFromArray($style_col);



        $numrow = 17;

        $numrow_last = 17;

        $no = 1;

        $total = 0;

        foreach ($f_detail as $i) {

            $numroww = $numrow+1;

            $numrow_last += $numrow_last+3;

            $total += $i['total_harga_barang'];

            $excel->setActiveSheetIndex(1)->setCellValue('B'.$numrow, ($no++));

            $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.$numroww);

            $excel->setActiveSheetIndex(1)->setCellValue('C'.$numrow, $i['nama_barang']);

            $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.$numroww);

            $excel->setActiveSheetIndex(1)->setCellValue('D'.$numrow, $i['kode_barang']);

            $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.$numroww);

            $excel->setActiveSheetIndex(1)->setCellValue('E'.$numrow, $i['satuan_barang']);

            $excel->getActiveSheet()->getStyle('E'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('E'.$numrow.':E'.$numroww);

            

            

            $excel->setActiveSheetIndex(1)->setCellValue('F'.$numrow, $i['jumlah_barang']);

            $excel->getActiveSheet()->getStyle('F'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('F'.$numrow.':F'.$numroww);

            

            $excel->setActiveSheetIndex(1)->setCellValue('G'.$numrow, $i['no_po']);

            $excel->getActiveSheet()->getStyle('G'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('G'.$numrow.':G'.$numroww);



            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);



            $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row);

            

            $numrow=$numrow+2;

            

        }



        $numrow_ttd = $numrow+1;





        $excel->setActiveSheetIndex(1)->setCellValue('B'.$numrow);

        $excel->getActiveSheet()->mergeCells('B'.$numrow.':G'.$numrow);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $excel->getActiveSheet()->getStyle('B'.$numrow.':G'.$numrow)->applyFromArray($style_row_top);



        $excel->setActiveSheetIndex(1)->setCellValue('B'.$numrow_ttd, 'HORMAT KAMI');

        $excel->setActiveSheetIndex(1)->setCellValue('D'.$numrow_ttd, 'PENERIMA');

        $excel->getActiveSheet()->mergeCells('B'.$numrow_ttd.':C'.$numrow_ttd);

        $excel->getActiveSheet()->mergeCells('D'.$numrow_ttd.':G'.$numrow_ttd);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd.':C'.$numrow_ttd)->applyFromArray($style_row_top);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd.':C'.$numrow_ttd)->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd.':C'.$numrow_ttd)->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd.':G'.$numrow_ttd)->applyFromArray($style_row_top);

        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd.':G'.$numrow_ttd)->applyFromArray($style_row_right);



        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+1))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+1))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+1))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+2))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+2))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+2))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+3))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+3))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+3))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+4))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+4))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+4))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+5))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+5))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+5))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->mergeCells('B'.intval($numrow_ttd+6).':C'.intval($numrow_ttd+6));

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+7).':C'.intval($numrow_ttd+7))->applyFromArray($style_row_top);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+6).':C'.intval($numrow_ttd+6))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+6).':C'.intval($numrow_ttd+6))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('D'.intval($numrow_ttd+7).':G'.intval($numrow_ttd+7))->applyFromArray($style_row_top);

        $excel->getActiveSheet()->getStyle('D'.intval($numrow_ttd+6).':G'.intval($numrow_ttd+6))->applyFromArray($style_row_right);

        $excel->setActiveSheetIndex(1)->setCellValue('B'.intval($numrow_ttd+6), 'EKA IMAM MAULANA');

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+6))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



        $objDrawing3 = new PHPExcel_Worksheet_Drawing();

        $excel->setActiveSheetIndex(1);

        $objDrawing3->setWorksheet($excel->getActiveSheet());

        $objDrawing3->setCoordinates('B'.intval($numrow_ttd+9));

        $objDrawing3->setName('Footer Invoice');

        $objDrawing3->setDescription('Sintesa');

        $objDrawing3->setPath('assets/img/footer_surat.png');

        $objDrawing3->setWidth(150)->setHeight(150);



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(1);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(4);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(45);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(8);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(8);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(26);

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="Invoice $ SJ ' .$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3]. '.xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');



        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

    }



    public function export_excel_bc_cs($id_bc_cs)

    {

        include_once APPPATH . 'third_party/PHPExcel.php';

        $bc 				= $this->M_cs->get_bc_by_id($id_bc_cs);

		$pch_tgl			= explode('-', date('d-F-Y', strtotime($bc['tanggal'])));

		$bulan 				= $this->bulan($pch_tgl[1]);

		$tanggal 			= date('d', strtotime($bc['tanggal'])).' '.$bulan.' '.date('Y', strtotime($bc['tanggal']));

		$bc_detail			= $this->M_cs->get_detail_bc($id_bc_cs)->result_array();



        $excel = new PHPExcel();



        $excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('Data BC')

                ->setSubject('BC')

                ->setDescription('BC '.$tanggal)

                ->setKeyWords('BC');



        $style_col = [

        	'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'ffff00']

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

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_j = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

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



        $excel->setActiveSheetIndex(0)->setCellValue('A2', 'NO');

        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'NAMA VENDOR');

        $excel->setActiveSheetIndex(0)->setCellValue('C2', 'TGL SJ');

        $excel->setActiveSheetIndex(0)->setCellValue('D2', 'NO SJ');

        $excel->setActiveSheetIndex(0)->setCellValue('E2', 'TGL PO');

        $excel->setActiveSheetIndex(0)->setCellValue('F2', 'NO PO');

        $excel->setActiveSheetIndex(0)->setCellValue('G2', 'NO PACKING LIST');

        $excel->setActiveSheetIndex(0)->setCellValue('H2', 'NO INVOICE');

        $excel->setActiveSheetIndex(0)->setCellValue('I2', 'ITEM CODE');

        $excel->setActiveSheetIndex(0)->setCellValue('J2', 'DESCRIPTION');

        $excel->setActiveSheetIndex(0)->setCellValue('K2', 'Kode Satuan');

        $excel->setActiveSheetIndex(0)->setCellValue('L2', 'QTY');

        $excel->setActiveSheetIndex(0)->setCellValue('M2', 'PACKAGING');

        $excel->setActiveSheetIndex(0)->setCellValue('N2', 'NPWP');

        

        $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('C2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('D2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('E2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('F2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('G2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('H2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('I2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('J2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('K2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('L2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('M2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('N2')->applyFromArray($style_col);



        $numrow = 3;

        $numrow_last = 3;

        $no = 1;

        $total = 0;

        foreach ($bc_detail as $i) {

        	$invoice = $this->db->get_where('cs', ['id_cs' => $i['id_cs']])->row_array();

        	$d_invoice = $this->db->get_where('detail_cs', ['id_cs' => $i['id_cs']])->result_array();

        	$no_sub = 3;

        	foreach ($d_invoice as $inv) {

        		$no_sub += $no_sub+1;

        		$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ($no++));

	            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $bc['nama_vendor']);

	            $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.intval($numrow+2));

	            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $bc['tanggal']);

	            $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numrow+2));

	            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $invoice['no_invoice']);

	            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $invoice['tanggal']);

	            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $inv['no_po']);

	            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $invoice['no_invoice']);

	            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $invoice['no_invoice']);

	            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $inv['kode_barang']);

	            $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $inv['nama_barang']);

	            $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $inv['satuan_barang']);

	            $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $inv['jumlah_barang']);

	            $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, 'Pack');

	            $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $bc['npwp']);

	            $excel->getActiveSheet()->mergeCells('N'.$numrow.':N'.intval($numrow+2));



	            $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row_j);

	            $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);

	            

	            $numrow=$numrow+1;

        	}

            

            

        }



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(3.14);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(26.14);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17.71);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(12.41);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20.71);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(12.57); 

        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(75.86);

        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(11.14);

        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);

        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(11.14);

        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(20.86);



        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="BC ' .$tanggal.'.xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');



        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

    }



    public function cetak_invoice_fabrikasi($id_fabrikasi, $mode = null)

	{

		$this->load->library('pdf');

		$data['title']		= 'Fabrikasi';

        $data['title2']		= 'Invoice';

		$data['f']			= $this->M_fabrikasi->get_by_id($id_fabrikasi);

		$inv				= explode('/', $data['f']['no_invoice']);

		$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));

		$bulan 				= $this->bulan($pch_tgl[1]);

		$data['tanggal']	= date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));

		$data['f_detail']	= $this->M_fabrikasi->get_detail($id_fabrikasi)->result_array();

		$data['mode']       = $mode;



		

        // if($mode == 'rm'){

        //     if($data['f_detail'][0]['harga_unit'] == NULL || $data['f_detail'][0]['harga_unit'] == 0){

        //         $html_content = $this->load->view('fabrikasi/cetak_invoice', $data, true);

        //     } else {

        //         $html_content = $this->load->view('fabrikasi/cetak_invoice_instalasi', $data, true);

        //     }  

        // }elseif($mode == 'install'){

        //     if($data['f_detail'][0]['harga_unit'] == NULL || $data['f_detail'][0]['harga_unit'] == 0){

        //         $html_content = $this->load->view('fabrikasi/cetak_invoice', $data, true);

        //     } else {

        //         $html_content = $this->load->view('fabrikasi/cetak_invoice_instalasi', $data, true);

        //     }   

        // }else{

        //     if($data['f_detail'][0]['harga_unit'] == NULL || $data['f_detail'][0]['harga_unit'] == 0){

        //         $html_content = $this->load->view('fabrikasi/cetak_invoice', $data, true);

        //     } else {

        //         $html_content = $this->load->view('fabrikasi/cetak_invoice_instalasi', $data, true);

        //     }  

        // }

        if($data['f_detail'][0]['harga_unit'] == NULL || $data['f_detail'][0]['harga_unit'] == 0){

            $html_content = $this->load->view('fabrikasi/cetak_invoice', $data, true);

        } else {

            $html_content = $this->load->view('fabrikasi/cetak_invoice_instalasi', $data, true);

        }  

		// $html_content = $this->load->view('fabrikasi/cetak_invoice', $data, true);

        

        $filename = 'Invoice - '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3].'.pdf';



        $this->pdf->loadHtml($html_content);



        $this->pdf->set_paper('a4','potrait');

        

        $this->pdf->render();

        $this->pdf->stream($filename, ['Attachment' => 1]);

	}



	public function cetak_surat_jalan_fabrikasi($id_fabrikasi, $mode = null)

	{

		$this->load->library('pdf');

		$data['title']		= 'Fabrikasi';

        $data['title2']		= 'Surat Jalan';

		$data['f']			= $this->M_fabrikasi->get_by_id($id_fabrikasi);

		$inv				= explode('/', $data['f']['no_invoice']);

		$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));

		$bulan 				= $this->bulan($pch_tgl[1]);

		$data['tanggal']	= date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));

		$data['f_detail']	= $this->M_fabrikasi->get_detail($id_fabrikasi)->result_array();

		

        if($mode == 'aj'){

            $html_content = $this->load->view('fabrikasi/cetak_surat_jalan_aj', $data, true);

        }else{

            $html_content = $this->load->view('fabrikasi/cetak_surat_jalan', $data, true);

        }



        $filename = 'Surat Jalan - '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3].'.pdf';



        $this->pdf->loadHtml($html_content);



        $this->pdf->set_paper('a4','potrait');

        

        $this->pdf->render();

        $this->pdf->stream($filename, ['Attachment' => 1]);

	}



	public function export_excel_invoice_fabrikasi($id_fabrikasi)

    {

        include_once APPPATH . 'third_party/PHPExcel.php';

        $f 					= $this->M_fabrikasi->get_by_id($id_fabrikasi);

		$inv				= explode('/', $f['no_invoice']);

		$pch_tgl			= explode('-', date('d-F-Y', strtotime($f['tanggal'])));

		$bulan 				= $this->bulan($pch_tgl[1]);

		$tanggal 			= date('d', strtotime($f['tanggal'])).' '.$bulan.' '.date('Y', strtotime($f['tanggal']));

		$f_detail 			= $this->M_fabrikasi->get_detail($id_fabrikasi)->result_array();



        $excel = new PHPExcel();



        $excel1 = new PHPExcel_Worksheet($excel, 'Invoice');



		// Attach the "My Data" worksheet as the first worksheet in the PHPExcel object

		$excel->addSheet($excel1, 0);



		$objDrawing = new PHPExcel_Worksheet_Drawing();

		$excel->setActiveSheetIndex(0);

        $objDrawing->setWorksheet($excel->getActiveSheet());

        $objDrawing->setCoordinates('B1');

        $objDrawing->setName('Header Invoice');

        $objDrawing->setDescription('Sintesa');

        $objDrawing->setPath('assets/img/header_invoice.png');

        $objDrawing->setWidth(160)->setHeight(160);



        $excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('Data Invoice & SJ')

                ->setSubject('Invoice & SJ')

                ->setDescription('Invoice & SJ '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3])

                ->setKeyWords('Invoice & SJ');



        $style_col = [

        	'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'd9e1f2']

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



        $excel->setActiveSheetIndex(0)->setCellValue('J9', $f['no_invoice']);

        $excel->getActiveSheet()->mergeCells('J9:L9');

        $excel->getActiveSheet()->getStyle('J9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(0)->setCellValue('J10', $tanggal);

        $excel->getActiveSheet()->mergeCells('J10:L10');

        $excel->getActiveSheet()->getStyle('J10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(0)->setCellValue('J11', 'Kepada:');

        $excel->getActiveSheet()->mergeCells('J11:L11');

        $excel->getActiveSheet()->getStyle('J11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(0)->setCellValue('J12', 'PT. TKG TAEKWANG INDONESIA Dusun Belendung II RT. 17 RW.06 Desa Belendung Kec. Cibogo, Kab. Subang');

        $excel->getActiveSheet()->mergeCells('J12:L15');

        $excel->getActiveSheet()->getStyle('J12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 

        $excel->getActiveSheet()->getStyle('J12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);   

        $excel->getActiveSheet()->getStyle('J12')->getAlignment()->setWrapText(true);    



        $excel->setActiveSheetIndex(0)->setCellValue('B16', 'NO');

        $excel->setActiveSheetIndex(0)->setCellValue('C16', 'NAMA BARANG');

        $excel->setActiveSheetIndex(0)->setCellValue('D16', 'ITEM CODE');

        $excel->setActiveSheetIndex(0)->setCellValue('E16', 'QTY');

        $excel->setActiveSheetIndex(0)->setCellValue('F16', 'UOM');

        $excel->setActiveSheetIndex(0)->setCellValue('G16', 'PRICE (IDR)');

        $excel->setActiveSheetIndex(0)->setCellValue('G17', 'RAW MATERIAL');

        $excel->setActiveSheetIndex(0)->setCellValue('H17', 'INSTALATION');

        $excel->setActiveSheetIndex(0)->setCellValue('I16', 'UNIT PRICE (IDR');

        $excel->setActiveSheetIndex(0)->setCellValue('I17', 'RAW MATERIAL');

        $excel->setActiveSheetIndex(0)->setCellValue('J17', 'INSTALATION');

        $excel->setActiveSheetIndex(0)->setCellValue('K16', 'TOTAL');

        $excel->setActiveSheetIndex(0)->setCellValue('L16', 'PO');

        

        $excel->getActiveSheet()->mergeCells('B16:B17');

        $excel->getActiveSheet()->getStyle('B16:B17')->applyFromArray($style_col);

        $excel->getActiveSheet()->mergeCells('C16:C17');

        $excel->getActiveSheet()->getStyle('C16:C17')->applyFromArray($style_col);

        $excel->getActiveSheet()->mergeCells('D16:D17');

        $excel->getActiveSheet()->getStyle('D16:D17')->applyFromArray($style_col);

        $excel->getActiveSheet()->mergeCells('E16:E17');

        $excel->getActiveSheet()->getStyle('E16:E17')->applyFromArray($style_col);

        $excel->getActiveSheet()->mergeCells('F16:F17');

        $excel->getActiveSheet()->getStyle('F16:F17')->applyFromArray($style_col);

        $excel->getActiveSheet()->mergeCells('G16:H16');

        $excel->getActiveSheet()->getStyle('G16:H16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('G17')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('H17')->applyFromArray($style_col);

        $excel->getActiveSheet()->mergeCells('I16:J16');

        $excel->getActiveSheet()->getStyle('I16:J16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('I17')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('J17')->applyFromArray($style_col);

        $excel->getActiveSheet()->mergeCells('K16:K17');

        $excel->getActiveSheet()->getStyle('K16:K17')->applyFromArray($style_col);

        $excel->getActiveSheet()->mergeCells('L16:L17');

        $excel->getActiveSheet()->getStyle('L16:L17')->applyFromArray($style_col);



        $numrow = 18;

        $numrow_last = 18;

        $no = 1;

        $total = 0;

        $total_instalasi=0;

        $total_unit=0;

        $total_instalasi_jml=0;

        $total_unit_jml=0;

        foreach ($f_detail as $i) {

        	$numroww = $numrow+1;

        	$numrow_last += $numrow_last+3;

        	$total += $i['total_harga_barang'];

            $total_instalasi += $i['harga_unit'];

            $total_unit += $i['harga_instalasi'];

            $total_instalasi_jml += $i['harga_unit']*$i['jumlah_barang'];

            $total_unit_jml += $i['harga_instalasi']*$i['jumlah_barang'];



            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ($no++));

            $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            

            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $i['nama_barang']);

            $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('C'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 

            $excel->getActiveSheet()->getStyle('C'.$numrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);   

            $excel->getActiveSheet()->getStyle('C'.$numrow)->getAlignment()->setWrapText(true);

            

            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $i['kode_barang']);

            $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.intval($numrow+1));

            

            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $i['jumlah_barang']);

            $excel->getActiveSheet()->mergeCells('E'.$numrow.':E'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('E'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            

            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $i['satuan_barang']);

            $excel->getActiveSheet()->mergeCells('F'.$numrow.':F'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('F'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            

            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, number_format($i['harga_unit'], 0,'.',','));

            $excel->getActiveSheet()->mergeCells('G'.$numrow.':G'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('G'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            

            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, number_format($i['harga_instalasi'], 0,'.',','));

            $excel->getActiveSheet()->mergeCells('H'.$numrow.':H'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('H'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            

            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, number_format($i['harga_unit']*$i['jumlah_barang'], 0,'.',','));

            $excel->getActiveSheet()->mergeCells('I'.$numrow.':I'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('I'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            

            $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, number_format($i['harga_instalasi']*$i['jumlah_barang'], 0,'.',','));

            $excel->getActiveSheet()->mergeCells('J'.$numrow.':J'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('J'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



            $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, number_format($i['total_harga_barang'], 0,'.',','));

            $excel->getActiveSheet()->mergeCells('K'.$numrow.':K'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('K'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            

            $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $i['no_po']);

            $excel->getActiveSheet()->mergeCells('L'.$numrow.':L'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('L'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);



            $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('H'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('I'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('J'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('K'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('L'.$numroww)->applyFromArray($style_row);

            

            $numrow=$numrow+2;

            

        }



        $numrow_terbilang = $numrow+1;

        $terbilang = $this->terbilang($total);



        $excel->setActiveSheetIndex(0)->setCellValue('B'.intval($numrow), 'TOTAL');

        $excel->getActiveSheet()->mergeCells('B'.intval($numrow).':F'.intval($numrow));

        $excel->getActiveSheet()->getStyle('B'.intval($numrow))->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow))->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow).':F'.intval($numrow))->applyFromArray($style_row_full);



        $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, number_format($total_unit, 0,'.',','));

        $excel->getActiveSheet()->getStyle('G'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('G'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('G'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row_full);



        $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, number_format($total_instalasi, 0,'.',','));

        $excel->getActiveSheet()->getStyle('H'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('H'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('H'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row_full);



        $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, number_format($total_unit, 0,'.',','));

        $excel->getActiveSheet()->getStyle('I'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('I'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('I'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row_full);



        $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, number_format($total_unit_jml, 0,'.',','));

        $excel->getActiveSheet()->getStyle('J'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('J'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('J'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row_full);



        $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, number_format($total, 0,'.',','));

        $excel->getActiveSheet()->getStyle('K'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('K'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('K'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row_full);

        $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row_full);



        $excel->setActiveSheetIndex(0)->setCellValue('B'.intval($numrow+1), 'TERBILANG : '.strtoupper($terbilang));

        $excel->getActiveSheet()->mergeCells('B'.intval($numrow+1).':L'.intval($numrow+1));

        $excel->getActiveSheet()->getStyle('B'.intval($numrow+1))->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow+1))->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow+1).':L'.intval($numrow+1))->applyFromArray($style_row_full);



        $excel->setActiveSheetIndex(0)->setCellValue('H'.intval($numrow_terbilang+3), 'HORMAT KAMI');

        $excel->getActiveSheet()->getStyle('H'.intval($numrow_terbilang+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('H'.intval($numrow_terbilang+3))->getFont()->setBold(true);

        $excel->getActiveSheet()->mergeCells('H'.intval($numrow_terbilang+3).':K'.intval($numrow_terbilang+3));



        $excel->setActiveSheetIndex(0)->setCellValue('H'.intval($numrow_terbilang+10), 'EKA IMAM MAULANA');

        $excel->getActiveSheet()->getStyle('H'.intval($numrow_terbilang+10))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->mergeCells('H'.intval($numrow_terbilang+10).':K'.intval($numrow_terbilang+10));



        $objDrawing2 = new PHPExcel_Worksheet_Drawing();

        $excel->setActiveSheetIndex(0);

        $objDrawing2->setWorksheet($excel->getActiveSheet());

        $objDrawing2->setCoordinates('B'.intval($numrow_terbilang+11));

        $objDrawing2->setName('Footer Invoice');

        $objDrawing2->setDescription('Sintesa');

        $objDrawing2->setPath('assets/img/footer_surat.png');

        $objDrawing2->setWidth(155)->setHeight(155);



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(43);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(5);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(6);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(13);

        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(26);

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        $excel2 = new PHPExcel_Worksheet($excel, 'Surat Jalan');



		// Attach the "My Data" worksheet as the first worksheet in the PHPExcel object

		$excel->addSheet($excel2, 1);



		$excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('Data Invoice & SJ')

                ->setSubject('Invoice & SJ')

                ->setDescription('Invoice & SJ '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3])

                ->setKeyWords('Invoice & SJ');



        $objDrawing3 = new PHPExcel_Worksheet_Drawing();

        $excel->setActiveSheetIndex(1);

        $objDrawing3->setWorksheet($excel->getActiveSheet());

        $objDrawing3->setCoordinates('B1');

        $objDrawing3->setName('Footer Invoice');

        $objDrawing3->setDescription('Sintesa');

        $objDrawing3->setPath('assets/img/header_surat_jalan.png');

        $objDrawing3->setWidth(195)->setHeight(195);



        $style_col = [

        	'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'd9e1f2']

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



        $style_row_top = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_left = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_right = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

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



        $excel->setActiveSheetIndex(1)->setCellValue('E10', $f['no_invoice']);

        $excel->getActiveSheet()->mergeCells('E10:G10');

        $excel->getActiveSheet()->getStyle('E10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(1)->setCellValue('E11', $tanggal);

        $excel->getActiveSheet()->mergeCells('E11:G11');

        $excel->getActiveSheet()->getStyle('E11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(1)->setCellValue('E12', 'Kepada:');

        $excel->getActiveSheet()->mergeCells('E12:G12');

        $excel->getActiveSheet()->getStyle('E12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(1   )->setCellValue('E13', 'PT. TKG TAEKWANG INDONESIA Dusun Belendung II RT. 17 RW.06 Desa Belendung Kec. Cibogo, Kab. Subang');

        $excel->getActiveSheet()->mergeCells('E13:G15');

        $excel->getActiveSheet()->getStyle('E13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 

        $excel->getActiveSheet()->getStyle('E13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);   

        $excel->getActiveSheet()->getStyle('E13')->getAlignment()->setWrapText(true);    



        $excel->setActiveSheetIndex(1)->setCellValue('B16', 'NO');

        $excel->setActiveSheetIndex(1)->setCellValue('C16', 'NAMA BARANG');

        $excel->setActiveSheetIndex(1)->setCellValue('D16', 'ITEM CODE');

        $excel->setActiveSheetIndex(1)->setCellValue('E16', 'UOM');

        $excel->setActiveSheetIndex(1)->setCellValue('F16', 'QTY');

        $excel->setActiveSheetIndex(1)->setCellValue('G16', 'PO');

        

        $excel->getActiveSheet()->getStyle('B16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('C16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('D16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('E16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('F16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('G16')->applyFromArray($style_col);



        $numrow = 17;

        $numrow_last = 17;

        $no = 1;

        $total = 0;

        foreach ($f_detail as $i) {

        	$numroww = $numrow+1;

        	$numrow_last += $numrow_last+3;

        	$total += $i['total_harga_barang'];

            $excel->setActiveSheetIndex(1)->setCellValue('B'.$numrow, ($no++));

            $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.$numroww);

            $excel->setActiveSheetIndex(1)->setCellValue('C'.$numrow, $i['nama_barang']);

            $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.$numroww);

            $excel->setActiveSheetIndex(1)->setCellValue('D'.$numrow, $i['kode_barang']);

            $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.$numroww);

            $excel->setActiveSheetIndex(1)->setCellValue('E'.$numrow, $i['satuan_barang']);

            $excel->getActiveSheet()->getStyle('E'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('E'.$numrow.':E'.$numroww);

            

            

            $excel->setActiveSheetIndex(1)->setCellValue('F'.$numrow, $i['jumlah_barang']);

            $excel->getActiveSheet()->getStyle('F'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('F'.$numrow.':F'.$numroww);

            

            $excel->setActiveSheetIndex(1)->setCellValue('G'.$numrow, $i['no_po']);

            $excel->getActiveSheet()->getStyle('G'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('G'.$numrow.':G'.$numroww);



            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);



            $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row);

            

            $numrow=$numrow+2;

            

        }



        $numrow_ttd = $numrow+1;





        $excel->setActiveSheetIndex(1)->setCellValue('B'.$numrow);

        $excel->getActiveSheet()->mergeCells('B'.$numrow.':G'.$numrow);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $excel->getActiveSheet()->getStyle('B'.$numrow.':G'.$numrow)->applyFromArray($style_row_top);



        $excel->setActiveSheetIndex(1)->setCellValue('B'.$numrow_ttd, 'HORMAT KAMI');

        $excel->setActiveSheetIndex(1)->setCellValue('D'.$numrow_ttd, 'PENERIMA');

        $excel->getActiveSheet()->mergeCells('B'.$numrow_ttd.':C'.$numrow_ttd);

        $excel->getActiveSheet()->mergeCells('D'.$numrow_ttd.':G'.$numrow_ttd);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd.':C'.$numrow_ttd)->applyFromArray($style_row_top);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd.':C'.$numrow_ttd)->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd.':C'.$numrow_ttd)->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd.':G'.$numrow_ttd)->applyFromArray($style_row_top);

        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd.':G'.$numrow_ttd)->applyFromArray($style_row_right);



        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+1))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+1))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+1))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+2))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+2))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+2))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+3))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+3))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+3))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+4))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+4))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+4))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+5))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+5))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+5))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->mergeCells('B'.intval($numrow_ttd+6).':C'.intval($numrow_ttd+6));

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+7).':C'.intval($numrow_ttd+7))->applyFromArray($style_row_top);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+6).':C'.intval($numrow_ttd+6))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+6).':C'.intval($numrow_ttd+6))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('D'.intval($numrow_ttd+7).':G'.intval($numrow_ttd+7))->applyFromArray($style_row_top);

        $excel->getActiveSheet()->getStyle('D'.intval($numrow_ttd+6).':G'.intval($numrow_ttd+6))->applyFromArray($style_row_right);

        $excel->setActiveSheetIndex(1)->setCellValue('B'.intval($numrow_ttd+6), 'EKA IMAM MAULANA');

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+6))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



        $objDrawing3 = new PHPExcel_Worksheet_Drawing();

        $excel->setActiveSheetIndex(1);

        $objDrawing3->setWorksheet($excel->getActiveSheet());

        $objDrawing3->setCoordinates('B'.intval($numrow_ttd+9));

        $objDrawing3->setName('Footer Invoice');

        $objDrawing3->setDescription('Sintesa');

        $objDrawing3->setPath('assets/img/footer_surat.png');

        $objDrawing3->setWidth(150)->setHeight(150);



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(1);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(4);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(45);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(8);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(8);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(26);

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="Invoice $ SJ ' .$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3]. '.xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');



        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

    }



    // public function export_excel_bc_fabrikasi($id_bc_fabrikasi)

    // {

    //     include_once APPPATH . 'third_party/PHPExcel.php';

    //     $bc 				= $this->M_fabrikasi->get_bc_by_id($id_bc_fabrikasi);

	// 	$pch_tgl			= explode('-', date('d-F-Y', strtotime($bc['tanggal'])));

	// 	$bulan 				= $this->bulan($pch_tgl[1]);

	// 	$tanggal 			= date('d', strtotime($bc['tanggal'])).' '.$bulan.' '.date('Y', strtotime($bc['tanggal']));

	// 	$bc_detail			= $this->M_fabrikasi->get_detail_bc($id_bc_fabrikasi)->result_array();



    //     $excel = new PHPExcel();



    //     $excel->getProperties()

    //             ->setCreator('IndoExpress')

    //             ->setLastModifiedBy('IndoExpress')

    //             ->setTitle('Data BC')

    //             ->setSubject('BC')

    //             ->setDescription('BC '.$tanggal)

    //             ->setKeyWords('BC');



    //     $style_col = [

    //     	'fill' => [

    //             'type' => PHPExcel_Style_Fill::FILL_SOLID,

    //             'color' => ['rgb' => 'ffff00']

    //         ],

    //         'font' => ['bold' => true],

    //         'alignment' => [

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ],

    //         'borders' => [

    //             'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $style_row = [

    //         'alignment' => [

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

    //         ],

    //         'borders' => [

    //             'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $style_row_j = [

    //         'alignment' => [

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

    //         ],

    //         'borders' => [

    //             'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $style_row_full = [

    //         'alignment' => [

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ],

    //         'borders' => [

    //             'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $excel->setActiveSheetIndex(0)->setCellValue('A2', 'NO');

    //     $excel->setActiveSheetIndex(0)->setCellValue('B2', 'NAMA VENDOR');

    //     $excel->setActiveSheetIndex(0)->setCellValue('C2', 'TGL SJ');

    //     $excel->setActiveSheetIndex(0)->setCellValue('D2', 'NO SJ');

    //     $excel->setActiveSheetIndex(0)->setCellValue('E2', 'TGL PO');

    //     $excel->setActiveSheetIndex(0)->setCellValue('F2', 'NO PO');

    //     $excel->setActiveSheetIndex(0)->setCellValue('G2', 'NO PACKING LIST');

    //     $excel->setActiveSheetIndex(0)->setCellValue('H2', 'NO INVOICE');

    //     $excel->setActiveSheetIndex(0)->setCellValue('I2', 'ITEM CODE');

    //     $excel->setActiveSheetIndex(0)->setCellValue('J2', 'DESCRIPTION');

    //     $excel->setActiveSheetIndex(0)->setCellValue('K2', 'Kode Satuan');

    //     $excel->setActiveSheetIndex(0)->setCellValue('L2', 'QTY');

    //     $excel->setActiveSheetIndex(0)->setCellValue('M2', 'PACKAGING');

    //     $excel->setActiveSheetIndex(0)->setCellValue('N2', 'NPWP');

        

    //     $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('C2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('D2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('E2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('F2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('G2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('H2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('I2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('J2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('K2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('L2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('M2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('N2')->applyFromArray($style_col);



    //     $numrow = 3;

    //     $numrow_last = 3;

    //     $no = 1;

    //     $total = 0;

    //     foreach ($bc_detail as $i) {

    //     	$invoice = $this->db->get_where('fabrikasi', ['id_fabrikasi' => $i['id_fabrikasi']])->row_array();

    //     	$d_invoice = $this->db->get_where('detail_fabrikasi', ['id_fabrikasi' => $i['id_fabrikasi']])->result_array();

    //     	$no_sub = 3;

    //     	foreach ($d_invoice as $inv) {

    //     		$no_sub += $no_sub+1;

    //     		$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ($no++));

	//             $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $bc['nama_vendor']);

	//             $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.intval($numrow+2));

	//             $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $bc['tanggal']);

	//             $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numrow+2));

	//             $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $invoice['no_invoice']);

	//             $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $invoice['tanggal']);

	//             $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $inv['no_po']);

	//             $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $invoice['no_invoice']);

	//             $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $invoice['no_invoice']);

	//             $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $inv['kode_barang']);

	//             $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $inv['nama_barang']);

	//             $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $inv['satuan_barang']);

	//             $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $inv['jumlah_barang']);

	//             $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, 'Pack');

	//             $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $bc['npwp']);

	//             $excel->getActiveSheet()->mergeCells('N'.$numrow.':N'.intval($numrow+2));



	//             $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);

	//             $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

	//             $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

	//             $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

	//             $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

	//             $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

	//             $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);

	//             $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);

	//             $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);

	//             $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row_j);

	//             $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);

	//             $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);

	//             $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);

	//             $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);

	            

	//             $numrow=$numrow+1;

    //     	}

            

            

    //     }



    //     $excel->getActiveSheet()->getColumnDimension('A')->setWidth(3.14);

    //     $excel->getActiveSheet()->getColumnDimension('B')->setWidth(26.14);

    //     $excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);

    //     $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17.71);

    //     $excel->getActiveSheet()->getColumnDimension('E')->setWidth(12.41);

    //     $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20.71);

    //     $excel->getActiveSheet()->getColumnDimension('G')->setWidth(16.43);

    //     $excel->getActiveSheet()->getColumnDimension('H')->setWidth(16.43);

    //     $excel->getActiveSheet()->getColumnDimension('I')->setWidth(12.57); 

    //     $excel->getActiveSheet()->getColumnDimension('J')->setWidth(75.86);

    //     $excel->getActiveSheet()->getColumnDimension('K')->setWidth(11.14);

    //     $excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);

    //     $excel->getActiveSheet()->getColumnDimension('M')->setWidth(11.14);

    //     $excel->getActiveSheet()->getColumnDimension('N')->setWidth(20.86);



    //     $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    //     header('Content-Disposition: attachment; filename="BC ' .$tanggal.'.xlsx"'); // Set nama file excel nya

    //     header('Cache-Control: max-age=0');



    //     $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

    //     $write->save('php://output');

    // }



    public function export_excel_bc_fabrikasi($id_bc_fabrikasi, $cabang)

    {

        $cab = $this->M_cabang->get_by('slug', $cabang);

        include_once APPPATH . 'third_party/PHPExcel.php';

        $bc 				= $this->M_fabrikasi->get_bc_by_id($id_bc_fabrikasi);

		$pch_tgl			= explode('-', date('d-F-Y', strtotime($bc['tanggal'])));

		$bulan 				= $this->bulan($pch_tgl[1]);

		$tanggal 			= date('d', strtotime($bc['tanggal'])).' '.$bulan.' '.date('Y', strtotime($bc['tanggal']));

		$bc_detail			= $this->M_fabrikasi->get_detail_bc($id_bc_fabrikasi, $cab['id_cabang'])->result_array();



        $excel = new PHPExcel();



        $excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('Data BC')

                ->setSubject('BC')

                ->setDescription('BC '.$tanggal)

                ->setKeyWords('BC');



        $style_col = [

        	'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'ffff00']

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

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_j = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

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



        $excel->setActiveSheetIndex(0)->setCellValue('A2', 'NO');

        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'NAMA VENDOR');

        $excel->setActiveSheetIndex(0)->setCellValue('C2', 'TGL SJ');

        $excel->setActiveSheetIndex(0)->setCellValue('D2', 'NO SJ');

        $excel->setActiveSheetIndex(0)->setCellValue('E2', 'TGL PO');

        $excel->setActiveSheetIndex(0)->setCellValue('F2', 'NO PO');

        $excel->setActiveSheetIndex(0)->setCellValue('G2', 'NO PACKING LIST');

        $excel->setActiveSheetIndex(0)->setCellValue('H2', 'NO INVOICE');

        $excel->setActiveSheetIndex(0)->setCellValue('I2', 'ITEM CODE');

        $excel->setActiveSheetIndex(0)->setCellValue('J2', 'DESCRIPTION');

        $excel->setActiveSheetIndex(0)->setCellValue('K2', 'Kode Satuan');

        $excel->setActiveSheetIndex(0)->setCellValue('L2', 'QTY');

        $excel->setActiveSheetIndex(0)->setCellValue('M2', 'PACKAGING');

        $excel->setActiveSheetIndex(0)->setCellValue('N2', 'NPWP');

        

        $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('C2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('D2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('E2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('F2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('G2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('H2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('I2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('J2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('K2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('L2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('M2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('N2')->applyFromArray($style_col);



        $numrow = 3;

        $numrow_last = 3;

        $no = 1;

        $total = 0;

        foreach ($bc_detail as $i) {

        	$invoice = $this->db->get_where('fabrikasi', ['id_fabrikasi' => $i['id_fabrikasi']])->row_array();

        	$d_invoice = $this->db->get_where('detail_fabrikasi', ['id_fabrikasi' => $i['id_fabrikasi'], 'id_cabang' => $cab['id_cabang']])->result_array();

        	$no_sub = 3;

        	foreach ($d_invoice as $inv) {

        		$no_sub += $no_sub+1;

        		$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ($no++));

	            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $bc['nama_vendor']);

	            $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.intval($numrow+2));

	            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $bc['tanggal']);

	            $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numrow+2));

	            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $invoice['no_invoice']);

	            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $invoice['tanggal']);

	            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $inv['no_po']);

	            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $invoice['no_invoice']);

	            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $invoice['no_invoice']);

	            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $inv['kode_barang']);

	            $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $inv['nama_barang']);

	            $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $inv['satuan_barang']);

	            $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $inv['jumlah_barang']);

	            $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, 'Pack');

	            $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $bc['npwp']);

	            $excel->getActiveSheet()->mergeCells('N'.$numrow.':N'.intval($numrow+2));



	            $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row_j);

	            $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);

	            $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);

	            

	            $numrow=$numrow+1;

        	}

        }



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(3.14);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(26.14);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17.71);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(12.41);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20.71);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(12.57); 

        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(75.86);

        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(11.14);

        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);

        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(11.14);

        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(20.86);



        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="BC ' .$tanggal.'.xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');



        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

    }



    // public function export_excel_bc_fabrikasi_new($id_bc_fabrikasi)

    // {

    //     include_once APPPATH . 'third_party/PHPExcel.php';

    //     $bc                 = $this->M_fabrikasi->get_bc_by_id($id_bc_fabrikasi);

    //     $pch_tgl            = explode('-', date('d-F-Y', strtotime($bc['tanggal'])));

    //     $bulan              = $this->bulan($pch_tgl[1]);

    //     $tanggal            = date('d', strtotime($bc['tanggal'])).' '.$bulan.' '.date('Y', strtotime($bc['tanggal']));

    //     $bc_detail          = $this->M_fabrikasi->get_detail_bc($id_bc_fabrikasi)->result_array();



    //     $excel = new PHPExcel();



    //     $excel->getProperties()

    //             ->setCreator('IndoExpress')

    //             ->setLastModifiedBy('IndoExpress')

    //             ->setTitle('Data BC')

    //             ->setSubject('BC')

    //             ->setDescription('BC '.$tanggal)

    //             ->setKeyWords('BC');



    //     $style_col = [

    //         'fill' => [

    //             'type' => PHPExcel_Style_Fill::FILL_SOLID,

    //             'color' => ['rgb' => 'ffff00']

    //         ],

    //         'font' => ['bold' => true],

    //         'alignment' => [

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ],

    //         'borders' => [

    //             'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $style_row = [

    //         'alignment' => [

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

    //         ],

    //         'borders' => [

    //             'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $style_row_j = [

    //         'alignment' => [

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

    //         ],

    //         'borders' => [

    //             'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $style_row_full = [

    //         'alignment' => [

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ],

    //         'borders' => [

    //             'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $excel->setActiveSheetIndex(0)->setCellValue('A2', 'NO');

    //     $excel->setActiveSheetIndex(0)->setCellValue('B2', 'NAMA VENDOR');

    //     $excel->setActiveSheetIndex(0)->setCellValue('C2', 'No. Surat Jalan');

    //     $excel->setActiveSheetIndex(0)->setCellValue('D2', 'Tgl. Surat Jalan');

    //     $excel->setActiveSheetIndex(0)->setCellValue('E2', 'No. PO');

    //     $excel->setActiveSheetIndex(0)->setCellValue('F2', 'Tgl. PO');

    //     $excel->setActiveSheetIndex(0)->setCellValue('G2', 'No. Invoice');

    //     $excel->setActiveSheetIndex(0)->setCellValue('H2', 'No. Packing List');

    //     $excel->setActiveSheetIndex(0)->setCellValue('I2', 'Kode Faktur Pajak');

    //     $excel->setActiveSheetIndex(0)->setCellValue('J2', 'No. Polisi');

    //     $excel->setActiveSheetIndex(0)->setCellValue('K2', 'HS Code');

    //     $excel->setActiveSheetIndex(0)->setCellValue('L2', 'Item Code');

    //     $excel->setActiveSheetIndex(0)->setCellValue('M2', 'Description');

    //     $excel->setActiveSheetIndex(0)->setCellValue('N2', 'Kode Satuan');

    //     $excel->setActiveSheetIndex(0)->setCellValue('O2', 'Qty');

    //     $excel->setActiveSheetIndex(0)->setCellValue('P2', 'Amont (IDR)');

    //     $excel->setActiveSheetIndex(0)->setCellValue('Q2', 'BERAT BERSIH (kg)');

    //     $excel->setActiveSheetIndex(0)->setCellValue('R2', 'BERAT KOTOR (kg)');

    //     $excel->setActiveSheetIndex(0)->setCellValue('S2', 'Packaging');

    //     $excel->setActiveSheetIndex(0)->setCellValue('T2', 'Jenis Packaging');

        

    //     $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('C2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('D2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('E2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('F2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('G2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('H2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('I2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('J2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('K2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('L2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('M2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('N2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('O2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('P2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('Q2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('R2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('S2')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('T2')->applyFromArray($style_col);





    //     $numrow = 3;

    //     $numrow_last = 3;

    //     $no = 1;

    //     $total = 0;

    //     foreach ($bc_detail as $i) {

    //         $invoice = $this->db->get_where('fabrikasi', ['id_fabrikasi' => $i['id_fabrikasi']])->row_array();

    //         $d_invoice = $this->db->get_where('detail_fabrikasi', ['id_fabrikasi' => $i['id_fabrikasi']])->result_array();

    //         $no_sub = 3;

    //         foreach ($d_invoice as $inv) {

    //             $no_sub += $no_sub+1;

    //             $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ($no++));

    //             $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $bc['nama_vendor']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $invoice['no_invoice']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $invoice['tanggal']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $inv['no_po']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $invoice['tanggal']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $invoice['no_invoice']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $invoice['no_invoice']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, '');

    //             $excel->setActiveSheetIndex(0)->setCellValue('j'.$numrow, '');

    //             $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, '');

    //             $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $inv['kode_barang']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $inv['nama_barang']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $inv['satuan_barang']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $inv['jumlah_barang']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $inv['total_harga_barang']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, '');

    //             $excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, '');

    //             $excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, '');

    //             $excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, '');



    //             $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row_j);

    //             $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('O'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('P'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('Q'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('R'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('S'.$numrow)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('T'.$numrow)->applyFromArray($style_row);

                

    //             $numrow=$numrow+1;

    //         }

            

            

    //     }



    //     $excel->getActiveSheet()->getColumnDimension('A')->setWidth(3.14);

    //     $excel->getActiveSheet()->getColumnDimension('B')->setWidth(26.14);

    //     $excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);

    //     $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17.71);

    //     $excel->getActiveSheet()->getColumnDimension('E')->setWidth(12.41);

    //     $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20.71);

    //     $excel->getActiveSheet()->getColumnDimension('G')->setWidth(16.43);

    //     $excel->getActiveSheet()->getColumnDimension('H')->setWidth(16.43);

    //     $excel->getActiveSheet()->getColumnDimension('I')->setWidth(16.43); 

    //     $excel->getActiveSheet()->getColumnDimension('J')->setWidth(16.43);

    //     $excel->getActiveSheet()->getColumnDimension('K')->setWidth(20.43);

    //     $excel->getActiveSheet()->getColumnDimension('L')->setWidth(20.43);

    //     $excel->getActiveSheet()->getColumnDimension('M')->setWidth(75.86);

    //     $excel->getActiveSheet()->getColumnDimension('N')->setWidth(13);

    //     $excel->getActiveSheet()->getColumnDimension('O')->setWidth(5);

    //     $excel->getActiveSheet()->getColumnDimension('P')->setWidth(16.43);

    //     $excel->getActiveSheet()->getColumnDimension('Q')->setWidth(16.43);

    //     $excel->getActiveSheet()->getColumnDimension('R')->setWidth(16.43);

    //     $excel->getActiveSheet()->getColumnDimension('S')->setWidth(16.43);

    //     $excel->getActiveSheet()->getColumnDimension('T')->setWidth(16.43);



    //     $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    //     header('Content-Disposition: attachment; filename="BC ' .$tanggal.'.xlsx"'); // Set nama file excel nya

    //     header('Cache-Control: max-age=0');



    //     $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

    //     $write->save('php://output');

    // }



    public function export_excel_bc_fabrikasi_new($id_bc_fabrikasi, $cabang)

    {

        $cab = $this->M_cabang->get_by('slug', $cabang);

        include_once APPPATH . 'third_party/PHPExcel.php';

        $bc                 = $this->M_fabrikasi->get_bc_by_id($id_bc_fabrikasi);

        $pch_tgl            = explode('-', date('d-F-Y', strtotime($bc['tanggal'])));

        $bulan              = $this->bulan($pch_tgl[1]);

        $tanggal            = date('d', strtotime($bc['tanggal'])).' '.$bulan.' '.date('Y', strtotime($bc['tanggal']));

        $bc_detail          = $this->M_fabrikasi->get_detail_bc($id_bc_fabrikasi, $cab['id_cabang'])->result_array();



        $excel = new PHPExcel();



        $excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('Data BC')

                ->setSubject('BC')

                ->setDescription('BC '.$tanggal)

                ->setKeyWords('BC');



        $style_col = [

            'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'ffff00']

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

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_j = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

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



        $excel->setActiveSheetIndex(0)->setCellValue('A2', 'NO');

        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'NAMA VENDOR');

        $excel->setActiveSheetIndex(0)->setCellValue('C2', 'No. Surat Jalan');

        $excel->setActiveSheetIndex(0)->setCellValue('D2', 'Tgl. Surat Jalan');

        $excel->setActiveSheetIndex(0)->setCellValue('E2', 'No. PO');

        $excel->setActiveSheetIndex(0)->setCellValue('F2', 'Tgl. PO');

        $excel->setActiveSheetIndex(0)->setCellValue('G2', 'No. Invoice');

        $excel->setActiveSheetIndex(0)->setCellValue('H2', 'No. Packing List');

        $excel->setActiveSheetIndex(0)->setCellValue('I2', 'Kode Faktur Pajak');

        $excel->setActiveSheetIndex(0)->setCellValue('J2', 'No. Polisi');

        $excel->setActiveSheetIndex(0)->setCellValue('K2', 'HS Code');

        $excel->setActiveSheetIndex(0)->setCellValue('L2', 'Item Code');

        $excel->setActiveSheetIndex(0)->setCellValue('M2', 'Description');

        $excel->setActiveSheetIndex(0)->setCellValue('N2', 'Kode Satuan');

        $excel->setActiveSheetIndex(0)->setCellValue('O2', 'Qty');

        $excel->setActiveSheetIndex(0)->setCellValue('P2', 'Amont (IDR)');

        $excel->setActiveSheetIndex(0)->setCellValue('Q2', 'BERAT BERSIH (kg)');

        $excel->setActiveSheetIndex(0)->setCellValue('R2', 'BERAT KOTOR (kg)');

        $excel->setActiveSheetIndex(0)->setCellValue('S2', 'Packaging');

        $excel->setActiveSheetIndex(0)->setCellValue('T2', 'Jenis Packaging');

        

        $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('C2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('D2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('E2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('F2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('G2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('H2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('I2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('J2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('K2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('L2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('M2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('N2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('O2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('P2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('Q2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('R2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('S2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('T2')->applyFromArray($style_col);





        $numrow = 3;

        $numrow_last = 3;

        $no = 1;

        $total = 0;

        foreach ($bc_detail as $i) {

            $invoice = $this->db->get_where('fabrikasi', ['id_fabrikasi' => $i['id_fabrikasi']])->row_array();

            $d_invoice = $this->db->get_where('detail_fabrikasi', ['id_fabrikasi' => $i['id_fabrikasi'], 'id_cabang' => $cab['id_cabang']])->result_array();

            $no_sub = 3;

            foreach ($d_invoice as $inv) {

                $no_sub += $no_sub+1;

                $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ($no++));

                $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $bc['nama_vendor']);

                $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $invoice['no_invoice']);

                $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $invoice['tanggal']);

                $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $inv['no_po']);

                $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $invoice['tanggal']);

                $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $invoice['no_invoice']);

                $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $invoice['no_invoice']);

                $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, '');

                $excel->setActiveSheetIndex(0)->setCellValue('j'.$numrow, '');

                $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, '');

                $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $inv['kode_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $inv['nama_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $inv['satuan_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $inv['jumlah_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $inv['total_harga_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, '');

                $excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, '');

                $excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, '');

                $excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, '');



                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row_j);

                $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('O'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('P'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('Q'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('R'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('S'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('T'.$numrow)->applyFromArray($style_row);

                

                $numrow=$numrow+1;

            }

            

            

        }



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(3.14);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(26.14);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17.71);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(12.41);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20.71);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(16.43); 

        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(20.43);

        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(20.43);

        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(75.86);

        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('O')->setWidth(5);

        $excel->getActiveSheet()->getColumnDimension('P')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('Q')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('R')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('S')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('T')->setWidth(16.43);



        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="BC ' .$tanggal.'.xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');



        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

    }



	public function eksport_quotation($id_no_quot, $mode = null)

	{

		$this->load->library('pdf');

		$data['title']		= 'Quotation';

        $data['title2']		= 'Quotation';

		$data['q']			= $this->M_quotation->get_no_by_id($id_no_quot);

		$quot				= explode('/', $data['q']['no_quot']);

		$data['quotation']	= $this->M_quotation->get($id_no_quot)->result_array();

		$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['q']['tanggal'])));

		$bulan 				= $this->bulan($pch_tgl[1]);

		$data['tanggal']	= date('d', strtotime($data['q']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['q']['tanggal']));

		

		$rincian = '';

		foreach ($data['quotation'] as $q) {

			$cek_rincian = $this->db->get_where('detail_quotation', ['id_quotation' => $q['id_quotation']])->num_rows();

			if ($cek_rincian != 0){

				$rincian .= $q['id_quotation'].',';

			}

		}



		if(!empty($rincian)){

			$rincian = substr($rincian, 0, -1);

		}

		

		$data['rincian'] = $rincian;



        if($mode == 'aj'){

            $html_content = $this->load->view('quotation/cetak_aj', $data, true);

        } else {

            $html_content = $this->load->view('quotation/cetak', $data, true);

        }

		

		$filename = '';

        if(count($quot) == 3) {

            $filename = 'Quotation - '.$quot[0].'-'.$quot[1].'-'.$quot[2].'.pdf';

        } else {

            $filename = 'Quotation - '.$quot[0].'-'.$quot[1].'-'.$quot[2].'-'.$quot[3].'.pdf';

        }

        

        $this->pdf->loadHtml($html_content);



        $this->pdf->set_paper('a4','potrait');

        

        $this->pdf->render();

        $this->pdf->stream($filename, ['Attachment' => 1]);

	}



    public function cetak_invoice_gs($id_gs, $mode = null)

    {

        $this->load->library('pdf');

        $data['title']      = 'Gs';

        $data['title2']     = 'Invoice';

        $data['f']          = $this->M_gs->get_by_id($id_gs);

        $inv                = explode('/', $data['f']['no_invoice']);

        $pch_tgl            = explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));

        $bulan              = $this->bulan($pch_tgl[1]);

        $data['tanggal']    = date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));

        $data['f_detail']    = $this->M_gs->get_detail($id_gs)->result_array();

        $this->db->select('*');

        $this->db->select_sum('jumlah_barang');

        $this->db->select_sum('total_harga_barang');

        $this->db->from('detail_gs');

        $this->db->where('id_gs', $id_gs);

        $this->db->order_by('id_detail_gs', 'ASC');

        $data['f_detaivchl']   = $this->db->get()->result_array();



        if($mode == 'aj'){

            $html_content = $this->load->view('gs/cetak_invoice_aj', $data, true);

        }else{

            $html_content = $this->load->view('gs/cetak_invoice', $data, true); 

        }

        

        

        $filename = 'Invoice - '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3].'.pdf';



        $this->pdf->loadHtml($html_content);



        $this->pdf->set_paper('a4','potrait');

        

        $this->pdf->render();

        $this->pdf->stream($filename, ['Attachment' => 1]);

    }



    public function cetak_surat_jalan_gs($id_gs, $mode = null)

    {

        $this->load->library('pdf');

        $data['title']      = 'Gs';

        $data['title2']     = 'Surat Jalan';

        $data['f']          = $this->M_gs->get_by_id($id_gs);

        $inv                = explode('/', $data['f']['no_invoice']);

        $pch_tgl            = explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));

        $bulan              = $this->bulan($pch_tgl[1]);

        $data['tanggal']    = date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));

        $data['f_detail'] = $this->M_gs->get_detail($id_gs)->result_array();

        $this->db->select('*');

        $this->db->select_sum('jumlah_barang');

        $this->db->select_sum('total_harga_barang');

        $this->db->from('detail_gs');

        $this->db->where('id_gs', $id_gs);

        $this->db->order_by('id_detail_gs', 'ASC');

        $data['f_dnjkkhetail']   = $this->db->get()->result_array();



        if($mode == 'aj'){

            $html_content = $this->load->view('gs/cetak_surat_jalan_aj', $data, true);

        } else {

            $html_content = $this->load->view('gs/cetak_surat_jalan', $data, true);

        }





        $filename = 'Surat Jalan - '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3].'.pdf';



        $this->pdf->loadHtml($html_content);



        $this->pdf->set_paper('a4','potrait');

        

        $this->pdf->render();

        $this->pdf->stream($filename, ['Attachment' => 1]);

    }



    public function export_excel_invoice_gs($id_gs)

    {

        include_once APPPATH . 'third_party/PHPExcel.php';

        $f                  = $this->M_gs->get_by_id($id_gs);

        $inv                = explode('/', $f['no_invoice']);

        $pch_tgl            = explode('-', date('d-F-Y', strtotime($f['tanggal'])));

        $bulan              = $this->bulan($pch_tgl[1]);

        $tanggal            = date('d', strtotime($f['tanggal'])).' '.$bulan.' '.date('Y', strtotime($f['tanggal']));

        $f_detail           = $this->M_gs->get_detail($id_gs)->result_array();



        $excel = new PHPExcel();



        $excel1 = new PHPExcel_Worksheet($excel, 'Invoice');



        // Attach the "My Data" worksheet as the first worksheet in the PHPExcel object

        $excel->addSheet($excel1, 0);



        $objDrawing = new PHPExcel_Worksheet_Drawing();

        $excel->setActiveSheetIndex(0);

        $objDrawing->setWorksheet($excel->getActiveSheet());

        $objDrawing->setCoordinates('B1');

        $objDrawing->setName('Header Invoice');

        $objDrawing->setDescription('Sintesa');

        $objDrawing->setPath('assets/img/header_invoice.png');

        $objDrawing->setWidth(160)->setHeight(160);



        $excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('Data Invoice & SJ')

                ->setSubject('Invoice & SJ')

                ->setDescription('Invoice & SJ '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3])

                ->setKeyWords('Invoice & SJ');



        $style_col = [

            'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'd9e1f2']

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



        $excel->setActiveSheetIndex(0)->setCellValue('G9', $f['no_invoice']);

        $excel->getActiveSheet()->mergeCells('G9:I9');

        $excel->getActiveSheet()->getStyle('G9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(0)->setCellValue('G10', $tanggal);

        $excel->getActiveSheet()->mergeCells('G10:I10');

        $excel->getActiveSheet()->getStyle('G10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(0)->setCellValue('G11', 'Kepada:');

        $excel->getActiveSheet()->mergeCells('G11:I11');

        $excel->getActiveSheet()->getStyle('G11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(0)->setCellValue('G12', 'PT. TKG TAEKWANG INDONESIA Dusun Belendung II RT. 17 RW.06 Desa Belendung Kec. Cibogo, Kab. Subang');

        $excel->getActiveSheet()->mergeCells('G12:I15');

        $excel->getActiveSheet()->getStyle('G12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 

        $excel->getActiveSheet()->getStyle('G12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);   

        $excel->getActiveSheet()->getStyle('G12')->getAlignment()->setWrapText(true);    



        $excel->setActiveSheetIndex(0)->setCellValue('B16', 'NO');

        $excel->setActiveSheetIndex(0)->setCellValue('C16', 'NAMA BARANG');

        $excel->setActiveSheetIndex(0)->setCellValue('D16', 'ITEM CODE');

        $excel->setActiveSheetIndex(0)->setCellValue('E16', 'UOM');

        $excel->setActiveSheetIndex(0)->setCellValue('F16', 'QTY');

        $excel->setActiveSheetIndex(0)->setCellValue('G16', 'COST');

        $excel->setActiveSheetIndex(0)->setCellValue('H16', 'TOTAL');

        $excel->setActiveSheetIndex(0)->setCellValue('I16', 'PO');

        

        $excel->getActiveSheet()->getStyle('B16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('C16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('D16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('E16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('F16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('G16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('H16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('I16')->applyFromArray($style_col);



        $numrow = 17;

        $numrow_last = 17;

        $no = 1;

        $total = 0;

        foreach ($f_detail as $i) {

            $numroww = $numrow+1;

            $numrow_last += $numrow_last+3;

            $total += $i['total_harga_barang'];

            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ($no++));

            $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $i['nama_barang']);

            $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('C'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 

            $excel->getActiveSheet()->getStyle('C'.$numrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);   

            $excel->getActiveSheet()->getStyle('C'.$numrow)->getAlignment()->setWrapText(true);

            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $i['kode_barang']);

            $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.intval($numrow+1));

            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $i['satuan_barang']);

            $excel->getActiveSheet()->mergeCells('E'.$numrow.':E'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('E'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $i['jumlah_barang']);

            $excel->getActiveSheet()->mergeCells('F'.$numrow.':F'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('F'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, number_format($i['harga_barang'], 0,'.',','));

            $excel->getActiveSheet()->mergeCells('G'.$numrow.':G'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('G'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, number_format($i['total_harga_barang'], 0,'.',','));

            $excel->getActiveSheet()->mergeCells('H'.$numrow.':H'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('H'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $i['no_po']);

            $excel->getActiveSheet()->mergeCells('I'.$numrow.':I'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('I'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);



            $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('H'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('I'.$numroww)->applyFromArray($style_row);

            

            $numrow=$numrow+2;

            

        }



        $numrow_terbilang = $numrow+1;

        $terbilang = $this->terbilang($total);



        $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, 'TOTAL');

        $excel->getActiveSheet()->getStyle('G'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('G'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('G'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row_full);

        $excel->getActiveSheet()->getStyle('B'.$numrow.':G'.$numrow)->applyFromArray($style_row_full);



        $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, number_format($total, 0,'.',','));

        $excel->getActiveSheet()->getStyle('H'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('H'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('H'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row_full);

        $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row_full);



        $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, 'TERBILANG : '.strtoupper($terbilang));

        $excel->getActiveSheet()->mergeCells('B'.$numrow.':F'.$numrow);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



        $excel->setActiveSheetIndex(0)->setCellValue('F'.intval($numrow_terbilang+2), 'HORMAT KAMI');

        $excel->getActiveSheet()->getStyle('F'.intval($numrow_terbilang+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('F'.intval($numrow_terbilang+2))->getFont()->setBold(true);

        $excel->getActiveSheet()->mergeCells('F'.intval($numrow_terbilang+2).':I'.intval($numrow_terbilang+2));



        $excel->setActiveSheetIndex(0)->setCellValue('F'.intval($numrow_terbilang+9), 'EKA IMAM MAULANA');

        $excel->getActiveSheet()->getStyle('F'.intval($numrow_terbilang+9))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->mergeCells('F'.intval($numrow_terbilang+9).':I'.intval($numrow_terbilang+9));



        $objDrawing2 = new PHPExcel_Worksheet_Drawing();

        $excel->setActiveSheetIndex(0);

        $objDrawing2->setWorksheet($excel->getActiveSheet());

        $objDrawing2->setCoordinates('B'.intval($numrow_terbilang+10));

        $objDrawing2->setName('Footer Invoice');

        $objDrawing2->setDescription('Sintesa');

        $objDrawing2->setPath('assets/img/footer_surat.png');

        $objDrawing2->setWidth(155)->setHeight(155);



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(43);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(5);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(6);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(13);

        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(26);

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        $excel2 = new PHPExcel_Worksheet($excel, 'Surat Jalan');



        // Attach the "My Data" worksheet as the first worksheet in the PHPExcel object

        $excel->addSheet($excel2, 1);



        $excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('Data Invoice & SJ')

                ->setSubject('Invoice & SJ')

                ->setDescription('Invoice & SJ '.$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3])

                ->setKeyWords('Invoice & SJ');



        $objDrawing3 = new PHPExcel_Worksheet_Drawing();

        $excel->setActiveSheetIndex(1);

        $objDrawing3->setWorksheet($excel->getActiveSheet());

        $objDrawing3->setCoordinates('B1');

        $objDrawing3->setName('Footer Invoice');

        $objDrawing3->setDescription('Sintesa');

        $objDrawing3->setPath('assets/img/header_surat_jalan.png');

        $objDrawing3->setWidth(195)->setHeight(195);



        $style_col = [

            'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'd9e1f2']

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



        $style_row_top = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_left = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_right = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

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



        $excel->setActiveSheetIndex(1)->setCellValue('E10', $f['no_invoice']);

        $excel->getActiveSheet()->mergeCells('E10:G10');

        $excel->getActiveSheet()->getStyle('E10')->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('E10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(1)->setCellValue('E11', $tanggal);

        $excel->getActiveSheet()->mergeCells('E11:G11');

        $excel->getActiveSheet()->getStyle('E11')->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('E11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(1)->setCellValue('E12', 'Kepada:');

        $excel->getActiveSheet()->mergeCells('E12:G12');

        $excel->getActiveSheet()->getStyle('E12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



        $excel->setActiveSheetIndex(1   )->setCellValue('E13', 'PT. TKG TAEKWANG INDONESIA Dusun Belendung II RT. 17 RW.06 Desa Belendung Kec. Cibogo, Kab. Subang');

        $excel->getActiveSheet()->mergeCells('E13:G15');

        $excel->getActiveSheet()->getStyle('E13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 

        $excel->getActiveSheet()->getStyle('E13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);   

        $excel->getActiveSheet()->getStyle('E13')->getAlignment()->setWrapText(true);    



        $excel->setActiveSheetIndex(1)->setCellValue('B16', 'NO');

        $excel->setActiveSheetIndex(1)->setCellValue('C16', 'NAMA BARANG');

        $excel->setActiveSheetIndex(1)->setCellValue('D16', 'ITEM CODE');

        $excel->setActiveSheetIndex(1)->setCellValue('E16', 'UOM');

        $excel->setActiveSheetIndex(1)->setCellValue('F16', 'QTY');

        $excel->setActiveSheetIndex(1)->setCellValue('G16', 'PO');

        

        $excel->getActiveSheet()->getStyle('B16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('C16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('D16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('E16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('F16')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('G16')->applyFromArray($style_col);



        $numrow = 17;

        $numrow_last = 17;

        $no = 1;

        $total = 0;

        foreach ($f_detail as $i) {

            $numroww = $numrow+1;

            $numrow2 = $numrow+2;

            $numrow_last += $numrow_last+3;

            $total += $i['total_harga_barang'];

            $excel->setActiveSheetIndex(1)->setCellValue('B'.$numrow, ($no++));

            $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.$numroww);

            $excel->setActiveSheetIndex(1)->setCellValue('C'.$numrow, $i['nama_barang']);

            $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.$numroww);

            $excel->setActiveSheetIndex(1)->setCellValue('D'.$numrow, $i['kode_barang']);

            $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.$numroww);

            $excel->setActiveSheetIndex(1)->setCellValue('E'.$numrow, $i['satuan_barang']);

            $excel->getActiveSheet()->getStyle('E'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('E'.$numrow.':E'.$numroww);

            

            

            $excel->setActiveSheetIndex(1)->setCellValue('F'.$numrow, $i['jumlah_barang']);

            $excel->getActiveSheet()->getStyle('F'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('F'.$numrow.':F'.$numroww);

            

            $excel->setActiveSheetIndex(1)->setCellValue('G'.$numrow, $i['no_po']);

            $excel->getActiveSheet()->getStyle('G'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('G'.$numrow.':G'.$numroww);



            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);



            $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row);



            $excel->getActiveSheet()->getStyle('B'.$numrow2)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numrow2)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numrow2)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numrow2)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numrow2)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('G'.$numrow2)->applyFromArray($style_row);

            

            $numrow=$numrow+3;

            

        }



        $numrow_ttd = $numrow+1;





        $excel->setActiveSheetIndex(1)->setCellValue('B'.$numrow);

        $excel->getActiveSheet()->mergeCells('B'.$numrow.':G'.$numrow);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $excel->getActiveSheet()->getStyle('B'.$numrow.':G'.$numrow)->applyFromArray($style_row_top);



        $excel->setActiveSheetIndex(1)->setCellValue('B'.$numrow_ttd, 'HORMAT KAMI');

        $excel->setActiveSheetIndex(1)->setCellValue('D'.$numrow_ttd, 'PENERIMA');

        $excel->getActiveSheet()->mergeCells('B'.$numrow_ttd.':C'.$numrow_ttd);

        $excel->getActiveSheet()->mergeCells('D'.$numrow_ttd.':G'.$numrow_ttd);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd.':C'.$numrow_ttd)->applyFromArray($style_row_top);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd.':C'.$numrow_ttd)->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd.':C'.$numrow_ttd)->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd.':G'.$numrow_ttd)->applyFromArray($style_row_top);

        $excel->getActiveSheet()->getStyle('D'.$numrow_ttd.':G'.$numrow_ttd)->applyFromArray($style_row_right);



        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+1))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+1))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+1))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+2))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+2))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+2))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+3))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+3))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+3))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+4))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+4))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+4))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+5))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('C'.intval($numrow_ttd+5))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('G'.intval($numrow_ttd+5))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->mergeCells('B'.intval($numrow_ttd+6).':C'.intval($numrow_ttd+6));

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+7).':C'.intval($numrow_ttd+7))->applyFromArray($style_row_top);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+6).':C'.intval($numrow_ttd+6))->applyFromArray($style_row_left);

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+6).':C'.intval($numrow_ttd+6))->applyFromArray($style_row_right);

        $excel->getActiveSheet()->getStyle('D'.intval($numrow_ttd+7).':G'.intval($numrow_ttd+7))->applyFromArray($style_row_top);

        $excel->getActiveSheet()->getStyle('D'.intval($numrow_ttd+6).':G'.intval($numrow_ttd+6))->applyFromArray($style_row_right);

        $excel->setActiveSheetIndex(1)->setCellValue('B'.intval($numrow_ttd+6), 'EKA IMAM MAULANA');

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+6))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



        $objDrawing3 = new PHPExcel_Worksheet_Drawing();

        $excel->setActiveSheetIndex(1);

        $objDrawing3->setWorksheet($excel->getActiveSheet());

        $objDrawing3->setCoordinates('B'.intval($numrow_ttd+9));

        $objDrawing3->setName('Footer Invoice');

        $objDrawing3->setDescription('Sintesa');

        $objDrawing3->setPath('assets/img/footer_surat.png');

        $objDrawing3->setWidth(150)->setHeight(150);



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(1);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(4);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(45);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(8);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(8);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(26);

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="Invoice $ SJ ' .$inv[0].'-'.$inv[1].'-'.$inv[2].'-'.$inv[3]. '.xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');



        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

    }



    public function export_excel_bc_gs($id_bc_gs)

    {

        include_once APPPATH . 'third_party/PHPExcel.php';

        $bc                 = $this->M_gs->get_bc_by_id($id_bc_gs);

        $pch_tgl            = explode('-', date('d-F-Y', strtotime($bc['tanggal'])));

        $bulan              = $this->bulan($pch_tgl[1]);

        $tanggal            = date('d', strtotime($bc['tanggal'])).' '.$bulan.' '.date('Y', strtotime($bc['tanggal']));

        $bc_detail          = $this->M_gs->get_detail_bc($id_bc_gs)->result_array();



        $excel = new PHPExcel();



        $excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('Data BC')

                ->setSubject('BC')

                ->setDescription('BC '.$tanggal)

                ->setKeyWords('BC');



        $style_col = [

            'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'ffff00']

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

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_j = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

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



        $excel->setActiveSheetIndex(0)->setCellValue('A2', 'NO');

        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'NAMA VENDOR');

        $excel->setActiveSheetIndex(0)->setCellValue('C2', 'TGL SJ');

        $excel->setActiveSheetIndex(0)->setCellValue('D2', 'NO SJ');

        $excel->setActiveSheetIndex(0)->setCellValue('E2', 'TGL PO');

        $excel->setActiveSheetIndex(0)->setCellValue('F2', 'NO PO');

        $excel->setActiveSheetIndex(0)->setCellValue('G2', 'NO PACKING LIST');

        $excel->setActiveSheetIndex(0)->setCellValue('H2', 'NO INVOICE');

        $excel->setActiveSheetIndex(0)->setCellValue('I2', 'ITEM CODE');

        $excel->setActiveSheetIndex(0)->setCellValue('J2', 'DESCRIPTION');

        $excel->setActiveSheetIndex(0)->setCellValue('K2', 'Kode Satuan');

        $excel->setActiveSheetIndex(0)->setCellValue('L2', 'QTY');

        $excel->setActiveSheetIndex(0)->setCellValue('M2', 'PACKAGING');

        $excel->setActiveSheetIndex(0)->setCellValue('N2', 'NPWP');

        

        $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('C2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('D2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('E2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('F2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('G2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('H2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('I2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('J2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('K2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('L2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('M2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('N2')->applyFromArray($style_col);



        $numrow = 3;

        $numrow_last = 3;

        $no = 1;

        $total = 0;

        foreach ($bc_detail as $i) {

            $invoice = $this->db->get_where('gs', ['id_gs' => $i['id_gs']])->row_array();

            $d_invoice = $this->db->get_where('detail_gs', ['id_gs' => $i['id_gs']])->result_array();

            $no_sub = 3;

            foreach ($d_invoice as $inv) {

                $no_sub += $no_sub+1;

                $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ($no++));

                $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $bc['nama_vendor']);

                $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.intval($numrow+2));

                $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $bc['tanggal']);

                $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numrow+2));

                $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $invoice['no_invoice']);

                $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $invoice['tanggal']);

                $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $inv['no_po']);

                $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $invoice['no_invoice']);

                $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $invoice['no_invoice']);

                $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $inv['kode_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $inv['nama_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $inv['satuan_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $inv['jumlah_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, 'Pack');

                $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $bc['npwp']);

                $excel->getActiveSheet()->mergeCells('N'.$numrow.':N'.intval($numrow+2));



                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row_j);

                $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);

                

                $numrow=$numrow+1;

            }

            

            

        }



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(3.14);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(26.14);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17.71);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(12.41);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20.71);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(12.57); 

        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(75.86);

        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(11.14);

        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);

        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(11.14);

        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(20.86);



        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="BC ' .$tanggal.'.xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');



        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

    }



    public function export_excel_bc_gs_new($id_bc_gs)

    {

        include_once APPPATH . 'third_party/PHPExcel.php';

        $bc                 = $this->M_gs->get_bc_by_id($id_bc_gs);

        $pch_tgl            = explode('-', date('d-F-Y', strtotime($bc['tanggal'])));

        $bulan              = $this->bulan($pch_tgl[1]);

        $tanggal            = date('d', strtotime($bc['tanggal'])).' '.$bulan.' '.date('Y', strtotime($bc['tanggal']));

        $bc_detail          = $this->M_gs->get_detail_bc($id_bc_gs)->result_array();



        $excel = new PHPExcel();



        $excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('Data BC')

                ->setSubject('BC')

                ->setDescription('BC '.$tanggal)

                ->setKeyWords('BC');



        $style_col = [

            'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'ffff00']

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

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_j = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

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



        $excel->setActiveSheetIndex(0)->setCellValue('A2', 'NO');

        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'NAMA VENDOR');

        $excel->setActiveSheetIndex(0)->setCellValue('C2', 'No. Surat Jalan');

        $excel->setActiveSheetIndex(0)->setCellValue('D2', 'Tgl. Surat Jalan');

        $excel->setActiveSheetIndex(0)->setCellValue('E2', 'No. PO');

        $excel->setActiveSheetIndex(0)->setCellValue('F2', 'Tgl. PO');

        $excel->setActiveSheetIndex(0)->setCellValue('G2', 'No. Invoice');

        $excel->setActiveSheetIndex(0)->setCellValue('H2', 'No. Packing List');

        $excel->setActiveSheetIndex(0)->setCellValue('I2', 'Kode Faktur Pajak');

        $excel->setActiveSheetIndex(0)->setCellValue('J2', 'No. Polisi');

        $excel->setActiveSheetIndex(0)->setCellValue('K2', 'HS Code');

        $excel->setActiveSheetIndex(0)->setCellValue('L2', 'Item Code');

        $excel->setActiveSheetIndex(0)->setCellValue('M2', 'Description');

        $excel->setActiveSheetIndex(0)->setCellValue('N2', 'Kode Satuan');

        $excel->setActiveSheetIndex(0)->setCellValue('O2', 'Qty');

        $excel->setActiveSheetIndex(0)->setCellValue('P2', 'Amont (IDR)');

        $excel->setActiveSheetIndex(0)->setCellValue('Q2', 'BERAT BERSIH (kg)');

        $excel->setActiveSheetIndex(0)->setCellValue('R2', 'BERAT KOTOR (kg)');

        $excel->setActiveSheetIndex(0)->setCellValue('S2', 'Packaging');

        $excel->setActiveSheetIndex(0)->setCellValue('T2', 'Jenis Packaging');

        

        $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('C2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('D2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('E2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('F2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('G2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('H2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('I2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('J2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('K2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('L2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('M2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('N2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('O2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('P2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('Q2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('R2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('S2')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('T2')->applyFromArray($style_col);





        $numrow = 3;

        $numrow_last = 3;

        $no = 1;

        $total = 0;

        foreach ($bc_detail as $i) {

            $invoice = $this->db->get_where('gs', ['id_gs' => $i['id_gs']])->row_array();

            $d_invoice = $this->db->get_where('detail_gs', ['id_gs' => $i['id_gs']])->result_array();

            $no_sub = 3;

            foreach ($d_invoice as $inv) {

                $no_sub += $no_sub+1;

                $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ($no++));

                $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $bc['nama_vendor']);

                $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $invoice['no_invoice']);

                $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $invoice['tanggal']);

                $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $inv['no_po']);

                $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $invoice['tanggal']);

                $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $invoice['no_invoice']);

                $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $invoice['no_invoice']);

                $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, '');

                $excel->setActiveSheetIndex(0)->setCellValue('j'.$numrow, '');

                $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, '');

                $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $inv['kode_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $inv['nama_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $inv['satuan_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $inv['jumlah_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $inv['total_harga_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, '');

                $excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, '');

                $excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, '');

                $excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, '');



                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row_j);

                $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('O'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('P'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('Q'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('R'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('S'.$numrow)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('T'.$numrow)->applyFromArray($style_row);

                

                $numrow=$numrow+1;

            }

            

            

        }



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(3.14);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(26.14);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17.71);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(12.41);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20.71);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(16.43); 

        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(20.43);

        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(20.43);

        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(75.86);

        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(13);

        $excel->getActiveSheet()->getColumnDimension('O')->setWidth(5);

        $excel->getActiveSheet()->getColumnDimension('P')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('Q')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('R')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('S')->setWidth(16.43);

        $excel->getActiveSheet()->getColumnDimension('T')->setWidth(16.43);



        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="BC ' .$tanggal.'.xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');



        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

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

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('Data Stok Barang')

                ->setSubject('Data Stok Barang')

                ->setDescription('Data Stok Barang')

                ->setKeyWords('Data Stok Barang');



        $style_col = [

            'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'd9e1f2']

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

        $excel->setActiveSheetIndex(0)->setCellValue('C5', 'NAMA BARANG');

        $excel->setActiveSheetIndex(0)->setCellValue('D5', 'ITEM CODE');

        $excel->setActiveSheetIndex(0)->setCellValue('E5', 'UOM');

        $excel->setActiveSheetIndex(0)->setCellValue('F5', 'STOK');

        

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

            $numroww = $numrow+1;

            $numrow2 = $numrow+2;

            $numrow_last += $numrow_last+3;

            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ($no++));

            $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.intval($numrow+1));

            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $i['nama_barang']);

            $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('C'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 

            $excel->getActiveSheet()->getStyle('C'.$numrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);   

            $excel->getActiveSheet()->getStyle('C'.$numrow)->getAlignment()->setWrapText(true);

            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $i['kode_barang']);

            $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('D'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $i['satuan_barang']);

            $excel->getActiveSheet()->mergeCells('E'.$numrow.':E'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('E'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $i['stok']);

            $excel->getActiveSheet()->mergeCells('F'.$numrow.':F'.intval($numrow+1));

            $excel->getActiveSheet()->getStyle('F'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);



            $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row);

            $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);



            // $excel->getActiveSheet()->getStyle('B'.$numrow2)->applyFromArray($style_row);

            // $excel->getActiveSheet()->getStyle('C'.$numrow2)->applyFromArray($style_row);

            // $excel->getActiveSheet()->getStyle('D'.$numrow2)->applyFromArray($style_row);

            // $excel->getActiveSheet()->getStyle('E'.$numrow2)->applyFromArray($style_row);

            // $excel->getActiveSheet()->getStyle('F'.$numrow2)->applyFromArray($style_row);

            

            $numrow=$numrow+2;

            

        }



        $numrow_terbilang = $numrow+1;

        $excel->getActiveSheet()->getStyle('B'.$numrow.':F'.$numrow)->applyFromArray($style_row_bottom);





        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(43);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(8);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(8);

        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(26);

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="Laporan Stok Barang' .date('d F Y'). '.xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');



        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

    }



    // public function export_excel_outstanding_fabrikasi()

    // {

    //     $this->db->select('*');

    //     $this->db->from('po_fabrikasi');

    //     $this->db->join('detail_po_fabrikasi', 'po_fabrikasi.id_po_fabrikasi=detail_po_fabrikasi.id_po_fabrikasi');

    //     $this->db->group_by('po_fabrikasi.no_po');

    //     $this->db->order_by('po_fabrikasi.tanggal', 'ASC');

    //     $detail_po_fabrikasi = $this->db->get()->result_array();

    //     //var_dump($detail_po_fabrikasi);

    //     //die();



    //     include_once APPPATH . 'third_party/PHPExcel.php';



    //     $excel = new PHPExcel();



    //     $excel1 = new PHPExcel_Worksheet($excel, 'OUTSTANDING FABRIKASI');



    //     // Attach the "My Data" worksheet as the first worksheet in the PHPExcel object

    //     $excel->addSheet($excel1, 0);



    //     $excel->getProperties()

    //             ->setCreator('IndoExpress')

    //             ->setLastModifiedBy('IndoExpress')

    //             ->setTitle('OUTSTANDING FABRIKASI')

    //             ->setSubject('OUTSTANDING FABRIKASI')

    //             ->setDescription('OUTSTANDING FABRIKASI')

    //             ->setKeyWords('OUTSTANDING FABRIKASI');



    //     $style_title = [

    //         'font' => ['bold' => true],

    //         'alignment' => [

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ]

    //     ];

    //     $style_title_sec = [

    //         'font' => ['bold' => true],

    //         'alignment' => [

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ]

    //     ];



    //     $style_col = [

    //         'fill' => [

    //             'type' => PHPExcel_Style_Fill::FILL_SOLID,

    //             'color' => ['rgb' => 'd9e1f2']

    //         ],

    //         'font' => ['bold' => true],

    //         'alignment' => [

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ],

    //         'borders' => [

    //             'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $style_row = [

    //         'alignment' => [

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ],

    //         'borders' => [

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $style_row_barang = [

    //         'alignment' => [

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ],

    //         'borders' => [

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $style_row_full = [

    //         'alignment' => [

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ],

    //         'borders' => [

    //             'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $excel->setActiveSheetIndex(0)->setCellValue('B2', 'LIST OUTSTANDING FABRIKASI');

    //     $excel->getActiveSheet()->mergeCells('B2:G2');

    //     $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_title);  



    //     $excel->setActiveSheetIndex(0)->setCellValue('B4', 'NO');

    //     $excel->setActiveSheetIndex(0)->setCellValue('C4', 'TANGGAL PO');

    //     $excel->setActiveSheetIndex(0)->setCellValue('D4', 'NO PO');

    //     $excel->setActiveSheetIndex(0)->setCellValue('E4', 'NAMA BARANG');

    //     $excel->setActiveSheetIndex(0)->setCellValue('F4', 'QTY');

    //     $excel->setActiveSheetIndex(0)->setCellValue('G4', 'DEPARTMENT');

        

    //     $excel->getActiveSheet()->getStyle('B4')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('C4')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('D4')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('E4')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('F4')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('G4')->applyFromArray($style_col);



    //     $numrow = 5;

    //     $numrow_last = 5;

    //     $no = 1;

    //     $total = 0;

    //     foreach ($detail_po_fabrikasi as $i) {

    //         $this->db->select('*');

    //         $this->db->from('detail_po_fabrikasi');

    //         $this->db->where('id_po_fabrikasi', $i['id_po_fabrikasi']);

    //         $detail_po = $this->db->get()->result_array();

    //         $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ($no++));

    //         $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $i['tanggal']);

    //         $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $i['no_po']);



    //         $numroww = $numrow;

    //         foreach ($detail_po as $k) {

    //             $excel->setActiveSheetIndex(0)->setCellValue('E'.$numroww, $k['nama_barang']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('F'.$numroww, $k['jumlah_barang']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('G'.$numroww, $k['departemen']);



    //             $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row_barang);

    //             $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row);



    //             $numroww = $numroww + 1;

    //         }



    //         $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.intval($numroww-1));

    //         $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numroww-1));

    //         $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.intval($numroww-1));



    //         $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row_full);

    //         $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row_full);

    //         $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row_full);

    //         $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row_full);

    //         $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row_full);

    //         $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row_full); 

            

    //         $numrow=$numroww;

    //     }



    //     $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);

    //     $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);

    //     $excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);

    //     $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17);

    //     $excel->getActiveSheet()->getColumnDimension('E')->setWidth(90);

    //     $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);

    //     $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);

    //     $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    //     header('Content-Disposition: attachment; filename="List Outstanding Fabrikasi .xlsx"'); // Set nama file excel nya

    //     header('Cache-Control: max-age=0');



    //     $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

    //     $write->save('php://output');

    // }



    public function id_cabang($cabang) 

	{

		$cabangAll = $this->M_cabang->get_data()->result_array();

		foreach ($cabangAll as $item) {

			if ($item['slug'] === $cabang) {

				return $item['id_cabang'];

			}

		}

	}



    public function export_excel_outstanding_fabrikasi($cabang)

    {

        $id_cabang = $this->id_cabang($cabang);

        $this->db->select('*');

        $this->db->from('po_fabrikasi');

        $this->db->join('detail_po_fabrikasi', 'po_fabrikasi.id_po_fabrikasi=detail_po_fabrikasi.id_po_fabrikasi');

        $this->db->where('po_fabrikasi.id_cabang', $id_cabang);

        $this->db->group_by('po_fabrikasi.no_po');

        $this->db->order_by('po_fabrikasi.tanggal', 'ASC');

        $detail_po_fabrikasi = $this->db->get()->result_array();

        //var_dump($detail_po_fabrikasi);

        //die();



        include_once APPPATH . 'third_party/PHPExcel.php';



        $excel = new PHPExcel();



        $excel1 = new PHPExcel_Worksheet($excel, 'OUTSTANDING FABRIKASI');



        // Attach the "My Data" worksheet as the first worksheet in the PHPExcel object

        $excel->addSheet($excel1, 0);



        $excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('OUTSTANDING FABRIKASI')

                ->setSubject('OUTSTANDING FABRIKASI')

                ->setDescription('OUTSTANDING FABRIKASI')

                ->setKeyWords('OUTSTANDING FABRIKASI');



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

                'color' => ['rgb' => 'd9e1f2']

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

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_barang = [

            'alignment' => [

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

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

            ]

        ];



        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'LIST OUTSTANDING FABRIKASI');

        $excel->getActiveSheet()->mergeCells('B2:G2');

        $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_title);  



        $excel->setActiveSheetIndex(0)->setCellValue('B4', 'NO');

        $excel->setActiveSheetIndex(0)->setCellValue('C4', 'TANGGAL PO');

        $excel->setActiveSheetIndex(0)->setCellValue('D4', 'NO PO');

        $excel->setActiveSheetIndex(0)->setCellValue('E4', 'NAMA BARANG');

        $excel->setActiveSheetIndex(0)->setCellValue('F4', 'QTY');

        $excel->setActiveSheetIndex(0)->setCellValue('G4', 'DEPARTMENT');



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

        foreach ($detail_po_fabrikasi as $i) {

            $this->db->select('*');

            $this->db->from('detail_po_fabrikasi');

            $this->db->where('id_po_fabrikasi', $i['id_po_fabrikasi']);

            $detail_po = $this->db->get()->result_array();

            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ($no++));

            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $i['tanggal']);

            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $i['no_po']);



            $numroww = $numrow;

            foreach ($detail_po as $k) {

                $excel->setActiveSheetIndex(0)->setCellValue('E'.$numroww, $k['nama_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('F'.$numroww, $k['jumlah_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('G'.$numroww, $k['departemen']);



                $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row_barang);

                $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row);



                $numroww = $numroww + 1;

            }



            $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.intval($numroww-1));

            $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numroww-1));

            $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.intval($numroww-1));



            $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row_full); 

            

            $numrow=$numroww;

        }



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(90);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="List Outstanding Fabrikasi .xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');



        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

    }



    public function export_excel_outstanding_cs()

    {

        $this->db->select('*');

        $this->db->from('po_cs');

        $this->db->join('detail_po_cs', 'po_cs.id_po_cs=detail_po_cs.id_po_cs');

        $this->db->group_by('po_cs.no_po');

        $this->db->order_by('po_cs.tanggal', 'ASC');

        $detail_po_cs = $this->db->get()->result_array();

        //var_dump($detail_po_fabrikasi);

        //die();



        include_once APPPATH . 'third_party/PHPExcel.php';



        $excel = new PHPExcel();



        $excel1 = new PHPExcel_Worksheet($excel, 'OUTSTANDING CS');



        // Attach the "My Data" worksheet as the first worksheet in the PHPExcel object

        $excel->addSheet($excel1, 0);



        $excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('OUTSTANDING CS')

                ->setSubject('OUTSTANDING CS')

                ->setDescription('OUTSTANDING CS')

                ->setKeyWords('OUTSTANDING CS');



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

                'color' => ['rgb' => 'd9e1f2']

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

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_barang = [

            'alignment' => [

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

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

            ]

        ];



        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'LIST OUTSTANDING CLEANING SUPPLY');

        $excel->getActiveSheet()->mergeCells('B2:G2');

        $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_title);  



        $excel->setActiveSheetIndex(0)->setCellValue('B4', 'NO');

        $excel->setActiveSheetIndex(0)->setCellValue('C4', 'TANGGAL PO');

        $excel->setActiveSheetIndex(0)->setCellValue('D4', 'NO PO');

        $excel->setActiveSheetIndex(0)->setCellValue('E4', 'NAMA BARANG');

        $excel->setActiveSheetIndex(0)->setCellValue('F4', 'QTY');

        $excel->setActiveSheetIndex(0)->setCellValue('G4', 'DEPARTMENT');

        

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

        foreach ($detail_po_cs as $i) {

            $this->db->select('*');

            $this->db->from('detail_po_cs');

            $this->db->where('id_po_cs', $i['id_po_cs']);

            $detail_po = $this->db->get()->result_array();

            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ($no++));

            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $i['tanggal']);

            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $i['no_po']);



            $numroww = $numrow;

            foreach ($detail_po as $k) {

                $excel->setActiveSheetIndex(0)->setCellValue('E'.$numroww, $k['nama_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('F'.$numroww, $k['jumlah_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('G'.$numroww, '-');



                $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row_barang);

                $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row);



                $numroww = $numroww + 1;

            }



            $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.intval($numroww-1));

            $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numroww-1));

            $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.intval($numroww-1));



            $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row_full);  

            

            $numrow=$numroww;

        }



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(90);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="List Outstanding Cleaning Supply .xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');



        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

    }



    // public function export_excel_outstanding_gs()

    // {

    //     $this->db->select('*');

    //     $this->db->from('po_gs');

    //     $this->db->join('detail_po_gs', 'po_gs.id_po_gs=detail_po_gs.id_po_gs');

    //     $this->db->group_by('po_gs.no_po');

    //     $this->db->order_by('po_gs.tanggal', 'ASC');

    //     $detail_po_gs = $this->db->get()->result_array();

    //     //var_dump($detail_po_fabrikasi);

    //     //die();



    //     include_once APPPATH . 'third_party/PHPExcel.php';



    //     $excel = new PHPExcel();



    //     $excel1 = new PHPExcel_Worksheet($excel, 'OUTSTANDING GS');



    //     // Attach the "My Data" worksheet as the first worksheet in the PHPExcel object

    //     $excel->addSheet($excel1, 0);



    //     $excel->getProperties()

    //             ->setCreator('IndoExpress')

    //             ->setLastModifiedBy('IndoExpress')

    //             ->setTitle('OUTSTANDING GS')

    //             ->setSubject('OUTSTANDING GS')

    //             ->setDescription('OUTSTANDING GS')

    //             ->setKeyWords('OUTSTANDING GS');



    //     $style_title = [

    //         'font' => ['bold' => true],

    //         'alignment' => [

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ]

    //     ];

    //     $style_title_sec = [

    //         'font' => ['bold' => true],

    //         'alignment' => [

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ]

    //     ];



    //     $style_col = [

    //         'fill' => [

    //             'type' => PHPExcel_Style_Fill::FILL_SOLID,

    //             'color' => ['rgb' => 'd9e1f2']

    //         ],

    //         'font' => ['bold' => true],

    //         'alignment' => [

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ],

    //         'borders' => [

    //             'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $style_row = [

    //         'alignment' => [

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ],

    //         'borders' => [

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $style_row_barang = [

    //         'alignment' => [

    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ],

    //         'borders' => [

    //             'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //             'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $style_row_full = [

    //         'alignment' => [

    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

    //         ],

    //         'borders' => [

    //             'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

    //         ]

    //     ];



    //     $excel->setActiveSheetIndex(0)->setCellValue('B2', 'LIST OUTSTANDING GENERAL SUPPLY');

    //     $excel->getActiveSheet()->mergeCells('B2:G2');

    //     $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_title);  



    //     $excel->setActiveSheetIndex(0)->setCellValue('B4', 'NO');

    //     $excel->setActiveSheetIndex(0)->setCellValue('C4', 'TANGGAL PO');

    //     $excel->setActiveSheetIndex(0)->setCellValue('D4', 'NO PO');

    //     $excel->setActiveSheetIndex(0)->setCellValue('E4', 'NAMA BARANG');

    //     $excel->setActiveSheetIndex(0)->setCellValue('F4', 'QTY');

    //     $excel->setActiveSheetIndex(0)->setCellValue('G4', 'DEPARTMENT');

        

    //     $excel->getActiveSheet()->getStyle('B4')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('C4')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('D4')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('E4')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('F4')->applyFromArray($style_col);

    //     $excel->getActiveSheet()->getStyle('G4')->applyFromArray($style_col);



    //     $numrow = 5;

    //     $numrow_last = 5;

    //     $no = 1;

    //     $total = 0;

    //     foreach ($detail_po_gs as $i) {

    //         $this->db->select('*');

    //         $this->db->from('detail_po_gs');

    //         $this->db->where('id_po_gs', $i['id_po_gs']);

    //         $detail_po = $this->db->get()->result_array();

    //         $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ($no++));

    //         $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $i['tanggal']);

    //         $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $i['no_po']);



    //         $numroww = $numrow;

    //         foreach ($detail_po as $k) {

    //             $excel->setActiveSheetIndex(0)->setCellValue('E'.$numroww, $k['nama_barang']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('F'.$numroww, $k['jumlah_barang']);

    //             $excel->setActiveSheetIndex(0)->setCellValue('G'.$numroww, $k['departemen']);



    //             $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row_barang);

    //             $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);

    //             $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row);



    //             $numroww = $numroww + 1;

    //         }



    //         $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.intval($numroww-1));

    //         $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numroww-1));

    //         $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.intval($numroww-1));



    //         $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row_full);

    //         $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row_full);

    //         $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row_full);

    //         $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row_full);

    //         $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row_full);

    //         $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row_full); 

            

    //         $numrow=$numroww;

    //     }



    //     $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);

    //     $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);

    //     $excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);

    //     $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17);

    //     $excel->getActiveSheet()->getColumnDimension('E')->setWidth(90);

    //     $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);

    //     $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);

    //     $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    //     header('Content-Disposition: attachment; filename="List Outstanding General Supply .xlsx"'); // Set nama file excel nya

    //     header('Cache-Control: max-age=0');



    //     $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

    //     $write->save('php://output');

    // }



    public function export_excel_outstanding_gs($cabang)

    {

        $id_cabang = $this->id_cabang($cabang);

        $this->db->select('*');

        $this->db->from('po_gs');

        $this->db->join('detail_po_gs', 'po_gs.id_po_gs=detail_po_gs.id_po_gs');

        $this->db->where('po_gs.id_cabang', $id_cabang);

        $this->db->group_by('po_gs.no_po');

        $this->db->order_by('po_gs.tanggal', 'ASC');

        $detail_po_gs = $this->db->get()->result_array();

        //var_dump($detail_po_fabrikasi);

        //die();



        include_once APPPATH . 'third_party/PHPExcel.php';



        $excel = new PHPExcel();



        $excel1 = new PHPExcel_Worksheet($excel, 'OUTSTANDING GS');



        // Attach the "My Data" worksheet as the first worksheet in the PHPExcel object

        $excel->addSheet($excel1, 0);



        $excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('OUTSTANDING GS')

                ->setSubject('OUTSTANDING GS')

                ->setDescription('OUTSTANDING GS')

                ->setKeyWords('OUTSTANDING GS');



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

                'color' => ['rgb' => 'd9e1f2']

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

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_barang = [

            'alignment' => [

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

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

            ]

        ];



        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'LIST OUTSTANDING GENERAL SUPPLY');

        $excel->getActiveSheet()->mergeCells('B2:G2');

        $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_title);  



        $excel->setActiveSheetIndex(0)->setCellValue('B4', 'NO');

        $excel->setActiveSheetIndex(0)->setCellValue('C4', 'TANGGAL PO');

        $excel->setActiveSheetIndex(0)->setCellValue('D4', 'NO PO');

        $excel->setActiveSheetIndex(0)->setCellValue('E4', 'NAMA BARANG');

        $excel->setActiveSheetIndex(0)->setCellValue('F4', 'QTY');

        $excel->setActiveSheetIndex(0)->setCellValue('G4', 'DEPARTMENT');

        

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

        foreach ($detail_po_gs as $i) {

            $this->db->select('*');

            $this->db->from('detail_po_gs');

            $this->db->where('id_po_gs', $i['id_po_gs']);

            $detail_po = $this->db->get()->result_array();

            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ($no++));

            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $i['tanggal']);

            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $i['no_po']);



            $numroww = $numrow;

            foreach ($detail_po as $k) {

                $excel->setActiveSheetIndex(0)->setCellValue('E'.$numroww, $k['nama_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('F'.$numroww, $k['jumlah_barang']);

                $excel->setActiveSheetIndex(0)->setCellValue('G'.$numroww, $k['departemen']);



                $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row_barang);

                $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row);

                $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row);



                $numroww = $numroww + 1;

            }



            $excel->getActiveSheet()->mergeCells('B'.$numrow.':B'.intval($numroww-1));

            $excel->getActiveSheet()->mergeCells('C'.$numrow.':C'.intval($numroww-1));

            $excel->getActiveSheet()->mergeCells('D'.$numrow.':D'.intval($numroww-1));



            $excel->getActiveSheet()->getStyle('B'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('C'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('D'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('E'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('F'.$numroww)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('G'.$numroww)->applyFromArray($style_row_full); 

            

            $numrow=$numroww;

        }



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(0.5);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(17);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(90);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);

        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="List Outstanding General Supply .xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');



        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

    }



    public function download_spk_fabrikasi($id_pbo)

	{

		$this->load->library('pdf');

		$data['title']		= 'Fabrikasi';

        $data['title2']		= 'Invoice';

        $data['f']		= $this->M_pbo->get_by_id($id_pbo);

		$pch_tgl			= explode('-', date('d-F-Y'));

		$bulan 				= $this->bulan($pch_tgl[1]);

        $bulan_romawi       = $this->bulan_romawi($pch_tgl[1]);

		$data['no_job']	    = 'JOB/'.$id_pbo.'/'.$data['f']['department'].'/'.$bulan_romawi.'/'.date('Y');

		$data['tanggal']	= date('d').' '.$bulan.' '.date('Y');

		

        $html_content = $this->load->view('pbo/cetak_spk', $data, true);

		

        

        $filename = 'SPK - JOB_'.$id_pbo.'_'.$data['f']['department'].'_'.$bulan_romawi.'_'.date('Y').'.pdf';



        $this->pdf->loadHtml($html_content);



        $this->pdf->set_paper('a4','potrait');

        

        $this->pdf->render();

        $this->pdf->stream($filename, ['Attachment' => 1]);

	}



    public function eksport_spk_fabrikasi($id_pbo)

    {

        include_once APPPATH . 'third_party/PHPExcel.php';

        $f		= $this->M_pbo->get_by_id($id_pbo);

		$pch_tgl			= explode('-', date('d-F-Y'));

		$bulan 				= $this->bulan($pch_tgl[1]);

        $bulan_romawi       = $this->bulan_romawi($pch_tgl[1]);

		$no_job	    = 'JOB/'.$id_pbo.'/'.$f['department'].'/'.$bulan_romawi.'/'.date('Y');

		$tanggal	= date('d').' '.$bulan.' '.date('Y');



        $excel = new PHPExcel();



        $excel2 = new PHPExcel_Worksheet($excel, 'Surat Perintah Kerja');



		// Attach the "My Data" worksheet as the first worksheet in the PHPExcel object

		$excel->addSheet($excel2, 0);



		$excel->getProperties()

                ->setCreator('IndoExpress')

                ->setLastModifiedBy('IndoExpress')

                ->setTitle('Data Invoice & SJ')

                ->setSubject('Invoice & SJ')

                ->setDescription('')

                ->setKeyWords('Invoice & SJ');



        $objDrawing3 = new PHPExcel_Worksheet_Drawing();

        $excel->setActiveSheetIndex(0);

        $objDrawing3->setWorksheet($excel->getActiveSheet());

        $objDrawing3->setCoordinates('B1');

        $objDrawing3->setName('Footer Invoice');

        $objDrawing3->setDescription('Sintesa');

        $objDrawing3->setPath('assets/img/header_spk.png');

        $objDrawing3->setWidth(180)->setHeight(180);



        $style_col = [

        	'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'd9e1f2']

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



        $style_row_top = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_left = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

            ]

        ];



        $style_row_right = [

            'alignment' => [

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],

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



        $excel->setActiveSheetIndex(0)->setCellValue('E10', $no_job);

        $excel->getActiveSheet()->mergeCells('E10:F10');

        $excel->getActiveSheet()->getStyle('E10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



        $excel->setActiveSheetIndex(0)->setCellValue('E11', $tanggal);

        $excel->getActiveSheet()->mergeCells('E11:F11');

        $excel->getActiveSheet()->getStyle('E11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 



        $excel->setActiveSheetIndex(0)->setCellValue('B13', 'NO');

        $excel->setActiveSheetIndex(0)->setCellValue('C13', 'DESKRIPSI');

        $excel->setActiveSheetIndex(0)->setCellValue('D13', 'UOM');

        $excel->setActiveSheetIndex(0)->setCellValue('E13', 'QTY');

        $excel->setActiveSheetIndex(0)->setCellValue('F13', 'PO');

        

        $excel->getActiveSheet()->getStyle('B13')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('C13')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('D13')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('E13')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('F13')->applyFromArray($style_col);



        $excel->setActiveSheetIndex(0)->setCellValue('B14', '1');

        $excel->getActiveSheet()->getStyle('B14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->mergeCells('B14:B15');

        $excel->setActiveSheetIndex(0)->setCellValue('C14', $f['deskripsi']);

        $excel->getActiveSheet()->mergeCells('C14:C15');

        $excel->setActiveSheetIndex(0)->setCellValue('D14', $f['uom']);

        $excel->getActiveSheet()->getStyle('D14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->mergeCells('D14:D15');

        

        

        $excel->setActiveSheetIndex(0)->setCellValue('E14', $f['qty']);

        $excel->getActiveSheet()->getStyle('E14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->mergeCells('E14:E15');

        

        $excel->setActiveSheetIndex(0)->setCellValue('F14', '-');

        $excel->getActiveSheet()->getStyle('F14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->mergeCells('F14:F15');



        $excel->getActiveSheet()->getStyle('B14')->applyFromArray($style_row);

        $excel->getActiveSheet()->getStyle('C14')->applyFromArray($style_row);

        $excel->getActiveSheet()->getStyle('D14')->applyFromArray($style_row);

        $excel->getActiveSheet()->getStyle('E14')->applyFromArray($style_row);

        $excel->getActiveSheet()->getStyle('F14')->applyFromArray($style_row);



        $excel->getActiveSheet()->getStyle('B15')->applyFromArray($style_row);

        $excel->getActiveSheet()->getStyle('C15')->applyFromArray($style_row);

        $excel->getActiveSheet()->getStyle('D15')->applyFromArray($style_row);

        $excel->getActiveSheet()->getStyle('E15')->applyFromArray($style_row);

        $excel->getActiveSheet()->getStyle('F15')->applyFromArray($style_row);



        $excel->setActiveSheetIndex(0)->setCellValue('B16', 'TOTAL MATERIAL');

        $excel->getActiveSheet()->getStyle('B16')->getFont()->setBold(true);

        $excel->getActiveSheet()->mergeCells('B16:D16');

        

        $excel->setActiveSheetIndex(0)->setCellValue('E16', $f['qty']);

        $excel->getActiveSheet()->getStyle('E16')->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('E16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        

        $excel->setActiveSheetIndex(0)->setCellValue('F16', '');



        $excel->getActiveSheet()->getStyle('B16')->applyFromArray($style_row_full);

        $excel->getActiveSheet()->getStyle('C16')->applyFromArray($style_row_full);

        $excel->getActiveSheet()->getStyle('D16')->applyFromArray($style_row_full);

        $excel->getActiveSheet()->getStyle('E16')->applyFromArray($style_row_full);

        $excel->getActiveSheet()->getStyle('F16')->applyFromArray($style_row_full);



        $excel->setActiveSheetIndex(0)->setCellValue('B18', 'NO');

        $excel->setActiveSheetIndex(0)->setCellValue('C18', 'BAHAN');

        $excel->setActiveSheetIndex(0)->setCellValue('D18', 'UOM');

        $excel->getActiveSheet()->mergeCells('D18:E18');

        $excel->setActiveSheetIndex(0)->setCellValue('F18', 'QTY');

        

        $excel->getActiveSheet()->getStyle('B18')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('C18')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('D18')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('E18')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('F18')->applyFromArray($style_col);



        $no = 19;

        for ($i=0; $i < 5; $i++) { 

            $excel->setActiveSheetIndex(0)->setCellValue('B'.$no, '');

            $excel->getActiveSheet()->getStyle('B'.$no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('B'.$no.':B'.intval($no+1));

            $excel->setActiveSheetIndex(0)->setCellValue('C'.$no, '');

            $excel->getActiveSheet()->mergeCells('C'.$no.':C'.intval($no+1));

            $excel->setActiveSheetIndex(0)->setCellValue('D'.$no, '');

            $excel->getActiveSheet()->getStyle('D'.$no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('D'.$no.':E'.intval($no+1));

            

            $excel->setActiveSheetIndex(0)->setCellValue('F'.$no, '');

            $excel->getActiveSheet()->getStyle('F'.$no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->mergeCells('F'.$no.':F'.intval($no+1));



            $excel->getActiveSheet()->getStyle('B'.$no)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('C'.$no)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('D'.$no)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('E'.$no)->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('F'.$no)->applyFromArray($style_row_full);



            $excel->getActiveSheet()->getStyle('B'.intval($no+1))->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('C'.intval($no+1))->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('D'.intval($no+1))->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('E'.intval($no+1))->applyFromArray($style_row_full);

            $excel->getActiveSheet()->getStyle('F'.intval($no+1))->applyFromArray($style_row_full);



            $no = $no + 2;

        }



        $numrow = $no;

        $numrow_ttd = $numrow+1;



        $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow);

        $excel->getActiveSheet()->mergeCells('B'.$numrow.':F'.$numrow);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('B'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $excel->getActiveSheet()->getStyle('B'.$numrow.':F'.$numrow)->applyFromArray($style_row_top);



        $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow_ttd, 'HORMAT KAMI');

        $excel->getActiveSheet()->mergeCells('B'.$numrow_ttd.':C'.$numrow_ttd);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getFont()->setBold(true);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getFont()->setSize(12);

        $excel->getActiveSheet()->getStyle('B'.$numrow_ttd)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



        $excel->getActiveSheet()->mergeCells('B'.intval($numrow_ttd+6).':C'.intval($numrow_ttd+6));

        $excel->setActiveSheetIndex(0)->setCellValue('B'.intval($numrow_ttd+6), 'EKA IMAM MAULANA');

        $excel->getActiveSheet()->getStyle('B'.intval($numrow_ttd+6))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



        $objDrawing3 = new PHPExcel_Worksheet_Drawing();

        $excel->setActiveSheetIndex(0);

        $objDrawing3->setWorksheet($excel->getActiveSheet());

        $objDrawing3->setCoordinates('B'.intval($numrow_ttd+9));

        $objDrawing3->setName('Footer Invoice');

        $objDrawing3->setDescription('Sintesa');

        $objDrawing3->setPath('assets/img/footer_surat.png');

        $objDrawing3->setWidth(150)->setHeight(150);



        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(1);

        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(4);

        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(50);

        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(8);

        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(8);

        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);

        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(26);

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="SPK JOB_'.$id_pbo.'_'.$f['department'].'_'.$bulan_romawi.'_'.date('Y').' .xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');



        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

    }



    // public function eksport_spk_fabrikasi_to_pdf($id_detail_po_fabrikasi)

	// {

	// 	$this->load->library('pdf');

	// 	$data['title']		= 'Fabrikasi';

    //     $data['title2']		= 'Invoice';

    //     $this->db->select('*');

    //     $this->db->from('detail_po_fabrikasi');

    //     $this->db->join('po_fabrikasi', 'po_fabrikasi.id_po_fabrikasi=detail_po_fabrikasi.id_po_fabrikasi');

    //     $this->db->where('detail_po_fabrikasi.id_detail_po_fabrikasi', $id_detail_po_fabrikasi);

    //     $data['f']		= $this->db->get()->row_array();

	// 	$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));

	// 	$bulan 				= $this->bulan($pch_tgl[1]);

    //     $bulan_romawi       = $this->bulan_romawi($pch_tgl[1]);

	// 	$data['no_job']	    = 'JOB/'.$id_detail_po_fabrikasi.'/'.$data['f']['nama_user'].'/'.$bulan_romawi.'/'.date('Y', strtotime($data['f']['tanggal']));

	// 	$data['tanggal']	= date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));

		

    //     $html_content = $this->load->view('outstanding-fabrikasi/cetak_spk', $data, true);

		

        

    //     $filename = 'SPK - JOB_'.$id_detail_po_fabrikasi.'_'.$data['f']['nama_user'].'_'.$bulan_romawi.'_'.date('Y', strtotime($data['f']['tanggal'])).'.pdf';



    //     $this->pdf->loadHtml($html_content);



    //     $this->pdf->set_paper('a4','potrait');

        

    //     $this->pdf->render();

    //     $this->pdf->stream($filename, ['Attachment' => 1]);

	// }



    public function eksport_spk_fabrikasi_to_pdf($id_detail_po_fabrikasi, $cabang)

	{

        $cab = $this->M_cabang->get_by('slug', $cabang);

		$this->load->library('pdf');

		$data['title']		= 'Fabrikasi';

        $data['title2']		= 'Invoice';

        $this->db->select('*');

        $this->db->from('detail_po_fabrikasi');

        $this->db->join('po_fabrikasi', 'po_fabrikasi.id_po_fabrikasi=detail_po_fabrikasi.id_po_fabrikasi');

        $this->db->where('detail_po_fabrikasi.id_cabang', $cab['id_cabang']);

        $this->db->where('detail_po_fabrikasi.id_detail_po_fabrikasi', $id_detail_po_fabrikasi);

        $data['f']		= $this->db->get()->row_array();

		$pch_tgl			= explode('-', date('d-F-Y', strtotime($data['f']['tanggal'])));

		$bulan 				= $this->bulan($pch_tgl[1]);

        $bulan_romawi       = $this->bulan_romawi($pch_tgl[1]);

		$data['no_job']	    = 'JOB/'.$id_detail_po_fabrikasi.'/'.$data['f']['nama_user'].'/'.$bulan_romawi.'/'.date('Y', strtotime($data['f']['tanggal']));

		$data['tanggal']	= date('d', strtotime($data['f']['tanggal'])).' '.$bulan.' '.date('Y', strtotime($data['f']['tanggal']));

		

        $html_content = $this->load->view('outstanding-fabrikasi/cetak_spk', $data, true);

		

        

        $filename = 'SPK - JOB_'.$id_detail_po_fabrikasi.'_'.$data['f']['nama_user'].'_'.$bulan_romawi.'_'.date('Y', strtotime($data['f']['tanggal'])).'.pdf';



        $this->pdf->loadHtml($html_content);



        $this->pdf->set_paper('a4','potrait');

        

        $this->pdf->render();

        $this->pdf->stream($filename, ['Attachment' => 1]);

	}



    private function penyebut($nilai)

    {

		$nilai = abs($nilai);

		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");

		$temp = "";

		if ($nilai < 12) {

			$temp = " ". $huruf[$nilai];

		} else if ($nilai <20) {

			$temp = $this->penyebut($nilai - 10). " belas";

		} else if ($nilai < 100) {

			$temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);

		} else if ($nilai < 200) {

			$temp = " seratus" . $this->penyebut($nilai - 100);

		} else if ($nilai < 1000) {

			$temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);

		} else if ($nilai < 2000) {

			$temp = " seribu" . $this->penyebut($nilai - 1000);

		} else if ($nilai < 1000000) {

			$temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);

		} else if ($nilai < 1000000000) {

			$temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);

		} else if ($nilai < 1000000000000) {

			$temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));

		} else if ($nilai < 1000000000000000) {

			$temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));

		}     

		return $temp;

	}

 

	private function terbilang($nilai) {

		if($nilai<0) {

			$hasil = "minus ". trim($this->penyebut($nilai))." RUPIAH";

		} else {

			$hasil = trim($this->penyebut($nilai))." RUPIAH";

		}     		

		return $hasil;

	}



	private function validation_no()

	{

		$this->form_validation->set_rules('no_quot', 'No. Quotation', 'required|trim');

		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');

	}



	private function validation()

	{

		$this->form_validation->set_rules('deskripsi', 'deskripsi', 'required|trim');

		$this->form_validation->set_rules('qty', 'qty', 'required|trim');

		$this->form_validation->set_rules('uom', 'uom', 'required|trim');

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



    private function bulan_romawi($bulan)

    {

        $bulan=$bulan;

        switch ($bulan) {

          case 'January':

            $bulan= "I";

            break;

          case 'February':

            $bulan= "II";

            break;

          case 'March':

            $bulan= "III";

            break;

          case 'April':

            $bulan= "IV";

            break;

          case 'May':

            $bulan= "V";

            break;

          case 'June':

            $bulan= "VI";

            break;

          case 'July':

            $bulan= "VII";

            break;

          case 'August':

            $bulan= "VIII";

            break;

          case 'September':

            $bulan= "IX";

            break;

          case 'October':

            $bulan= "X";

            break;

          case 'November':

            $bulan= "XI";

            break;

          case 'December':

            $bulan= "XII";

            break;

          default:

            $bulan= "Isi variabel tidak di temukan";

            break;

        }



        return $bulan;

    }



}

