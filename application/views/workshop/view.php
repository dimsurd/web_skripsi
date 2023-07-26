<?php $this->load->view("templates/template_header"); ?>

<!-- Main content -->
<section class="content pt-3">
    <div class="container-fluid ">
        <div class="row">
            <div class="col-md text-right">
                <a href="<?= base_url("workshop/add_page") ?>" class="btn btn-primary">Add Data</a>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nopol</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_workshop as $key => $value) { ?>
                            <tr>
                                <td><?= $value->nopol ?></td>
                                <td><?= $value->customer_name ?></td>
                                <td><?= number_format($value->total, 2) ?></td>
                                <td>
                                    <?php if ($value->status > 0) { ?>
                                        <a href="<?= base_url("workshop/detail_page/") . $value->id ?>" class="btn btn-primary mr-2">Detail</a>
                                        <a class="btn btn-info" href="<?= base_url("home/generate_fpdf/") . $value->id ?>" target="_blank">Print Data</a>
                                    <?php } else { ?>
                                        <?php if ($_SESSION['role'] == "kasir" || $_SESSION['role'] == 'admin') { ?>
                                            <a href="<?= base_url("workshop/confirm_payment/") . $value->id ?>" class="btn btn-success mr-2">Confirm</a>
                                        <?php } ?>
                                        <a href="<?= base_url("workshop/edit_page/") . $value->id ?>" class="btn btn-info mr-2">Edit</a>
                                        <button class="btn btn-danger" onclick="delete_data(<?= $value->id ?>)">Delete</button>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?php $this->load->view("templates/template_footer"); ?>

<script>
    function delete_data(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("workshop/delete_data"); ?>',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        })
    }
</script>