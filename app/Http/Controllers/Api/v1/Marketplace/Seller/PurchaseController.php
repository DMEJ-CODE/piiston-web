<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\PurchaseOrder;
use App\Services\Marketplace\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $pos = PurchaseOrder::with('supplier')->where('seller_id', $seller->id)->latest()->get();

        return response()->json($pos);
    }

    public function store(Request $request): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'currency_id' => 'required|exists:currencies,id',
            'po_number' => 'required|string|unique:purchase_orders,po_number',
            'expected_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.part_id' => 'required|exists:spare_parts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($seller, $data) {
            $po = PurchaseOrder::create([
                'seller_id' => $seller->id,
                'supplier_id' => $data['supplier_id'],
                'po_number' => $data['po_number'],
                'currency_id' => $data['currency_id'],
                'expected_date' => $data['expected_date'],
                'status' => 'DRAFT',
            ]);

            $total = 0;
            foreach ($data['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_cost'];
                $po->items()->create([
                    'part_id' => $item['part_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $po->update(['total_amount' => $total]);

            return response()->json($po->load('items'), 201);
        });
    }

    public function receive(Request $request, int $id): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $po = PurchaseOrder::with('items')->where('seller_id', $seller->id)->findOrFail($id);

        if ($po->status === 'RECEIVED') {
            return response()->json(['message' => 'Order already received'], 422);
        }

        return DB::transaction(function () use ($po) {
            foreach ($po->items as $item) {
                // Find or create listing for this part in seller's store
                // In professional flow, receiving PO usually populates inventory
                // We'll look for an existing listing or skip for now if not mapped
                $listing = $po->seller->listings()->where('part_id', $item['part_id'])->first();
                if ($listing) {
                    $this->inventoryService.recordMovement(
                        $listing->id,
                        'PURCHASE',
                        $item->quantity,
                        "PO #{$po->po_number}"
                    );
                }
                $item->update(['received_quantity' => $item->quantity]);
            }

            $po->update(['status' => 'RECEIVED', 'received_date' => now()]);

            return response()->json($po);
        });
    }
}
