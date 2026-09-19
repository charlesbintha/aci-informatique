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

Route::get('/sitemap.xml', function () {
    $urls = [config('seo.url').'/'];
    foreach (array_keys(config('aci.services')) as $slug) {
        $urls[] = config('seo.url').'/expertises/'.$slug;
    }
    $urls[] = config('seo.url').'/confidentialite';

    return response()->view('sitemap', compact('urls'))->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('sitemap');
