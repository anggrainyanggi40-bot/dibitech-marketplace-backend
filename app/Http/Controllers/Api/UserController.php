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

}
