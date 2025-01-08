var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    scrollY: '500px',
    ajax: {
        url: '/finishing-dua/datatable',
        type: 'POST',
        data: function(d) {
            d._token        = token;
            d.start_date    = $('#start_date').val();
            d.end_date      = $('#end_date').val();
            d.status        = $('#filter_status').val();
        },
        error: function (xhr) {
            if (xhr.status === 401) { // Unauthorized error
                NioApp.Toast('Your session has expired. Redirecting to login...', 'error', {position: 'top-right'});
                window.location.href = "/login"; 
            } else {
                NioApp.Toast('An error occurred while loading data. Please try again.', 'error', {position: 'top-right'});
            }
        }
    },
    order: [1, 'ASC'],
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'tanggal', name: 'o.tanggal'},
        {data: 'customer', name: 'o.customer'},
        {data: 'nama', name: 'o.nama'},
        {data: 'deadline', name: 'o.deadline'},
        {data: 'jenis_produk', name: 'o.jenis_produk'},
        {data: 'jenis_kertas', name: 'o.jenis_kertas'},
        {data: 'ukuran', name: 'o.ukuran'},
        {data: 'jumlah', name: 'o.jumlah', className: 'text-end', render: $.fn.dataTable.render.number( ',', '.', 0)},
        {data: 'progress', name: 'd.nama', className: 'fw-bold', orderable: false, searchable: false},
        {data: 'status'},
        {data: 'action', orderable: false, searchable: false},
    ],
    columnDefs: [
        {
            className: "nk-tb-col",
            targets: "_all"
        },
        {
            targets: -2,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {

                var status = {
                    1: {'title': 'ON PROGRESS', 'class': ' bg-info'},
                    2: {'title': 'DONE', 'class': ' bg-success'},
                    3: {'title': 'PENDING', 'class': ' bg-warning'},
                };
                if (typeof status[full['status']] === 'undefined') {
                    return data;
                }
                return '<span class="badge badge-dot '+ status[full['status']].class +'">'+ status[full['status']].title +'</span>';
            }
        },
    ]
});

$('#btn-filter').click(function() {
    $("#dt-table").DataTable().ajax.reload();
})

$('.select2-js').select2({
    minimumResultsForSearch: Infinity
});

const thousandView = (number = 0) => {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function detail(id) {

    // open modal
    $('#modalDetail').modal('show');

    $.ajax({
        url: '/finishing-dua/detail/'+id,
        dataType: 'json',
        success: function(response) {
            let data = response.data.order;
            if(response.status) {
                $('#nama').val(data.nama);
                $('#customer').val(data.customer);
                $('#tanggal').val(data.tanggal);
                $('#deadline').val(data.deadline);
                $('#jenis_produk').val(data.jenis_produk);
                $('#tambahan').val(data.tambahan);
                $('#ukuran').val(data.ukuran);
                $('#jumlah').val(thousandView(data.jumlah));
                $('#jenis_kertas').val(data.jenis_kertas);
                $('#finishing_satu').val(data.finishing_satu);
                $('#finishing_dua').val(data.finishing_dua);
                $('#pengambilan').val(data.pengambilan).change();
                $('#order_by').val(data.order_by).change();
                $('#keterangan').val(data.keterangan);
                $('#hasil_jadi').val(thousandView(data.hasil_jadi));
                $('#jumlah_koli').val(thousandView(data.jumlah_koli));
                $('#hasil_jadi_tambahan').val(thousandView(data.hasil_jadi_tambahan));
                $('#jumlah_koli_tambahan').val(thousandView(data.jumlah_koli_tambahan));
                $('#nomor_nota').val(data.nomor_nota);
                $('#nomor_resi').val(data.nomor_resi);
                $('#rusak_mesin').val(data.rusak_mesin);
                $('#rusak_cetakan').val(data.rusak_cetakan);
                $('#rusak_mesin_forming').val(data.rusak_mesin_forming);
                $('#rusak_cetakan_forming').val(data.rusak_cetakan_forming);
                $('#tanggal_approve').val(data.tanggal_approve);
            }

            $('#uid_order').val(id);
            $("#dt-table-detail").DataTable().ajax.reload();
        },
        error: function(error) {
            console.log(error)
            NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
        }
    })

}

var table = NioApp.DataTable('#dt-table-detail', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    ajax: {
        url: '/finishing-dua/detail/datatable',
        type: 'POST',
        data: function(d) {
            d._token    = token;
            d.uid       = $('#uid_order').val();
        },
        error: function (xhr) {
            if (xhr.status === 401) { // Unauthorized error
                NioApp.Toast('Your session has expired. Redirecting to login...', 'error', {position: 'top-right'});
                window.location.href = "/login"; 
            } else {
                NioApp.Toast('An error occurred while loading data. Please try again.', 'error', {position: 'top-right'});
            }
        }
    },
    order: [1, 'ASC'],
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'nama_divisi', name: 'd.nama'},
        {data: 'keterangan', name: 'od.keterangan'},
        {data: 'approve_at', name: 'od.approve_at'},
        {data: 'approve_by', name: 'od.approve_by'},
        {data: 'status'}
    ],
    columnDefs: [
        {
            className: "nk-tb-col",
            targets: "_all"
        },
        {
            targets: -1,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {

                var status = {
                    1: {'title': 'ON PROGRESS', 'class': ' bg-info'},
                    2: {'title': 'DONE', 'class': ' bg-success'},
                    3: {'title': 'PENDING', 'class': ' bg-warning'},
                };
                if (typeof status[full['status']] === 'undefined') {
                    return data;
                }
                return '<span class="badge badge-dot '+ status[full['status']].class +'">'+ status[full['status']].title +'</span>';
            }
        },
    ]
});

function hapus(id) {
    Swal.fire({
        title: 'Apakah anda yakin akan hapus data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, saya yakin!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/finishing-dua/delete/'+id,
                dataType: 'JSON',
                success: function(response) {
                    if(response.status){
                        $("#dt-table").DataTable().ajax.reload(null, false);
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

function cancel(id) {
    Swal.fire({
        title: 'Apakah anda yakin akan back process job?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, saya yakin!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/finishing-dua/cancel/'+id,
                dataType: 'JSON',
                success: function(response) {
                    if(response.status){
                        $("#dt-table").DataTable().ajax.reload(null, false);
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

function approve(id) {
    $('#modalApprove').modal('show');
    $('#uid_approve').val(id);
    $('#keterangan_approve').val('');
}

$('#form-approve').submit(function(e) {

    e.preventDefault();
    formData = new FormData($(this)[0]);

    $.ajax({
        url: '/finishing-dua/approve',
        data : formData,
        type : "POST",
        dataType : "JSON",
        cache:false,
        async : true,
        contentType: false,
        processData: false,
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
                $('#modalApprove').modal('hide');
                $("#dt-table").DataTable().ajax.reload(null, false);
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

})

function pending(id) {
    $('#modalPending').modal('show');
    $('#uid_pending').val(id);
    $('#keterangan_pending').val('');
}

$('#form-pending').submit(function(e) {
    e.preventDefault();

    $.ajax({
        url: '/finishing-dua/pending',
        dataType: 'JSON',
        type: 'POST',
        data: $(this).serialize(),
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
                $('#modalPending').modal('hide');
                $("#dt-table").DataTable().ajax.reload(null, false);
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
})

var table = NioApp.DataTable('#dt-table-incoming', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    ajax: {
        url: '/finishing-dua/datatable-incoming',
        type: 'POST',
        data: function(d) {
            d._token        = token;
        },
        error: function (xhr) {
            if (xhr.status === 401) { // Unauthorized error
                NioApp.Toast('Your session has expired. Redirecting to login...', 'error', {position: 'top-right'});
                window.location.href = "/login"; 
            } else {
                NioApp.Toast('An error occurred while loading data. Please try again.', 'error', {position: 'top-right'});
            }
        }
    },
    order: [1, 'ASC'],
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'tanggal', name: 'o.tanggal'},
        {data: 'customer', name: 'o.customer'},
        {data: 'nama', name: 'o.nama'},
        {data: 'deadline', name: 'o.deadline'},
        {data: 'jenis_produk', name: 'o.jenis_produk'},
        {data: 'jenis_kertas', name: 'o.jenis_kertas'},
        {data: 'ukuran', name: 'o.ukuran'},
        {data: 'jumlah', name: 'o.jumlah', className: 'text-end', render: $.fn.dataTable.render.number( ',', '.', 0)},
        {data: 'progress', name: 'd.nama', className: 'fw-bold', orderable: false, searchable: false},
    ],
    columnDefs: [
        {
            className: "nk-tb-col",
            targets: "_all"
        }
    ]
});