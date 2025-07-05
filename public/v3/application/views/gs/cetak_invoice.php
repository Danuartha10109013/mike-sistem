<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?= 'General Supply'?></title>



    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style type="text/css">

        #footer {

          position: fixed;

          left: 0;

            right: 0;

            color: #aaa;

            font-size: 0.9em;

        }



        #footer {

          bottom: 0;

          border-top: 0.1pt solid #aaa;

        }



        .page-number {

          text-align: center;

        }



        .page-number:before {

          content: counter(page);

        }

/*        .page-number:before {

          content: "Page " counter(page);

        }*/

    </style>

</head>

<body>

    <div id="footer">

        <div class="page-number"></div>

    </div>

    <div class="container mt-2">

        <div class="mt-2">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-body">

                            <img src="<?= base_url(); ?>assets/img/header_invoice-min.png" width="100%">

                            <table class="" border="0" width="100%">

                                <tbody>

                                    <tr>

                                        <td class="text-center" width="70%"></td>

                                        <td class=""><?= $f['no_invoice'] ?><br/><?= $tanggal ?><br/>Kepada :<br/>PT. TKG TAEKWANG INDONESIA<br/>Dusun Belendung II RT. 17 RW.06<br/>Desa Belendung<br/>Kec. Cibogo, Kab. Subang</td>

                                    </tr>

                                </tbody>

                            </table>

                            <br/>

                            <table class="" border="1" width="100%">

                                <thead>

                                    <tr>

                                        <th class="text-center" style="width: 20px;">NO</th>

                                        <th class="text-center">NAMA BARANG</th>

                                        <th class="text-center" style="width: 80px;">ITEM CODE</th>

                                        <th class="text-center" style="width: 30px;">QTY</th>

                                        <th class="text-center" style="width: 30px;">UOM</th>

                                        <th class="text-center" style="width: 80px;">HARGA</th>

                                        <th class="text-center" style="width: 80px;">TOTAL</th>

                                        <th class="text-center" style="width: 60px;">PO</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php if($f_detail): ?>

                                        <?php 

                                        $no = 1; 

                                        $total=0;

                                        $total_ppn=0;

                                        $total_qty=0;

                                        $ppn = 0;

                                        $jml_ppn = 0;

                                        $nama_ppn = '';

                                        foreach($f_detail as $i):

                                            $total_ppn += round($i['total_harga_barang']);

                                            $terbilang = terbilang($total_ppn);  ?>
    
                                            <?php if($i['nama_barang'] == 'PPn 10%' || $i['nama_barang'] == 'PPn 11%'): 
                                                $ppn = 1;
                                                $nama_ppn = $i['nama_barang'];
                                                $jml_ppn = round($i['total_harga_barang']);
    
                                            ?>
                                            <?php else: 
                                                $total += $i['total_harga_barang'];
                                                $total_qty += $i['jumlah_barang'];
                                            ?>

                                            <tr>

                                                <td class="text-center" style="font-size: 8pt;"><?= $no++;?></td>

                                                <td class="" style="font-size: 8pt;"><?= strtoupper($i['nama_barang']);?></td>

                                                <td class="text-center" style="font-size: 8pt;"><?= strtoupper($i['kode_barang']);?></td>

                                                <td class="text-center" style="font-size: 8pt;"><?= $i['jumlah_barang'];?></td>

                                                <td class="text-center" style="font-size: 8pt;"><?= strtoupper($i['satuan_barang']);?></td>

                                                <td class="text-center" style="font-size: 8pt;" ><?= number_format($i['harga_barang'], 2,',','.');?></td>

                                                <td class="text-center" style="font-size: 8pt;" ><?= number_format($i['total_harga_barang'], 2,',','.');?></td>

                                                <td class="text-center" style="font-size: 8pt;"><?= strtoupper($i['no_po']);?></td>

                                            </tr>
                                            <?php endif; ?>
                                            <?php endforeach;?>
                                            
                                            <?php if($nama_ppn != ''): ?>

                                                <tr>
    
                                                      <td class="" style="font-size: 7pt; text-align: right;" colspan="6"><b>SUB TOTAL</b></td>
    
                                                      <td class="text-center" style="font-size: 7pt; text-align: right;"><?= number_format($total, 2, ',', '.');?></td>
    
                                                      <td colspan=""></td>
    
                                                </tr>
                                                <tr>
    
                                                      <td class="" style="font-size: 7pt; text-align: right;" colspan="6"><b><?= $nama_ppn ?></b></td>
    
                                                      <td class="text-center" style="font-size: 7pt; text-align: right;"><?= number_format($jml_ppn, 2, ',', '.');?></td>
    
                                                      <td colspan=""></td>
    
                                                </tr>
                                            <?php endif; ?>
                                             <tr>

                                                  <td class="" style="font-size: 7pt; text-align: right;" colspan="6"><b>TOTAL</b></td>

                                                  <td class="text-center" style="font-size: 7pt; text-align: right;"><?= number_format($total_ppn, 2, ',', '.');?></td>

                                                  <td colspan=""></td>

                                            </tr>

                                            <tr>

                                              <td class="" style="font-size: 7pt;text-align: center;" colspan="8"><b>TERBILANG : <?= strtoupper($terbilang) ?></b></td>

                                            </tr>

                                    <?php else: ?>

                                        <tr>

                                            <td class="bg-light" colspan="7">Tidak ada data.</td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                            <table class="table" border="0">

                                <tbody>

                                    <tr>

                                        <td class="text-left" width="100%" style="font-size: 8pt;" >

                                            *) Pembayaran bisa ditransfer melalui rekening BNI Cabang subang 4405550660 A/n Sintesa Niaga Karya PT

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                            <table class="table" border="0">

                                <tbody>

                                    <tr>

                                        <td class="text-center" width="65%"></td>

                                        <td class="text-center">HORMAT KAMI<br/><br/><br/><br/><br/><br/><br/>EKA IMAM MAULANA</td>

                                    </tr>

                                </tbody>

                            </table>

                            <img src="<?= base_url(); ?>assets/img/footer_surat.png" width="100%" class="mt-auto mb-0">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php

    function penyebut($nilai) {

        $nilai = abs($nilai);

        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");

        $temp = "";

        if ($nilai < 12) {

            $temp = " ". $huruf[$nilai];

        } else if ($nilai <20) {

            $temp = penyebut($nilai - 10). " belas";

        } else if ($nilai < 100) {

            $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);

        } else if ($nilai < 200) {

            $temp = " seratus" . penyebut($nilai - 100);

        } else if ($nilai < 1000) {

            $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);

        } else if ($nilai < 2000) {

            $temp = " seribu" . penyebut($nilai - 1000);

        } else if ($nilai < 1000000) {

            $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);

        } else if ($nilai < 1000000000) {

            $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);

        } else if ($nilai < 1000000000000) {

            $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));

        } else if ($nilai < 1000000000000000) {

            $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));

        }     

        return $temp;

    }

 

    function terbilang($nilai) {

        if($nilai<0) {

            $hasil = "minus ". trim(penyebut($nilai))." RUPIAH";

        } else {

            $hasil = trim(penyebut($nilai))." RUPIAH";

        }           

        return $hasil;

    }



    ?>

</body>

</html>

