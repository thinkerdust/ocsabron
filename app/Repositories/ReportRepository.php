<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Carbon\Carbon;

class ReportRepository {

    protected $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    public function dataTableReportOperator($start_date, $end_date, $order_by)
    {
        $start_date = Carbon::createFromFormat('d/m/Y', $start_date);
        $start_date = $start_date->format('Y-m-d');

        $end_date = Carbon::createFromFormat('d/m/Y', $end_date);
        $end_date = $end_date->format('Y-m-d');

        $query = DB::table('order')
                    ->whereIn('status', [0, 2]) 
                    ->whereBetween('tanggal', [$start_date, $end_date])
                    ->select('uid', 'nama', 'customer', 'jenis_produk', 'jenis_kertas', 'ukuran', 'jumlah', 'status', 'hasil_jadi', 'tambahan',
                        DB::raw("DATE_FORMAT(deadline, '%d/%m/%Y') as deadline, DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal")
                    );

        if($order_by != 'ALL') {
            $query->where('order_by', $order_by);
        }
        
        return $query;
    }

}
