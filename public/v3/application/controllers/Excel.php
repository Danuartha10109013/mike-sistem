<?php

defined('BASEPATH') OR exit('No direct script access allowed');

  class Excel extends CI_Controller {

    function index(){


      $data['invoice']  = $this->M_fabrikasi->get()->result_array();

      $this->load->view("excel", $data);

    }

    function action(){


      $this->load->library("excel");

      $object = new PHPExcel();

      $object->setActiveSheetIndex(0);

      $table_columns = array("Name", "Email");

      $column = 0;

      foreach($table_columns as $field){

        $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);

        $column++;

      }

      $invoice  = $this->M_fabrikasi->get()->result_array();
      $excel_row = 2;

      foreach($invoice as $row){

        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row['no_invoice']);

        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['tanggal']);

        $excel_row++;

      }

      $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');

      header('Content-Type: application/vnd.ms-excel');

      header('Content-Disposition: attachment;filename="Employee Data.xls"');

      $object_writer->save('php://output');

    }

  }