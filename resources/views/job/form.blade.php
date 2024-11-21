@extends('master')

@section('content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Form Job</h2>
                        </div>
                    </div><!-- .nk-block-head -->
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <div class="preview-block">
                                    <form class="form-validate is-alter" id="form-data">
                                        @csrf
                                        <input type="hidden" name="id" id="id" value="{{ $id ?? '' }}">
                                    <h4>1. DATA JOB</h4>
                                    <hr class="preview-hr">
                                    <div class="row gy-4">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="form-label">Nama Job</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Tanggal Order</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control date-picker" id="tanggal" name="tanggal" data-date-format="dd/mm/yyyy" readonly required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Deadline</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control date-picker" id="deadline" name="deadline" data-date-format="dd/mm/yyyy" readonly required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Jenis Produk</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="jenis_produk" name="jenis_produk" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Tambahan</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="tambahan" name="tambahan" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Ukuran</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control phone" id="ukuran" name="ukuran" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Jumlah</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control number" id="jumlah" name="jumlah" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Jenis Kertas</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="jenis_kertas" name="jenis_kertas" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Finishing 1</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="finishing_satu" name="finishing_satu" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Finishing 2</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="finishing_dua" name="finishing_dua" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Pengambilan</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-control js-select2" name="pengambilan" id="pengambilan" required>
                                                        <option value="DIAMBIL">Diambil</option>
                                                        <option value="EKSPEDISI">Ekspedisi</option>
                                                        <option value="LOKAL">Lokal</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Order By</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-control js-select2" name="order_by" id="order_by" required>
                                                        <option value="OSCAPACK">Oscapack</option>
                                                        <option value="BRONPACK">Bronpack</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Keterangan</label>
                                                <div class="form-control-wrap">
                                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="5" required></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <hr class="preview-hr">
                                    <h4>2. TASK</h4>
                                    <hr class="preview-hr">
                                    <div class="row gy-4">
                                        
                                        @foreach ($divisi as $d)    
                                            <div class="col-md-2">
                                                <div class="custom-control custom-control-lg custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="divisi[]" id="{{ $d->uid }}" value="{{ $d->uid }}">
                                                    <label class="custom-control-label" for="{{ $d->uid }}">{{ $d->nama }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                    </div>
                                    
                                    <hr class="preview-hr">
                                    <button type="submit" class="btn btn-theme-sml" id="btn-submit">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div><!-- .card-preview -->
                    </div><!-- .nk-block -->
                </div><!-- .components-preview -->
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    label.cabinet{
        display: block;
        cursor: pointer;
    }

    label.cabinet input.file{
        position: relative;
        height: 100%;
        width: auto;
        opacity: 0;
        -moz-opacity: 0;
        filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);
        margin-top:-30px;
    }

    .gambar {
        width: 200px;
        height: 200px;
        object-fit: cover;
        object-position: 50% 0;
    }
</style>

@endsection
