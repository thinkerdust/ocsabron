<!DOCTYPE html>
<html>
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
            width: 99mm;
            height: 73mm;
            /* border: 1px solid black; */
            overflow: hidden;
        }
    
        .label span {
            font-size: 18px;
            word-break: break-word;
        }
    </style>
</head>

<body>
    <div class="pages" style="display: inline-block; width: 100%;">

        @foreach($data['isi'] as $index => $val)
            <table>
                <tr>
                    <td style="width: 50%;">
                        <div class="label" style="position: relative;">
                            <table style="width: 95%; margin: auto; margin-top: 4mm; border-spacing: 0; position: absolute; top:0;">
                                <!-- Table 1 Row -->
                                <tr>
                                    <td style="vertical-align: top;">
                                        <table style="width: 100%; margin-bottom: 0;">
                                            <tr>
                                                <td style="width: 50%;">
                                                    <div>
                                                        <span>{{ $data['customer'] ?? '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span>{{ $data['nama'] ?? '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span>{{ $data['jenis_produk'] ?? '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span>{{ $data['ukuran'] ?? '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span>{{ $val }}</span>
                                                    </div>
                                                    <div>
                                                        <span>{{ $data['keterangan'][$index] ?? '' }}</span>
                                                    </div>
                                                </td>
                                                <td style="width: 50%; text-align: right; vertical-align: top;">
                                                    <div>
                                                        <span style="font-size: 26px;">{{ $data['order_by'] ?? '' }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 95%; margin: auto; border-spacing: 0; position: absolute; bottom: 22mm;">
                                <!-- Table 2 Row -->
                                <tr style="position: absolute; bottom: 0; left: 0;">
                                    <td style="vertical-align: bottom;">
                                        <table style="width: 100%; margin-top: 0;">
                                            <tr>
                                                <td style="width: 50%;">
                                                    <div>
                                                        <span>{{ $data['username'] ?? '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span>{{ $data['tanggal'] ?? '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span>{{ $data['jam'] ?? '' }}</span>
                                                    </div>
                                                </td>
                                                <td style="width: 50%; text-align: right;">
                                                    <div>
                                                        <span style="font-size: 26px;">{{ $data['jumlah_koli'] ?? '' }}</span>
                                                        <span style="font-size: 26px">Dus</span>
                                                    </div>
                                                    <div>
                                                        <span style="font-size: 26px">{{ ($index + 1) . '/' . $data['jumlah_koli'] ?? '' }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
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
</html>