<html>
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?= $title?></title>



    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>
  .kopatas{
        display: flex !important;
        align-items: center !important; /* Agar gambar dan teks rata vertikal */
       justify-content: end !important;
    }
    .kopbawah{
        display: flex !important;
        align-items: center !important; /* Agar gambar dan teks rata vertikal */
       justify-content: end !important;
    }

        
    .logoM{
     display: flex;
     flex-wrap: wrap;
     align-content: center;   
    }

    .textkop{
        font-weight: 700;
        width: 450px;
        text-align: center;
    }

    .linekop{
    margin-top: 0px;
    margin-bottom: 0px;
    border: 0.5px solid;
    }
    
    .linekop2{
        margin-top: 1px;
        border: 1px solid;
    }

    h1{
        font-weight: 900;
        margin-top: 0px;
    }

    .w-25{
        width: 25%;
    }

    .w-40{
        width: 40%;
    }

    .w-75{
        width: 75% !important;
    }
    .w-60{
        width: 60% !important;
    }

    .w-60 .card-title{
        padding: 5px  !important;
        padding-right: 0px !important;
        padding-bottom: 10px !important;
        margin: 0px;
    }
    thead, tr >th {
        
        color: #fff !important;
    }
   
    .table{
        width: 100% !important;
    }
    * {
        -webkit-print-color-adjust: exact !important;
        /* Chrome, Safari 6 – 15.3, Edge */
        color-adjust: exact !important;
        /* Firefox 48 – 96 */
        print-color-adjust: exact !important;
        /* Firefox 97+, Safari 15.4+ */
      }
 
        @media print {
            thead.header-surat {
                display:table-header-group;
            }
            @page {
                size:21.0cm 29.7cm; 
                margin: 1cm 1cm 2cm 2cm; 
            }
        }
                /*
        UNTUK GOOGLE CHROME
        */
        @media print and (-webkit-min-device-pixel-ratio:0) {
        #blank {
            height: 85px;
            position: relative;
        }
            
        #print-footer { 
            display: table-footer-group;
            bottom: 1px;
            left: 2px;
            position: fixed;        
        }
        }    


        @media print and (min--moz-device-pixel-ratio:0) {
            
        #blank {
            height: 85px;
            position: relative;
        }
            
        #print-footer { 
            display: table-footer-group;
            bottom: 1px;
            left: 2px;
            position: fixed;        
        }
        
        }
    </style>
</head>
<body>
  <table class="container-surat">

    <thead class="header-surat">
      <tr>
        <th class="header-surat-cell">
        <div class="kopatas">
            <div class="logoM">
                <img src="<?=  base_url() . '/assets/img/logotext.jpg'?>" width="200" alt="" >
            </div>
        </div>

        </th>
      </tr>
    </thead>

    <tbody class="body-surat">
      <tr>
        <td class="body-surat-cell">
         
            <div class="w-60"  style="background-color: darksalmon !important;">

                <h1 class="card-title text-start fw-bold ">Invoice</h1>
            </div>

            <p class="w-40" style="margin: 30px 0px 10px 0px;">Jln raya pantura, Dsn Kubangjaran RT.02/RW.01, Ds. Karanganyar, Kec. Pusakajaya, Kab. Subang-JABAR</p>

            <div class="col-md-2"><h5 class="card-title mb-4 text-left">Nomor Invoice: <?= $f['no_invoice']?> </h5> </div>
            <div class="col-md-2"><h5 class="card-title mb-4 text-left">Tanggal Invoice: <?= $tanggal?> </h5> </div>
            <table class="table table-striped table-bordered">

                <thead>
                    <tr>
                        <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">No</th>
                        <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">Deskripsi</th>
                        <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">Agen</th> 
                        <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">Nomor Order</th>
                        <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">QTY/UOM</th>
                        <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">Harga</th>
                        <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">Total</th>
                    </tr>
                </thead>
            <tbody>

                <tbody>
                    <?php
                    $no = 1; 
                    $total = 0; 
                    $diskon = 0; 
                    foreach($f_detail as $i):?>
                    <?php if ($i['tipe_item'] === 'Diskon') {
                        ?>
                      <tr>
                        <td class="text-center"><?= $no++;?></td>
                        <td colspan="5" class="text-center"><?= $i['nama_barang']; ;?></td>
                        <td><?= str_replace('-', '', number_format($i['total_harga_barang'], 0,',','.'));?></td>
                        
                       
                      </tr>
                    <?php  $diskon =  str_replace('-', '',$i['total_harga_barang']);
                    
                    } else { ?>
                      <tr>
                        <td class="text-center"><?= $no++;?></td>
                        <td><?= $i['nama_barang'].'<br/>'.$i['kode_barang'];?></td>
                        <td><?= $i['no_po'];?></td>
                        <td><?= $i['nama_departemen'] == null ? $i['departemen'] : $i['nama_departemen'];?></td>
                        <td><?= $i['jumlah_barang'].'/'. $i['satuan_barang'];?></td>
                        <td><?= number_format($i['harga_barang'], 0,',','.');?></td>
                        <td><?= number_format($i['total_harga_barang'], 0,',','.');?></td>
                      
                      </tr>
                    <?php $total += $i['total_harga_barang'] ; } ?>
                    <?php endforeach;
                    
                    ?>
                    <tr>
                        <td class="text-center"></td>
                        <td colspan="5" class="text-center">Total</td>
                        <td class="text-start"><?= number_format( $total-$diskon, 0,',','.') ?></td>
                    </tr>
                  </tbody>

                </tbody>

            </table>

            <h4>SYARAT DAN KETENTUAN PEMBAYARAN</h4>
            <p class="" style="margin: 30px 0px 10px 0px;">
                1. Silakan kirim pembayaran dalam waktu 30 hari setelah menerima faktur ini.<br>
                2. Tidak dapat melakukan pembatalan setelah pembayaran dilakukan.
                <br>
                <br>
                Saya telah setuju dengan syarat dan ketentuan yang berlaku.<br>
                Terimakasih telah berbelanja dan bekerjasama dengan kami.

            </p>
            <br>

            <div class="kopbawah" style="width: 100vw !important;">

                    <div class="w-25"  >
                        
                        <h5 class="card-title text-start fw-bold  w-425 style="width: 100% !important;">Mengetahui,</h5>
            <br>
            <br>    

                        <h5 class="card-title text-start fw-bold  w-40" style="width: 100% !important;">Merlina Wijaya</h5>
                    </div>
                </div>
        </td>
      </tr>
      
    </tbody>

    <tfoot class="footer-surat">
      <tr>
        <td class="footer-surat-cell">
          <div id="blank"></div>
            <span id="print-footer">
                <div class="kopbawah" style="width: 100vw !important;">

                    <div class="w-60"  style="background-color: darksalmon !important; ">
                        
                        <h1 class="card-title text-start fw-bold  w-60" style="width: 100% !important;">&nbsp</h1>
                    </div>
                </div>
            </span>
        </td>
      </tr>
    </tfoot>
  </table>
  
  <script>

window.print();

</script>
</body>
</html>