<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Repositories\JobRepository;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class JobController extends BaseController
{
    protected $jobrepo;

    function __construct(JobRepository $jobrepo)
    {
        $this->job = $jobrepo;
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

        $data = $this->job->dataTableJob($start_date, $end_date); 
        return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '';
                    if(Gate::allows('crudAccess', 'JOB', $row)) {
                        $btn = '<div class="drodown">
                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <ul class="link-list-opt no-bdr">
                                    <li><a href="/job/form/'.$row->uid.'" class="btn"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                    <li><a class="btn" onclick="hapus(\'' . $row->uid . '\')"><em class="icon ni ni-trash"></em><span>Hapus</span></a></li>
                                    <li><a class="btn" onclick="pending(\'' . $row->uid . '\')"><em class="icon ni ni-na"></em><span>Pending</span></a></li>
                                    <li><a class="btn" onclick="approve(\'' . $row->uid . '\')"><em class="icon ni ni-check-round-cut"></em><span>Approve</span></a></li>
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

        $divisi = DB::table('divisi')->where([['status', 1], ['urutan', '<>', 0]])->orderBy('urutan', 'asc')->get();

        return view('job.form', compact('title', 'js', 'id', 'divisi'));
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

            // delete order
            DB::table('order')->where('uid', $id)->update(['status' => 0, 'update_at' => Carbon::now(), 'update_by' => $user->username]);

            // delete order detail
            DB::table('order_detail')->where('uid_order', $id)->update(['status' => 0, 'update_at' => Carbon::now(), 'update_by' => $user->username]);

            DB::commit();
            return $this->ajaxResponse(true, 'Data berhasil dihapus');
        } catch (\Exception $e) {
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

            // reset order detail
            DB::table('order_detail')->where('uid_order', $id)->delete();

            // insert order detail
            $data_detail = [];

            // new job
            $data_detail[] = [
                'uid'           => 'OD'.Carbon::now()->format('YmdHisu'),
                'uid_order'     => $id,
                'uid_divisi'        => 'D20241117144239748170',
                'insert_at'     => Carbon::now(),
                'insert_by'     => $user->username,
            ];

            foreach ($request->divisi as $divisi) {
                $data_detail[] = [
                    'uid'           => 'OD'.Carbon::now()->format('YmdHisu'),
                    'uid_order'     => $id,
                    'uid_divisi'        => $divisi,
                    'insert_at'     => Carbon::now(),
                    'insert_by'     => $user->username,
                ];
            }
            DB::table('order_detail')->insert($data_detail);

            DB::commit();
            return $this->ajaxResponse(true, 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->ajaxResponse(false, 'Data gagal disimpan', $e);
        }

    }
}
