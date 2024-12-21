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
use App\Repositories\PackingRepository;
use Carbon\Carbon;
use PDF;
use Yajra\DataTables\DataTables;

class PackingController extends BaseController
{
    protected $packingrepo;
    protected $order;
    protected $order_detail;

    function __construct(PackingRepository $packingrepo, Order $order, OrderDetail $order_detail)
    {
        $this->packing      = $packingrepo;
        $this->order        = $order;
        $this->order_detail = $order_detail;
    }

    public function index()
    {
        $title  = 'Packing';
        $js     = 'js/apps/packing/index.js?_='.rand();
        return view('packing.index', compact('js', 'title'));
    }

    public function datatable_packing(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;
        $status     = $request->status;

        $data = $this->packing->dataTablePacking($start_date, $end_date, $status); 
        return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '';
                    if(Gate::allows('crudAccess', 'PACK', $row)) {
                        $btn_action     = '';
                        $btn_approve    = '<li><a class="btn" onclick="approve(\'' . $row->uid . '\', ' . $row->jumlah_koli . ', ' . $row->hasil_jadi . ')"><em class="icon ni ni-check-round-cut"></em><span>Approve</span></a></li>';
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
                                        <li><a class="btn" onclick="detail(\'' . $row->uid . '\')"><em class="icon ni ni-eye"></em><span>Detail</span></a></li>
                                        <li><a target="_blank" href="' . asset('storage/uploads/' . $row->file_spk) . '" class="btn"><em class="icon ni ni-download"></em><span>Download SPK</span></a></li>
                                        <li><a class="btn" onclick="generate_label(\'' . $row->uid . '\', ' . $row->jumlah_koli . ', ' . $row->hasil_jadi . ', ' . $row->isi . ')"><em class="icon ni ni-plus"></em><span>Generate Label</span></a></li>
                                        ' . $btn_action . '
                                    </ul>
                                </div>
                            </div>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function detail_packing(Request $request) 
    {
        $id     = $request->id;
        $user   = $this->order->getOrder($id);

        return $this->ajaxResponse(true, 'Success!', $user);
    }

    public function datatable_detail_packing(Request $request)
    {
        $uid    = $request->uid;
        $data   = $this->order->dataTableDetailOrder($uid); 
        return Datatables::of($data)->addIndexColumn()->make(true);
    }

    public function approve_packing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hasil_jadi_approve'    => 'required',
            'jumlah_koli_approve'   => 'required',
            'keterangan_approve'    => 'required',
        ], validation_message());

        if($validator->stopOnFirstFailure()->fails()){
            return $this->ajaxResponse(false, $validator->errors()->first());        
        }

        try {
            DB::beginTransaction();

            $id         = $request->post('uid_approve');
            $hasil_jadi = $request->post('hasil_jadi_approve');
            $hasil_jadi = Str::replace('.', '', $hasil_jadi);
            $jumlah_koli= $request->post('jumlah_koli_approve');
            $jumlah_koli= Str::replace('.', '', $jumlah_koli);
            $ket        = $request->post('keterangan_approve');
            $user       = Auth::user();

            $order = Order::where('uid', $id)->first();
            $this->logs($id, $order->uid_divisi, 2);
            OrderDetail::where([['uid_order', $id], ['uid_divisi', $order->uid_divisi]])
                ->update([
                    'status' => 2, 
                    'keterangan' => $ket, 
                    'approve_at' => Carbon::now(), 
                    'approve_by' => $user->username
                ]);

            $step = $this->order->getNextStep($id);
            Order::where('uid', $id)->update([
                    'uid_divisi'    => $step->uid_divisi,
                    'hasil_jadi'    => $hasil_jadi,
                    'jumlah_koli'   => $jumlah_koli,
                    'update_at'     => Carbon::now(), 
                    'update_by'     => $user->username
                ]);
            $this->logs($id, $step->uid_divisi, 1);

            DB::commit();

            return $this->ajaxResponse(true, 'Approve data berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->ajaxResponse(false, 'Approve data gagal', $e);
        }
    }

    public function generate_label_packing(Request $request)
    {

        $hasil_jadi     = $request->post('generate_hasil_jadi');   
        $jumlah_koli    = $request->post('generate_jumlah_koli');
        $isi            = $request->post('generate_isi');
        $uid            = $request->post('uid_generate');

        // insert to order
        DB::table('order')->where('uid', $uid)->update([
            'hasil_jadi'    => $hasil_jadi,
            'jumlah_koli'   => $jumlah_koli,
            'isi'           => $isi
        ]);

        // data order
        $order = Order::where('uid', 'O20241214145613361753')->first();

        $data = [
            'customer'         => $order->customer,
            'jenis_produk'     => $order->jenis_produk,
            'ukuran'           => $order->ukuran,
            'isi'              => $isi,
            'keterangan'       => $order->keterangan,
            'operator'         => $order->order_by,
            'tanggal'          => date('d/m/Y', strtotime($order->tanggal)),
            'jam'              => date('H:i', strtotime($order->update_at)),
            'jumlah_koli'      => $jumlah_koli,
            'hasil_jadi'       => $hasil_jadi,
        ];

        $pdf = PDF::loadView('packing.label', compact('data'))
              ->setPaper('a4', 'portrait');
        return $pdf->stream('label.pdf');
    }

    public function pending_packing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keterangan_pending'    => 'required',
        ], validation_message());

        if($validator->stopOnFirstFailure()->fails()){
            return $this->ajaxResponse(false, $validator->errors()->first());        
        }

        try {
            DB::beginTransaction();

            $id     = $request->post('uid_pending');
            $ket    = $request->post('keterangan_pending');
            $user   = Auth::user();

            $order = Order::where('uid', $id)->first();
            $this->logs($id, $order->uid_divisi, 3);
            OrderDetail::where([['uid_order', $id], ['uid_divisi', $order->uid_divisi]])
                ->update([
                    'status'    => 3, 
                    'keterangan' => $ket, 
                    'approve_at' => Carbon::now(), 
                    'approve_by' => $user->username
                ]);

            DB::commit();
            return $this->ajaxResponse(true, 'Pending data berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->ajaxResponse(false, 'Pending data gagal', $e);
        }
    }

    public function datatable_incoming_job(Request $request)
    {
        $data = $this->packing->dataTableIncomingJob(); 
        return Datatables::of($data)->addIndexColumn()->make(true);
    }
}
