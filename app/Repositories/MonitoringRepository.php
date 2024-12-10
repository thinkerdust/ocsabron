<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Carbon\Carbon;

class TambahanRepository {

    protected $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    public function dataTableTambahan($start_date, $end_date, $status)
    {
        $start_date = Carbon::createFromFormat('d/m/Y', $start_date);
        $start_date = $start_date->format('Y-m-d');

        $end_date = Carbon::createFromFormat('d/m/Y', $end_date);
        $end_date = $end_date->format('Y-m-d');

        $query = DB::table('order as o')
                    ->join('divisi as d', 'o.uid_divisi', '=', 'd.uid')
                    ->join('order_detail as od', function($join) {
                        $join->on('o.uid', '=', 'od.uid_order')
                            ->on('d.uid', '=', 'od.uid_divisi');
                    })
                    ->where('o.status', 1)
                    ->where('d.urutan', 10) // tambahan 
                    ->whereBetween('o.tanggal', [$start_date, $end_date])
                    ->select('o.uid', 'o.nama', 'o.customer', 'o.jenis_produk', 'o.jenis_kertas', 'o.ukuran', 'o.jumlah', 'd.nama as progress', 'od.status',
                        DB::raw("DATE_FORMAT(o.deadline, '%d/%m/%Y') as deadline, DATE_FORMAT(o.tanggal, '%d/%m/%Y') as tanggal")
                    );

        if($status) {
            $query->where('od.status', $status);
        }
        
        return $query;
    }

}
