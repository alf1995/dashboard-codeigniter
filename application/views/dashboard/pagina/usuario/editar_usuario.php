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
              <li class="breadcrumb-item active"><a href="#">Editar</a></li>
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
                            <h3 class="card-title">EDITAR USUARIO</h3>
                        </div>
                        <div class="card-body">
                            <form class="form-horizontal formulario" method="POST" action="<?php echo base_url('dashboard/usuario/proceso/editar') ?>"  accept-charset="utf-8" enctype="multipart/form-data">
                                <span class="respuesta"></span>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="col-form-label"for="simpleinput">Usuario:</label>
                                                <input type="text" class="form-control" value="<?php echo $prefil_usuario ?>" name="usuario" maxlength="80" autocomplete="off" placeholder="email@gmail.com">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="col-form-label" for="simpleinput">Nombre:</label>
                                                <input type="text" class="form-control" value="<?php echo $prefil_nombre ?>" name="nombre">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="col-form-label" for="simpleinput">Apellido:</label>
                                                <input type="text" class="form-control"  value="<?php echo $prefil_apellido ?>" name="apellido">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="col-form-label">Contraseña:</label>
                                                <input type="password" class="form-control" value="" name="clave">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="col-form-label">Verificar Contraseña:</label>
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
                                                <span class="help-block"><small>Cargar imágen con una dimension equivalente a <b>1000*900</b> px como máximo. Tambien tener en cuenta que tiene que tener un peso Máximo de <b>1 MB</b>.</small></span>
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