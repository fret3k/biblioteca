<?php
/**
 * CONFIGURACIÓN PARA MYSQL (INFINITYFREE)
 * 
 * Este archivo maneja la URL base y las credenciales de la base de datos MySQL.
 */

// Credenciales obtenidas de InfinityFree
define('host', getenv('DB_HOST') ?: "sql302.infinityfree.com");
define('user', getenv('DB_USER') ?: "if0_40875887");
define('pass', getenv('DB_PASS') ?: "H1d0lPgJujhEx");
define('db', getenv('DB_NAME') ?: "if0_40875887_biblioteca");
define('charset', "charset=utf8");

// --- LÓGICA DE URL BASE ---
$protocol = 'http://';
if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
    $protocol = 'https://';
}
$host_url = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
$script_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$base_path = ($script_path === '/') ? '/' : $script_path . '/';
$computed_base_url = $protocol . $host_url . $base_path;

define('base_url', getenv('BASE_URL') ?: $computed_base_url);
?>