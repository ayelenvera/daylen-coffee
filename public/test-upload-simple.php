<?php
// Configuración para manejar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mostrar información de límites
echo "<h2>Límites actuales:</h2>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "memory_limit: " . ini_get('memory_limit') . "<br>";

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>Resultado de la subida:</h3>";
    
    if (isset($_FILES['test_file'])) {
        echo "<pre>";
        echo "Información del archivo:\n";
        print_r($_FILES['test_file']);
        echo "\n\n";
        
        // Mostrar información del error
        $errorMessages = [
            UPLOAD_ERR_OK => 'No hay error, archivo subido con éxito',
            UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño máximo permitido por la directiva upload_max_filesize en php.ini',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño máximo permitido por el formulario',
            UPLOAD_ERR_PARTIAL => 'El archivo solo se subió parcialmente',
            UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo',
            UPLOAD_ERR_NO_TMP_DIR => 'No se encontró el directorio temporal',
            UPLOAD_ERR_CANT_WRITE => 'Error al escribir el archivo en el disco',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la carga del archivo',
        ];
        
        $errorCode = $_FILES['test_file']['error'];
        echo "Código de error: $errorCode - " . ($errorMessages[$errorCode] ?? 'Error desconocido') . "\n\n";
        
        // Intentar mover el archivo
        if ($errorCode === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/images/test/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $targetFile = $uploadDir . basename($_FILES['test_file']['name']);
            
            if (move_uploaded_file($_FILES['test_file']['tmp_name'], $targetFile)) {
                echo "Archivo movido exitosamente a: $targetFile\n";
                echo "Tamaño real: " . filesize($targetFile) . " bytes\n";
            } else {
                echo "Error al mover el archivo. Detalles del error:\n";
                print_r(error_get_last());
            }
        }
        
        echo "</pre>";
    } else {
        echo "No se recibió ningún archivo.";
    }
}
?>

<h2>Prueba de subida de archivos</h2>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="test_file" required>
    <button type="submit">Probar subida</button>
</form>
