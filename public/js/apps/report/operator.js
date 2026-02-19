var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    scrollY: '500px',
    ajax: {
        url: '/report/operator/datatable',
        type: 'POST',
        data: function(d) {
            d._token        = token;
            d.start_date    = $('#start_date').val();
            d.end_date      = $('#end_date').val();
            d.order_by      = $('#order_by').val();
        },
        error: function (xhr) {
            if (xhr.status === 419) { 
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
        {data: 'tambahan'},
        {data: 'hasil_jadi', className: 'text-end', render: $.fn.dataTable.render.number( ',', '.', 0)},
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

$('.select2-js').select2({
    minimumResultsForSearch: Infinity
});