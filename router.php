<?php
// router.php
// Archivo que actúa como front controller para el servidor embebido

// Si el archivo existe físicamente (como una imagen, JS, etc), lo sirve directamente
if (php_sapi_name() == 'cli-server') {
    $url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $file = __DIR__ . $url;

    if (is_file($file)) {
        return false;
    }
}

// Si no existe el archivo, delega todo a index.php (tu aplicación Slim)
require_once __DIR__ . '/app/index.php';