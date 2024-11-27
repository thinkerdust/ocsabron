<?php 

    if(!function_exists('js_tree')){
        function js_tree() {
            return '<script src="'. asset('assets/js/libs/jstree.js?ver=3.1.0') .'"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jstreegrid/3.10.2/jstreegrid.js"
            integrity="sha512-X6Gxkg/DfpLDVkviLz0tOU9sUECOVif8FTDKX4IJi6vbCNQlqWZ2dwRvCqetOJlDzijiLWfH286XYsmBDejkwQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jstreegrid/3.10.2/jstreegrid.min.js"
            integrity="sha512-984rgpiU2asdjWnDK870ho0raSWqYVU9yAK/Uc5dPE22zZPChgf/jOEpCbM2TXRmBy6vCoCh39EWziAno1XKNQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>';
        }
    }

    if(!function_exists('css_tree')) {
        function css_tree() {
            return '<link rel="stylesheet" href="'. asset('assets/css/libs/jstree.css?ver=3.1.0') .'">';
        }
    }
    
    if(!function_exists('js_summernote')) {
        function js_summernote() {
            return '<script src="'. asset('assets/js/libs/editors/summernote.js?ver=3.1.0').'"></script>';
        }
    }

    if(!function_exists('css_summernote')) {
        function css_summernote() {
            return '<link rel="stylesheet" href="'. asset('assets/css/editors/summernote.css?ver=3.1.0') .'">';
        }
    }

    if(!function_exists('js_qrcode')) {
        function js_qrcode() {
            return '<script src="'. asset('assets/js/libs/html5qrcode.js').'"></script>';
        }
    }

    if(!function_exists('js_webcam')) {
        function js_webcam() {
            return '<script src="'. asset('assets/js/libs/webcam.min.js').'"></script>';
        }
    }

    if(!function_exists('css_treant')) {
        function css_treant() {
            return '<link rel="stylesheet" href="'. asset('assets/css/libs/Treant.css') .'">';
        }
    }

    if(!function_exists('js_treant')) {
        function js_treant() {
            return '<script src="'. asset('assets/js/libs/raphael.js').'"></script><script src="'. asset('assets/js/libs/Treant.js').'"></script>';
        }
    }

    if(!function_exists('js_datatable_button')) {
        function js_datatable_button() {
            return '<script src="'. asset('assets/js/libs/datatable-btns.js') .'"></script>';
        }
    }

    if(!function_exists('js_moment')) {
        function js_moment() {
            return '<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>';
        }
    }

    if(!function_exists('css_bs_datetimepicker')) {
        function css_bs_datetimepicker() {
            return '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">';
        }
    }

    if(!function_exists('js_bs_datetimepicker')) {
        function js_bs_datetimepicker() {
            return '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>';
        }
    }

    if (!function_exists('validation_message')) {
        function validation_message()
        {
            $messages = [
                'required' => 'Field :attribute harus diisi.',
                'required_with' => 'Field :attribute harus diisi.',
                'unique' => 'Field :attribute sudah digunakan.',
                'required_if' => 'Field :attribute harus diisi jika :other adalah :value.',
                'email' => 'Field :attribute harus berupa alamat email yang valid.',
                'email.rfc,dns' => 'Field :attribute harus berupa alamat email yang valid sesuai dengan standar RFC dan DNS.',
                'numeric' => 'Field :attribute harus angka.',
                'max' => 'Field :attribute tidak boleh lebih dari :max .',
                'mimes' => 'Tipe file harus :value',
                'confirm_password.same' => 'Field New Password harus sama dengan Confirm Password.',
            ];

            return $messages;
        }
    }

    if (!function_exists('phone_number_format')) {
        function phone_number_format($nomor)
        {
            // Remove non-numeric characters
            $nomor_r = preg_replace('/[^0-9]+/', '', $nomor);
    
            // Handle different prefixes
            if (substr($nomor_r, 0, 1) === '0') {
                $nomor_r = substr($nomor_r, 1);
            } elseif (substr($nomor_r, 0, 2) === '62') {
                $nomor_r = substr($nomor_r, 2);
            } elseif (substr($nomor_r, 0, 3) === '+62') {
                $nomor_r = substr($nomor_r, 3);
            }
    
            // Ensure the phone number starts with '62'
            if (substr($nomor_r, 0, 2) !== '62') {
                if (substr($nomor_r, 0, 1) === '8') {
                    $nomor_fix = '62' . $nomor_r;
                } else {
                    $nomor_fix = '62' . substr($nomor_r, 2);
                }
            } else {
                $nomor_fix = $nomor_r;
            }
    
            return $nomor_fix;
        }
    }

    if(!function_exists('upload_file_name')) {
        function upload_file_name($val, $name)
        {
            $filename = explode('.', $name);
            $response = Http::attach(
                'file', file_get_contents($val), $name       
            )->post(env('MINIO_URL'), ['filename' => $filename[0]]);

            $result = $response->body();
            $parse = json_decode($result);

            return $parse;
        }
    }

    if(!function_exists('upload_file')) {
        function upload_file($val, $name)
        {
            $response = Http::attach(
                'file', file_get_contents($val), $name
            )->post(env('MINIO_URL'));

            $result = $response->body();
            $parse = json_decode($result);

            return $parse;
        }
    }

    if(!function_exists('generate_date_from_dateranges')) {
        function generate_date_from_dateranges($start_date, $end_date)  
        {
            // Create Carbon instances from the dates
            $start = Carbon\Carbon::parse($start_date);
            $end = Carbon\Carbon::parse($end_date);

            $list_dates = array();

            // Loop through each day between the start and end dates
            while ($start->lte($end)) {
                $list_dates[] = $start->format('Y-m-d'); // Output or use the date as needed
                
                // Move to the next day
                $start->addDay();
            }

            return $list_dates;
        }
    }
    
    if (!function_exists('list_bulan')) {
        function list_bulan()
        {
            $bln = array(
                '1' => 'Januari',
                '2' => 'Februari',
                '3' => 'Maret',
                '4' => 'April',
                '5' => 'Mei',
                '6' => 'Juni',
                '7' => 'Juli',
                '8' => 'Agustus',
                '9' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember'
                );

            return $bln;
        }
    }

    if (!function_exists('list_tahun')) {
        function list_tahun()
        {
            $start = '2023';
            $end = date('Y');

            $array = array();
            for($end; $end >= $start; $end--) {
                $array[$end] = $end;
            }

            return $array;
        }
    }

    if (!function_exists('getDayName'))
    {
        function getDayName($param = '', $style='1')
        {
            if ($style == 1) {
                switch ($param) {
                    case '0':
                        $result = 'Min';
                        break;
                    case '1':
                        $result = 'Sen';
                        break;
                    case '2':
                        $result = 'Sel';
                        break;
                    case '3':
                        $result = 'Rab';
                        break;
                    case '4':
                        $result = 'Kam';
                        break;
                    case '5':
                        $result = 'Jum';
                        break;
                    case '6':
                        $result = 'Sab';
                        break;
                    default:
                        $result = '';
                        break;
                }
            } else {
                switch ($param) {
                    case '0':
                        $result = 'Minggu';
                        break;
                    case '1':
                        $result = 'Senin';
                        break;
                    case '2':
                        $result = 'Selasa';
                        break;
                    case '3':
                        $result = 'Rabu';
                        break;
                    case '4':
                        $result = 'Kamis';
                        break;
                    case '5':
                        $result = 'Jumat';
                        break;
                    case '6':
                        $result = 'Sabtu';
                        break;
                    default:
                        $result = '';
                        break;
                }
            }
            return $result;
        }
    }

    function generateRandomString($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

?>