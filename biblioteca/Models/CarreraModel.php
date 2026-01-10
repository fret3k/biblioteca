<?php
class CarreraModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getCarrera()
    {
        $sql = "SELECT * FROM carrera";
        $res = $this->selectAll($sql);
        return $res;
    }
    public function insertarCarrera($carrera)
    {
        $verificar = "SELECT * FROM carrera WHERE carrera = '$carrera'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $query = "INSERT INTO carrera(carrera) VALUES (?)";
            $datos = array($carrera);
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
    public function editCarrera($id)
    {
        $sql = "SELECT * FROM carrera WHERE id = $id";
        $res = $this->select($sql);
        return $res;
    }
    public function actualizarCarrera($carrera, $id)
    {
        $query = "UPDATE carrera SET carrera = ? WHERE id = ?";
        $datos = array($carrera, $id);
        $data = $this->save($query, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }
    public function estadoCarrera($estado, $id)
    {
        $query = "UPDATE carrera SET estado = ? WHERE id = ?";
        $datos = array($estado, $id);
        $data = $this->save($query, $datos);
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
    public function buscarCarrera($valor)
    {
        $sql = "SELECT id, carrera AS text FROM carrera WHERE carrera LIKE '%" . $valor . "%'  AND estado = 1 LIMIT 10";
        $data = $this->selectAll($sql);
        return $data;
    }

}
