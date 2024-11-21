<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Menu;
use App\Models\Role;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class UserManagementController extends BaseController
{
    function __construct()
    {
        $this->user = new User();
        $this->menu = new Menu();
        $this->role = new Role();
    }

    public function index()
    {
        $title = 'User Management';
        $js = 'js/apps/user-management/user.js?_='.rand();
        return view('user-management.user', compact('js', 'title'));
    }

    public function datatable_user_management(Request $request)
    {
        $data = $this->user->dataTableUser(); 
        return Datatables::of($data)->addIndexColumn()->make(true);
    }

    public function register(Request $request)
    {
        $id = $request->id_user;

        $validator = Validator::make($request->all(), [
            'username' => 'required|max:50|unique:users, "username",'.$id,
            'nama'=> 'required',
            'email' => 'required|email:rfc,dns|unique:users, "email",'.$id,
            'role' => 'required_if:id_user, null'
        ], validation_message());
   
        if($validator->stopOnFirstFailure()->fails()){
            return $this->ajaxResponse(false, $validator->errors()->first());       
        }

        $auth = Auth::user();

        $data_user = [
            'id' => $id,
            'username' => Str::lower(str_replace(' ','',$request->username)),
            'name' => $request->nama,
            'email' => $request->email,
            'id_role' => $request->role,
            'created_by' => $auth->username,
            'updated_by' => $auth->username,
        ];

        if(empty($id)) {
            $data_user['password'] = Hash::make('ocsabron.com');
        }

        $user = User::updateOrCreate(['id' => $id], $data_user);

        if($user) {
            return $this->ajaxResponse(true, 'Data berhasil disimpan');
        }else{
            return $this->ajaxResponse(false, 'Data gagal disimpan');
        } 
    }

    public function edit_user(Request $request) 
    {
        $id = $request->id;
        $user = $this->user->editUser($id);
        return $this->ajaxResponse(true, 'Success!', $user);
    }

    public function delete_user(Request $request)
    {
        $id = $request->id;
        $user = Auth::user();
        $process = User::where('id', $id)->update(['status' => 0, 'updated_by' => $user->username]);

        if($process) {
            return $this->ajaxResponse(true, 'Data berhasil dihapus');
        }else{
            return $this->ajaxResponse(false, 'Data gagal dihapus');
        }
    }

    public function menu()
    {
        $title = 'Menu Management';
        $js = 'js/apps/user-management/menu.js?_='.rand();
        return view('user-management.menu', compact('js', 'title'));
    }

    public function datatable_menu()
    {
        $data = $this->menu->dataTableMenu();
        return Datatables::of($data)->addIndexColumn()->make(true);
    }

    public function store_menu(Request $request)
    {
        $id = $request->input('id_menu');

        $validator = Validator::make($request->all(), [
            'menu' => 'required|max:100|unique:menu, "nama",'.$id,
            'parent' => 'required|max:5',
            'kode' => 'required|max:5|unique:menu, "kode",'.$id,
            'icon' => 'required_if:parent,0',
        ], validation_message());

        if($validator->stopOnFirstFailure()->fails()){
            return $this->ajaxResponse(false, $validator->errors()->first());        
        }

        $user = Auth::user();

        $data = [
            'nama' => $request->menu,
            'icon' => $request->icon,
            'parent' => $request->parent,
            'kode' => $request->kode,
            'flag_level' => $user->id_level,
            'url' => $request->url,
        ];

        if(!empty($id)) {
            $data['update_at'] = Carbon::now();
            $data['update_by'] = $user->username;
        }else{
            $data['insert_at'] = Carbon::now();
            $data['insert_by'] = $user->username;
        }

        $process = Menu::updateOrCreate(['id' => $id], $data);

        if($process) {
            return $this->ajaxResponse(true, 'Data berhasil disimpan');
        }else{
            return $this->ajaxResponse(false, 'Data gagal disimpan');
        }
    }

    public function edit_menu(Request $request) 
    {
        $id = $request->id;
        $data = Menu::where('id', $id)->first();
        return $this->ajaxResponse(true, 'Success!', $data);
    }

    public function delete_menu(Request $request)
    {
        $id = $request->id;
        $user = Auth::user();
        $process = Menu::where('id', $id)->update(['status' => 0, 'update_at' => Carbon::now(), 'update_by' => $user->username]);

        if($process) {
            return $this->ajaxResponse(true, 'Data berhasil dihapus');
        }else{
            return $this->ajaxResponse(false, 'Data gagal dihapus');
        }
    }

    public function list_permissions_menu(Request $request)
    {
        $id = $request->get('id');
        $role = Role::where('id', $id)->first();
        $code_role = !empty($role->id) ? $role->id : 0;

        $menu = $this->menu->viewMenuTemplate('0', '0', $code_role);

        $arr = [
            'menu' => $menu,
        ];

        return response()->json($arr);
    }

    public function role()
    {
        $title = 'Role Management';
        $css_library = css_tree();
        $js_library = js_tree();
        $js = 'js/apps/user-management/role.js?_='.rand();
        return view('user-management.role', compact('js', 'js_library', 'css_library', 'title'));
    }

    public function datatable_role()
    {
        $data = $this->role->dataTableRole();
        return Datatables::of($data)->addIndexColumn()->make(true);
    }

    public function store_role(Request $request)
    {
        $id = $request->post('id_role');
        $nama = Str::lower($request->post('role'));
        $flag_access = $request->except('role', 'id_role', '_token');

        $validator = Validator::make($request->all(), [
            'role' => 'required|max:100|unique:role, "nama",'.$id,
        ],validation_message());

        if($validator->stopOnFirstFailure()->fails()){
            return $this->ajaxResponse(false, $validator->errors()->first());        
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();

            $data_role = [
                'nama' => $nama,
            ];

            if(!empty($id)) {
                $data_role['update_at'] = Carbon::now();
                $data_role['update_by'] = $user->username;
            }else{
                $data_role['insert_at'] = Carbon::now();
                $data_role['insert_by'] = $user->username;
            }

            Role::updateOrCreate(['id' => $id], $data_role);

            $role = Role::where('nama', $nama)->first();

            $data_akses = [];

            foreach ($flag_access as $key => $value) {
                $arr_akses = array(
                    'id_role' => $role->id,
                    'kode_menu' => $key,
                    'flag_access' => isset($value) ? $value : 0,
                );

                $akses_role = DB::table('akses_role')->where(['id_role' => $role->id, 'kode_menu' => $key])->first();
                if(!empty($akses_role)){
                    $arr_akses['id'] = $akses_role->id;
                    $arr_akses['insert_at'] = $akses_role->insert_at;
                    $arr_akses['insert_by'] = $akses_role->insert_by;
                    $arr_akses['update_at'] = Carbon::now();
                    $arr_akses['update_by'] = $user->username;
                }else{
                    $arr_akses['id'] = null;
                    $arr_akses['insert_at'] = Carbon::now();
                    $arr_akses['insert_by'] = $user->username;
                    $arr_akses['update_at'] = null;
                    $arr_akses['update_by'] = null;
                }

                $data_akses[] = $arr_akses;
            }

            DB::table('akses_role')->upsert(
                $data_akses,
                ['id'],
                ['id_role', 'kode_menu', 'flag_access' ]
            );

            DB::commit();
            return $this->ajaxResponse(true, 'Data berhasil disimpan');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->ajaxResponse(false, 'Data gagal disimpan', $e);
        }
    }

    public function edit_role(Request $request) 
    {
        $id = $request->id;
        $data = Role::where('id', $id)->first();
        return $this->ajaxResponse(true, 'Success!', $data);
    }

    public function delete_role(Request $request)
    {
        $id = $request->id;
        $user = Auth::user();
        $process = Role::where('id', $id)->update(['status' => 0, 'update_at' => Carbon::now(), 'update_by' => $user->username]);

        if($process) {
            return $this->ajaxResponse(true, 'Data berhasil dihapus');
        }else{
            return $this->ajaxResponse(false, 'Data gagal dihapus');
        }
    }
}
