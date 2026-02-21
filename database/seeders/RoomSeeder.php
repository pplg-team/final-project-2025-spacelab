<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Room, Building};

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [];

        $buildingMap = Building::pluck('id', 'code');

        /*
        |--------------------------------------------------------------------------
        | GEDUNG BARU (GBR) - 3 LANTAI
        | Aula, Ruang Pertemuan, Kurikulum, TU, BK, + kelas, kantor, lab
        |--------------------------------------------------------------------------
        */

        $rooms = array_merge($rooms, [

            // Lantai 1
            ['code' => 'GBR-KLS101', 'name' => 'Kelas 1 GBR', 'building_code' => 'GBR', 'floor' => 1, 'capacity' => 36, 'type' => 'kelas'],
            ['code' => 'GBR-KLS102', 'name' => 'Kelas 2 GBR', 'building_code' => 'GBR', 'floor' => 1, 'capacity' => 36, 'type' => 'kelas'],
            ['code' => 'GBR-TU', 'name' => 'Ruang Tata Usaha', 'building_code' => 'GBR', 'floor' => 1, 'capacity' => 15, 'type' => 'lainnya'],
            ['code' => 'GBR-KURIK', 'name' => 'Ruang Kurikulum', 'building_code' => 'GBR', 'floor' => 1, 'capacity' => 10, 'type' => 'lainnya'],

            // Lantai 2
            ['code' => 'GBR-BK', 'name' => 'Ruang BK', 'building_code' => 'GBR', 'floor' => 2, 'capacity' => 8, 'type' => 'lainnya'],
            ['code' => 'GBR-RPT', 'name' => 'Ruang Pertemuan', 'building_code' => 'GBR', 'floor' => 2, 'capacity' => 40, 'type' => 'lainnya'],
            ['code' => 'GBR-LAB1', 'name' => 'Laboratorium Multimedia', 'building_code' => 'GBR', 'floor' => 2, 'capacity' => 30, 'type' => 'lab'],

            // Lantai 3
            ['code' => 'GBR-AULA', 'name' => 'Aula Serbaguna', 'building_code' => 'GBR', 'floor' => 3, 'capacity' => 300, 'type' => 'aula'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | GEDUNG BKK (BKK)
        | Hanya Kantin, Aula, dan Ruang BKK
        |--------------------------------------------------------------------------
        */

        $rooms = array_merge($rooms, [
            ['code' => 'BKK-KANTIN', 'name' => 'Kantin Sekolah', 'building_code' => 'BKK', 'floor' => 1, 'capacity' => 120, 'type' => 'lainnya'],
            ['code' => 'BKK-AULA', 'name' => 'Aula BKK', 'building_code' => 'BKK', 'floor' => 1, 'capacity' => 150, 'type' => 'aula'],
            ['code' => 'BKK-UTAMA', 'name' => 'Ruang BKK', 'building_code' => 'BKK', 'floor' => 1, 'capacity' => 20, 'type' => 'lainnya'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | GEDUNG MASJID (MASJID) - 2 LANTAI, 1 RUANG PER LANTAI
        |--------------------------------------------------------------------------
        */

        $rooms = array_merge($rooms, [
            ['code' => 'MSJ-LT1', 'name' => 'Ruang Ibadah Lantai 1', 'building_code' => 'MASJID', 'floor' => 1, 'capacity' => 200, 'type' => 'lainnya'],
            ['code' => 'MSJ-LT2', 'name' => 'Ruang Ibadah Lantai 2', 'building_code' => 'MASJID', 'floor' => 2, 'capacity' => 200, 'type' => 'lainnya'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | GEDUNG RPL
        | Lantai 1 hanya kelas
        | Lantai 2 ada 1 kelas + AWS + kantor + lab
        |--------------------------------------------------------------------------
        */

        $rooms = array_merge($rooms, [

            // Lantai 1
            ['code' => 'RPL-KLS101', 'name' => 'Kelas RPL 1', 'building_code' => 'GRPL', 'floor' => 1, 'capacity' => 36, 'type' => 'kelas'],
            ['code' => 'RPL-KLS102', 'name' => 'Kelas RPL 2', 'building_code' => 'GRPL', 'floor' => 1, 'capacity' => 36, 'type' => 'kelas'],
            ['code' => 'RPL-KLS103', 'name' => 'Kelas RPL 3', 'building_code' => 'GRPL', 'floor' => 1, 'capacity' => 36, 'type' => 'kelas'],

            // Lantai 2
            ['code' => 'RPL-KLS201', 'name' => 'Kelas RPL 4', 'building_code' => 'GRPL', 'floor' => 2, 'capacity' => 36, 'type' => 'kelas'],
            ['code' => 'RPL-AWS', 'name' => 'Ruang AWS Cloud Lab', 'building_code' => 'GRPL', 'floor' => 2, 'capacity' => 30, 'type' => 'lab'],
            ['code' => 'RPL-LAB1', 'name' => 'Laboratorium Pemrograman', 'building_code' => 'GRPL', 'floor' => 2, 'capacity' => 35, 'type' => 'lab'],
            ['code' => 'RPL-KANTOR', 'name' => 'Kantor RPL', 'building_code' => 'GRPL', 'floor' => 2, 'capacity' => 8, 'type' => 'lainnya'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | GEDUNG TKJ (1 LANTAI SAJA)
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= 6; $i++) {
            $rooms[] = [
                'code' => "TKJ-KLS10{$i}",
                'name' => "Kelas TKJ {$i}",
                'building_code' => 'GTKJ',
                'floor' => 1,
                'capacity' => 36,
                'type' => 'kelas',
            ];
        }

        $rooms[] = [
            'code' => 'TKJ-LAB1',
            'name' => 'Laboratorium Jaringan',
            'building_code' => 'GTKJ',
            'floor' => 1,
            'capacity' => 30,
            'type' => 'lab',
        ];

        /*
        |--------------------------------------------------------------------------
        | GEDUNG LAIN (2 LANTAI)
        | Pastikan tiap gedung punya kelas, kantor, dan lab
        |--------------------------------------------------------------------------
        */

        $twoFloorBuildings = ['GDPIB', 'GTSM', 'GTMR', 'GTITL', 'GTFLM', 'GTEI', 'BTKR'];

        foreach ($twoFloorBuildings as $code) {

            // Lantai 1 - kelas
            for ($i = 1; $i <= 3; $i++) {
                $rooms[] = [
                    'code' => "{$code}-KLS1{$i}",
                    'name' => "Kelas {$code} {$i}",
                    'building_code' => $code,
                    'floor' => 1,
                    'capacity' => 36,
                    'type' => 'kelas',
                ];
            }

            // Lantai 2 - lab + kantor
            $rooms[] = [
                'code' => "{$code}-LAB1",
                'name' => "Laboratorium {$code}",
                'building_code' => $code,
                'floor' => 2,
                'capacity' => 30,
                'type' => 'lab',
            ];

            $rooms[] = [
                'code' => "{$code}-KANTOR",
                'name' => "Kantor {$code}",
                'building_code' => $code,
                'floor' => 2,
                'capacity' => 10,
                'type' => 'lainnya',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | RUANG LUAR (Tetap punya building_id)
        |--------------------------------------------------------------------------
        */

        $rooms[] = [
            'code' => 'GTMR-LAP',
            'name' => 'Lapangan Olahraga',
            'building_code' => 'GTMR',
            'floor' => 1,
            'capacity' => 500,
            'type' => 'lainnya',
        ];

        /*
        |--------------------------------------------------------------------------
        | PERSIST
        |--------------------------------------------------------------------------
        */

        foreach ($rooms as $room) {

            Room::updateOrCreate(
                ['code' => $room['code']],
                [
                    'name' => $room['name'],
                    'building_id' => $buildingMap[$room['building_code']] ?? null,
                    'floor' => $room['floor'],
                    'capacity' => $room['capacity'],
                    'type' => $room['type'],
                    'is_active' => true,
                    'notes' => null,
                ]
            );
        }

        $this->command->info("✅ RoomSeeder: " . count($rooms) . " rooms seeded successfully.");
    }
}