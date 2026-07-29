<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use App\Models\Item;
use App\Models\LoanRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    /**
     * Record loan details and update item status to 'Dipinjam'.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'borrower_name' => ['required', 'string', 'max:255'],
            'borrower_phone' => ['required', 'string', 'max:50'],
            'loan_date' => ['required', 'date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:loan_date'],
        ]);

        $item = Item::findOrFail($validated['item_id']);

        if ($item->status !== 'Tersedia') {
            throw ValidationException::withMessages([
                'item_id' => ["Barang {$item->name} saat ini tidak tersedia untuk dipinjam (Status: {$item->status})."],
            ]);
        }

        $loanRecord = DB::transaction(function () use ($validated, $item, $request) {
            // Update item status to 'Dipinjam'
            $item->update(['status' => 'Dipinjam']);

            // Create circulation loan record
            $loan = LoanRecord::create([
                'item_id' => $item->id,
                'borrower_name' => $validated['borrower_name'],
                'borrower_phone' => $validated['borrower_phone'],
                'loan_date' => $validated['loan_date'],
                'return_date' => $validated['return_date'] ?? null,
                'status' => 'Active',
            ]);

            // Create inventory log
            InventoryLog::create([
                'item_id' => $item->id,
                'user_id' => $request->user()->id,
                'action' => 'LOANED',
                'notes' => "Peminjaman dikonfirmasi oleh admin untuk peminjam: {$validated['borrower_name']} ({$validated['borrower_phone']}).",
            ]);

            return $loan;
        });

        return response()->json([
            'message' => 'Peminjaman berhasil dicatat dan status barang diubah menjadi Dipinjam.',
            'data' => $loanRecord->load('item'),
        ], 201);
    }
}
