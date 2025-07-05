<?php $this->load->view('template/header2'); ?>

<?php $this->load->view('template/sidebar2'); ?>
<style>
    .card {
        border-top: 2px solid #dd5093;
    }
</style>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-body pt-2 mt-2 pt-lg-0 mt-lg-0 pt-xl-0 mt-xl-0">

            <div class="card">
                <div class="card-body p-5">
                    <div class="row">
                        <!-- Balance -->

                        <div class="table-responsive">

                            <table class="table table-striped table-hovered table-bordered" id="datatables-log">

                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Description</th>
                                        <th>IP Address</th>
                                        <th>User Agent</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs as $k => $log): ?>
                                        <tr>
                                            <td><?= $k + 1 ?></td>
                                            <td><?= $log->username ? $log->username : $log->user_id  ?></td>
                                            <td><?= $log->action ?></td>
                                            <td><?= $log->description ?></td>
                                            <td><?= $log->ip_address ?></td>
                                            <td><?= $log->user_agent ?></td>
                                            <td><?= $log->created_at ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<?php $this->load->view('template/footer2'); ?>