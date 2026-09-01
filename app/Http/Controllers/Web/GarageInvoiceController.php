<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\GarageInvoice;
use Illuminate\Http\Request;

class GarageInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = GarageInvoice::whereHas('repairOrder', function ($q) use ($branch) {
            $q->where('branch_id', $branch->id);
        })->with(['repairOrder.vehicle', 'repairOrder.garageCustomer.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('repairOrder.vehicle', function ($q) use ($search) {
                $q->where('license_plate', 'like', "%{$search}%");
            })->orWhereHas('repairOrder.garageCustomer.user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('garage.invoices.index', [
            'invoices' => $invoices,
            'branch' => $branch,
        ]);
    }
}
