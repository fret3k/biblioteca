<?php
class PrestamosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getPrestamos()
    {
        $sql = "SELECT e.id, e.codigo, CONCAT(e.nombre, ' ', e.apellido_pa, ' ', e.apellido_ma) AS nombre, c.carrera, l.id, l.titulo, p.id, p.id_estudiante, p.id_libro, p.fecha_prestamo, p.fecha_devolucion, p.observacion, p.estado 
            FROM estudiante e 
            INNER JOIN libro l 
            INNER JOIN prestamo p ON p.id_estudiante = e.id
            inner join carrera c on e.id_carrera = c.id
            WHERE p.id_libro = l.id";
        $res = $this->selectAll($sql);
        return $res;
    }
    public function insertarPrestamo($estudiante, $libro, string $fecha_prestamo, string $fecha_devolucion, string $observacion, $usuario)
    {
        $query = "INSERT INTO prestamo(id_estudiante, id_libro, fecha_prestamo, fecha_devolucion, cantidad, observacion, id_usuario) VALUES (?,?,?,?,?,?,?)";
        $datos = array($estudiante, $libro, $fecha_prestamo, $fecha_devolucion, 1, $observacion, $usuario);
        $data = $this->insert($query, $datos);
        if ($data > 0) {
            $lib = "SELECT * FROM libro WHERE id = $libro";
            $resLibro = $this->select($lib);
            $total = $resLibro['cantidad'] - 1;
            $libroUpdate = "UPDATE libro SET cantidad = ? WHERE id = ?";
            $datosLibro = array($total, $libro);
            $this->save($libroUpdate, $datosLibro);
            $res = $data;
        } else {
            $res = 0;
        }
        return $res;
    }
    public function actualizarPrestamo($estado, $id)
    {
        $sql = "UPDATE prestamo SET estado = ? WHERE id = ?";
        $datos = array($estado, $id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $lib = "SELECT * FROM prestamo WHERE id = $id";
            $resLibro = $this->select($lib);
            $id_libro = $resLibro['id_libro'];
            $lib = "SELECT * FROM libro WHERE id = $id_libro";
            $residLibro = $this->select($lib);
            $total = $residLibro['cantidad'] + $resLibro['cantidad'];
            $libroUpdate = "UPDATE libro SET cantidad = ? WHERE id = ?";
            $datosLibro = array($total, $id_libro);
            $this->save($libroUpdate, $datosLibro);
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }
    public function verificarPrestamoPendiente($estudiante)
    {
        $query = "SELECT * FROM prestamo WHERE id_estudiante = ? AND estado = 1";
        $datos = array($estudiante);
        $result = $this->select($query, $datos);
        return !empty($result);
    }
    public function selectDatos()
    {
        $sql = "SELECT * FROM configuracion";
        $res = $this->select($sql);
        return $res;
    }
    public function getCantLibro($libro)
    {
        $sql = "SELECT * FROM libro WHERE id = $libro";
        $res = $this->select($sql);
        return $res;
    }
    public function selectPrestamoDebe()
    {
        $sql = "
        select e.nombre, l.titulo, p.fecha_prestamo, p.cantidad from estudiante e inner join libro l inner join prestamo p on p.id_estudiante = e.id where p.id_libro = l.id and p.estado = 1 order by e.nombre ASC
        ";
        $res = $this->selectAll($sql);
        return $res;
    }
    public function selectMayorPrestamo()
    {
        $sql = "SELECT COUNT(p.id_libro) AS total_prestamos, l.titulo AS libro, l.isbn, a.autor, l.anio_edicion, l.cantidad
            FROM prestamo p
            INNER JOIN libro l ON p.id_libro = l.id
            INNER JOIN autor a ON l.id_autor = a.id
            GROUP BY l.titulo, a.autor
            ORDER BY total_prestamos DESC;
        ";
        $res = $this->selectAll($sql);
        return $res;
    }
    public function selectStockCritico()
    {
        $sql = "SELECT l.titulo, l.cantidad, e.editorial AS editorial, l.anio_edicion, l.isbn
            FROM libro l
            JOIN editorial e ON l.id_editorial = e.id
            WHERE l.cantidad <= 5 AND l.estado = 1
            ORDER BY l.cantidad ASC;
        ";
        $res = $this->selectAll($sql);
        return $res;
    }
    public function selectPrestamosPorPeriodo($fechaInicio, $fechaFin)
    {
        $sql = "SELECT l.isbn, l.titulo, p.fecha_prestamo, p.fecha_devolucion
            FROM prestamo p
            INNER JOIN libro l ON l.id = p.id_libro
            WHERE p.fecha_prestamo BETWEEN ? AND ?;
        ";
        $datos = array($fechaInicio, $fechaFin);
        $res = $this->selectWithParams($sql, $datos);
        return $res;
    }
    public function selectEstudianteMasPrestamo()
    {
        $sql = "SELECT e.codigo, e.nombre, c.carrera AS carrera, COUNT(p.id) AS total_prestamos 
            FROM estudiante e 
            JOIN prestamo p ON e.id = p.id_estudiante 
            JOIN carrera c ON e.id_carrera = c.id 
            WHERE p.fecha_prestamo BETWEEN '2025-01-01' AND '2025-02-26' 
            GROUP BY e.id, e.codigo, e.nombre, c.carrera 
            ORDER BY total_prestamos DESC 
            LIMIT 10";
    
        $res = $this->selectAll($sql);
        return $res;
    }
    public function selectMayorMateria()
    {
        $sql = "SELECT COUNT(l.id_materia) AS total_prestamos, m.materia
            FROM prestamo p
            INNER JOIN libro l ON p.id_libro = l.id
            INNER JOIN materia m ON l.id_materia = m.id
            GROUP BY m.materia
            ORDER BY total_prestamos DESC;
        ";
        $res = $this->selectAll($sql);
        return $res;
    }
    public function selectMayorCarrera()
    {
        $sql = "SELECT COUNT(e.id_carrera) AS total_prestamos, c.carrera
            FROM prestamo p
            inner join estudiante e on p.id_estudiante = e.id
            inner join carrera c on e.id_carrera = c.id
            GROUP BY c.carrera
            ORDER BY total_prestamos DESC;
        ";
        $res = $this->selectAll($sql);
        return $res;
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
    public function getPrestamoLibro($id_prestamo)
    {
        $sql = "SELECT e.id, e.codigo, e.nombre, l.id, l.titulo, p.id, p.id_estudiante, p.id_libro, p.fecha_prestamo, p.fecha_devolucion, p.cantidad, p.observacion, p.estado FROM estudiante e INNER JOIN libro l INNER JOIN prestamo p ON p.id_estudiante = e.id WHERE p.id_libro = l.id AND p.id = $id_prestamo";
        $res = $this->select($sql);
        return $res;
    }
}
