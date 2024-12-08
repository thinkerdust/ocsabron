<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\DesainController;
use App\Http\Controllers\BahanController;

Route::get('/', function () { return redirect('login'); });

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::group(['middleware' => ['web', 'auth']], function () {

    Route::get('log-viewer', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/change-password', [AuthController::class, 'change_password']);
    Route::post('/process-change-password', [AuthController::class, 'process_change_password'])->middleware('ajax-request');

    // Get Data Master
    Route::middleware('ajax-request')->group(function() {
        Route::controller(MasterController::class)->group(function () {
            Route::get('/data-role', 'list_data_role');
            Route::get('/data-divisi', 'list_data_divisi');
        });
    });

    // User Management
    Route::middleware("can:Menu, 'UM'")->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/reset-password/{id}', 'reset_password')->middleware('ajax-request');
        });

        Route::group(['prefix' => 'user-management'], function () {
            Route::controller(UserManagementController::class)->group(function () {
                // user
                Route::get('/', 'index')->middleware("can:SubMenu, 'UM1'");
                Route::get('/datatable-user-management', 'datatable_user_management');
                Route::post('/register', 'register')->name('register');
                Route::get('/edit-user/{id}', 'edit_user');
                Route::get('/delete-user/{id}', 'delete_user');

                // menu
                Route::get('/menu', 'menu')->middleware("can:SubMenu, 'UM2'");
                Route::get('/datatable-menu', 'datatable_menu');
                Route::post('/store-menu', 'store_menu');
                Route::get('/edit-menu/{id}', 'edit_menu');
                Route::get('/delete-menu/{id}', 'delete_menu');

                // role
                Route::get('/role', 'role')->middleware("can:SubMenu, 'UM3'");
                Route::get('/datatable-role', 'datatable_role');
                Route::get('/list-permissions-menu', 'list_permissions_menu')->middleware('ajax-request');
                Route::post('/store-role', 'store_role');
                Route::get('/edit-role/{id}', 'edit_role');
                Route::get('/delete-role/{id}', 'delete_role');
            });
        });

    });

    // Job
    Route::group(['prefix' => 'job', 'middleware' => ["can:Menu, 'JOB'"]], function () {
        Route::controller(JobController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_job');
            Route::get('/form/{id?}', 'form_job');
            Route::get('/detail/{id}', 'detail_job');
            Route::get('/edit/{id}', 'edit_job');
            Route::get('/delete/{id}', 'delete_job');
            Route::post('/store', 'store_job');
            Route::get('/approve/{id}', 'approve_job');
            Route::get('/pending/{id}', 'pending_job');
            Route::get('/cetak/{id}', 'cetak_job');
        });
    });

    // Desain
    Route::group(['prefix' => 'desain', 'middleware' => ["can:Menu, 'DSN'"]], function () {
        Route::controller(DesainController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_desain');
            Route::get('/detail/{id}', 'detail_desain');
            Route::get('/generate', 'generate_spk');
            Route::post('/process-generate', 'process_generate_spk');
            Route::post('/approve', 'approve_desain');
            Route::post('/pending', 'pending_desain');
            Route::get('/cetak/{id}', 'cetak_desain');
        });
    });

    // Bahan
    Route::group(['prefix' => 'bahan', 'middleware' => ["can:Menu, 'BAHAN'"]], function () {
        Route::controller(BahanController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_bahan');
            Route::get('/approve/{id}', 'approve_bahan');
            Route::get('/pending/{id}', 'pending_bahan');
        });
    });
});