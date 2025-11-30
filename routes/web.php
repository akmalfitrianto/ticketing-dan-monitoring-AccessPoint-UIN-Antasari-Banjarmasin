<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AccessPointController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CampusMapController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Models\Building;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    //misal manajemen gedung, ruangan, accesspoint dipisah
    Route::get('/api/buildings/{building}/floors', [BuildingController::class, 'getFloors'])
        ->name('api.buildings.floors');

    // Campus Map & Floor Plan
    Route::get('/campus-map', [CampusMapController::class, 'index'])->name('campus.map');
    Route::get('/building/{building}', [CampusMapController::class, 'show'])->name('building.show');
    Route::get('/building/{building}/floor/{floor}', [CampusMapController::class, 'floor'])->name('building.floor');

    // Tickets - Available for both admin and superadmin
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('my');
        Route::get('/create/{accessPoint}', [TicketController::class, 'create'])->name('create');
        Route::post('/store', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');

        // Superadmin only - update status
        Route::middleware('superadmin')->group(function () {
            Route::put('/{ticket}/status', [TicketController::class, 'updateStatus'])->name('update.status');
            Route::put('/{ticket}/resolve', [TicketController::class, 'resolve'])->name('resolve');
            Route::put('/{ticket}/close', [TicketController::class, 'close'])->name('close');
        });
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read.all');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/read/clear', [NotificationController::class, 'destroyRead'])->name('destroy.read');
    });

    // Superadmin only routes
    Route::middleware('superadmin')->prefix('admin')->name('admin.')->group(function () {
        // Building Management
        Route::resource('buildings', BuildingController::class);
        Route::post('buildings/{building}/preview', [BuildingController::class, 'preview'])->name('buildings.preview');

        // Floor Management
        Route::resource('buildings.floors', FloorController::class)->shallow();

        // Room Management
        Route::get('rooms', [RoomController::class, 'indexAll'])->name('rooms.index');

        Route::resource('floors.rooms', RoomController::class)->shallow();
        Route::post('rooms/{room}/preview', [RoomController::class, 'preview'])->name('rooms.preview');

        // Access Point Management
        Route::resource('rooms.access-points', AccessPointController::class)->shallow();
        Route::put('access-points/{accessPoint}/status', [AccessPointController::class, 'updateStatus'])->name('access-points.status');

        // Admin Management
        Route::resource('admins', AdminController::class)->except(['show']);
    });

    Route::get('/test-email', function () {
        $ticket = \App\Models\Ticket::with([
            'accessPoint.room.floor.building',
            'admin'
        ])->latest()->first();

        if (!$ticket) {
            return 'Tidak ada ticket! Buat ticket dulu untuk testing.';
        }

        try {
            Mail::to('test@example.com')->send(new \App\Mail\TicketCreatedMail($ticket));
            return 'Email berhasil dikirim! Cek inbox di Mailtrap.';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    });

    Route::get('/test-real-email', function () {
        try {
            Mail::raw('Test email real!', function ($message) {
                $message->to('fitriantoakmal@gmail.com')
                    ->subject('Test dari Laravel');
            });
            return 'Email terkirim! Cek inbox Gmail Anda.';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    })->middleware('auth');
});
