<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
     // GET /api/users
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'phone_number', 'role')->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil daftar user',
            'data' => $users
        ]);
    }

    // GET /api/users/{id}
    public function show($id)
    {
        $user = User::select('id', 'name', 'email', 'phone_number', 'role')
            ->where('id', $id)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil detail user',
            'data' => $user
        ]);
    }

    public function destroy(Request $request, $id)
{   // Cek apakah yang melakukan request adalah admin
    if ($request->user()->role !== 'admin') {
        return response()->json([
            'success' => false,
            'message' => 'Akses ditolak. Hanya admin yang dapat menghapus user.'
        ], 403);
    }


    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User tidak ditemukan'
        ], 404);
    }

    // Mencegah admin menghapus akun sendiri
    if ($user->id === $request->user()->id) {
        return response()->json([
            'success' => false,
            'message' => 'Kamu tidak dapat menghapus akun sendiri'
        ], 422);
    }

    $user->delete();

    return response()->json([
        'success' => true,
        'message' => 'User berhasil dihapus'
    ]);
}
public function becomeSeller(Request $request)
{
    $user = $request->user();

    if ($user->role === 'admin') {
        return response()->json([
            'success' => false,
            'message' => 'Admin tidak dapat menjadi seller'
        ], 422);
    }

    if ($user->role === 'seller') {
        return response()->json([
            'success' => true,
            'message' => 'Kamu sudah terdaftar sebagai seller',
            'data' => $user
        ]);
    }

    $user->update([
        'role' => 'seller'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Berhasil menjadi seller',
        'data' => $user->fresh()
    ]);
}

}
