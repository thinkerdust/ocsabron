var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: true,
    searchDelay: 500,
    ajax: {
        url: '/user-management/datatable-user-management'
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'name', name: 'u.name'},
        {data: 'username', name: 'u.username'},
        {data: 'email', name: 'u.email'},
        {data: 'role', name: 'r.nama'},
        {data: 'id'}
    ],
    columnDefs: [
        {
            targets: -1,
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {

                return `<div class="drodown">
                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a class="btn" onclick="edit(${full['id']})"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                        <li><a class="btn" onclick="hapus(${full['id']})"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                                        <li><a class="btn" onclick="reset_password(${full['id']})"><em class="icon ni ni-security"></em><span>Reset Password</span></a></li>
                                    </ul>
                                </div>
                            </div>`;
            }
        },
    ] 
});

$('#username').on('keyup', function() {
    var value = $(this).val();
    $(this).val(value.replace(/\s/g, ''));
});

function reset_password(id) {
    $.ajax({
        url: '/reset-password/'+id,
        dataType: 'json',
        success: function(response) {
            if(response.status) {
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

$('#role').select2({
    placeholder: 'Pilih Role',
    dropdownParent: $('#modalForm'),
    ajax: {
        url: '/data-role',
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

function tambah() {
    $('#modalForm').modal('show');
    $('#username').attr('readonly', false);
    $('#form-data')[0].reset();
    $('#id_user').val('');
    $('#role').val('').change();
}

$('#form-data').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit');

    $.ajax({
        url : "/user-management/register",  
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
        url: '/user-management/edit-user/'+id,
        dataType: 'json',
        success: function(response) {
            if(response.status) {
                $('#modalForm').modal('show');
                let data = response.data;
                $('#id_user').val(id);
                $('#nama').val(data.name);
                $('#email').val(data.email);
                $('#username').val(data.username);
                $('#username').attr('readonly', true);
                $("#role").empty().append(`<option value="${data.id_role}">${data.nama_role}</option>`).val(data.id_role).trigger('change');
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
                url: '/user-management/delete-user/'+id,
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