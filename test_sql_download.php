<?php
// Este script prueba la funcionalidad de download_sql.php

// Archivo a probar
$file = 'procesadores_pc.sql';

if (file_exists($file)) {
    // Leer el contenido original del archivo
    $original_content = file_get_contents($file);
    
    echo "<h2>Contenido Original:</h2>";
    echo "<pre>" . htmlspecialchars($original_content) . "</pre>";
    
    // Aplicar las mismas transformaciones que en download_sql.php
    $modified_content = $original_content;
    
    // Eliminar comentarios de estilo SQL (-- línea de comentario)
    $modified_content = preg_replace('/--.*$/m', '', $modified_content);
    
    // Eliminar comentarios de estilo MySQL (/* ... */)
    $modified_content = preg_replace('/\/\*.*?\*\//s', '', $modified_content);
    
    // Eliminar líneas que empiezan con comentarios
    $modified_content = preg_replace('/^\s*--.*$/m', '', $modified_content);
    
    // Eliminar líneas vacías
    $modified_content = preg_replace('/^\s*[\r\n]/m', '', $modified_content);
    
    // Eliminar líneas que contienen "Versión de PHP"
    $modified_content = preg_replace('/^.*Versión de PHP.*$/m', '', $modified_content);
    
    // Eliminar líneas que contienen "AUTO_INCREMENT de la tabla"
    $modified_content = preg_replace('/^.*AUTO_INCREMENT de la tabla.*$/m', '', $modified_content);
    
    echo "<h2>Contenido Sin Comentarios:</h2>";
    echo "<pre>" . htmlspecialchars($modified_content) . "</pre>";
    
} else {
    echo "El archivo $file no existe.";
}
?> 