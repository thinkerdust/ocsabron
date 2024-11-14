$('.timepicker').datetimepicker({
    format: 'HH:mm:ss',
    icons: {
        up: "fas fa-chevron-up",    // Replace with your desired up icon class
        down: "fas fa-chevron-down" // Replace with your desired down icon class
    }
})

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
        url: '/presensi/lembur/datatable',
        type: 'POST',
        data: function(d) {
            d._token = token;
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.unit = $('#filter_unit').val();
            d.divisi = $('#filter_divisi').val();
            d.tipe_lembur = $('#filter_tipe_lembur').val();
            d.status = $('#filter_status').val();
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'kode'},
        {data: 'pin', name: 'k.pin'},
        {data: 'nama_karyawan', name: 'k.nama'},
        {data: 'nama_unit', name: 'u.nama'},
        {data: 'nama_tipe_lembur', name: 'mi.nama'},
        {data: 'tanggal_lembur', name: 'i.tanggal_awal'},
        {data: 'durasi', orderable: false, searchable: false},
        {data: 'keterangan', orderable: false, searchable: false},
        {data: 'status'},
        {data: 'action', orderable: false, searchable: false},
    ],
    columnDefs: [
        {
            targets: 1,
            orderable: false, 
            searchable: false,
            render: (data, type, row, meta) => {
                return `<div class="custom-control custom-control-sm custom-checkbox notext">
                        <input type="checkbox" name="row_check[]" class="custom-control-input row-check" id="check_${data}" data-id="${data}" value="${data}">
                        <label class="custom-control-label" for="check_${data}"></label>
                    </div>`; 
            }
        },
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
    ] 
});

$('#btn-filter').click(function() {
    $("#dt-table").DataTable().ajax.reload();
    $('#check_all').prop('checked', false);
})

// Handle click on "Check All" control
$('#check_all').click(function() {
    let rows = $('#dt-table').DataTable().rows({ 'search': 'applied' }).nodes();

    if ($('#check_all').is(':checked')) {
        $('input[name="row_check[]"]', rows).prop("checked", true);
    }  else {
        $('input[name="row_check[]"]', rows).prop("checked", false);
    }
});

// Handle click on individual checkboxes to update "Check All" control
$('#dt-table tbody').on('change', '.row-check', function() {
    let totalCheckboxes = $('.row-check').length;
    let checkedCheckboxes = $('.row-check:checked').length;
    $('#check_all').prop('checked', totalCheckboxes === checkedCheckboxes);
});

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

function tambah() {
    $('#modalForm').modal('show');
    $('#form-data')[0].reset();
    $('#staff').val('').change();
    $('#tipe_lembur').val('').change();
}

$('#form-data').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit');

    $.ajax({
        url : "/presensi/lembur/store",  
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
                url : "/presensi/lembur/approval",  
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