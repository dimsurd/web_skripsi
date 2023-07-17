</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy; 2023 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        Habibi
    </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<?php $this->load->view("templates/template_script_footer"); ?>

<script>
    const dataTable = $('#myTable').DataTable();

    $('#searchInput').keyup(function() {
        dataTable.search($(this).val()).draw();
    });

    $('.text-only').on('input', function() {
        var input = $(this).val();
        var sanitizedInput = input.replace(/[^a-zA-Z\s]/g, '');
        $(this).val(sanitizedInput);
    });

    $(".select-2").select2();

    $("#btn-logout").on('click', () => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url("auth/log_out"); ?>';
            }
        })
    })


    function numberWithCommas(x) {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }


    function change_prices(dis, selector) {
        if (dis.value != '' && dis.value != null) {
            var ids = "#" + selector;
            valuee = dis.value.replace(/,/g, "");
            $(ids).val(valuee).trigger('change');
            dis.value = numberWithCommas(valuee);
        }
    }

    function addCommas(nStr) {
        nStr += "";
        x = nStr.split(".");
        x1 = x[0];
        x2 = x.length > 1 ? "." + x[1] : "";
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, "$1" + "," + "$2");
        }
        return x1 + x2;
    }

    function number_format(number, decimals, decPoint, thousandsSep) {
        number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
        var n = !isFinite(+number) ? 0 : +number;
        var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
        var sep = typeof thousandsSep === "undefined" ? "," : thousandsSep;
        var dec = typeof decPoint === "undefined" ? "." : decPoint;
        var s = "";

        var toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return "" + (Math.round(n * k) / k).toFixed(prec);
        };

        // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || "").length < prec) {
            s[1] = s[1] || "";
            s[1] += new Array(prec - s[1].length + 1).join("0");
        }

        return s.join(dec);
    }

    function validate_number(evt) {
        evt.value = evt.value.replace(/[^0-9.]/g, "");
    }
</script>
</body>

</html>