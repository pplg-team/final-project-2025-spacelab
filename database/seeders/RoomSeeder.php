<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Room, Building};

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [];

        // ====== LABORATORIUM (Shared Resources) ======
        $labRooms = [
            ['code' => 'LAB01', 'name' => 'Laboratorium Komputer 1', 'building' => 'Gedung RPL', 'floor' => 1, 'capacity' => 40, 'type' => 'lab'],
            ['code' => 'LAB02', 'name' => 'Laboratorium Komputer 2', 'building' => 'Gedung TKJ', 'floor' => 2, 'capacity' => 40, 'type' => 'lab'],
            ['code' => 'LAB03', 'name' => 'Laboratorium Komputer 3', 'building' => 'Gedung DPIB', 'floor' => 1, 'capacity' => 40, 'type' => 'lab'],
            ['code' => 'LAB04', 'name' => 'Laboratorium Jaringan', 'building' => 'Gedung TKJ', 'floor' => 1, 'capacity' => 30, 'type' => 'lab'],
            ['code' => 'LAB05', 'name' => 'Laboratorium Praktik Teknik', 'building' => 'Gedung TEI', 'floor' => 2, 'capacity' => 35, 'type' => 'lab'],
            ['code' => 'LAB06', 'name' => 'Laboratorium Desain Grafis', 'building' => 'Gedung DPIB', 'floor' => 2, 'capacity' => 30, 'type' => 'lab'],
            ['code' => 'LAB07', 'name' => 'Laboratorium Mesin 1', 'building' => 'Gedung TFLM', 'floor' => 1, 'capacity' => 35, 'type' => 'lab'],
            ['code' => 'LAB08', 'name' => 'Laboratorium Mesin 2', 'building' => 'Gedung TFLM', 'floor' => 2, 'capacity' => 35, 'type' => 'lab'],
            ['code' => 'LAB09', 'name' => 'Laboratorium Multimedia', 'building' => 'Gedung Baru', 'floor' => 3, 'capacity' => 30, 'type' => 'lab'],
            ['code' => 'LAB10', 'name' => 'Laboratorium IPA', 'building' => 'Gedung Timur', 'floor' => 1, 'capacity' => 35, 'type' => 'lab'],
        ];
        $rooms = array_merge($rooms, $labRooms);

        // ====== KELAS REGULER (90 kelas: 10 jurusan × 9 kelas per jurusan) ======
        $majors = ['RPL', 'TKJ', 'DKV', 'TSM', 'TKR', 'AKL', 'BDP', 'MP', 'KLN', 'PHT'];
        $levels = [10, 11, 12];
        
        // Map majors to specific buildings where appropriate
        $majorBuildingMap = [
            'RPL' => 'Gedung RPL',
            'TKJ' => 'Gedung TKJ',
            'DKV' => 'Gedung DPIB',
            'TSM' => 'Gedung TSM',
            'TKR' => 'Bengkel TKR',
            'AKL' => 'Gedung Timur',
            'BDP' => 'Gedung Timur',
            'MP'  => 'Gedung Timur',
            'KLN' => 'Gedung TITL',
            'PHT' => 'Gedung TEI',
        ];

        foreach ($majors as $major) {
            foreach ($levels as $level) {
                for ($rombel = 1; $rombel <= 3; $rombel++) {
                    $currentBuilding = $majorBuildingMap[$major] ?? 'Gedung Baru';
                    // Simple floor logic: 1 or 2
                    $floor = ($rombel % 2) + 1;

                    $roomCode = "KLS_{$major}{$level}{$rombel}";
                    $roomName = "Kelas {$level} {$major} {$rombel}";

                    $rooms[] = [
                        'code' => $roomCode,
                        'name' => $roomName,
                        'building' => $currentBuilding,
                        'floor' => $floor,
                        'capacity' => 36, // Untuk 35 siswa
                        'type' => 'kelas',
                    ];
                }
            }
        }

        // ====== RUANG KANTOR DAN GURU ======
        $officeRooms = [
            ['code' => 'RGURU01', 'name' => 'Ruang Guru', 'building' => 'Gedung Baru', 'floor' => 1, 'capacity' => 50, 'type' => 'kantor'],
            ['code' => 'RGURU02', 'name' => 'Ruang Guru Khusus', 'building' => 'Gedung Baru', 'floor' => 2, 'capacity' => 30, 'type' => 'kantor'],
            ['code' => 'RKEPSEK', 'name' => 'Ruang Kepala Sekolah', 'building' => 'Gedung Baru', 'floor' => 3, 'capacity' => 8, 'type' => 'kantor'],
            ['code' => 'RWAKIL', 'name' => 'Ruang Wakil Kepala Sekolah', 'building' => 'Gedung Baru', 'floor' => 3, 'capacity' => 6, 'type' => 'kantor'],
            ['code' => 'RTU01', 'name' => 'Ruang Tata Usaha', 'building' => 'Gedung Baru', 'floor' => 1, 'capacity' => 15, 'type' => 'kantor'],
            ['code' => 'RKURIKULUM', 'name' => 'Ruang Kurikulum', 'building' => 'Gedung Baru', 'floor' => 1, 'capacity' => 10, 'type' => 'kantor'],
        ];
        $rooms = array_merge($rooms, $officeRooms);

        // ====== FASILITAS UMUM ======
        $facilityRooms = [
            ['code' => 'RPERPUS', 'name' => 'Perpustakaan Sekolah', 'building' => 'Gedung Timur', 'floor' => 1, 'capacity' => 50, 'type' => 'fasilitas'],
            ['code' => 'RUKS', 'name' => 'Ruang UKS', 'building' => 'Gedung Timur', 'floor' => 1, 'capacity' => 8, 'type' => 'fasilitas'],
            ['code' => 'RMUSHOLLA', 'name' => 'Musholla', 'building' => 'Gedung Timur', 'floor' => 1, 'capacity' => 100, 'type' => 'fasilitas'],
            ['code' => 'RKANTIN', 'name' => 'Kantin Sekolah', 'building' => 'Gedung Timur', 'floor' => 1, 'capacity' => 80, 'type' => 'fasilitas'],
            ['code' => 'RSERBAGUNA', 'name' => 'Aula Serbaguna', 'building' => 'Gedung Baru', 'floor' => 3, 'capacity' => 300, 'type' => 'fasilitas'],
            ['code' => 'RGUDANG', 'name' => 'Gudang Sekolah', 'building' => 'Gedung TFLM', 'floor' => 1, 'capacity' => 5, 'type' => 'penyimpanan'],
            ['code' => 'RTERBUKA', 'name' => 'Lapangan Olahraga', 'building' => 'Gedung Timur', 'floor' => 1, 'capacity' => 200, 'type' => 'fasilitas'],
        ];
        $rooms = array_merge($rooms, $facilityRooms);

        // Persist rooms to database
        foreach ($rooms as $room) {
            $typeMap = [
                'lab' => 'lab',
                'kelas' => 'kelas',
                'aula' => 'aula',
                'kantor' => 'lainnya',
                'fasilitas' => 'lainnya',
                'penyimpanan' => 'lainnya',
            ];

            $type = $typeMap[$room['type']] ?? 'lainnya';

            // Map building name to building_id
            $building = Building::where('name', $room['building'])->first();
            $building_id = $building?->id;

            Room::create([
                'code' => $room['code'],
                'name' => $room['name'],
                'building_id' => $building_id,
                'floor' => $room['floor'] ?? null,
                'capacity' => $room['capacity'] ?? null,
                'type' => $type,
                'is_active' => true,
                'notes' => null,
            ]);
        }

        $this->command->info("✅ RoomSeeder: Created " . count($rooms) . " rooms (90 classrooms + labs + facilities).");
    }
}
