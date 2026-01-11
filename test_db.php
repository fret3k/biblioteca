<?php
require_once "Config/Config.php";

$pdo = "mysql:host=" . host . ";dbname=" . db . ";charset=utf8";
echo "<h3>Testeando Conexión a Base de Datos</h3>";
echo "Host: " . host . "<br>";
echo "Base de datos: " . db . "<br>";
echo "Usuario: " . user . "<br>";

try {
    $conect = new PDO($pdo, user, pass);
    $conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<br><b style='color: green;'>✅ Conexión establecida correctamente.</b>";
    
    // Test simple query
    $query = $conect->query("SHOW TABLES");
    $tables = $query->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<br><br>Tablas encontradas en la base de datos:<br>";
    if (count($tables) > 0) {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>" . $table . "</li>";
        }
        echo "</ul>";
    } else {
        echo "La base de datos está vacía.";
    }

} catch (PDOException $e) {
    echo "<br><b style='color: red;'>❌ Error en la conexión:</b> " . $e->getMessage();
    
    if (strpos($e->getMessage(), 'Connect timeout') !== false || strpos($e->getMessage(), 'Connection refused') !== false) {
        echo "<br><br><b>Sugerencia:</b> Es posible que el servidor de base de datos (Azure) esté bloqueando la IP de Render o que los datos de acceso sean incorrectos.";
    }
}
?>
