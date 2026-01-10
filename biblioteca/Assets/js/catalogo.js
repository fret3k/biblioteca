document.addEventListener("DOMContentLoaded", function () {
    // Agregar eventos a los botones para mostrar detalles de libros
    document.querySelectorAll(".btn-mas").forEach(button => {
        button.addEventListener("click", function () {
            let libro = JSON.parse(this.getAttribute("data-libro"));

            document.getElementById("modalTitulo").textContent = libro.titulo;
            document.getElementById("modalImagen").src = BASE_URL + libro.imagen;
            document.getElementById("modalCantidad").textContent = libro.cantidad;
            document.getElementById("modalPaginas").textContent = libro.num_pagina;
            document.getElementById("modalAutor").textContent = libro.autor;
            document.getElementById("modalEditorial").textContent = libro.editorial;
            document.getElementById("modalISBN").textContent = libro.isbn;
            document.getElementById("modalPrestamos").textContent = libro.total_prestamos || "N/A";
            document.getElementById("modalDescripcion").textContent = libro.descripcion || "Sin descripción.";

            document.getElementById("modalLibro").style.display = "flex";
        });
    });
});

// Mostrar Libros por Carrera
function mostrarLibros(id) {
    var contenedores = document.querySelectorAll(".libros-container");
    contenedores.forEach(c => c.style.display = "none");

    var container = document.getElementById("libros-" + id);
    if (container) {
        container.style.display = "flex";
    }
}

// Cerrar modal
function cerrarModal() {
    document.getElementById("modalLibro").style.display = "none";
}
