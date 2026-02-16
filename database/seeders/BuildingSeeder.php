<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = [
            ['code' => 'GBR', 'name' => 'Gedung Baru', 'description' => 'ini gedung', 'total_floors' => 3],
            ['code' => 'GRPL', 'name' => 'Gedung RPL', 'description' => 'ini gedung', 'total_floors' => 2],
            ['code' => 'GDPIB', 'name' => 'Gedung DPIB', 'description' => 'ini gedung', 'total_floors' => 2],
            ['code' => 'GTKJ', 'name' => 'Gedung TKJ', 'description' => 'ini gedung', 'total_floors' => 1],
            ['code' => 'GTMR', 'name' => 'Gedung Timur', 'description' => 'ini gedung', 'total_floors' => 2],
            ['code' => 'GTITL', 'name' => 'Gedung TITL', 'description' => 'ini gedung', 'total_floors' => 2],
            ['code' => 'GTSM', 'name' => 'Gedung TSM', 'description' => 'ini gedung', 'total_floors' => 2],
            ['code' => 'GTEI', 'name' => 'Gedung TEI', 'description' => 'ini gedung', 'total_floors' => 2],
            ['code' => 'GTFLM', 'name' => 'Gedung TFLM', 'description' => 'ini gedung', 'total_floors' => 2],
            ['code' => 'BTKR', 'name' => 'Bengkel TKR', 'description' => 'ini gedung', 'total_floors' => 2],
        ];

        foreach ($buildings as $b) {
            Building::updateOrCreate(['code' => $b['code']], $b);
        }

        $this->command->info('✅ BuildingSeeder: ' . count($buildings) . ' buildings seeded.');
    }
}
