<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Repositories\HistoryRepository;
use Yajra\DataTables\DataTables;

class HistoryController extends BaseController
{
    protected $historyrepo;
    protected $order;
    protected $order_detail;

    function __construct(HistoryRepository $historyrepo, Order $order, OrderDetail $order_detail)
    {
        $this->history = $historyrepo;
        $this->order        = $order;
        $this->order_detail = $order_detail;
    }

    public function index()
    {
        $title      = 'History';
        $js_library = js_datatable_button();
        $js         = 'js/apps/history/index.js?_='.rand();
        return view('history.index', compact('js', 'js_library', 'title'));
    }

    public function datatable_history(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $data = $this->history->dataTableHistory($start_date, $end_date); 
        return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '';
                    if(Gate::allows('crudAccess', 'HSTRY', $row)) {
                        $btn_spk = '';
                        if(!empty($row->file_spk)) {
                            $btn_spk = '<li><a target="_blank" href="' . asset('storage/uploads/' . $row->file_spk) . '" class="btn"><em class="icon ni ni-download"></em><span>Download SPK</span></a></li>';
                        }
                        $btn = '<div class="drodown">
                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a class="btn" onclick="detail(\'' . $row->uid . '\')"><em class="icon ni ni-eye"></em><span>Detail</span></a></li>
                                        '.$btn_spk.'
                                    </ul>
                                </div>
                            </div>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function detail_history(Request $request) 
    {
        $id     = $request->id;
        $user   = $this->order->getOrder($id);

        return $this->ajaxResponse(true, 'Success!', $user);
    }

    public function datatable_detail_history(Request $request)
    {
        $uid    = $request->uid;
        $data   = $this->order->dataTableDetailOrder($uid); 
        return Datatables::of($data)->addIndexColumn()->make(true);
    }
}
