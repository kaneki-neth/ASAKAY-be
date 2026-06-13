<?php

namespace App\Http\Controllers;

use App\Models\Stop;
use App\Models\Route as TransportRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NavigationController extends Controller
{
    /**
     * Navigate from origin to destination.
     */
    public function navigate(Request $request)
    {
        $request->validate([
            'origin_lat' => 'required|numeric',
            'origin_lng' => 'required|numeric',
            'dest_lat' => 'required|numeric',
            'dest_lng' => 'required|numeric',
            'vehicle_type_ids' => 'nullable|array',
            'vehicle_type_ids.*' => 'integer',
        ]);

        $originLat = $request->origin_lat;
        $originLng = $request->origin_lng;
        $destLat = $request->dest_lat;
        $destLng = $request->dest_lng;
        $vehicleTypeIds = $request->vehicle_type_ids;

        // 1. Find Nearby Stops (Radius ~500m)
        $originStops = $this->findNearbyStops($originLat, $originLng);
        $destStops = $this->findNearbyStops($destLat, $destLng);

        if ($originStops->isEmpty() || $destStops->isEmpty()) {
            return $this->error('No transport stops found near your location or destination.', 404);
        }

        $itineraries = [];

        // 2. Check for Direct Routes
        $directRoutes = $this->findDirectRoutes($originStops, $destStops, $vehicleTypeIds);
        foreach ($directRoutes as $result) {
            $itineraries[] = [
                'type' => 'direct',
                'description' => "Take {$result->route_name} from {$result->origin_stop_name} to {$result->dest_stop_name}.",
                'segments' => [
                    [
                        'type' => 'walk',
                        'instruction' => "Walk to {$result->origin_stop_name}.",
                        'start' => ['lat' => $originLat, 'lng' => $originLng],
                        'end' => ['lat' => $result->origin_lat, 'lng' => $result->origin_lng]
                    ],
                    [
                        'type' => 'ride',
                        'route_id' => $result->route_id,
                        'route_name' => $result->route_name,
                        'route_color' => $result->route_color,
                        'instruction' => "Ride {$result->route_name} ({$result->route_code})",
                        'from_stop' => $result->origin_stop_name,
                        'to_stop' => $result->dest_stop_name,
                        'polyline' => json_decode($result->polyline)
                    ],
                    [
                        'type' => 'walk',
                        'instruction' => "Walk to destination.",
                        'start' => ['lat' => $result->dest_lat, 'lng' => $result->dest_lng],
                        'end' => ['lat' => $destLat, 'lng' => $destLng]
                    ]
                ]
            ];
        }

        // Return results
        if (empty($itineraries)) {
            return $this->error('No direct routes found with current filters.', 404);
        }

        return $this->success($itineraries, 'Itineraries calculated successfully');
    }

    protected function findNearbyStops($lat, $lng, $radiusKm = 0.5)
    {
        // Haversine formula
        return Stop::select('*')
            ->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', [$lat, $lng, $lat])
            ->having('distance', '<', $radiusKm)
            ->orderBy('distance')
            ->get();
    }

    protected function findDirectRoutes($originStops, $destStops, $vehicleTypeIds = null)
    {
        $originStopIds = $originStops->pluck('id')->toArray();
        $destStopIds = $destStops->pluck('id')->toArray();

        $query = DB::table('route_stop as rs1')
            ->join('route_stop as rs2', 'rs1.route_id', '=', 'rs2.route_id')
            ->join('routes', 'rs1.route_id', '=', 'routes.id')
            ->join('stops as s1', 'rs1.stop_id', '=', 's1.id')
            ->join('stops as s2', 'rs2.stop_id', '=', 's2.id')
            ->whereIn('rs1.stop_id', $originStopIds)
            ->whereIn('rs2.stop_id', $destStopIds)
            ->whereRaw('rs1.order < rs2.order') // Must be in forward direction
            ->where('routes.status', 'active');

        if (!empty($vehicleTypeIds)) {
            $query->whereIn('routes.vehicle_type_id', $vehicleTypeIds);
        }

        return $query->select(
                'routes.id as route_id',
                'routes.name as route_name',
                'routes.code as route_code',
                'routes.color as route_color',
                'routes.polyline',
                's1.name as origin_stop_name',
                's1.latitude as origin_lat',
                's1.longitude as origin_lng',
                's2.name as dest_stop_name',
                's2.latitude as dest_lat',
                's2.longitude as dest_lng'
            )
            ->get();
    }
}
