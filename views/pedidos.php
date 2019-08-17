<?php
// Transforma o nome do arquivo para o nome do módulo
$modulo = str_replace("-form", "", basename(__FILE__, ".php"));
// Constroi o cabeçalho
require "_header_browser.php";
// Constroi a tabela
require "_table_datatable.php";
?>
<script type="text/javascript">
    var baselink = '<?php echo BASE_URL;?>',
        currentModule = '<?php echo $modulo ?>',  // usa o nome da tabela como nome do módulo, necessário para outras interações
        parametros = [],
        campoPesquisa = 'id_vendedor',
        valorPesquisa = '<?php echo in_array('podetudo_ver', $_SESSION['permissoesUsuario']) ? "" : $_SESSION["idUsuario"]; ?>';
        parametros = <?php echo json_encode($parametros); ?>;
</script>
<script src="<?php echo BASE_URL?>/assets/js/pedidosbronwser.js" type="text/javascript"></script>