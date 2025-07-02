<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobVacancy;
use App\Models\JobApplySociety;
use App\Models\JobApplyPosition;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApplicationController extends Controller
{
    /**
     * Menyimpan data lamaran pekerjaan baru dari pengguna.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // 1. Validasi Input dari Frontend
        $request->validate([
            'vacancy_id' => 'required|exists:job_vacancies,id',
            'positions' => 'required|array|min:1',
            'positions.*' => 'required|exists:available_positions,id',
            'notes' => 'nullable|string',
        ]);

        // 2. Validasi Bisnis: Cek apakah validasi data user sudah 'accepted'
        if ($user->validation?->status !== 'accepted') {
            return response()->json(['message' => 'Your data validation must be accepted by a validator before applying for a job.'], 403);
        }

        // 3. Validasi Bisnis: Cek apakah user sudah pernah melamar di lowongan ini
        $hasApplied = JobApplySociety::where('society_id', $user->id)
            ->where('job_vacancy_id', $request->vacancy_id)
            ->exists();
        if ($hasApplied) {
            return response()->json(['message' => 'Application for a job can only be once.'], 400);
        }

        // Mulai Transaksi Database: untuk memastikan semua data berhasil disimpan
        DB::beginTransaction();
        try {
            // 4. Buat data lamaran utama di tabel 'job_apply_societies'
            $application = JobApplySociety::create([
                'notes' => $request->notes,
                'date' => Carbon::now()->toDateString(),
                'society_id' => $user->id,
                'job_vacancy_id' => $request->vacancy_id,
            ]);

            // 5. Simpan setiap posisi yang dipilih ke tabel 'job_apply_positions'
            foreach ($request->positions as $positionId) {
                JobApplyPosition::create([
                    'date' => Carbon::now()->toDateString(),
                    'society_id' => $user->id,
                    'job_vacancy_id' => $request->vacancy_id,
                    'position_id' => $positionId,
                    'job_apply_societies_id' => $application->id,
                    'status' => 'pending', // Status default untuk setiap posisi
                ]);
            }

            // Jika semua query berhasil, simpan perubahan secara permanen
            DB::commit();

            return response()->json(['message' => 'Applying for job successful'], 201);
        } catch (\Exception $e) {
            // Jika terjadi error di tengah jalan, batalkan semua perubahan
            DB::rollBack();
            // Kirim response error server
            return response()->json(['message' => 'An error occurred during the application process.', 'error' => $e->getMessage()], 500);
        }
    }
}
