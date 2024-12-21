@extends('master')

@section('css')
    <style>
        .preview-hr {
            border-top: 1px solid black;
            margin-top: 1rem !important;
            margin-bottom: 1rem !important;
        }

        .bootstrap-datetimepicker-widget table td span {
            width: 50px !important;
        }
    </style>
@endsection

@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="components-preview mx-auto">
                        <div class="nk-block-head nk-block-head-lg wide-sm">
                            <div class="nk-block-head-content">
                                <h2 class="nk-block-title fw-normal">{{ $title }}</h2>
                            </div>
                        </div><!-- .nk-block-head -->
                        <div class="nk-block nk-block-lg">
                            <div class="card card-bordered card-preview">
                                <div class="card-inner">
                                    <div class="preview-block">
                                        <form class="form-validate is-alter" id="form-data" method="POST" action="/desain/process-generate" enctype="multipart/form-data" target="_blank">

                                            @csrf
                                            
                                            <div class="row mt-4">
                                                <div class="col-md-6">

                                                    <h4>1. SPK</h4>
                                                    <hr class="preview-hr">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Nama Job</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Nama Customer</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="customer" name="customer" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Tanggal Order</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control date-picker" id="tanggal_order" name="tanggal_order" data-date-format="dd/mm/yyyy" readonly required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Tanggal ACC</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control date-picker" id="tanggal_acc" name="tanggal_acc" data-date-format="dd/mm/yyyy" readonly required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Jam</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control timepicker" id="jam" name="jam" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Deadline</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control date-picker" id="deadline" name="deadline" data-date-format="dd/mm/yyyy" readonly required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Pengambilan</label>
                                                                <div class="form-control-wrap">
                                                                    <select name="pengambilan" class="select2-js" id="pengambilan">
                                                                        <option value="DIAMBIL">DIAMBIL</option>
                                                                        <option value="DIKIRIM EKSPEDISI">DIKIRIM EKSPEDISI</option>
                                                                        <option value="DIKIRIM LOKAL">DIKIRIM LOKAL</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">No. Tanda Terima</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="no_tanda_terima" name="no_tanda_terima" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Operator</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="operator" name="operator" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Job By</label>
                                                                <div class="form-control-wrap">
                                                                    <select name="job_by" class="select2-js" id="job_by">
                                                                        <option value="OCSAPACK">OCSAPACK</option>
                                                                        <option value="BRONPACK">BRONPACK</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">

                                                    <h4>2. Bahan</h4>
                                                    <hr class="preview-hr">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Bahan</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="bahan" name="bahan" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Jenis Bahan</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="jenis_bahan" name="jenis_bahan" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Foto Bahan</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="file" class="form-control" id="foto_bahan" name="foto_bahan" accept=".png, .jpg, .jpeg" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Jumlah Bahan</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="number" class="form-control" id="jumlah_bahan" name="jumlah_bahan" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Jumlah Kertas</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="jumlah_kertas" name="jumlah_kertas" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Dipotong Jadi</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="number" class="form-control" id="dipotong_jadi" name="dipotong_jadi" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Ukuran Jadi</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="ukuran" name="ukuran" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Ukuran File</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="ukuran_file" name="ukuran_file" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Untuk Cetakan</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="cetakan" name="cetakan" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Struk</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="struk" name="struk" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-5">

                                                <div class="col-md-12">
                                                    <h4>3. Cetak</h4>
                                                    <hr class="preview-hr">
    
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label class="form-label">Mesin</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="mesin" autocomplete="false">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label class="form-label">Set</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="set" autocomplete="false">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Model Cetak</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="model_cetak" autocomplete="false">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label class="form-label">Order</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="order" autocomplete="false">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Insheet</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="insheet" autocomplete="false">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label class="form-label">Plat</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="jumlah_plat" autocomplete="false">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Keterangan</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="keterangan" autocomplete="false">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 d-flex align-items-end">
                                                            <button type="button" class="btn btn-theme-sml" id="btn-add">Add</button>
                                                        </div>
                                                    </div>

                                                    <table class="table datatable-init-scrollable table-bordered w-100 mt-3" id="tabel-cetak">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th scope="col">Mesin</th>
                                                                <th scope="col">Set</th>
                                                                <th scope="col">Model Cetak</th>
                                                                <th scope="col">Order</th>
                                                                <th scope="col">Insheet</th>
                                                                <th scope="col">Jumlah Plat</th>
                                                                <th scope="col">Keterangan</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                    </table>
                                                    
                                                </div>

                                            </div>

                                            <div class="row mt-5">
                                                <div class="col-md-6">

                                                    <h4>4. Laminasi</h4>
                                                    <hr class="preview-hr">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Jenis Laminasi</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="jenis_laminasi" name="jenis_laminasi" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Tipe Laminasi</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="tipe_laminasi" name="tipe_laminasi" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Keterangan</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="keterangan_laminasi" name="keterangan_laminasi" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">

                                                    <h4>5. Foil</h4>
                                                    <hr class="preview-hr">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Foil Warna</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="foil_warna" name="foil_warna" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Keterangan</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="keterangan_foil" name="keterangan_foil" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row mt-5">
                                                <div class="col-md-3">

                                                    <h4>6. Mika</h4>
                                                    <hr class="preview-hr">

                                                    <div class="form-group">
                                                        <label class="form-label">Keterangan</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="keterangan_mika" name="keterangan_mika" required>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-3">

                                                    <h4>7. Lem</h4>
                                                    <hr class="preview-hr">

                                                    <div class="form-group">
                                                        <label class="form-label">Keterangan</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="keterangan_lem" name="keterangan_lem" required>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-3">

                                                    <h4>8. Lipat</h4>
                                                    <hr class="preview-hr">

                                                    <div class="form-group">
                                                        <label class="form-label">Keterangan</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="keterangan_lipat" name="keterangan_lipat" required>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-3">

                                                    <h4>9. Lain-lain</h4>
                                                    <hr class="preview-hr">

                                                    <div class="form-group">
                                                        <label class="form-label">Keterangan</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="keterangan_lain" name="keterangan_lain" required>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        
                                            <div class="row mt-5">
                                                <div class="col-md-12">
                                                    <hr class="preview-hr">
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-theme-sml" id="btn-submit">Print PDF</button>
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
@endsection
