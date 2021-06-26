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
              <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard/permisos/listar') ?>" style="color: #324768;">Permisos</a></li>
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
                            <h3 class="card-title">AGREGAR PERMISOS</h3>
                        </div>
                            <div class="card-body"> 
                            <form class="form-horizontal formulario" method="POST" action="<?php echo base_url('dashboard/permisos/proceso/agregar') ?>"  accept-charset="utf-8" enctype="multipart/form-data">
                                <span class="respuesta"></span>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php if(isset($listadoUsuarios)){ ?>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Usuarios</label>
                                            <div class="col-lg-10">
                                                <?php echo $listadoUsuarios ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if(isset($listadoModulos)){ ?>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Módulos</label>
                                            <div class="col-lg-10">
                                                <?php echo $listadoModulos ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-lg-12">
                                        <?php if(isset($listadoAcciones)){ ?>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Acciones</label>
                                            <div class="col-lg-10">
                                                <?php echo $listadoAcciones ?>
                                            </div>
                                        </div>
                                        <?php } ?>
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
<style>
    .custom-control{
        display: inline-block;
    }
</style>
</div>