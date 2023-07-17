<div class="modal fade" id="modal_charge_repair" tabindex="-1" aria-labelledby="modal_charge_repairLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="modal_charge_repairLabel">Add Charge Repair</h5>
            </div>
            <div class="modal-body">
                <form class="parsleyy" data-parsley-validate="" novalidate="" id="frmSaveCharge">
                    <input type="hidden" name="workshop_id" id="workshop_id">
                    <input type="hidden" name="table_index" id="table_index">
                    <div class="row mb-2">
                        <div class="col-md">
                            <div class="form-group">
                                <label>Name<span style="color:red">*</span></label>
                                <input type="text" class="form-control" parsley-trigger="change" id="charge_name" name="charge_name" placeholder="Insert Charge Name" required="">
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <label>Cost<span style="color:red">*</span></label>
                                <input type="text" class="form-control" parsley-trigger="change" id="charge_cost" name="charge_cost" placeholder="Insert Charge Cost" onkeyup="validate_number(this),number_format(this),change_prices(this)" required="">
                            </div>
                        </div>
                    </div>
            </div>
            <!-- </div> -->
            <div class="modal-footer">
                <div class="rows" style="float: right;">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-danger ml-2 btn_close_modal" data-bs-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" data-backdrop="static" data-keyboard="false" id="modal_charge_repair_edit" role="dialog" aria-labelledby="usersModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="parsleyy" data-parsley-validate="" novalidate="" id="frmSaveChargeEdit">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="usersModalLabel">Edit Charge Repair</h5>
                    <input type="hidden" name="charge_id" id="charge_id">
                    <input type="hidden" name="table_index_edit" id="table_index_edit">
                </div>
                <div class="modal-body" style="padding: 1rem 1rem 2rem;">
                    <div class="row mb-2">
                        <div class="col-md">
                            <div class="form-group">
                                <label>Name<span style="color:red">*</span></label>
                                <input type="text" class="form-control" parsley-trigger="change" id="charge_name_edit" name="charge_name_edit" placeholder="Insert Charge Name" required="">
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <label>Cost<span style="color:red">*</span></label>
                                <input type="text" class="form-control" parsley-trigger="change" id="charge_cost_edit" name="charge_cost_edit" placeholder="Insert Charge Cost" onkeyup="validate_number(this),number_format(this),change_prices(this)" required="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="rows" style="float: right;">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-danger ml-2 btn_close_modal" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>