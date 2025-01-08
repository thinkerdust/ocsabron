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
                                <h4 class="card-title title">Active Job</h4>

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
                                            <th>Customer</th> 
                                            <th>Nama Job</th> 
                                            <th>Deadline</th> 
                                            <th>Produk</th> 
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

                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <h4 class="card-title title">Incoming Job</h4>

                                <table class="table table-striped nowrap" id="dt-table-incoming">
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
                                            <th>Progress</th>
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
                <input type="hidden" id="uid_order">
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Nama Job</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="nama" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Customer</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="customer" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Order</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="tanggal" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deadline</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="deadline" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jenis Produk</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="jenis_produk" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tambahan</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="tambahan" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ukuran</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="ukuran" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control text-end" id="jumlah" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jenis Kertas</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="jenis_kertas" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Finishing 1</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="finishing_satu" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Finishing 2</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="finishing_dua" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pengambilan</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="pengambilan" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Order By</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="order_by" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Keterangan</label>
                            <div class="form-control-wrap">
                                <textarea class="form-control" id="keterangan" readonly></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Approve (Desain)</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="tanggal_approve" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rusak Mesin (PON)</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="rusak_mesin" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rusak Cetakan (PON)</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="rusak_cetakan" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Hasil Jadi (Packing)</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control text-end" id="hasil_jadi" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah Koli (Packing)</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control text-end" id="jumlah_koli" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hasil Jadi (Packing Tambahan)</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control text-end" id="hasil_jadi_tambahan" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah Koli (Packing Tambahan)</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control text-end" id="jumlah_koli_tambahan" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor Nota (Administrasi)</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="nomor_nota" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor Resi (Ekpedisi)</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="nomor_resi" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rusak Mesin (Forming)</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="rusak_mesin_forming" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rusak Cetakan (Forming)</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="rusak_cetakan_forming" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="preview-hr">

                <table class="table table-striped nowrap" id="dt-table-detail">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Divisi</th> 
                            <th>Keterangan</th> 
                            <th>Approve At</th> 
                            <th>Approve By</th> 
                            <th>Status</th>
                        </tr>
                    </thead>

                </table>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalApprove">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-header">
                <h5 class="modal-title">Approve Job</h5>
            </div>
            <div class="modal-body">
                <form class="form-validate is-alter" id="form-approve">
                    @csrf
                    <input type="hidden" name="uid_approve" id="uid_approve">
                    <div class="form-group">
                        <label class="form-label">Hasil Jadi</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control format-currency text-end" name="hasil_jadi_approve" id="hasil_jadi_approve">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah Koli</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control format-currency text-end" name="jumlah_koli_approve" id="jumlah_koli_approve">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <div class="form-control-wrap">
                            <textarea class="form-control" name="keterangan_approve" id="keterangan_approve" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    
                    <hr class="preview-hr">
                    <button type="submit" class="btn btn-theme-sml" id="btn-submit">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalGenerate">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-header">
                <h5 class="modal-title">Generate Label</h5>
            </div>
            <div class="modal-body">
                <form class="form-validate is-alter" id="form-generate"  method="POST" action="/tambahan/generate-label" target="_blank">
                    @csrf
                    <input type="hidden" name="uid_generate" id="uid_generate">
                    <div class="form-group">
                        <label class="form-label">Hasil Jadi</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control format-currency text-end" name="generate_hasil_jadi" id="generate_hasil_jadi">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label">Jumlah Koli</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control format-currency text-end" name="generate_jumlah_koli" id="generate_jumlah_koli">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-info" id="btn-generate-form-isi"><span>Generate Form</span></button>
                        </div>
                    </div>

                    <div class="row mt-4" id="form-isi">

                    </div>
                    
                    <hr class="preview-hr">
                    <button type="submit" class="btn btn-theme-sml" id="btn-submit">Generate</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalPending">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-header">
                <h5 class="modal-title">Pending Job</h5>
            </div>
            <div class="modal-body">
                <form class="form-validate is-alter" id="form-pending">
                    @csrf
                    <input type="hidden" name="uid_pending" id="uid_pending">
                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <div class="form-control-wrap">
                            <textarea class="form-control" name="keterangan_pending" id="keterangan_pending" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    
                    <hr class="preview-hr">
                    <button type="submit" class="btn btn-theme-sml" id="btn-submit">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection