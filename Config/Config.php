<?php
// Configuración dinámica de la URL base
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $protocol = "http://";
} else {
    $protocol = "https://";
}
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script_name = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$computed_base_url = $protocol . $host . rtrim($script_name, '/\\') . '/';

// Definición de constantes usando define() para permitir ejecución de funciones
define('base_url', getenv('BASE_URL') ?: $computed_base_url);

define('host', getenv('DB_HOST') ?: "bd-biblioteca.mysql.database.azure.com");
define('user', getenv('DB_USER') ?: "adminuser");
define('pass', getenv('DB_PASS') ?: "199925@c");
define('db', getenv('DB_NAME') ?: "biblioteca");
define('charset', "charset=utf8");
?>