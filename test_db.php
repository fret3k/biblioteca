<?php
require_once "Config/Config.php";
require_once "Config/App/Conexion.php";

echo "<h3>Testeando Conexión a Supabase (PostgreSQL)</h3>";
echo "Host: " . host . "<br>";
echo "Base de datos: " . db . "<br>";
echo "Puerto: " . port . "<br>";
echo "Usuario: " . user . "<br>";

try {
    $con = new Conexion();
    $conect = $con->conect();
    
    if ($conect) {
        echo "<br><b style='color: green;'>✅ Conexión con Supabase establecida correctamente.</b>";
        
        // Test simple query
        $query = $conect->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
        $tables = $query->fetchAll(PDO::FETCH_COLUMN);
        
        echo "<br><br>Tablas encontradas en el esquema público de Supabase:<br>";
        if (count($tables) > 0) {
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li>" . $table . "</li>";
            }
            echo "</ul>";
        } else {
            echo "La base de datos está vacía o no tiene tablas en el esquema 'public'.";
        }
    } else {
        echo "<br><b style='color: red;'>❌ La conexión devolvió NULL.</b>";
    }

} catch (Exception $e) {
    echo "<br><b style='color: red;'>❌ Error en la conexión:</b> " . $e->getMessage();
}
?>
