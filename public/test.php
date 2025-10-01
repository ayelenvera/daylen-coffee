<?php
// public/test.php - VERSIÓN EXTENDIDA
echo "PHP está funcionando<br>";

try {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    echo "Laravel carga correctamente<br>";
    
    // Probar kernel HTTP
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "Kernel HTTP funciona<br>";
    
    // Probar configuración básica
    echo "APP_ENV: " . ($_ENV['APP_ENV'] ?? 'No definido') . "<br>";
    echo "APP_DEBUG: " . ($_ENV['APP_DEBUG'] ?? 'No definido') . "<br>";
    
    // Probar base de datos
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        echo "✅ Conexión a DB exitosa<br>";
    } catch (Exception $e) {
        echo "❌ Error DB: " . $e->getMessage() . "<br>";
    }
    
} catch (Exception $e) {
    echo "Error Laravel: " . $e->getMessage() . "<br>";
}
?>
