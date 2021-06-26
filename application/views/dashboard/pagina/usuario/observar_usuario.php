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
              <li class="breadcrumb-item active"><a href="#">Observar</a></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mt-3">
                                <img src="<?php echo $observar_imagen ?>" alt="<?php echo $observar_nombre ?>" class="avatar-lg rounded-circle">
                                <h5 class="mt-2 mb-0"><?php echo $observar_nivel ?></h5>
                                <p></p>
                                <a href="<?php echo $btn_editar ?>" type="button" class="btn btn-primary btn-sm mr-2">Editar</a>
                            </div>
                            <div class="mt-3 pt-2 border-top">
                                <h4 class="mb-3 font-size-15">Información:</h4>
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0 text-muted">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Usuario</th>
                                                <td><b><?php echo $observar_usuario ?></b></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Nombre</th>
                                                <td><?php echo $observar_nombre ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Apellido</th>
                                                <td><?php echo $observar_apellido ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mt-3 pt-2 border-top">
                                <h4 class="mb-3 font-size-15">Datos registro:</h4>
                                 <div class="table-responsive">
                                    <table class="table table-borderless mb-0 text-muted">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Fecha registro</th>
                                                <td><?php echo $fechaRegistro ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Fecha modificación</th>
                                                <td><?php echo $fechaModificacion ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div> 