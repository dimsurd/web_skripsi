<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Works | Login</title>

    <?php $this->load->view("templates/template_script_header"); ?>

    <style>
        .container-fluid {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* Adjust this value as needed */
        }

        #show-password {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form id="frmLogin" data-parsley-validate="">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Insert Username" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Insert Password" required>
                                <span class="input-group-text" id="show-password"><i class="nav-icon fas fa-eye-slash" id="icon-show-password"></i></span>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php $this->load->view("templates/template_script_footer"); ?>

<script>
    $(document).ready(function() {

        $('#show-password').on('click', (e) => {
            e.preventDefault()
            const passwordField = $('#password');
            const passwordIcon = $('#icon-show-password');
            const passwordFieldType = passwordField.attr('type');

            if (passwordFieldType === 'password') {
                passwordField.attr('type', 'text');
                passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                passwordField.attr('type', 'password');
                passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        })

        $('#frmLogin').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '<?= base_url("auth/do_login"); ?>',
                type: 'POST',
                data: formData,
                dataType: "JSON",
                success: function(response) {
                    if (response.status) {
                        window.location.href = '<?= base_url(); ?>';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
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

</html>