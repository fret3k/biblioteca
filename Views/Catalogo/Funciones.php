<?php
include_once 'Views/Catalogo/conexion.php';

function obtenerLibrosPorCarrera($conexion, $id_carrera) {
    $query = "SELECT L.imagen,L.titulo, L.cantidad, L.num_pagina, A.autor, E.editorial, L.isbn, L.descripcion
              FROM libro L
              INNER JOIN autor A ON L.id_autor = A.id
              INNER JOIN editorial E ON L.id_editorial = E.id
              INNER JOIN detalle_librocarrera LC ON L.id = LC.id_libro
              WHERE LC.id_carrera = ?";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_carrera);
    $stmt->execute();
    return $stmt->get_result();
}

// Libro por Carrera
$carreras = [
    "Informática" => ["id" => 1, "imagen" => "images/Informatica/eapiis.jpg"],
    "Civil" => ["id" => 4, "imagen" => "images/Civil/civil.jpg"],
    "Agroindustrial" => ["id" => 5, "imagen" => "images/Agro/agro.jpg"],
    "Minas" => ["id" => 6, "imagen" => "images/Minas/minas.jpg"],
    "Veterinaria" => ["id" => 2, "imagen" => "images/Veterinaria/veterinaria.png"],
    "Administración" => ["id" => 3, "imagen" => "images/Admi/administracion.jpg"],
    "Educación Inicial" => ["id" => 8, "imagen" => "images/Inicial/educacion.png"],
    "Ciencias Políticas" => ["id" => 7, "imagen" => "images/Politica/politica.jpg"],
];

// Guardar los libros en un array asociativo
$librosPorCarrera = [];
foreach ($carreras as $nombre => $datos) {
    $librosPorCarrera[$datos['id']] = obtenerLibrosPorCarrera($conexion, $datos['id']);
}

//LIBROS CON MAYOR CONSECUENCIA O PRÉSTAMOS
$query1 = "SELECT 
            L.imagen, 
            L.titulo, 
            L.cantidad, 
            L.num_pagina, 
            A.autor, 
            E.editorial, 
            L.isbn, 
            L.descripcion,
            COUNT(P.id_libro) AS total_prestamos
        FROM libro L
        INNER JOIN autor A ON L.id_autor = A.id
        INNER JOIN editorial E ON L.id_editorial = E.id
        LEFT JOIN prestamo P ON L.id = P.id_libro
        GROUP BY L.id, L.imagen, L.titulo, L.cantidad, L.num_pagina, A.autor, E.editorial, L.isbn, L.descripcion
        ORDER BY total_prestamos DESC
        LIMIT 7;
";
$resultado1 = $conexion->query($query1);

if (isset($_GET['query'])) {
    $search = '%' . $_GET['query'] . '%';

    $queryb = "SELECT L.id, L.titulo AS title, A.autor AS author, L.cantidad AS copies, 
                     L.isbn, L.num_pagina, E.editorial, L.imagen, 
                     COUNT(P.id_libro) AS total_prestamos
              FROM libro L
              INNER JOIN autor A ON L.id_autor = A.id
              INNER JOIN editorial E ON L.id_editorial = E.id
              LEFT JOIN prestamo P ON L.id = P.id_libro
              WHERE LOWER(L.titulo) LIKE LOWER(?) 
                 OR LOWER(A.autor) LIKE LOWER(?) 
                 OR LOWER(L.isbn) LIKE LOWER(?)
              GROUP BY L.id, L.titulo, A.autor, L.cantidad, L.isbn, L.num_pagina, E.editorial, L.imagen
              ORDER BY total_prestamos DESC";

    $stmt = $conexion->prepare($queryb);
    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    echo json_encode($books);
    exit;
}
?>