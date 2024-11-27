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
use App\Repositories\DesainRepository;
use Carbon\Carbon;
use Svg\Tag\Rect;
use Yajra\DataTables\DataTables;
use PDF;
use Illuminate\Support\Facades\Storage;

class DesainController extends BaseController
{
    protected $desainrepo;
    protected $order;
    protected $order_detail;

    function __construct(DesainRepository $desainrepo, Order $order, OrderDetail $order_detail)
    {
        $this->desain       = $desainrepo;
        $this->order        = $order;
        $this->order_detail = $order_detail;
    }

    public function index()
    {
        $title  = 'Desain Management';
        $js     = 'js/apps/desain/index.js?_='.rand();
        return view('desain.index', compact('js', 'title'));
    }

    public function datatable_desain(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;
        $status     = $request->status;

        $data = $this->desain->dataTableDesain($start_date, $end_date, $status); 
        return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '';
                    if(Gate::allows('crudAccess', 'JOB', $row)) {
                        $btn_action     = '';
                        $btn_approve    = '<li><a class="btn" onclick="approve(\'' . $row->uid . '\')"><em class="icon ni ni-check-round-cut"></em><span>Approve</span></a></li>';
                        $btn_pending    = '<li><a class="btn" onclick="pending(\'' . $row->uid . '\')"><em class="icon ni ni-na"></em><span>Pending</span></a></li>';
                        
                        if($row->status == 1) {
                            $btn_action = $btn_approve.$btn_pending;
                        } elseif($row->status == 3) {
                            $btn_action = $btn_approve;
                        }

                        $btn = '<div class="drodown">
                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="link-list-opt no-bdr">
                                        '.$btn_action.'
                                        <li><a href="/desain/cetak/'.$row->uid.'" target="_blank" class="btn"><em class="icon ni ni-file-pdf"></em><span>Cetak</span></a></li>
                                    </ul>
                                </div>
                            </div>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function approve_desain(Request $request)
    {

        // validation
        $validator = Validator::make($request->all(), [
            'tgl_acc_approve'       => 'required',
            'keterangan_approve'    => 'required',
            'spk_approve'           => 'required|mimes:pdf|max:2048'
        ], validation_message());

        if($validator->stopOnFirstFailure()->fails()){
            return $this->ajaxResponse(false, $validator->errors()->first());        
        }

        try {
            DB::beginTransaction();

            $id         = $request->post('uid_approve');
            $tgl_acc    = $request->post('tgl_acc_approve');
            $tgl_acc    = Carbon::createFromFormat('d/m/Y', $request->post('tgl_acc_approve'))->format('Y-m-d');
            $ket        = $request->post('keterangan_approve');
            $user       = Auth::user();

            $order = Order::where('uid', $id)->first();
            $this->logs($id, $order->uid_divisi, 2);
            OrderDetail::where([['uid_order', $id], ['uid_divisi', $order->uid_divisi]])->update(['status' => 2, 'tgl_acc' => $tgl_acc, 'keterangan' => $ket, 'approve_at' => Carbon::now(), 'approve_by' => $user->username]);

            $step = $this->order->getNextStep($id);

            $dataOrder = [
                'uid_divisi'    => $step->uid_divisi, 
                'update_at'     => Carbon::now(), 
                'update_by'     => $user->username
            ];

            // remove old file
            if(!empty($uid) && $request->file('spk_approve')) {
                $data_order = Order::where('id', $uid)->first();
                $oldFile    = $data_order->file_spk;

                if(!empty($oldFile)) {
                    if (Storage::disk('public')->exists($oldFile)) {
                        // Delete the file
                        Storage::disk('public')->delete($oldFile);
                    }
                }
                
            }

            // upload gambar
            if($request->file('spk_approve')) {

                $file       = $request->file('spk_approve');
                $fileName   = $file->getClientOriginalName();
                $fileName   = str_replace(' ', '', $fileName);

                // Define a file path
                $filePath = 'uploads/' . uniqid() . '_' . $fileName;

                // Store the file in the local storage
                $upload = Storage::disk('public')->put($filePath, file_get_contents($file));
                if ($upload) {
                    $dataOrder['file_spk'] = $filePath;
                } 
            }

            Order::where('uid', $id)->update($dataOrder);
            $this->logs($id, $step->uid_divisi, 1);

            DB::commit();
            return $this->ajaxResponse(true, 'Approve data berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->ajaxResponse(false, 'Approve data gagal', $e);
        }
    }

    public function pending_desain(Request $request)
    {

        // validation
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
            OrderDetail::where([['uid_order', $id], ['uid_divisi', $order->uid_divisi]])->update(['status' => 3, 'keterangan' => $ket, 'approve_at' => Carbon::now(), 'approve_by' => $user->username]);

            DB::commit();
            return $this->ajaxResponse(true, 'Pending data berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->ajaxResponse(false, 'Pending data gagal', $e);
        }
    }

    public function cetak_desain(Request $request) 
    {
        $id     = $request->id;
        $data   = $this->desain->cetakDesain($id);

        $pdf = PDF::loadView('job.cetak', compact('data'));
        return $pdf->stream('job.pdf');
    }
}
