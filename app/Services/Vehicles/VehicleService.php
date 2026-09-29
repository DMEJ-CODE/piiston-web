<?php

namespace App\Services\Vehicles;

use App\Models\Vehicles\Vehicle;
use App\Repositories\Vehicles\VehicleRepositoryInterface;
use App\Services\Identity\ActivityService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class VehicleService
{
    protected $vehicleRepository;

    protected $activityService;

    public function __construct(VehicleRepositoryInterface $vehicleRepository, ActivityService $activityService)
    {
        $this->vehicleRepository = $vehicleRepository;
        $this->activityService = $activityService;
    }

    public function createVehicle(array $data): Vehicle
    {
        $photo = $data['photo'] ?? null;
        unset($data['photo']);

        $data['owner_id'] = $data['owner_id'] ?? Auth::id();
        $vehicle = $this->vehicleRepository->create($data);

        if ($photo && $photo instanceof UploadedFile) {
            $path = $photo->store('vehicles', 'public');
            $vehicle->images()->create([
                'image_url' => $path,
                'type' => 'main',
            ]);
        }

        // Log activity
        $this->activityService->log(
            $vehicle->owner,
            'VEHICLE_ADDED',
            'Nouveau véhicule ajouté',
            "Votre véhicule {$vehicle->brand->name} {$vehicle->model->name} a été ajouté avec succès.",
            $vehicle
        );

        // Log history
        $vehicle->history()->create([
            'event_type' => 'REGISTRATION',
            'description' => 'Vehicle registered in the system.',
            'date' => now(),
            'created_by' => Auth::id(),
        ]);

        return $vehicle;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateVehicle(Vehicle $vehicle, array $data): Vehicle
    {
        $photo = $data['photo'] ?? null;
        unset($data['photo']);

        $this->vehicleRepository->update($vehicle->id, $data);

        if ($photo && $photo instanceof UploadedFile) {
            $path = $photo->store('vehicles', 'public');
            $vehicle->images()->create([
                'image_url' => $path,
                'type' => 'main',
            ]);
        }

        $vehicle->history()->create([
            'event_type' => 'UPDATE',
            'description' => 'Vehicle details updated.',
            'date' => now(),
            'created_by' => Auth::id(),
        ]);

        return $vehicle->refresh();
    }

    public function deleteVehicle(Vehicle $vehicle): void
    {
        $vehicle->delete();
    }

    public function updateMileage(Vehicle $vehicle, int $newMileage): void
    {
        $oldMileage = $vehicle->mileage;
        $vehicle->update(['mileage' => $newMileage]);

        // Log usage
        $vehicle->usageLogs()->create([
            'distance_km' => $newMileage - $oldMileage,
            'start_time' => now(),
            'purpose' => 'MILEAGE_UPDATE',
        ]);

        // Log history
        $vehicle->history()->create([
            'event_type' => 'MILEAGE_UPDATE',
            'description' => "Mileage updated from $oldMileage to $newMileage km.",
            'date' => now(),
            'created_by' => Auth::id(),
        ]);
    }
}
