<?php
ini_set('display_errors', 0);
error_reporting(0);
class EstudiantesModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getEstudiantes()
    {
        $sql = "SELECT e.*, c.carrera FROM estudiante e INNER JOIN carrera c ON e.id_carrera = c.id";
        $res = $this->selectAll($sql);
        return $res;
    }
    public function insertarEstudiante($codigo, $dni, $nombre,$apellido_pa,$apellido_ma,$genero,$id_carrera, $direccion, $telefono)
    {
        $verificar = "SELECT * FROM estudiante WHERE codigo = '$codigo' OR dni = '$dni' OR telefono = '$telefono'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $query = "INSERT INTO estudiante(codigo,dni,nombre,apellido_pa, apellido_ma, genero, id_carrera,direccion,telefono) VALUES (?,?,?,?,?,?,?,?,?)";
            $datos = array($codigo, $dni, $nombre,$apellido_pa, $apellido_ma,$genero, $id_carrera, $direccion,$telefono);
            $data = $this->save($query, $datos);
            if ($data == 1) {
                $res = "ok";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }
        return $res;
    }
    public function editEstudiante($id)
    {
        $sql = "SELECT * FROM estudiante WHERE id = $id";
        $res = $this->select($sql);
        return $res;
    }
    public function actualizarEstudiante($codigo, $dni, $nombre, $apellido_pa, $apellido_ma, $genero, $id_carrera, $direccion, $telefono, $id)
    {
        $verificar = "SELECT * FROM estudiante WHERE (codigo = ? OR dni = ? OR telefono = ?) AND id != ?";
        $datosVerificacion = array($codigo, $dni, $telefono, $id);
        $existe = $this->select($verificar, $datosVerificacion);

        if (empty($existe)) {
            $query = "UPDATE estudiante SET codigo = ?, dni = ?, nombre = ?, apellido_pa = ?, apellido_ma = ?, genero = ?, id_carrera = ?, direccion = ?, telefono = ? WHERE id = ?";
            $datos = array($codigo, $dni, $nombre, $apellido_pa, $apellido_ma, $genero, $id_carrera, $direccion, $telefono, $id);
            $data = $this->save($query, $datos);
            $res = $data == 1 ? "modificado" : "error";
        } else {
            $res = "existe";
        }
        
        return $res;
    }
    public function estadoEstudiante($estado, $id)
    {
        $query = "UPDATE estudiante SET estado = ? WHERE id = ?";
        $datos = array($estado, $id);
        $data = $this->save($query, $datos);
        return $data;
    }
    public function verificarPrestamosPendientes($id)
    {
        $query = "SELECT COUNT(*) AS total
            FROM prestamo p
            WHERE p.id_estudiante = ?
            AND p.estado = 1
        ";
        $datos = array($id);
        $data = $this->select($query, $datos);
        return $data;
    }
    public function buscarEstudiante($valor)
    {
        $sql = "SELECT id, codigo, CONCAT(nombre, ' ', apellido_pa, ' ', apellido_ma) AS text FROM estudiante 
            WHERE (codigo LIKE '%" . $valor . "%' OR nombre LIKE '%" . $valor . "%' OR dni LIKE '%" . $valor . "%' OR apellido_pa LIKE '%" . $valor . "%' OR apellido_ma LIKE '%" . $valor . "%') 
            AND estado = 1 
            LIMIT 10;";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function verificarPermisos($id_user, $permiso)
    {
        $tiene = false;
        $sql = "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'";
        $existe = $this->select($sql);
        if ($existe != null || $existe != "") {
            $tiene = true;
        }
        return $tiene;
    }
}
