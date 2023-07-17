<?php $this->load->view("templates/template_header"); ?>
<!-- Main content -->
<section class="content pt-3">
    <div class="container-fluid">
        <h1>Edit Page</h1>
        <div class="row pb-2">
            <div class="col-12">
                <form id="frmEdit" data-parsley-validate="">
                    <input type="hidden" name="idHidden" value="<?= $data_workshop->id ?>">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <h4>Car Information</h4>
                                    </div>
                                    <div class="col-md mb-3">
                                        <div class="form-group">
                                            <label>Nopol<span style="color:red">*</span></label>
                                            <input type="text" class="form-control" parsley-trigger="change" id="nopol" name="nopol" placeholder="Insert Car's Plate Number" disabled required="" value="<?= $data_workshop->nopol ?>">
                                        </div>
                                    </div>
                                    <div class="col-md mb-3">
                                        <div class="form-group">
                                            <label>Customer Name<span style="color:red">*</span></label>
                                            <input type="text" class="form-control" parsley-trigger="change" id="customer_name" name="customer_name" placeholder="Insert Customer Name" disabled required="" value="<?= $data_workshop->customer_name ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md">
                                        <h4>Charge Repair</h4>
                                        <div id="charge_repair_input_fields"></div>
                                    </div>
                                </div>
                                <table id="dataTblCost" class="table table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Cost</th>
                                            <th>Is Scanned</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $z = 0;
                                        foreach ($data_workshop_charge_repair as $key => $value) { ?>
                                            <tr id="row_data_charge_repair_<?= $value->id ?>">
                                                <td><?= $value->id ?></td>
                                                <td><?= $value->charge_name ?></td>
                                                <td><?= number_format($value->charge_cost, 2) ?></td>
                                                <td><?= $value->is_scanned == 1 ? '<span class="badge text-bg-success">Scanned</span>' : '-' ?></td>
                                                <td>
                                                </td>
                                            </tr>
                                        <?php $z++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8 mb-3 text-end">
                                        <h3>Total Charge : </h3>
                                    </div>
                                    <div class="col-md mb-3">
                                        <input type="hidden" name="total_charge" id="total_charge">
                                        <h3 id="total_charge_text">Rp. <?= number_format($data_workshop->total, 2) ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row float-right">
                        <a href="<?= base_url("workshop") ?>" type="button" class="btn btn-danger mr-2">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?php $this->load->view("templates/template_footer"); ?>
<?php $this->load->view('workshop/modals/charge_repair_modal') ?>

<script>
    let preventFormSubmit = false;
    let rowIndex = "<?= $z; ?>"


    const tblCost = $('#dataTblCost').DataTable({
        "order": [
            [0, 'asc']
        ]
    });

    // Hide id in datatable
    tblCost.column(0).visible(false);
    // End Hide id in datatable


    let total_cost = <?= $data_workshop->total ?>;

    $('#modal_charge_repair').on('hidden.bs.modal', function(e) {
        $('#frmSaveCharge').trigger('reset')
    })
    $('#modal_charge_repair').on('show.bs.modal', function(e) {
        $('#workshop_id').val("<?= $data_workshop->id ?>")
    })
    $('#modal_charge_repair_edit').on('hidden.bs.modal', function(e) {
        $('#frmSaveChargeEdit').trigger('reset')
    })

    $('.btn_close_modal').on('click', () => {
        $('.modal').modal('hide')
    })

    function openModalAdd() {
        $("#table_index").val(rowIndex)
        $('#modal_charge_repair').modal('show')
    }

    function openModalEditCharge(idData, index) {
        preventFormSubmit = true;
        $('#modal_charge_repair_edit').modal('show');

        $.ajax({
            url: "<?= base_url("workshop/get_data_charge") ?>",
            data: {
                id: idData
            },
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (!data.status) {
                    Swal.fire({
                        icon: 'error',
                        title: data.message
                    });
                } else {
                    $('#table_index_edit').val(index);
                    $('#charge_id').val(data.data_charge.id);
                    $('#charge_name_edit').val(data.data_charge.charge_name);
                    $('#charge_cost_edit').val(addCommas(data.data_charge.charge_cost));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire(errorThrown);
            },
            beforeSend: function() {
                $("#animatedPreloader").show();
            },
            complete: function() {
                $("#animatedPreloader").fadeOut();
            },
        });
    }

    function deleteCharge(idData, charge_cost) {
        preventFormSubmit = true;
        $.ajax({
            url: "<?= base_url("workshop/delete_charge") ?>",
            data: {
                id: idData
            },
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    Swal.fire({
                        icon: 'success',
                        title: data.message
                    }).then(function() {
                        // Get a reference to the row that we want to delete
                        var rowToDelete = tblCost.row('#row_data_charge_repair_' + idData);
                        const costDeleted = charge_cost;

                        // Count Total
                        // const float_charge_cost = parseFloat(costDeleted.replace(/,/g, ''));

                        total_cost = (total_cost - costDeleted)
                        $('#total_charge_text').html("Rp. " + addCommas(total_cost))
                        $('#total_charge').val(addCommas(total_cost))
                        // End Count Total

                        // Remove the row from the table
                        $('.charge_repair_value_' + idData).remove();
                        rowToDelete.remove().draw();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: data.message
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire(errorThrown);
            },
            beforeSend: function() {
                $("#animatedPreloader").show();
            },
            complete: function() {
                $("#animatedPreloader").fadeOut();
            },
        });
    }


    $("#frmEdit").on("submit", function(e) {
        if (preventFormSubmit) {
            e.preventDefault(); // Prevent form submission
            return false;
        }
        e.preventDefault();
        if ($(this).parsley().isValid()) {
            var form = $(this)[0];
            var formData = new FormData(form);
            $.ajax({
                url: "<?= base_url("workshop/submit_form_edit") ?>",
                data: formData,
                dataType: "JSON",
                type: "POST",
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: data.message
                        }).then(function() {
                            window.location.href = "<?= base_url("workshop") ?>";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: data.message
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire(errorThrown);
                },
                beforeSend: function() {
                    $("#animatedPreloader").show();
                },
                complete: function() {
                    $("#animatedPreloader").fadeOut();
                },
            });
        }
    });

    function appendTotalCharge(charge_cost) {
        const float_charge_cost = parseFloat(charge_cost.replace(/,/g, ''));

        total_cost = (total_cost + float_charge_cost)

        $('#total_charge_text').html("Rp. " + addCommas(total_cost))
        $('#total_charge').val(addCommas(total_cost))
    }


    // Add Charge Handler
    $("#frmSaveCharge").on("submit", function(e) {
        e.preventDefault();
        if ($(this).parsley().isValid()) {
            var form = $(this)[0];
            var formData = new FormData(form);
            const charge_name = $('#charge_name').val();
            const charge_cost = $('#charge_cost').val();

            $.ajax({
                url: '<?= base_url("workshop/submit_form_charge") ?>',
                data: formData,
                dataType: "JSON",
                type: "POST",
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: data.message
                        }).then(function() {
                            // Add action buttons
                            htmlButtons = ''
                            htmledit =
                                '<button onclick="openModalEditCharge(\'' + data.id_workshop_charge + '\'' + rowIndex + ')" data-toggle="tooltip" data-placement="top" title="Edit" type="button" class="btn light btn-success btn-sm sharp mr-1"><i class="fas fa-pen"></i></button>'
                            htmlDelete =
                                '<button onclick="deleteCharge(\'' + data.id_workshop_charge + '\')" data-toggle="tooltip" data-placement="top" title="Delete" type="button" class="btn light btn-danger btn-sm sharp mr-1"><i class="fas fa-trash"></i></button>'
                            htmlButtons += htmledit;
                            htmlButtons += htmlDelete;
                            // End Add action buttons

                            // Append Data to Datatable
                            var newRow = $('<tr id="row_data_charge_repair_' + data.id_workshop_charge + '">');
                            var cell1 = $(' <td > ').text(data.id_workshop_charge);
                            var cell2 = $(' <td > ').text(charge_name);
                            var cell3 = $(' <td > ').text(charge_cost);
                            var cell4 = $(' <td > ').text('-');
                            var cell5 = $(' <td > ').html(htmlButtons);
                            newRow.append(cell1, cell2, cell3, cell4, cell5);
                            tblCost.row.add(newRow).draw();
                            // Append Data to Datatable

                            $('#modal_charge_repair').modal('hide');
                            $("#total_charge_text").html("Rp " + addCommas(data.current_total))

                            rowIndex++
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: data.message
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire(errorThrown);
                },
                beforeSend: function() {
                    $("#animatedPreloader").show();
                },
                complete: function() {
                    $("#animatedPreloader").fadeOut();
                },
            });
        }
    });
    // End Add Charge Handler

    // Edit Charge Handler
    $("#frmSaveChargeEdit").on("submit", function(e) {
        e.preventDefault();
        if ($(this).parsley().isValid()) {
            var form = $(this)[0];
            var formData = new FormData(form);
            $.ajax({
                url: '<?= base_url("workshop/submit_form_charge_edit") ?>',
                data: formData,
                dataType: "JSON",
                type: "POST",
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: data.message
                        }).then(function() {
                            const table_index_edit = $('#table_index_edit').val();
                            const charge_id = $('#charge_id').val()
                            const charge_name_edit = $('#charge_name_edit').val()
                            const charge_cost_edit = $('#charge_cost_edit').val()


                            // Set Holidays Name
                            $('td:eq(0)', tblCost.rows().nodes()[table_index_edit]).text(charge_name_edit);
                            // End Set Holidays Name

                            // Set Holidays Name
                            $('td:eq(1)', tblCost.rows().nodes()[table_index_edit]).text(charge_cost_edit);
                            // End Set Holidays Name


                            // End Get all charge cost
                            $("#total_charge_text").html("Rp " + addCommas(data.current_total))
                            $('#modal_charge_repair_edit').modal('hide');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: data.message
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire(errorThrown);
                },
                beforeSend: function() {
                    $("#animatedPreloader").show();
                },
                complete: function() {
                    $("#animatedPreloader").fadeOut();
                },
            });
        }
    });
    // End Edit Charge Handler
</script>