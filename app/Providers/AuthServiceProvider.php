<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        #==================== Gate CRUD ====================
        Gate::define('crudAccess', function ($user, $id_menu) {
            $db = DB::table('akses_role as ar')
                    ->join('users as u', 'ar.id_role', '=', 'u.id_role')
                    ->where([['ar.flag_access', 1], ['u.id_role', $user->id_role], ['ar.kode_menu', $id_menu]])
                    ->select('ar.kode_menu')->first();
            return $db;
        });

        #====================  Menu ====================
        Gate::define('Menu', function ($user, $kd_parent) {
            $db = DB::table('akses_role as ar')
                    ->join('users as u', 'ar.id_role', '=', 'u.id_role')
                    ->join('menu as m', 'ar.kode_menu', '=', 'm.kode')
                    ->where([['ar.flag_access', '<>', 9], ['u.id_role', $user->id_role], ['m.parent', 0], ['ar.kode_menu', $kd_parent]])
                    ->select('ar.kode_menu')
                    ->first();
            return $db;
        });

        #==================== Sub Menu ====================
        Gate::define('SubMenu', function ($user, $code_menu) {
            $db = DB::table('akses_role as ar')
                    ->join('users as u', 'ar.id_role', '=', 'u.id_role')
                    ->where([['u.id_role', $user->id_role], ['ar.kode_menu', $code_menu], ['ar.flag_access', '<>', 9]])
                    ->select('ar.kode_menu')->first();
            return $db;
        });
    }
}
