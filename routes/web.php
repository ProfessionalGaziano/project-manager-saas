<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/invitation/{token}', [App\Http\Controllers\TeamInvitationController::class, 'accept'])->name('invitation.accept');
Route::post('/invitation/{token}/register', [App\Http\Controllers\TeamInvitationController::class, 'register'])->name('invitation.register');

Route::middleware([backpack_middleware()])->group(function () {
    Route::post('/invitation/invite', [App\Http\Controllers\TeamInvitationController::class, 'invite'])->name('invitation.invite');
});

Route::middleware([backpack_middleware()])->group(function () {
    Route::get('/invitation', [App\Http\Controllers\TeamInvitationController::class, 'index'])->name('invitation.index');
    Route::post('/invitation/invite', [App\Http\Controllers\TeamInvitationController::class, 'invite'])->name('invitation.invite');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/admin');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Link di verifica inviato!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/test-mail', function () {
    Mail::to('test@test.com')
        ->send(new TestMail());

    return 'mail inviata';
});

Route::middleware([backpack_middleware()])->group(function () {
    Route::get('/subscription/plans', [App\Http\Controllers\SubscriptionController::class, 'plans'])->name('subscription.plans');
    Route::post('/subscription/checkout', [App\Http\Controllers\SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::get('/subscription/success', [App\Http\Controllers\SubscriptionController::class, 'success'])->name('subscription.success');
    Route::post('/subscription/cancel', [App\Http\Controllers\SubscriptionController::class, 'cancel'])->name('subscription.cancel');
});

// Rotte per le richieste di progetto
Route::middleware([backpack_middleware()])->group(function () {
    
    // Client
    Route::get('/project-requests', [App\Http\Controllers\ProjectRequestController::class, 'index'])->name('project-requests.index');
    Route::post('/project-requests', [App\Http\Controllers\ProjectRequestController::class, 'store'])->name('project-requests.store');
    
    // Admin
    //Route::get('/project-requests/admin', [App\Http\Controllers\ProjectRequestController::class, 'adminIndex'])->name('project-requests.admin');
    Route::post('/project-requests/{projectRequest}/accept', [App\Http\Controllers\ProjectRequestController::class, 'accept'])->name('project-requests.accept');
    Route::post('/project-requests/{projectRequest}/reject', [App\Http\Controllers\ProjectRequestController::class, 'reject'])->name('project-requests.reject');
});


require __DIR__.'/auth.php';
