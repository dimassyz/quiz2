<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Validation;
use App\Models\JobCategory;

class ValidationController extends Controller
{
    /**
     * Menyimpan permintaan validasi baru dari pengguna.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->validation) {
            return response()->json(['message' => 'You have already requested data validation.'], 400);
        }

        $request->validate([
            'job_category_id' => 'required|exists:job_categories,id',
            'work_experience' => 'required',
            'job_position' => 'required',
            'reason_accepted' => 'required',
        ]);

        Validation::create([
            'society_id' => $user->id,
            'job_category_id' => $request->job_category_id,
            'work_experience' => $request->work_experience,
            'job_position' => $request->job_position,
            'reason_accepted' => $request->reason_accepted,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Request data validation sent successful'], 201);
    }

    /**
     * Menampilkan data validasi milik pengguna yang sedang login.
     */
    public function show(Request $request)
    {
        $user = $request->user();

        $validation = $user->validation()->with([
            'jobCategory',
            'validator' => function ($query) {
                $query->with('user');
            }
        ])->first();

        if (!$validation) {
            return response()->json(['validation' => null], 200);
        }

        return response()->json(['validation' => $validation], 200);
    }

    /**
     * Mengambil semua kategori pekerjaan.
     */
    public function getJobCategories()
    {
        $categories = JobCategory::all();
        return response()->json(['categories' => $categories], 200);
    }
}
