<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/sidebar'); ?>
<!-- Main Content -->
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1><?= $title ?></h1>
		</div>

		<div class="section-header">
			<h6>
				Berikut Peminjaman Kasbon, Potongan Kasbon, dan Sisa Pinjaman Kasbon.
			</h6>
		</div>
	</section>

	<section class="section">
		<div class="section-header">
			<form action="<?= base_url('kepegawaian-dashboard'); ?>" method="post">
				<div class="row">
					<div class="col-md-12 form-group">
						<label>Dari Tanggal</label>
						<input type="date" name="dari_tanggal" class="form-control" value="<?= set_value('dari_tanggal', $dari_tanggal); ?>" required="">
					</div>
					<div class="col-md-12 form-group">
						<label>Sampai Tanggal</label>
						<input type="date" name="sampai_tanggal" class="form-control" value="<?= set_value('sampai_tanggal', $sampai_tanggal); ?>" required="">
					</div>
					<div class="col-md-12 form-group">
						<button type="submit" name="filter" value="filter" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
					</div>
				</div>
			</form>
		</div>
	</section>

	<section class="section">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-12">
				<div class="card card-statistic-2">
					<div class="card-stats">
						<div class="card-stats-title">
							<h5>Total Pinjaman</h5>
						</div>
					</div>
					<div class="card-icon shadow-primary bg-primary">
						<i class="fas fa-dollar-sign"></i>
					</div>
					<div class="card-wrap">
						<div class="card-header">
							<h4>Balance</h4>
						</div>
						<div class="card-body">
							<h6><?= 'Rp ' . number_format($total_pinjaman_filter, 0, ',', '.'); ?></h6>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12">
				<div class="card card-statistic-2">
					<div class="card-stats">
						<div class="card-stats-title">
							<h5>Total Potongan</h5>
						</div>
					</div>
					<div class="card-icon shadow-primary bg-primary">
						<i class="fas fa-dollar-sign"></i>
					</div>
					<div class="card-wrap">
						<div class="card-header">
							<h4>Balance</h4>
						</div>
						<div class="card-body">
							<h6><?= 'Rp ' . number_format($total_potongan_filter, 0, ',', '.'); ?></h6>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12">
				<div class="card card-statistic-2">
					<div class="card-stats">
						<div class="card-stats-title">
							<h5>Total Sisa Pinjaman</h5>
						</div>
					</div>
					<div class="card-icon shadow-primary bg-primary">
						<i class="fas fa-dollar-sign"></i>
					</div>
					<div class="card-wrap">
						<div class="card-header">
							<h4>Balance</h4>
						</div>
						<div class="card-body">
							<h6><?= 'Rp ' . number_format($total_sisa_pinjaman_filter, 0, ',', '.'); ?></h6>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
</div>
<?php $this->load->view('template/footer'); ?>