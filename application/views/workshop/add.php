<?php $this->load->view("templates/template_header"); ?>
<!-- Main content -->
<section class="content pt-3">
    <div class="container-fluid">
        <h1>Add Page</h1>
        <div class="row pb-2">
            <div class="col-12">
                <form id="frmAdd" data-parsley-validate="">
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
                                            <input type="text" class="form-control" parsley-trigger="change" id="nopol" name="nopol" placeholder="Insert Car's Plate Number" required="">
                                        </div>
                                    </div>
                                    <div class="col-md mb-3">
                                        <div class="form-group">
                                            <label>Customer Name<span style="color:red">*</span></label>
                                            <input type="text" class="form-control" parsley-trigger="change" id="customer_name" name="customer_name" placeholder="Insert Customer Name" required="">
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
                                    <div class="col-md text-end">
                                        <button type="button" class="btn btn-primary float-right" onclick="$('#modal_charge_repair').modal('show')">Add Data
                                            <span class="btn-icon-right"><i class="fas fa-plus-circle"></i></span>
                                        </button>
                                    </div>
                                </div>
                                <table id="dataTblCost" class="table table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Cost</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_table_preview">
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
                                        <h3 id="total_charge_text">Rp. </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row float-right">
                        <a href="<?= base_url("workshop") ?>" type="button" class="btn btn-danger mr-2">Back</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
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
    const tblCost = $('#dataTblCost').DataTable();
    let charge_repair_id = 0;
    let total_cost = 0;

    $('#modal_charge_repair').on('hidden.bs.modal', function(e) {
        $('#frmSaveCharge').trigger('reset')
    })
    $('#modal_charge_repair_edit').on('hidden.bs.modal', function(e) {
        $('#frmSaveChargeEdit').trigger('reset')
    })

    $('.btn_close_modal').on('click', () => {
        $('.modal').modal('hide')
    })

    function openModalEditCharge(modalName, idData) {
        $('#' + modalName).modal('show');
        const charge_name = $('#charge_repair_name_' + idData).val()
        const charge_cost = $('#charge_repair_cost_' + idData).val()

        $('#charge_id').val(idData);
        $('#charge_name_edit').val(charge_name);
        $('#charge_cost_edit').val(charge_cost);

    }

    function deleteCharge(idData) {

        // Get a reference to the row that we want to delete
        var rowToDelete = tblCost.row('#row_data_charge_repair_' + idData);
        const costDeleted = $("#charge_repair_cost_" + idData).val(); // Use idData instead of 0


        // Count Total
        const float_charge_cost = parseFloat(costDeleted.replace(/,/g, ''));

        total_cost = (total_cost - float_charge_cost)
        $('#total_charge_text').html("Rp. " + addCommas(total_cost))
        $('#total_charge').val(addCommas(total_cost))
        // End Count Total

        // Remove the row from the table
        $('.charge_repair_value_' + idData).remove();
        rowToDelete.remove().draw();
    }


    $("#frmAdd").on("submit", function(e) {
        e.preventDefault();
        if ($(this).parsley().isValid()) {
            var form = $(this)[0];
            var formData = new FormData(form);
            $.ajax({
                url: "<?= base_url("workshop/submit_form") ?>",
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
            const charge_name = $('#charge_name').val();
            const charge_cost = $('#charge_cost').val();
            const charge_repair_input_fields_container = $('#charge_repair_input_fields');

            // Add action buttons
            htmlButtons = ''
            htmledit =
                '<div class="btn-group btn-sm" style="float: left; margin-right: 2px; padding:0;">' +
                '<button onclick="openModalEditCharge(\'modal_charge_repair_edit\',\'' + charge_repair_id + '\')" data-toggle="tooltip" data-placement="top" title="Edit" type="button" class="btn light btn-success btn-sm sharp mr-1"><i class="fas fa-pen"></i></button>' +
                '</div>';
            htmlDelete =
                '<div class="btn-group btn-sm" style="float: left; margin-right: 2px; padding:0;">' +
                '<button onclick="deleteCharge(\'' + charge_repair_id + '\')" data-toggle="tooltip" data-placement="top" title="Delete" type="button" class="btn light btn-danger btn-sm sharp mr-1"><i class="fas fa-trash"></i></button>' +
                '</div>';
            htmlButtons += htmledit;
            htmlButtons += htmlDelete;
            // End Add action buttons

            // Append Data to Datatable
            var newRow = $('<tr id="row_data_charge_repair_' + charge_repair_id + '">');
            var cell1 = $(' <td > ').text(charge_name);
            var cell2 = $(' <td > ').text(charge_cost);
            var cell3 = $(' <td > ').html(htmlButtons);
            newRow.append(cell1, cell2, cell3);
            // newRow.append(cell1, cell2, cell3, cell4);
            tblCost.row.add(newRow).draw();
            // Append Data to Datatable

            charge_repair_input_fields_container.append("<input type='hidden' class='charge_repair_value_" + charge_repair_id + "' name='charge_repair_name[]' id='charge_repair_name_" + charge_repair_id + "' value='" + charge_name + "'>")
            charge_repair_input_fields_container.append("<input type='hidden' class='charge_cost charge_repair_value_" + charge_repair_id + "' name='charge_repair_cost[]' id='charge_repair_cost_" + charge_repair_id + "' value='" + charge_cost + "'>")

            // Count Total Charge
            appendTotalCharge(charge_cost)
            // End Count Total Charge

            $('#modal_charge_repair').modal('hide');
            charge_repair_id++
        }
    });
    // End Add Charge Handler

    // Edit Charge Handler
    $("#frmSaveChargeEdit").on("submit", function(e) {
        e.preventDefault();
        if ($(this).parsley().isValid()) {
            const charge_id = $('#charge_id').val()
            const charge_name_edit = $('#charge_name_edit').val()
            const charge_cost_edit = $('#charge_cost_edit').val()


            // Set Holidays Name
            $('td:eq(0)', tblCost.rows().nodes()[charge_id]).text(charge_name_edit);
            $('#charge_repair_name_' + charge_id).val(charge_name_edit)
            // End Set Holidays Name

            // Set Holidays Name
            $('td:eq(1)', tblCost.rows().nodes()[charge_id]).text(charge_cost_edit);
            $('#charge_repair_cost_' + charge_id).val(charge_cost_edit)
            // End Set Holidays Name


            // Get all charge cost
            let sum = 0
            $('.charge_cost').each(function() {
                sum += parseFloat(this.value.replace(/,/g, ''));
            });
            // End Get all charge cost
            $('#total_charge_text').html("Rp. " + addCommas(sum))
            $('#total_charge').val(addCommas(sum))

            $('#modal_charge_repair_edit').modal('hide');
        }
    });
    // End Edit Charge Handler
</script>