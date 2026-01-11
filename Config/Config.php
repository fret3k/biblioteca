<?php
// Configuración dinámica de la URL base compatible con proxies (Render, Cloudflare, etc.)
$protocol = 'http://';
if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
    (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) {
    $protocol = 'https://';
}

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$base_path = ($script_path === '/') ? '/' : $script_path . '/';
$computed_base_url = $protocol . $host . $base_path;

// Definición de constantes usando define()
define('base_url', getenv('BASE_URL') ?: $computed_base_url);

define('host', getenv('DB_HOST') ?: "bd-biblioteca.mysql.database.azure.com");
define('user', getenv('DB_USER') ?: "adminuser");
define('pass', getenv('DB_PASS') ?: "199925@c");
define('db', getenv('DB_NAME') ?: "biblioteca");
define('charset', "charset=utf8");
?>