var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: false,
    scrollX: true,
    searchDelay: 500,
    ajax: {
        url: '/master/karyawan/datatable',
        type: 'POST',
        data: function(d) {
            d._token = token;
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'nip', name: 'k.nip'},
        {data: 'nama', name: 'k.nama'},
        {data: 'foto'},
        {data: 'status_karyawan'},
        {data: 'action', orderable: false, searchable: false},
    ],
    columnDefs: [
        {
            targets: 3,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {

                return `<a target="_blank" href="${full['foto']}"><img src="${full['foto']}" width="100px"></a>`;
            }
        },
        {
            targets: -2,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {

                var status = {
                    'TIDAK AKTIF': {'title': 'TIDAK AKTIF', 'class': ' bg-danger'},
                    'AKTIF': {'title': 'AKTIF', 'class': ' bg-success'},
                };
                if (typeof status[full['status_karyawan']] === 'undefined') {
                    return data;
                }
                return '<span class="badge '+ status[full['status_karyawan']].class +'">'+ status[full['status_karyawan']].title +'</span>';
            }
        },
    ] 
});

function hapus(nip) {
    Swal.fire({
        title: 'Apakah anda yakin akan menghapus data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus data.'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/master/karyawan/delete/'+nip,
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