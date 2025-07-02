<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Mengambil riwayat lamaran kerja milik pengguna yang sedang login.
     * Fungsi getValidation() telah dipindahkan ke ValidationController.
     */
    public function getApplications(Request $request)
    {
        // Mengambil data Society yang sedang login dari token
        $user = $request->user();

        // Menggunakan "with" dengan closure untuk memuat relasi bersarang secara aman.
        // Ini akan memuat relasi jobVacancy, dan juga relasi appliedPositions.
        // Untuk setiap appliedPositions, ia juga akan memuat relasi position-nya.
        $applications = $user->applications()->with([
            'jobVacancy',
            'appliedPositions' => function ($query) {
                $query->with('position');
            }
        ])->get();

        // Mengembalikan data lamaran dalam format JSON
        return response()->json(['applications' => $applications], 200);
    }
}
