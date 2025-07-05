<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title?></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style type="text/css">
        /*#footer {
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
        }*/
/*        .page-number:before {
          content: "Page " counter(page);
        }*/
        .break {
            page-break-after: always;
        }
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
                            <table class="" border="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="font-size: 11pt;">HARGA JUAL-BELI PERIODE <?= $dari_tanggal ?> - <?= $sampai_tanggal ?></th>
                                    </tr>
                                </thead>
                            </table>
                            <br/>
                            <table class="" border="1" width="100%">
                                <thead>
                                    <tr style="background-color: orange;">
                                        <th class="text-center" style="font-size: 9pt;">#</th>
                                        <th style="font-size: 9pt;">Nama Barang</th>
                                        <th class="text-center" style="font-size: 9pt;">QTY</th>
                                        <th class="text-center" style="font-size: 9pt;">Harga Beli (PCS)</th>
                                        <th class="text-center" style="font-size: 9pt;">Harga Jual (PCS)</th>
                                        <th class="text-center" style="font-size: 9pt;">Laba Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1; 
                                    $total = 0;
                                    foreach($detail_jb as $u): 
                                      $margin_total = $u['harga_barang']*$u['jumlah_barang']-$u['harga_beli']*$u['jumlah_barang'];
                                      $total += $margin_total;
                                      ?>
                                    <tr>
                                        <td class="text-center" style="width: 30px;font-size: 9pt;"><?= $no++;?></td>
                                        <td style="font-size: 9pt;"><?= $u['nama_barang'];?></td>
                                        <td class="text-center" style="width: 60px;font-size: 9pt;"><?= $u['jumlah_barang'];?></td>
                                        <td class="text-right" style="width: 15%;font-size: 9pt;"><?= number_format($u['harga_beli'], 0, '.', '.');?></td>
                                        <td class="text-right" style="width: 15%;font-size: 9pt;"><?= number_format($u['harga_barang'], 0, '.', '.');?></td>
                                        <td class="text-right" style="width: 13%;font-size: 9pt;">
                                            <?php if($margin_total < 0): ?>
                                                <b>(<?= number_format($margin_total, 0, '.', '.');?>)</b>
                                            <?php else: ?>
                                                <?= number_format($margin_total, 0, '.', '.');?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                    <tr>
                                        <th class="text-center" colspan="5">TOTAL</th>
                                        
                                        <th class="text-right" style="width: 180px;"><?= number_format($total, 0, '.', '.');?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="break"></div>
                        <?php
                        foreach($jual_beli as $i):
                        ?>
                        <div class="card-body">
                            <table class="" border="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-left" style="font-size: 10pt; width: 10%">PO</th>
                                        <th class="text-left" style="font-size: 10pt;">: <?= $i['no_po'] ?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-left" style="font-size: 10pt; width: 10%">Tanggal</th>
                                        <th class="text-left" style="font-size: 10pt;">: <?= date('d F Y', strtotime($i['tanggal'])) ?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-left" style="font-size: 10pt; width: 10%">Department</th>
                                        <th class="text-left" style="font-size: 10pt;">: <?= $i['nama_user'] ?></th>
                                    </tr>
                                </thead>
                            </table>
                            <br/>
                            <table class="" border="1" width="100%">
                                <thead>
                                    <tr style="background-color: orange;">
                                        <th class="text-center" style="font-size: 9pt;">#</th>
                                        <th style="font-size: 9pt;">Nama Barang</th>
                                        <th class="text-center" style="font-size: 9pt;">QTY</th>
                                        <th class="text-center" style="font-size: 9pt;">Harga Beli (PCS)</th>
                                        <th class="text-center" style="font-size: 9pt;">Harga Jual (PCS)</th>
                                        <th class="text-center" style="font-size: 9pt;">Laba Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1; 
                                    $this->db->select('*');
                                    $this->db->from('detail_cs');
                                    $this->db->where('detail_cs.no_po', $i['no_po']);
                                    $this->db->order_by('detail_cs.nama_barang', 'ASC');
                                    $detail_jual_beli = $this->db->get()->result_array();
                                    $total = 0;
                                    foreach($detail_jual_beli as $u): 
                                      $margin_total = $u['harga_barang']*$u['jumlah_barang']-$u['harga_beli']*$u['jumlah_barang'];
                                      $total += $margin_total;
                                      ?>
                                    <tr>
                                        <td class="text-center" style="width: 30px;font-size: 9pt;"><?= $no++;?></td>
                                        <td style="font-size: 9pt;"><?= $u['nama_barang'];?></td>
                                        <td class="text-center" style="width: 60px;font-size: 9pt;"><?= $u['jumlah_barang'];?></td>
                                        <td class="text-right" style="width: 15%;font-size: 9pt;"><?= number_format($u['harga_beli'], 0, '.', '.');?></td>
                                        <td class="text-right" style="width: 15%;font-size: 9pt;"><?= number_format($u['harga_barang'], 0, '.', '.');?></td>
                                        <td class="text-right" style="width: 13%;font-size: 9pt;">
                                            <?php if($margin_total < 0): ?>
                                                <b>(<?= number_format($margin_total, 0, '.', '.');?>)</b>
                                            <?php else: ?>
                                                <?= number_format($margin_total, 0, '.', '.');?>
                                            <?php endif; ?>    
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                    <tr>
                                        <th class="text-center" colspan="5">TOTAL</th>
                                        
                                        <th class="text-right" style="width: 180px;"><?= number_format($total, 0, '.', '.');?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="break"></div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.print();
    </script>
</body>
</html>
