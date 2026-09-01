<?php

namespace Database\Seeders;

use App\Models\Garages\GarageAppointment;
use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCustomer;
use App\Models\Garages\RepairOrder;
use App\Models\Garages\VehicleCheckIn;
use App\Models\Garages\VehicleDiagnosis;
use App\Models\User;
use App\Models\Vehicles\Vehicle;
use App\Models\Workflows\DiagnosticMeasurement;
use App\Models\Workflows\RepairProgress;
use App\Models\Workflows\ServiceRequest;
use App\Models\Workflows\VehicleFaultCode;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WorkflowSystemSeeder extends Seeder
{
    public function run(): void
    {
        $client = User::where('email', 'client@piiston.com')->first();
        $mechanic = User::where('email', 'mechanic@piiston.com')->first();
        $vehicle = Vehicle::first();
        $branch = GarageBranch::first();

        if (! $client || ! $vehicle || ! $branch) {
            return;
        }

        // 1. Service Request
        $request = ServiceRequest::create([
            'user_id' => $client->id,
            'vehicle_id' => $vehicle->id,
            'request_type' => 'REPAIR',
            'description' => 'Engine makes a whistling sound when accelerating.',
            'priority' => 'high',
            'status' => 'APPROVED',
        ]);

        // 1b. Create Garage Customer
        $garageCustomer = GarageCustomer::create([
            'branch_id' => $branch->id,
            'user_id' => $client->id,
            'customer_type' => 'Individual',
        ]);

        // 2. Appointment
        $appointment = GarageAppointment::create([
            'request_id' => $request->id,
            'branch_id' => $branch->id,
            'customer_id' => $garageCustomer->id,
            'vehicle_id' => $vehicle->id,
            'scheduled_date' => Carbon::now()->addDay(),
            'duration_minutes' => 120,
            'status' => 'CONFIRMED',
        ]);

        // 3. Check-In
        $checkIn = VehicleCheckIn::create([
            'appointment_id' => $appointment->id,
            'branch_id' => $branch->id,
            'vehicle_id' => $vehicle->id,
            'customer_id' => $garageCustomer->id,
            'received_by' => User::where('email', 'eric@piiston.com')->first()->id,
            'arrival_date' => Carbon::now(),
            'mileage' => 152000,
            'fuel_level' => '50%',
            'status' => 'RECEIVED',
        ]);

        // 4. Repair Order
        $ro = RepairOrder::create([
            'check_in_id' => $checkIn->id,
            'branch_id' => $branch->id,
            'vehicle_id' => $vehicle->id,
            'customer_id' => $garageCustomer->id,
            'assigned_mechanic_id' => $mechanic->id,
            'problem_description' => 'Engine whistling noise diagnosis and repair.',
            'status' => 'DIAGNOSIS',
            'opened_at' => Carbon::now(),
        ]);

        // 5. Diagnosis
        $diagnosis = VehicleDiagnosis::create([
            'repair_order_id' => $ro->id,
            'mechanic_id' => $mechanic->id,
            'symptoms' => 'High pitched whistle during acceleration.',
            'detected_problem' => 'Turbocharger intake hose leak.',
            'root_cause' => 'Loose clamp and worn rubber hose.',
            'solution' => 'Replace intake hose and secure with new clamps.',
            'severity' => 'high',
        ]);

        // 6. Technical Data
        DiagnosticMeasurement::create([
            'diagnosis_id' => $diagnosis->id,
            'parameter' => 'Boost Pressure',
            'value' => '0.8',
            'unit' => 'bar',
            'reference_value' => '1.2 bar',
            'status' => 'LOW',
        ]);

        VehicleFaultCode::create([
            'diagnosis_id' => $diagnosis->id,
            'code' => 'P0299',
            'description' => 'Turbocharger Underboost Condition',
            'severity' => 'high',
        ]);

        // 7. Progress
        RepairProgress::create(['repair_order_id' => $ro->id, 'status' => 'Vehicle Received', 'message' => 'Vehicle is in the workshop.']);
        RepairProgress::create(['repair_order_id' => $ro->id, 'status' => 'Diagnosis Started', 'message' => 'Technician is inspecting the engine.']);
        RepairProgress::create(['repair_order_id' => $ro->id, 'status' => 'Problem Identified', 'message' => 'Found a leak in the turbo intake.']);
    }
}
