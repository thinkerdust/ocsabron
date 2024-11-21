<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Job extends Model
{
    use HasFactory;

    public function dataTableJob($start_date, $end_date)
    {
        $start_date = Carbon::createFromFormat('d/m/Y', $start_date);
        $start_date = $start_date->format('Y-m-d');

        $end_date = Carbon::createFromFormat('d/m/Y', $end_date);
        $end_date = $end_date->format('Y-m-d');

        $query = DB::table('order')
                    ->where('status', '1')
                    ->where(function ($join) use ($start_date, $end_date) {
                        $join->whereRaw('order.tanggal BETWEEN ? AND ?', [$start_date, $end_date]);
                    })
                    ->select('order.uid', 'order.nama', DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal"), DB::raw("DATE_FORMAT(deadline, '%d/%m/%Y') as deadline"), 'order.jenis_produk', 'order.jenis_kertas', 'order.jumlah', 'order.status');

        $order = request('order')[0];
        if ($order['column'] == '0') {
            $query->orderBy('tanggal', 'DESC');
        }
        
        return $query;
    }

    public function editJob($id)
    {
        $query = DB::table('order')
                    ->join('order_detail as od', 'od.order_header', '=', 'order.uid')
                    ->where('order.uid', $id)
                    ->select('order.uid', 'order.nama', 'order.tanggal', 'order.deadline', 'order.jenis_produk', 'order.tambahan', 'order.ukuran', 
                        'order.jumlah', 'order.jenis_kertas', 'order.finishing_satu', 'order.finishing_dua', 'order.pengambilan', 'order.order_by', 
                        'order.keterangan', 'order.status', 'od.divisi', 'od.status as status_detail')
                    ->get();

        return $query;
    }
}
