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
use App\Repositories\BahanRepository;
use Carbon\Carbon;
use Svg\Tag\Rect;
use Yajra\DataTables\DataTables;

class BahanController extends BaseController
{
    protected $bahanrepo;
    protected $order;
    protected $order_detail;

    function __construct(BahanRepository $bahanrepo, Order $order, OrderDetail $order_detail)
    {
        $this->bahan        = $bahanrepo;
        $this->order        = $order;
        $this->order_detail = $order_detail;
    }

    public function index()
    {
        $title  = 'Bahan Management';
        $js     = 'js/apps/bahan/index.js?_='.rand();
        return view('bahan.index', compact('js', 'title'));
    }

    public function datatable_bahan(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;
        $status     = $request->status;

        $data = $this->bahan->dataTableBahan($start_date, $end_date, $status); 
        return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '';
                    if(Gate::allows('crudAccess', 'BAHAN', $row)) {
                        $btn_action     = '';
                        $btn_approve    = '<li><a class="btn" onclick="approve(\'' . $row->uid . '\')"><em class="icon ni ni-check-round-cut"></em><span>Approve</span></a></li>';
                        $btn_pending    = '<li><a class="btn" onclick="pending(\'' . $row->uid . '\')"><em class="icon ni ni-na"></em><span>Pending</span></a></li>';
                        
                        if($row->status == 1) {
                            $btn_action = $btn_pending.$btn_approve;
                        } elseif($row->status == 3) {
                            $btn_action = $btn_approve;
                        }

                        $btn = '<div class="drodown">
                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="link-list-opt no-bdr">
                                        '.$btn_action.'
                                    </ul>
                                </div>
                            </div>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function approve_bahan(Request $request)
    {
        try {
            DB::beginTransaction();

            $id     = $request->id;
            $user   = Auth::user();

            $order = Order::where('uid', $id)->first();
            $this->logs($id, $order->uid_divisi, 2);
            OrderDetail::where([['uid_order', $id], ['uid_divisi', $order->uid_divisi]])->update(['status' => 2, 'approve_at' => Carbon::now(), 'approve_by' => $user->username]);

            $step = $this->order->getNextStep($id);
            Order::where('uid', $id)->update(['uid_divisi' => $step->uid_divisi, 'update_at' => Carbon::now(), 'update_by' => $user->username]);
            $this->logs($id, $step->uid_divisi, 1);

            DB::commit();
            return $this->ajaxResponse(true, 'Approve data berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->ajaxResponse(false, 'Approve data gagal', $e);
        }
    }

    public function pending_bahan(Request $request)
    {
        try {
            DB::beginTransaction();

            $id     = $request->id;
            $user   = Auth::user();

            $order = Order::where('uid', $id)->first();
            $this->logs($id, $order->uid_divisi, 3);
            OrderDetail::where([['uid_order', $id], ['uid_divisi', $order->uid_divisi]])->update(['status' => 3, 'approve_at' => Carbon::now(), 'approve_by' => $user->username]);

            DB::commit();
            return $this->ajaxResponse(true, 'Pending data berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->ajaxResponse(false, 'Pending data gagal', $e);
        }
    }

}
