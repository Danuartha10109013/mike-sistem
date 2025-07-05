<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= $title ?></title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/components.css">

  <style>
    .bg-login {
      background-image: url('<?= base_url(); ?>assets/img/bg-login2.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }
  </style>
</head>

<body class="bg-login">
  <div id="app">
    <section class="section">
      <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100">
          <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
            <div class="login-brand text-center">
              <img src="<?= base_url(); ?>assets/img/logo-large.png" alt="logo" width="100%" class="shadow-light rounded mb-2">
              <!-- <h2 style="color: #006400;">Merlin Store</h2>
          <h6 style="color: #DAA520;">Trusted Partner</h6> -->
            </div>
          </div>

          <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
            <div class="card card-primary w-100 col-lg-10">
              <div class="card-header text-center">
                <h4>Masukkan Username & Password Anda.</h4>
              </div>

              <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <form method="POST" action="<?= base_url('login'); ?>">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" class="form-control" name="username" required autofocus>
                  </div>

                  <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control" name="password" required>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                      Login
                    </button>
                  </div>
                </form>
                <div class="simple-footer mb-0 text-center">
                  Copyright <?= date('Y'); ?> Merlin Store
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

  <script src="<?= base_url(); ?>assets/js/stisla.js"></script>

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="<?= base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?= base_url(); ?>assets/js/custom.js"></script>

  <!-- Page Specific JS File -->
</body>

</html>