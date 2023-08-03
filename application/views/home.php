<?php $this->load->view("templates/template_header"); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <?php if ($_SESSION['role'] === "bengkel" || $_SESSION['role'] === "admin") { ?>
            <div class="row">
                <div class="col-md-12">
                    <h3>Scan Barcode To Update Your Process</h3>
                </div>
                <div class="col-md">
                    <form id="frmAdd">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" class="form-control" parsley-trigger="change" id="onscan_string" name="onscan_string" placeholder="Scan Barcode" required="">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-transparent border-bottom p-3">
                        <h5>Table Waiting For Approval</h5>
                    </div>
                    <div class="card-body">
                        <table id="dataTblWaitingForApproval" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Nopol</th>
                                    <th>Customer Name</th>
                                    <th>Total Estimated Cost</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_waiting_for_approval as $key => $value) { ?>
                                    <tr>
                                        <td><?= $value->nopol ?></td>
                                        <td><?= $value->customer_name ?></td>
                                        <td><?= number_format($value->total, 2) ?></td>
                                        <td>Waiting for Approval</td>
                                        <td>
                                            <a href="<?= base_url("workshop/edit_page/") . $value->id ?>" class="btn btn-info mr-2">Edit</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-transparent border-bottom p-3">
                        <h5>Table Antri Bongkar</h5>
                    </div>
                    <div class="card-body">
                        <table id="dataTblAntriBongkar" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Nopol</th>
                                    <th>Customer Name</th>
                                    <th>Total Estimated Cost</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_antri_bongkar as $key => $value) { ?>
                                    <tr>
                                        <td><?= $value->nopol ?></td>
                                        <td><?= $value->customer_name ?></td>
                                        <td><?= number_format($value->total, 2) ?></td>
                                        <td>Waiting for bongkar</td>
                                        <td>
                                            <a href="<?= base_url("workshop/detail_page/") . $value->id ?>" class="btn btn-info mr-2">Edit</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-transparent border-bottom p-3">
                        <h5>Table On Process</h5>
                    </div>
                    <div class="card-body">
                        <table id="dataTblProcess" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Nopol</th>
                                    <th>Customer Name</th>
                                    <th>Total Estimated Cost</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_on_process as $key => $value) { ?>
                                    <tr>
                                        <td><?= $value->nopol ?></td>
                                        <td><?= $value->customer_name ?></td>
                                        <td><?= number_format($value->total, 2) ?></td>
                                        <td><?php
                                            if ($value->status == 2) {
                                                echo 'Proses Bongkar';
                                            } else if ($value->status == 3) {
                                                echo 'Proses Dempul';
                                            } else if ($value->status == 4) {
                                                echo 'Proses Masking';
                                            } else if ($value->status == 5) {
                                                echo 'Proses Cat';
                                            } else if ($value->status == 6) {
                                                echo 'Proses Rakit';
                                            } else if ($value->status == 7) {
                                                echo 'Proses Poles';
                                            }
                                            ?></td>
                                        <td>
                                            <a href="<?= base_url("workshop/detail_page/") . $value->id ?>" class="btn btn-primary mr-2">Detail</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-transparent border-bottom p-3">
                        <h5>Table Finishing</h5>
                    </div>
                    <div class="card-body">
                        <table id="dataTblFinishing" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Nopol</th>
                                    <th>Customer Name</th>
                                    <th>Total Estimated Cost</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_finishing as $key => $value) { ?>
                                    <tr>
                                        <td><?= $value->nopol ?></td>
                                        <td><?= $value->customer_name ?></td>
                                        <td><?= number_format($value->total, 2) ?></td>
                                        <td>Finishing</td>
                                        <td>
                                            <a href="<?= base_url("workshop/detail_page/") . $value->id ?>" class="btn btn-primary mr-2">Detail</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-transparent border-bottom p-3">
                        <h5>Table Finished</h5>
                    </div>
                    <div class="card-body">
                        <table id="dataTblFinished" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Nopol</th>
                                    <th>Customer Name</th>
                                    <th>Total Estimated Cost</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_finished as $key => $value) { ?>
                                    <tr>
                                        <td><?= $value->nopol ?></td>
                                        <td><?= $value->customer_name ?></td>
                                        <td><?= number_format($value->total, 2) ?></td>
                                        <td>Finished</td>
                                        <td>
                                            <a href="<?= base_url("workshop/detail_page/") . $value->id ?>" class="btn btn-primary mr-2">Detail</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end row -->

    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?php $this->load->view("templates/template_footer"); ?>

<script>
    const tables = $('.table').DataTable();

    let mergeString = ''
    var url = "<?php echo base_url() ?>";

    setTimeout(() => {
        location.reload()
    }, 600000);

    $('#onscan_string').focus();

    $('#onscan_string').on('change', function() {
        const stringVal = $('#onscan_string').val()
        var matches = stringVal.match(/\d+/g);

        if (matches) {
            matches.forEach(function(num) {
                mergeString += num
            });
        }

        $("#frmAdd").submit();
    });




    $('#onscan_string').on('keydown', function() {
        setTimeout(() => {
            $('#onscan_string').attr('disabled', true);
        }, 200);
    });

    $("#frmAdd").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?php echo base_url('home/scan_data/'); ?>",
            dataType: "JSON",
            data: {
                id: mergeString,
            },
            type: 'POST',
            success: function(data) {
                if (data.status) {
                    Swal.fire({
                        icon: 'success',
                        title: data.msg
                    }).then(() => {
                        location.reload();
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: data.msg
                    }).then(() => {
                        location.reload();
                    })
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    textStatus
                })
            },
            beforeSend: function() {
                $('#animatedPreloader').show();
            },
            complete: function() {
                setTimeout(() => {
                    $('#onscan_string').attr('disabled', false);
                    $('#onscan_string').val('');
                    $('#onscan_string').focus();
                }, 300);
                $('#animatedPreloader').fadeOut();
            }
        });
    });
</script>