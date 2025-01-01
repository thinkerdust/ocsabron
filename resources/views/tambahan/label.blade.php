<head>
    <title>Label</title>

    <style>
        @font-face {
            font-family: 'Poppins';
            src: url("{{ asset('assets/fonts/Poppins-Regular.ttf') }}") format('truetype');
        }
    
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0; /* Remove all default margins */
            padding: 0; /* Remove all default padding */
            box-sizing: border-box; /* Include padding and borders in dimensions */
        }
    
        body {
            margin: 0;
            padding: 0;
        }
    
        .label {
            margin: 0;
            padding: 0;
            width: 76mm;
            height: 44mm;
            /* border: 1px solid black; */
            overflow: hidden; /* Prevent content overflow */
        }
    
        .label span {
            font-size: 16px;
            word-break: break-word;
        }
    </style>
</head>

<body>
    <div class="pages" style="display: inline-block; width: 100%;">

        @foreach($data['isi'] as $index => $val)
            <table>
                <tr>
                    <td style="width: 50%; padding: 5px;">
                        <div class="label">
                            <table style="width: 100%; margin-bottom: 0px;">
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
        
                            <table style="width: 100%; margin-top: {{ strlen($data['keterangan'] ?? '') > 35 ? '0px' : '15px' }};">
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

        @endforeach            
    
    </div>
</body>
