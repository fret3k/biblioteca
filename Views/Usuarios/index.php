<?php include "Views/Templates/header.php"; ?>
<div class="app-title">
    <div>
        <h1><i class="fa fa-dashboard"></i> Usuarios</h1>
    </div>
</div>
<button class="btn btn-primary mb-2" type="button" onclick="frmUsuario();"><i class="fa fa-plus"></i> Agregar
    usuario</button>
<div class="row">
    <div class="col-lg-12">
        <div class="tile">
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tblUsuarios">
                        <thead class="thead-dark">
                            <tr>
                                <th>Id</th>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="nuevo_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="title">Nuevo Usuario</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frmUsuario" onsubmit="registrarUser(event)">
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input type="hidden" id="id" name="id">
                        <input id="usuario" class="form-control" type="text" name="usuario" 
                            placeholder="Usuario">
                        <span id="usuario-error" class="text-danger text-danger-tamaño"></span>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input id="nombre_usuario" class="form-control" type="text" name="nombre"
                            placeholder="Nombre del usuario">
                        <span id="nombre_usuario-error" class="text-danger text-danger-tamaño"></span>
                    </div>
                    <div class="row" id="claves">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clave">Contraseña</label>
                                <input id="clave" class="form-control" type="password" name="clave"
                                    placeholder="Contraseña">
                                <span id="clave-error" class="text-danger text-danger-tamaño"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirmar">Confirmar Contraseña</label>
                                <input id="confirmar" class="form-control" type="password" name="confirmar"
                                    placeholder="Confirmar contraseña">
                                <span id="confirmar-error" class="text-danger text-danger-tamaño"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button class="btn btn-primary" type="submit" id="btnAccion">
                                <i class="fa fa-check"></i>
                                Registrar
                            </button>
                            <button class="btn btn-danger" type="button" data-dismiss="modal">
                                <i class="fa fa-arrow-left"></i>
                                Atras
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="permisos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Asignar Permisos</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frmPermisos">
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "Views/Templates/footer.php"; ?>