<script type="text/javascript">
    var baselink = '<?php echo BASE_URL;?>',
        currentModule = '<?php echo str_replace(array("-add", "-edt"), "", basename(__FILE__, ".php")) ?>',
        campoPesquisa = '', // aqui vai o campo de id-usuario caso seja necessário filtrar o datatable somente para os registros referentes ao usuário logado
        valorPesquisa = '<?php echo in_array('podetudo_ver', $_SESSION['permissoesUsuario']) ? "" : $_SESSION["idUsuario"]; ?>';
</script>
<?php if (isset($_SESSION["returnMessage"])): ?>

  <div class="alert <?php echo $_SESSION["returnMessage"]["class"] ?> alert-dismissible">

    <?php echo $_SESSION["returnMessage"]["mensagem"] ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>

  </div>

<?php endif?>   

   
<?php if( in_array("clientes_ver", $infoUser["permissoesUsuario"]) ): ?>

<!-- <script src="<?php echo BASE_URL?>/assets/js/home.js" type="text/javascript"></script> -->
<h2 class="display-4 font-weight-bold py-4">Bom dia, <?php echo ucfirst($_SESSION['nomeUsuario'])?> !</h2>
<div class="row my-2">
    <div class="col-lg">
      <img src="<?php echo BASE_URL?>/assets/images/motivacional.jpeg" class="img-fluid w-100" style="height: auto; max-height: 600px; object-fit: fill;">
    </div>
    <?php if ( !empty($infoParametros['aviso_home_func']) ):?>
        <div class="col-lg-3">
          <div class="alert alert-danger text-center" role="alert">
            <?php echo ucfirst( $infoParametros['aviso_home_func'] ); ?>
          </div>
        </div>
    <?php endif;?>
    
</div>  
<div class="row my-2">
  
</div>
<div class="row my-2">
    <div class="col-lg">
    <div class="alert alert-info text-center" role="alert">Informações da Empresa! </div>
    </div>
</div>  


    
<?php else:?>
    <h1 class="display-4 font-weight-bold py-4">Home</h1>
<?php endif ?>
