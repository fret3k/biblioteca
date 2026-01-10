<?php
class Carrera extends Controller
{
    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        parent::__construct();
        $id_user = $_SESSION['id_usuario'];
        $perm = $this->model->verificarPermisos($id_user, "Carrera");
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
        $data = $this->model->getCarrera();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-icon btn-sm btn btn-primary" type="button" title="Editar" onclick="btnEditarEdi(' . $data[$i]['id'] . ');">
                    <i class="fa fa-pencil-square-o"></i>
                </button>
                <button class="btn btn-icon btn-sm btn btn-danger" type="button" title="Desactivar" onclick="btnEliminarEdi(' . $data[$i]['id'] . ');">
                    <i class="fa fa-trash-o"></i>
                </button>
                <div/>';
            } else {
                $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-icon btn-sm btn btn-success" type="button" title="Activar" onclick="btnReingresarEdi(' . $data[$i]['id'] . ');">
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
        $carrera = strClean($_POST['carrera']);
        $id = strClean($_POST['id']);
        if (empty($carrera)) {
            $msg = array('msg' => 'El nombre es requerido', 'icono' => 'warning');
        } else {
            if ($id == "") {
                $data = $this->model->insertarCarrera($carrera);
                if ($data == "ok") {
                    $msg = array('msg' => 'Carrera registrado', 'icono' => 'success');
                } else if ($data == "existe") {
                    $msg = array('msg' => 'El carrera ya existe', 'icono' => 'warning');
                } else {
                    $msg = array('msg' => 'Error al registrar', 'icono' => 'error');
                }
            } else {
                $data = $this->model->actualizarCarrera($carrera, $id);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Carrera modificado', 'icono' => 'success');
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
        $data = $this->model->editCarrera($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function eliminar($id)
    {
        $data = $this->model->estadoCarrera(0, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Carrera dado de baja', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al eliminar', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function reingresar($id)
    {
        $data = $this->model->estadoCarrera(1, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Carrera restaurado', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al restaurar', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function buscarCarrera()
    {
        if (isset($_GET['q'])) {
            $valor = $_GET['q'];
            $data = $this->model->buscarCarrera($valor);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
}
