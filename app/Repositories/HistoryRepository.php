<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Carbon\Carbon;

class HistoryRepository {

    protected $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    public function dataTableHistory($start_date, $end_date)
    {
        $start_date = Carbon::createFromFormat('d/m/Y', $start_date);
        $start_date = $start_date->format('Y-m-d');

        $end_date = Carbon::createFromFormat('d/m/Y', $end_date);
        $end_date = $end_date->format('Y-m-d');

        $query = DB::table('order')
                    ->whereIn('status', [0, 2]) 
                    ->whereBetween('tanggal', [$start_date, $end_date])
                    ->select('uid', 'nama', 'customer', 'jenis_produk', 'jenis_kertas', 'ukuran', 'jumlah', 'file_spk', 'status',
                        DB::raw("DATE_FORMAT(deadline, '%d/%m/%Y') as deadline, DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal")
                    );
        
        return $query;
    }

}
