<?php $this->load->view("templates/template_header"); ?>
<!-- Main content -->
<section class="content pt-3">
    <div class="container-fluid">
        <h1>Add Page</h1>
        <div class="row pb-2">
            <div class="col-8">
                <form id="frmAdd" data-parsley-validate="">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Insert Users Name" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Insert Username" required>
                    </div>
                    <div class="form-group">
                        <label>Roles</label>
                        <div class="input-group mb-3">
                            <select class="custom-select form-control select-2" id="role" name="role" data-parsley-errors-container="#validation-role" required>
                                <option selected disabled>Choose...</option>
                                <option value="admin">Admin</option>
                                <option value="bengkel">Bengkel</option>
                                <option value="front_office">Front Office</option>
                            </select>
                        </div>
                        <div id="validation-role"></div>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Insert Password" required>
                    </div>
                    <div class="form-group">
                        <label>Re Enter Password</label>
                        <input type="password" class="form-control" id="password_validate" name="password_validate" placeholder="Insert Password" data-parsley-equalto="#password" data-parsley-equalto-message="Password Doesn't Match" required>
                    </div>
                    <div class="row float-right">
                        <a href="<?= base_url("users") ?>" type="button" class="btn btn-danger mr-2">Back</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?php $this->load->view("templates/template_footer"); ?>

<script>
    $(document).ready(function() {
        $('#frmAdd').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: '<?= base_url("users/submit_form"); ?>',
                type: 'POST',
                data: formData,
                dataType: "JSON",
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success.',
                            text: 'Data Saved!',
                        }).then(function() {
                            window.location.href = '<?= base_url("users"); ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed.',
                            text: response.message,
                        })
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>