<!DOCTYPE html>
<html>
<head>
    <title>Label</title>
    <style>
        @font-face {
            font-family: 'Poppins';
            src: url("{{ asset('assets/fonts/Poppins-Regular.ttf') }}") format('truetype');
        }
    
        /* PENTING: Hilangkan margin default browser & margin halaman PDF */
        @page { margin: 0px; }
        body { margin: 0px; padding: 0px; font-family: 'Poppins', sans-serif; }

        /* Container utama per label = 1 Halaman PDF */
        .label-container {
            position: relative;
            width: 100%; 
            height: 100%; 
            page-break-after: always; /* Paksa ganti halaman setiap loop */
            overflow: hidden;
        }

        /* Hapus page-break untuk halaman terakhir agar tidak ada halaman kosong di akhir */
        .label-container:last-child {
            page-break-after: avoid;
        }

        .content-wrapper {
            padding: 3mm; /* Padding aman agar tidak terpotong printer */
            height: 100%;
            position: relative;
        }

        /* Utilitas Teks */
        .text-row { margin-bottom: 2px; }
        .text-label { font-size: 14px; font-weight: bold; display: block; }
        .text-value { font-size: 16px; display: block; word-wrap: break-word; }
        .text-big { font-size: 24px; font-weight: bold; }
        
        /* Layouting sederhana menggunakan Float karena Flexbox support DOMPDF terbatas */
        .col-left { float: left; width: 65%; }
        .col-right { float: right; width: 35%; text-align: right; }
        
        /* Clear float fix */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* Footer Positioning */
        .footer-section {
            position: absolute;
            bottom: 5mm; /* Jarak dari bawah */
            left: 3mm;
            right: 3mm;
            width: 96%;
        }
    </style>
</head>

<body>
    @foreach($data['isi'] as $index => $val)
        <div class="label-container">
            <div class="content-wrapper">
                
                <div class="clearfix">
                    <div class="col-left">
                        <div class="text-row">
                            <span class="text-value">{{ $data['customer'] ?? '-' }}</span>
                        </div>
                        <div class="text-row">
                            <span class="text-value">{{ $data['nama'] ?? '-' }}</span>
                        </div>
                        <div class="text-row">
                            <span class="text-value">{{ $data['jenis_produk'] ?? '-' }}</span>
                        </div>
                        <div class="text-row">
                            <span class="text-value">{{ $data['ukuran'] ?? '-' }}</span>
                        </div>
                        
                        <div class="text-row" style="margin-top: 5px;">
                            <span class="text-value" style="font-weight:bold;">{{ $val }}</span>
                        </div>
                        <div class="text-row">
                            <span class="text-value">{{ $data['keterangan'][$index] ?? '' }}</span>
                        </div>
                    </div>

                    <div class="col-right">
                        <span class="text-big">{{ $data['order_by'] ?? '' }}</span>
                    </div>
                </div>

                <div class="footer-section clearfix">
                    <div class="col-left">
                        <div class="text-row">
                            <span class="text-value" style="font-size: 12px;">{{ $data['username'] ?? '' }}</span>
                        </div>
                        <div class="text-row">
                            <span class="text-value" style="font-size: 12px;">
                                {{ $data['tanggal'] ?? '' }} | {{ $data['jam'] ?? '' }}
                            </span>
                        </div>
                    </div>

                    <div class="col-right">
                        <div class="text-row">
                            <span class="text-big">{{ $data['jumlah_koli'] ?? '' }} <span style="font-size: 14px;">Dus</span></span>
                        </div>
                        <div class="text-row">
                            <span class="text-big">{{ ($index + 1) . '/' . $data['jumlah_koli'] }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
</body>
</html>