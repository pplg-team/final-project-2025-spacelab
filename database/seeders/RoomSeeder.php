<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Room, Building};

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [];

        /*
        |--------------------------------------------------------------------------
        | LABORATORIUM
        |--------------------------------------------------------------------------
        */
        $labRooms = [
            ['code' => 'LAB01', 'name' => 'Laboratorium Komputer 1', 'building_code' => 'GRPL', 'floor' => 1, 'capacity' => 40, 'type' => 'lab'],
            ['code' => 'LAB02', 'name' => 'Laboratorium Komputer 2', 'building_code' => 'GTKJ', 'floor' => 1, 'capacity' => 40, 'type' => 'lab'],
            ['code' => 'LAB03', 'name' => 'Laboratorium Komputer 3', 'building_code' => 'GDPIB', 'floor' => 1, 'capacity' => 40, 'type' => 'lab'],
            ['code' => 'LAB04', 'name' => 'Laboratorium Jaringan', 'building_code' => 'GTKJ', 'floor' => 1, 'capacity' => 30, 'type' => 'lab'],
            ['code' => 'LAB05', 'name' => 'Laboratorium Praktik Teknik', 'building_code' => 'GTEI', 'floor' => 2, 'capacity' => 35, 'type' => 'lab'],
            ['code' => 'LAB06', 'name' => 'Laboratorium Desain Grafis', 'building_code' => 'GDPIB', 'floor' => 2, 'capacity' => 30, 'type' => 'lab'],
            ['code' => 'LAB07', 'name' => 'Laboratorium Mesin 1', 'building_code' => 'GTFLM', 'floor' => 1, 'capacity' => 35, 'type' => 'lab'],
            ['code' => 'LAB08', 'name' => 'Laboratorium Mesin 2', 'building_code' => 'GTFLM', 'floor' => 2, 'capacity' => 35, 'type' => 'lab'],
            ['code' => 'LAB09', 'name' => 'Laboratorium Multimedia', 'building_code' => 'GBR', 'floor' => 3, 'capacity' => 30, 'type' => 'lab'],
            ['code' => 'LAB10', 'name' => 'Laboratorium IPA', 'building_code' => 'GTMR', 'floor' => 1, 'capacity' => 35, 'type' => 'lab'],
        ];

        $rooms = array_merge($rooms, $labRooms);

        /*
        |--------------------------------------------------------------------------
        | KELAS REGULER
        |--------------------------------------------------------------------------
        */
        $majors = ['RPL', 'TKJ', 'DKV', 'TSM', 'TKR', 'AKL', 'BDP', 'MP', 'KLN', 'PHT'];
        $levels = [10, 11, 12];

        $majorBuildingMap = [
            'RPL' => 'GRPL',
            'TKJ' => 'GTKJ',
            'DKV' => 'GDPIB',
            'TSM' => 'GTSM',
            'TKR' => 'BTKR',
            'AKL' => 'GTMR',
            'BDP' => 'GTMR',
            'MP'  => 'GTMR',
            'KLN' => 'GTITL',
            'PHT' => 'GTEI',
        ];

        foreach ($majors as $major) {
            foreach ($levels as $level) {
                for ($rombel = 1; $rombel <= 3; $rombel++) {

                    $buildingCode = $majorBuildingMap[$major] ?? 'GBR';
                    $floor = ($rombel % 2) + 1;

                    $rooms[] = [
                        'code' => "KLS_{$major}{$level}{$rombel}",
                        'name' => "Kelas {$level} {$major} {$rombel}",
                        'building_code' => $buildingCode,
                        'floor' => $floor,
                        'capacity' => 36,
                        'type' => 'kelas',
                    ];
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RUANG KANTOR
        |--------------------------------------------------------------------------
        */
        $officeRooms = [
            ['code' => 'RGURU01', 'name' => 'Ruang Guru', 'building_code' => 'GBR', 'floor' => 1, 'capacity' => 50, 'type' => 'kantor'],
            ['code' => 'RGURU02', 'name' => 'Ruang Guru Khusus', 'building_code' => 'GBR', 'floor' => 2, 'capacity' => 30, 'type' => 'kantor'],
            ['code' => 'RKEPSEK', 'name' => 'Ruang Kepala Sekolah', 'building_code' => 'GBR', 'floor' => 3, 'capacity' => 8, 'type' => 'kantor'],
            ['code' => 'RWAKIL', 'name' => 'Ruang Wakil Kepala Sekolah', 'building_code' => 'GBR', 'floor' => 3, 'capacity' => 6, 'type' => 'kantor'],
            ['code' => 'RTU01', 'name' => 'Ruang Tata Usaha', 'building_code' => 'GBR', 'floor' => 1, 'capacity' => 15, 'type' => 'kantor'],
            ['code' => 'RKURIKULUM', 'name' => 'Ruang Kurikulum', 'building_code' => 'GBR', 'floor' => 1, 'capacity' => 10, 'type' => 'kantor'],
        ];

        $rooms = array_merge($rooms, $officeRooms);

        /*
        |--------------------------------------------------------------------------
        | FASILITAS UMUM
        |--------------------------------------------------------------------------
        */
        $facilityRooms = [
            ['code' => 'RPERPUS', 'name' => 'Perpustakaan Sekolah', 'building_code' => 'GTMR', 'floor' => 1, 'capacity' => 50, 'type' => 'fasilitas'],
            ['code' => 'RUKS', 'name' => 'Ruang UKS', 'building_code' => 'GTMR', 'floor' => 1, 'capacity' => 8, 'type' => 'fasilitas'],
            ['code' => 'RMUSHOLLA', 'name' => 'Musholla', 'building_code' => 'GTMR', 'floor' => 1, 'capacity' => 100, 'type' => 'fasilitas'],
            ['code' => 'RKANTIN', 'name' => 'Kantin Sekolah', 'building_code' => 'GTMR', 'floor' => 1, 'capacity' => 80, 'type' => 'fasilitas'],
            ['code' => 'RSERBAGUNA', 'name' => 'Aula Serbaguna', 'building_code' => 'GBR', 'floor' => 3, 'capacity' => 300, 'type' => 'fasilitas'],
            ['code' => 'RGUDANG', 'name' => 'Gudang Sekolah', 'building_code' => 'GTFLM', 'floor' => 1, 'capacity' => 5, 'type' => 'penyimpanan'],
            ['code' => 'RTERBUKA', 'name' => 'Lapangan Olahraga', 'building_code' => 'GTMR', 'floor' => 1, 'capacity' => 200, 'type' => 'fasilitas'],
        ];

        $rooms = array_merge($rooms, $facilityRooms);

        /*
        |--------------------------------------------------------------------------
        | PERSIST DATA
        |--------------------------------------------------------------------------
        */

        $buildingMap = Building::pluck('id', 'code');

        $typeMap = [
            'lab' => 'lab',
            'kelas' => 'kelas',
            'kantor' => 'lainnya',
            'fasilitas' => 'lainnya',
            'penyimpanan' => 'lainnya',
        ];

        foreach ($rooms as $room) {

            Room::updateOrCreate(
                ['code' => $room['code']],
                [
                    'name' => $room['name'],
                    'building_id' => $buildingMap[$room['building_code']] ?? null,
                    'floor' => $room['floor'] ?? null,
                    'capacity' => $room['capacity'] ?? null,
                    'type' => $typeMap[$room['type']] ?? 'lainnya',
                    'is_active' => true,
                    'notes' => null,
                ]
            );
        }

        $this->command->info("✅ RoomSeeder: " . count($rooms) . " rooms seeded successfully.");
    }
}