<?php
class LibrosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getLibros()
    {
        $sql = "SELECT l.*, m.materia, a.autor, e.editorial FROM libro l INNER JOIN materia m ON l.id_materia = m.id INNER JOIN autor a ON l.id_autor = a.id INNER JOIN editorial e ON l.id_editorial = e.id";
        $res = $this->selectAll($sql);
        return $res;
    }
    public function insertarLibros($titulo,$id_autor,$id_editorial,$id_materia,$cantidad,$num_pagina,$anio_edicion,$descripcion,$imgNombre,$isbn)
    {
        $verificar = "SELECT * FROM libro WHERE titulo = '$titulo' OR isbn = '$isbn'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $query = "INSERT INTO libro(titulo, id_autor, id_editorial, id_materia, cantidad, num_pagina, anio_edicion, descripcion, imagen, isbn) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $datos = array($titulo, $id_autor, $id_editorial, $id_materia, $cantidad, $num_pagina, $anio_edicion, $descripcion, $imgNombre, $isbn);
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
    public function editLibros($id)
    {
        $sql = "SELECT * FROM libro WHERE id = $id";
        $res = $this->select($sql);
        return $res;
    }
    public function actualizarLibros($titulo, $id_autor, $id_editorial, $id_materia, $cantidad, $num_pagina, $anio_edicion, $descripcion, $imgNombre, $isbn, $id)
    {
        $verificar = "SELECT * FROM libro WHERE (titulo = ? OR isbn = ?) AND id != ?";
        $datosVerificacion = array($titulo, $isbn, $id);
        $existe = $this->select($verificar, $datosVerificacion);

        if (empty($existe)) {
            $query = "UPDATE libro SET titulo = ?, id_autor = ?, id_editorial = ?, id_materia = ?, cantidad = ?, num_pagina = ?, anio_edicion = ?, descripcion = ?, imagen = ?, isbn = ? WHERE id = ?";
            $datos = array($titulo, $id_autor, $id_editorial, $id_materia, $cantidad, $num_pagina, $anio_edicion, $descripcion, $imgNombre, $isbn, $id);
            $data = $this->save($query, $datos);
            $res = $data == 1 ? "modificado" : "error";
        } else {
            $res = "existe";
        }

        return $res;
    }
    //Dar de baja temporal al libro
    public function estadoLibros($estado, $id)
    {
        $query = "UPDATE libro SET estado = ? WHERE id = ?";
        $datos = array($estado, $id);
        $data = $this->save($query, $datos);
        return $data;
    }
    //Verificar si el libro tiene prestamos pendientes
    public function verificarPrestamosPendientes($id)
    {
        $query = "SELECT COUNT(*) AS total
            FROM prestamo
            WHERE id_libro = ?
            AND estado = 1
            ";
        $datos = array($id);
        $data = $this->select($query, $datos);
        return $data;
    }
    public function buscarLibro($valor)
    {
        $sql = "SELECT id, titulo AS text FROM libro WHERE titulo LIKE '%" . $valor . "%' AND estado = 1 LIMIT 10";
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
    public function getCarreras()
    {
        $sql = "SELECT * FROM carrera";
        $data = $this->selectAll($sql);
        return $data;
    }
    public function getDetalleCarreras($id)
    {
        $sql = "SELECT * FROM detalle_librocarrera WHERE id_libro = $id";
        $data = $this->selectAll($sql);
        return $data;
    }
    public function deleteCarreras($id)
    {
        $sql = "DELETE FROM detalle_librocarrera WHERE id_libro = ?";
        $datos = array($id);
        $data = $this->save($sql, $datos);
        return $data;
    }
    public function actualizarCarreras($libro, $carrera)
    {
        $sql = "INSERT INTO detalle_librocarrera(id_libro, id_carrera) VALUES (?,?)";
            $datos = array($libro, $carrera);
            $data = $this->save($sql, $datos);
            if ($data == 1) {
                $res = "ok";
            } else {
                $res = "error";
            }
        return $res;
    }
}
