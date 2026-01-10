<?php include "Views/Templates/header.php"; ?>
<div class="app-title">
    <div>
        <h1><i class="fa fa-dashboard"></i> Prestamos</h1>
    </div>
</div>
<button class="btn btn-primary mb-2" onclick="frmPrestar()"><i class="fa fa-plus"></i> Agregar Préstamo</button>
<div class="tile">
    <div class="tile-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped mt-4" id="tblPrestar">
                <thead class="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Libro</th>
                        <th>Código</th>
                        <th>Estudiante</th>
                        <th>Carrera</th>
                        <th>Fecha Prestamo</th>
                        <th>Fecha Devolución</th>
                        <th>Observación</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="prestar" class="modal fade" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="title">Prestar Libro</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frmPrestar" onsubmit="registroPrestamos(event)">
                    <div class="form-group">
                        <label id="cantidad" for="libro">Libro</label><br>
                        <select id="libro" class="form-control libro" name="libro" onchange="verificarLibro()" required
                            style="width: 100%;">
                        </select>
                        <strong id="msg_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for="estudiante">Estudiante</label><br>
                        <select name="estudiante" id="estudiante" class="form-control estudiante" required
                            style="width: 100%;">
                            <option value="">Seleccione un estudiante</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_prestamo">Fecha de Prestamo</label>
                                <input id="fecha_prestamo" name="fecha_prestamo" class="form-control" type="date"
                                    value="<?php echo date('Y-m-d'); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_devolucion">Fecha de Devolución</label>
                                <input id="fecha_devolucion" name="fecha_devolucion" class="form-control" type="date"
                                    value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observacion">Observación</label>
                                <textarea id="observacion" class="form-control" placeholder="Observación"
                                    name="observacion" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <button class="btn btn-primary" type="submit" id="btnAccion">
                                    <i class="fa fa-check"></i>
                                    Registrar</button>
                                <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="limpiarCamposPrestamo()">
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
<?php include "Views/Templates/footer.php"; ?>