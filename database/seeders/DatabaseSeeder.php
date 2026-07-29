<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Pre-seeded Admin / Pengurus Account
        $admin = User::firstOrCreate(
            ['email' => 'admin@ledhak-unhas.org'],
            [
                'name' => 'Admin Pengurus LeDHaK',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Sample Inventory Items
        Item::firstOrCreate(
            ['item_code' => 'INV-LDK-001'],
            [
                'name' => 'Proyektor Epson EB-X400',
                'category' => 'Elektronik',
                'description' => 'Proyektor utama sekretariat untuk presentasi dan kajian rutin.',
                'status' => 'Tersedia',
                'qr_code_url' => 'https://ledhak-unhas.org/qr/INV-LDK-001',
                'photo_path' => 'items/proyektor-ebx400.jpg',
            ]
        );

        Item::firstOrCreate(
            ['item_code' => 'INV-LDK-002'],
            [
                'name' => 'Sound System Portable Wireless',
                'category' => 'Elektronik',
                'description' => 'Sound system portable dan 2 wireless mic untuk perlombaan debat.',
                'status' => 'Tersedia',
                'qr_code_url' => 'https://ledhak-unhas.org/qr/INV-LDK-002',
                'photo_path' => 'items/sound-system.jpg',
            ]
        );

        // 3. Sample News Article
        Article::firstOrCreate(
            ['slug' => 'penerimaan-anggota-baru-ledhak-unhas-2026'],
            [
                'title' => 'Open Recruitment Penerimaan Anggota Baru UKM LeDHaK UNHAS 2026',
                'content' => 'UKM LeDHaK UNHAS secara resmi membuka pendaftaran Anggota Baru untuk seluruh mahasiswa aktif Universitas Hasanuddin.',
                'image_path' => 'articles/oprec-2026.jpg',
                'status' => 'Published',
            ]
        );
    }
}
