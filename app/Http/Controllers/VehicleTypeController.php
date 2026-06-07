<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use App\Http\Requests\StoreVehicleTypeRequest;
use App\Http\Requests\UpdateVehicleTypeRequest;
use App\Http\Resources\VehicleTypeResource;
use App\Services\VehicleTypeService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class VehicleTypeController extends Controller
{
    use ApiResponse;

    protected $vehicleTypeService;

    public function __construct(VehicleTypeService $vehicleTypeService)
    {
        $this->vehicleTypeService = $vehicleTypeService;
        $this->authorizeResource(VehicleType::class, 'vehicle_type');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vehicleTypes = $this->vehicleTypeService->getPaginated(
            $request->input('per_page', 10),
            $request->input('search')
        );

        return $this->success(
            VehicleTypeResource::collection($vehicleTypes)->response()->getData(true),
            'Vehicle types retrieved successfully'
        );
    }

    /**
     * Get options for dropdown.
     */
    public function options()
    {
        // Explicitly check viewAny permission for options since it's not a standard resource method
        $this->authorize('viewAny', VehicleType::class);
        
        $options = $this->vehicleTypeService->getOptions();
        return $this->success($options, 'Vehicle type options retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleTypeRequest $request)
    {
        try {
            $vehicleType = $this->vehicleTypeService->create($request->validated());
            return $this->success(new VehicleTypeResource($vehicleType), 'Vehicle type created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create vehicle type', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleType $vehicleType)
    {
        return $this->success(new VehicleTypeResource($vehicleType), 'Vehicle type retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleTypeRequest $request, VehicleType $vehicleType)
    {
        try {
            $vehicleType = $this->vehicleTypeService->update($vehicleType, $request->validated());
            return $this->success(new VehicleTypeResource($vehicleType), 'Vehicle type updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update vehicle type', 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleType $vehicleType)
    {
        try {
            $this->vehicleTypeService->delete($vehicleType);
            return $this->success(null, 'Vehicle type deleted successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
