<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ShopSettingsController extends Controller
{
    /**
     * Verificar que el usuario es administrador
     */
    protected function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Acceso no autorizado');
        }
    }

    /**
     * Mostrar el panel de configuración de la tienda
     */
    public function index()
    {
        $this->checkAdmin();

        $settings = ShopSetting::getActiveSettings();

        return Inertia::render('Admin/ShopSettings', [
            'settings' => $settings
        ]);
    }

    /**
     * Actualizar la configuración de la tienda
     */
// En app/Http/Controllers/Admin/ShopSettingsController.php - método update
public function update(Request $request)
{
    $this->checkAdmin();

    $validated = $request->validate([
        'shop_name' => 'required|string|max:255',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:50',
        'address' => 'required|string|max:500',
        'ruc' => 'required|string|max:20',
        'about_us' => 'required|string|max:2000',
        'primary_color' => 'required|string|max:7',
        'secondary_color' => 'required|string|max:7',
    ]);

    try {
        \Log::info('Iniciando actualización de shop settings', $validated);

        // Obtener o crear la configuración
        $settings = ShopSetting::where('is_active', true)->first();
        
        if (!$settings) {
            $settings = new ShopSetting();
            $settings->is_active = true;
            \Log::info('Creando nueva configuración de tienda');
        } else {
            \Log::info('Actualizando configuración existente ID: ' . $settings->id);
        }

        $settings->shop_name = $validated['shop_name'];
        $settings->email = $validated['email'];
        $settings->phone = $validated['phone'];
        $settings->address = $validated['address'];
        $settings->ruc = $validated['ruc'];
        $settings->about_us = $validated['about_us'];
        $settings->primary_color = $validated['primary_color'];
        $settings->secondary_color = $validated['secondary_color'];

        // Manejar upload del logo
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            
            // Crear directorio si no existe
            $logoDir = public_path('images/logo');
            if (!is_dir($logoDir)) {
                mkdir($logoDir, 0755, true);
            }

            // Eliminar logo anterior si existe
            if ($settings->logo_url && !Str::startsWith($settings->logo_url, ['http://', 'https://'])) {
                $oldLogoPath = public_path(parse_url($settings->logo_url, PHP_URL_PATH));
                if (file_exists($oldLogoPath) && is_file($oldLogoPath)) {
                    unlink($oldLogoPath);
                    \Log::info('Logo anterior eliminado: ' . $oldLogoPath);
                }
            }

            // Guardar nuevo logo
            $logoName = 'logo-' . uniqid() . '.' . $logoFile->getClientOriginalExtension();
            $logoFile->move($logoDir, $logoName);
            
            $settings->logo_url = '/images/logo/' . $logoName;
            \Log::info('Nuevo logo guardado: ' . $settings->logo_url);
        }

        $settings->save();
        \Log::info('Configuración guardada exitosamente', $settings->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Configuración de la tienda actualizada correctamente',
            'settings' => $settings
        ]);

    } catch (\Exception $e) {
        \Log::error('Error al actualizar shop settings: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar la configuración: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Obtener la configuración actual de la tienda (para APIs)
     */
    public function getSettings()
    {
        $settings = ShopSetting::getActiveSettings();
        
        return response()->json([
            'success' => true,
            'settings' => $settings
        ]);
    }
}