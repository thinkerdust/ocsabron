$(document).ready(function() {

    let nip = $('#nip').val();
    if(nip != 0) {
        $.ajax({
            url: '/master/karyawan/edit/'+nip,
            dataType: 'json',
            success: function(response) {
                if(response.status) {
                    let data = response.data;
                    if(data.foto) {
                        $('#preview_image').attr('src', data.foto);
                    }

                    $('#nama').val(data.nama);
                    $('#nik').val(data.nik);
                    $('#npwp').val(data.npwp);
                    $('#gender').val(data.gender).change();
                    $('#nama_bank').val(data.bank);
                    $('#nomor_rekening').val(data.norek);
                    $('#nama_rekening').val(data.norek_an);
                    $('#tempat_lahir').val(data.tempat_lahir);
                    $('#tanggal_lahir').val(data.tanggal_lahir);
                    $('#telp').val(data.telp);
                    $('#email').val(data.email);
                    $("#provinsi").empty().append(`<option value="${data.id_prov}">${data.nama_provinsi}</option>`).val(data.id_prov).trigger('change');
                    $("#kota").empty().append(`<option value="${data.id_kab}">${data.nama_kota}</option>`).val(data.id_kab).trigger('change');
                    $("#kecamatan").empty().append(`<option value="${data.id_kec}">${data.nama_kecamatan}</option>`).val(data.id_kec).trigger('change');
                    $("#kelurahan").empty().append(`<option value="${data.id_kel}">${data.nama_kelurahan}</option>`).val(data.id_kel).trigger('change');
                    $('#alamat').val(data.alamat);
                    $('#tanggal_masuk').val(data.tanggal_masuk);
                    $('#status_pernikahan').val(data.status_pernikahan).change();
                    $('#tinggkat_pendidikan').val(data.tinggkat_pendidikan).change();
                    $('#nama_sekolah').val(data.nama_sekolah);
                    $('#jurusan_sekolah').val(data.jurusan_sekolah);
                    $('#tahun_lulus').val(data.tahun_lulus);
                    $('#agama').val(data.agama).change();
                    $('#no_bpjsks').val(data.no_bpjsks);
                    $('#no_bpjstk').val(data.no_bpjstk);
                    $('#golongan_darah').val(data.golongan_darah);
                    $('#rhesus').val(data.rhesus);
                    $('#tunjangan_hari_raya').val(data.status_thr);
                    $('#emergency_nama').val(data.emergency_nama);
                    $('#emergency_hubungan').val(data.emergency_hubungan);
                    $('#emergency_telp').val(data.emergency_telp);

                    if(data.file_ktp) {
                        $('#sectionFileKTP').html(`<a target="_blank" href="${data.file_ktp}" class="btn btn-info btn-sm">Link File KTP</a>`);
                    }

                    if(data.file_kk) {
                        $('#sectionFileKK').html(`<a target="_blank" href="${data.file_kk}" class="btn btn-info btn-sm">Link File KK</a>`);
                    }

                    if(data.file_bpjs_ks) {
                        $('#sectionFileBPJSKS').html(`<a target="_blank" href="${data.file_bpjs_ks}" class="btn btn-info btn-sm">Link File BPJS KS</a>`);
                    }

                    if(data.file_bpjs_tk) {
                        $('#sectionFileBPJSTK').html(`<a target="_blank" href="${data.file_bpjs_tk}" class="btn btn-info btn-sm">Link File BPJS TK</a>`);
                    }

                    if(data.file_npwp) {
                        $('#sectionFileNPWP').html(`<a target="_blank" href="${data.file_npwp}" class="btn btn-info btn-sm">Link File NPWP</a>`);
                    }

                    if(data.file_ijazah) {
                        $('#sectionFileIjazah').html(`<a target="_blank" href="${data.file_ijazah}" class="btn btn-info btn-sm">Link File Ijazah</a>`);
                    }
                }
            },
            error: function(error) {
                console.log(error)
                NioApp.Toast('Error while fetching data', 'error', {position: 'top-right'});
            }
        })
    }
});

$('.format-number').keyup(function() {
    $(this).val(function (index, value) {
      return value.replace(/\D/g, "");
    });
});

$('#provinsi').select2({
    placeholder: 'Pilih Provinsi',
    allowClear: true,
    ajax: {
        url: '/data-provinsi',
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

$('#provinsi').change(function() {
    $("#kota").empty().append(`<option value="">Pilih Kota</option>`).val('').trigger('change');
    let idx = $(this).val();
    if(idx) {
        $("#kota").select2({
            allowClear: true,
            placeholder: "Pilih Kota",
            ajax: {
                url: '/data-kota?id_prov='+idx,
                dataType: "json",
                type: "get",
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
        });
    }
})

$('#kota').change(function() {
    $("#kecamatan").empty().append(`<option value="">Pilih Kecamatan</option>`).val('').trigger('change');
    let idx = $(this).val();
    if(idx) {
        $("#kecamatan").select2({
            allowClear: true,
            placeholder: "Pilih Kecamatan",
            ajax: {
                url: '/data-kecamatan?id_kab='+idx,
                dataType: "json",
                type: "get",
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
        });
    }
})

$('#kecamatan').change(function() {
    $("#kelurahan").empty().append(`<option value="">Pilih Kelurahan</option>`).val('').trigger('change');
    let idx = $(this).val();
    if(idx) {
        $("#kelurahan").select2({
            allowClear: true,
            placeholder: "Pilih Kelurahan",
            ajax: {
                url: '/data-kelurahan?id_kec='+idx,
                dataType: "json",
                type: "get",
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
        });
    } 
})

$('#preview_image').attr('src', "https://minio.nexa.net.id/stemba/profil-user.png");

$('#foto').on('change', function() {

    // The recommended plugin to animate custom file input: bs-custom-file-input, is what bootstrap using currently
    // bsCustomFileInput.init();

    // Set maximum filesize
    var maxSizeMb = 5;

    // Get the file by using JQuery's selector
    var file = $('#foto')[0].files[0];

    // Make sure that a file has been selected before attempting to get its size.
    if(file !== undefined) {

        // Get the filesize
        var totalSize = file.size;

        // Convert bytes into MB
        var totalSizeMb = totalSize  / Math.pow(1024,2);

        // Check to see if it is too large.
        if(totalSizeMb > maxSizeMb) {

            // Create an error message
            var errorMsg = 'File too large. Maximum file size is ' + maxSizeMb + ' MB. Selected file is ' + totalSizeMb.toFixed(2) + ' MB';
            NioApp.Toast(errorMsg, 'warning', {position: 'top-right'});

            // Clear the value
            $('#foto').val('');
            $('#preview_image').attr('src', "https://minio.nexa.net.id/stemba/profil-user.png");
            $('#foto').next('label').html('Choose file');
        }else{
        	readURL(this,'preview_image');
        }
    }

});

const readURL = (input,el) => {
	if (input.files && input.files[0]) {
		const reader = new FileReader()
		reader.onload = (e) => {
			$('#'+el).removeAttr('src')
			$('#'+el).attr('src', e.target.result)
		}
		reader.readAsDataURL(input.files[0])
	}
};

$('#form-data').submit(function(e) {
    e.preventDefault();
    formData = new FormData($(this)[0]);
    var btn = $('#btn-submit');

    $.ajax({
        url : "/master/karyawan/store",  
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
                NioApp.Toast(response.message, 'success', {position: 'top-right'});
                setTimeout(function(){
                    window.location.href = '/master/karyawan';
                }, 2000)
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