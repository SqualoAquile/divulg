<?php $modulo = 'producao' ?>
<script type="text/javascript">
    var baselink = '<?php echo BASE_URL;?>',
        currentModule = '<?php echo $modulo ?>',
        campoPesquisa = 'id_usuario',
        valorPesquisa = '<?php echo in_array('podetudo_ver', $_SESSION['permissoesUsuario']) ? "" : $_SESSION["idUsuario"]; ?>';
</script>

<style>
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
    }
    /* IE 6 doesn't support max-height
    * we use height instead, but this forces the menu to always be this tall
    */
    * html .ui-autocomplete {
        height: 200px;
  }</style>

<script src="<?php echo BASE_URL?>/assets/js/vendor/jquery-ui.min.js" type="text/javascript"></script>
<!-- Chama o arquivo específico do módulo, caso não exista,  -->
<!-- Este javaScript serve para fazer verificações inerentes à cada módulo, por exemplo o radio de Clientes -->
<script src="<?php echo BASE_URL?>/assets/js/producao.js" type="text/javascript"></script>

<header class="d-lg-flex align-items-center my-5">
    <?php if(in_array($modulo . "_ver", $infoUser["permissoesUsuario"])): ?>
        <a href="<?php echo BASE_URL . '/home'?>" class="btn btn-secondary mr-lg-4" title="Voltar">
            <i class="fas fa-chevron-left"></i>
        </a>
    <?php endif ?>
    <h1 class="display-4 m-0 text-capitalize font-weight-bold"><?php echo $viewInfo["title"]; ?></h1>
</header>

<section class="mb-5">
    <div class="row">
        <div class="col-lg-4 mb-3">
        <label class="font-weight-bold" for="opcoes"> Dias da Semana </label>    
        <select id="opcoes" name="opcoes" class="form-control" >
                <option value="" selected >Todos os Dias</option>  
        </select>
        </div>
    </div> 
    

    <?php if( !empty( $producao_semana['operacao'] ) ):?>
    <div class="table-responsive mb-lg-5 mb-3" >
        <table id="prod_semana" class="table" style="max-height: 500px; overflow-y: auto; overflow-x:auto; width: 100%;">
            <thead>
                <tr role="row" class="d-flex flex-column flex-lg-row text-center border-2">
                    <th class="col text-center">
                        <label class="text-center" > Sabores </label><br>
                    </th>
                    <?php for($j = 1; $j <= count( $producao_semana['semana'] ); $j++):?>
                        <th class="col text-center">
                            <label class="text-center" > <?php echo $producao_semana['semana'][$j]['diasem']; ?> </label><br>
                            <label class="text-center" > <?php $aux = explode('-', $producao_semana['semana'][$j]['data']); echo $aux[2].'/'.$aux[1].'/'.$aux[0]; ?> </label><br>
                            <label class="text-center" > Pedido  |  Produzido </label>
                        </th>    
                    <?php endfor;?> 
                </tr>    
            </thead>
            <tbody >
                <?php foreach ($producao_semana['operacao'] as $chaveOp => $valorOp): ?>
                    
                    <tr role="row" class="d-flex flex-column flex-lg-row text-center border-2">
                        <td class="col d-flex">
                            <label class="col font-weight-bold"><?php echo $chaveOp; ?></label>
                        </td> 
                        <?php foreach ($producao_semana['operacao'][$chaveOp] as $chaveSab => $valorSab) :?>
                            <td class="col d-flex">
                                <label class="col font-weight-bold">
                                    <?php echo $valorSab['pedtotal'].'  |  '.$valorSab['enttotal']; ?>
                                </label>
                            </td>         
                        <?php endforeach;?>                                                           
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>  
    <?php else:?>
        <div class="alert alert-danger position-fixed my-toast m-3 shadow-sm alert-dismissible" role="alert"> 
            <label > Não foram encontradas informações de pedidos nessa semana.</label>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif;?>
</section>