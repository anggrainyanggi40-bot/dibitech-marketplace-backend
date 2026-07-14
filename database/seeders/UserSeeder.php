<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
        [
            'name' =>'Andi Saputra',
            'phone_number' =>'081111111001',
            'email' =>'andi@mail.com',
            'password' => Hash::make('pass123'),
            'role' => 'seller',
        ],
        [
            'name' =>'Budi Santoso',
            'phone_number' =>'081111111002',
            'email' =>'budi@mail.com',
            'password' => Hash::make('pass124'),
            'role' => 'buyer',
        ],
        [
            'name' =>'Citra Dewi',
            'phone_number' =>'081111111003',
            'email' =>'citra@mail.com',
            'password' => Hash::make('pass123'),
            'role' => 'seller',
        ],
        [
            'name' =>'Dewi Lestari',
            'phone_number' =>'081111111004',
            'email' =>'dewi@mail.com',
            'password' => Hash::make('pass123'),
            'role' => 'buyer',
        ],
        [
            'name' =>'Eko Prasetyo',
            'phone_number' =>'081111111005',
            'email' =>'eko@mail.com',
            'password' => Hash::make('pass123'),
            'role' => 'seller',
        ],
        [
            'name' =>'Andin Prastya',
            'phone_number' =>'081111111225',
            'email' =>'andin@mail.com',
            'password' => Hash::make('pass123'),
            'role' => 'admin',
        ],
        [
            'name' =>'Dwi Pangestu',
            'phone_number' =>'081234567890',
            'email' =>'dwi@mail.com',
            'password' => Hash::make('pass123'),
            'role' => 'admin',
        ]


        ];

        foreach($users as $user){
            User::create($user);
        }
    }
}
