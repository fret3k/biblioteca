<?php
class Conexion{
    private $conect;
    public function __construct()
    {
        // DSN para PostgreSQL (Supabase)
        $host = host;
        $db = db;
        $port = port; // Definido en Config.php
        $user = user;
        $pass = pass;
        
        $pdo = "pgsql:host=$host;port=$port;dbname=$db;";
        
        try {
            $this->conect = new PDO($pdo, $user, $pass);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error en la conexión con Supabase: " . $e->getMessage();
        }
    }
    public function conect()
    {
        return $this->conect;
    }
}
?>