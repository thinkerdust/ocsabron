function formatDate(inputDate) {
    // Split the input date by '/'
    var parts = inputDate.split('/');
    
    // Ensure the date has exactly three parts
    if (parts.length !== 3) {
        throw new Error('Invalid date format');
    }

    // Extract day, month, and year
    var day = parts[0];
    var month = parts[1];
    var year = parts[2];
    
    // Create a new date object with the provided year, month, and day
    // Months are zero-based in JavaScript Date (0 = January, 11 = December)
    var date = new Date(year, month - 1, day);
    
    // Format the date to 'Y-m-d'
    var formattedDate = date.getFullYear() + '-' +
                        String(date.getMonth() + 1).padStart(2, '0') + '-' +
                        String(date.getDate()).padStart(2, '0');
    
    return formattedDate;
}

// Setting Jadwal
var tableSetting = NioApp.DataTable('#dt-table-setting', {
    serverSide: true,
    processing: true,
    responsive: false,
    scrollX: true,
    searchDelay: 500,
    ajax: {
        url: '/presensi/jadwal/datatable-setting',
        type: 'POST',
        data: function(d) {
            d._token = token;
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'nip', name: 'jk.nip'},
        {data: 'nama_karyawan', name: 'k.nama'},
        {data: 'tanggal', name: 'jk.tanggal'},
        {data: 'nama_shift', name: 's.nama'},
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

$('#tab-setting').click(function() {
    $("#dt-table-setting").DataTable().ajax.reload(null, false);
});

function tambah_setting() {
    $('#modalFormSetting').modal('show');
    $('#form-data-setting')[0].reset();
    $('#setting_jadwal_import').next('label').html('Choose file');
}

$('#btn-export-setting-jadwal').click(function() {
    let tanggal_awal = $('#tanggal_awal_setting_jadwal').val();
    let tanggal_akhir = $('#tanggal_akhir_setting_jadwal').val();

    if(tanggal_awal && tanggal_akhir) {
        tanggal_awal = formatDate(tanggal_awal);
        tanggal_akhir = formatDate(tanggal_akhir);

        if(tanggal_akhir >= tanggal_awal) {
            location.href = '/presensi/jadwal/export-setting?tanggal_awal='+tanggal_awal+'&tanggal_akhir='+tanggal_akhir;
        }else{
            NioApp.Toast('Field Tanggal Akhir harus lebih besar atau sama dengan dari Tanggal Awal.', 'warning', {position: 'top-right'});
        }
    }else{
        NioApp.Toast('Field Tanggal Awal & Tanggal Akhir harus di isi.', 'warning', {position: 'top-right'});
    }
})

$('#form-data-setting').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit-setting');

    $.ajax({
        url : "/presensi/jadwal/import-setting",  
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
                $('#form-data-setting')[0].reset();
                $('#modalFormSetting').modal('hide');
                $("#dt-table-setting").DataTable().ajax.reload(null, false);
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

// END: Setting Jadwal

// Data Jadwal 
$('.select2').select2();

function loadTabelRekap() {
    let bulan = $('#filterBulanRekap').val();
    let tahun = $('#filterTahunRekap').val();   
    $.ajax({
        url: '/presensi/jadwal/report',
        type: 'POST',
        data: { _token: token, bulan: bulan, tahun: tahun},
        beforeSend: function() {
            $('#loadTabelRekap').html(`<div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>`);
        },
        success: function(response) {
            $('#loadTabelRekap').html(response);
        },
        error: function(error) {
            console.log(error)
            NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
        }
    })
}

loadTabelRekap();

// END: Data Jadwal

// Tukar Jadwal

var tableSetting = NioApp.DataTable('#dt-table-tukar', {
    serverSide: true,
    processing: true,
    responsive: false,
    scrollX: true,
    searchDelay: 500,
    ajax: {
        url: '/presensi/jadwal/datatable-tukar',
        type: 'POST',
        data: function(d) {
            d._token = token;
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'tanggal', name: 'tj.tanggal'},
        {data: 'nama_asal', name: 'k1.nama'},
        {data: 'shift_asal', orderable: false, searchable: false},
        {data: 'nama_tujuan', name: 'k2.nama'},
        {data: 'shift_asal', orderable: false, searchable: false},
        {data: 'status_approval'},
        {data: 'action', orderable: false, searchable: false},
    ],
    columnDefs: [
        {
            targets: -2,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {
                
                var status = {
                    0: {'title': 'Ditolak', 'class': ' bg-danger'},
                    1: {'title': 'Menunggu Persetujuan', 'class': ' bg-info'},
                    2: {'title': 'Disetujui', 'class': ' bg-success'},
                };
                if (typeof status[full['status_approval']] === 'undefined') {
                    return data;
                }
                return '<span class="badge '+ status[full['status_approval']].class +'">'+ status[full['status_approval']].title +'</span>';
            }
        },
    ] 
});

$('#tab-tukar').click(function() {
    $("#dt-table-tukar").DataTable().ajax.reload(null, false);
});

function approval(kode) {
    $('#modalFormApprovalTukar').modal('show');
    $('#kode_approval_tukar').val(kode);
    $('input[name="approval_tukar_jadwal"]').prop('checked', false);
    $('#alasan_reject_tukar_jadwal').val('');
}

$('#form-data-approval-tukar').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit-approval-tukar');

    $.ajax({
        url : "/presensi/jadwal/approval-tukar",  
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
                $('#form-data-approval-tukar')[0].reset();
                $('#modalFormApprovalTukar').modal('hide');
                $("#dt-table-tukar").DataTable().ajax.reload(null, false);
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
// END: Tukar Jadwal