<?php
class Libros extends Controller
{
    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        parent::__construct();
        $id_user = $_SESSION['id_usuario'];
        $perm = $this->model->verificarPermisos($id_user, "Libros");
        if (!$perm && $id_user != 1) {
            $this->views->getView($this, "permisos");
            exit;
        }
    }
    public function index()
    {
        $this->views->getView($this, "index");
    }
    public function listar()
    {
        $data = $this->model->getLibros();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['foto'] = '<img class="img-thumbnail" src="' . base_url . "Assets/img/libros/" . $data[$i]['imagen'] . '" width="100">';
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                $data[$i]['acciones'] = '<div class="d-flex">
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
                <button class="btn btn-icon btn-sm btn btn-primary" type="button" title="Editar" onclick="btnEditarLibro(' . $data[$i]['id'] . ');">
                    <i class="fa fa-pencil-square-o"></i>
                </button>
                <button class="btn btn-icon btn-sm btn btn-primary" type="button" title="Materia" onclick="btnRolesCarrera(' . $data[$i]['id'] . ');">
                    <i class="fa fa-user-graduate"></i>
                </button>
                <button class="btn btn-icon btn-sm btn btn-danger" type="button" title="Desactivar" onclick="btnEliminarLibro(' . $data[$i]['id'] . ');">
                    <i class="fa fa-trash-o"></i>
                </button>
                <div/>';
            } else {
                $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-icon btn-sm btn btn-success" type="button" title="Activar" onclick="btnReingresarLibro(' . $data[$i]['id'] . ');">
                    <i class="fa fa-reply-all"></i>
                </button>
                <div/>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        $titulo = strClean($_POST['titulo']);
        $autor = strClean($_POST['autor']);
        $editorial = strClean($_POST['editorial']);
        $materia = strClean($_POST['materia']);
        $isbn = preg_replace('/-/', '', strClean($_POST['isbn'])); 
        $cantidad = strClean($_POST['cantidad']);
        $num_pagina = strClean($_POST['num_pagina']);
        $anio_edicion = strClean($_POST['anio_edicion']);
        $descripcion = strClean($_POST['descripcion']);
        $id = strClean($_POST['id']);
        $img = $_FILES['imagen'];
        $name = $img['name'];
        $fecha = date("YmdHis");
        $tmpName = $img['tmp_name'];

        if (empty($titulo) || empty($autor) || empty($editorial) || empty($materia) || empty($cantidad) || empty($isbn)) {
            $msg = array('msg' => 'Todo los campos son requeridos', 'icono' => 'warning');
        } else {
            if (!empty($name)) {
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $formatos_permitidos =  array('png', 'jpeg', 'jpg', 'jpg');
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                if (!in_array($extension, $formatos_permitidos)) {
                    $msg = array('msg' => 'Archivo no permitido', 'icono' => 'warning');
                } else {
                    $imgNombre = $fecha . ".jpg";
                    $destino = "Assets/img/libros/" . $imgNombre;
                }
            } else if (!empty($_POST['foto_actual']) && empty($name)) {
                $imgNombre = $_POST['foto_actual'];
            } else {
                $imgNombre = "logo.png";
            }
            if ($id == "") {
                $data = $this->model->insertarLibros($titulo, $autor, $editorial, $materia, $cantidad, $num_pagina, $anio_edicion, $descripcion, $imgNombre, $isbn);
                if ($data == "ok") {
                    if (!empty($name)) {
                        move_uploaded_file($tmpName, $destino);
                    }
                    $msg = array('msg' => 'Libro registrado', 'icono' => 'success');
                } else if ($data == "existe") {
                    $msg = array('msg' => 'El libro ya existe', 'icono' => 'warning');
                } else {
                    $msg = array('msg' => 'Error al registrar', 'icono' => 'error');
                }
            } else {
                $imgDelete = $this->model->editLibros($id);
                if ($imgDelete['imagen'] != 'logo.png') {
                    if (file_exists("Assets/img/libros/" . $imgDelete['imagen'])) {
                        unlink("Assets/img/libros/" . $imgDelete['imagen']);
                    }
                }
                $data = $this->model->actualizarLibros($titulo, $autor, $editorial, $materia, $cantidad, $num_pagina, $anio_edicion, $descripcion, $imgNombre, $isbn, $id);
                if ($data == "modificado") {
                    if (!empty($name)) {
                        move_uploaded_file($tmpName, $destino);
                    }
                    $msg = array('msg' => 'Libro modificado', 'icono' => 'success');
                } else if ($data == "existe") {
                    $msg = array('msg' => 'El libro ya existe', 'icono' => 'warning');
                } else {
                    $msg = array('msg' => 'Error al modificar', 'icono' => 'error');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function editar($id)
    {
        $data = $this->model->editLibros($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function eliminar($id)
    {
        $tienePrestamo = $this->model->verificarPrestamosPendientes($id);
        if ($tienePrestamo['total'] > 0) {
            $msg = array('msg' => 'Libro con préstamo pendiente', 'icono' => 'warning');
        } else {
            $data = $this->model->estadoLibros(0, $id);
            if ($data == 1) {
                $msg = array('msg' => 'Libro dado de baja', 'icono' => 'success');
            } else {
                $msg = array('msg' => 'Error al eliminar', 'icono' => 'error');
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function reingresar($id)
    {
        $data = $this->model->estadoLibros(1, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Libro restaurado', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al restaurar', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function verificar($id_libro)
    {
        if (is_numeric($id_libro)) {
            $data = $this->model->editLibros($id_libro);
            if (!empty($data)) {
                $msg = array('cantidad' => $data['cantidad'], 'icono' => 'success');
            }
        }else{
            $msg = array('msg' => 'Error Fatal', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function buscarLibro()
    {
        if (isset($_GET['lb'])) {
            $valor = $_GET['lb'];
            $data = $this->model->buscarLibro($valor);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function carreras($id)
    {
        $data = $this->model->getCarreras();
        $asignados = $this->model->getDetalleCarreras($id);
        $datos = array();
        foreach ($asignados as $asignado) {
            $datos[$asignado['id_carrera']] = true;
        }
        echo '<div class="row">
        <input type="hidden" name="id_libro" value="' . $id . '">';
        foreach ($data as $row) {
            echo '<div class="col-md-4 text-center mb-4">
                    <hr>
                    <label for="" class="font-weight-bold text-capitalize">' . $row['carrera'] . '</label>
                    <div class="center">
                        <input type="checkbox" name="carreras[]" value="' . $row['id'] . '" ';
            if (isset($datos[$row['id']])) {
                echo "checked";
            }
            echo '>
                        <span class="span">On</span>
                        <span class="span">Off</span>
                    </div>
                </div>';
        }
        echo '</div>
            <button class="btn btn-primary mt-3 btn-block" type="button" onclick="registrarCarreras(event);">
                <i class="fa fa-pencil-square-o"></i> Actualizar
            </button>';
        die();
    }
    public function registrarCarreras()
    {
        $id = strClean($_POST['id_libro']);
        $carreras = $_POST['carreras'];
        $this->model->deleteCarreras($id);
        if ($carreras != "") {
            foreach ($carreras as $carrera) {
                $this->model->actualizarCarreras($id, $carrera);
            }
        }
        echo json_encode("ok");
        die();
    }
}
