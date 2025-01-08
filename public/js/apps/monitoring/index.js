var table = NioApp.DataTable('#dt-table', {
    serverSide: true,
    processing: true,
    responsive: false,
    searchDelay: 500,
    scrollX: true,
    ajax: {
        url: '/monitoring/datatable',
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
        {
            data: null, // Render multiple fields in a single cell
            render: function (data, type, row) {
                return `
                    <div class="info">
                        <div class="tanggal">${row.tanggal}</div>
                    </div>
                `;
            },
            name: 'o.tanggal'
        },
        {
            data: null, // Render multiple fields in a single cell
            render: function (data, type, row) {
                return `
                    <div class="info">
                        <div class="tanggal-approve">${row.tanggal_approve}</div>
                    </div>
                `;
            },
            name: 'o.tanggal_approve'
        },
        {
            data: null, // Render multiple fields in a single cell
            render: function (data, type, row) {
                return `
                    <div class="info">
                        <div class="deadline">${row.deadline}</div>
                    </div>
                `;
            },
            name: 'o.deadline'
        },
        {
            data: null, // Render multiple fields in a single cell
            render: function (data, type, row) {
                return `
                    <div class="info">
                        <div class="customer fw-bold" style="text-transform: capitalize; word-wrap: break-word;">${row.customer}</div>
                        <div class="jenis_produk" style="text-transform: capitalize; word-wrap: break-word;">${row.jenis_produk}</div>
                        <div class="ukuran" style="text-transform: capitalize; word-wrap: break-word;">${row.ukuran}</div>
                    </div>
                `;
            },
            name: 'o.customer'
        },
        {
            data: 'desain',
            orderable: false, 
            searchable: false,
            render: function(data) {
                if (data === 1) {
                    return '<span class="badge bg-danger">On-Progress</span>';
                } else if (data === 2) {
                    return '<span class="badge bg-success">Done</span>';
                } else if (data === 3) {
                    return '<span class="badge bg-warning">Pending</span>';
                } else {
                    return '<span class="badge bg-dark">None</span>';
                }
            }
        },
        {
            data: 'bahan',
            orderable: false, 
            searchable: false,
            render: function(data) {
                if (data === 1) {
                    return '<span class="badge bg-danger">On-Progress</span>';
                } else if (data === 2) {
                    return '<span class="badge bg-success">Done</span>';
                } else if (data === 3) {
                    return '<span class="badge bg-warning">Pending</span>';
                } else {
                    return '<span class="badge bg-dark">None</span>';
                }
            }
        },
        {
            data: 'cetak',
            orderable: false, 
            searchable: false,
            render: function(data) {
                if (data === 1) {
                    return '<span class="badge bg-danger">On-Progress</span>';
                } else if (data === 2) {
                    return '<span class="badge bg-success">Done</span>';
                } else if (data === 3) {
                    return '<span class="badge bg-warning">Pending</span>';
                } else {
                    return '<span class="badge bg-dark">None</span>';
                }
            }
        },
        {
            data: 'finishing_satu',
            orderable: false, 
            searchable: false,
            render: function(data) {
                if (data === 1) {
                    return '<span class="badge bg-danger">On-Progress</span>';
                } else if (data === 2) {
                    return '<span class="badge bg-success">Done</span>';
                } else if (data === 3) {
                    return '<span class="badge bg-warning">Pending</span>';
                } else {
                    return '<span class="badge bg-dark">None</span>';
                }
            }
        },
        {
            data: 'pon',
            orderable: false, 
            searchable: false,
            render: function(data) {
                if (data === 1) {
                    return '<span class="badge bg-danger">On-Progress</span>';
                } else if (data === 2) {
                    return '<span class="badge bg-success">Done</span>';
                } else if (data === 3) {
                    return '<span class="badge bg-warning">Pending</span>';
                } else {
                    return '<span class="badge bg-dark">None</span>';
                }
            }
        },
        {
            data: 'finishing_dua',
            orderable: false, 
            searchable: false,
            render: function(data) {
                if (data === 1) {
                    return '<span class="badge bg-danger">On-Progress</span>';
                } else if (data === 2) {
                    return '<span class="badge bg-success">Done</span>';
                } else if (data === 3) {
                    return '<span class="badge bg-warning">Pending</span>';
                } else {
                    return '<span class="badge bg-dark">None</span>';
                }
            }
        },
        {
            data: 'forming',
            orderable: false, 
            searchable: false,
            render: function(data) {
                if (data === 1) {
                    return '<span class="badge bg-danger">On-Progress</span>';
                } else if (data === 2) {
                    return '<span class="badge bg-success">Done</span>';
                } else if (data === 3) {
                    return '<span class="badge bg-warning">Pending</span>';
                } else {
                    return '<span class="badge bg-dark">None</span>';
                }
            }
        },
        {
            data: 'packing',
            orderable: false, 
            searchable: false,
            render: function(data) {
                if (data === 1) {
                    return '<span class="badge bg-danger">On-Progress</span>';
                } else if (data === 2) {
                    return '<span class="badge bg-success">Done</span>';
                } else if (data === 3) {
                    return '<span class="badge bg-warning">Pending</span>';
                } else {
                    return '<span class="badge bg-dark">None</span>';
                }
            }
        },
        {
            data: 'administrasi',
            orderable: false, 
            searchable: false,
            render: function(data) {
                if (data === 1) {
                    return '<span class="badge bg-danger">On-Progress</span>';
                } else if (data === 2) {
                    return '<span class="badge bg-success">Done</span>';
                } else if (data === 3) {
                    return '<span class="badge bg-warning">Pending</span>';
                } else {
                    return '<span class="badge bg-dark">None</span>';
                }
            }
        },
        {
            data: 'tambahan',
            orderable: false, 
            searchable: false,
            render: function(data) {
                if (data === 1) {
                    return '<span class="badge bg-danger">On-Progress</span>';
                } else if (data === 2) {
                    return '<span class="badge bg-success">Done</span>';
                } else if (data === 3) {
                    return '<span class="badge bg-warning">Pending</span>';
                } else {
                    return '<span class="badge bg-dark">None</span>';
                }
            }
        },
        {
            data: 'ekspedisi',
            orderable: false, 
            searchable: false,
            render: function(data) {
                if (data === 1) {
                    return '<span class="badge bg-danger">On-Progress</span>';
                } else if (data === 2) {
                    return '<span class="badge bg-success">Done</span>';
                } else if (data === 3) {
                    return '<span class="badge bg-warning">Pending</span>';
                } else {
                    return '<span class="badge bg-dark">None</span>';
                }
            }
        },

        {data: 'action', orderable: false, searchable: false},
    ],
    columnDefs: [
        {
            className: "nk-tb-col",
            targets: "_all"
        }
    ],
    createdRow: function(row, data, dataIndex) {
        console.log(data);
        // Access the specific cell and apply styles
        if (data.desain === 1) {
            $('td', row).eq(5).addClass('border border-white bg-danger');
        } else if (data.desain === 2) {
            $('td', row).eq(5).addClass('border border-white bg-success');
        } else if (data.desain === 3) {
            $('td', row).eq(5).addClass('border border-white bg-warning');
        } else {
            $('td', row).eq(5).addClass('border border-white bg-dark');
        }

        if (data.bahan === 1) {
            $('td', row).eq(6).addClass('border border-white bg-danger');
        } else if (data.bahan === 2) {
            $('td', row).eq(6).addClass('border border-white bg-success');
        } else if (data.bahan === 3) {
            $('td', row).eq(6).addClass('border border-white bg-warning');
        } else {
            $('td', row).eq(6).addClass('border border-white bg-dark');
        }

        if (data.bahan === 1) {
            $('td', row).eq(6).addClass('border border-white bg-danger');
        } else if (data.bahan === 2) {
            $('td', row).eq(6).addClass('border border-white bg-success');
        } else if (data.bahan === 3) {
            $('td', row).eq(6).addClass('border border-white bg-warning');
        } else {
            $('td', row).eq(6).addClass('border border-white bg-dark');
        }

        if (data.cetak === 1) {
            $('td', row).eq(7).addClass('border border-white bg-danger');
        } else if (data.cetak === 2) {
            $('td', row).eq(7).addClass('border border-white bg-success');
        } else if (data.cetak === 3) {
            $('td', row).eq(7).addClass('border border-white bg-warning');
        } else {
            $('td', row).eq(7).addClass('border border-white bg-dark');
        }

        if (data.finishing_satu === 1) {
            $('td', row).eq(8).addClass('border border-white bg-danger');
        } else if (data.finishing_satu === 2) {
            $('td', row).eq(8).addClass('border border-white bg-success');
        } else if (data.finishing_satu === 3) {
            $('td', row).eq(8).addClass('border border-white bg-warning');
        } else {
            $('td', row).eq(8).addClass('border border-white bg-dark');
        }

        if (data.pon === 1) {
            $('td', row).eq(9).addClass('border border-white bg-danger');
        } else if (data.pon === 2) {
            $('td', row).eq(9).addClass('border border-white bg-success');
        } else if (data.pon === 3) {
            $('td', row).eq(9).addClass('border border-white bg-warning');
        } else {
            $('td', row).eq(9).addClass('border border-white bg-dark');
        }

        if (data.finishing_dua === 1) {
            $('td', row).eq(10).addClass('border border-white bg-danger');
        } else if (data.finishing_dua === 2) {
            $('td', row).eq(10).addClass('border border-white bg-success');
        } else if (data.finishing_dua === 3) {
            $('td', row).eq(10).addClass('border border-white bg-warning');
        } else {
            $('td', row).eq(10).addClass('border border-white bg-dark');
        }

        if (data.forming === 1) {
            $('td', row).eq(11).addClass('border border-white bg-danger');
        } else if (data.forming === 2) {
            $('td', row).eq(11).addClass('border border-white bg-success');
        } else if (data.forming === 3) {
            $('td', row).eq(11).addClass('border border-white bg-warning');
        } else {
            $('td', row).eq(11).addClass('border border-white bg-dark');
        }

        if (data.packing === 1) {
            $('td', row).eq(12).addClass('border border-white bg-danger');
        } else if (data.packing === 2) {
            $('td', row).eq(12).addClass('border border-white bg-success');
        } else if (data.packing === 3) {
            $('td', row).eq(12).addClass('border border-white bg-warning');
        } else {
            $('td', row).eq(12).addClass('border border-white bg-dark');
        }

        if (data.administrasi === 1) {
            $('td', row).eq(13).addClass('border border-white bg-danger');
        } else if (data.administrasi === 2) {
            $('td', row).eq(13).addClass('border border-white bg-success');
        } else if (data.administrasi === 3) {
            $('td', row).eq(13).addClass('border border-white bg-warning');
        } else {
            $('td', row).eq(13).addClass('border border-white bg-dark');
        }

        if (data.tambahan === 1) {
            $('td', row).eq(14).addClass('border border-white bg-danger');
        } else if (data.tambahan === 2) {
            $('td', row).eq(14).addClass('border border-white bg-success');
        } else if (data.tambahan === 3) {
            $('td', row).eq(14).addClass('border border-white bg-warning');
        } else {
            $('td', row).eq(14).addClass('border border-white bg-dark');
        }

        if (data.ekspedisi === 1) {
            $('td', row).eq(15).addClass('border border-white bg-danger');
        } else if (data.ekspedisi === 2) {
            $('td', row).eq(15).addClass('border border-white bg-success');
        } else if (data.ekspedisi === 3) {
            $('td', row).eq(15).addClass('border border-white bg-warning');
        } else {
            $('td', row).eq(15).addClass('border border-white bg-dark');
        }
    }
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
        url: '/tambahan/detail/'+id,
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
        url: '/tambahan/detail/datatable',
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
                    
                }
                return '<span class="badge badge-dot '+ status[full['status']].class +'">'+ status[full['status']].title +'</span>';
            }
        },
    ]
});