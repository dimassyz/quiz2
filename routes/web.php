<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rute-rute ini akan menyajikan file-file frontend statis Anda dari
| folder 'public'.
|
*/

// Rute untuk Halaman Utama (root url: /)
// Ini akan langsung menyajikan file public/index.html
Route::get('/', function () {
    return file_get_contents(public_path('index.html'));
});

// Rute ini akan menangani semua URL lain yang mungkin diminta.
// Contoh: /dashboard, /dashboard/index.html, /assets/css/custom.css
// Ini harus menjadi rute terakhir untuk menghindari konflik.
Route::get('/{any}', function ($any) {
    $path = public_path($any);

    // Cek jika path yang diminta adalah file yang ada
    if (file_exists($path) && !is_dir($path)) {
        // Tentukan tipe konten berdasarkan ekstensi file
        $mime = pathinfo($path, PATHINFO_EXTENSION);
        $headers = [];
        if ($mime == 'css') {
            $headers['Content-Type'] = 'text/css';
        } elseif ($mime == 'js') {
            $headers['Content-Type'] = 'application/javascript';
        }
        // Tambahkan tipe lain jika perlu (png, jpg, dll.)

        return response()->file($path, $headers);
    }

    // Jika path adalah direktori dan ada index.html di dalamnya
    if (is_dir($path) && file_exists($path . '/index.html')) {
        return file_get_contents($path . '/index.html');
    }

    // Jika tidak ditemukan sama sekali, kembalikan 404
    abort(404);
})->where('any', '.*');
