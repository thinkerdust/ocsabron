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
}
