<?php
include_once 'Config/Config.php';

try {
    $host = host;
    $db = db;
    $port = port;
    $user = user;
    $pass = pass;
    
    // Usamos PDO para ser compatibles con Supabase (PostgreSQL)
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    $conexion = new PDO($dsn, $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Error de conexión con Supabase (Catalogo): " . $e->getMessage());
}
?>
