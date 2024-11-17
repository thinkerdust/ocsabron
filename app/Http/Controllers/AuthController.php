<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Menu;
use Validator;

class AuthController extends BaseController
{
    function __construct()
    {
        $this->user = new User();
        $this->menu = new Menu();
    }

    public function login()
    {
        $js = 'js/apps/auth/login.js?_='.rand();
        return view('auth/login', compact('js'));
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ], validation_message());
   
        if($validator->stopOnFirstFailure()->fails()){
            return $this->ajaxResponse(false, $validator->errors()->first());       
        }

        $credential = $request->only('username', 'password');
        $credential['status'] = 1;
        if(Auth::attempt($credential)){
            $menu = $this->menu->menu();
            $request->session()->push('menu', $menu);

            return $this->ajaxResponse(true, 'Berhasil login');
        }else{
            return $this->ajaxResponse(false, 'Username atau password salah. Silahkan cek kembali.');
        }

    }

    public function change_password()
    {
        $title = 'Update Password';
        $js = 'js/apps/auth/change-password.js?_='.rand();
        return view('auth/change_password', compact('js','title'));
    }

    public function process_change_password(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                'current_password' => 'required',
                'new_password'     => 'required|min:5|max:255',
                'confirm_password'  => 'required|same:new_password',
            ],
            validation_message()
        );

        if($validator->stopOnFirstFailure()->fails()){
            return $this->ajaxResponse(false, $validator->errors()->first());        
        }

        $auth = Auth::user();
        if(Hash::check($request->current_password, auth()->user()->password)){ 
            $process = User::find(auth()->user()->id)->update(['password'=> bcrypt($request->new_password), 'updated_by' => $auth->username]);
            if($process) {
                return $this->ajaxResponse(true, 'Password berhasil di update');
            }else{
                return $this->ajaxResponse(true, 'Password gagal di update');
            }
        }else{ 
            return $this->ajaxResponse(false, 'Password anda salah');
        }
    }

    public function reset_password(Request $request)
    {
        $id = $request->id;
        $auth = Auth::user();
        $user = User::where('id', $id)->update(['password'=> Hash::make('ocsabron.com'), 'updated_by' => $auth->username]);
        
        if($user) {
            return $this->ajaxResponse(true, 'Reset password berhasil');
        }else{
            return $this->ajaxResponse(false, 'Reset password gagal');
        }
    }
}
