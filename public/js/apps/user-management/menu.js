var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: true,
    searchDelay: 500,
    ajax: {
        url: '/user-management/datatable-menu'
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'parent'},
        {data: 'kode'},
        {data: 'nama'},
        {data: 'icon', orderable: false, searchable: false},
        {data: 'url', orderable: false},
        {data: 'id'},
    ],
    columnDefs: [
        {
            targets: -1,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {

                if(full['status'] == 1){
                    return `<div class="drodown">
                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <ul class="link-list-opt no-bdr">
                                    <li><a class="btn" onclick="edit(${full['id']})"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                    <li><a class="btn" onclick="hapus(${full['id']})"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                                </ul>
                            </div>
                        </div>`;
                }

                return '';
            }
        },
    ] 
});

function tambah() {
    $('#form-data')[0].reset();
    $('#id_menu').val('');
    $('#modalForm').modal('show');
}

$('#form-data').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit');

    $.ajax({
        url : "/user-management/store-menu",  
        data : formData,
        type : "POST",
        dataType : "JSON",
        cache:false,
        async : true,
        contentType: false,
        processData: false,
        // beforeSend: function() {
        //     btn.attr('disabled', true);
        //     btn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span>Loading ...</span>`);
        // },
        success: function(response) {
            if(response.status){
                $('#form-data')[0].reset();
                $('#modalForm').modal('hide');
                $("#dt-table").DataTable().ajax.reload(null, false);
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

function edit(id) {
    $.ajax({
        url: '/user-management/edit-menu/'+id,
        dataType: 'JSON',
        success: function(response) {
            if(response.status) {
                $('#modalForm').modal('show');
                let data = response.data;
                $('#id_menu').val(id);
                $('#menu').val(data.nama);
                $('#icon').val(data.icon);
                $('#kode').val(data.kode);
                $('#parent').val(data.parent);
                $('#url').val(data.url);
            }
        },
        error: function(error) {
            console.log(error)
            NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
        }
    })
}

function hapus(id) {
    Swal.fire({
        title: 'Apakah anda yakin akan menghapus data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, saya yakin!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/user-management/delete-menu/'+id,
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