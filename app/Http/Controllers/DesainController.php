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
        $title  = 'Desain';
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

                    $btn_action = '';
                    
                    if(Gate::allows('crudAccess', 'DSN', $row)) {
                        $user = Auth::user();
                        if(in_array($user->id_role, [1,2])) {
                            $btn_action .= '<li><a href="/desain/form/'.$row->uid.'" class="btn"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                        <li><a class="btn" onclick="hapus(\'' . $row->uid . '\')"><em class="icon ni ni-trash"></em><span>Hapus</span></a></li>
                                        <li><a class="btn" onclick="cancel(\'' . $row->uid . '\')"><em class="icon ni ni-undo"></em><span>Cancel</span></a></li>';
                        }
                        
                        $btn_approve    = '<li><a class="btn" onclick="approve(\'' . $row->uid . '\')"><em class="icon ni ni-check-round-cut"></em><span>Approve</span></a></li>';
                        $btn_pending    = '<li><a class="btn" onclick="pending(\'' . $row->uid . '\')"><em class="icon ni ni-na"></em><span>Pending</span></a></li>';
                        
                        if($row->status == 1) {
                            $btn_action .= $btn_approve.$btn_pending;
                        } elseif($row->status == 3) {
                            $btn_action .= $btn_approve;
                        }
                    }

                    $btn = '<div class="drodown">
                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a class="btn" onclick="detail(\'' . $row->uid . '\')"><em class="icon ni ni-eye"></em><span>Detail</span></a></li>
                                        '.$btn_action.'
                                    </ul>
                                </div>
                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function detail_desain(Request $request) 
    {
        $id     = $request->id;
        $user   = $this->order->getOrder($id);

        return $this->ajaxResponse(true, 'Success!', $user);
    }

    public function datatable_detail_desain(Request $request)
    {
        $uid    = $request->uid;
        $data   = $this->order->dataTableDetailOrder($uid); 
        return Datatables::of($data)->addIndexColumn()->make(true);
    }

    public function form_desain(Request $request)
    {
        $title      = 'Form Job';
        $id         = $request->id;
        $js         = 'js/apps/desain/form.js?_='.rand();
        $divisi     = DB::table('divisi')->where([['status', 1], ['urutan', '<>', 0]])->orderBy('urutan', 'asc')->get();

        return view('desain.form', compact('title', 'js', 'id', 'divisi'));
    }

    public function edit_desain(Request $request) 
    {
        $id     = $request->id;
        $user   = $this->order->getOrder($id);

        return $this->ajaxResponse(true, 'Success!', $user);
    }

    public function delete_desain(Request $request)
    {
        $id     = $request->id;
        $user   = Auth::user();

        try {
            DB::beginTransaction();

            DB::table('order')->where('uid', $id)->update(['status' => 0, 'update_at' => Carbon::now(), 'update_by' => $user->username]);

            DB::commit();
            return $this->ajaxResponse(true, 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->ajaxResponse(false, 'Data gagal dihapus', $e);
        }
    }

    public function store_desain(Request $request)
    {
        $id = $request->input('id');

        $validator = Validator::make($request->all(), [
            'nama'          => 'required|max:100',
            'customer'      => 'required',
            'tanggal'       => 'required',
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
            $jumlah = Str::replace('.', '', $request->jumlah);

            $data = [
                'nama'          => $request->nama,
                'customer'      => $request->customer,
                'tanggal'       => Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d'),
                'jenis_produk'  => $request->jenis_produk,
                'jenis_kertas'  => $request->jenis_kertas,
                'tambahan'      => $request->tambahan,
                'jumlah'        => $jumlah,
                'ukuran'        => $request->ukuran,
                'finishing_satu'=> $request->finishing_satu,
                'finishing_dua' => $request->finishing_dua,
                'pengambilan'   => $request->pengambilan,
                'order_by'      => $request->order_by,
                'keterangan'    => $request->keterangan,
            ];

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

            DB::commit();
            return $this->ajaxResponse(true, 'Data berhasil disimpan');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->ajaxResponse(false, 'Data gagal disimpan', $e);
        }
    }

    public function approve_desain(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl_acc_approve'       => 'required',
            'tanggal_deadline'      => 'required',
            'keterangan_approve'    => 'required',
            'upload_spk'            => 'required|mimes:pdf|max:2048'
        ], validation_message());

        if($validator->stopOnFirstFailure()->fails()){
            return $this->ajaxResponse(false, $validator->errors()->first());        
        }

        try {
            DB::beginTransaction();

            $id         = $request->post('uid_approve');
            $tgl_acc    = $request->post('tgl_acc_approve');
            $tgl_acc    = Carbon::createFromFormat('d/m/Y', $request->post('tgl_acc_approve'))->format('Y-m-d');
            $deadline   = $request->post('tanggal_deadline');
            $deadline   = Carbon::createFromFormat('d/m/Y', $request->post('tanggal_deadline'))->format('Y-m-d');
            $ket        = $request->post('keterangan_approve');
            $user       = Auth::user();

            $order = Order::where('uid', $id)->first();
            $this->logs($id, $order->uid_divisi, 2);
            OrderDetail::where([['uid_order', $id], ['uid_divisi', $order->uid_divisi]])->update(['status' => 2, 'keterangan' => $ket, 'approve_at' => Carbon::now(), 'approve_by' => $user->username]);

            $step = $this->order->getNextStep($id);

            $dataOrder = [
                'uid_divisi'        => $step->uid_divisi,
                'tanggal_approve'   => $tgl_acc,
                'deadline'          => $deadline,
                'update_at'         => Carbon::now(), 
                'update_by'         => $user->username
            ];

            // remove old file
            if(!empty($uid) && $request->file('upload_spk')) {
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
            if($request->file('upload_spk')) {

                $file       = $request->file('upload_spk');
                $fileName   = time() . '_SPK.pdf';

                // Define a file path
                $filePath = 'uploads/' . $fileName;

                // Store the file in the local storage
                $upload = Storage::disk('public')->put($filePath, file_get_contents($file));
                if ($upload) {
                    $dataOrder['file_spk'] = $fileName;
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

    public function generate_spk(Request $request) {

        $title          = 'Generate SPK';
        $js             = 'js/apps/desain/generate.js?_='.rand();
        $js_library     = js_moment().js_bs_datetimepicker();
        $css_library    = css_bs_datetimepicker();

        return view('desain.generate', compact('title', 'js', 'js_library', 'css_library'));
    }

    public function process_generate_spk(Request $request) {

        $data = [
            'nama_job'            => $request->post('nama'),
            'nama_customer'       => $request->post('customer'),
            'tgl_order'           => $request->post('tanggal_order'),
            'tgl_acc'             => $request->post('tanggal_acc'),
            'jam'                 => $request->post('jam'),
            'deadline'            => $request->post('deadline'),
            'pengambilan'         => $request->post('pengambilan'),
            'no_tanda_terima'     => $request->post('no_tanda_terima'),
            'operator'            => $request->post('operator'),
            'job_by'              => $request->post('job_by'),
            'bahan'               => $request->post('bahan'),
            'jenis_bahan'         => $request->post('jenis_bahan'),
            'foto_bahan'          => $request->file('foto_bahan'),
            'jumlah_bahan'        => $request->post('jumlah_bahan'),
            'jumlah_kertas'       => $request->post('jumlah_kertas'),
            'dipotong_jadi'       => $request->post('dipotong_jadi'),
            'ukuran_jadi'         => $request->post('ukuran'),
            'ukuran_file'         => $request->post('ukuran_file'),
            'cetakan'             => $request->post('cetakan'),
            'struk'               => $request->post('struk'),
            'mesin'               => $request->post('mesin') ?? [],
            'set'                 => $request->post('set'),
            'model_cetak'         => $request->post('model_cetak'),
            'order'               => $request->post('order'),
            'insheet'             => $request->post('insheet'),
            'jumlah_plat'         => $request->post('jumlah_plat'),
            'keterangan'          => $request->post('keterangan'),
            'jenis_laminasi'      => $request->post('jenis_laminasi'),
            'tipe_laminasi'       => $request->post('tipe_laminasi'),
            'keterangan_laminasi' => $request->post('keterangan_laminasi'),
            'foil_warna'          => $request->post('foil_warna'),
            'keterangan_foil'     => $request->post('keterangan_foil'),
            'keterangan_mika'     => $request->post('keterangan_mika'),
            'keterangan_lem'      => $request->post('keterangan_lem'),
            'keterangan_lipat'    => $request->post('keterangan_lipat'),
            'keterangan_lain'     => $request->post('keterangan_lain'),
        ];    
                
        $pdf = PDF::loadView('desain.cetak', compact('data'));
        return $pdf->stream('spk.pdf');
    }

    public function datatable_incoming_job(Request $request)
    {
        $data = $this->desain->dataTableIncomingJob(); 
        return Datatables::of($data)->addIndexColumn()->make(true);
    }

    public function cancel_job(Request $request)
    {
        
        try {
            DB::beginTransaction();
            
            $id     = $request->id;
            $user   = Auth::user();

            $order = Order::where('uid', $id)->first();
            $this->logs($id, $order->uid_divisi, 4);

            $step = $this->order->getBackStep($id);

            OrderDetail::where([['uid_order', $id], ['uid_divisi', $step->uid_divisi]])->update(['status' => 1, 'update_at' => Carbon::now(), 'update_by' => $user->username]);

            Order::where('uid', $id)->update([
                'uid_divisi'    => $step->uid_divisi,
                'update_at'     => Carbon::now(), 
                'update_by'     => $user->username
            ]);

            $this->logs($id, $step->uid_divisi, 1);
            
            DB::commit();
            return $this->ajaxResponse(true, 'Back process berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->ajaxResponse(false, 'Back process gagal', $e);
        }
    }
}
