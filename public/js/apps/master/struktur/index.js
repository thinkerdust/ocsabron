// UNIT 
var tableUnit = NioApp.DataTable('#dt-table-unit', {
    serverSide: true,
    processing: true,
    responsive: false,
    scrollX: true,
    searchDelay: 500,
    ajax: {
        url: '/master/struktur/datatable-unit',
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

$('#tab-unit').click(function() {
    $("#dt-table-unit").DataTable().ajax.reload(null, false);
});

function hapus_unit(kode) {
    Swal.fire({
        title: 'Apakah anda yakin akan menghapus data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus data.'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/master/struktur/delete-unit/'+kode,
                dataType: 'JSON',
                success: function(response) {
                    if(response.status){
                        $("#dt-table-unit").DataTable().ajax.reload(null, false);
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

function tambah_unit() {
    $('#modalFormUnit').modal('show');
    $('#form-data-unit')[0].reset();
    $('#kode_unit').val('');
}

$('#form-data-unit').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit-unit');

    $.ajax({
        url : "/master/struktur/store-unit",  
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
                $('#form-data-unit')[0].reset();
                $('#modalFormUnit').modal('hide');
                $("#dt-table-unit").DataTable().ajax.reload(null, false);
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

function edit_unit(kode) {
    $.ajax({
        url: '/master/struktur/edit-unit/'+kode,
        dataType: 'JSON',
        success: function(response) {
            if(response.status) {
                $('#modalFormUnit').modal('show');
                let data = response.data;
                $('#kode_unit').val(kode);
                $('#nama_unit').val(data.nama);
            }
        },
        error: function(error) {
            console.log(error)
            NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
        }
    })
}
// END: UNIT

// DIVISI
var tableDivisi = NioApp.DataTable('#dt-table-divisi', {
    serverSide: true,
    processing: true,
    responsive: false,
    scrollX: true,
    searchDelay: 500,
    ajax: {
        url: '/master/struktur/datatable-divisi',
        type: 'POST',
        data: function(d) {
            d._token = token;
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'nama_unit', name: 'u.nama'},
        {data: 'nama', name: 'd.nama'},
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

$('#tab-divisi').click(function() {
    $("#dt-table-divisi").DataTable().ajax.reload(null, false);
});

$('#unit_divisi').select2({
    placeholder: 'Pilih Unit',
    allowClear: true,
    dropdownParent: $('#modalFormDivisi'),
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

function hapus_divisi(kode) {
    Swal.fire({
        title: 'Apakah anda yakin akan menghapus data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus data.'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/master/struktur/delete-divisi/'+kode,
                dataType: 'JSON',
                success: function(response) {
                    if(response.status){
                        $("#dt-table-divisi").DataTable().ajax.reload(null, false);
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

function tambah_divisi() {
    $('#modalFormDivisi').modal('show');
    $('#form-data-divisi')[0].reset();
    $('#kode_divisi').val('');
    $("#unit_divisi").val('').change();
}

$('#form-data-divisi').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit-divisi');

    $.ajax({
        url : "/master/struktur/store-divisi",  
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
                $('#form-data-divisi')[0].reset();
                $('#modalFormDivisi').modal('hide');
                $("#dt-table-divisi").DataTable().ajax.reload(null, false);
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

function edit_divisi(kode) {
    $.ajax({
        url: '/master/struktur/edit-divisi/'+kode,
        dataType: 'JSON',
        success: function(response) {
            if(response.status) {
                $('#modalFormDivisi').modal('show');
                let data = response.data;
                $('#kode_divisi').val(kode);
                $('#nama_divisi').val(data.nama);
                $("#unit_divisi").empty().append(`<option value="${data.kode_unit}">${data.nama_unit}</option>`).val(data.kode_unit).trigger('change');
            }
        },
        error: function(error) {
            console.log(error)
            NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
        }
    })
}
// END: DIVISI

// JABATAN
var tableJabatan = NioApp.DataTable('#dt-table-jabatan', {
    serverSide: true,
    processing: true,
    responsive: false,
    scrollX: true,
    searchDelay: 500,
    ajax: {
        url: '/master/struktur/datatable-jabatan',
        type: 'POST',
        data: function(d) {
            d._token = token;
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'nama_unit', name: 'u.nama'},
        {data: 'nama_divisi', name: 'd.nama'},
        {data: 'nama', name: 'j.nama'},
        {data: 'atasan', name: 'k.nama', orderable: false},
        {data: 'level', name: 'j.level', orderable: false, searchable: false},
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

$('#tab-jabatan').click(function() {
    $("#dt-table-jabatan").DataTable().ajax.reload(null, false);
});

$('#unit_jabatan').select2({
    placeholder: 'Pilih Unit',
    allowClear: true,
    dropdownParent: $('#modalFormJabatan'),
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

$('#unit_jabatan').change(function() {
    $("#divisi_jabatan").empty().append(`<option value="">Pilih Divisi</option>`).val('').trigger('change');
    let kode = $(this).val();
    if(kode) {
        $('#divisi_jabatan').select2({
            placeholder: 'Pilih Divisi',
            allowClear: true,
            dropdownParent: $('#modalFormJabatan'),
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

$('#level_jabatan').change(function() {
    $("#atasan_jabatan").empty().append(`<option value="">Pilih Atasan</option>`).val('').trigger('change');
    let level = $(this).val();
    if(level) {
        $('#atasan_jabatan').select2({
            placeholder: 'Pilih Atasan',
            allowClear: true,
            dropdownParent: $('#modalFormJabatan'),
            ajax: {
                url: '/data-atasan?level='+level,
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

function hapus_jabatan(kode) {
    Swal.fire({
        title: 'Apakah anda yakin akan menghapus data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus data.'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/master/struktur/delete-jabatan/'+kode,
                dataType: 'JSON',
                success: function(response) {
                    if(response.status){
                        $("#dt-table-jabatan").DataTable().ajax.reload(null, false);
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

function tambah_jabatan() {
    $('#modalFormJabatan').modal('show');
    $('#form-data-jabatan')[0].reset();
    $('#kode_jabatan').val('');
    $('#unit_jabatan').val('').change();
    $('#divisi_jabatan').val('').change();
    $('#level_jabatan').val(1).change();
}

$('#form-data-jabatan').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit-jabatan');

    $.ajax({
        url : "/master/struktur/store-jabatan",  
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
                $('#form-data-jabatan')[0].reset();
                $('#modalFormJabatan').modal('hide');
                $("#dt-table-jabatan").DataTable().ajax.reload(null, false);
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

function edit_jabatan(kode) {
    $.ajax({
        url: '/master/struktur/edit-jabatan/'+kode,
        dataType: 'JSON',
        success: function(response) {
            if(response.status) {
                $('#modalFormJabatan').modal('show');
                let data = response.data;
                $('#kode_jabatan').val(kode);
                $('#nama_jabatan').val(data.nama);
                $('#level_jabatan').val(data.level).change();
                $("#unit_jabatan").empty().append(`<option value="${data.kode_unit}">${data.nama_unit}</option>`).val(data.kode_unit).trigger('change');
                $("#divisi_jabatan").empty().append(`<option value="${data.kode_divisi}">${data.nama_divisi}</option>`).val(data.kode_divisi).trigger('change');
                $("#atasan_jabatan").empty().append(`<option value="${data.kode_atasan}">${data.nama_atasan}</option>`).val(data.kode_atasan).trigger('change');
            }
        },
        error: function(error) {
            console.log(error)
            NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
        }
    })
}
// END: JABATAN

// STRUKTUR ORGANISASI

let simple_chart_config = {};

simple_chart_config = {
    chart: {
        container: "#tree-simple",
        node: {
            HTMLclass: 'nodeExample1',
            drawLineThrough: true,
            collapsable: true,
            rootOrientation: 'south'
            // nodeAlign: 'bottom'
            // link: {
            //     target: '_self'
            // }
        },
        nodeAlign: 'center'
    },

    nodeStructure: {}
};

function get_data(level = '', atasan = '') {
    var result = '';
    $.ajax({
        url: "/master/struktur/get-data-organisasi",
        dataType: "json",
        type: "post",
        data: {
            _token: token,
            level: level,
            atasan: atasan
        },
        async: false,  
        success: function(data) {
            result = data;
        }
    });
    return result;
}


let level1 = get_data('1');

for (a = 0; a < level1.length; a++) {
    console.log(level1[a].atasan);
    let detail_level1 = {
        text: {
            name: level1[a].atasan,
            title: level1[a].nama_jabatan
        },
        connectors: {type:'step'},
        children: []
    }
    
    simple_chart_config.nodeStructure = detail_level1;

    // director, corporate
    let level2 = get_data('2', level1[a].kode);

    for (b = 0; b < level2.length; b++) {
        let detail_level2 = {
            text: {
                name: level2[b].atasan ? level2[b].atasan : '-',
                title: level2[b].nama_jabatan
            },
            connectors: {type:'step'},
            children: []
        }

        simple_chart_config.nodeStructure.children.push(detail_level2);

        // manager atau director di setiap perusahaan
        let level3 = get_data('3', level2[b].kode)

        for (c = 0; c < level3.length; c++) {
            let detail_level3 = {
                text: {
                    name: level3[c].atasan ? level3[c].atasan : '-',
                    title: level3[c].nama_jabatan
                },
                connectors: {type:'step'},
                children: []
            }
    
            simple_chart_config.nodeStructure.children[b].children.push(detail_level3);

            // divisi
            let level4 = get_data('4', level3[c].kode);
            
            for (d = 0; d < level4.length; d++) {
                let detail_level4 = {
                    text: {
                        name: level4[d].atasan ? level4[d].atasan : '-',
                        title: level4[d].nama_jabatan
                    },
                    connectors: {type:'step'},
                    children: []
                }
        
                simple_chart_config.nodeStructure.children[b].children[c].children.push(detail_level4);

                let level5 = get_data('5', level4[d].kode);

                for (e = 0; e < level5.length; e++) {
                    let detail_level5 = {
                        text: {
                            name: level5[e].atasan,
                            title: level5[e].nama_jabatan,
                        },
                        connectors: {type:'step'},
                        children: []
                    }

                    simple_chart_config.nodeStructure.children[b].children[c].children[d].children.push(detail_level5);

                }
            }
        }
    }
}


let tree_chart = new Treant(simple_chart_config);

// END: STRUKTUR ORGANISASI