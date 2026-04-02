<?php

namespace App\Services;

class LocationService
{
    public function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371000;

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return $angle * $earthRadius;
    }

    public function checkRadius(
        float $branchLat,
        float $branchLon,
        float $userLat,
        float $userLon,
        float|int $radius
    ): array {
        $distance = $this->calculateDistance(
            $branchLat,
            $branchLon,
            $userLat,
            $userLon
        );

        return [
            'distance' => $distance,
            'inside_radius' => $distance <= $radius,
        ];
    }
}
