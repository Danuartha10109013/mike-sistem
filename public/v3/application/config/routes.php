<?php

defined('BASEPATH') or exit('No direct script access allowed');



/*

| -------------------------------------------------------------------------

| URI ROUTING

| -------------------------------------------------------------------------

| This file lets you re-map URI requests to specific controller functions.

|

| Typically there is a one-to-one relationship between a URL string

| and its corresponding controller class/method. The segments in a

| URL normally follow this pattern:

|

|	example.com/class/method/id/

|

| In some instances, however, you may want to remap this relationship

| so that a different class/function is called than the one

| corresponding to the URL.

|

| Please see the user guide for complete details:

|

|	https://codeigniter.com/user_guide/general/routing.html

|

| -------------------------------------------------------------------------

| RESERVED ROUTES

| -------------------------------------------------------------------------

|

| There are three reserved routes:

|

|	$route['default_controller'] = 'welcome';

|

| This route indicates which controller class should be loaded if the

| URI contains no data. In the above example, the "welcome" class

| would be loaded.

|

|	$route['404_override'] = 'errors/page_missing';

|

| This route will tell the Router which controller/method to use if those

| provided in the URL cannot be matched to a valid route.

|

|	$route['translate_uri_dashes'] = FALSE;

|

| This is not exactly a route, but allows you to automatically route

| controller and method names that contain dashes. '-' isn't a valid

| class or method name character, so it requires translation.

| When you set this option to TRUE, it will replace ALL dashes in the

| controller and method URI segments.

|

| Examples:	my-controller/index	-> my_controller/index

|		my-controller/my-method	-> my_controller/my_method

*/

$route['default_controller'] = 'Login';

$route['404_override'] = '';

$route['translate_uri_dashes'] = FALSE;



$route['login']         = 'Login/proses';

$route['logout']         = 'Login/logout';

$route['dashboard']        = 'Dashboard';



//admin
$route['kelola-user']           = 'User';
$route['tambah-user']           = 'User/tambah';
$route['tambah-user-modal']     = 'User/tambah_modal';
$route['edit-user/(:any)']      = 'User/edit/$1';
$route['hapus-user/(:any)']     = 'User/hapus/$1';
$route['setting']               = 'User/setting';

$route['kelola-user-agen']           = 'UserAgen';
$route['tambah-user-agen']           = 'UserAgen/tambah';
$route['edit-user-agen/(:any)']      = 'UserAgen/edit/$1';
$route['hapus-user-agen/(:any)']     = 'UserAgen/hapus/$1';

$route['barang']                    = 'Barang/index2';
$route['tambah-barang']             = 'Barang/tambah';
$route['edit-barang/(:any)']        = 'Barang/edit/$1';
$route['hapus-barang/(:any)']       = 'Barang/hapus/$1';

$route['barang-masuk']                  = 'BarangMasuk';
$route['tambah-barang-masuk']           = 'BarangMasuk/tambah';
$route['edit-barang-masuk/(:any)']      = 'BarangMasuk/edit/$1';
$route['hapus-barang-masuk/(:any)']     = 'BarangMasuk/hapus/$1';

$route['stok-barang']                   = 'StokBarang';
$route['detail-stok-barang/(:any)']     = 'StokBarang/detail_stok_barang/$1';
$route['ekspor-stok-barang']            = 'StokBarang/export_excel_stok_barang';

$route['stok-opname']                   = 'StokOpname';
$route['tambah-stok-opname']            = 'StokOpname/tambah';
$route['edit-stok-opname/(:any)']       = 'StokOpname/edit/$1';
$route['hapus-stok-opname/(:any)']      = 'StokOpname/hapus/$1';
$route['eksport-stok-opname']      = 'StokOpname/export_excel_stok_opname';

$route['data-order']           = 'Order';
$route['hapus-order/(:any)']   = 'Order/hapus/$1';
$route['order/riwayat']        = 'Order/riwayat';
$route['ekspor-order']         = 'Cetak/export_excel';

$route['detail-order/(:any)']               = 'Order/detail/$1';
$route['tambah-detail-order/(:any)']        = 'Order/tambah_detail/$1';
$route['edit-detail-order/(:any)/(:any)']   = 'Order/edit_detail/$1/$2';
$route['hapus-detail-order/(:any)/(:any)']  = 'Order/hapus_detail/$1/$2';

$route['riwayat-pesanan']                     = 'RiwayatOrder';
$route['riwayat-invoice']                     = 'RiwayatInvoice';
$route['status-tagihan']                      = 'StatusTagihan';

$route['laporan-pemasukan-stok']                     = 'ReportStokMasuk';

$route['laporan-pengeluaran-stok']                   = 'ReportStokKeluar';
$route['laporan-pendapatan-finance']                 = 'ReportPendapatanFinance';

$route['invoice']                   = 'Invoice';
$route['tambah-invoice']            = 'Invoice/tambah';
$route['edit-invoice/(:any)']       = 'Invoice/edit/$1';
$route['hapus-invoice/(:any)']      = 'Invoice/hapus/$1';
$route['ubah-status-kirim/(:any)/(:any)'] = 'Invoice/editStatusKirim/$1/$2';

$route['return']                   = 'Retur';
$route['tambah-return']            = 'Retur/tambah';
$route['edit-return/(:any)']       = 'Retur/edit/$1';
$route['hapus-return/(:any)']      = 'Retur/hapus/$1';

$route['detail-invoice/(:any)']                  = 'Invoice/detail/$1';
$route['tambah-detail-invoice/(:any)']           = 'Invoice/tambah_detail_invoice/$1';
$route['edit-detail-invoice/(:any)/(:any)']      = 'Invoice/edit_detail/$1/$2';
$route['hapus-detail-invoice/(:any)/(:any)/(:any)'] = 'Invoice/hapus_detail/$1/$2/$3';
$route['include-ppn/(:any)']                     = 'Invoice/include_ppn/$1';
$route['update-status-invoice/(:any)/(:any)']    = 'Invoice/edit_status/$1/$2';
$route['tambah-diskon-invoice']                  = 'Invoice/tambah_diskon';
$route['tambah-driver-invoice']                  = 'Invoice/tambah_driver';
$route['cetak-detail-invoice/(:any)']            = 'Invoice/cetak_detail_invoice/$1';
$route['cetak-detail-surat-jalan-stiker/(:any)']     = 'Invoice/cetak_detail_surat_jalan_stiker/$1';
$route['cetak-detail-surat-jalan/(:any)']        = 'Invoice/cetak_detail_surat_jalan/$1';

$route['package']                    = 'Package/index';
$route['tambah-package']             = 'Package/tambah';
$route['edit-package/(:any)']        = 'Package/edit/$1';
$route['hapus-package/(:any)']       = 'Package/hapus/$1';


$route['shipment']                    = 'Shipment/index';
$route['tambah-shipment']             = 'Shipment/tambah';
$route['edit-shipment/(:any)']        = 'Shipment/edit/$1';
$route['hapus-shipment/(:any)']       = 'Shipment/hapus/$1';

$route['pembelian']                   = 'Pembelian';
$route['edit-pembelian/(:any)']       = 'Pembelian/edit/$1';
$route['hapus-pembelian/(:any)']      = 'Pembelian/hapus/$1';

$route['detail-pembelian/(:any)']                  = 'Pembelian/detail/$1';
$route['tambah-detail-pembelian/(:any)']           = 'Pembelian/tambah_detail/$1';
$route['hapus-detail-pembelian/(:any)/(:any)'] = 'Pembelian/hapus_detail/$1/$2';
$route['hapus-detail-pembelian-bayar/(:any)/(:any)'] = 'Pembelian/hapus_detail_bayar/$1/$2';


$route['detail-penjualan/(:any)']                  = 'Penjualan/detail/$1';
$route['detail-pembayaran-distributor/(:any)']                  = 'Penjualan/detail_pembayaran_distributor/$1';
$route['edit-inv-pembayaran']                  = 'Penjualan/edit_inv';
$route['tambah-detail-penjualan/(:any)']           = 'Penjualan/tambah_detail/$1';
$route['hapus-detail-penjualan/(:any)/(:any)'] = 'Penjualan/hapus_detail/$1/$2';
$route['hapus-penjualan-bayar/(:any)/(:any)'] = 'Penjualan/hapus_bayar/$1/$2';

// $route['cabang']                = 'Cabang';

// $route['tambah-cabang']         = 'Cabang/tambah';

// $route['tambah-cabang-modal']   = 'Cabang/tambah_modal';

// $route['edit-cabang/(:any)']    = 'Cabang/edit/$1';

// $route['hapus-cabang/(:any)']   = 'Cabang/hapus/$1';



// $route['detail-cabang/(:any)']          = 'DetailCabang/index/$1';

// $route['tambah-detail-cabang/(:any)']   = 'DetailCabang/tambah/$1';

// $route['hapus-detail-cabang/(:any)/(:any)']     = 'DetailCabang/hapus/$1/$2';



// $route['departemen']                = 'Departemen';

// $route['tambah-departemen']         = 'Departemen/tambah';

// $route['edit-departemen/(:any)']    = 'Departemen/edit/$1';

// $route['hapus-departemen/(:any)']   = 'Departemen/hapus/$1';



// // $route['barang']         = 'Barang';

// $route['barang']         = 'Barang/index2';

// $route['tambah-barang']     = 'Barang/tambah';

// $route['edit-barang/(:any)']   = 'Barang/edit/$1';

// $route['hapus-barang/(:any)']   = 'Barang/hapus/$1';



// $route['no-quotation']         = 'Quotation';

// $route['tambah-no-quotation']     = 'Quotation/tambah_no_quot';

// $route['edit-no-quotation/(:any)']   = 'Quotation/edit_no_quot/$1';

// $route['hapus-no-quotation/(:any)']   = 'Quotation/hapus_no_quot/$1';

// $route['copy-quotation/(:any)']   = 'Quotation/copy/$1';



// $route['all-quotation']         = 'Quotation/all_quotation';

// $route['quotation/(:any)']         = 'Quotation/quotation/$1';

// $route['tambah-quotation/(:any)']     = 'Quotation/tambah/$1';

// $route['edit-quotation/(:any)/(:any)']   = 'Quotation/edit/$1/$2';

// $route['hapus-quotation/(:any)/(:any)']   = 'Quotation/hapus/$1/$2';



// $route['biodata/(:any)']         = 'Biodata/index/$1';

// $route['tambah-biodata']     = 'Biodata/tambah';

// $route['edit-biodata/(:any)']   = 'Biodata/edit/$1';

// $route['hapus-biodata/(:any)']   = 'Biodata/hapus/$1';

// $route['detail-biodata/(:any)']   = 'Biodata/detail/$1';



// $route['detail-quotation/(:any)/(:any)']         = 'Quotation/detail/$1/$2';

// $route['tambah-detail-quotation/(:any)/(:any)']     = 'Quotation/tambah_detail/$1/$2';

// $route['edit-detail-quotation/(:any)/(:any)/(:any)']   = 'Quotation/edit_detail/$1/$2/$3';

// $route['hapus-detail-quotation/(:any)/(:any)/(:any)']   = 'Quotation/hapus_detail/$1/$2/$3';

// $route['ekspor-quotation/(:any)']         = 'Cetak/eksport_quotation/$1';

// $route['ekspor-quotation/(:any)/(:any)']         = 'Cetak/eksport_quotation/$1/$2';



// $route['outstanding-fabrikasi']         = 'Fabrikasi/outstanding';

// $route['tambah-outstanding-fabrikasi']       = 'Fabrikasi/tambah_outstanding';

// $route['edit-outstanding-fabrikasi/(:any)']   = 'Fabrikasi/edit_outstanding/$1';

// $route['hapus-outstanding-fabrikasi/(:any)']     = 'Fabrikasi/hapus_outstanding/$1';

// $route['outstanding-fabrikasi/riwayat']      = 'Fabrikasi/riwayat_outstanding';

// $route['ekspor-outstanding-fabrikasi']         = 'Cetak/export_excel_outstanding_fabrikasi';



// // CABANG

// $route['outstanding-fabrikasi/(:any)']              = 'Fabrikasi/outstanding/$1';

// $route['tambah-outstanding-fabrikasi/(:any)']       = 'Fabrikasi/tambah_outstanding/$1';

// $route['edit-outstanding-fabrikasi/(:any)/(:any)']  = 'Fabrikasi/edit_outstanding/$1/$2';

// $route['hapus-outstanding-fabrikasi/(:any)/(:any)'] = 'Fabrikasi/hapus_outstanding/$1/$2';

// $route['outstanding-fabrikasi/riwayat/(:any)']      = 'Fabrikasi/riwayat_outstanding/$1';

// $route['ekspor-outstanding-fabrikasi/(:any)']       = 'Cetak/export_excel_outstanding_fabrikasi/$1';

// $route['detail-outstanding-fabrikasi/(:any)/(:any)']     = 'Fabrikasi/detail_outstanding/$1/$2';

// $route['tambah-detail-outstanding-fabrikasi/(:any)/(:any)'] = 'Fabrikasi/tambah_detail_outstanding/$1/$2';

// $route['edit-detail-outstanding-fabrikasi/(:any)/(:any)/(:any)']   = 'Fabrikasi/edit_detail_outstanding/$1/$2/$3';

// $route['hapus-detail-outstanding-fabrikasi/(:any)/(:any)/(:any)'] = 'Fabrikasi/hapus_detail_outstanding/$1/$2/$3';

// $route['download-spk-fabrikasi-to-pdf/(:any)/(:any)']     = 'Cetak/eksport_spk_fabrikasi_to_pdf/$1/$2';

// // CABANG



// $route['detail-outstanding-fabrikasi/(:any)']     = 'Fabrikasi/detail_outstanding/$1';

// $route['tambah-detail-outstanding-fabrikasi/(:any)'] = 'Fabrikasi/tambah_detail_outstanding/$1';

// $route['edit-detail-outstanding-fabrikasi/(:any)/(:any)']   = 'Fabrikasi/edit_detail_outstanding/$1/$2';

// $route['hapus-detail-outstanding-fabrikasi/(:any)/(:any)'] = 'Fabrikasi/hapus_detail_outstanding/$1/$2';

// $route['download-spk-fabrikasi-to-pdf/(:any)']     = 'Cetak/eksport_spk_fabrikasi_to_pdf/$1';

// $route['download-spk-fabrikasi/(:any)']     = 'Cetak/eksport_spk_fabrikasi/$1';



// // cabang

// $route['invoice-fabrikasi/(:any)']                              = 'Fabrikasi/index/$1';

// $route['tambah-invoice-fabrikasi/(:any)']                       = 'Fabrikasi/tambah/$1';

// $route['detail-invoice-fabrikasi/(:any)/(:any)']                = 'Fabrikasi/detail/$1/$2';

// $route['edit-invoice-fabrikasi/(:any)/(:any)']                  = 'Fabrikasi/edit/$1/$2';

// $route['hapus-invoice-fabrikasi/(:any)/(:any)']                 = 'Fabrikasi/hapus/$1/$2';

// $route['tambah-detail-invoice-fabrikasi/(:any)/(:any)']         = 'Fabrikasi/tambah_detail/$1/$2';

// $route['edit-detail-invoice-fabrikasi/(:any)/(:any)/(:any)']    = 'Fabrikasi/edit_detail/$1/$2/$3';

// $route['hapus-detail-invoice-fabrikasi/(:any)/(:any)/(:any)']   = 'Fabrikasi/hapus_detail/$1/$2/$3';

// // cabang



// $route['invoice-fabrikasi']         = 'Fabrikasi';

// $route['tambah-invoice-fabrikasi']       = 'Fabrikasi/tambah';

// $route['edit-invoice-fabrikasi/(:any)']   = 'Fabrikasi/edit/$1';

// $route['hapus-invoice-fabrikasi/(:any)']     = 'Fabrikasi/hapus/$1';

// $route['include-ppn-fabrikasi/(:any)']     = 'Fabrikasi/include_ppn/$1';



// $route['invoice-fabrikasi-aj']         = 'Fabrikasi/aj';

// $route['tambah-invoice-fabrikasi-aj']       = 'Fabrikasi/tambah_aj';

// $route['edit-invoice-fabrikasi-aj/(:any)']   = 'Fabrikasi/edit_aj/$1';

// $route['hapus-invoice-fabrikasi-aj/(:any)']     = 'Fabrikasi/hapus_aj/$1';



// $route['detail-invoice-fabrikasi/(:any)']     = 'Fabrikasi/detail/$1';

// $route['tambah-detail-invoice-fabrikasi/(:any)'] = 'Fabrikasi/tambah_detail/$1';

// $route['edit-detail-invoice-fabrikasi/(:any)/(:any)']   = 'Fabrikasi/edit_detail/$1/$2';

// $route['hapus-detail-invoice-fabrikasi/(:any)/(:any)'] = 'Fabrikasi/hapus_detail/$1/$2';



// $route['cetak-detail-invoice-fabrikasi/(:any)']   = 'Cetak/cetak_invoice_fabrikasi/$1';

// $route['cetak-detail-surat-jalan-fabrikasi/(:any)'] = 'Cetak/cetak_surat_jalan_fabrikasi/$1';

// $route['cetak-detail-invoice-fabrikasi/(:any)/(:any)']   = 'Cetak/cetak_invoice_fabrikasi/$1/$2';

// $route['cetak-detail-surat-jalan-fabrikasi/(:any)/(:any)'] = 'Cetak/cetak_surat_jalan_fabrikasi/$1/$2';

// $route['ekspor-detail-invoice-fabrikasi/(:any)']   = 'Cetak/export_excel_invoice_fabrikasi/$1';



// // CABANG

// $route['bc-fabrikasi/(:any)']           = 'Fabrikasi/bc/$1';

// $route['tambah-bc-fabrikasi/(:any)']    = 'Fabrikasi/tambah_bc/$1';



// $route['detail-bc-fabrikasi/(:any)/(:any)']                 = 'Fabrikasi/detail_bc/$1/$2';

// $route['tambah-detail-bc-fabrikasi/(:any)/(:any)']          = 'Fabrikasi/tambah_detail_bc/$1/$2';

// $route['edit-detail-bc-fabrikasi/(:any)/(:any)/(:any)']     = 'Fabrikasi/edit_detail_bc/$1/$2/$3';

// $route['hapus-detail-bc-fabrikasi/(:any)/(:any)/(:any)']    = 'Fabrikasi/hapus_detail_bc/$1/$2/$3';

// $route['ekspor-detail-bc-fabrikasi/(:any)/(:any)']          = 'Cetak/export_excel_bc_fabrikasi/$1/$2';

// $route['ekspor-detail-bc-fabrikasi-new/(:any)/(:any)']      = 'Cetak/export_excel_bc_fabrikasi_new/$1/$2';

// // CABANG



// $route['bc-fabrikasi']         = 'Fabrikasi/bc';

// $route['tambah-bc-fabrikasi']       = 'Fabrikasi/tambah_bc';

// $route['edit-bc-fabrikasi/(:any)']   = 'Fabrikasi/edit_bc/$1';

// $route['hapus-bc-fabrikasi/(:any)']     = 'Fabrikasi/hapus_bc/$1';



// $route['detail-bc-fabrikasi/(:any)']   = 'Fabrikasi/detail_bc/$1';

// $route['tambah-detail-bc-fabrikasi/(:any)']   = 'Fabrikasi/tambah_detail_bc/$1';

// $route['edit-detail-bc-fabrikasi/(:any)/(:any)']   = 'Fabrikasi/edit_detail_bc/$1/$2';

// $route['hapus-detail-bc-fabrikasi/(:any)/(:any)']   = 'Fabrikasi/hapus_detail_bc/$1/$2';

// $route['ekspor-detail-bc-fabrikasi/(:any)']   = 'Cetak/export_excel_bc_fabrikasi/$1';

// $route['ekspor-detail-bc-fabrikasi-new/(:any)']   = 'Cetak/export_excel_bc_fabrikasi_new/$1';



// // CABANG

// $route['bap-fabrikasi/(:any)']              = 'Fabrikasi/bap/$1';

// $route['tambah-bap-fabrikasi/(:any)']       = 'Fabrikasi/tambah_bap/$1';

// $route['edit-bap-fabrikasi/(:any)/(:any)']  = 'Fabrikasi/edit_bap/$1/$2';

// $route['hapus-bap-fabrikasi/(:any)/(:any)'] = 'Fabrikasi/hapus_bap/$1/$2';



// $route['detail-bap-fabrikasi/(:any)/(:any)']                = 'Fabrikasi/detail_bap/$1/$2';

// $route['tambah-detail-bap-fabrikasi/(:any)/(:any)']         = 'Fabrikasi/tambah_detail_bap/$1/$2';

// $route['edit-detail-bap-fabrikasi/(:any)/(:any)/(:any)']    = 'Fabrikasi/edit_detail_bap/$1/$2/$3';

// $route['hapus-detail-bap-fabrikasi/(:any)/(:any)/(:any)']   = 'Fabrikasi/hapus_detail_bap/$1/$2/$3';

// $route['cetak-detail-bap-fabrikasi/(:any)/(:any)']          = 'Fabrikasi/cetak_bap/$1/$2';

// // CABANG



// $route['bap-fabrikasi']         = 'Fabrikasi/bap';

// $route['tambah-bap-fabrikasi']       = 'Fabrikasi/tambah_bap';

// $route['edit-bap-fabrikasi/(:any)']   = 'Fabrikasi/edit_bap/$1';

// $route['hapus-bap-fabrikasi/(:any)']     = 'Fabrikasi/hapus_bap/$1';



// $route['detail-bap-fabrikasi/(:any)']   = 'Fabrikasi/detail_bap/$1';

// $route['tambah-detail-bap-fabrikasi/(:any)']   = 'Fabrikasi/tambah_detail_bap/$1';

// $route['edit-detail-bap-fabrikasi/(:any)/(:any)']   = 'Fabrikasi/edit_detail_bap/$1/$2';

// $route['hapus-detail-bap-fabrikasi/(:any)/(:any)']   = 'Fabrikasi/hapus_detail_bap/$1/$2';

// $route['cetak-detail-bap-fabrikasi/(:any)']   = 'Fabrikasi/cetak_bap/$1';



// $route['outstanding-cs']         = 'Cs/outstanding';

// $route['tambah-outstanding-cs']       = 'Cs/tambah_outstanding';

// $route['edit-outstanding-cs/(:any)']   = 'Cs/edit_outstanding/$1';

// $route['hapus-outstanding-cs/(:any)']     = 'Cs/hapus_outstanding/$1';

// $route['outstanding-cs/riwayat']      = 'Cs/riwayat_outstanding';

// $route['ekspor-outstanding-cs']         = 'Cetak/export_excel_outstanding_cs';



// $route['detail-outstanding-cs/(:any)']     = 'Cs/detail_outstanding/$1';

// $route['tambah-detail-outstanding-cs/(:any)'] = 'Cs/tambah_detail_outstanding/$1';

// $route['edit-detail-outstanding-cs/(:any)/(:any)']   = 'Cs/edit_detail_outstanding/$1/$2';

// $route['hapus-detail-outstanding-cs/(:any)/(:any)'] = 'Cs/hapus_detail_outstanding/$1/$2';



// $route['invoice-cs']         = 'Cs';

// $route['tambah-invoice-cs']     = 'Cs/tambah';

// $route['edit-invoice-cs/(:any)']   = 'Cs/edit/$1';

// $route['hapus-invoice-cs/(:any)']   = 'Cs/hapus/$1';



// $route['detail-invoice-cs/(:any)']       = 'Cs/detail/$1';

// $route['tambah-detail-invoice-cs/(:any)']  = 'Cs/tambah_detail/$1';

// $route['edit-detail-invoice-cs/(:any)/(:any)']   = 'Cs/edit_detail/$1/$2';

// $route['hapus-detail-invoice-cs/(:any)/(:any)']   = 'Cs/hapus_detail/$1/$2';



// $route['cetak-detail-invoice-cs/(:any)']   = 'Cetak/cetak_invoice_cs/$1';

// $route['cetak-detail-surat-jalan-cs/(:any)'] = 'Cetak/cetak_surat_jalan_cs/$1';

// $route['ekspor-detail-invoice-cs/(:any)']   = 'Cetak/export_excel_invoice_cs/$1';



// $route['bc-cs']         = 'Cs/bc';

// $route['tambah-bc-cs']       = 'Cs/tambah_bc';

// $route['edit-bc-cs/(:any)']   = 'Cs/edit_bc/$1';

// $route['hapus-bc-cs/(:any)']     = 'Cs/hapus_bc/$1';



// $route['detail-bc-cs/(:any)']       = 'Cs/detail_bc/$1';

// $route['tambah-detail-bc-cs/(:any)']   = 'Cs/tambah_detail_bc/$1';

// $route['edit-detail-bc-cs/(:any)/(:any)']   = 'Cs/edit_detail_bc/$1/$2';

// $route['hapus-detail-bc-cs/(:any)/(:any)']   = 'Cs/hapus_detail_bc/$1/$2';

// $route['ekspor-detail-bc-cs/(:any)']   = 'Cetak/export_excel_bc_cs/$1';



// //Genera; Supply

// $route['outstanding-gs']         = 'Gs/outstanding';

// $route['tambah-outstanding-gs']       = 'Gs/tambah_outstanding';

// $route['edit-outstanding-gs/(:any)']   = 'Gs/edit_outstanding/$1';

// $route['hapus-outstanding-gs/(:any)']     = 'Gs/hapus_outstanding/$1';

// $route['outstanding-gs/riwayat']      = 'Gs/riwayat_outstanding';

// $route['ekspor-outstanding-gs']         = 'Cetak/export_excel_outstanding_gs';



// // CABANG

// $route['outstanding-gs/(:any)']                 = 'Gs/outstanding/$1';

// $route['tambah-outstanding-gs/(:any)']          = 'Gs/tambah_outstanding/$1';

// $route['edit-outstanding-gs/(:any)/(:any)']     = 'Gs/edit_outstanding/$1/$2';

// $route['hapus-outstanding-gs/(:any)/(:any)']    = 'Gs/hapus_outstanding/$1/$2';

// $route['outstanding-gs/riwayat/(:any)']         = 'Gs/riwayat_outstanding/$i';

// $route['ekspor-outstanding-gs/(:any)']          = 'Cetak/export_excel_outstanding_gs/$1';



// $route['detail-outstanding-gs/(:any)/(:any)']               = 'Gs/detail_outstanding/$1/$2';

// $route['tambah-detail-outstanding-gs/(:any)/(:any)']        = 'Gs/tambah_detail_outstanding/$1/$2';

// $route['edit-detail-outstanding-gs/(:any)/(:any)/(:any)']   = 'Gs/edit_detail_outstanding/$1/$2/$3';

// $route['hapus-detail-outstanding-gs/(:any)/(:any)/(:any)']  = 'Gs/hapus_detail_outstanding/$1/$2/$3';

// // CABANG



// $route['detail-outstanding-gs/(:any)']     = 'Gs/detail_outstanding/$1';

// $route['tambah-detail-outstanding-gs/(:any)'] = 'Gs/tambah_detail_outstanding/$1';

// $route['edit-detail-outstanding-gs/(:any)/(:any)']   = 'Gs/edit_detail_outstanding/$1/$2';

// $route['hapus-detail-outstanding-gs/(:any)/(:any)'] = 'Gs/hapus_detail_outstanding/$1/$2';



// $route['invoice-gs']         = 'Gs';

// $route['tambah-invoice-gs']     = 'Gs/tambah';

// $route['edit-invoice-gs/(:any)']   = 'Gs/edit/$1';

// $route['hapus-invoice-gs/(:any)']   = 'Gs/hapus/$1';



// $route['invoice-gs-aj']         = 'Gs/aj';

// $route['tambah-invoice-gs-aj']     = 'Gs/tambah_aj';

// $route['edit-invoice-gs-aj/(:any)']   = 'Gs/edit_aj/$1';

// $route['hapus-invoice-gs-aj/(:any)']   = 'Gs/hapus_aj/$1';



// $route['detail-invoice-gs/(:any)']       = 'Gs/detail/$1';

// $route['tambah-detail-invoice-gs/(:any)']  = 'Gs/tambah_detail/$1';

// $route['edit-detail-invoice-gs/(:any)/(:any)']   = 'Gs/edit_detail/$1/$2';

// $route['hapus-detail-invoice-gs/(:any)/(:any)']   = 'Gs/hapus_detail/$1/$2';

// $route['include-ppn-gs/(:any)']     = 'Gs/include_ppn/$1';



// $route['cetak-detail-invoice-gs/(:any)']   = 'Cetak/cetak_invoice_gs/$1';

// $route['cetak-detail-surat-jalan-gs/(:any)'] = 'Cetak/cetak_surat_jalan_gs/$1';

// $route['cetak-detail-invoice-gs/(:any)/(:any)']   = 'Cetak/cetak_invoice_gs/$1/$2';

// $route['cetak-detail-surat-jalan-gs/(:any)/(:any)'] = 'Cetak/cetak_surat_jalan_gs/$1/$2';

// $route['ekspor-detail-invoice-gs/(:any)']   = 'Cetak/export_excel_invoice_gs/$1';



// $route['bc-gs']         = 'Gs/bc';

// $route['tambah-bc-gs']       = 'Gs/tambah_bc';

// $route['edit-bc-gs/(:any)']   = 'Gs/edit_bc/$1';

// $route['hapus-bc-gs/(:any)']     = 'Gs/hapus_bc/$1';



// $route['detail-bc-gs/(:any)']       = 'Gs/detail_bc/$1';

// $route['tambah-detail-bc-gs/(:any)']   = 'Gs/tambah_detail_bc/$1';

// $route['edit-detail-bc-gs/(:any)/(:any)']   = 'Gs/edit_detail_bc/$1/$2';

// $route['hapus-detail-bc-gs/(:any)/(:any)']   = 'Gs/hapus_detail_bc/$1/$2';

// $route['ekspor-detail-bc-gs/(:any)']   = 'Cetak/export_excel_bc_gs/$1';

// $route['ekspor-detail-bc-gs-new/(:any)']   = 'Cetak/export_excel_bc_gs_new/$1';



// $route['laporan-fabrikasi'] = 'Laporan/fabrikasi';

// $route['laporan-cs'] = 'Laporan/cs';

// $route['laporan-gs'] = 'Laporan/gs';

// $route['laporan-all'] = 'Laporan/all';



// $route['laporan-omset-fabrikasi-by-user'] = 'Laporan/omset_fabrikasi_by_user';

// $route['laporan-omset-fabrikasi-by-departemen'] = 'Laporan/omset_fabrikasi_by_departemen';



// $route['laporan-omset-gs-by-user'] = 'Laporan/omset_gs_by_user';

// $route['laporan-omset-gs-by-departemen'] = 'Laporan/omset_gs_by_departemen';



// $route['pbo']         = 'Pbo';

// $route['tambah-pbo']     = 'Pbo/tambah';

// $route['edit-pbo/(:any)']   = 'Pbo/edit/$1';

// $route['generate-po/(:any)']   = 'Pbo/generate_po/$1';

// $route['hapus-pbo/(:any)']   = 'Pbo/hapus/$1';



// //Barang

// $route['barang-masuk']         = 'Barang/barang_masuk';

// $route['tambah-barang-masuk']     = 'Barang/tambah_barang_masuk';

// $route['edit-barang-masuk/(:any)']   = 'Barang/edit_barang_masuk/$1';

// $route['hapus-barang-masuk/(:any)']   = 'Barang/hapus_barang_masuk/$1';



// $route['stok-barang']         = 'Barang/stok_barang';

// $route['detail-stok-barang/(:any)'] = 'Barang/detail_stok_barang/$1';

// $route['ekspor-stok-barang'] = 'Cetak/export_excel_stok_barang';

// $route['laporan-harga-jual-beli'] = 'Laporan/harga_jual_beli';

// $route['laporan-harga-jual-beli-gs'] = 'Laporan/harga_jual_beli_gs';



// $route['stok-opname']         = 'Barang/stok_opname';

// $route['tambah-stok-opname']     = 'Barang/tambah_stok_opname';

// $route['edit-stok-opname/(:any)']   = 'Barang/edit_stok_opname/$1';

// $route['hapus-stok-opname/(:any)']   = 'Barang/hapus_stok_opname/$1';



// $route['tracking-barang'] = 'Barang/tracking_barang';



// // kepegawaian

// $route['kepegawaian-dashboard'] = 'Kepegawaian';

// $route['kepegawaian-data-kasbon'] = 'Kepegawaian/data_kasbon';

// $route['kepegawaian-pinjaman-kasbon'] = 'Kepegawaian/laporan_pinjaman_kasbon';

// $route['kepegawaian-potongan-kasbon'] = 'Kepegawaian/laporan_potongan_kasbon';



$route['tracking-barang'] = 'Tracking';

$route['data-order']            = 'Order';
$route['tambah-order']          = 'Order/tambah';
$route['edit-order/(:any)']     = 'Order/edit/$i';

$route['riwayat-tagihan']               = 'RiwayatTagihan';
$route['riwayat-tagihan-surat-jalan']   = 'RiwayatTagihan/suratJalan';
$route['riwayat-tagihan-invoice']       = 'RiwayatTagihan/invoice';

$route['status-tagihan']               = 'StatusTagihan';
$route['status-tagihan-surat-jalan']   = 'StatusTagihan/suratJalan';
$route['status-tagihan-invoice']       = 'StatusTagihan/invoice';
