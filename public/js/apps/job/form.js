$(document).ready(function() {

    $('.format-number').keyup(function() {
        $(this).val(function (index, value) {
            return value.replace(/\D/g, "");
        });
    });

    $('.select2-js').select2({
        minimumResultsForSearch: Infinity
    });

    // Edit Form
    let id = $('#id').val();
    if(id) {
        $.ajax({
            url: '/job/edit/'+id,
            dataType: 'json',
            success: function(response) {
                let order = response.data.order;
                if(response.status) {
                    $('#nama').val(order.nama);
                    $('#tanggal').datepicker('setDate', order.tanggal);
                    $('#deadline').datepicker('setDate', order.deadline);
                    $('#jenis_produk').val(order.jenis_produk);
                    $('#tambahan').val(order.tambahan);
                    $('#ukuran').val(order.ukuran);
                    $('#jumlah').val(order.jumlah);
                    $('#jenis_kertas').val(order.jenis_kertas);
                    $('#finishing_satu').val(order.finishing_satu);
                    $('#finishing_dua').val(order.finishing_dua);
                    $('#pengambilan').val(order.pengambilan).change();
                    $('#order_by').val(order.order_by).change();
                    $('#keterangan').val(order.keterangan);
                }

                let detail = response.data.detail;
                if(detail) {
                    // loop selected checkboxes divisi
                    $.each(detail, function(index, value) {
                        $('#'+value.uid_divisi).prop('checked', true);
                    });
                }
            },
            error: function(error) {
                console.log(error)
                NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
            }
        })
    }
    
    $('#form-data').submit(function(e) {
        e.preventDefault();
        formData = new FormData($(this)[0]);
        var btn = $('#btn-submit');

        $.ajax({
            url : "/job/store",  
            data : formData,
            type : "POST",
            dataType : "JSON",
            cache:false,
            async : true,
            contentType: false,
            processData: false,
            beforeSend: function() {
                btn.attr('disabled', true);
                btn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span>Loading ...</span>`);
            },
            success: function(response) {
                if(response.status){
                    NioApp.Toast(response.message, 'success', {position: 'top-right'});
                    setTimeout(function(){
                        window.location.href = '/job';
                    }, 2000)
                }else{
                    NioApp.Toast(response.message, 'warning', {position: 'top-right'});
                }
                btn.attr('disabled', false);
                btn.html('Save');
            },
            error: function(error) {
                console.log(error)
                btn.attr('disabled', false);
                btn.html('Save');
                NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
            }
        });
    });
});

