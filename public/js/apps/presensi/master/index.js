// Hari Libur
var tableHariLibur = NioApp.DataTable('#dt-table-hari-libur', {
    serverSide: true,
    processing: true,
    responsive: false,
    scrollX: true,
    searchDelay: 500,
    ajax: {
        url: '/presensi/master/datatable-hari-libur',
        type: 'POST',
        data: function(d) {
            d._token = token;
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'tanggal'},
        {data: 'keterangan', searchable: false},
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
                    0: {'title': 'Non-Aktif', 'class': ' bg-danger'},
                    1: {'title': 'Aktif', 'class': ' bg-success'},
                };
                if (typeof status[full['status']] === 'undefined') {
                    return data;
                }
                return '<span class="badge '+ status[full['status']].class +'">'+ status[full['status']].title +'</span>';
            }
        },
    ] 
});

$('#tab-hari-libur').click(function() {
    $("#dt-table-hari-libur").DataTable().ajax.reload(null, false);
});

function tambah_hari_libur() {
    $('#modalFormHariLibur').modal('show');
    $('#form-data-hari-libur')[0].reset();
    $('#kode_hari_libur').val('');
}

$('#form-data-hari-libur').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit-hari-libur');

    $.ajax({
        url : "/presensi/master/store-hari-libur",  
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
                $('#form-data-hari-libur')[0].reset();
                $('#modalFormHariLibur').modal('hide');
                $("#dt-table-hari-libur").DataTable().ajax.reload(null, false);
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

function edit_hari_libur(kode) {
    $.ajax({
        url: '/presensi/master/edit-hari-libur/'+kode,
        dataType: 'JSON',
        success: function(response) {
            if(response.status) {
                $('#modalFormHariLibur').modal('show');
                let data = response.data;
                $('#kode_hari_libur').val(kode);
                $('#tanggal_hari_libur').val(data.tanggal);
                $('#keterangan_hari_libur').val(data.keterangan);
            }
        },
        error: function(error) {
            console.log(error)
            NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
        }
    })
}

function hapus_hari_libur(kode) {
    Swal.fire({
        title: 'Apakah anda yakin akan menghapus data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus data.'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/presensi/master/delete-hari-libur/'+kode,
                dataType: 'JSON',
                success: function(response) {
                    if(response.status){
                        $("#dt-table-hari-libur").DataTable().ajax.reload(null, false);
                        NioApp.Toast(response.message, 'success', {position: 'top-right'});
                    }else{
                        NioApp.Toast(response.message, 'warning', {position: 'top-right'});
                    }
                },
                error: function(error) {
                    console.log(error)
                    NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
                }
            })
        }
    });
}

// END: Hari Libur

$('.timepicker').datetimepicker({
    format: 'HH:mm:ss',
    icons: {
        up: "fas fa-chevron-up",    // Replace with your desired up icon class
        down: "fas fa-chevron-down" // Replace with your desired down icon class
    }
})

// Shift
var tableShift = NioApp.DataTable('#dt-table-shift', {
    serverSide: true,
    processing: true,
    responsive: false,
    scrollX: true,
    searchDelay: 500,
    ajax: {
        url: '/presensi/master/datatable-shift',
        type: 'POST',
        data: function(d) {
            d._token = token;
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'kode'},
        {data: 'nama'},
        {data: 'jam_masuk'},
        {data: 'jam_pulang'},
        {data: 'jam_istirahat'},
        {data: 'jam_lembur'},
        {data: 'flag_libur'},
        {data: 'status'},
        {data: 'action', orderable: false, searchable: false},
    ],
    columnDefs: [
        {
            targets: 7,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {
                
                var status = {
                    0: {'title': 'Tidak Libur', 'class': ' bg-gray'},
                    1: {'title': 'Libur', 'class': ' bg-primary'},
                };
                if (typeof status[full['flag_libur']] === 'undefined') {
                    return data;
                }
                return '<span class="badge '+ status[full['flag_libur']].class +'">'+ status[full['flag_libur']].title +'</span>';
            }
        },
        {
            targets: -2,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {
                
                var status = {
                    0: {'title': 'Non-Aktif', 'class': ' bg-danger'},
                    1: {'title': 'Aktif', 'class': ' bg-success'},
                };
                if (typeof status[full['status']] === 'undefined') {
                    return data;
                }
                return '<span class="badge '+ status[full['status']].class +'">'+ status[full['status']].title +'</span>';
            }
        },
    ] 
});

$('#tab-shift').click(function() {
    $("#dt-table-shift").DataTable().ajax.reload(null, false);
});

$('#kode_shift').on('keyup', function() {
    var value = $(this).val();
    $(this).val(value.replace(/\s/g, ''));
});

function tambah_shift() {
    $('#modalFormShift').modal('show');
    $('#form-data-shift')[0].reset();
    $('#id_shift').val('');
    $('#libur_shift').val(0).change();
    $('#kode_shift').attr('readonly', false);
}

$('#form-data-shift').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit-shift');

    $.ajax({
        url : "/presensi/master/store-shift",  
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
                $('#form-data-shift')[0].reset();
                $('#modalFormShift').modal('hide');
                $("#dt-table-shift").DataTable().ajax.reload(null, false);
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

function edit_shift(id) {
    $.ajax({
        url: '/presensi/master/edit-shift/'+id,
        dataType: 'JSON',
        success: function(response) {
            if(response.status) {
                $('#modalFormShift').modal('show');
                let data = response.data;
                $('#id_shift').val(id);
                $('#kode_shift').val(data.kode);
                $('#kode_shift').attr('readonly', true);
                $('#nama_shift').val(data.nama);
                $('#jam_masuk_shift').val(data.jam_masuk);
                $('#jam_pulang_shift').val(data.jam_pulang);
                $('#jam_istirahat_shift').val(data.jam_istirahat);
                $('#jam_lembur_shift').val(data.jam_lembur);
                $('#keterangan_shift').val(data.keterangan);
                $('#libur_shift').val(data.flag_libur).change();
            }
        },
        error: function(error) {
            console.log(error)
            NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
        }
    })
}

function hapus_shift(id) {
    Swal.fire({
        title: 'Apakah anda yakin akan menghapus data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus data.'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/presensi/master/delete-shift/'+id,
                dataType: 'JSON',
                success: function(response) {
                    if(response.status){
                        $("#dt-table-shift").DataTable().ajax.reload(null, false);
                        NioApp.Toast(response.message, 'success', {position: 'top-right'});
                    }else{
                        NioApp.Toast(response.message, 'warning', {position: 'top-right'});
                    }
                },
                error: function(error) {
                    console.log(error)
                    NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
                }
            })
        }
    });
}
// END: Shift

// Cuti
var tableCuti = NioApp.DataTable('#dt-table-cuti', {
    serverSide: true,
    processing: true,
    responsive: false,
    scrollX: true,
    searchDelay: 500,
    ajax: {
        url: '/presensi/master/datatable-cuti',
        type: 'POST',
        data: function(d) {
            d._token = token;
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'nama'},
        {data: 'keterangan', orderable: false,},
        {data: 'jumlah_hari'},
        {data: 'potong_gaji'},
        {data: 'potong_cuti'},
        {data: 'status'},
        {data: 'action', orderable: false, searchable: false},
    ],
    columnDefs: [
        {
            targets: 4,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {
                
                var status = {
                    0: {'title': 'Tidak', 'class': ' bg-gray'},
                    1: {'title': 'Ya', 'class': ' bg-primary'},
                };
                if (typeof status[full['potong_gaji']] === 'undefined') {
                    return data;
                }
                return '<span class="badge '+ status[full['potong_gaji']].class +'">'+ status[full['potong_gaji']].title +'</span>';
            }
        },
        {
            targets: 5,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {
                
                var status = {
                    0: {'title': 'Tidak', 'class': ' bg-gray'},
                    1: {'title': 'Ya', 'class': ' bg-primary'},
                };
                if (typeof status[full['potong_cuti']] === 'undefined') {
                    return data;
                }
                return '<span class="badge '+ status[full['potong_cuti']].class +'">'+ status[full['potong_cuti']].title +'</span>';
            }
        },
        {
            targets: -2,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {
                
                var status = {
                    0: {'title': 'Non-Aktif', 'class': ' bg-danger'},
                    1: {'title': 'Aktif', 'class': ' bg-success'},
                };
                if (typeof status[full['status']] === 'undefined') {
                    return data;
                }
                return '<span class="badge '+ status[full['status']].class +'">'+ status[full['status']].title +'</span>';
            }
        },
    ] 
});

$('#tab-cuti').click(function() {
    $("#dt-table-cuti").DataTable().ajax.reload(null, false);
});

function tambah_cuti() {
    $('#modalFormCuti').modal('show');
    $('#form-data-cuti')[0].reset();
    $('#kode_cuti').val('');
    $('#potong_gaji_cuti').val(0).change();
    $('#potong_cuti').val(0).change();
}

$('#form-data-cuti').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit-cuti');

    $.ajax({
        url : "/presensi/master/store-cuti",  
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
                $("#dt-table-cuti").DataTable().ajax.reload(null, false);
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

function edit_cuti(kode) {
    $.ajax({
        url: '/presensi/master/edit-cuti/'+kode,
        dataType: 'JSON',
        success: function(response) {
            if(response.status) {
                $('#modalFormCuti').modal('show');
                let data = response.data;
                $('#kode_cuti').val(kode);
                $('#nama_cuti').val(data.nama);
                $('#keterangan_cuti').val(data.keterangan);
                $('#jumlah_hari_cuti').val(data.jumlah_hari);
                $('#potong_gaji_cuti').val(data.potong_gaji).change();
                $('#potong_cuti').val(data.potong_cuti).change();
            }
        },
        error: function(error) {
            console.log(error)
            NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
        }
    })
}

function hapus_cuti(kode) {
    Swal.fire({
        title: 'Apakah anda yakin akan menghapus data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus data.'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/presensi/master/delete-cuti/'+kode,
                dataType: 'JSON',
                success: function(response) {
                    if(response.status){
                        $("#dt-table-cuti").DataTable().ajax.reload(null, false);
                        NioApp.Toast(response.message, 'success', {position: 'top-right'});
                    }else{
                        NioApp.Toast(response.message, 'warning', {position: 'top-right'});
                    }
                },
                error: function(error) {
                    console.log(error)
                    NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
                }
            })
        }
    });
}
// END: Cuti

// Ijin
var tableIjin = NioApp.DataTable('#dt-table-ijin', {
    serverSide: true,
    processing: true,
    responsive: false,
    scrollX: true,
    searchDelay: 500,
    ajax: {
        url: '/presensi/master/datatable-ijin',
        type: 'POST',
        data: function(d) {
            d._token = token;
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'nama'},
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
                    0: {'title': 'Non-Aktif', 'class': ' bg-danger'},
                    1: {'title': 'Aktif', 'class': ' bg-success'},
                };
                if (typeof status[full['status']] === 'undefined') {
                    return data;
                }
                return '<span class="badge '+ status[full['status']].class +'">'+ status[full['status']].title +'</span>';
            }
        },
    ] 
});

$('#tab-ijin').click(function() {
    $("#dt-table-ijin").DataTable().ajax.reload(null, false);
});

function tambah_ijin() {
    $('#modalFormIjin').modal('show');
    $('#form-data-ijin')[0].reset();
    $('#kode_ijin').val('');
}

$('#form-data-ijin').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit-ijin');

    $.ajax({
        url : "/presensi/master/store-ijin",  
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
                $('#form-data-ijin')[0].reset();
                $('#modalFormIjin').modal('hide');
                $("#dt-table-ijin").DataTable().ajax.reload(null, false);
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

function edit_ijin(kode) {
    $.ajax({
        url: '/presensi/master/edit-ijin/'+kode,
        dataType: 'JSON',
        success: function(response) {
            if(response.status) {
                $('#modalFormIjin').modal('show');
                let data = response.data;
                $('#kode_ijin').val(kode);
                $('#nama_ijin').val(data.nama);
            }
        },
        error: function(error) {
            console.log(error)
            NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
        }
    })
}

function hapus_ijin(kode) {
    Swal.fire({
        title: 'Apakah anda yakin akan menghapus data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus data.'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/presensi/master/delete-ijin/'+kode,
                dataType: 'JSON',
                success: function(response) {
                    if(response.status){
                        $("#dt-table-ijin").DataTable().ajax.reload(null, false);
                        NioApp.Toast(response.message, 'success', {position: 'top-right'});
                    }else{
                        NioApp.Toast(response.message, 'warning', {position: 'top-right'});
                    }
                },
                error: function(error) {
                    console.log(error)
                    NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
                }
            })
        }
    });
}
// END: Ijin