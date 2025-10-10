<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Models\OrderItem; // ✅ Asegurar que esté importado
use Illuminate\Support\Facades\DB; // ✅ ESTE ES EL QUE FALTA

/**
 * Controlador para gestión de pedidos en el panel de administración
 */
class OrdersController extends Controller
{
    /**
     * Verificar si el usuario es administrador
     */
    protected function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Acceso no autorizado');
        }
    }

    /**
     * Mostrar lista de todos los pedidos
     */
    // En app/Http/Controllers/Admin/OrdersController.php - método index()

    public function index()
    {
        $this->checkAdmin();

        $orders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'customer_name' => $order->customer_name,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'total' => $order->total,
                    'status' => $order->status,
                    'payment_method' => $order->payment_method,
                    'notes' => $order->notes,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                    'user' => $order->user ? [
                        'id' => $order->user->id,
                        'name' => $order->user->name,
                        'email' => $order->user->email,
                    ] : null,
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
                            // ✅ AGREGAR TOPPINGS Y ADDONS
                            'toppings' => $item->toppings ?? [],
                            'addons' => $item->addons ?? [],
                        ];
                    }),
                    // ✅ AGREGAR RESUMEN DE PRODUCTOS PARA LA TABLA
                    'products_summary' => $this->getProductsSummary($order),
                    'total_items' => $order->orderItems->sum('quantity'),
                ];
            });

        return Inertia::render('Admin/Orders', [
            'orders' => $orders,
        ]);
    }

    // ✅ AGREGAR MÉTODO PARA RESUMEN DE PRODUCTOS
    protected function getProductsSummary($order)
    {
        $items = $order->orderItems;
        if ($items->isEmpty()) {
            return 'Sin productos';
        }

        $summary = [];
        foreach ($items as $item) {
            $productName = $item->product ? $item->product->name : 'Producto eliminado';
            $customizations = [];
        
            if ($item->size && $item->size !== 'Mediano') {
                $customizations[] = $item->size;
            }
            if ($item->sugar && $item->sugar !== 'Normal') {
                $customizations[] = $item->sugar;
            }
            if (!empty($item->toppings)) {
                $customizations[] = 'Toppings: ' . implode(', ', $item->toppings);
            }
            if (!empty($item->addons)) {
                $customizations[] = 'Addons: ' . implode(', ', $item->addons);
            }

            $itemText = "{$productName} × {$item->quantity}";
            if (!empty($customizations)) {
                $itemText .= ' (' . implode(', ', $customizations) . ')';
            }
        
            $summary[] = $itemText;
        }

        return implode('; ', $summary);
    }

/**
 * Ver factura como administrador (sin restricciones de usuario)
 */
public function adminInvoice(Request $request, Order $order)
{
    $this->checkAdmin();

    $order->load(['user', 'orderItems.product']);

    // ✅ SIEMPRE generar PDF, no usar Inertia para esta ruta
    try {
        // ✅ USAR DATOS DE SHOP SETTINGS EXISTENTES
        $shopSettings = \App\Models\ShopSetting::getActiveSettings();
        
        $shopData = [
            'name' => $shopSettings->shop_name,
            'address' => $shopSettings->address,
            'phone' => $shopSettings->phone,
            'email' => $shopSettings->email,
            'ruc' => $shopSettings->ruc,
            'about_us' => $shopSettings->about_us
        ];

        // Cargar la vista de factura con todos los datos necesarios
        $html = view('pdf.invoice', [
            'order' => $order,
            'shop' => $shopData, // ✅ USAR DATOS REALES DE LA TIENDA
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
        $options->set('chroot', public_path()); // ✅ AGREGAR PARA RECURSOS LOCALES
        
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // ✅ DESCARGAR EL PDF SIEMPRE
        return $dompdf->stream("factura-{$order->id}.pdf", [
            'Attachment' => true
        ]);

    } catch (\Exception $e) {
        \Log::error('Error generating admin invoice PDF: ' . $e->getMessage());
        return back()->with('error', 'Error al generar la factura: ' . $e->getMessage());
    }
}
    
    /**
     * Actualizar estado de un pedido
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'status' => 'required|string|in:pending,paid,cancelled',
        ]);

        try {
            $order->update(['status' => $validated['status']]);

            Log::info('Order status updated by admin', [
                'order_id' => $order->id,
                'old_status' => $order->getOriginal('status'),
                'new_status' => $validated['status'],
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.orders')->with('success', 'Estado del pedido actualizado correctamente');

        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            return redirect()->route('admin.orders')->with('error', 'Error al actualizar el estado del pedido');
        }
    }

    /**
     * Eliminar pedido individual
     */
    public function destroy(Order $order)
    {
        $this->checkAdmin();

        try {
            Log::info('Order deleted by admin', [
                'order_id' => $order->id,
                'admin_id' => auth()->id()
            ]);

            $order->delete();

            return redirect()->route('admin.orders')->with('success', 'Pedido eliminado correctamente');

        } catch (\Exception $e) {
            Log::error('Error deleting order: ' . $e->getMessage());
            return redirect()->route('admin.orders')->with('error', 'Error al eliminar el pedido');
        }
    }

    /**
     * Eliminar múltiples pedidos
     */
    public function bulkDelete(Request $request)
    {
        $this->checkAdmin();

        $orderIds = $request->input('order_ids', []);

        Log::info('Bulk delete orders request received', ['order_ids' => $orderIds]);

        // Si viene como string (JSON), convertir a array
        if (is_string($orderIds)) {
            $orderIds = json_decode($orderIds, true) ?? [];
        }

        // Asegurar que sea array
        if (!is_array($orderIds)) {
            $orderIds = [$orderIds];
        }

        // Filtrar y validar
        $orderIds = array_filter($orderIds, function($id) {
            return is_numeric($id) && $id > 0;
        });
        $orderIds = array_map('intval', $orderIds);
        $orderIds = array_unique($orderIds);

        Log::info('Processed order IDs', ['processed_ids' => $orderIds]);

        if (empty($orderIds)) {
            return redirect()->route('admin.orders')->with('error', 'No se seleccionaron pedidos válidos');
        }

        try {
            // Verificar que los pedidos existen
            $existingOrders = Order::whereIn('id', $orderIds)->get();
            $existingIds = $existingOrders->pluck('id')->toArray();

            Log::info('Orders found for deletion', ['existing_ids' => $existingIds]);

            if (empty($existingIds)) {
                return redirect()->route('admin.orders')->with('error', 'No se encontraron los pedidos seleccionados');
            }

            // Eliminar solo los que existen
            $deletedCount = Order::whereIn('id', $existingIds)->delete();

            Log::info('Orders deleted successfully', ['count' => $deletedCount]);

            $message = $deletedCount === 1
                ? '1 pedido eliminado exitosamente'
                : "{$deletedCount} pedidos eliminados exitosamente";

            return redirect()->route('admin.orders')->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error in bulk delete orders: ' . $e->getMessage());
            return redirect()->route('admin.orders')->with('error', 'Error al eliminar los pedidos: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar todos los pedidos
     */
    public function deleteAll()
    {
        $this->checkAdmin();

        try {
            $orders = Order::all();
            $count = $orders->count();

            if ($count > 0) {
                Order::query()->delete();
                return redirect()->route('admin.orders')->with('success', "Todos los pedidos ({$count}) eliminados exitosamente");
            } else {
                return redirect()->route('admin.orders')->with('info', 'No hay pedidos para eliminar');
            }

        } catch (\Exception $e) {
            Log::error('Error deleting all orders: ' . $e->getMessage());
            return redirect()->route('admin.orders')->with('error', 'Error al eliminar todos los pedidos');
        }
    }

    /**
     * Restaurar pedidos eliminados
     */
    public function restore(Request $request)
    {
        $this->checkAdmin();

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
                // Crear el pedido
                $order = Order::create([
                    'user_id' => $orderData['user_id'] ?? null,
                    'total' => $orderData['total'],
                    'status' => $orderData['status'] ?? 'pending',
                    'payment_id' => $orderData['payment_id'] ?? null,
                    'customer_name' => $orderData['customer_name'] ?? null,
                    'phone' => $orderData['phone'] ?? null,
                    'address' => $orderData['address'] ?? null,
                    'payment_method' => $orderData['payment_method'] ?? null,
                    'notes' => $orderData['notes'] ?? null,
                ]);

                // ✅ CRÍTICO: Restaurar también los OrderItems con información completa
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
                        ]);
                    }
                }

                $restoredCount++;
            }

            DB::commit();

            $message = $restoredCount === 1
                ? '1 pedido restaurado exitosamente'
                : "{$restoredCount} pedidos restaurados exitosamente";

            return redirect()->route('admin.orders')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error restoring orders: ' . $e->getMessage());
            return redirect()->route('admin.orders')->with('error', 'Error al restaurar los pedidos');
        }
    }
}
