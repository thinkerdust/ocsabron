<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdministrasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\CetakController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesainController;
use App\Http\Controllers\EkspedisiController;
use App\Http\Controllers\FinishingDuaController;
use App\Http\Controllers\FinishingSatuController;
use App\Http\Controllers\FormingController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\PackingController;
use App\Http\Controllers\PONController;
use App\Http\Controllers\TambahanController;
use App\Http\Controllers\UserManagementController;

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

    // Job
    Route::group(['prefix' => 'job', 'middleware' => ["can:Menu, 'JOB'"]], function () {
        Route::controller(JobController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_job');
            Route::get('/form/{id?}', 'form_job');
            Route::get('/edit/{id}', 'edit_job');
            Route::get('/delete/{id}', 'delete_job');
            Route::post('/store', 'store_job');
            Route::get('/approve/{id}', 'approve_job');
            Route::get('/pending/{id}', 'pending_job');
            Route::get('/detail/{id}', 'detail_job');
            Route::post('/detail/datatable', 'datatable_detail_job');
        });
    });

    // Desain
    Route::group(['prefix' => 'desain', 'middleware' => ["can:Menu, 'DSN'"]], function () {
        Route::controller(DesainController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_desain');
            Route::post('/approve', 'approve_desain');
            Route::post('/pending', 'pending_desain');
            Route::get('/detail/{id}', 'detail_desain');
            Route::post('/detail/datatable', 'datatable_detail_desain');
            Route::get('/generate', 'generate_spk');
            Route::post('/process-generate', 'process_generate_spk');
            Route::post('/datatable-incoming', 'datatable_incoming_job');
        });
    });

    // Bahan
    Route::group(['prefix' => 'bahan', 'middleware' => ["can:Menu, 'BAHAN'"]], function () {
        Route::controller(BahanController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_bahan');
            Route::post('/approve', 'approve_bahan');
            Route::post('/pending', 'pending_bahan');
            Route::get('/detail/{id}', 'detail_bahan');
            Route::post('/detail/datatable', 'datatable_detail_bahan');
            Route::post('/datatable-incoming', 'datatable_incoming_job');
        });
    });

    // Cetak
    Route::group(['prefix' => 'cetak', 'middleware' => ["can:Menu, 'CETAK'"]], function () {
        Route::controller(CetakController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_cetak');
            Route::post('/approve', 'approve_cetak');
            Route::post('/pending', 'pending_cetak');
            Route::get('/detail/{id}', 'detail_cetak');
            Route::post('/detail/datatable', 'datatable_detail_cetak');
            Route::post('/datatable-incoming', 'datatable_incoming_job');
        });
    });

    // Finishing Satu
    Route::group(['prefix' => 'finishing-satu', 'middleware' => ["can:Menu, 'FS1'"]], function () {
        Route::controller(FinishingSatuController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_finishing_satu');
            Route::post('/approve', 'approve_finishing_satu');
            Route::post('/pending', 'pending_finishing_satu');
            Route::get('/detail/{id}', 'detail_finishing_satu');
            Route::post('/detail/datatable', 'datatable_detail_finishing_satu');
            Route::post('/datatable-incoming', 'datatable_incoming_job');
        });
    });

    // PON
    Route::group(['prefix' => 'pon', 'middleware' => ["can:Menu, 'PON'"]], function () {
        Route::controller(PONController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_pon');
            Route::post('/approve', 'approve_pon');
            Route::post('/pending', 'pending_pon');
            Route::get('/detail/{id}', 'detail_pon');
            Route::post('/detail/datatable', 'datatable_detail_pon');
            Route::post('/datatable-incoming', 'datatable_incoming_job');
        });
    });

    // Finishing Dua
    Route::group(['prefix' => 'finishing-dua', 'middleware' => ["can:Menu, 'FS2'"]], function () {
        Route::controller(FinishingDuaController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_finishing_dua');
            Route::post('/approve', 'approve_finishing_dua');
            Route::post('/pending', 'pending_finishing_dua');
            Route::get('/detail/{id}', 'detail_finishing_dua');
            Route::post('/detail/datatable', 'datatable_detail_finishing_dua');
            Route::post('/datatable-incoming', 'datatable_incoming_job');
        });
    });

    // Forming
    Route::group(['prefix' => 'forming', 'middleware' => ["can:Menu, 'FORM'"]], function () {
        Route::controller(FormingController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_forming');
            Route::post('/approve', 'approve_forming');
            Route::post('/pending', 'pending_forming');
            Route::get('/detail/{id}', 'detail_forming');
            Route::post('/detail/datatable', 'datatable_detail_forming');
            Route::post('/datatable-incoming', 'datatable_incoming_job');
        });
    });

    // Packing
    Route::group(['prefix' => 'packing', 'middleware' => ["can:Menu, 'PACK'"]], function () {
        Route::controller(PackingController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_packing');
            Route::post('/approve', 'approve_packing');
            Route::post('/generate-label', 'generate_label_packing');
            Route::post('/pending', 'pending_packing');
            Route::get('/detail/{id}', 'detail_packing');
            Route::post('/detail/datatable', 'datatable_detail_packing');
            Route::post('/datatable-incoming', 'datatable_incoming_job');
        });
    });

    // Administrasi
    Route::group(['prefix' => 'administrasi', 'middleware' => ["can:Menu, 'ADM'"]], function () {
        Route::controller(AdministrasiController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_administrasi');
            Route::post('/approve', 'approve_administrasi');
            Route::post('/pending', 'pending_administrasi');
            Route::get('/detail/{id}', 'detail_administrasi');
            Route::post('/detail/datatable', 'datatable_detail_administrasi');
            Route::post('/datatable-incoming', 'datatable_incoming_job');
        });
    });

    // Tambahan
    Route::group(['prefix' => 'tambahan', 'middleware' => ["can:Menu, 'PACK1'"]], function () {
        Route::controller(TambahanController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_tambahan');
            Route::post('/approve', 'approve_tambahan');
            Route::post('/generate-label', 'generate_label_tambahan');
            Route::post('/pending', 'pending_tambahan');
            Route::get('/detail/{id}', 'detail_tambahan');
            Route::post('/detail/datatable', 'datatable_detail_tambahan');
            Route::post('/datatable-incoming', 'datatable_incoming_job');
        });
    });

    // Ekspedisi
    Route::group(['prefix' => 'ekspedisi', 'middleware' => ["can:Menu, 'EPD'"]], function () {
        Route::controller(EkspedisiController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_ekspedisi');
            Route::post('/approve', 'approve_ekspedisi');
            Route::post('/pending', 'pending_ekspedisi');
            Route::get('/detail/{id}', 'detail_ekspedisi');
            Route::post('/detail/datatable', 'datatable_detail_ekspedisi');
            Route::post('/datatable-incoming', 'datatable_incoming_job');
        });
    });

    // Monitoring
    Route::group(['prefix' => 'monitoring', 'middleware' => ["can:Menu, 'MR'"]], function () {
        Route::controller(MonitoringController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_monitoring');
        });
    });

    // History
    Route::group(['prefix' => 'history', 'middleware' => ["can:Menu, 'HSTRY'"]], function () {
        Route::controller(HistoryController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/datatable', 'datatable_history');
            Route::get('/detail/{id}', 'detail_history');
            Route::post('/detail/datatable', 'datatable_detail_history');
        });
    });
});