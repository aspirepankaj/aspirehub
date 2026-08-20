<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', function () {
    $user = auth()->user();
    if ($user && $user->admin && $user->admin->is_active) {
        return redirect()->route('admin.dashboard');
    }
    if ($user && $user->staff && $user->staff->status === 'active') {
        return redirect()->route('staff.dashboard');
    }
    return redirect()->route('client.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('profile', function () {
    $user = auth()->user();
    if ($user && $user->admin && $user->admin->is_active) {
        return redirect()->route('admin.profile');
    }
    if ($user && $user->staff && $user->staff->status === 'active') {
        return redirect()->route('staff.profile');
    }
    return view('profile');
})->middleware(['auth'])->name('profile');

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/impersonate/stop', [App\Http\Controllers\ImpersonateController::class, 'stop'])->name('impersonate.stop');
    Route::get('/impersonate/{userId}', [App\Http\Controllers\ImpersonateController::class, 'start'])->name('impersonate.start');
});

Route::get('storage/{path}', function ($path) {
    if (!auth()->check()) {
        abort(403, 'Unauthorized. Please log in to view this file.');
    }
    
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    
    return response()->file($fullPath);
})->where('path', '.*');
