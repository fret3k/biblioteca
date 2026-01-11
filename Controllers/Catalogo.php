<?php
class Catalogo extends Controller
{
    public function __construct() {
        parent::__construct();
    }
    public function index()
    {
        // El catálogo no requiere sesión activa por lo que no llamamos a session_start() con validación
        $this->views->getView($this, "index");
    }
}
?>
