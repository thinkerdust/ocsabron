var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    ajax: {
        url: '/presensi/scanlog/datatable',
        type: 'POST',
        data: function(d) {
            d._token = token;
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.unit = $('#filter_unit').val();
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'pin', name: 'k.pin'},
        {data: 'nama_karyawan', name: 'k.nama'},
        {data: 'nama_unit', name: 'u.nama'},
        {data: 'scan_date', name: 's.scan_date'},
        {data: 'dev_id', name: 's.dev_id', orderable: false, searchable: false}
    ],
    columnDefs: [] 
});

$('#btn-filter').click(function() {
    $("#dt-table").DataTable().ajax.reload();
})

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