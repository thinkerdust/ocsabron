var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    scrollY: '500px',
    ajax: {
        url: '/desain/datatable',
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
        {data: 'deadline', name: 'o.deadline'},
        {data: 'jenis_produk', name: 'o.jenis_produk'},
        {data: 'ukuran', name: 'o.ukuran'},
        {data: 'jumlah', name: 'o.jumlah', className: 'text-end', render: $.fn.dataTable.render.number( ',', '.', 0)},
        {data: 'progress', name: 'd.nama', className: 'fw-bold', orderable: false, searchable: false},
        {data: 'status'},
        {data: 'action', orderable: false, searchable: false},
    ],
    columnDefs: [
        {
            className: "nk-tb-col",
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
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

function detail(id) {

    // open modal
    $('#modalDetail').modal('show');

    $.ajax({
        url: '/desain/detail/'+id,
        dataType: 'json',
        success: function(response) {
            let order = response.data.order;
            console.log(order)
            if(response.status) {
                $('#nama').val(order.nama);
                $('#customer').val(order.customer);
                $('#tanggal').val(order.tanggal);
                $('#deadline').val(order.deadline);
                $('#jenis_produk').val(order.jenis_produk);
                $('#tambahan').val(order.tambahan);
                $('#ukuran').val(order.ukuran);
                $('#jumlah').val(order.jumlah);
                $('#jenis_kertas').val(order.jenis_kertas);
                $('#finishing_satu').val(order.finishing_satu);
                $('#finishing_dua').val(order.finishing_dua);
                $('#pengambilan').val(order.pengambilan).change();
                $('#order_by').val(order.order_by).change();
                $('#keterangan').val(order.keterangan);
            }

            let order_detail = response.data.detail;
            if(order_detail) {
                // loop add li to id = order_detail
                let html = '';
                order_detail.forEach(function(data) {
                    html += `
                        <div class="col-md-3">
                            <div class="custom-control custom-control-md custom-switch">
                                <input type="checkbox" class="custom-control-input" checked>
                                <label class="custom-control-label">${data.nama_divisi}</label>
                            </div>
                        </div>
                    `
                });
                $('#order_detail').html(html);
            }
        },
        error: function(error) {
            console.log(error)
            NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
        }
    })

}

function approve(id) {

    $('#modalApprove').modal('show');
    $('#uid_approve').val(id);

}

$('#form-approve').submit(function(e) {

    e.preventDefault();
    formData = new FormData($(this)[0]);

    $.ajax({
        url: '/desain/approve',
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

}

$('#form-pending').submit(function(e) {

    e.preventDefault();

    $.ajax({
        url: '/desain/pending',
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