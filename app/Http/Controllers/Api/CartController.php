<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador API para el carrito de compras
 * 
 * Maneja las operaciones del carrito usando sesiones de Laravel
 */
class CartController extends Controller
{
    /**
     * Agregar un producto al carrito
     * 
     * @param Request $request Datos del producto (id, quantity)
     * @return JsonResponse Confirmación de agregado
     */
    public function add(Request $request): JsonResponse
    {
        try {
            // Verificar autenticación
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe iniciar sesión para agregar productos al carrito',
                    'redirect' => url('/login') // URL absoluta para el frontend
                ], 401);
            }

            $validated = $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1|max:10'
            ]);

            $product = Product::findOrFail($validated['product_id']);

            // Verificar stock disponible
            if ($product->stock < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente. Disponible: ' . $product->stock
                ], 400);
            }

            $cart = session()->get('cart', []);
            $productId = $validated['product_id'];

            \Log::info('ANTES de agregar producto:', [
                'session_id' => session()->getId(),
                'cart_actual' => $cart,
                'producto_a_agregar' => $productId
            ]);

            // Si el producto ya está en el carrito, sumar la cantidad
            if (isset($cart[$productId])) {
                $newQuantity = $cart[$productId]['quantity'] + $validated['quantity'];
                
                // Verificar que no exceda el stock total
                if ($newQuantity > $product->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cantidad excede el stock disponible. Stock: ' . $product->stock
                    ], 400);
                }
                
                $cart[$productId]['quantity'] = $newQuantity;
            } else {
                // Agregar nuevo producto al carrito
                $cart[$productId] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $validated['quantity'],
                    'image' => $product->image
                ];
            }

            session()->put('cart', $cart);
            
            \Log::info('DESPUÉS de agregar producto:', [
                'session_id' => session()->getId(),
                'cart_actualizado' => $cart,
                'cart_count' => count($cart)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Producto agregado al carrito correctamente',
                'cart_count' => count($cart)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar producto al carrito: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Quitar un producto del carrito
     * 
     * @param Request $request Datos del producto (product_id)
     * @return JsonResponse Confirmación de eliminación
     */
    public function remove(Request $request): JsonResponse
    {
        try {

            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe iniciar sesión para modificar el carrito',
                    'redirect' => route('login')
                ], 401);
            }

            $validated = $request->validate([
                'product_id' => 'required|integer'
            ]);

            $cart = session()->get('cart', []);
            $productId = $validated['product_id'];

            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                session()->put('cart', $cart);

                return response()->json([
                    'success' => true,
                    'message' => 'Producto quitado del carrito correctamente',
                    'cart_count' => count($cart)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado en el carrito'
                ], 404);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al quitar producto del carrito: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Ver el contenido actual del carrito
     * 
     * @return JsonResponse Lista de productos en el carrito con totales
     */
    public function view(): JsonResponse
    {
        try {
            // Verificar autenticación
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe iniciar sesión para ver el carrito',
                    'redirect' => route('login')
                ], 401);
            }
    
            // ✅ Verificar que no sea administrador
            if (Auth::user()->is_admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Los administradores no pueden acceder al carrito',
                    'redirect' => route('home')
                ], 403);
            }
    
            // ... resto del código del método
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el carrito: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpiar todo el carrito
     * 
     * @return JsonResponse Confirmación de limpieza
     */
    public function clear(): JsonResponse
    {
        try {

            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe iniciar sesión para limpiar el carrito',
                    'redirect' => route('login')
                ], 401);
            }

            session()->forget('cart');

            return response()->json([
                'success' => true,
                'message' => 'Carrito limpiado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al limpiar el carrito: ' . $e->getMessage()
            ], 500);
        }
    }
}
