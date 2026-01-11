<?php
/**
 * CONFIGURACIÓN PARA MYSQL
 * 
 * Este archivo maneja la URL base y las credenciales de la base de datos MySQL.
 */

// Prioridad: 1. Variables de entorno (Render/Docker) | 2. Valores por defecto (Azure/Local)
define('host', getenv('DB_HOST') ?: "bd-biblioteca.mysql.database.azure.com");
define('user', getenv('DB_USER') ?: "adminuser");
define('pass', getenv('DB_PASS') ?: "199925@c");
define('db', getenv('DB_NAME') ?: "biblioteca");
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