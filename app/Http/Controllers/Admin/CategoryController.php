<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        try {
            // Verificar si el usuario está autenticado
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado',
                    'error' => 'Usuario no autenticado'
                ], 401);
            }

            // Verificar si el usuario es administrador
            if (!auth()->user()->is_admin) {
                return response()->json([
                    'message' => 'Acceso denegado',
                    'error' => 'No tienes permiso para realizar esta acción'
                ], 403);
            }

            // Validar los datos de entrada
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:30', 'unique:categories,name'],
                'emoji' => ['required', 'integer', 'min:0', 'max:28'] // 0-28 son los índices válidos
            ]);
            
            // Crear la categoría
            $category = new Category();
            $category->name = $validated['name'];
            $category->slug = Str::slug($validated['name']);
            $category->emoji = (int)$validated['emoji']; // Guarda el índice numérico
            
            $category->save();

            // Recargar el modelo para asegurarnos de tener los datos actualizados
            $category = $category->fresh();

            \Log::info('Categoría creada exitosamente', ['category_id' => $category->id]);

            // Devolver la categoría creada
            return response()->json([
                'success' => true,
                'data' => $category,
                'message' => 'Categoría creada exitosamente'
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error al crear categoría: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Verificar si el usuario está autenticado y es administrador
            if (!auth()->check() || !auth()->user()->is_admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado',
                    'error' => 'No tienes permiso para realizar esta acción',
                    'auth' => auth()->check() ? 'Autenticado' : 'No autenticado',
                    'is_admin' => auth()->check() ? (auth()->user()->is_admin ? 'Sí' : 'No') : 'N/A'
                ], 403);
            }
            
            \Log::info('Solicitud de actualización de categoría recibida', [
                'user_id' => auth()->id(),
                'category_id' => $id,
                'request_data' => $request->all()
            ]);

            // Buscar la categoría
            $category = Category::find($id);
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Categoría no encontrada',
                    'category_id' => $id
                ], 404);
            }

            // Validar los datos de entrada
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:30', Rule::unique('categories', 'name')->ignore($category->id)],
                'emoji' => ['required', 'integer', 'min:0', 'max:28'] // 0-28 son los índices válidos
            ]);

            // Actualizar la categoría
            $category->name = $validated['name'];
            $category->slug = Str::slug($validated['name']);
            $category->emoji = (int)$validated['emoji']; // Actualiza el índice numérico
            
            $category->save();

            // Recargar el modelo para asegurarnos de tener los datos actualizados
            $category = $category->fresh();

            return response()->json([
                'success' => true,
                'data' => $category,
                'message' => 'Categoría actualizada exitosamente'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error al actualizar categoría: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        try {
            // Verificar si el usuario está autenticado y es administrador
            if (!auth()->check() || !auth()->user()->is_admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado',
                    'error' => 'No tienes permiso para realizar esta acción'
                ], 403);
            }

            \Log::info('Solicitud de eliminación de categoría recibida', [
                'user_id' => auth()->id(),
                'category_id' => $category->id
            ]);

            // Verificar si hay productos asociados a esta categoría
            $productCount = Product::where('category', $category->id)->count();
            
            if ($productCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar la categoría porque tiene productos asociados.',
                    'product_count' => $productCount
                ], 422);
            }

            $category->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada correctamente',
                'deleted' => true
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al eliminar categoría: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
