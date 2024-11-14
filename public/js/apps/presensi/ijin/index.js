$('.select2-js').select2({
    minimumResultsForSearch: Infinity
});

var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    ajax: {
        url: '/presensi/ijin/datatable',
        type: 'POST',
        data: function(d) {
            d._token = token;
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.unit = $('#filter_unit').val();
            d.tipe_ijin = $('#filter_tipe_ijin').val();
            d.staff = $('#filter_staff').val();
            d.status = $('#filter_status').val();
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'pin', name: 'k.pin'},
        {data: 'nama_karyawan', name: 'k.nama'},
        {data: 'nama_unit', name: 'u.nama'},
        {data: 'nama_tipe_ijin', name: 'mi.nama'},
        {data: 'tanggal_ijin', name: 'i.tanggal_awal'},
        {data: 'jml_hari', orderable: false, searchable: false},
        {data: 'keterangan', orderable: false, searchable: false},
        {data: 'lampiran', orderable: false, searchable: false},
        {data: 'status'},
        {data: 'action', orderable: false, searchable: false},
    ],
    columnDefs: [
        {
            targets: -2,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {
                
                var status = {
                    0: {'title': 'Dibatalkan', 'class': ' bg-warning'},
                    1: {'title': 'Baru', 'class': ' bg-info'},
                    2: {'title': 'Disetujui', 'class': ' bg-success'},
                    3: {'title': 'Ditolak', 'class': ' bg-danger'}
                };
                if (typeof status[full['status']] === 'undefined') {
                    return data;
                }
                return '<span class="badge '+ status[full['status']].class +'">'+ status[full['status']].title +'</span>';
            }
        },
        {
            targets: 8,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {
                if(full['lampiran']) {
                    return `<a target="_blank" href="${full['lampiran']}" class="btn btn-theme-sml btn-sm"><em class="icon ni ni-download"></em></a>`;
                }else{
                    return '';
                }
            }
        }
    ] 
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

$('#filter_tipe_ijin').select2({
    placeholder: 'Pilih Tipe Ijin',
    allowClear: true,
    ajax: {
        url: '/data-tipe-ijin',
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

$('#filter_staff').select2({
    placeholder: 'Pilih Staff',
    allowClear: true,
    ajax: {
        url: '/data-karyawan',
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
    },
    minimumInputLength: 3
})

$('#staff').select2({
    placeholder: 'Pilih Staff',
    allowClear: true,
    dropdownParent: $('#modalForm'),
    ajax: {
        url: '/data-karyawan',
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
    },
    minimumInputLength: 3
})

$('#tipe_ijin').select2({
    placeholder: 'Pilih Tipe Ijin',
    allowClear: true,
    dropdownParent: $('#modalForm'),
    ajax: {
        url: '/data-tipe-ijin',
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

function tambah() {
    $('#modalForm').modal('show');
    $('#form-data')[0].reset();
    $('#staff').val('').change();
    $('#tipe_ijin').val('').change();
    $('#lampiran').next('label').html('Choose file');
}

$('#form-data').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit');

    $.ajax({
        url : "/presensi/ijin/store",  
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
                $('#form-data')[0].reset();
                $('#modalForm').modal('hide');
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
});

function approval(kode) {
    $('#modalFormApproval').modal('show');
    $('#kode_approval').val(kode);
}

$('#form-data-approval').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit-approval');

    Swal.fire({
        title: `Apakah anda yakin akan menyimpan data ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, saya yakin',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url : "/presensi/ijin/approval",  
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
                        $('#form-data-approval')[0].reset();
                        $('#modalFormApproval').modal('hide');
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