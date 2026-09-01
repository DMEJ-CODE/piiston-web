<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\GaragePurchaseOrder;
use App\Models\Garages\GarageSupplier;
use App\Services\Garages\PurchaseOrderService;
use Illuminate\Http\Request;

class GaragePurchaseOrderController extends Controller
{
    public function __construct(protected PurchaseOrderService $purchaseOrderService) {}

    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = GaragePurchaseOrder::where('branch_id', $branch->id)->with(['supplier', 'items']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('supplier', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $purchaseOrders = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('garage.purchase-orders.index', [
            'purchaseOrders' => $purchaseOrders,
            'branch' => $branch,
        ]);
    }

    public function create(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $suppliers = GarageSupplier::where('branch_id', $branch->id)->get();
        $parts = \App\Models\Garages\RepairPart::where('branch_id', $branch->id)->get();

        return view('garage.purchase-orders.create', [
            'branch' => $branch,
            'suppliers' => $suppliers,
            'parts' => $parts,
        ]);
    }

    public function store(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $request->validate([
            'supplier_id' => ['required', 'exists:garage_suppliers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.part_id' => ['nullable', 'exists:repair_parts,id'],
            'items.*.part_name' => ['required', 'string', 'max:255'],
            'items.*.part_number' => ['nullable', 'string', 'max:100'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $supplier = GarageSupplier::where('branch_id', $branch->id)->findOrFail($request->supplier_id);
        $this->purchaseOrderService->createPurchaseOrder($branch, $supplier, $request->validated());

        return redirect()->route('garage.purchase-orders.index')->with('success', 'Commande créée avec succès.');
    }

    public function edit(Request $request, $purchaseOrderId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $purchaseOrder = GaragePurchaseOrder::where('branch_id', $branch->id)
            ->with(['supplier', 'items'])
            ->findOrFail($purchaseOrderId);

        $suppliers = GarageSupplier::where('branch_id', $branch->id)->get();
        $parts = RepairPart::where('branch_id', $branch->id)->get();

        return view('garage.purchase-orders.edit', [
            'branch' => $branch,
            'purchaseOrder' => $purchaseOrder,
            'suppliers' => $suppliers,
            'parts' => $parts,
        ]);
    }

    public function update(Request $request, $purchaseOrderId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $purchaseOrder = GaragePurchaseOrder::where('branch_id', $branch->id)->findOrFail($purchaseOrderId);

        $request->validate([
            'supplier_id' => ['required', 'exists:garage_suppliers,id'],
            'status' => ['nullable', 'string', 'in:draft,ordered,received,cancelled'],
        ]);

        $purchaseOrder->update($request->validated());

        return redirect()->route('garage.purchase-orders.index')->with('success', 'Commande mise à jour avec succès.');
    }

    public function receive(Request $request, $purchaseOrderId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $purchaseOrder = GaragePurchaseOrder::where('branch_id', $branch->id)->findOrFail($purchaseOrderId);

        $request->validate([
            'received_items' => ['required', 'array'],
            'received_items.*' => ['required', 'integer', 'min:0'],
        ]);

        $this->purchaseOrderService->receiveItems($purchaseOrder, $request->received_items);

        return redirect()->route('garage.purchase-orders.index')->with('success', 'Articles reçus et stock mis à jour.');
    }

    public function destroy(Request $request, $purchaseOrderId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $purchaseOrder = GaragePurchaseOrder::where('branch_id', $branch->id)->findOrFail($purchaseOrderId);
        $purchaseOrder->delete();

        return redirect()->route('garage.purchase-orders.index')->with('success', 'Commande supprimée avec succès.');
    }
}
