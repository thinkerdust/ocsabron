<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    public $timestamps  = false;
    protected $table    = 'order';

    protected $fillable = [
        'uid',
        'uid_divisi',
        'insert_at',
        'insert_by',
        'update_at',
        'update_by',
    ];

    public function getNextStep($uid_order)
    {
        $query = DB::table('order as o')
                    ->join('order_detail as od', function($join) {
                        $join->on('o.uid', '=', 'od.uid_order')
                            ->whereColumn('od.uid_divisi', '<>', 'o.uid_divisi')
                            ->where('od.status', '<>', '2');
                    })
                    ->join('divisi as d', 'od.uid_divisi', '=', 'd.uid')
                    ->where('o.uid', $uid_order)
                    ->orderBy('d.urutan', 'asc')
                    ->select('d.uid as uid_divisi')
                    ->first();

        return $query;
    }

    public function getOrder($uid)
    {
        $order = DB::table('order')
                    ->where('uid', $uid)
                    ->select('uid', 'nama', 'customer', 'jenis_produk', 'ukuran', 'jumlah', 'tambahan', 'jenis_kertas', 'finishing_satu', 'finishing_dua', 'pengambilan', 'order_by', 'keterangan',
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
