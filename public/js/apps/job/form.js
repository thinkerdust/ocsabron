$(document).ready(function() {

    $('.number').keyup(function() {
        $(this).val(function (index, value) {
            return value.replace(/\D/g, "");
        });
    });

    // Edit Form
    let id = $('#id').val();
    if(id) {
        console.log('masuk', id)
        $.ajax({
            url: '/job/edit/'+id,
            dataType: 'json',
            success: function(response) {
                if(response.status) {
                    let data = response.data;

                    $('#nama').val(data[0].nama);
                    $('#tanggal').datepicker('setDate', moment(data[0].tanggal).format('DD-MM-YYYY'));
                    $('#deadline').datepicker('setDate', moment(data[0].deadline).format('DD-MM-YYYY'));
                    $('#jenis_produk').val(data[0].jenis_produk);
                    $('#tambahan').val(data[0].tambahan);
                    $('#ukuran').val(data[0].ukuran);
                    $('#jumlah').val(data[0].jumlah);
                    $('#jenis_kertas').val(data[0].jenis_kertas);
                    $('#finishing_satu').val(data[0].finishing_satu);
                    $('#finishing_dua').val(data[0].finishing_dua);
                    $('#pengambilan').val(data[0].pengambilan).change();
                    $('#order_by').val(data[0].order_by).change();
                    $('#keterangan').val(data[0].keterangan);

                    // loop selected checkboxes divisi
                    $.each(data, function(index, value) {
                        $('#'+value.divisi).prop('checked', true);
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


