<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    /**
     * Get inventory catalog list for public view.
     */
    public function index(): JsonResponse
    {
        $items = Item::latest()->paginate(15);

        return response()->json($items);
    }

    /**
     * Get item detail by item_code (for catalog viewing & QR scanning).
     */
    public function show(string $item_code): JsonResponse
    {
        $item = Item::where('item_code', $item_code)->firstOrFail();

        return response()->json([
            'data' => $item,
        ]);
    }
}
