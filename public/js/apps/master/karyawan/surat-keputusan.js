$(document).ready(function() {
    let nip = $('#nip').val();
    let kode = $('#kode').val();
    if(nip) {
        edit(nip, kode);
    }
})

$('#perusahaan').select2({
    placeholder: 'Pilih Perusahaan',
    allowClear: true,
    ajax: {
        url: '/data-perusahaan',
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

$('#unit').select2({
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

$('#unit').change(function() {
    $("#divisi").empty().append(`<option value="">Pilih Divisi</option>`).val('').trigger('change');
    let kode = $(this).val();
    if(kode) {
        $('#divisi').select2({
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

$('#divisi').change(function() {
    $("#jabatan").empty().append(`<option value="">Pilih Jabatan</option>`).val('').trigger('change');
    let kode = $(this).val();
    if(kode) {
        $('#jabatan').select2({
            placeholder: 'Pilih Jabatan',
            allowClear: true,
            ajax: {
                url: '/data-jabatan?kode_divisi='+kode,
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

const thousandView = (number = 0) => {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$('.format-currency').on('keyup', (evt) => {
    keyUpThousandView(evt)
})

const keyUpThousandView = (evt) => {
    let currentValue = (evt.currentTarget.value != '') ? evt.currentTarget.value.replaceAll('.','') : '0';
    let iNumber = parseInt(currentValue);
    let result = isNaN(iNumber) == false ? thousandView(iNumber) : '0';
    evt.currentTarget.value = result;
}


$('#form-data').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit');

    $.ajax({
        url : "/master/karyawan/store-surat-keputusan",  
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
                $("#dt-table").DataTable().ajax.reload(null, false);
                reset_form();
                NioApp.Toast(response.message, 'success', {position: 'top-right'});
            }else{
                NioApp.Toast(response.message, 'warning', {position: 'top-right'});
            }
            btn.attr('disabled', false);
            btn.html(`<em class="icon ni ni-save"></em> <span>Save</span>`);
        },
        error: function(error) {
            console.log(error)
            btn.attr('disabled', false);
            btn.html(`<em class="icon ni ni-save"></em> <span>Save</span>`);
            NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
        }
    });
});

var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: false,
    scrollX: true,
    searchDelay: 500,
    ajax: {
        url: '/master/karyawan/datatable-surat-keputusan',
        type: 'POST',
        data: function(d) {
            d._token = token;
            d.nip = $('#nip').val();
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'nomor_sk', name: 'sk.nomor_sk'},
        {data: 'tanggal_sk', name: 'sk.tanggal_sk'},
        {data: 'jenis_sk'},
        {data: 'nama_perusahaan', name: 'p.nama'},
        {data: 'nama_unit', name: 'u.nama'},
        {data: 'nama_divisi', name: 'd.nama'},
        {data: 'nama_jabatan', name: 'j.nama'},
        {data: 'upah_pokok', name: 'sk.upah_pokok', className: 'text-end', render: $.fn.dataTable.render.number( ',', '.', 0, 'Rp. ' )},
        {data: 'tunjangan_jabatan', name: 'sk.tunjangan_jabatan', className: 'text-end', render: $.fn.dataTable.render.number( ',', '.', 0, 'Rp. ' )},
        {data: 'status_karyawan'},
        {data: 'status'},
        {data: 'action', orderable: false, searchable: false},
    ],
    columnDefs: [
        {
            targets: 3,
            name: 'sk.jenis_sk',
            orderable: false,
            render: function(data, type, full, meta) {

                var status = {
                    1: {'title': 'PENERIMAAN KARYAWAN BARU ', 'class': ' bg-blue'},
                    2: {'title': 'PENGANGKATAN TETAP', 'class': ' bg-success'},
                    3: {'title': 'MUTASI', 'class': ' bg-pink'}
                };
                if (typeof status[full['jenis_sk']] === 'undefined') {
                    return data;
                }
                return '<span class="badge '+ status[full['jenis_sk']].class +'">'+ status[full['jenis_sk']].title +'</span>';
            }
        },
        {
            targets: 10,
            name: 'sk.status_karyawan',
            orderable: false,
            render: function(data, type, full, meta) {

                var status = {
                    'KONTRAK': {'title': 'KONTRAK ', 'class': ' bg-blue'},
                    'TETAP': {'title': 'TETAP', 'class': ' bg-success'},
                    'HARIAN': {'title': 'HARIAN', 'class': ' bg-pink'}
                };
                if (typeof status[full['status_karyawan']] === 'undefined') {
                    return data;
                }
                return '<span class="badge '+ status[full['status_karyawan']].class +'">'+ status[full['status_karyawan']].title +'</span>';
            }
        },
        {
            targets: -2,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {

                var status = {
                    0: {'title': 'TIDAK AKTIF', 'class': ' bg-danger'},
                    1: {'title': 'AKTIF', 'class': ' bg-success'},
                };
                if (typeof status[full['status']] === 'undefined') {
                    return data;
                }
                return '<span class="badge '+ status[full['status']].class +'">'+ status[full['status']].title +'</span>';
            }
        },
    ] 
});

function reset_form() {

    $('#form-data')[0].reset();
    $('#kode').val('');
    $('#unit').val('').change();
    $('#perusahaan').val('').change();
    $('#jenis_sk').val('').change();
    $('#status_karyawan').val('').change();

    let nip = $('#nip').val();
    let kode = $('#kode').val();
    edit(nip, kode);
    $('#jenis_sk').val(1).change();
    $('#status_karyawan').val('KONTRAK').change();

    let form = document.getElementById("form-data");
    form.scrollIntoView({ behavior: 'smooth' });
}

function edit(nip, kode) {
    $.ajax({
        url: '/master/karyawan/edit-surat-keputusan?nip='+nip+'&kode='+kode,
        dataType: 'json',
        success: function(response) {
            let data = response.data;
            $('#nama').val(data.nama);
            if(kode) {
                $('#kode').val(kode);
                $("#perusahaan").empty().append(`<option value="${data.kode_perusahaan}">${data.nama_perusahaan}</option>`).val(data.kode_perusahaan).trigger('change');
                $("#unit").empty().append(`<option value="${data.kode_unit}">${data.nama_unit}</option>`).val(data.kode_unit).trigger('change');
                $("#divisi").empty().append(`<option value="${data.kode_divisi}">${data.nama_divisi}</option>`).val(data.kode_divisi).trigger('change');
                $("#jabatan").empty().append(`<option value="${data.kode_jabatan}">${data.nama_jabatan}</option>`).val(data.kode_jabatan).trigger('change');
                
                $('#nomor_sk').val(data.nomor_sk);
                $('#tanggal_sk').val(data.tanggal_sk);
                $('#jenis_sk').val(data.jenis_sk).change();
                $('#status_karyawan').val(data.status_karyawan).change();
                $('#upah_pokok').val(thousandView(data.upah_pokok));
                $('#tunjangan_jabatan').val(thousandView(data.tunjangan_jabatan));

                let form = document.getElementById("form-data");
                form.scrollIntoView({ behavior: 'smooth' });
            }
        },
        error: function(error) {
            console.log(error)
            NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
        }
    });
}