<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Carbon\Carbon;

class MonitoringRepository {

    protected $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    public function dataTableMonitoring($start_date, $end_date, $order_by)
    {
        $start_date = Carbon::createFromFormat('d/m/Y', $start_date);
        $start_date = $start_date->format('Y-m-d');

        $end_date = Carbon::createFromFormat('d/m/Y', $end_date);
        $end_date = $end_date->format('Y-m-d');
        
        $query = DB::table('order as o')
            ->join('order_detail as od', function($join) {
                $join->on('o.uid', '=', 'od.uid_order');
            })
            ->join('divisi as d', function($join) {
                $join->on('od.uid_divisi', '=', 'd.uid')
                    ->where('d.status', '=', '1')
                    ->where('d.urutan', '>', '0');
            })
            ->whereBetween('o.tanggal', [$start_date, $end_date])
            ->where('o.status', '1')
            ->select('o.uid', 'o.nama', 'o.customer', 'o.jenis_produk', 'o.ukuran', 'o.jumlah', 'o.status', 'd.urutan', 'd.nama as divisi',
                DB::raw("DATE_FORMAT(o.deadline, '%d/%m/%Y') as deadline, DATE_FORMAT(o.tanggal_approve, '%d/%m/%Y') as tanggal_approve, DATE_FORMAT(o.tanggal, '%d/%m/%Y') as tanggal"),
                DB::raw("MAX(CASE WHEN d.nama = 'DESAIN' AND od.status = 1 THEN 1 
                                WHEN d.nama = 'DESAIN' AND od.status = 2 THEN 2
                                WHEN d.nama = 'DESAIN' AND od.status = 3 THEN 3
                            ELSE 0 END) as desain"),
                DB::raw("MAX(CASE WHEN d.nama = 'BAHAN' AND od.status = 1 THEN 1 
                                WHEN d.nama = 'BAHAN' AND od.status = 2 THEN 2
                                WHEN d.nama = 'BAHAN' AND od.status = 3 THEN 3
                            ELSE 0 END) as bahan"),
                DB::raw("MAX(CASE WHEN d.nama = 'CETAK' AND od.status = 1 THEN 1 
                                WHEN d.nama = 'CETAK' AND od.status = 2 THEN 2
                                WHEN d.nama = 'CETAK' AND od.status = 3 THEN 3
                            ELSE 0 END) as cetak"),
                DB::raw("MAX(CASE WHEN d.nama = 'FINISHING 1' AND od.status = 1 THEN 1 
                                WHEN d.nama = 'FINISHING 1' AND od.status = 2 THEN 2
                                WHEN d.nama = 'FINISHING 1' AND od.status = 3 THEN 3
                            ELSE 0 END) as finishing_satu"),
                DB::raw("MAX(CASE WHEN d.nama = 'PON' AND od.status = 1 THEN 1 
                                WHEN d.nama = 'PON' AND od.status = 2 THEN 2
                                WHEN d.nama = 'PON' AND od.status = 3 THEN 3
                            ELSE 0 END) as pon"),
                DB::raw("MAX(CASE WHEN d.nama = 'FINISHING 2' AND od.status = 1 THEN 1 
                            WHEN d.nama = 'FINISHING 2' AND od.status = 2 THEN 2
                            WHEN d.nama = 'FINISHING 2' AND od.status = 3 THEN 3
                        ELSE 0 END) as finishing_dua"),
                DB::raw("MAX(CASE WHEN d.nama = 'FORMING' AND od.status = 1 THEN 1 
                                WHEN d.nama = 'FORMING' AND od.status = 2 THEN 2
                                WHEN d.nama = 'FORMING' AND od.status = 3 THEN 3
                            ELSE 0 END) as forming"),
                DB::raw("MAX(CASE WHEN d.nama = 'PACKING' AND od.status = 1 THEN 1 
                                WHEN d.nama = 'PACKING' AND od.status = 2 THEN 2
                                WHEN d.nama = 'PACKING' AND od.status = 3 THEN 3
                            ELSE 0 END) as packing"),
                DB::raw("MAX(CASE WHEN d.nama = 'ADMINISTRASI' AND od.status = 1 THEN 1 
                                WHEN d.nama = 'ADMINISTRASI' AND od.status = 2 THEN 2
                                WHEN d.nama = 'ADMINISTRASI' AND od.status = 3 THEN 3
                            ELSE 0 END) as administrasi"),
                DB::raw("MAX(CASE WHEN d.nama = 'TAMBAHAN' AND od.status = 1 THEN 1 
                                WHEN d.nama = 'TAMBAHAN' AND od.status = 2 THEN 2
                                WHEN d.nama = 'TAMBAHAN' AND od.status = 3 THEN 3
                            ELSE 0 END) as tambahan"),
                DB::raw("MAX(CASE WHEN d.nama = 'EKSPEDISI' AND od.status = 1 THEN 1 
                                WHEN d.nama = 'EKSPEDISI' AND od.status = 2 THEN 2
                                WHEN d.nama = 'EKSPEDISI' AND od.status = 3 THEN 3
                            ELSE 0 END) as ekspedisi"),
            )
            ->groupBy('o.uid');
        
        if($order_by != 'ALL') {
            $query->where('o.order_by', $order_by);
        }

        return $query;
    }

}
