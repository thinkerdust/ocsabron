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
                                                    <input type="text" class="form-control" id="start_date" value="{{ date('d/m/Y', strtotime('-3 months')) }}" readonly />
                                                    <div class="input-group-addon">TO</div>
                                                    <input type="text" class="form-control"  id="end_date" value="{{ date('d/m/Y') }}" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Order By</label>
                                            <div class="form-control-wrap">
                                                <select class="form-control js-select2" id="order_by" required>
                                                    <option value="ALL">SEMUA</option>
                                                    <option value="OCSAPACK">OCSAPACK</option>
                                                    <option value="BRONPACK">BRONPACK</option>
                                                    <option value="RDS">RDS</option>
                                                    <option value="LYNUS">LYNUS</option>
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
                                            <th>Customer</th> 
                                            <th>Nama Job</th> 
                                            <th>Deadline</th> 
                                            <th>Produk</th> 
                                            <th>Bahan</th> 
                                            <th>Ukuran</th> 
                                            <th>Jumlah</th>
                                            <th>Tambahan</th>
                                            <th>Hasil Jadi</th>
                                            <th>Status</th>
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