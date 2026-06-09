<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Http\Requests\StoreRouteRequest;
use App\Http\Requests\UpdateRouteRequest;
use App\Http\Resources\RouteResource;
use App\Services\RouteService;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    protected $routeService;

    public function __construct(RouteService $routeService)
    {
        $this->routeService = $routeService;
        $this->authorizeResource(Route::class, 'route');
    }

    public function index(Request $request)
    {
        $routes = $this->routeService->getPaginated(
            $request->input('per_page', 15),
            $request->only(['search', 'vehicle_type_id'])
        );

        return $this->success(
            RouteResource::collection($routes)->response()->getData(true),
            'Routes retrieved successfully'
        );
    }

    public function store(StoreRouteRequest $request)
    {
        try {
            $route = $this->routeService->create($request->validated());
            return $this->success(new RouteResource($route), 'Route created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create route', 500, $e->getMessage());
        }
    }

    public function show(Route $route)
    {
        return $this->success(new RouteResource($route->load(['vehicleType', 'stops'])), 'Route retrieved successfully');
    }

    public function update(UpdateRouteRequest $request, Route $route)
    {
        try {
            $route = $this->routeService->update($route, $request->validated());
            return $this->success(new RouteResource($route), 'Route updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update route', 500, $e->getMessage());
        }
    }

    public function destroy(Route $route)
    {
        try {
            $this->routeService->delete($route);
            return $this->success(null, 'Route deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete route', 500, $e->getMessage());
        }
    }
}
