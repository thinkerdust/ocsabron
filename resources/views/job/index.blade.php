@extends('master')

@section('content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{ $title }}</h3>
                        </div>
                    </div><!-- .nk-block-head -->
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                @can("crudAccess", "JOB")
                                    <a href="/job/form" class="btn btn-theme-sml btn-sm"><em class="icon ni ni-plus"></em><span>Add Data</span></a>
                                    <hr class="preview-hr">
                                @endcan

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Filter Tanggal</label>
                                            <div class="form-control-wrap">
                                                <div class="input-daterange date-picker-range input-group">
                                                    <input type="text" class="form-control" name="start_date" id="start_date" value="{{ date('01/m/Y') }}" readonly /> 
                                                    <div class="input-group-addon">TO</div>
                                                    <input type="text" class="form-control" name="end_date" id="end_date" value="{{ date('d/m/Y') }}" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Status</label>
                                            <div class="form-control-wrap">
                                                <select class="form-control select2-js" name="filter_status" id="filter_status">
                                                    <option value="">ALL</option>
                                                    <option value="1">ON PROGRESS</option>
                                                    <option value="3">PENDING</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2" style="margin-top:30px">
                                        <button type="button" class="btn btn-info" id="btn-filter"><em class="icon ni ni-search"></em><span>Filter</span></button>
                                    </div>
                                </div>

                                <hr class="preview-hr">
                                <table class="table table-striped nowrap" id="dt-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Order</th> 
                                            <th>Nama Job</th> 
                                            <th>Deadline</th> 
                                            <th>Jenis Produk</th> 
                                            <th>Ukuran</th> 
                                            <th>Jumlah</th>
                                            <th>Progress</th>
                                            <th>Status</th>
                                            <th>Action</th> 
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div> <!-- nk-block -->
                </div><!-- .components-preview -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalDetail">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-header">
                <h5 class="modal-title">Detail Job</h5>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nama Job</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="nama" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Customer</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="customer" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Order</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="tanggal" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deadline</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="deadline" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jenis Produk</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="jenis_produk" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tambahan</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="tambahan" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ukuran</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="ukuran" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Jumlah</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="jumlah" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jenis Kertas</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="jenis_kertas" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Finishing 1</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="finishing_satu" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Finishing 2</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="finishing_dua" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pengambilan</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="pengambilan" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Order By</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="order_by" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Keterangan</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="keterangan" required>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="preview-hr">

                <div class="row gy-4" id="order_detail"></div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection