$(document).ready(function() {

    $('.format-number').keyup(function() {
        $(this).val(function (index, value) {
            return value.replace(/\D/g, "");
        });
    });

    $('.timepicker').datetimepicker({
        format: 'HH:mm:ss',
        icons: {
            up: "fas fa-chevron-up",    // Replace with your desired up icon class
            down: "fas fa-chevron-down" // Replace with your desired down icon class
        }
    })

    $('.select2-js').select2({
        minimumResultsForSearch: Infinity
    });

    $('#tabel-cetak').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false
    });
    
    $('#btn-add').click(function() {

        // get data then add to table
        let mesin           = $('#mesin').val();
        let set             = $('#set').val();
        let model_cetak     = $('#model_cetak').val();
        let order           = $('#order').val();
        let insheet         = $('#insheet').val();
        let jumlah_plat     = $('#jumlah_plat').val();
        let keterangan      = $('#keterangan').val();

        if(mesin == '' || set == '' || model_cetak == '' || order == '' || insheet == '' || jumlah_plat == '' || keterangan == '') {
            NioApp.Toast('Data tidak boleh kosong', 'warning', {position: 'top-right'});
            return false;
        }

        let table = $('#tabel-cetak').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false,
            "destroy": true
        });

        table.row.add([
            `${mesin}<input type="hidden" name="mesin[]" value="${mesin}">`,
            `${set}<input type="hidden" name="set[]" value="${set}">`,
            `${model_cetak}<input type="hidden" name="model_cetak[]" value="${model_cetak}">`,
            `${order}<input type="hidden" name="order[]" value="${order}">`,
            `${insheet}<input type="hidden" name="insheet[]" value="${insheet}">`,
            `${jumlah_plat}<input type="hidden" name="jumlah_plat[]" value="${jumlah_plat}">`,
            `${keterangan}<input type="hidden" name="keterangan[]" value="${keterangan}">`,
            `<button type="button" class="btn btn-danger btn-sm btn-delete" id="btn-delete">
                <em class="icon ni ni-trash-fill"></em>
            </button>`
        ]).draw(false);
            
        $('#mesin').val('');
        $('#set').val('');
        $('#model_cetak').val('');
        $('#order').val('');
        $('#insheet').val('');
        $('#jumlah_plat').val('');
        $('#keterangan').val('');

    })

    $('#tabel-cetak').on('click', '.btn-delete', function() {
        let table = $('#tabel-cetak').DataTable();
        table.row($(this).parents('tr')).remove().draw();
    });
});


