<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLoanRequest;
use App\Models\InventoryLog;
use App\Models\Item;
use App\Models\LoanRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    /**
     * Record loan details, update item status to 'Dipinjam', and insert log entries within a DB transaction.
     */
    public function store(StoreLoanRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $item = Item::findOrFail($validated['item_id']);

        if ($item->status !== 'Tersedia') {
            throw ValidationException::withMessages([
                'item_id' => ["Barang {$item->name} saat ini tidak tersedia untuk dipinjam (Status: {$item->status})."],
            ]);
        }

        $loanRecord = DB::transaction(function () use ($validated, $item, $request) {
            // 1. Update item status to 'Dipinjam'
            $item->update(['status' => 'Dipinjam']);

            // 2. Create circulation loan record
            $loan = LoanRecord::create([
                'item_id' => $item->id,
                'borrower_name' => $validated['borrower_name'],
                'borrower_phone' => $validated['borrower_phone'],
                'loan_date' => $validated['loan_date'],
                'return_date' => $validated['return_date'] ?? null,
                'status' => 'Active',
            ]);

            // 3. Create inventory audit log
            InventoryLog::create([
                'item_id' => $item->id,
                'user_id' => $request->user()->id,
                'action' => 'LOANED',
                'notes' => "Peminjaman dikonfirmasi oleh admin untuk peminjam: {$validated['borrower_name']} ({$validated['borrower_phone']}).",
            ]);

            return $loan;
        });

        return response()->json([
            'message' => 'Peminjaman berhasil dicatat, status barang diubah menjadi Dipinjam, dan log inventaris disimpan.',
            'data' => $loanRecord->load('item'),
        ], 201);
    }
}
