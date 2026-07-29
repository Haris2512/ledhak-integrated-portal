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
        User::firstOrCreate(
            ['email' => 'admin@ledhak-unhas.org'],
            [
                'name' => 'Admin Pengurus LeDHaK',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Real Inventory Data UKM LeDHaK UNHAS (77 Items)
        $rawItems = [
            ['name' => 'Spanduk lawan bicara baru', 'qty' => '7 Pcs', 'category' => 'Spanduk & Banner'],
            ['name' => 'Spanduk lawan bicara lama', 'qty' => '2 Pcs', 'category' => 'Spanduk & Banner'],
            ['name' => 'Spanduk X Banner ledhak 25', 'qty' => '1 Pcs', 'category' => 'Spanduk & Banner'],
            ['name' => 'Spanduk X banner ledhak putih merah', 'qty' => '1 Pcs', 'category' => 'Spanduk & Banner'],
            ['name' => 'Spanduk meja', 'qty' => '1 Pcs', 'category' => 'Spanduk & Banner'],
            ['name' => 'Miyako (Rice Cooker / Dispenser)', 'qty' => '1 Unit', 'category' => 'Elektronik & Alat Rumah Tangga'],
            ['name' => 'Speaker KS-2613', 'qty' => '1 Unit', 'category' => 'Elektronik & Sound'],
            ['name' => 'Mic Wireless / Kabel', 'qty' => '2 Pcs', 'category' => 'Elektronik & Sound'],
            ['name' => 'Bendera LeDHaK UNHAS', 'qty' => '1 Pcs', 'category' => 'Perlengkapan Organisasi'],
            ['name' => 'PDH Lama', 'qty' => '4 Pcs', 'category' => 'Seragam & Atribut'],
            ['name' => 'Cas Speaker', 'qty' => '1 Pcs', 'category' => 'Elektronik & Aksesoris'],
            ['name' => 'Tiang Bendera', 'qty' => '1 Pcs', 'category' => 'Perlengkapan Organisasi'],
            ['name' => 'Double Tape Nachi', 'qty' => '1 Roll', 'category' => 'ATK & Perekat'],
            ['name' => 'Lakban Hitam Daimaru', 'qty' => '1 Roll', 'category' => 'ATK & Perekat'],
            ['name' => 'Baterai AA / AAA', 'qty' => '25 Pcs', 'category' => 'Perlengkapan & Konsumsi ATK'],
            ['name' => 'Plastik Merch', 'qty' => '1 Pack', 'category' => 'Merchandise & Kemasan'],
            ['name' => 'Plastik Biru Kecil', 'qty' => '1 Pack', 'category' => 'Perlengkapan Sekretariat'],
            ['name' => 'Cutter Biru', 'qty' => '1 Pcs', 'category' => 'ATK & Alat Potong'],
            ['name' => 'Dos Kue Polos', 'qty' => '23 Pcs', 'category' => 'Perlengkapan Konsumsi'],
            ['name' => 'Dos Kue Motif', 'qty' => '78 Pcs', 'category' => 'Perlengkapan Konsumsi'],
            ['name' => 'Totebag LeDHaK', 'qty' => '5 Pcs', 'category' => 'Merchandise & Souvenir'],
            ['name' => 'Map Batik Hard', 'qty' => '2 Pcs', 'category' => 'ATK & Persuratan'],
            ['name' => 'Map Batik Medium', 'qty' => '1 Pcs', 'category' => 'ATK & Persuratan'],
            ['name' => 'Map Batik Soft', 'qty' => '2 Pcs', 'category' => 'ATK & Persuratan'],
            ['name' => 'Pulpen', 'qty' => '14 Pcs', 'category' => 'ATK & Alat Tulis'],
            ['name' => 'Map LeDHaK', 'qty' => '5 Pcs', 'category' => 'ATK & Persuratan'],
            ['name' => 'Map Bludru', 'qty' => '3 Pcs', 'category' => 'ATK & Persuratan'],
            ['name' => 'Bingkai Sertifikat', 'qty' => '2 Pcs', 'category' => 'Dokumentasi & Penghargaan'],
            ['name' => 'Plastik Kecil', 'qty' => '1 Pack', 'category' => 'Perlengkapan Sekretariat'],
            ['name' => 'Merch Package', 'qty' => '1 Pack', 'category' => 'Merchandise & Souvenir'],
            ['name' => 'Cutter Orange Hitam', 'qty' => '1 Pcs', 'category' => 'ATK & Alat Potong'],
            ['name' => 'Sedotan', 'qty' => '66 Pcs', 'category' => 'Perlengkapan Konsumsi'],
            ['name' => 'Gelas Plastik', 'qty' => '6 Pcs', 'category' => 'Perlengkapan Konsumsi'],
            ['name' => 'Penutup Gelas', 'qty' => '31 Pcs', 'category' => 'Perlengkapan Konsumsi'],
            ['name' => 'Lakban Hitam Tipis', 'qty' => '1 Roll', 'category' => 'ATK & Perekat'],
            ['name' => 'Lakban Coklat', 'qty' => '1 Roll', 'category' => 'ATK & Perekat'],
            ['name' => 'Pita Kawat', 'qty' => '1 Roll', 'category' => 'ATK & Aksesoris'],
            ['name' => 'Double Tape Hijau', 'qty' => '1 Roll', 'category' => 'ATK & Perekat'],
            ['name' => 'Slot Id Card B2', 'qty' => '14 Pcs', 'category' => 'Perlengkapan Kepanitiaan'],
            ['name' => 'Staples Tembak', 'qty' => '1 Pcs', 'category' => 'ATK & Perkakas'],
            ['name' => 'Isian Staples Tembak', 'qty' => '1 Pack', 'category' => 'ATK & Perkakas'],
            ['name' => 'Gelas Plastik Polkadot', 'qty' => '33 Pcs', 'category' => 'Perlengkapan Konsumsi'],
            ['name' => 'Bola Dekorasi / Olahraga', 'qty' => '8 Pcs', 'category' => 'Perlengkapan Olahraga & Acara'],
            ['name' => 'Benang Wol Biru', 'qty' => '1 Roll', 'category' => 'ATK & Kerajinan'],
            ['name' => 'Klip Kertas', 'qty' => '1 Pack', 'category' => 'ATK & Persuratan'],
            ['name' => 'Tanaman Hias / Daun Dekorasi', 'qty' => '1 Pot', 'category' => 'Dekorasi Sekretariat'],
            ['name' => 'Gelas Polkadot Merah', 'qty' => '27 Pcs', 'category' => 'Perlengkapan Konsumsi'],
            ['name' => 'Plastik Hitam Sedang', 'qty' => '15 Pcs', 'category' => 'Perlengkapan Sekretariat'],
            ['name' => 'Rak Display Putar', 'qty' => '1 Pcs', 'category' => 'Perlengkapan Pameran'],
            ['name' => 'Plakat Juara 2 PLC 2020', 'qty' => '1 Pcs', 'category' => 'Piala & Trofi Presitasi'],
            ['name' => 'Piala Juara 3 Cinta Buku', 'qty' => '1 Pcs', 'category' => 'Piala & Trofi Presitasi'],
            ['name' => 'Piala Juara 2 Empati', 'qty' => '1 Pcs', 'category' => 'Piala & Trofi Presitasi'],
            ['name' => 'Piala Juara 3 Empati', 'qty' => '1 Pcs', 'category' => 'Piala & Trofi Presitasi'],
            ['name' => 'Plakat Juara 1 Haluoleo', 'qty' => '1 Pcs', 'category' => 'Piala & Trofi Presitasi'],
            ['name' => 'Spanduk Lawan Bicara Besar Merah', 'qty' => '2 Pcs', 'category' => 'Spanduk & Banner'],
            ['name' => 'Spanduk Lawan Bicara Besar Merah Putih', 'qty' => '2 Pcs', 'category' => 'Spanduk & Banner'],
            ['name' => 'Sapu Lidi', 'qty' => '1 Pcs', 'category' => 'Kebersihan Sekretariat'],
            ['name' => 'Sapu Ijuk', 'qty' => '1 Pcs', 'category' => 'Kebersihan Sekretariat'],
            ['name' => 'Tempat Sampah', 'qty' => '1 Pcs', 'category' => 'Kebersihan Sekretariat'],
            ['name' => 'Terminal Kabel Stopkontak', 'qty' => '1 Pcs', 'category' => 'Elektronik & Kabel'],
            ['name' => 'Keset Kaki', 'qty' => '1 Pcs', 'category' => 'Perlengkapan Sekretariat'],
            ['name' => 'Karpet Sekretariat', 'qty' => '1 Pcs', 'category' => 'Perlengkapan Sekretariat'],
            ['name' => 'Stempel Delegasi LeDHaK', 'qty' => '1 Pcs', 'category' => 'Stempel & Legalisasi'],
            ['name' => 'Stempel Panpel LeDHaK', 'qty' => '1 Pcs', 'category' => 'Stempel & Legalisasi'],
            ['name' => 'Stempel Utama LeDHaK UNHAS', 'qty' => '1 Pcs', 'category' => 'Stempel & Legalisasi'],
            ['name' => 'Palu Sidang', 'qty' => '1 Pcs', 'category' => 'Perlengkapan Persidangan'],
            ['name' => 'Gunting', 'qty' => '2 Pcs', 'category' => 'ATK & Alat Potong'],
            ['name' => 'Amplop Surat', 'qty' => '1 Pack', 'category' => 'ATK & Persuratan'],
            ['name' => 'Pulpen Pack', 'qty' => '1 Pack', 'category' => 'ATK & Alat Tulis'],
            ['name' => 'Cutter Besar', 'qty' => '1 Pcs', 'category' => 'ATK & Alat Potong'],
            ['name' => 'Cutter Kecil', 'qty' => '1 Pcs', 'category' => 'ATK & Alat Potong'],
            ['name' => 'Lakban Hitam Besar', 'qty' => '1 Roll', 'category' => 'ATK & Perekat'],
            ['name' => 'Klip Paper', 'qty' => '1 Pack', 'category' => 'ATK & Persuratan'],
            ['name' => 'Isi Klip Staples', 'qty' => '2 Pack', 'category' => 'ATK & Persuratan'],
            ['name' => 'Kertas Linen Sertifikat', 'qty' => '20 Lembar', 'category' => 'ATK & Kertas'],
            ['name' => 'Map L Bening', 'qty' => '6 Pcs', 'category' => 'ATK & Persuratan'],
            ['name' => 'Kertas HVS A4 80gr', 'qty' => '1 Rim', 'category' => 'ATK & Kertas'],
        ];

        foreach ($rawItems as $index => $data) {
            $codeNumber = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $itemCode = "INV-LDK-{$codeNumber}";
            $scanUrl = url("/api/public/inventory/{$itemCode}");
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($scanUrl);

            Item::firstOrCreate(
                ['item_code' => $itemCode],
                [
                    'name' => $data['name'],
                    'category' => $data['category'],
                    'description' => "Jumlah ketersediaan: {$data['qty']}. Perlengkapan resmi UKM LeDHaK UNHAS.",
                    'status' => 'Tersedia',
                    'qr_code_url' => $qrUrl,
                    'photo_path' => "items/item-{$codeNumber}.jpg",
                ]
            );
        }

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
