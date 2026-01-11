<?php
/**
 * CONFIGURACIÓN PARA SUPABASE (POSTGRESQL)
 */

// Si existe la variable de entorno la usa, si no, usa valores por defecto (ejemplo)
define('host', getenv('DB_HOST') ?: "aws-0-us-west-1.pooler.supabase.com"); // Cambia esto por tu host de Supabase
define('user', getenv('DB_USER') ?: "postgres.xxxxxx");                      // Cambia esto por tu usuario
define('pass', getenv('DB_PASS') ?: "tu_password_aqui");                    // Cambia esto por tu contraseña
define('db', getenv('DB_NAME') ?: "postgres");                              // En Supabase suele ser 'postgres'
define('port', getenv('DB_PORT') ?: "6543");                                // Puerto de Supabase (Transacción) o 5432
define('charset', "utf8");

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