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

                            <img src="<?= base_url(); ?>assets/img/header_quotation_aj.jpg" width="100%">

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

                                        <th class="text-center" rowspan="2" style="width: 20px;font-size: 7pt;">NO</th>

                                        <th class="text-center" rowspan="2" style="width: 80px;font-size: 7pt;">ITEM CODE</th>

                                        <th class="text-center" rowspan="2" style="font-size: 7pt;">NAMA BARANG</th>

                                        <th class="text-center" rowspan="2" style="width: 30px;font-size: 7pt;">QTY</th>

                                        <th class="text-center" rowspan="2" style="width: 30px;font-size: 7pt;">UNIT</th>

                                        <th class="text-center" style="width: 80px;font-size: 7pt;">PRICE (IDR)</th>

                                        <th class="text-center" rowspan="2" style="width: 80px;font-size: 7pt;">AMOUNT PRICE (IDR)</th>

                                        <th class="text-center" rowspan="2" style="width: 80px;font-size: 7pt;">PO</th>

                                    </tr>

                                    <tr>

                                        <th class="text-center" style="width: 80px;font-size: 7pt;">INSTALLATION</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php if($f_detail): ?>

                                        <?php 

                                        $no = 1; 

                                        $total=0;
                                        
                                        $total_qty=0;

                                        foreach($f_detail as $i):

                                          $total += $i['total_harga_barang'];

                                          $total_qty += $i['jumlah_barang'];

                                          $terbilang = terbilang($total);  ?>

                                            <tr>

                                                <td class="text-center" style="font-size: 8pt;"><?= $no++;?></td>

                                                <td class="text-center" style="font-size: 8pt;"><?= $i['kode_barang'];?></td>

                                                <td class="" style="font-size: 8pt;"><?= $i['nama_barang'];?></td>

                                                <td class="text-center" style="font-size: 8pt;"><?= $i['jumlah_barang'];?></td>

                                                <td class="text-center" style="font-size: 8pt;"><?= $i['satuan_barang'];?></td>

                                                <td class="text-center" style="font-size: 8pt;" ><?= number_format($i['harga_barang'], 2,',','.');?></td>

                                                <td class="text-center" style="font-size: 8pt;" ><?= number_format($i['total_harga_barang'], 2,',','.');?></td>

                                                <td class="text-center" style="font-size: 8pt;"><?= $i['no_po'];?></td>

                                            </tr>

                                            <?php endforeach;?>

                                            <tr>

                                              <td class="" style="font-size: 7pt; text-align: center;" colspan="3"><b>TOTAL</b></td>

                                              <td class="text-center" style="font-size: 7pt;"><b><?= $total_qty ?></b></td>

                                              <td colspan="2"></td>

                                              <td class="text-center" style="font-size: 7pt;"><?= number_format($total, 2, ',', '.');?></td>

                                              <td colspan=""></td>

                                            </tr>

                                            <tr>

                                              <td class="" style="font-size: 7pt;" colspan="8"><b>TERBILANG : <?= strtoupper($terbilang) ?></b></td>

                                            </tr>

                                    <?php else: ?>

                                        <tr>

                                            <td class="bg-light" colspan="7">Tidak ada data.</td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                            <br/>

                            <table class="table" border="0">

                                <tbody>

                                    <tr>

                                        <td class="text-center" width="65%"></td>

                                        <td class="text-center">HORMAT KAMI<br/><br/><br/><br/><br/><br/><br/>Andhika Rheza Pratama Khan</td>

                                    </tr>

                                </tbody>

                            </table>

                            <img src="<?= base_url(); ?>assets/img/footer_quotation_aj.jpg" width="100%" class="mt-auto mb-0" style="position: fixed;z-index: 1;bottom: 100px;">

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

