<?php
// public/test.php - CORREGIDO
echo "PHP está funcionando<br>";

try {
    require __DIR__.'/../vendor/autoload.php';
    
    // Crear la aplicación Laravel
    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    // Ejecutar la aplicación para que bootee completamente
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    
    echo "✅ Laravel inicializado correctamente<br>";
    
    // AHORA SÍ podemos usar Facades y DB
    echo "APP_KEY: " . (config('app.key') ? '✅ Existe' : '❌ FALTANTE') . "<br>";
    
    // Probar base de datos
    try {
        Illuminate\Support\Facades\DB::connection()->getPdo();
        echo "✅ Conexión a DB exitosa<br>";
        
        // Verificar tablas
        $tables = Illuminate\Support\Facades\DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
        echo "Tablas en DB: " . count($tables) . "<br>";
        
    } catch (Exception $e) {
        echo "❌ Error DB: " . $e->getMessage() . "<br>";
    }
    
    // Terminar la aplicación
    $kernel->terminate($request, $response);
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Archivo: " . $e->getFile() . " Linea: " . $e->getLine() . "<br>";
}
?>
