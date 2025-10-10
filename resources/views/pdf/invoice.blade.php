<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura - {{ $order->id }}</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            color: #333; 
            font-size: 12px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 25px; 
            border-bottom: 2px solid #d97706; 
            padding-bottom: 15px; 
        }
        .shop-info, .customer-info { 
            margin-bottom: 20px; 
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
            font-size: 11px;
        }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        .table th { 
            background-color: #f8f9fa; 
            font-weight: bold;
        }
        .total-section { 
            text-align: right; 
            margin-top: 20px; 
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        .footer { 
            margin-top: 30px; 
            text-align: center; 
            font-size: 10px; 
            color: #666; 
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
        .section-title {
            font-weight: bold;
            color: #374151;
            margin-bottom: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; color: #d97706; font-size: 24px;">☕ {{ $shop['name'] ?? 'Daylen Cafetería' }}</h1>
        <h2 style="margin: 5px 0; font-size: 18px;">FACTURA N° {{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>
        <p style="margin: 0; color: #6b7280;">Factura Electrónica</p>
    </div>

    <div class="shop-info">
        <div class="section-title">Información de la Empresa</div>
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Empresa:</strong> {{ $shop['name'] ?? 'Daylen Cafetería' }}</td>
                <td style="border: none; padding: 2px 0;"><strong>RUC:</strong> {{ $shop['ruc'] ?? '12345678-9' }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Email:</strong> {{ $shop['email'] ?? 'daylencoffee@gmail.com' }}</td>
                <td style="border: none; padding: 2px 0;"><strong>Teléfono:</strong> {{ $shop['phone'] ?? '+595 986 195914' }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;" colspan="2"><strong>Dirección:</strong> {{ $shop['address'] ?? 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este' }}</td>
            </tr>
        </table>
    </div>

    <div class="customer-info">
        <div class="section-title">Información del Cliente</div>
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Cliente:</strong> {{ $order->user->name }}</td>
                <td style="border: none; padding: 2px 0;"><strong>Email:</strong> {{ $order->user->email }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Nombre de Entrega:</strong> {{ $order->customer_name }}</td>
                <td style="border: none; padding: 2px 0;"><strong>Teléfono:</strong> {{ $order->phone }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;" colspan="2"><strong>Dirección:</strong> {{ $order->address }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Detalles del Pedido</div>
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th style="width: 60px; text-align: center;">Cant.</th>
                <th style="width: 80px; text-align: right;">Precio Unit.</th>
                <th style="width: 80px; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>
                    <strong>{{ $item->product->name ?? 'Producto eliminado' }}</strong>
                    @if($item->size || $item->sugar)
                        <br><small style="color: #6b7280;">{{ $item->size }} {{ $item->size && $item->sugar ? '•' : '' }} {{ $item->sugar }}</small>
                    @endif
                    @if(!empty($item->toppings) || !empty($item->addons))
                        <br><small style="color: #6b7280;">
                            @if(!empty($item->toppings)) <strong>Coberturas:</strong> {{ implode(', ', $item->toppings) }} @endif
                            @if(!empty($item->toppings) && !empty($item->addons)) • @endif
                            @if(!empty($item->addons)) <strong>Agregados:</strong> {{ implode(', ', $item->addons) }} @endif
                        </small>
                    @endif
                </td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">₲ {{ $formatNumber($item->price) }}</td>
                <td style="text-align: right;">₲ {{ $formatNumber($item->subtotal) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div style="font-size: 16px; font-weight: bold; margin-bottom: 10px;">
            Total: ₲ {{ $formatNumber($order->total) }}
        </div>
        
        <!-- ✅ AGREGAR DESGLOSE DE ENVÍO -->
        @if($order->shipping_cost > 0)
        <div style="margin-bottom: 5px;">
            <strong>Envío:</strong> ₲ {{ $formatNumber($order->shipping_cost) }}
        </div>
        @endif
        
        <div style="margin-bottom: 5px;">
            <strong>Método de Pago:</strong> {{ $order->payment_method === 'efectivo' ? 'Efectivo' : 'Tarjeta' }}
        </div>
        <div style="margin-bottom: 5px;">
            <strong>Estado:</strong> 
            @if($order->status === 'pending')
                Pendiente
            @elseif($order->status === 'paid')
                Pagado
            @else
                Cancelado
            @endif
        </div>
        <div style="margin-bottom: 5px;">
            <strong>Fecha:</strong> {{ $formatDate($order->created_at) }}
        </div>
        @if($order->payment_id)
        <div style="margin-bottom: 5px;">
            <strong>ID de Pago:</strong> {{ $order->payment_id }}
        </div>
        @endif
    </div>

    <div class="footer">
        <p><strong>¡Gracias por su compra en {{ $shop['name'] ?? 'Daylen Cafetería' }}!</strong></p>
        <p>Para consultas: {{ $shop['phone'] ?? '+595 986 195914' }} | {{ $shop['email'] ?? 'daylencoffee@gmail.com' }}</p>
        <p>Factura generada automáticamente el {{ $formatDate(now()) }}</p>
    </div>
</body>
</html>