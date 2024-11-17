<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master;

class MasterController extends Controller
{
    function __construct()
    {
        $this->master = new Master();
    }

    public function list_data_role(Request $request)
    {
        $q = $request->get('q');
        $data = $this->master->listDataRole($q);
        return response()->json($data);
    }
}
