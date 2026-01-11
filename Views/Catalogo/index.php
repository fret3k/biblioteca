<?php 
include_once 'Views/Catalogo/Funciones.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Virtual UNAMBA</title>
    <link rel="stylesheet" href="<?php echo base_url; ?>Views/Catalogo/style.css">
</head>
<body>
    <!-- Contenido principal -->
    <div class="main-content">
        <header class="header">
            <div class="header-content container">
                <h1>Biblioteca Virtual</h1>
                <p class="subtitle"><b>UNAMBA - Apurímac</b></p>
                <p class="subti" style="font-size: 1rem;"><i><b>"Accede a un mundo de conocimiento desde cualquier lugar".</b></i></p>
            </div>
        </header>
        <p class="p3">🔎 Escribe una palabra clave y encuentra el libro perfecto para ti.</p>
        <p class="p3"> ✨ Explora, aprende y crece con cada página.</p>
        <h2>Libros Disponibles</h2>
        <form method="GET" action="">
            <input type="text" id="searchInput" class="campo" name="searchInput" placeholder="Buscar por título o autor">
            <button id="searchButton" type="submit">Buscar</button>
        </form>
        <?php
        if (!empty($_GET['searchInput'])) {
            $searchInput = '%' . $_GET['searchInput'] . '%';

            $query = "SELECT L.imagen, L.titulo, L.cantidad, L.num_pagina, A.autor, E.editorial, L.isbn, L.descripcion
                    FROM libro L
                    INNER JOIN autor A ON L.id_autor = A.id
                    INNER JOIN editorial E ON L.id_editorial = E.id
                    WHERE LOWER(L.titulo) LIKE LOWER(:search) OR LOWER(A.autor) LIKE LOWER(:search)";
            
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':search', $searchInput, PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo '<ul class="book-list">';
            if (count($results) > 0) {
                foreach ($results as $libro) {
                    echo '<li class="product-item">
                            <div class="libro-card">
                                <img src="' . base_url . 'Assets/img/libros/' . $libro['imagen'] . '" 
                                    alt="Portada del libro" class="img-thumbnail">
                                <h3>' . htmlspecialchars($libro['titulo'], ENT_QUOTES, 'UTF-8') . '</h3>
                                <p><strong>Autor:</strong> ' . htmlspecialchars($libro['autor'], ENT_QUOTES, 'UTF-8') . '</p>
                                <button class="btn-mas" data-libro=\'' . json_encode($libro, JSON_HEX_APOS | JSON_HEX_QUOT) . '\'>
                                    Más...
                                </button>
                            </div>
                        </li>';
                }
            } else {
                echo '<p>No se encontraron resultados para la búsqueda.</p>';
            }
            echo '</ul>';
        }
        ?>

        <!-- Container -->
        <div class="container">
            <!-- Sidebar con imágenes de carreras -->
            <div class="sidebar">
                <?php foreach ($carreras as $nombre => $datos): ?>
                    <img src="<?php echo base_url; ?>Views/Catalogo/<?php echo $datos['imagen']; ?>" alt="<?php echo $nombre; ?>"
                     onclick="mostrarLibros('<?php echo $datos['id']; ?>')">
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Libros por Carrera -->
            <?php foreach ($carreras as $nombre => $datos): ?>
                <div id="libros-<?php echo $datos['id']; ?>" class="libros-container">
                    <?php 
                    $libros = $librosPorCarrera[$datos['id']]->fetchAll(PDO::FETCH_ASSOC);
                    if (count($libros) > 0): ?>
                        <ul class="book-list">
                            <?php foreach ($libros as $libro): ?>
                                <li class="product-item">
                                    <div class="libro-card">
                                        <img src="<?php echo base_url . 'Assets/img/libros/' . $libro['imagen']; ?>" 
                                            alt="Portada del libro" class="img-thumbnail">
                                        <h3><?php echo htmlspecialchars($libro['titulo'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <button class="btn-mas" data-libro='<?php echo json_encode($libro, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>
                                            Más...
                                        </button>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="p2">No hay libros disponibles para esta carrera.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <!-- Modal (compartido para todas las listas de libros) -->
            <div id="modalLibro" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close" onclick="cerrarModal()">&times;</span>
                    <h3 id="modalTitulo"></h3>
                    <img id="modalImagen" class="img-thumbnail" alt="Portada del libro">
                    <p><strong>Cantidad:</strong> <span id="modalCantidad"></span></p>
                    <p><strong>Páginas:</strong> <span id="modalPaginas"></span></p>
                    <p><strong>Autor:</strong> <span id="modalAutor"></span></p>
                    <p><strong>Editorial:</strong> <span id="modalEditorial"></span></p>
                    <p><strong>ISBN:</strong> <span id="modalISBN"></span></p>
                    <p><strong>Total Préstamos:</strong> <span id="modalPrestamos"></span></p>
                    <p><strong>Descripción:</strong> <span id="modalDescripcion"></span></p>
                </div>
            </div>

            <h2>Libros con Mayor Consecuencia</h2>
            <div class="product-gridet">
                <ul id="bookList" class="book-list">
                    <?php 
                    $librosMayor = $resultado1->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($librosMayor as $libro) { ?>
                        <li class="product-item">
                            <div class="libro-card">
                                <img src="<?php echo base_url . 'Assets/img/libros/' . $libro['imagen']; ?>" 
                                    alt="Portada del libro" class="img-thumbnail">
                                <h3><?php echo htmlspecialchars($libro['titulo'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <button class="btn-mas" data-libro='<?php echo json_encode($libro, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>
                                    Más...
                                </button>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
    </div>

    <footer>
        <div class="footer-bg">
            <p><i><b>"Explora, aprende y crece con nuestra biblioteca virtual".</b></i></p>
            <div class="footer-info">
                <div>
                    <h3 class="h3info">Ubicación</h3>
                    <p>Av. Garcilazo de la Vega S/N Tamburco - Abancay - Apurímac</p>
                </div>
                <div>
                    <h3 class="h3info">Horario de Atención</h3>
                    <p>Lunes a Viernes de 7:30 a 15:30</p>
                </div>
                <div>
                    <h3 class="h3info">Contacto</h3>
                    <p>Central telefónica: 083-321965</p>
                </div>
            </div>
        </div>
    </footer>
    <script>
        const BASE_URL = "<?php echo base_url . 'Assets/img/libros/'; ?>";
    </script>
    <script src="<?php echo base_url; ?>Assets/js/catalogo.js"></script>
</body>
</html>
