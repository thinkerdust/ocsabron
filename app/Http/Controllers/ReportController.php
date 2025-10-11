<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Repositories\ReportRepository;

class ReportController extends BaseController
{
    protected $reportrepo;
    protected $order;
    protected $order_detail;

    function __construct(ReportRepository $reportrepo, Order $order, OrderDetail $order_detail)
    {
        $this->report       = $reportrepo;
        $this->order        = $order;
        $this->order_detail = $order_detail;
    }

    public function report_operator()
    {
        $title  = 'Laporan Operator';
        $js     = 'js/apps/report/operator.js?_='.rand();
        $js_library = js_datatable_button();
        return view('report.operator', compact('js', 'title', 'js_library'));
    }

    public function datatable_report_operator(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;
        $order_by   = $request->order_by;

        $data = $this->report->dataTableReportOperator($start_date, $end_date, $order_by); 
        return Datatables::of($data)->addIndexColumn()->make(true);
    }
}
