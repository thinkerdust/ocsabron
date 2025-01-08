var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    scrollY: '500px',
    ajax: {
        url: '/history/datatable',
        type: 'POST',
        data: function(d) {
            d._token        = token;
            d.start_date    = $('#start_date').val();
            d.end_date      = $('#end_date').val();
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
        {data: 'tanggal'},
        {data: 'customer'},
        {data: 'nama'},
        {data: 'deadline'},
        {data: 'jenis_produk'},
        {data: 'jenis_kertas'},
        {data: 'ukuran'},
        {data: 'jumlah', className: 'text-end', render: $.fn.dataTable.render.number( ',', '.', 0)},
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
                    0: {'title': 'DELETE', 'class': ' bg-danger'},
                    2: {'title': 'DONE', 'class': ' bg-success'}
                };
                if (typeof status[full['status']] === 'undefined') {
                    return data;
                }
                return '<span class="badge badge-dot '+ status[full['status']].class +'">'+ status[full['status']].title +'</span>';
            }
        },
    ],
    buttons: ['copy', 'excel', 'csv', 'pdf']
});

$('#btn-filter').click(function() {
    $("#dt-table").DataTable().ajax.reload();
})

const thousandView = (number = 0) => {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function detail(id) {

    // open modal
    $('#modalDetail').modal('show');

    $.ajax({
        url: '/history/detail/'+id,
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
        url: '/history/detail/datatable',
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
                    0: {'title': 'DELETE', 'class': ' bg-danger'},
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