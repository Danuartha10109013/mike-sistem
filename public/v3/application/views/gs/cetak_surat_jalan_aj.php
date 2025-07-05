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
                            <!--<table class="table table-striped table-bordered">-->
                            <table class="" border="1" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 20px;">No</th>
                                        <th class="text-center">NAMA BARANG</th>
                                        <th class="text-center" style="width: 80px;">ITEM CODE</th>
                                        <th class="text-center" style="width: 30px;">QTY</th>
                                        <th class="text-center" style="width: 40px;">UOM</th>
                                        <th class="text-center" style="width: 60px;">PO</th>
                                        <th class="text-center" style="width: 80px;">DEPARTMENT</th>
                                        <th class="text-center" style="width: 80px;">USER</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($f_detail): ?>
                                        <?php 
                                        $no = 1; 
                                        $total=0;
                                        foreach($f_detail as $i): ?>
                                            <tr>
                                                <td class="text-center" style="font-size: 8pt;"><?= $no++;?></td>
                                                <td class="" style="font-size: 8pt;"><?= $i['nama_barang'];?></td>
                                                <td class="text-center" style="font-size: 8pt;"><?= $i['kode_barang'];?></td>
                                                <td class="text-center" style="font-size: 8pt;"><?= $i['jumlah_barang'];?></td>
                                                <td class="text-center" style="font-size: 8pt;"><?= $i['satuan_barang'];?></td>
                                                <td class="text-center" style="font-size: 8pt;"><?= $i['no_po'];?></td>
                                                <td class="text-center" style="font-size: 8pt;"><?= $i['nama_departemen'] == null ? $i['departemen'] : $i['nama_departemen'];?></td>
                                                <td class="text-center" style="font-size: 8pt;"></td>
                                            </tr>
                                            <?php endforeach;?>
                                    <?php else: ?>
                                        <tr>
                                            <td class="bg-light" colspan="7">Tidak ada data.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <br/>
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="text-center" width="60%">HORMAT KAMI<br/><br/><br/><br/><br/><br/><br/>Andhika Rheza Pratama Khan</td>
                                        <td class="text-center">PENERIMA</td>
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
</body>
</html>
