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
use App\Models\Job;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class JobController extends BaseController
{
    function __construct()
    {
        $this->job = new Job();
    }

    public function index()
    {
        $title  = 'Job';
        $js     = 'js/apps//job/job.js?_='.rand();
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
                        if($row->status == 1) {
                            $btn = '<a href="/job/form/'.$row->uid.'" class="btn btn-dim btn-outline-secondary btn-sm"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                <a class="btn btn-dim btn-outline-danger btn-sm" onclick="hapus(\'' . $row->uid . '\')"><em class="icon ni ni-trash"></em><span>Hapus</span></a>
                                ';
                        }
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function form_job(Request $request)
    {
        $id         = $request->id;
        $js         = 'js/apps/job/form.js?_='.rand();
        $js_library = js_moment();

        $divisi = DB::table('divisi')->where('status', 1)->orderBy('urutan', 'asc')->get();

        return view('job.form', compact('js', 'id', 'divisi', 'js_library'));
    }

    public function edit_job(Request $request) 
    {
        $id     = $request->id;
        $user   = $this->job->editJob($id);

        return $this->ajaxResponse(true, 'Success!', $user);
    }

    public function delete_job(Request $request)
    {
        $id         = $request->id;
        $user       = Auth::user();

        try {
            DB::beginTransaction();

            // delete order
            DB::table('order')->where([
                ['uid', '=', $id]
            ])->delete();

            // delete order detail
            DB::table('order_detail')->where([
                ['order_header', '=', $id]
            ])->delete();

            DB::commit();
            return $this->ajaxResponse(true, 'Data berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return $this->ajaxResponse(false, 'Data gagal dihapus');
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

            $uid = 'O'.Carbon::now()->format('YmdHisu');

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
                'status'        => 1
            ];

            // insert order
            if(!empty($id)) {
                $data['update_at']  = Carbon::now();
                $data['update_by']  = $user->username;
            } else {
                $data['uid']        = $uid;
                $data['insert_at']  = Carbon::now();
                $data['insert_by']  = $user->username;
            }

            $process = DB::table('order')->updateOrInsert(
                ['uid' => $id],
                $data
            );

            // reset order detail
            DB::table('order_detail')->where([
                ['order_header', '=', $id]
            ])->delete();

            // insert order detail
            foreach ($request->divisi as $divisi) {
                $data_detail = [
                    'uid'           => 'OD'.Carbon::now()->format('YmdHisu'),
                    'divisi'        => $divisi,
                    'status'        => 1,
                    'insert_at'     => Carbon::now(),
                    'insert_by'     => $user->username,
                ];

                if(!empty($id)) {
                    $data_detail['order_header']   = $id;
                } else {
                    $data_detail['order_header']   = $uid;
                }
                
                DB::table('order_detail')->insert($data_detail);
            }

            DB::commit();
            return $this->ajaxResponse(true, 'Data berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            // $e->getMessage() nanti kirim log kalo sudah ada log-nya
            return $this->ajaxResponse(false, 'Data gagal disimpan');
        }

    }
}
