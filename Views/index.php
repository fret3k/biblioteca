<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>Assets/css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>Assets/css/font-awesome.min.css">
    <title>Iniciar | Sesión</title>
</head>

<body>
    <section class="material-half-bg">
        <div class="cover"></div>
    </section>
    <section class="login-content">
        <div class="logo" >
        <h1>Biblioteca Unamba <img src="Assets/img/logos.png"  alt="User Image" width="100"></h1>
            <a href="http://localhost:8080/biblioteca/Views/Catalogo/index.php" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
                    <path d="M1 2.828c.885-.37 2.154-.829 3.5-.829 1.346 0 2.615.459 3.5.829V14.17c-.885-.37-2.154-.829-3.5-.829-1.346 0-2.615.459-3.5.829V2.828zM0 1.993C0 .892 1.79 0 4 0c2.21 0 4 .892 4 1.993v12.014C8 15.108 6.21 16 4 16c-2.21 0-4-.892-4-1.993V1.993zm9 0C9 .892 10.79 0 13 0s4 .892 4 1.993v12.014C17 15.108 15.21 16 13 16s-4-.892-4-1.993V1.993zm1 0v12.014c.885-.37 2.154-.829 3.5-.829s2.615.459 3.5.829V1.993c-.885-.37-2.154-.829-3.5-.829s-2.615.459-3.5.829z"/>
                </svg>
                Catálogo
            </a>
        </div>
        <div class="logo">
            <h2>Bienvenido</h2>
        </div>
        <div class="login-box">
            <form class="login-form" id="frmLogin" onsubmit="frmLogin(event);">
                <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>Iniciar Sesión</h3>
                <div class="form-group">
                    <label class="control-label">USUARIO</label>
                    <input class="form-control" type="text" placeholder="Usuario" id="usuario" name="usuario" autofocus required>
                </div>
                <div class="form-group">
                    <label class="control-label">CONTRASEÑA</label>
                    <input class="form-control" type="password" placeholder="Contraseña" id="clave" name="clave" required>
                </div>
                <div class="alert alert-danger d-none" role="alert" id="alerta">
                    
                </div>
                <div class="form-group btn-container">
                    <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-sign-in fa-lg fa-fw"></i>Login</button>
                </div>
            </form>
        </div>
    </section>
    <!-- Essential javascripts for application to work-->
    <script src="<?php echo base_url; ?>Assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo base_url; ?>Assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url; ?>Assets/js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="<?php echo base_url; ?>Assets/js/pace.min.js"></script>
    <script>
        const base_url = '<?php echo base_url; ?>';
    </script>
    <script src="<?php echo base_url; ?>Assets/js/login.js"></script>
    <script type="text/javascript">
        // Login Page Flipbox control
        $('.login-content [data-toggle="flip"]').click(function() {
            $('.login-box').toggleClass('flipped');
            return false;
        });
    </script>
</body>

</html>