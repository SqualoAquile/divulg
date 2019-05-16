<!doctype html>
<html lang="pt-BR" class="h-100">
  <head>
      
    <title>Entrar - <?php echo NOME_EMPRESA;?></title>
    <base href="<?php echo BASE_URL?>">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="manifest" href="<?php echo BASE_URL?>/manifest.json">

    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo BASE_URL?>/assets/images/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo BASE_URL?>/assets/images/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo BASE_URL?>/assets/images/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo BASE_URL?>/assets/images/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo BASE_URL?>/assets/images/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo BASE_URL?>/assets/images/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo BASE_URL?>/assets/images/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo BASE_URL?>/assets/images/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URL?>/assets/images/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo BASE_URL?>/assets/images/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo BASE_URL?>/assets/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo BASE_URL?>/assets/images/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo BASE_URL?>/assets/images/icons/favicon-16x16.png">
    
    <link rel="shortcut icon" href="<?php echo BASE_URL?>/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo BASE_URL?>/favicon.ico" type="image/x-icon">

    <meta name="msapplication-TileColor" content="#212936">
    <meta name="msapplication-TileImage" content="<?php echo BASE_URL?>/assets/images/icons/ms-icon-144x144.png">
    <meta name="theme-color" content="#212936">

    <link href="<?php echo BASE_URL;?>/assets/css/login.css" rel="stylesheet" type="text/css"/>

    <script src="<?php echo BASE_URL;?>/assets/js/vendor/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL;?>/assets/js/vendor/popper.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL;?>/assets/js/vendor/bootstrap.min.js" type="text/javascript"></script>
  </head>

  <body class="text-center h-100 d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mx-auto">
                <form method="POST" class="form-signin">
                    <?php if(!empty($aviso)):?>
                        <div class="alert alert-danger alert-dismissible mb-5" role="alert">
                            <?php echo $aviso ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif;?>
                    <!-- <h1><?php //echo NOME_EMPRESA;?></h1> -->
                    <img class="card-img-left img-fluid" src="<?php echo BASE_URL?>/assets/images/IDFX.png" width = "auto" height = "auto">
                    <p class="lead mb-4">Entre, por favor</p>
                    <label for="inputEmail" class="sr-only">Email</label>
                    <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Email" required autofocus>
                    <label for="inputPassword" class="sr-only">Senha</label>
                    <input type="password" id="inputPassword" class="form-control" name="senha" placeholder="Senha" required>
                    <button class="btn btn-lg btn-primary btn-block mt-3" type="submit">Entrar</button>
                    <p class="mt-5 mb-3 text-muted">&copy; <strong>Leme</strong> 2019</p>
                </form>
            </div>
        </div>
    </div>
  </body>
</html>
