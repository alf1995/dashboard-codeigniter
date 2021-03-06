<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Dashboard</li>
              <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard/usuario/listar') ?>" style="color: #324768;">Usuario</a></li>
              <li class="breadcrumb-item active"><a href="#">Agregar</a></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">AGREGAR USUARIO</h3>
                        </div>
                        <div class="card-body">
                            <form class="form-horizontal formulario" method="POST" action="<?php echo base_url('dashboard/usuario/proceso/agregar') ?>"  accept-charset="utf-8" enctype="multipart/form-data">
                                <span class="respuesta"></span>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="col-form-label"for="simpleinput">Usuario:</label>
                                                <input type="text" class="form-control" value="" name="usuario" maxlength="80" autocomplete="off" placeholder="email@gmail.com">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="col-form-label" for="simpleinput">Nombre:</label>
                                                <input type="text" class="form-control" value="" name="nombre">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="col-form-label" for="simpleinput">Apellido:</label>
                                                <input type="text" class="form-control"  value="" name="apellido">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="col-form-label">Contrase??a:</label>
                                                <input type="password" class="form-control" value="" name="clave">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="col-form-label">Verificar Contrase??a:</label>
                                                <input type="password" class="form-control" value="" name="reclave">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="col-form-label" for="example-fileinput">Imagen de usuario:</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="customFile" name="imagenPrincipal">
                                                    <label class="custom-file-label" for="customFile">Buscar archivo</label>
                                                </div>
                                                <span class="help-block"><small>Cargar im??gen con una dimension equivalente a <b>1000*900</b> px como m??ximo. Tambien tener en cuenta que tiene que tener un peso M??ximo de <b>1 MB</b>.</small></span>
                                            </div>
                                        </div>  
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>