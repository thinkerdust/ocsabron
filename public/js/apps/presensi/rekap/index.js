var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    ajax: {
        url: '/presensi/rekap/datatable',
        type: 'POST',
        data: function(d) {
            d._token = token;
            d.bulan = $('#filter_bulan').val();
            d.tahun = $('#filter_tahun').val();
            d.unit = $('#filter_unit').val();
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'pin', name: 'k.pin'},
        {data: 'nama_karyawan', name: 'k.nama'},
        {data: 'nama_unit', name: 'u.nama'},
        {data: 'hari_kerja', className: 'text-end', orderable: false, searchable: false},
        {data: 'masuk', className: 'text-end', orderable: false, searchable: false},
        {data: 'cuti', className: 'text-end', orderable: false, searchable: false},
        {data: 'cuti_diluar_tanggungan', className: 'text-end', orderable: false, searchable: false},
        {data: 'mangkir', className: 'text-end', orderable: false, searchable: false},
        {data: 'terlambat_quarter', className: 'text-end', orderable: false, searchable: false},
        {data: 'terlambat', className: 'text-end', orderable: false, searchable: false},
        {data: 'absen_tidak_lengkap', className: 'text-end', orderable: false, searchable: false},
        {data: 'absen_tidak_lengkap', className: 'text-end', orderable: false, searchable: false},
        {data: 'klarifikasi', className: 'text-end', orderable: false, searchable: false}
    ],
    columnDefs: [] 
});

$('#btn-filter').click(function() {
    $("#dt-table").DataTable().ajax.reload();
})

$('.select2-js').select2({
    minimumResultsForSearch: Infinity
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