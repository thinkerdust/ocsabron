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
                                            <th>Nama Customer</th> 
                                            <th>Deadline</th> 
                                            <th>Jenis Produk</th> 
                                            <th>Bahan</th>
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

@endsection