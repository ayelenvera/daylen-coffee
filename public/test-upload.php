<?php
// Mostrar información de configuración de PHP
phpinfo(INFO_VARIABLES);

// Probar la subida de archivos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    $uploadDir = __DIR__ . '/images/test/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $fileName = basename($_FILES['test_file']['name']);
    $targetFile = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['test_file']['tmp_name'], $targetFile)) {
        echo "<p style='color: green;'>Archivo subido exitosamente: " . htmlspecialchars($fileName) . "</p>";
        echo "<p>Tamaño del archivo: " . filesize($targetFile) . " bytes</p>";
    } else {
        echo "<p style='color: red;'>Error al subir el archivo. Código de error: " . $_FILES['test_file']['error'] . "</p>";
        
        // Mostrar información detallada del error
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño máximo permitido por la directiva upload_max_filesize en php.ini',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño máximo permitido por el formulario',
            UPLOAD_ERR_PARTIAL => 'El archivo solo se subió parcialmente',
            UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo',
            UPLOAD_ERR_NO_TMP_DIR => 'No se encontró el directorio temporal',
            UPLOAD_ERR_CANT_WRITE => 'Error al escribir el archivo en el disco',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la carga del archivo',
        ];
        
        $errorCode = $_FILES['test_file']['error'];
        echo "<p>Detalles del error: " . ($errorMessages[$errorCode] ?? 'Error desconocido') . "</p>";
    }
}
?>

<h2>Prueba de subida de archivos</h2>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="test_file" required>
    <button type="submit">Probar subida</button>
</form>

<h3>Configuración actual de PHP:</h3>
<ul>
    <li>upload_max_filesize: <?php echo ini_get('upload_max_filesize'); ?></li>
    <li>post_max_size: <?php echo ini_get('post_max_size'); ?></li>
    <li>memory_limit: <?php echo ini_get('memory_limit'); ?></li>
    <li>max_execution_time: <?php echo ini_get('max_execution_time'); ?> segundos</li>
    <li>max_input_time: <?php echo ini_get('max_input_time'); ?> segundos</li>
</ul>
