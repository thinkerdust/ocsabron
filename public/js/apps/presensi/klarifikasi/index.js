var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    ajax: {
        url: '/presensi/klarifikasi/datatable',
        type: 'POST',
        data: function(d) {
            d._token = token;
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.unit = $('#filter_unit').val();
            d.divisi = $('#filter_divisi').val();
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'pin', name: 'sq.pin'},
        {data: 'nama_karyawan', name: 'sq.nama_karyawan'},
        {data: 'nama_unit', name: 'sq.nama_unit'},
        {data: 'format_tanggal', name: 'sq.tanggal'},
        {data: 'jadwal_masuk', name: 'sq.jadwal_masuk'},
        {data: 'jadwal_pulang', name: 'sq.jadwal_pulang'},
        {data: 'scan_masuk', name: 's1.scan_date'},
        {data: 'scan_pulang', name: 's1.scan_date'},
        {data: 'keterangan', orderable: false, searchable: false},
        {data: 'action', orderable: false, searchable: false},
    ],
    columnDefs: [] 
});

$('#btn-filter').click(function() {
    $("#dt-table").DataTable().ajax.reload();
})

$('#filter_unit').select2({
    placeholder: 'Pilih Unit',
    allowClear: true,
    ajax: {
        url: '/data-unit',
        dataType: "json",
        type: "GET",
        delay: 250,
        data: function (params) {
            return { q: params.term };
        },
        processResults: function (data, params) {
            return {
                results: $.map(data, function (item) {
                    return {
                        text: item.nama,
                        id: item.id
                    }
                })
            };
        },
        cache: true
    }
})

$("#filter_divisi").empty().append(`<option value="">Pilih Divisi</option>`).val('').trigger('change');

$('#filter_unit').change(function() {
    $("#filter_divisi").empty().append(`<option value="">Pilih Divisi</option>`).val('').trigger('change');
    let kode = $(this).val();
    if(kode) {
        $('#filter_divisi').select2({
            placeholder: 'Pilih Divisi',
            allowClear: true,
            ajax: {
                url: '/data-divisi?kode_unit='+kode,
                dataType: "json",
                type: "GET",
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        })
    }
})

function klarifikasi(kode) {
    $('#modalFormKlarifikasi').modal('show');
    $('#kode_klarifikasi').val(kode);
}

$('#form-data-klarifikasi').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit-klarifikasi');

    Swal.fire({
        title: `Apakah anda yakin akan menyimpan data ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, saya yakin',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url : "/presensi/klarifikasi/approval",  
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
                        $('#form-data-klarifikasi')[0].reset();
                        $('#modalFormKlarifikasi').modal('hide');
                        $("#dt-table").DataTable().ajax.reload(null, false);
                        NioApp.Toast(response.message, 'success', {position: 'top-right'});
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
        }
    })
});