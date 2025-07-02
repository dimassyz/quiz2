<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobVacancy;
use App\Models\JobApplyPosition; // Import model ini untuk menghitung pelamar

class JobVacancyController extends Controller
{
    /**
     * Menampilkan daftar semua lowongan pekerjaan.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $validation = $user->validation;
        if (!$validation || $validation->status !== 'accepted') {
            return response()->json(['message' => 'Your data validation must be accepted to see job vacancies.'], 403);
        }

        $vacancies = JobVacancy::with(['jobCategory', 'availablePositions'])->get();
        $appliedVacancyIds = $user->applications()->pluck('job_vacancy_id')->toArray();

        $vacancies->map(function ($vacancy) use ($appliedVacancyIds) {
            $vacancy->has_applied = in_array($vacancy->id, $appliedVacancyIds);
            return $vacancy;
        });

        return response()->json(['vacancies' => $vacancies], 200);
    }

    /**
     * Menampilkan detail satu lowongan pekerjaan.
     */
    public function show(Request $request, JobVacancy $vacancy)
    {
        // 1. Cek ulang status validasi pengguna
        $validation = $request->user()->validation;
        if (!$validation || $validation->status !== 'accepted') {
            return response()->json(['message' => 'Your data validation must be accepted.'], 403);
        }

        // 2. Laravel's Model-Route Binding sudah otomatis menemukan lowongan berdasarkan ID di URL.
        //    Kita hanya perlu memuat relasi-relasi yang dibutuhkan.
        $vacancy->load(['jobCategory', 'availablePositions']);

        // 3. Untuk setiap posisi, hitung berapa banyak orang yang sudah melamar
        //    agar bisa ditampilkan di frontend (Application / Max)
        foreach ($vacancy->availablePositions as $position) {
            $position->apply_count = JobApplyPosition::where('position_id', $position->id)->count();
        }

        // 4. Kembalikan data lowongan tunggal dalam format JSON
        return response()->json(['vacancy' => $vacancy], 200);
    }
}
