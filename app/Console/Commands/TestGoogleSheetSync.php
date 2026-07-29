<?php

namespace App\Console\Commands;

use App\Services\GoogleSheetService;
use Illuminate\Console\Command;

class TestGoogleSheetSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:google-sheet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Google Sheets synchronization via Apps Script';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Mengirim data simulasi peminjaman ke Google Sheets...');

        $success = GoogleSheetService::syncLoanRecord([
            'item_code' => 'INV-LDK-001',
            'item_name' => 'Proyektor Epson EB-X400 (Tes)',
            'borrower_name' => 'Penguji Sistem',
            'borrower_phone' => '081234567890',
            'loan_date' => now()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'status' => 'Active',
        ]);

        if ($success) {
            $this->info('✅ SUKSES: Data simulasi berhasil dikirim ke Google Sheets!');
            return Command::SUCCESS;
        } else {
            $this->warn('⚠️ PERHATIAN: Data belum terkirim. Pastikan GOOGLE_APPS_SCRIPT_URL di .env sudah diisi dengan URL Web App Apps Script Anda.');
            return Command::FAILURE;
        }
    }
}
