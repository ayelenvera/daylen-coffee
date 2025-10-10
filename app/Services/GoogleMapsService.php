<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleMapsService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_maps.api_key');
    }

    /**
     * Geocodificación: Dirección → Coordenadas
     */
    public function geocodeAddress(string $address)
    {
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key' => $this->apiKey,
            'region' => 'py', // Paraguay
            'components' => 'country:PY'
        ]);

        return $response->json();
    }

    /**
     * Geocodificación inversa: Coordenadas → Dirección
     */
    public function reverseGeocode(float $lat, float $lng)
    {
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'latlng' => "{$lat},{$lng}",
            'key' => $this->apiKey,
            'language' => 'es',
            'region' => 'py'
        ]);

        return $response->json();
    }

    /**
     * Autocompletado de direcciones
     */
    public function autocompleteAddress(string $input)
    {
        $response = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json', [
            'input' => $input,
            'key' => $this->apiKey,
            'components' => 'country:py',
            'language' => 'es',
            'types' => 'address'
        ]);

        return $response->json();
    }

    /**
     * Obtener detalles de un lugar por place_id
     */
    public function getPlaceDetails(string $placeId)
    {
        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id' => $placeId,
            'key' => $this->apiKey,
            'language' => 'es',
            'fields' => 'formatted_address,geometry,name'
        ]);

        return $response->json();
    }

    /**
     * Calcular distancia y tiempo entre dos puntos
     */
    public function calculateDistance($origin, $destination)
    {
        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            'origins' => $origin,
            'destinations' => $destination,
            'key' => $this->apiKey,
            'language' => 'es',
            'units' => 'metric'
        ]);

        return $response->json();
    }
}