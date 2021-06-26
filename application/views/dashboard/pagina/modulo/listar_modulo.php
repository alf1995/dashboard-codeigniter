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
              <li class="breadcrumb-item">Módulo</li>
              <li class="breadcrumb-item active"><a href="#">Listar</a></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="col-sm-12 col-xl-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">LISTADO DE MÓDULOS</h3>
                                </div>
                                <div class="card-body">
                                    <div class="dt-buttons btn-group" style="margin-bottom: 1%;"> 
                                        <a href="<?php echo base_url('dashboard/modulo/agregar') ?>" class="btn btn-primary buttons-copy buttons-html5" tabindex="0" type="button"><span>AGREGAR MÓDULO</span></a> 
                                    </div>     
                                    <span class="respuesta"></span>
                                    <?php if(isset($generaTabla)){  ?>
                                        <div class="table-responsive">
                                            <?php echo $generaTabla ?>
                                        </div>
                                    <?php } ?>
                                    <?php if(isset($generaPaginacion)){  ?>
                                        <div class="float-right ">
                                            <?php echo $generaPaginacion ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
        </div>
    </section>
</div>
