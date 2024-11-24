<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Master extends Model
{
    public function listDataRole($q)
    {
        $data = DB::table('role')->where('status', 1)->select('id', 'nama');
        if($q) {
            $data = $data->where('nama', 'like', '%'.$q.'%');
        }
        return $data->get();
    }

    public function listDataDivisi($q)
    {
        $data = DB::table('divisi')->where('status', 1)->select('uid as id', 'nama');
        if($q) {
            $data = $data->where('nama', 'like', '%'.$q.'%');
        }
        return $data->get();
    }
}
