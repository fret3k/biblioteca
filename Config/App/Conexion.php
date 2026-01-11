<?php
class Conexion{
    private $conect;
    public function __construct()
    {
        $host = host;
        $db = db;
        $user = user;
        $pass = pass;
        $charset = charset;
        
        $pdo = "mysql:host=$host;dbname=$db;$charset";
        
        try {
            $this->conect = new PDO($pdo, $user, $pass);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error en la conexión con MySQL: " . $e->getMessage();
        }
    }
    public function conect()
    {
        return $this->conect;
    }
}
?>