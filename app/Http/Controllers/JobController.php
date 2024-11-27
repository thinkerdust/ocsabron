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
use App\Repositories\JobRepository;
use Carbon\Carbon;
use Svg\Tag\Rect;
use Yajra\DataTables\DataTables;
use PDF;

class JobController extends BaseController
{
    protected $jobrepo;
    protected $order;
    protected $order_detail;

    function __construct(JobRepository $jobrepo, Order $order, OrderDetail $order_detail)
    {
        $this->job          = $jobrepo;
        $this->order        = $order;
        $this->order_detail = $order_detail;
    }

    public function index()
    {
        $title  = 'Create Job';
        $js     = 'js/apps/job/index.js?_='.rand();
        return view('job.index', compact('js', 'title'));
    }

    public function datatable_job(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;
        $status     = $request->status;

        $data = $this->job->dataTableJob($start_date, $end_date, $status); 
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
                                        <li><a class="btn" onclick="detail(\'' . $row->uid . '\')"><em class="icon ni ni-eye"></em><span>Detail</span></a></li>
                                        <li><a href="/job/form/'.$row->uid.'" class="btn"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                        <li><a class="btn" onclick="hapus(\'' . $row->uid . '\')"><em class="icon ni ni-trash"></em><span>Hapus</span></a></li>
                                        '.$btn_action.'
                                        <li><a href="/job/cetak/'.$row->uid.'" target="_blank" class="btn"><em class="icon ni ni-file-pdf"></em><span>Cetak</span></a></li>
                                    </ul>
                                </div>
                            </div>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function form_job(Request $request)
    {
        $title      = 'Form Job';
        $id         = $request->id;
        $js         = 'js/apps/job/form.js?_='.rand();
        $divisi     = DB::table('divisi')->where([['status', 1], ['urutan', '<>', 0]])->orderBy('urutan', 'asc')->get();

        return view('job.form', compact('title', 'js', 'id', 'divisi'));
    }

    public function detail_job(Request $request) 
    {
        $id     = $request->id;
        $user   = $this->order->getOrder($id);

        return $this->ajaxResponse(true, 'Success!', $user);
    }

    public function edit_job(Request $request) 
    {
        $id     = $request->id;
        $user   = $this->job->editJob($id);

        return $this->ajaxResponse(true, 'Success!', $user);
    }

    public function delete_job(Request $request)
    {
        $id     = $request->id;
        $user   = Auth::user();

        try {
            DB::beginTransaction();

            DB::table('order')->where('uid', $id)->update(['status' => 0, 'update_at' => Carbon::now(), 'update_by' => $user->username]);
            DB::table('order_detail')->where('uid_order', $id)->update(['status' => 0, 'update_at' => Carbon::now(), 'update_by' => $user->username]);

            DB::commit();
            return $this->ajaxResponse(true, 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->ajaxResponse(false, 'Data gagal dihapus', $e);
        }
    }

    public function store_job(Request $request)
    {
        $id = $request->input('id');

        $validator = Validator::make($request->all(), [
            'nama'          => 'required|max:100',
            'tanggal'       => 'required',
            'deadline'      => 'required',
            'jenis_produk'  => 'required',
            'jenis_kertas'  => 'required',
            'jumlah'        => 'required',
            'ukuran'        => 'required',
            'finishing_satu'=> 'required',
            'finishing_dua' => 'required',
            'pengambilan'   => 'required',
            'order_by'      => 'required',
        ], validation_message());

        if($validator->stopOnFirstFailure()->fails()){
            return $this->ajaxResponse(false, $validator->errors()->first());        
        }

        $user = Auth::user();

        try {
            DB::beginTransaction();

            $data = [
                'nama'          => $request->nama,
                'tanggal'       => Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d'),
                'deadline'      => Carbon::createFromFormat('d/m/Y', $request->deadline)->format('Y-m-d'),
                'jenis_produk'  => $request->jenis_produk,
                'jenis_kertas'  => $request->jenis_kertas,
                'tambahan'      => $request->tambahan,
                'jumlah'        => $request->jumlah,
                'ukuran'        => $request->ukuran,
                'finishing_satu'=> $request->finishing_satu,
                'finishing_dua' => $request->finishing_dua,
                'pengambilan'   => $request->pengambilan,
                'order_by'      => $request->order_by,
                'keterangan'    => $request->keterangan,
            ];

            // insert order
            if(!empty($id)) {
                $data['update_at']  = Carbon::now();
                $data['update_by']  = $user->username;
            } else {
                $id                 = 'O'.Carbon::now()->format('YmdHisu');
                $data['uid']        = $id;
                $data['insert_at']  = Carbon::now();
                $data['insert_by']  = $user->username;
            }

            DB::table('order')->updateOrInsert(
                ['uid' => $id],
                $data
            );

            $this->logs($id, 'D20241117144239748170');

            // reset order detail
            DB::table('order_detail')->where('uid_order', $id)->delete();

            // insert order detail
            $data_detail = [];

            // new job
            $data_detail[] = [
                'uid'           => 'OD'.Carbon::now()->format('YmdHisu'),
                'uid_order'     => $id,
                'uid_divisi'    => 'D20241117144239748170',
                'insert_at'     => Carbon::now(),
                'insert_by'     => $user->username,
            ];

            foreach ($request->divisi as $divisi) {
                $data_detail[] = [
                    'uid'           => 'OD'.Carbon::now()->format('YmdHisu'),
                    'uid_order'     => $id,
                    'uid_divisi'    => $divisi,
                    'insert_at'     => Carbon::now(),
                    'insert_by'     => $user->username,
                ];
            }
            DB::table('order_detail')->insert($data_detail);

            DB::commit();
            return $this->ajaxResponse(true, 'Data berhasil disimpan');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->ajaxResponse(false, 'Data gagal disimpan', $e);
        }
    }

    public function approve_job(Request $request)
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

    public function pending_job(Request $request)
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

    public function cetak_job(Request $request) 
    {
        $id     = $request->id;
        $data   = $this->job->cetakJob($id);

        $pdf = PDF::loadView('job.cetak', compact('data'));
        return $pdf->stream('job.pdf');
    }
}
