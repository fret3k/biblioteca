-- SCRIPT PARA SUPABASE (POSTGRESQL)
-- Este script crea las tablas e inserta los datos iniciales necesarios.
-- IMPORTANTE: Ejecuta este script en el "SQL Editor" de tu proyecto en Supabase.

-- 1. LIMPIEZA DE TABLAS (Opcional, úsalo si quieres reiniciar la DB)
DROP TABLE IF EXISTS prestamo CASCADE;
DROP TABLE IF EXISTS detalle_librocarrera CASCADE;
DROP TABLE IF EXISTS detalle_permisos CASCADE;
DROP TABLE IF EXISTS libro CASCADE;
DROP TABLE IF EXISTS estudiante CASCADE;
DROP TABLE IF EXISTS carrera CASCADE;
DROP TABLE IF EXISTS autor CASCADE;
DROP TABLE IF EXISTS editorial CASCADE;
DROP TABLE IF EXISTS materia CASCADE;
DROP TABLE IF EXISTS permisos CASCADE;
DROP TABLE IF EXISTS usuarios CASCADE;
DROP TABLE IF EXISTS configuracion CASCADE;

-- 2. CREACIÓN DE TABLAS
CREATE TABLE autor (
  id SERIAL PRIMARY KEY,
  autor VARCHAR(150) NOT NULL,
  imagen VARCHAR(100) NOT NULL,
  estado INT DEFAULT 1
);

CREATE TABLE editorial (
  id SERIAL PRIMARY KEY,
  editorial VARCHAR(150) NOT NULL,
  estado INT DEFAULT 1
);

CREATE TABLE materia (
  id SERIAL PRIMARY KEY,
  materia TEXT NOT NULL,
  estado INT DEFAULT 1
);

CREATE TABLE permisos (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  tipo INT NOT NULL
);

CREATE TABLE usuarios (
  id SERIAL PRIMARY KEY,
  usuario VARCHAR(50) NOT NULL,
  nombre VARCHAR(200) NOT NULL,
  clave VARCHAR(100) NOT NULL,
  estado INT DEFAULT 1
);

CREATE TABLE carrera (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  estado INT DEFAULT 1
);

CREATE TABLE configuracion (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  telefono VARCHAR(20) NOT NULL,
  direccion TEXT NOT NULL,
  correo VARCHAR(100) NOT NULL,
  foto VARCHAR(50) NOT NULL
);

CREATE TABLE estudiante (
  id SERIAL PRIMARY KEY,
  codigo VARCHAR(20) NOT NULL,
  dni VARCHAR(20) NOT NULL,
  nombre VARCHAR(150) NOT NULL,
  carrera VARCHAR(255) NOT NULL,
  direccion TEXT NOT NULL,
  telefono VARCHAR(15) NOT NULL,
  id_carrera INT REFERENCES carrera(id),
  estado INT DEFAULT 1
);

CREATE TABLE libro (
  id SERIAL PRIMARY KEY,
  titulo TEXT NOT NULL,
  cantidad INT NOT NULL,
  id_autor INT REFERENCES autor(id),
  id_editorial INT REFERENCES editorial(id),
  anio_edicion DATE NOT NULL,
  id_materia INT REFERENCES materia(id),
  num_pagina INT NOT NULL,
  descripcion TEXT NOT NULL,
  imagen VARCHAR(100) NOT NULL,
  estado INT DEFAULT 1
);

-- Tabla intermedia para libros y carreras (si aplica en tu diseño)
CREATE TABLE detalle_librocarrera (
  id SERIAL PRIMARY KEY,
  id_libro INT REFERENCES libro(id),
  id_carrera INT REFERENCES carrera(id)
);

CREATE TABLE prestamo (
  id SERIAL PRIMARY KEY,
  id_estudiante INT REFERENCES estudiante(id),
  id_libro INT REFERENCES libro(id),
  id_usuario INT REFERENCES usuarios(id),
  fecha_prestamo DATE NOT NULL,
  fecha_devolucion DATE NOT NULL,
  cantidad INT NOT NULL,
  observacion TEXT NOT NULL,
  estado INT DEFAULT 1
);

CREATE TABLE detalle_permisos (
  id SERIAL PRIMARY KEY,
  id_usuario INT REFERENCES usuarios(id),
  id_permiso INT REFERENCES permisos(id)
);

-- 3. INSERCIÓN DE DATOS INICIALES
-- Usuario administrador (clave: admin)
INSERT INTO usuarios (usuario, nombre, clave, estado) VALUES
('admin', 'Administrador Sistema', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 1);

-- Configuración base
INSERT INTO configuracion (nombre, telefono, direccion, correo, foto) VALUES
('Biblioteca Virtual UNAMBA', '083-321965', 'Av. Garcilazo de la Vega S/N - Apurímac', 'biblioteca@unamba.edu.pe', 'logo.png');

-- Lista de Permisos
INSERT INTO permisos (nombre, tipo) VALUES 
('Libros', 1), ('Autor', 2), ('Editorial', 3), ('Usuarios', 4), 
('Configuracion', 5), ('Estudiantes', 6), ('Materias', 7), ('Reportes', 8), ('Prestamos', 9);

-- Carreras de ejemplo
INSERT INTO carrera (nombre) VALUES 
('Ingeniería de Sistemas'), ('Ingeniería Civil'), ('Ingeniería Agroindustrial'), 
('Ingeniería de Minas'), ('Medicina Veterinaria'), ('Administración de Empresas'),
('Carrera Universitaria Educación Inicial'), ('Ciencia Política y Gobernabilidad');

-- Autores y Materias de ejemplo
INSERT INTO autor (autor, imagen) VALUES ('Autor Principal', 'logo.png');
INSERT INTO editorial (editorial) VALUES ('Editorial Universitaria');
INSERT INTO materia (materia) VALUES ('Base de Datos'), ('Programación'), ('Matemáticas');
