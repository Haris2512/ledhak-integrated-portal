<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleSheetService
{
    /**
     * Send loan record payload to Google Apps Script Web App.
     */
    public static function syncLoanRecord(array $loanData): bool
    {
        $webAppUrl = config('services.google_apps_script.url');

        if (empty($webAppUrl) || str_contains($webAppUrl, 'YOUR_DEPLOYMENT_ID')) {
            Log::info('Google Apps Script URL is not fully configured in .env. Skipping Google Sheets sync.');
            return false;
        }

        try {
            $response = Http::timeout(5)
                ->post($webAppUrl, [
                    'type' => 'LOAN_RECORD',
                    'timestamp' => now()->toIso8601String(),
                    'item_code' => $loanData['item_code'] ?? '-',
                    'item_name' => $loanData['item_name'] ?? '-',
                    'borrower_name' => $loanData['borrower_name'] ?? '-',
                    'borrower_phone' => $loanData['borrower_phone'] ?? '-',
                    'loan_date' => $loanData['loan_date'] ?? '-',
                    'return_date' => $loanData['return_date'] ?? '-',
                    'status' => $loanData['status'] ?? 'Active',
                ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Failed to sync loan record to Google Sheets: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send inventory log payload to Google Apps Script Web App.
     */
    public static function syncInventoryLog(array $logData): bool
    {
        $webAppUrl = config('services.google_apps_script.url');

        if (empty($webAppUrl) || str_contains($webAppUrl, 'YOUR_DEPLOYMENT_ID')) {
            return false;
        }

        try {
            $response = Http::timeout(5)
                ->post($webAppUrl, [
                    'type' => 'INVENTORY_LOG',
                    'timestamp' => now()->toIso8601String(),
                    'item_code' => $logData['item_code'] ?? '-',
                    'action' => $logData['action'] ?? '-',
                    'notes' => $logData['notes'] ?? '-',
                    'admin_name' => $logData['admin_name'] ?? 'System',
                ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Failed to sync inventory log to Google Sheets: ' . $e->getMessage());
            return false;
        }
    }
}
