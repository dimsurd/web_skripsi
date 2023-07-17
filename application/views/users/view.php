<?php $this->load->view("templates/template_header"); ?>

<!-- Main content -->
<section class="content pt-3">
    <div class="container-fluid ">
        <div class="row">
            <div class="col-md text-right">
                <a href="<?= base_url("users/add_page") ?>" class="btn btn-primary">Add Data</a>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_users as $key => $value) { ?>
                            <tr>
                                <td><?= $value->name ?></td>
                                <td><?= $value->username ?></td>
                                <td><?= $value->role ?></td>
                                <td>
                                    <a href="<?= base_url("users/edit_page/") . $value->id ?>" class="btn btn-info mr-2">Edit</a>
                                    <btn class="btn btn-danger" onclick="delete_data(<?= $value->id ?>)">Delete</btn>
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
                    url: '<?= base_url("users/delete_data"); ?>',
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