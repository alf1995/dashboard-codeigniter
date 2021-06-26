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
              <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard/modulo/listar') ?>" style="color: #324768;">Módulo</a></li>
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
                            <h3 class="card-title">AGREGAR MÓDULO</h3>
                        </div>
                        <div class="card-body"> 
                            <form class="form-horizontal formulario" method="POST" action="<?php echo base_url('dashboard/modulo/proceso/agregar') ?>"  accept-charset="utf-8" enctype="multipart/form-data">
                                <span class="respuesta"></span>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label"
                                                for="simpleinput">Nombre</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" value="" name="nombre" maxlength="60" autocomplete="off">
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="col-lg-12">
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