<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'item_code' => ['required', 'string', 'max:50', 'unique:items,item_code'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(['Tersedia', 'Dipinjam', 'Perbaikan'])],
            'qr_code_url' => ['nullable', 'string', 'max:255'],
            'photo_path' => ['nullable', 'string', 'max:255'],
        ]);

        $item = Item::create($validated);

        // Audit log creation
        InventoryLog::create([
            'item_id' => $item->id,
            'user_id' => $request->user()->id,
            'action' => 'CREATED',
            'notes' => 'Barang baru berhasil ditambahkan ke sistem inventaris.',
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
    public function update(Request $request, Item $item): JsonResponse
    {
        $validated = $request->validate([
            'item_code' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('items', 'item_code')->ignore($item->id)],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'category' => ['sometimes', 'required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'required', Rule::in(['Tersedia', 'Dipinjam', 'Perbaikan'])],
            'qr_code_url' => ['nullable', 'string', 'max:255'],
            'photo_path' => ['nullable', 'string', 'max:255'],
        ]);

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
