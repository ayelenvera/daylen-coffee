<?php
// public/test.php
echo "PHP está funcionando<br>";

// Verificar Laravel
try {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    echo "Laravel carga correctamente<br>";
} catch (Exception $e) {
    echo "Error Laravel: " . $e->getMessage() . "<br>";
}

// Verificar extensiones
$extensions = ['mbstring', 'pdo_mysql', 'pdo_pgsql', 'gd', 'zip'];
foreach ($extensions as $ext) {
    echo extension_loaded($ext) ? "✅ $ext<br>" : "❌ $ext<br>";
}
?>
