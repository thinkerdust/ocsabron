<head>
    <title>Label</title>

    <style>
        @font-face {
            font-family: 'Poppins';
            src: url("{{ asset('assets/fonts/Poppins-Regular.ttf') }}") format('truetype');
        }
        * {
            font-family: 'Poppins', sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .label {
            width: 80mm;
            height: 50mm;
            border: 1px solid black;
            margin: 5mm;
            padding: 10px;
        }
        .label span {
            font-size: 16px;
            word-break: break-word;
        }
    </style>
</head>

<body>
    <div class="pages" style="display: inline-block; width: 100%;">
    
        @php 
            $counter = 1;
            $jml = sizeof($data['isi']);
        @endphp 
        @foreach($data['isi'] as $index => $val)
            <table>
                <tr>
                    <td style="width: 50%;">
                        <div class="label" style="margin: 20px auto;">
                            <table style="width: 100%; margin-bottom: 20px;">
                                <tr>
                                    <td><span>{{ $data['customer'] ?? '' }}</span></td>
                                </tr>
                                <tr>
                                    <td><span>{{ $data['jenis_produk'] ?? '' }}</span></td>
                                </tr>
                                <tr>
                                    <td><span>{{ $data['ukuran'] ?? '' }}</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>{{ $val }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span>{{ $data['keterangan'] ?? '' }}</span></td>
                                </tr>
                            </table>
        
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 80%;">
                                        <div>
                                            <span>{{ $data['operator'] ?? '' }}</span>
                                        </div>
                                        <div>
                                            <span>{{ $data['tanggal'] ?? '' }}</span>
                                        </div>
                                    </td>
                                    <td style="width: 20%;">
                                        <div>
                                            <span>{{ $data['jumlah_koli'] ?? '' }}</span>
                                            <span>Dus</span>
                                        </div>
                                        <div>
                                            <span>{{ ($index + 1) . '/' . $data['jumlah_koli'] ?? '' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>

            @if($counter < $jml)
            <div style="page-break-before : always;"></div>
            @php $counter++; @endphp
            @endif
        @endforeach
    
    </div>
</body>
