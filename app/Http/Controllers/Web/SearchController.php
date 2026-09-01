<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\GarageAppointment;
use App\Models\Garages\GarageCustomer;
use App\Models\Garages\RepairOrder;
use App\Models\Garages\RepairPart;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function global(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = trim((string) $request->get('q', ''));

        $results = [
            'customers' => [],
            'appointments' => [],
            'repairs' => [],
            'parts' => [],
        ];

        if ($query !== '') {
            $results['customers'] = GarageCustomer::where('branch_id', $branch->id)
                ->whereHas('user', function ($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                })
                ->orWhere('customer_type', 'like', "%{$query}%")
                ->with('user')
                ->limit(10)
                ->get();

            $results['appointments'] = GarageAppointment::where('branch_id', $branch->id)
                ->whereHas('customer.user', function ($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%");
                })
                ->with(['customer.user', 'vehicle'])
                ->limit(10)
                ->get();

            $results['repairs'] = RepairOrder::where('branch_id', $branch->id)
                ->where('problem_description', 'like', "%{$query}%")
                ->with(['vehicle', 'garageCustomer.user'])
                ->limit(10)
                ->get();

            $results['parts'] = RepairPart::where('branch_id', $branch->id)
                ->where('name', 'like', "%{$query}%")
                ->orWhere('part_number', 'like', "%{$query}%")
                ->limit(10)
                ->get();
        }

        return view('garage.search.index', [
            'query' => $query,
            'results' => $results,
            'branch' => $branch,
        ]);
    }
}
