<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Repositories\MonitoringRepository;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class MonitoringController extends BaseController
{
    protected $monitoring;
    protected $order;
    protected $order_detail;

    function __construct(MonitoringRepository $monitoring, Order $order, OrderDetail $order_detail)
    {
        $this->monitoring   = $monitoring;
        $this->order        = $order;
        $this->order_detail = $order_detail;
    }

    public function index()
    {
        $title  = 'Monitoring Status';
        $js     = 'js/apps/monitoring/index.js?_='.rand();
        return view('monitoring.index', compact('js', 'title'));
    }

    public function datatable_monitoring(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $data = $this->monitoring->dataTableMonitoring($start_date, $end_date); 
        return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '';
                    if(Gate::allows('crudAccess', 'MR', $row)) {

                        $btn = '<div class="drodown">
                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a class="btn" onclick="detail(\'' . $row->uid . '\')"><em class="icon ni ni-eye"></em><span>Detail</span></a></li>
                                    </ul>
                                </div>
                            </div>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function detail_monitoring(Request $request) 
    {
        $id     = $request->id;
        $user   = $this->order->getOrder($id);

        return $this->ajaxResponse(true, 'Success!', $user);
    }

    public function datatable_detail_monitoring(Request $request)
    {
        $uid    = $request->uid;
        $data   = $this->order->dataTableDetailOrder($uid); 
        return Datatables::of($data)->addIndexColumn()->make(true);
    }

}
