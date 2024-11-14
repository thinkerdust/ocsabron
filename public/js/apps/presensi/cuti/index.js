$('.select2-js').select2({
    minimumResultsForSearch: Infinity
});

// saldo cuti
$('#tab-saldo-cuti').click(function() {
    $("#dt-table-saldo-cuti").DataTable().ajax.reload(null, false);
});

var tableSaldoCuti = NioApp.DataTable('#dt-table-saldo-cuti', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    ajax: {
        url: '/presensi/cuti/datatable-saldo-cuti',
        type: 'POST',
        data: function(d) {
            d._token = token;
            d.unit = $('#filter_unit_saldo').val();
            d.divisi = $('#filter_divisi_saldo').val();
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'pin', name: 'k.pin'},
        {data: 'nama_karyawan', name: 'k.nama'},
        {data: 'nama_unit', name: 'u.nama'},
        {data: 'tanggal_masuk', name: 'k.tanggal_masuk'},
        {data: 'jumlah', name: 'c.jumlah'},
        {data: 'action', orderable: false, searchable: false},
    ],
    columnDefs: [] 
});

$('#btn-filter-saldo-cuti').click(function() {
    $("#dt-table-saldo-cuti").DataTable().ajax.reload();
})

$('#filter_unit_saldo').select2({
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

$("#filter_divisi_saldo").empty().append(`<option value="">Pilih Divisi</option>`).val('').trigger('change');

$('#filter_unit_saldo').change(function() {
    $("#filter_divisi_saldo").empty().append(`<option value="">Pilih Divisi</option>`).val('').trigger('change');
    let kode = $(this).val();
    if(kode) {
        $('#filter_divisi_saldo').select2({
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

function reproses_cuti(nip) {
    Swal.fire({
        title: 'Apakah anda yakin akan reproses cuti ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, saya yakin',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/presensi/cuti/reproses-data',
                dataType: 'JSON',
                type: 'POST',
                data: {'_token': token, 'nip': nip},
                beforeSend: function() {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Please wait while we load the data.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        onOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    if(response.status){
                        $("#dt-table-saldo-cuti").DataTable().ajax.reload(null, false);
                        NioApp.Toast(response.message, 'success', {position: 'top-right'});
                    }else{
                        NioApp.Toast(response.message, 'warning', {position: 'top-right'});
                    }
                    Swal.close();
                },
                error: function(error) {
                    console.log(error)
                    NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
                }
            })
        }
    });
}

// data cuti
$('#tab-data-cuti').click(function() {
    $("#dt-table-data-cuti").DataTable().ajax.reload(null, false);
});

var tableDataCuti = NioApp.DataTable('#dt-table-data-cuti', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    ajax: {
        url: '/presensi/cuti/datatable-data-cuti',
        type: 'POST',
        data: function(d) {
            d._token = token;
            d.start_date = $('#start_date_data').val();
            d.end_date = $('#end_date_data').val();
            d.unit = $('#filter_unit_data').val();
            d.tipe_cuti = $('#filter_tipe_cuti_data').val();
            d.staff = $('#filter_staff_data').val();
            d.status = $('#filter_status_data').val();
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'pin', name: 'k.pin'},
        {data: 'nama_karyawan', name: 'k.nama'},
        {data: 'nama_unit', name: 'u.nama'},
        {data: 'nama_tipe_cuti', name: 'mc.nama'},
        {data: 'tanggal_cuti', name: 'ck.tanggal_awal'},
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

$('#btn-filter-data-cuti').click(function() {
    $("#dt-table-data-cuti").DataTable().ajax.reload();
})

$('#filter_unit_data').select2({
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

$('#filter_tipe_cuti_data').select2({
    placeholder: 'Pilih Tipe Cuti',
    allowClear: true,
    ajax: {
        url: '/data-tipe-cuti',
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

$('#filter_staff_data').select2({
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

$('#staff_cuti').select2({
    placeholder: 'Pilih Staff',
    allowClear: true,
    dropdownParent: $('#modalFormCuti'),
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

$('#tipe_cuti').select2({
    placeholder: 'Pilih Tipe Cuti',
    allowClear: true,
    dropdownParent: $('#modalFormCuti'),
    ajax: {
        url: '/data-tipe-cuti',
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

function tambah_cuti() {
    $('#modalFormCuti').modal('show');
    $('#form-data-cuti')[0].reset();
    $('#staff_cuti').val('').change();
    $('#tipe_cuti').val('').change();
    $('#lampiran_cuti').next('label').html('Choose file');
}

$('#form-data-cuti').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit-cuti');

    $.ajax({
        url : "/presensi/cuti/store",  
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
                $('#form-data-cuti')[0].reset();
                $('#modalFormCuti').modal('hide');
                $("#dt-table-data-cuti").DataTable().ajax.reload(null, false);
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

function approval_cuti(kode) {
    $('#modalFormApprovalCuti').modal('show');
    $('#kode_approval_cuti').val(kode);
}

$('#form-data-approval-cuti').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit-approval-cuti');

    Swal.fire({
        title: `Apakah anda yakin akan menyimpan data ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, saya yakin',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url : "/presensi/cuti/approval",  
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
                        $('#form-data-approval-cuti')[0].reset();
                        $('#modalFormApprovalCuti').modal('hide');
                        $("#dt-table-data-cuti").DataTable().ajax.reload(null, false);
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