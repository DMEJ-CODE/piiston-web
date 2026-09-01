<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\GaragePayment;
use Illuminate\Http\Request;

class GaragePaymentController extends Controller
{
    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = GaragePayment::whereHas('invoice.repairOrder', function ($q) use ($branch) {
            $q->where('branch_id', $branch->id);
        })->with(['invoice.repairOrder.garageCustomer.user']);

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        $payments = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('garage.payments.index', [
            'payments' => $payments,
            'branch' => $branch,
        ]);
    }
}
