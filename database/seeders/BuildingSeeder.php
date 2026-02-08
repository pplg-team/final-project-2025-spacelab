<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = [
            ['code' => 'GBR', 'name' => 'Gedung Baru'],
            ['code' => 'GRPL', 'name' => 'Gedung RPL'],
            ['code' => 'GDPIB', 'name' => 'Gedung DPIB'],
            ['code' => 'GTKJ', 'name' => 'Gedung TKJ'],
            ['code' => 'GTMR', 'name' => 'Gedung Timur'],
            ['code' => 'GTITL', 'name' => 'Gedung TITL'],
            ['code' => 'GTSM', 'name' => 'Gedung TSM'],
            ['code' => 'GTEI', 'name' => 'Gedung TEI'],
            ['code' => 'GTFLM', 'name' => 'Gedung TFLM'],
            ['code' => 'BTKR', 'name' => 'Bengkel TKR'],

        ];

        foreach ($buildings as $b) {
            Building::updateOrCreate(['code' => $b['code']], $b);
        }

        $this->command->info('✅ BuildingSeeder: ' . count($buildings) . ' buildings seeded.');
    }
}
