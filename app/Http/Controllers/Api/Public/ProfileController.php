<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * Display organization profile information.
     */
    public function show(): JsonResponse
    {
        return response()->json([
            'organization_name' => 'UKM LeDHaK UNHAS',
            'full_name' => 'Lembaga Debat dan Hak Asasi Manusia Universitas Hasanuddin',
            'description' => 'Unit Kegiatan Mahasiswa di Universitas Hasanuddin yang berfokus pada pengembangan kemampuan penalaran, analisis hukum, debat ilmiah, dan pengkajian isu-isu Hak Asasi Manusia.',
            'vision' => 'Menjadi wadah pengembangan kapasitas intelektual dan kepemimpinan mahasiswa Universitas Hasanuddin dalam bidang penalaran, debat, dan advokasi HAM yang berintegritas.',
            'mission' => [
                'Meningkatkan budaya kritis dan daya nalar mahasiswa melalui kegiatan debat ilmiah.',
                'Menyelenggarakan kajian dan edukasi rutin terkait isu-isu Hak Asasi Manusia.',
                'Memfasilitasi tata kelola inventaris dan sekretariat yang transparan, modern, dan akuntabel.',
            ],
            'contact' => [
                'email' => 'ledhak@unhas.ac.id',
                'whatsapp' => '6281234567890',
                'location' => 'Sekretariat UKM LeDHaK, Gedung PKM Unhas Tamalanrea, Makassar',
            ],
        ]);
    }
}
