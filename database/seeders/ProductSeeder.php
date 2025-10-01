<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Crear directorios de imágenes
        $imageDir = public_path('images/products');
        $placeholderDir = public_path('images');
        
        if (!File::exists($imageDir)) {
            File::makeDirectory($imageDir, 0755, true);
        }
        
        if (!File::exists($placeholderDir)) {
            File::makeDirectory($placeholderDir, 0755, true);
        }

        // Crear placeholder si no existe
        $placeholderPath = public_path('images/placeholder.jpg');
        if (!File::exists($placeholderPath)) {
            // Crear un placeholder SVG simple convertido a base64
            $placeholderSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300"><rect width="400" height="300" fill="#f3f4f6"/><text x="200" y="150" text-anchor="middle" font-family="Arial" font-size="20" fill="#9ca3af">Imagen no disponible</text></svg>';
            file_put_contents($placeholderPath, $placeholderSvg);
        }

        $currentTime = Carbon::now();

        $products = [
            // Bebidas Calientes - Café
            [
                'name' => 'Espresso',
                'description' => 'Café concentrado y aromático, la esencia del café puro.',
                'price' => 8000,
                'stock' => 45,
                'image' => 'espresso.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Espresso Doble',
                'description' => 'Doble dosis de café espresso para los amantes del café fuerte.',
                'price' => 12000,
                'stock' => 35,
                'image' => 'espresso-doble.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Americano',
                'description' => 'Café negro suave y aromático, perfecto para empezar el día.',
                'price' => 9000,
                'stock' => 50,
                'image' => 'americano.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Capuchino',
                'description' => 'Café espresso con leche vaporizada y espuma de leche, espolvoreado con canela.',
                'price' => 15000,
                'stock' => 40,
                'image' => 'cappuccino.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Latte Coquette',
                'description' => 'Café espresso con leche vaporizada y una suave capa de espuma.',
                'price' => 16000,
                'stock' => 38,
                'image' => 'latte.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Mocha',
                'description' => 'Deliciosa combinación de café espresso, chocolate y leche vaporizada.',
                'price' => 18000,
                'stock' => 32,
                'image' => 'mocha.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Macchiato',
                'description' => 'Espresso con una pequeña cantidad de leche espumada.',
                'price' => 13000,
                'stock' => 28,
                'image' => 'macchiato.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Café con Leche',
                'description' => 'La combinación perfecta de café y leche caliente en proporciones equilibradas.',
                'price' => 10000,
                'stock' => 55,
                'image' => 'cafe-con-leche.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Cortado',
                'description' => 'Espresso cortado con una pequeña cantidad de leche caliente.',
                'price' => 11000,
                'stock' => 30,
                'image' => 'cortado.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],

            // Bebidas Frías
            [
                'name' => 'Iced Coffee',
                'description' => 'Café frío servido con hielo y leche, refrescante y delicioso.',
                'price' => 15000,
                'stock' => 25,
                'image' => 'iced-coffee.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Frappé',
                'description' => 'Bebida helada de café batido con hielo y crema batida.',
                'price' => 20000,
                'stock' => 22,
                'image' => 'frappe.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Cold Brew',
                'description' => 'Café infusionado en frío durante 12 horas, suave y menos ácido.',
                'price' => 17000,
                'stock' => 20,
                'image' => 'cold-brew.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],

            // Tés e Infusiones
            [
                'name' => 'Té Negro',
                'description' => 'Té negro aromático de alta calidad, perfecto para cualquier momento.',
                'price' => 7000,
                'stock' => 40,
                'image' => 'te-negro.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Té Verde',
                'description' => 'Té verde suave con propiedades antioxidantes.',
                'price' => 7500,
                'stock' => 35,
                'image' => 'te-verde.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Té de Manzanilla',
                'description' => 'Infusión relajante de manzanilla, ideal para calmar y relajar.',
                'price' => 6500,
                'stock' => 30,
                'image' => 'manzanilla.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Té de Menta',
                'description' => 'Té refrescante de menta, perfecto para la digestión.',
                'price' => 6800,
                'stock' => 28,
                'image' => 'te-menta.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],

            // Chocolate y Bebidas Especiales
            [
                'name' => 'Chocolate Caliente',
                'description' => 'Delicioso chocolate caliente hecho con auténtico cacao y leche cremosa.',
                'price' => 12000,
                'stock' => 35,
                'image' => 'chocolate-caliente.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Chocolate Blanco',
                'description' => 'Chocolate blanco cremoso con un toque de vainilla.',
                'price' => 13000,
                'stock' => 25,
                'image' => 'chocolate-blanco.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Submarino',
                'description' => 'Leche caliente con barra de chocolate que se derrite lentamente.',
                'price' => 14000,
                'stock' => 20,
                'image' => 'submarino.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],

            // Pastelería - Facturas y Croissants
            [
                'name' => 'Croissant',
                'description' => 'Crujiente y esponjoso croissant de manteca, perfecto para acompañar.',
                'price' => 6000,
                'stock' => 40,
                'image' => 'croissant.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Croissant de Jamón y Queso',
                'description' => 'Croissant relleno con jamón y queso derretido.',
                'price' => 10000,
                'stock' => 30,
                'image' => 'croissant-jamon-queso.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Medialuna de Grasa',
                'description' => 'Clásica medialuna argentina hecha con grasa, liviana y sabrosa.',
                'price' => 5000,
                'stock' => 45,
                'image' => 'medialuna-grasa.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Medialuna de Manteca',
                'description' => 'Medialuna premium elaborada con manteca, extra hojaldrada.',
                'price' => 7000,
                'stock' => 35,
                'image' => 'medialuna-manteca.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],

            // Tortas y Postres
            [
                'name' => 'Porción de Torta Chocolate',
                'description' => 'Torta de chocolate intenso con crema chantilly.',
                'price' => 12000,
                'stock' => 25,
                'image' => 'torta-chocolate.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Porción de Torta Red Velvet',
                'description' => 'Torta suave de terciopelo rojo con frosting de queso crema.',
                'price' => 14000,
                'stock' => 20,
                'image' => 'torta-red-velvet.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Cheesecake',
                'description' => 'Cheesecake cremoso con base de galleta y frutos rojos.',
                'price' => 15000,
                'stock' => 18,
                'image' => 'cheesecake.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Brownie',
                'description' => 'Brownie de chocolate intenso con nueces y salsa de chocolate.',
                'price' => 9000,
                'stock' => 30,
                'image' => 'brownie.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Lemon Pie',
                'description' => 'Tarta de limón con merengue italiano, balance perfecto entre dulce y ácido.',
                'price' => 13000,
                'stock' => 22,
                'image' => 'lemon-pie.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],

            // Sandwiches y Salados
            [
                'name' => 'Sandwich de Jamón y Queso',
                'description' => 'Clásico sandwich de jamón y queso en pan ciabatta tostado.',
                'price' => 15000,
                'stock' => 35,
                'image' => 'sandwich-jamon-queso.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Sandwich Club',
                'description' => 'Sandwich triple con pollo, panceta, lechuga, tomate y mayonesa.',
                'price' => 22000,
                'stock' => 25,
                'image' => 'sandwich-club.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Tostado Mixto',
                'description' => 'Tostado caliente de jamón y queso derretido.',
                'price' => 12000,
                'stock' => 40,
                'image' => 'tostado-mixto.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Empanada de Carne',
                'description' => 'Empanada horneada rellena de carne jugosa con aceitunas y huevo.',
                'price' => 8000,
                'stock' => 50,
                'image' => 'empanada-carne.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Empanada de Jamón y Queso',
                'description' => 'Empanada horneada rellena de jamón y queso derretido.',
                'price' => 7500,
                'stock' => 45,
                'image' => 'empanada-jamon-queso.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Empanada de Pollo',
                'description' => 'Empanada horneada rellena de pollo cremoso con verduras.',
                'price' => 8000,
                'stock' => 40,
                'image' => 'empanada-pollo.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],

            // Panadería
            [
                'name' => 'Factura',
                'description' => 'Factura clásica de manteca espolvoreada con azúcar.',
                'price' => 4500,
                'stock' => 60,
                'image' => 'factura.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Cañoncito',
                'description' => 'Factura rellena de dulce de leche o crema pastelera.',
                'price' => 6000,
                'stock' => 35,
                'image' => 'canoncito.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Vigilante',
                'description' => 'Dos galletas de manteca unidas con dulce de membrillo.',
                'price' => 5000,
                'stock' => 30,
                'image' => 'vigilante.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Alfajor',
                'description' => 'Alfajor de maicena relleno de dulce de leche y coco.',
                'price' => 7000,
                'stock' => 40,
                'image' => 'alfajor.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Galletas de Avena',
                'description' => 'Galletas saludables de avena con pasas de uva y miel.',
                'price' => 5500,
                'stock' => 45,
                'image' => 'galletas-avena.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],

            // Bebidas Extras
            [
                'name' => 'Agua Mineral 500ml',
                'description' => 'Agua mineral natural sin gas.',
                'price' => 5000,
                'stock' => 100,
                'image' => 'agua-mineral.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Jugo de Naranja Natural',
                'description' => 'Jugo de naranja exprimido al momento, rico en vitamina C.',
                'price' => 10000,
                'stock' => 30,
                'image' => 'jugo-naranja.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Limonada',
                'description' => 'Limonada natural refrescante con hierbabuena.',
                'price' => 9000,
                'stock' => 35,
                'image' => 'limonada.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ],
            [
                'name' => 'Smoothie de Frutilla',
                'description' => 'Smoothie cremoso de frutilla con yogurt natural.',
                'price' => 16000,
                'stock' => 25,
                'image' => 'smoothie-frutilla.jpg',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('45 productos creados exitosamente para la cafetería y confitería.');
        $this->command->info('Las imágenes deben ser colocadas en: public/images/products/');
        $this->command->info('Placeholder disponible en: public/images/placeholder.jpg');
        $this->command->info('Precios en Guaraníes (₲) con valores realistas del mercado paraguayo.');
    }
}