<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Controlador API para pedidos de la cafetería
 * 
 * Maneja la creación de pedidos y el proceso de checkout
 */
class OrderController extends Controller
{

    // En OrderController.php
    public function stats(): JsonResponse
    {
        try {
            if (!auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos de administrador'
                ], 403);
            }

            $stats = [
                'total_orders' => Order::count(),
                'total_customers' => User::where('is_admin', false)->count(), // ← CORREGIDO
                'total_products' => Product::count(),
                'revenue' => Order::where('status', 'paid')->sum('total')
            ];

            $recentOrders = Order::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'recent_orders' => $recentOrders
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obtener todos los pedidos del usuario autenticado
     * 
     * @return JsonResponse Lista de pedidos del usuario
     */
    public function index(): JsonResponse
    {
        try {
            $orders = Order::where('user_id', auth()->id())
                ->with('orderItems.product')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $orders,
                'message' => 'Pedidos obtenidos correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener pedidos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener un pedido específico por ID
     * 
     * @param string $id ID del pedido
     * @return JsonResponse Información detallada del pedido
     */
    public function show(string $id): JsonResponse
    {
        try {
            $order = Order::where('user_id', auth()->id())
                ->with('orderItems.product')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Pedido obtenido correctamente'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesar el checkout del carrito
     * 
     * @param Request $request Datos del checkout
     * @return JsonResponse Información del pedido creado
     */
    public function checkout(Request $request): JsonResponse
    {
        try {
            // Verificar que el usuario esté autenticado
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe estar autenticado para realizar el checkout'
                ], 401);
            }

            $cart = session()->get('cart', []);

            // Verificar que el carrito no esté vacío
            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El carrito está vacío'
                ], 400);
            }

            // Calcular total y verificar stock
            $total = 0;
            $orderItems = [];

            foreach ($cart as $item) {
                $product = Product::findOrFail($item['id']);
                
                // Verificar stock actualizado
                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente para {$product->name}. Disponible: {$product->stock}"
                    ], 400);
                }

                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal
                ];
            }

            // Crear pedido en una transacción
            DB::beginTransaction();

            try {
                // Crear el pedido
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'total' => $total,
                    'status' => Order::STATUS_PENDING,
                    'payment_id' => null
                ]);

                // Crear los items del pedido
                foreach ($orderItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal']
                    ]);

                    // Reducir stock del producto
                    $product = Product::find($item['product_id']);
                    $product->decrement('stock', $item['quantity']);
                }

                // Simular pago exitoso
                $paymentId = 'FAKE-' . rand(10000, 99999);
                $order->update([
                    'status' => Order::STATUS_PAID,
                    'payment_id' => $paymentId
                ]);

                // Limpiar carrito
                session()->forget('cart');

                DB::commit();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'order_id' => $order->id,
                        'total' => $total,
                        'payment_id' => $paymentId,
                        'status' => $order->status
                    ],
                    'message' => 'Pago simulado con éxito. Pedido creado correctamente'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el checkout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todos los pedidos (solo para administradores)
     * 
     * @return JsonResponse Lista de todos los pedidos
     */
    public function adminIndex(): JsonResponse
    {
        try {
            $orders = Order::with(['user', 'orderItems.product'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $orders,
                'message' => 'Pedidos obtenidos correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener pedidos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar estado de un pedido (solo para administradores)
     * 
     * @param Request $request Datos del pedido
     * @param string $id ID del pedido
     * @return JsonResponse Pedido actualizado
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,paid,cancelled'
            ]);

            $order = Order::findOrFail($id);
            $order->update(['status' => $validated['status']]);
    
        return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Estado del pedido actualizado correctamente'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el pedido: ' . $e->getMessage()
            ], 500);
        }
    }
}
