<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::get('/expertises/{slug}', function (string $slug) {
    $service = config("aci.services.$slug");
    abort_unless(is_array($service), 404);

    return view('service', compact('service', 'slug'));
})->where('slug', '[a-z]+')->name('services.show');
Route::view('/confidentialite', 'privacy')->name('privacy');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
