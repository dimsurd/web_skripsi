<?php $this->load->view("templates/template_header"); ?>
<!-- Main content -->
<section class="content pt-3">
    <div class="container-fluid">
        <h1>Edit Page</h1>
        <div class="row pb-2">
            <div class="col-8">
                <form id="frmEdit" data-parsley-validate="">
                    <input type="hidden" name="id_hidden" value="<?= $data_users->id ?>">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Insert Users Name" value="<?= $data_users->name ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Insert Username" value="<?= $data_users->username ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Roles</label>
                        <div class="input-group mb-3">
                            <select class="custom-select form-control select-2" id="role" name="role" data-parsley-errors-container="#validation-role" required>
                                <option selected disabled>Choose...</option>
                                <option value="admin">Admin</option>
                                <option value="kasir">Kasir</option>
                                <option value="bengkel">bengkel</option>
                                <option value="front_office">Front Office</option>
                            </select>
                        </div>
                        <div id="validation-role"></div>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Insert Password">
                    </div>
                    <div class="form-group">
                        <label>Re Enter Password</label>
                        <input type="password" class="form-control" id="password_validate" name="password_validate" placeholder="Insert Password" data-parsley-equalto="#password" data-parsley-equalto-message="Password Doesn't Match" disabled>
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

        $('#role').val('<?= $data_users->role ?>').trigger('change')

        // Check if user type password
        $('#password').on('change', (e) => {
            let charLength = $('#password').val().length

            if (charLength > 0) {
                $("#password_validate").prop("disabled", false);
                $("#password_validate").prop("required", true);
            } else {
                $("#password_validate").prop("disabled", true);
                $("#password_validate").removeAttr("required");
            }
        })
        // End Check if user type password

        $('#frmEdit').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: '<?= base_url("users/submit_form_edit"); ?>',
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
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>