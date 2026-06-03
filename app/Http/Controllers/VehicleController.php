<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use App\Services\VehicleService;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    protected $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
        $this->authorizeResource(Vehicle::class, 'vehicle');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'type', 'status', 'sort_by', 'sort_order']);
        $perPage = $request->get('per_page', 15);

        $vehicles = $this->vehicleService->getPaginatedVehicles($filters, $perPage);

        return $this->success(
            VehicleResource::collection($vehicles)->response()->getData(true),
            'Vehicles retrieved successfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        try {
            $vehicle = $this->vehicleService->createVehicle($request->validated(), $request->user()->id);

            return $this->success(
                new VehicleResource($vehicle),
                'Vehicle created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return $this->success(
            new VehicleResource($vehicle->load(['creator', 'updater'])),
            'Vehicle retrieved successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        try {
            $updatedVehicle = $this->vehicleService->updateVehicle($vehicle, $request->validated(), $request->user()->id);

            return $this->success(
                new VehicleResource($updatedVehicle),
                'Vehicle updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        try {
            $this->vehicleService->deleteVehicle($vehicle);

            return $this->success(null, 'Vehicle deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }
}
