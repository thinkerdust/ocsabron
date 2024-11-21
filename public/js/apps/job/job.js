$(document).ready(function() {
    $('#btn-filter').click(function() {
        $("#dt-table").DataTable().ajax.reload();
    })
})

var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    ajax: {
        url: '/job/datatable',
        type: 'POST',
        data: function(d) {
            d._token        = token;
            d.start_date    = $('#start_date').val();
            d.end_date      = $('#end_date').val();
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'nama', name: 'nama'},
        {data: 'tanggal', name: 'tanggal'},
        {data: 'deadline', name: 'deadline'},
        {data: 'jenis_produk', orderable: false, searchable: false},
        {data: 'jenis_kertas', orderable: false, searchable: false},
        {data: 'jumlah'},
        {data: 'action', orderable: false, searchable: false},
    ],
});

function tambah() {
    $('#modalForm').modal('show');
    $('#form-data')[0].reset();
    $('#tipe_ijin').val('').change();
    $('#lampiran').next('label').html('Choose file');
}

function edit(uid) {
    // redirect to for edit
    window.location.href = '/job/form/'+uid;
}

function hapus(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/job/delete/'+id,
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