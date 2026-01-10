<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="description"
        content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">
    <!-- Twitter meta-->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:site" content="@pratikborsadiya">
    <meta property="twitter:creator" content="@pratikborsadiya">
    <!-- Open Graph Meta-->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Vali Admin">
    <meta property="og:title" content="Vali - Free Bootstrap 4 admin theme">
    <meta property="og:url" content="http://pratikborsadiya.in/blog/vali-admin">
    <meta property="og:image" content="http://pratikborsadiya.in/blog/vali-admin/hero-social.png">
    <meta property="og:description"
        content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">
    <title>Panel Administrativo</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link href="<?php echo base_url; ?>Assets/css/main.css" rel="stylesheet" />
    <link href="<?php echo base_url; ?>Assets/css/datatables.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="<?php echo base_url; ?>Assets/css/select2.min.css" rel="stylesheet" />
    <link href="<?php echo base_url; ?>Assets/css/estilos.css" rel="stylesheet" />
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>Assets/css/font-awesome.min.css">
</head>

<body class="app sidebar-mini">
    <!-- Navbar-->
    <header class="app-header"><a class="app-header__logo"
            href="<?php echo base_url; ?>Configuracion/admin"><b>Unamba</b></a>
        <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar"
            aria-label="Hide Sidebar"></a>
        <!-- Navbar Right Menu-->
        <ul class="app-nav">
            <!--Notification Menu-->
            <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown"
                    aria-label="Show notifications"><i class="fa fa-bell-o fa-lg"></i></a>
                <ul class="app-notification dropdown-menu dropdown-menu-right">
                    <li class="app-notification__title">Libros no devueltos.</li>
                    <div class="app-notification__content">
                        <li id="nombre_estudiante">

                        </li>
                    </div>
                    <li class="app-notification__footer"><a href="<?php echo base_url; ?>Configuracion/libros"
                            target="_blank">Generar Reporte.</a></li>
                </ul>
            </li>
            <!-- User Menu-->
            <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown"
                    aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
                <ul class="dropdown-menu settings-menu dropdown-menu-right">
                    <li><a class="dropdown-item" href="#" id="modalPass"><i class="fa fa-user fa-lg"></i> Perfil</a>
                    </li>
                    <li><a class="dropdown-item" href="<?php echo base_url; ?>Usuarios/salir"><i
                                class="fa fa-sign-out fa-lg"></i> Salir</a></li>
                </ul>
            </li>
        </ul>
    </header>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
        <div class="app-sidebar__user"><img class="app-sidebar__user-avatar"
                src="<?php echo base_url; ?>Assets/img/logos.png" alt="User Image" width="50">
            <div>
                <p class="app-sidebar__user-name"><?php echo $_SESSION['nombre'] ?></p>
                <p class="app-sidebar__user-designation"><?php echo $_SESSION['usuario']; ?></p>
            </div>
        </div>
        <ul class="app-menu">
            <li><a class="app-menu__item" href="<?php echo base_url; ?>Prestamos"><i
                        class="app-menu__icon fa fa-hourglass-start"></i><span
                        class="app-menu__label">Prestamos</span></a></li>
            <li><a class="app-menu__item" href="<?php echo base_url; ?>Estudiantes"><i
                        class="app-menu__icon fa fa-graduation-cap"></i><span
                        class="app-menu__label">Estudiantes</span></a></li>
            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                        class="app-menu__icon fa fa-list"></i><span class="app-menu__label">Libros</span><i
                        class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="<?php echo base_url; ?>Autor"><i class="icon fa fa-address-book-o"></i>
                            Autor</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url; ?>Editorial"><i class="icon fa fa-tags"></i>
                            Editorial</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url; ?>Materia"><i class="icon fa fa-tags"></i>
                            Materia</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url; ?>Libros"><i class="icon fa fa-book"></i>
                            Libros</a></li>
                </ul>
            </li>
            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                        class="app-menu__icon fa fa-wrench"></i><span class="app-menu__label">Administración</span><i
                        class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="<?php echo base_url; ?>Usuarios"><i
                                class="icon fa fa-user-o"></i> Usuarios</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url; ?>Configuracion"><i
                                class="icon fa fa-cogs"></i> Configuración</a></li>
                </ul>
            </li>
            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                        class="app-menu__icon fa fa-file-text"></i><span class="app-menu__label">Reportes</span><i
                        class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li>
                        <a class="treeview-item" target="_blank" href="<?php echo base_url; ?>Prestamos/pdf">
                            <i class="icon fa fa-file-pdf-o"></i> Libros Prestados
                        </a>
                        <a class="treeview-item" target="_blank" href="<?php echo base_url; ?>Prestamos/LibrosMasPrestado">
                            <i class="icon fa fa-file-pdf-o"></i> Conteo de libros más prestados
                        </a>
                        <a class="treeview-item" target="_blank" href="<?php echo base_url; ?>Prestamos/LibrosStockCritico">
                            <i class="icon fa fa-file-pdf-o"></i> Libros con stock criticos
                        </a>
                        <a class="treeview-item" href="#" onclick="formReporte()">
                            <i class="fa fa-external-link"></i> Libros consultados por periodo
                        </a>
                        <a class="treeview-item" target="_blank" href="<?php echo base_url; ?>Prestamos/EstudianteMayorDemanda">
                            <i class="icon fa fa-file-pdf-o"></i> Estudiantes con mayor demanda de préstamos
                        </a>
                        <a class="treeview-item" target="_blank" href="<?php echo base_url; ?>Prestamos/MateriaMayorDemanda">
                            <i class="icon fa fa-file-pdf-o"></i> Materia con mayor demanda de préstamos
                        </a>
                        <a class="treeview-item" target="_blank" href="<?php echo base_url; ?>Prestamos/CarreraMayorDemanda">
                            <i class="icon fa fa-file-pdf-o"></i> Carrera con mayor demanda de préstamos
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
<main class="app-content">

<!-- Modal -->
<div id="reporteModal" class="modal fade" role="dialog" aria-labelledby="reporteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="reporteModalLabel">Seleccionar Fechas para el Reporte</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formReporte" onsubmit="reportePeriodo(event)">
          <div class="form-group">
            <label for="fechaInicio">Fecha de Inicio</label>
            <input type="date" class="form-control" id="fechaInicio" name="FechaInicio" required>
          </div>
          <div class="form-group">
            <label for="fechaFin">Fecha de Fin</label>
            <input type="date" class="form-control" id="fechaFin" name="FechaFin" required>
          </div>
          <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-check"></i> Generar Reporte
            </button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">
              <i class="fa fa-arrow-left"></i> Atrás
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

