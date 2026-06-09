<?php

namespace App\Http\Controllers;

use App\Models\Stop;
use App\Http\Requests\StoreStopRequest;
use App\Http\Requests\UpdateStopRequest;
use App\Http\Resources\StopResource;
use App\Services\StopService;
use Illuminate\Http\Request;

class StopController extends Controller
{
    protected $stopService;

    public function __construct(StopService $stopService)
    {
        $this->stopService = $stopService;
        $this->authorizeResource(Stop::class, 'stop');
    }

    public function index(Request $request)
    {
        $stops = $this->stopService->getPaginated(
            $request->input('per_page', 15),
            $request->input('search')
        );

        return $this->success(
            StopResource::collection($stops)->response()->getData(true),
            'Stops retrieved successfully'
        );
    }

    public function store(StoreStopRequest $request)
    {
        try {
            $stop = $this->stopService->create($request->validated());
            return $this->success(new StopResource($stop), 'Stop created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create stop', 500, $e->getMessage());
        }
    }

    public function show(Stop $stop)
    {
        return $this->success(new StopResource($stop), 'Stop retrieved successfully');
    }

    public function update(UpdateStopRequest $request, Stop $stop)
    {
        try {
            $stop = $this->stopService->update($stop, $request->validated());
            return $this->success(new StopResource($stop), 'Stop updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update stop', 500, $e->getMessage());
        }
    }

    public function destroy(Stop $stop)
    {
        try {
            $this->stopService->delete($stop);
            return $this->success(null, 'Stop deleted successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    public function all()
    {
        $this->authorize('viewAny', Stop::class);
        $stops = $this->stopService->getAll();
        return $this->success(StopResource::collection($stops), 'All stops retrieved successfully');
    }
}
