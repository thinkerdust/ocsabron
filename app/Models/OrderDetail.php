<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    public $timestamps  = false;
    protected $table    = 'order_detail';

    protected $fillable = [
        'uid',
        'uid_order',
        'uid_divisi',
        'status',
        'insert_at',
        'insert_by',
        'update_at',
        'update_by',
        'approve_at',
        'approve_by',
    ];
}
