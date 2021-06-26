<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url('dashboard/usuario/perfil') ?>" role="button"><b>Editar Perfil</b></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo base_url('dashboard/login/acceso/logout') ?>" class="nav-link">
          <i class="fas fa-sign-out-alt"></i><b>Cerrar sesión</b>
        </a>
      </li>
    </ul>
  </nav>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="javascript:void(0);" class="brand-link">
    <img src="<?php echo base_url('public/panel/img/AdminLTELogo.png') ?>" alt="Panel control" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">DASHBOARD</span>
  </a>
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?php echo $accesoTmpImagen ?>" class="img-circle elevation-2" alt="<?php echo $accesoTmpNombre ?>">
      </div>
      <div class="info">
        <a href="javascript:void(0);" class="d-block"><?php echo $accesoTmpNombre ?></a>
      </div>
    </div>
    <nav class="mt-2">
       <?php echo $menuGenerado ?>
    </nav>
   
  </div>
</aside>