<?php

use App\Models\PpdbUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION (PPDB)
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', function () {
    return view('ppdb.auth.verify-email');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {

    $user = PpdbUser::findOrFail($id);

    if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
        abort(403);
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    Auth::guard('ppdb')->login($user);

    return redirect()->route('ppdb.dashboard');

})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user('ppdb')->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi dikirim ulang!');
})->middleware(['auth:ppdb', 'throttle:6,1'])->name('verification.send');