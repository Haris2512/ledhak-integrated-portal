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
        $url = config('services.google_apps_script.url');
        $this->info("Mengirim data simulasi peminjaman ke Google Sheets...");
        $this->comment("Target URL: {$url}");

        $success = GoogleSheetService::syncLoanRecord([
            'item_code' => 'INV-LDK-001',
            'item_name' => 'Proyektor Epson EB-X400 (Tes Live)',
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
            $this->error('❌ GAGAL: Data belum terkirim ke Google Sheets.');
            $this->line('Silakan periksa storage/logs/laravel.log atau pastikan di Apps Script:');
            $this->line('1. Sudah menambahkan fungsi doGet dan doPost.');
            $this->line('2. Pengaturan Deploy > Who has access diatur ke "Anyone" (Siapa saja).');
            return Command::FAILURE;
        }
    }
}
