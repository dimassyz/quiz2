<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Society;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle user login request.
     * This will validate input, check credentials, and return a token on success.
     */
    public function login(Request $request)
    {
        // 1. Validasi input request.
        // Menggunakan Validator::make() agar kita bisa mengontrol format response error.
        $validator = Validator::make($request->all(), [
            'id_card_number' => 'required',
            'password' => 'required',
        ]);

        // Jika validasi gagal, kembalikan response error 422 (Unprocessable Entity)
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Cari pengguna (society) berdasarkan id_card_number.
        // Kita juga langsung mengambil data 'regional' untuk efisiensi (Eager Loading).
        $society = Society::with('regional')->where('id_card_number', $request->id_card_number)->first();

        // 3. Cek apakah pengguna ditemukan DAN password yang diberikan cocok.
        //    Gunakan Hash::check() untuk membandingkan password teks biasa dengan hash di database.
        if (!$society || !Hash::check($request->password, $society->password)) {
            // Jika tidak cocok, kembalikan response error 401 (Unauthorized)
            return response()->json([
                'message' => 'ID Card Number or Password incorrect'
            ], 401);
        }

        // 4. Jika kredensial benar, buat token API baru untuk pengguna.
        $token = $society->createToken('auth_token')->plainTextToken;

        // Simpan token ke kolom login_tokens (sesuai spesifikasi ERD)
        $society->login_tokens = $token;
        $society->save();

        // 5. Kembalikan response sukses (200 OK) dengan data yang dibutuhkan oleh frontend.
        // Format disesuaikan dengan dokumen Halaman 3.
        return response()->json([
            'name' => $society->name,
            'born_date' => $society->born_date,
            'gender' => $society->gender,
            'address' => $society->address,
            'token' => $token,
            'regional' => $society->regional, // Eloquent akan mengubah relasi ini menjadi objek JSON
        ], 200);
    }

    /**
     * Handle user logout request.
     */
    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan untuk otentikasi.
        // $request->user() secara otomatis mengidentifikasi pengguna yang sedang login.
        $request->user()->currentAccessToken()->delete();

        // Kembalikan response sukses.
        return response()->json([
            'message' => 'Logout success'
        ], 200);
    }
}
