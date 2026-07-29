<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreItemRequest;
use App\Http\Requests\Admin\UpdateItemRequest;
use App\Models\InventoryLog;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Display listing of inventory items.
     */
    public function index(): JsonResponse
    {
        $items = Item::with(['loanRecords', 'inventoryLogs'])->latest()->paginate(20);

        return response()->json($items);
    }

    /**
     * Store a newly created item in inventory.
     * Automatically generates a unique item_code and QR code URL.
     */
    public function store(StoreItemRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // 1. Generate unique item_code if not provided
        if (empty($validated['item_code'])) {
            do {
                $generatedCode = 'INV-LDK-' . strtoupper(Str::random(6));
            } while (Item::where('item_code', $generatedCode)->exists());

            $validated['item_code'] = $generatedCode;
        }

        // 2. Generate QR Code URL based on public scanner endpoint
        $scanUrl = url('/api/public/inventory/' . $validated['item_code']);
        $validated['qr_code_url'] = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($scanUrl);

        $item = Item::create($validated);

        // Audit log entry
        InventoryLog::create([
            'item_id' => $item->id,
            'user_id' => $request->user()->id,
            'action' => 'CREATED',
            'notes' => "Barang [{$item->item_code}] {$item->name} berhasil ditambahkan ke inventaris dengan QR code otomatis.",
        ]);

        return response()->json([
            'message' => 'Barang inventaris berhasil dibuat.',
            'data' => $item,
        ], 201);
    }

    /**
     * Display specified item detail.
     */
    public function show(Item $item): JsonResponse
    {
        $item->load(['loanRecords', 'inventoryLogs.user']);

        return response()->json([
            'data' => $item,
        ]);
    }

    /**
     * Update specified item in inventory.
     */
    public function update(UpdateItemRequest $request, Item $item): JsonResponse
    {
        $validated = $request->validated();

        // Regenerate QR code if item_code is updated
        if (isset($validated['item_code']) && $validated['item_code'] !== $item->item_code) {
            $scanUrl = url('/api/public/inventory/' . $validated['item_code']);
            $validated['qr_code_url'] = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($scanUrl);
        }

        $oldStatus = $item->status;
        $item->update($validated);

        $notes = 'Data barang berhasil diperbarui.';
        if (isset($validated['status']) && $validated['status'] !== $oldStatus) {
            $notes .= " Status diubah dari {$oldStatus} ke {$validated['status']}.";
        }

        InventoryLog::create([
            'item_id' => $item->id,
            'user_id' => $request->user()->id,
            'action' => 'UPDATED',
            'notes' => $notes,
        ]);

        return response()->json([
            'message' => 'Barang inventaris berhasil diperbarui.',
            'data' => $item,
        ]);
    }

    /**
     * Update item status directly (e.g. 'Tersedia', 'Dipinjam', 'Perbaikan').
     */
    public function updateStatus(Request $request, Item $item): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Tersedia', 'Dipinjam', 'Perbaikan'])],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $oldStatus = $item->status;
        $item->update(['status' => $validated['status']]);

        // Audit log entry
        InventoryLog::create([
            'item_id' => $item->id,
            'user_id' => $request->user()->id,
            'action' => 'STATUS_CHANGE',
            'notes' => $validated['notes'] ?? "Status barang diubah dari {$oldStatus} ke {$validated['status']}.",
        ]);

        return response()->json([
            'message' => "Status barang {$item->name} berhasil diubah menjadi {$validated['status']}.",
            'data' => $item,
        ]);
    }

    /**
     * Remove specified item from inventory.
     */
    public function destroy(Request $request, Item $item): JsonResponse
    {
        $itemCode = $item->item_code;
        $itemName = $item->name;

        $item->delete();

        return response()->json([
            'message' => "Barang [{$itemCode}] {$itemName} berhasil dihapus dari inventaris.",
        ]);
    }
}
