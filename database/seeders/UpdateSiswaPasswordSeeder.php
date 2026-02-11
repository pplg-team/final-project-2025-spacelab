<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateSiswaPasswordSeeder extends Seeder
{
    public function run(): void
    {
        User::whereHas('role', fn ($q) => $q->where('name', 'Siswa'))
            ->update([
                'password_hash' => Hash::make('siswa123')
            ]);
    }
}
