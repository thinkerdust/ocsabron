<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    public $timestamps = false;
    protected $table = 'logs';

    protected $fillable = [
        'uid',
        'uid_order',
        'uid_divisi',
        'status',
        'insert_at',
        'insert_by',
    ];
}
