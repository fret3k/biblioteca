<?php
class Prestamos extends Controller
{
    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        parent::__construct();
        $id_user = $_SESSION['id_usuario'];
        $perm = $this->model->verificarPermisos($id_user, "Prestamos");
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
        $data = $this->model->getPrestamos();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-secondary">Prestado</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-icon btn-sm btn btn-primary" type="button" title="Entregar" onclick="btnEntregar(' . $data[$i]['id'] . ');">
                    <i class="fa fa-hourglass-start"></i>
                </button>
                <a class="btn btn-icon btn-sm btn btn-danger" target="_blank" title="Generar Ticket" href="'.base_url.'Prestamos/ticked/'. $data[$i]['id'].'">
                    <i class="fa fa-file-pdf-o"></i>
                </a>
                <div/>';
            } else {
                $data[$i]['estado'] = '<span class="badge badge-primary">Devuelto</span>';
                $data[$i]['acciones'] = '<div>
                <a class="btn btn-icon btn-sm btn btn-danger" target="_blank" title="Generar Ticket" href="'.base_url.'Prestamos/ticked/'. $data[$i]['id'].'">
                    <i class="fa fa-file-pdf-o"></i>
                </a>
                <div/>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        $libro = strClean($_POST['libro']);
        $estudiante = strClean($_POST['estudiante']);
        $cantidad = 1;
        $fecha_prestamo = strClean($_POST['fecha_prestamo']);
        $fecha_devolucion = strClean($_POST['fecha_devolucion']);
        $observacion = strClean($_POST['observacion']);
        $usuario = strClean($_SESSION['id_usuario']);
        if (empty($libro) || empty($estudiante) || empty($fecha_prestamo) || empty($fecha_devolucion)) {
            $msg = array('msg' => 'Todo los campos son requeridos', 'icono' => 'warning');
        } else {
            $prestamoPendiente = $this->model->verificarPrestamoPendiente($estudiante);
            if ($prestamoPendiente) {
                $msg = array('msg' => 'El estudiante tiene un préstamo pendiente.', 'icono' => 'warning');
            } else {
                $verificar_cant = $this->model->getCantLibro($libro);
                if ($verificar_cant['cantidad'] >= $cantidad) {
                    $data = $this->model->insertarPrestamo($estudiante, $libro, $fecha_prestamo, $fecha_devolucion, $observacion, $usuario);
                    if (is_numeric($data) && $data > 0) {
                        $msg = array('msg' => 'Libro Prestado', 'icono' => 'success', 'id' => $data);
                    } else {
                        $msg = array('msg' => 'Error al prestar', 'icono' => 'error');
                    }        
                } else {
                    $msg = array('msg' => 'Stock no disponible', 'icono' => 'warning');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function entregar($id)
    {
        $datos = $this->model->actualizarPrestamo(0, $id);
        if ($datos == "ok") {
            $msg = array('msg' => 'Libro recibido', 'icono' => 'success');
        }else{
            $msg = array('msg' => 'Error al recibir el libro', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();

    }
    public function pdf()
    {
        $datos = $this->model->selectDatos();
        $prestamo = $this->model->selectPrestamoDebe();
        if (empty($prestamo)) {
            header('Location: ' . base_url . 'Configuracion/vacio');
        }
        require_once 'Libraries/pdf/fpdf.php';
        $pdf = new FPDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle("Prestamos");
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(195, 5, utf8_decode($datos['nombre']), 0, 1, 'C');

        $pdf->Image(base_url. "Assets/img/logo.png", 180, 10, 23, 23, 'PNG');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, $datos['telefono'], 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, "Correo: ", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, utf8_decode($datos['correo']), 0, 1, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(196, 5, "Detalle de Prestamos", 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(12, 5, utf8_decode('N°'), 1, 0, 'L');
        $pdf->Cell(50, 5, utf8_decode('Estudiantes'), 1, 0, 'L');
        $pdf->Cell(89, 5, 'Libros', 1, 0, 'L');
        $pdf->Cell(30, 5, 'Fecha Prestamo', 1, 0, 'L');
        $pdf->Cell(15, 5, 'Cant.', 1, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $contador = 1;
        foreach ($prestamo as $row) {
            $pdf->Cell(12, 5, $contador, 1, 0, 'L');
            $pdf->Cell(50, 5, $row['nombre'], 1, 0, 'L');
            $pdf->Cell(89, 5, utf8_decode($row['titulo']), 1, 0, 'L');
            $pdf->Cell(30, 5, $row['fecha_prestamo'], 1, 0, 'L');
            $pdf->Cell(15, 5, $row['cantidad'], 1, 1, 'L');
            $contador++;
        }
        $pdf->Output("prestamos.pdf", "I");
    }
    public function ticked($id_prestamo)
    {
        $datos = $this->model->selectDatos();
        $prestamo = $this->model->getPrestamoLibro($id_prestamo);
        if (empty($prestamo)) {
            header('Location: '.base_url. 'Configuracion/vacio');
        }
        require_once 'Libraries/pdf/fpdf.php';
        $pdf = new FPDF('P', 'mm', array(80, 200));
        $pdf->AddPage();
        $pdf->SetMargins(5, 5, 5);
        $pdf->SetTitle("Prestamos");
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(65, 5, utf8_decode($datos['nombre']), 0, 1, 'C');

        $pdf->Image(base_url . "Assets/img/logo.png", 55, 15, 20, 20, 'PNG');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, 5, $datos['telefono'], 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode("Dirección: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, "Correo: ", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, 5, utf8_decode($datos['correo']), 0, 1, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(72, 5, "Detalle de Prestamos", 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(60, 5, 'Libros', 1, 0, 'L');
        $pdf->Cell(12, 5, 'Cant.', 1, 1, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(60, 5, utf8_decode($prestamo['titulo']), 1, 0, 'L');
        $pdf->Cell(12, 5, $prestamo['cantidad'], 1, 1, 'L');
        $pdf->Ln();
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(72, 5, "Estudiante", 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(35, 5, 'Nombre.', 1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 5, $prestamo['nombre'], 1, 0, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(72, 5, 'Fecha Prestamo', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(72, 5, $prestamo['fecha_prestamo'], 0, 1, 'C');
        $pdf->Output("prestamos.pdf", "I");
    }
    public function LibrosMasPrestado()
    {
        $datos = $this->model->selectDatos();
        $prestamo = $this->model->selectMayorPrestamo();
        if (empty($prestamo)) {
            header('Location: ' . base_url . 'Configuracion/vacio');
        }
        require_once 'Libraries/pdf/fpdf.php';
        $pdf = new FPDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle("Conteo de libros prestados");
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(195, 5, utf8_decode($datos['nombre']), 0, 1, 'C');

        $pdf->Image(base_url. "Assets/img/logo.png", 180, 10, 23, 23, 'PNG');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, $datos['telefono'], 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, "Correo: ", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 5, utf8_decode($datos['correo']), 0, 1, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(196, 5, "Detalle de Prestamos", 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(12, 5, utf8_decode('N°'), 1, 0, 'L');
        $pdf->Cell(19, 5, utf8_decode('Prestados'), 1, 0, 'L');
        $pdf->Cell(60, 5, utf8_decode('Libro'), 1, 0, 'L');
        $pdf->Cell(30, 5, 'ISBN', 1, 0, 'L');
        $pdf->Cell(38, 5, 'Autor', 1, 0, 'L');
        $pdf->Cell(25, 5, utf8_decode('Año'), 1, 0, 'L');
        $pdf->Cell(12, 5, 'Cant.', 1, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $contador = 1;
        foreach ($prestamo as $row) {
            $pdf->Cell(12, 5, $contador, 1, 0, 'L');
            $pdf->Cell(19, 5, $row['total_prestamos'], 1, 0, 'L');
            $pdf->Cell(60, 5, utf8_decode($row['libro']), 1, 0, 'L');
            $pdf->Cell(30, 5, $row['isbn'], 1, 0, 'L');
            $pdf->Cell(38, 5, utf8_decode($row['autor']), 1, 0, 'L');
            $pdf->Cell(25, 5, $row['anio_edicion'], 1, 0, 'L');
            $pdf->Cell(12, 5, $row['cantidad'], 1, 1, 'L');
            $contador++;
        }
        $pdf->Output("prestamos.pdf", "I");
    }
    public function LibrosStockCritico()
    {
        $datos = $this->model->selectDatos();
        $prestamo = $this->model->selectStockCritico();
        if (empty($prestamo)) {
            header('Location: ' . base_url . 'Configuracion/vacio');
        }
        require_once 'Libraries/pdf/fpdf.php';
        $pdf = new FPDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle("Libros con Stock crítico");
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(195, 5, utf8_decode($datos['nombre']), 0, 1, 'C');

        $pdf->Image(base_url. "Assets/img/logo.png", 180, 10, 23, 23, 'PNG');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, $datos['telefono'], 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, "Correo: ", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 5, utf8_decode($datos['correo']), 0, 1, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(196, 5, "Detalle de Libros", 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(12, 5, 'N°', 1, 0, 'L');
        $pdf->Cell(41, 5, utf8_decode('Libro'), 1, 0, 'L');
        $pdf->Cell(20, 5, 'Cantidad', 1, 0, 'L');
        $pdf->Cell(60, 5, utf8_decode('Editorial'), 1, 0, 'L');
        $pdf->Cell(34, 5, 'Anio de edicion', 1, 0, 'L');
        $pdf->Cell(29, 5, 'ISBN', 1, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $contador = 1;
        foreach ($prestamo as $row) {
            $pdf->Cell(12, 5, $contador, 1, 0, 'L');
            $pdf->Cell(41, 5, utf8_decode($row['titulo']), 1, 0, 'L');
            $pdf->Cell(20, 5, $row['cantidad'], 1, 0, 'L');
            $pdf->Cell(60, 5, utf8_decode($row['editorial']), 1, 0, 'L');
            $pdf->Cell(34, 5, $row['anio_edicion'], 1, 0, 'L');
            $pdf->Cell(29, 5, $row['isbn'], 1, 1, 'L');
            $contador++;
        }
        $pdf->Output("prestamos.pdf", "I");
    }
    public function LibrosPorPeriodo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
            $fechaInicio = $_REQUEST['FechaInicio'] ?? null;
            $fechaFin = $_REQUEST['FechaFin'] ?? null;

            if ($fechaInicio && $fechaFin) {
                $datos = $this->model->selectDatos();
                $prestamo = $this->model->selectPrestamosPorPeriodo($fechaInicio, $fechaFin);

                if (empty($prestamo)) {
                    header('Location: ' . base_url . 'Configuracion/vacio');
                }
                require_once 'Libraries/pdf/fpdf.php';
                $pdf = new FPDF('P', 'mm', 'letter');
                $pdf->AddPage();
                $pdf->SetMargins(10, 10, 10);
                $pdf->SetTitle("Libros consultados por periodo");
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(195, 5, utf8_decode($datos['nombre']), 0, 1, 'C');

                $pdf->Image(base_url. "Assets/img/logo.png", 180, 10, 23, 23, 'PNG');
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(20, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(20, 5, $datos['telefono'], 0, 1, 'L');
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(20, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(20, 5, "Correo: ", 0, 0, 'L');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(12, 5, utf8_decode($datos['correo']), 0, 1, 'L');
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetFillColor(0, 0, 0);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->Cell(196, 5, utf8_decode(sprintf("Detalle de Libros del [%s] al [%s]", $fechaInicio, $fechaFin)), 1, 1, 'C', 1);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(12, 5, utf8_decode('N°'), 1, 0, 'L');
                $pdf->Cell(29, 5, 'ISBN', 1, 0, 'L');
                $pdf->Cell(85, 5, utf8_decode('Libro'), 1, 0, 'L');
                $pdf->Cell(35, 5, utf8_decode('Fecha Préstamo'), 1, 0, 'L');
                $pdf->Cell(35, 5, utf8_decode('Fecha Devolución'), 1, 1, 'L');
                $pdf->SetFont('Arial', '', 10);
                $contador = 1;
                foreach ($prestamo as $row) {
                    $pdf->Cell(12, 5, $contador, 1, 0, 'L');
                    $pdf->Cell(29, 5, $row['isbn'], 1, 0, 'L');
                    $pdf->Cell(85, 5, utf8_decode($row['titulo']), 1, 0, 'L');
                    $pdf->Cell(35, 5, utf8_decode($row['fecha_prestamo']), 1, 0, 'L');
                    $pdf->Cell(35, 5, utf8_decode($row['fecha_devolucion']), 1, 1, 'L');
                    $contador++;
                }
                $pdf->Output("prestamos.pdf", "I");
            } else {
                echo json_encode(["msg" => "Fechas no proporcionadas", "icono" => "error"]);
            }
        } else {
            http_response_code(405);
        }
    }
    public function EstudianteMayorDemanda()
    {
        $datos = $this->model->selectDatos();
        $prestamos = $this->model->selectEstudianteMasPrestamo();

        if (empty($prestamos)) {
            header('Location: ' . base_url . 'Configuracion/vacio');
            return;
        }

        require_once 'Libraries/pdf/fpdf.php';
        $pdf = new FPDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle("Estudiantes con Mas Prestamos");

        require_once 'Libraries/pdf/fpdf.php';
        $pdf = new FPDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle("Conteo de libros prestados");
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(195, 5, utf8_decode($datos['nombre']), 0, 1, 'C');

        // Información del encabezado
        $pdf->Image(base_url . "Assets/img/logo.png", 180, 10, 23, 23, 'PNG');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, $datos['telefono'], 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, "Correo: ", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 5, utf8_decode($datos['correo']), 0, 1, 'L');
        
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(196, 5, "Estudiantes con Mas Prestamos", 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(12, 5, utf8_decode('N°'), 1, 0, 'L');
        $pdf->Cell(20, 5, utf8_decode('Código'), 1, 0, 'L');
        $pdf->Cell(50, 5, utf8_decode('Nombre'), 1, 0, 'L');
        $pdf->Cell(100, 5, 'Carrera', 1, 0, 'L');
        $pdf->Cell(14, 5, 'Total', 1, 1, 'L');

        $pdf->SetFont('Arial', '', 10);
        $contador = 1;
        
        foreach ($prestamos as $row) {
            $pdf->Cell(12, 5, $contador, 1, 0, 'L');
            $pdf->Cell(20, 5, utf8_decode($row['codigo']), 1, 0, 'L');
            $pdf->Cell(50, 5, utf8_decode($row['nombre']), 1, 0, 'L');
            $pdf->Cell(100, 5, utf8_decode($row['carrera']), 1, 0, 'L');
            $pdf->Cell(14, 5, $row['total_prestamos'], 1, 1, 'L');
            $contador++;
        }
        $pdf->Output("prestamos.pdf", "I");
    }
    public function MateriaMayorDemanda()
    {
        $datos = $this->model->selectDatos();
        $prestamo = $this->model->selectMayorMateria();
        if (empty($prestamo)) {
            header('Location: ' . base_url . 'Configuracion/vacio');
        }
        require_once 'Libraries/pdf/fpdf.php';
        $pdf = new FPDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle("Conteo de libros prestados");
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(195, 5, utf8_decode($datos['nombre']), 0, 1, 'C');

        $pdf->Image(base_url. "Assets/img/logo.png", 180, 10, 23, 23, 'PNG');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, $datos['telefono'], 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, "Correo: ", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 5, utf8_decode($datos['correo']), 0, 1, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(196, 5, "Detalle de Prestamos", 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(12, 5, utf8_decode('N°'), 1, 0, 'L');
        $pdf->Cell(50, 5, utf8_decode('Cant. de libros prestados'), 1, 0, 'L');
        $pdf->Cell(134, 5, utf8_decode('Materia'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $contador = 1;
        foreach ($prestamo as $row) {
            $pdf->Cell(12, 5, $contador, 1, 0, 'L');
            $pdf->Cell(50, 5, $row['total_prestamos'], 1, 0, 'L');
            $pdf->Cell(134, 5, utf8_decode($row['materia']), 1, 1, 'L');
            $contador++;
        }
        $pdf->Output("prestamos.pdf", "I");
    }
    public function CarreraMayorDemanda()
    {
        $datos = $this->model->selectDatos();
        $prestamo = $this->model->selectMayorCarrera();
        if (empty($prestamo)) {
            header('Location: ' . base_url . 'Configuracion/vacio');
        }
        require_once 'Libraries/pdf/fpdf.php';
        $pdf = new FPDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle("Conteo de libros prestados");
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(195, 5, utf8_decode($datos['nombre']), 0, 1, 'C');

        $pdf->Image(base_url. "Assets/img/logo.png", 180, 10, 23, 23, 'PNG');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, $datos['telefono'], 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, "Correo: ", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 5, utf8_decode($datos['correo']), 0, 1, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(196, 5, "Detalle de Prestamos", 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(12, 5, utf8_decode('N°'), 1, 0, 'L');
        $pdf->Cell(50, 5, utf8_decode('Cant. de libros prestados'), 1, 0, 'L');
        $pdf->Cell(134, 5, utf8_decode('Carrera Profesional'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $contador = 1;
        foreach ($prestamo as $row) {
            $pdf->Cell(12, 5, $contador, 1, 0, 'L');
            $pdf->Cell(50, 5, $row['total_prestamos'], 1, 0, 'L');
            $pdf->Cell(134, 5, utf8_decode($row['carrera']), 1, 1, 'L');
            $contador++;
        }
        $pdf->Output("prestamos.pdf", "I");
    }
}
