<?php

namespace App\Livewire\Garage;

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCompany;
use App\Models\Globalization\Country;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Setup extends Component
{
    use WithFileUploads;

    public $step = 1;

    // Type Choice
    public $has_annexes = false;

    // Company Info
    public $company_name;

    public $company_legal_name;

    public $company_registration_number;

    public $company_tax_number;

    public $company_email;

    public $company_phone;

    public $company_website;

    public $company_description;

    public $company_logo;

    public $country_id;

    // Branch Info
    public $branch_name = 'Siège Principal';

    public $branch_email;

    public $branch_phone;

    public $branch_address;

    public $branch_city;

    public $latitude;

    public $longitude;

    public function mount()
    {
        $user = Auth::user();
        $this->company_email = $user->email;
        $this->company_phone = $user->phone;
        $this->branch_email = $user->email;
        $this->branch_phone = $user->phone;
        $this->country_id = $user->country_id ?: Country::first()?->id;

        $company = GarageCompany::where('owner_id', $user->id)->first();
        if ($company) {
            $this->has_annexes = $company->has_annexes;
            $this->step = 3;
        }
    }

    public function selectType($withAnnexes)
    {
        $this->has_annexes = $withAnnexes;
        $this->step = 2;
    }

    public function createCompany()
    {
        $this->validate([
            'company_name' => 'required|string|max:255',
            'company_legal_name' => 'nullable|string|max:255',
            'company_registration_number' => 'nullable|string|max:100',
            'company_tax_number' => 'nullable|string|max:100',
            'company_email' => 'required|email|max:255',
            'company_phone' => 'required|string|max:20',
            'company_website' => 'nullable|url|max:255',
            'company_description' => 'nullable|string|max:1000',
            'country_id' => 'required|exists:countries,id',
            'company_logo' => 'nullable|image|max:2048',
        ]);

        $logoPath = null;
        if ($this->company_logo) {
            $logoPath = $this->company_logo->store('garage_logos', 'public');
        }

        $company = GarageCompany::create([
            'owner_id' => Auth::id(),
            'has_annexes' => $this->has_annexes,
            'country_id' => $this->country_id,
            'name' => $this->company_name,
            'legal_name' => $this->company_legal_name,
            'registration_number' => $this->company_registration_number,
            'tax_number' => $this->company_tax_number,
            'email' => $this->company_email,
            'phone' => $this->company_phone,
            'website' => $this->company_website,
            'description' => $this->company_description,
            'logo' => $logoPath,
            'status' => true,
        ]);

        $this->step = 3;
    }

    public function createBranch()
    {
        $this->validate([
            'branch_name' => 'required|string|max:255',
            'branch_email' => 'nullable|email|max:255',
            'branch_phone' => 'nullable|string|max:20',
            'branch_address' => 'nullable|string|max:500',
            'branch_city' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $company = GarageCompany::where('owner_id', Auth::id())->firstOrFail();

        $branch = GarageBranch::create([
            'company_id' => $company->id,
            'manager_id' => Auth::id(),
            'name' => $this->branch_name,
            'email' => $this->branch_email,
            'phone' => $this->branch_phone,
            'latitude' => $this->latitude ?: 4.0511,
            'longitude' => $this->longitude ?: 9.7679,
            'status' => true,
        ]);

        // Note: Address logic might need more detail if we want to use the addresses table properly

        session(['active_garage_branch_id' => $branch->id]);

        return redirect()->route('garage.dashboard');
    }

    #[Layout('layouts.setup')] // Using the new clean setup layout
    public function render()
    {
        return view('livewire.garage.setup', [
            'countries' => Country::all(),
        ]);
    }
}
