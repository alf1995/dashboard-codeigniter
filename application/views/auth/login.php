<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $titulo_pagina ?></title>

    <link rel="canonical" href="">
    <link href="<?php echo base_url('public/assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('public/panel/css/admin.min.css') ?>" rel="stylesheet">
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <link href="<?php echo base_url('public/login/css/signin.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('public/assets/alertify/css/alertify.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('public/assets/alertify/css/themes/default.css') ?>" type="text/css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

  </head>
  <body>
    <div class="col d-flex justify-content-center">
      <div class="card" style="width: 18rem;">
        <form class="form-signin formulario" action="<?php echo base_url('dashboard/login/acceso/login') ?>" method="post">
          <span class="respuesta"></span>
          <h1 class="h3 mb-3 font-weight-normal text-center">PANEL</h1>
          <div class="form-group input-group-sm">
            <label for="inputEmail" class="sr-only">Usuario</label>
            <input type="email" id="inputEmail" class="form-control" placeholder="Usuario" name="usuario" required autofocus>
          </div>
          <div class="form-group input-group-sm">
            <label for="inputPassword" class="sr-only">Contraseña</label>
            <input type="password" id="inputPassword" class="form-control" placeholder="Contraseña" name="clave" required maxlength="30">
          </div>
          <button class="btn btn-sm btn-primary btn-block" type="submit">INGRESAR</button>
          <div class="mb-3 mt-1 text-center">
            <label>
              <a href="<?php echo base_url('dashboard/recuperar') ?>"> Olvide mi contraseña</a>
            </label>
          </div>
          <p class="mt-5 mb-3 text-muted text-center">&copy; <?php echo config_admin()['sisDate']; ?></p>
        </form>
      </div>
    </div>
  </body>

  <script src="<?php echo base_url('public/assets/jquery/jquery.min.js') ?>"></script>
  <script src="<?php echo base_url('public/assets/blockUI/jquery.blockUI.js') ?>"></script>
  <script src="<?php echo base_url('public/assets/alertify/alertify.js') ?>"></script>
  <script src="<?php echo base_url('public/login/js/login.min.js') ?>"></script>
</html>
