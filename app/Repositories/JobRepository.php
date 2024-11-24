<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Carbon\Carbon;

class JobRepository {

    protected $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    public function dataTableJob($start_date, $end_date, $status)
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
                    ->where('d.urutan', 0) // new job
                    ->whereBetween('o.tanggal', [$start_date, $end_date])
                    ->select('o.uid', 'o.nama', 'o.jenis_produk', 'o.ukuran', 'o.jumlah', 'd.nama as progress', 'od.status',
                        DB::raw("DATE_FORMAT(o.deadline, '%d/%m/%Y') as deadline, DATE_FORMAT(o.tanggal, '%d/%m/%Y') as tanggal")
                    );

        if($status) {
            $query->where('od.status', $status);
        }
        
        return $query;
    }

    public function editJob($uid)
    {
        $order = DB::table('order')
                    ->where('uid', $uid)
                    ->select('uid', 'nama', 'jenis_produk', 'ukuran', 'jumlah', 'tambahan', 'jenis_kertas', 'finishing_satu', 'finishing_dua', 'pengambilan', 'order_by', 'keterangan',
                        DB::raw("DATE_FORMAT(deadline, '%d/%m/%Y') as deadline, DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal")
                    )
                    ->first();

        $detail = DB::table('order_detail as od')
                    ->join('divisi as d', 'od.uid_divisi', '=', 'd.uid')
                    ->where('od.uid_order', $uid)
                    ->select('od.uid_divisi', 'd.nama as nama_divisi')
                    ->get();

        $data = [
            'order'     => $order,
            'detail'    => $detail
        ];

        return $data;
    }
}