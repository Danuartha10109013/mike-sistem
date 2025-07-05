<html>

<head>

  <title>Membuat Export Excel Menggunakan Codeigniter dan PHPExcel</title>   

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 

</head>

<body>

 <div class="container box">

  <h3 align="center">Membuat Export Excel Menggunakan Codeigniter dan PHPExcel</h3>

  <div class="table-responsive">

   <table class="table table-bordered">

    <tr>

     <th>Nama</th>

     <th>Email</th>

    </tr>

    <?php foreach($invoice as $row){

     echo '

     <tr>

      <td>'.$row['no_invoice'].'</td>

      <td>'.$row['tanggal'].'</td>

     </tr>

     ';

    } ?>

   </table>

   <div align="center">

    <form method="post" action="<?= base_url('Excel/action')?>">

     <input type="submit" name="export" class="btn btn-success" value="Export" />

    </form>

   </div>

   <br />

   <br />

  </div>

 </div>

</body>

</html>