<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoogleMapsService;
use Illuminate\Http\Request;

class GoogleMapsController extends Controller
{
    protected $mapsService;

    public function __construct(GoogleMapsService $mapsService)
    {
        $this->mapsService = $mapsService;
    }

    /**
     * Autocompletar direcciones
     */
    public function autocomplete(Request $request)
    {
        $request->validate([
            'input' => 'required|string|min:3'
        ]);

        try {
            $results = $this->mapsService->autocompleteAddress($request->input);
            
            return response()->json([
                'success' => true,
                'predictions' => $results['predictions'] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar direcciones'
            ], 500);
        }
    }

    /**
     * Obtener detalles de una dirección
     */
    public function placeDetails(Request $request)
    {
        $request->validate([
            'place_id' => 'required|string'
        ]);

        try {
            $details = $this->mapsService->getPlaceDetails($request->place_id);
            
            return response()->json([
                'success' => true,
                'result' => $details['result'] ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener detalles del lugar'
            ], 500);
        }
    }

    /**
     * Geocodificación inversa (coordenadas a dirección)
     */
    public function reverseGeocode(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ]);

        try {
            $results = $this->mapsService->reverseGeocode(
                $request->lat, 
                $request->lng
            );
            
            return response()->json([
                'success' => true,
                'address' => $results['results'][0]['formatted_address'] ?? null,
                'results' => $results['results'] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la dirección'
            ], 500);
        }
    }

    /**
     * Geocodificación (dirección a coordenadas)
     */
    public function geocode(Request $request)
    {
        $request->validate([
            'address' => 'required|string'
        ]);

        try {
            $results = $this->mapsService->geocodeAddress($request->address);
            
            return response()->json([
                'success' => true,
                'location' => $results['results'][0]['geometry']['location'] ?? null,
                'results' => $results['results'] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al geocodificar la dirección'
            ], 500);
        }
    }

    /**
     * Calcular distancia y tiempo de entrega
     */
    public function calculateDelivery(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string'
        ]);

        try {
            $results = $this->mapsService->calculateDistance(
                $request->origin,
                $request->destination
            );

            $element = $results['rows'][0]['elements'][0] ?? null;
            
            return response()->json([
                'success' => true,
                'distance' => $element['distance']['text'] ?? null,
                'duration' => $element['duration']['text'] ?? null,
                'status' => $element['status'] ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular la distancia'
            ], 500);
        }
    }
}