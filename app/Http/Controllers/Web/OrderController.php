<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Procesar checkout con validación de stock y cálculo correcto de precios con personalizaciones
     */
    public function checkout(Request $request)
    {
        if (auth()->user()->is_admin) {
            return redirect()->route('home');
        }
    
        $cart = session('cart', ['items' => [], 'total' => 0, 'item_count' => 0]);
        if (empty($cart['items'])) {
            return back()->with('error', 'Tu carrito está vacío');
        }
    
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'payment_method' => 'required|in:efectivo,tarjeta',
            'notes' => 'nullable|string|max:500',
        ]);
    
        // ✅ OBTENER COSTO DE ENVÍO DEL REQUEST
        $shippingCost = $request->input('shipping_cost', 0);
    
        // Verificar stock antes de procesar el pedido
        $stockErrors = [];
        foreach ($cart['items'] as $item) {
            $product = Product::find($item['id']);
            if (!$product) {
                $stockErrors[] = "El producto '{$item['name']}' ya no está disponible";
                continue;
            }
            
            if ($product->stock < $item['quantity']) {
                $stockErrors[] = "No hay suficiente stock de '{$item['name']}'. Disponible: {$product->stock}, Solicitado: {$item['quantity']}";
            }
        }
    
        if (!empty($stockErrors)) {
            return back()->withErrors(['stock' => $stockErrors])->withInput();
        }
    
        // Procesar el pedido en una transacción
        try {
            DB::beginTransaction();
    
            // Obtener personalizaciones del request
            $personalizations = $request->input('personalizations', '{}');
            if (is_string($personalizations)) {
                $personalizations = json_decode($personalizations, true) ?? [];
            } else {
                $personalizations = $personalizations ?? [];
            }
    
            // Cargar reglas de personalización para todos los productos
            $customizationRules = [];
            foreach ($cart['items'] as $item) {
                $customizationRules[$item['id']] = CartService::getProductCustomizationRules($item['id']);
            }
    
            // Calcular total real del pedido con personalizaciones
            $orderTotal = 0;
            $orderItemsData = [];
    
            foreach ($cart['items'] as $item) {
                $product = Product::find($item['id']);
                
                // Obtener personalizaciones para este producto
                $productPersonalizations = $personalizations[$item['id']] ?? [];
                
                // CALCULAR PRECIO FINAL CON PERSONALIZACIONES
                $basePrice = (float)$item['price'];
                $sizePrice = 0;
                $toppingsPrice = 0;
                $addonsPrice = 0;
                
                // Calcular costo del tamaño
                if (isset($productPersonalizations['size'])) {
                    $sizeRules = $customizationRules[$item['id']]['size_options'] ?? [];
                    $selectedSize = $productPersonalizations['size'];
                    $sizeOption = collect($sizeRules)->firstWhere('name', $selectedSize);
                    $sizePrice = $sizeOption['price'] ?? 0;
                }
                
                // Calcular costo de toppings
                if (isset($productPersonalizations['toppings']) && is_array($productPersonalizations['toppings'])) {
                    $toppingRules = $customizationRules[$item['id']]['topping_options'] ?? [];
                    foreach ($productPersonalizations['toppings'] as $toppingName) {
                        $toppingOption = collect($toppingRules)->firstWhere('name', $toppingName);
                        $toppingsPrice += $toppingOption['price'] ?? 0;
                    }
                }
                
                // Calcular costo de addons
                if (isset($productPersonalizations['addons']) && is_array($productPersonalizations['addons'])) {
                    $addonRules = $customizationRules[$item['id']]['addon_options'] ?? [];
                    foreach ($productPersonalizations['addons'] as $addonName) {
                        $addonOption = collect($addonRules)->firstWhere('name', $addonName);
                        $addonsPrice += $addonOption['price'] ?? 0;
                    }
                }
                
                // PRECIO FINAL CON PERSONALIZACIONES
                $finalUnitPrice = $basePrice + $sizePrice + $toppingsPrice + $addonsPrice;
                $finalSubtotal = $finalUnitPrice * (int)$item['quantity'];
                
                $orderTotal += $finalSubtotal;
    
                // Guardar datos para crear los order items
                $orderItemsData[] = [
                    'product' => $product,
                    'product_id' => $item['id'],
                    'quantity' => (int)$item['quantity'],
                    'final_unit_price' => $finalUnitPrice,
                    'final_subtotal' => $finalSubtotal,
                    'personalizations' => $productPersonalizations
                ];
            }
    
            // ✅ AGREGAR COSTO DE ENVÍO AL TOTAL FINAL
            $orderTotal += $shippingCost;
    
            // Crear el pedido con el TOTAL CORRECTO (incluyendo envío)
            $order = new Order();
            $order->user_id = auth()->id();
            $order->total = $orderTotal;  // ← TOTAL CON PERSONALIZACIONES Y ENVÍO
            $order->status = 'pending';
            $order->customer_name = $data['customer_name'];
            $order->phone = $data['phone'];
            $order->address = $data['address'];
            $order->payment_method = $data['payment_method'];
            $order->notes = $data['notes'] ?? null;
            $order->save();
    
            // Crear items del pedido con precios correctos
            foreach ($orderItemsData as $itemData) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $itemData['product_id'];
                $orderItem->quantity = $itemData['quantity'];
                $orderItem->price = $itemData['final_unit_price'];    // ← PRECIO UNITARIO CON PERSONALIZACIONES
                $orderItem->subtotal = $itemData['final_subtotal'];   // ← SUBTOTAL CORRECTO
                $orderItem->size = $itemData['personalizations']['size'] ?? null;
                $orderItem->sugar = $itemData['personalizations']['sugar'] ?? null;
                $orderItem->toppings = $itemData['personalizations']['toppings'] ?? [];
                $orderItem->addons = $itemData['personalizations']['addons'] ?? [];
                
                $orderItem->save();
    
                // Descontar stock
                $itemData['product']->stock = max(0, $itemData['product']->stock - $itemData['quantity']);
                $itemData['product']->save();
            }
    
            // Simular pago si es con tarjeta
            if ($data['payment_method'] === 'tarjeta') {
                // Simular procesamiento de pago exitoso
                $order->payment_status = 'paid';
                $order->status = 'paid'; // Cambiar estado del pedido a pagado
                $order->card_issuer = $request->input('card_issuer');
                $order->card_last_four = $request->input('card_last_four');
                $order->card_expiration = $request->input('card_expiration');
                $order->payment_response = json_encode([
                    'status' => 'success',
                    'message' => 'Pago procesado exitosamente',
                    'transaction_id' => 'TXN_' . time() . '_' . $order->id,
                    'simulated' => true
                ]);
            } else {
                // Pago en efectivo
                $order->payment_status = 'pending';
                $order->status = 'pending';
            }
    
            $order->save();
    
            DB::commit();
    
            // Limpiar carrito
            session(['cart' => ['items' => [], 'total' => 0, 'item_count' => 0]]);
    
            return redirect()->route('orders.invoice', ['order' => $order->id])
                ->with('success', '¡Pedido creado y pagado con éxito!');
    
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error en checkout: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Error al procesar el pedido. Por favor intenta nuevamente.');
        }
    }

    /**
     * Mostrar factura del pedido
     */
    public function invoice(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load(['user', 'orderItems.product']);
        
        return inertia('Invoice', [
            'order' => [
                'id' => $order->id,
                'total' => $order->total,
                'status' => $order->status,
                'payment_method' => $order->payment_method,
                'customer_name' => $order->customer_name,
                'phone' => $order->phone,
                'address' => $order->address,
                'notes' => $order->notes,
                'payment_id' => $order->payment_id,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
                'user' => [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ],
                'order_items' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product ? $item->product->name : 'Producto eliminado',
                        'product' => $item->product ? [
                            'id' => $item->product->id,
                            'name' => $item->product->name,
                            'price' => $item->product->price
                        ] : null,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                        'size' => $item->size,
                        'sugar' => $item->sugar,
                        'toppings' => $item->toppings ?? [],
                        'addons' => $item->addons ?? [],
                    ];
                }),
            ],
        ]);
    }

    /**
     * Listar pedidos del usuario  
     */
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with('orderItems.product')
            ->latest()
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'total' => $order->total,
                    'status' => $order->status,
                    'payment_method' => $order->payment_method,
                    'customer_name' => $order->customer_name,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'notes' => $order->notes,
                    'payment_id' => $order->payment_id,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                    'user' => [
                        'id' => $order->user->id,
                        'name' => $order->user->name,
                        'email' => $order->user->email,
                    ],
                    'order_items' => $order->orderItems->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'product_name' => $item->product ? $item->product->name : 'Producto eliminado',
                            'product' => $item->product ? [
                                'id' => $item->product->id,
                                'name' => $item->product->name,
                                'price' => $item->product->price
                            ] : null,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'subtotal' => $item->subtotal,
                            'size' => $item->size,
                            'sugar' => $item->sugar,
                            'toppings' => $item->toppings ?? [],
                            'addons' => $item->addons ?? [],
                        ];
                    }),
                ];
            });

        return inertia('Orders', [
            'orders' => $orders
        ]);
    }

    /**
     * Eliminar pedido individual del usuario
     */
    public function destroy(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403, 'No tienes permisos para eliminar este pedido');

        try {
            $order->delete();
            return redirect()->route('orders.index')->with('success', 'Pedido eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('orders.index')->with('error', 'Error al eliminar el pedido');
        }
    }

    /**
     * Eliminar múltiples pedidos del usuario
     */
    public function bulkDelete(Request $request)
    {
        $orderIds = $request->input('order_ids', []);

        if (is_string($orderIds)) {
            $orderIds = json_decode($orderIds, true) ?? [];
        }

        if (!is_array($orderIds)) {
            $orderIds = [$orderIds];
        }

        $orderIds = array_filter($orderIds, function($id) {
            return is_numeric($id) && $id > 0;
        });
        $orderIds = array_map('intval', $orderIds);
        $orderIds = array_unique($orderIds);

        if (empty($orderIds)) {
            return redirect()->route('orders.index')->with('error', 'No se seleccionaron pedidos válidos');
        }

        try {
            $deletedCount = Order::whereIn('id', $orderIds)
                ->where('user_id', auth()->id())
                ->delete();

            $message = $deletedCount === 1
                ? '1 pedido eliminado exitosamente'
                : "{$deletedCount} pedidos eliminados exitosamente";

            return redirect()->route('orders.index')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('orders.index')->with('error', 'Error al eliminar los pedidos');
        }
    }

    /**
     * Eliminar todos los pedidos del usuario
     */
    public function deleteAll()
    {
        try {
            $count = auth()->user()->orders()->count();

            if ($count > 0) {
                auth()->user()->orders()->delete();
                return redirect()->route('orders.index')->with('success', "Todos tus pedidos ({$count}) eliminados exitosamente");
            } else {
                return redirect()->route('orders.index')->with('info', 'No tienes pedidos para eliminar');
            }

        } catch (\Exception $e) {
            return redirect()->route('orders.index')->with('error', 'Error al eliminar todos los pedidos');
        }
    }

    /**
    * Restaurar pedidos eliminados del usuario
    */
    public function restore(Request $request)
    {
        $request->validate([
            'undo_data' => 'required|array',
            'undo_data.type' => 'required|string|in:all,selected',
            'undo_data.orders' => 'required|array',
            'undo_data.orders.*' => 'array'
        ]);

        $undoData = $request->undo_data;
        $restoredCount = 0;

        try {
            DB::beginTransaction();

            foreach ($undoData['orders'] as $orderData) {
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'total' => $orderData['total'],
                    'status' => $orderData['status'] ?? 'pending',
                    'payment_id' => $orderData['payment_id'] ?? null,
                    'customer_name' => $orderData['customer_name'] ?? null,
                    'phone' => $orderData['phone'] ?? null,
                    'address' => $orderData['address'] ?? null,
                    'payment_method' => $orderData['payment_method'] ?? null,
                    'notes' => $orderData['notes'] ?? null,
                ]);

                if (isset($orderData['order_items']) && is_array($orderData['order_items'])) {
                    foreach ($orderData['order_items'] as $itemData) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $itemData['product_id'] ?? null,
                            'quantity' => $itemData['quantity'] ?? 1,
                            'price' => $itemData['price'] ?? 0,
                            'subtotal' => $itemData['subtotal'] ?? 0,
                            'size' => $itemData['size'] ?? null,
                            'sugar' => $itemData['sugar'] ?? null,
                            'toppings' => $itemData['toppings'] ?? [],
                            'addons' => $itemData['addons'] ?? [],
                        ]);
                    }
                }

                $restoredCount++;
            }

            DB::commit();

            $message = $restoredCount === 1
                ? '1 pedido restaurado exitosamente'
                : "{$restoredCount} pedidos restaurados exitosamente";

            return redirect()->route('orders.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('orders.index')->with('error', 'Error al restaurar los pedidos: ' . $e->getMessage());
        }
    }

/**
 * Descargar factura PDF del pedido (para usuarios)
 */
/**
 * Descargar factura PDF del pedido (para usuarios)
 */
public function downloadInvoice(Order $order)
{
    abort_unless($order->user_id === auth()->id(), 403);
    $order->load(['user', 'orderItems.product']);

    try {
        // ✅ OBTENER SHOP SETTINGS DIRECTAMENTE DE LA BASE DE DATOS
        $shopSettings = \App\Models\ShopSetting::where('is_active', true)->first();
        
        // ✅ USAR DATOS POR DEFECTO SI NO HAY CONFIGURACIÓN
        $shopData = [
            'name' => $shopSettings->shop_name ?? 'Daylen Cafetería',
            'address' => $shopSettings->address ?? 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este',
            'phone' => $shopSettings->phone ?? '+595 986 195914',
            'email' => $shopSettings->email ?? 'daylencoffee@gmail.com',
            'ruc' => $shopSettings->ruc ?? '12345678-9',
            'about_us' => $shopSettings->about_us ?? 'Somos una cafetería dedicada a ofrecer los mejores productos con ingredientes de calidad.'
        ];

        // Cargar la vista de factura
        $html = view('pdf.invoice', [
            'order' => $order,
            'shop' => $shopData, // ✅ AHORA SÍ TIENE LOS DATOS CORRECTOS
            'formatNumber' => function($number) {
                return number_format($number, 0, ',', '.');
            },
            'formatDate' => function($date) {
                return \Carbon\Carbon::parse($date)->format('d/m/Y H:i');
            },
        ])->render();

        // Configurar DomPDF
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');
        $options->set('chroot', public_path());
        
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream("factura-{$order->id}.pdf", [
            'Attachment' => true
        ]);

    } catch (\Exception $e) {
        \Log::error('Error generating user invoice PDF: ' . $e->getMessage());
        return back()->with('error', 'Error al generar la factura: ' . $e->getMessage());
    }
}
}