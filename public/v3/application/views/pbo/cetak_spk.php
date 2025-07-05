<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title?></title>

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
                            <img src="<?= base_url(); ?>assets/img/header_spk.png" width="100%">
                            <table class="" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td class="text-center" width="70%"></td>
                                        <td class="text-right" style="font-size: 12pt;"><?= $no_job ?><br/><?= $tanggal ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br/>
                            <!--<table class="table table-striped table-bordered">-->
                            <table class="" border="1" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 20px;vertical-align: center;font-size: 10pt;">NO</th>
                                        <th class="text-center" style="vertical-align: center;font-size: 10pt;">NAMA BARANG</th>
                                        <th class="text-center" style="width: 50px;vertical-align: center;font-size: 10pt;">UOM</th>
                                        <th class="text-center" style="width: 50px;vertical-align: center;font-size: 10pt;">QTY</th>
                                        <th class="text-center" style="width: 120px;vertical-align: center;font-size: 10pt;">NO PO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center" style="font-size: 10pt;">1</td>
                                        <td class="" style="font-size: 10pt;"><?= $f['deskripsi'];?></td>
                                        <td class="text-center" style="font-size: 10pt;"><?= $f['uom'];?></td>
                                        <td class="text-center" style="font-size: 10pt;"><?= $f['qty'];?></td>
                                        <td class="text-center" style="font-size: 10pt;">-</td>
                                    </tr>
                                    <tr>
                                        <td class="" style="font-size: 10pt;" colspan="3"><b>TOTAL MATERIAL</b></td>
                                        <td class="text-center" style="font-size: 10pt;"><b><?= $f['qty'];?></b></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br/>
                            <table class="table" border="0">
                                <tbody>
                                    <tr>
                                        <td class="text-center" width="10%"></td>
                                        <td class="text-center">HORMAT KAMI<br/><img src="<?= base_url('assets/img/ttd.jpg')?>" height="120px"><br/>EKA IMAM MAULANA</td>
                                        <td class="text-center" width="60%"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
