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
    
    $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    $customMimes = [
        'mp4'  => 'video/mp4',
        'webm' => 'video/webm',
        'mov'  => 'video/quicktime',
        'm4v'  => 'video/mp4',
        'ogg'  => 'video/ogg',
        'ogv'  => 'video/ogg',
        'avi'  => 'video/x-msvideo',
        'mkv'  => 'video/x-matroska',
        'flv'  => 'video/x-flv',
        'wmv'  => 'video/x-ms-wmv',
        'mp3'  => 'audio/mpeg',
        'wav'  => 'audio/wav',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'webp' => 'image/webp',
        'svg'  => 'image/svg+xml',
        'pdf'  => 'application/pdf',
    ];

    $mimeType = $customMimes[$extension] ?? (mime_content_type($fullPath) ?: 'application/octet-stream');

    $headers = [
        'Content-Type'        => $mimeType,
        'Content-Disposition' => 'inline; filename="' . basename($fullPath) . '"',
        'Accept-Ranges'       => 'bytes',
    ];

    return response()->file($fullPath, $headers);
})->where('path', '.*');

use App\Modules\CRM\Clients\Controllers\GoogleIntegrationController;

Route::get('/admin/integrations/google/callback', [GoogleIntegrationController::class, 'callback'])
    ->middleware(['web'])
    ->name('admin.integrations.google.callback');
