<script src="<?php echo BASE_URL;?>/assets/js/pedidos_form.js" type="text/javascript"></script>
<style>
    @media screen and (max-width: 793px) {
        #mesclar {
            display: none;
        }
        td.bg-light{
            background-color: #b7b7b7 !important;
        }
    }
</style>
<!-- <form id="contatos-form" autocomplete="off" novalidate> -->
    <h3 class="mt-5 mb-4 font-weight-bold">* Pedido</h3>
    <div class="table-responsive mb-lg-5 mb-3" >
        <table id="pedidos_fixos" class="table" style="max-height: 500px; overflow-y: auto; overflow-x:auto; width: 100%;">
            <thead>
                <tr role="form" class="d-flex flex-column flex-lg-row text-center">
                    
                    <th class="col-lg-4">
                        <label class="text-center" for=""></label>
                    </th>
                    <th class="col-lg-2" colspan="<?php echo count($produtos);?>" >
                        <label class="text-center" for="">Quantidades</label>
                    </th>
                    <?php for($j = 1; $j < count($produtos); $j++):?>
                        <th class="col-lg-2" id="mesclar">
                        </th>
                    <?php endfor;?>  
                </tr>
            </thead>
            <tbody >
                <!-- SOBRA DIA ANTERIOR  -->
                <tr role="row" class="d-flex flex-column flex-lg-row text-center border-2">
                    <td class="col-lg-4 d-flex bg-white">
                        <label for="" class="col font-weight-bold">Sobra Dia Anterior :</label>
                        <input  type="text" 
                                class="form-control col" 
                                id="dt_pedido" 
                                name="dt_pedido"
                                value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : '16/07/2019' ?>" 
                                data-mascara_validacao="false" 
                                maxlength="10" 
                                required
                        >
                    </td>
                    <?php for($j = 0; $j < count($produtos); $j++):?>
                        <td class="col-lg-2 d-flex bg-white">
                            <label for="" class="col font-weight-bold"><?php echo $produtos[$j]['codigo']; ?></label>
                            <input  type="text" 
                                    class="form-control col" 
                                    id="pedido" 
                                    name="pedido['<?php echo $produtos[$j]['codigo']; ?>']" data-mascara_validacao="numero"
                                    data-podeZero="true" 
                                    maxlength="2" 
                                    data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                    value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : intval(0) ?>" 
                                    required
                            >
                        </td>
                    <?php endfor;?> 

                </tr><!-- PEDIDO  -->
                <tr role="row" class="d-flex flex-column flex-lg-row text-center border-2">
                    <td class="col-lg-4 d-flex bg-white">
                        <label for="" class="col font-weight-bold">Pedido :</label>
                        <input  type="text" 
                                class="form-control col" 
                                id="dt_pedido" 
                                name="dt_pedido"
                                value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : '16/07/2019' ?>" 
                                data-mascara_validacao="false" 
                                maxlength="10" 
                                required
                        >
                    </td>
                    <?php for($j = 0; $j < count($produtos); $j++):?>
                        <td class="col-lg-2 d-flex bg-white">
                            <label for="" class="col font-weight-bold"><?php echo $produtos[$j]['codigo']; ?></label>
                            <input  type="text" 
                                    class="form-control col" 
                                    id="pedido" 
                                    name="pedido['<?php echo $produtos[$j]['codigo']; ?>']" data-mascara_validacao="numero"
                                    data-podeZero="true" 
                                    maxlength="2" 
                                    data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                    value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : intval(0) ?>" 
                                    required
                            >
                        </td>
                    <?php endfor;?>            
                </tr>
                <!-- RECEBIMENTO -->
                <tr role="row" class="d-flex flex-column flex-lg-row text-center">
                    <td class="col-lg-4 d-flex bg-light">
                        <label for="" class="col font-weight-bold">Recebimento :</label>
                        <input  type="text" 
                                class="form-control col" 
                                id="rota" 
                                name="pedido['terca']['rota']"
                                value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : 'Sem Rota' ?>" 
                                data-mascara_validacao="false" 
                                maxlength="40" 
                                required
                        >
                    </td>
                    <?php for($j = 0; $j < count($produtos); $j++):?>
                        <td class="col-lg-2 d-flex bg-light">
                            <label for="" class="col font-weight-bold"> <?php echo $produtos[$j]['codigo']; ?></label>
                            <input  type="text" 
                                class="form-control col" 
                                id="pedido" 
                                name="pedido['terca']['<?php echo $produtos[$j]['codigo']; ?>']" data-mascara_validacao="numero"
                                data-podeZero="true" 
                                maxlength="2" 
                                data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : intval(0) ?>"
                                required
                            >
                        </td>
                    <?php endfor;?>            
                </tr>
                 <!-- VENDA    -->
                <tr role="row" class="d-flex flex-column flex-lg-row text-center">
                    <td class="col-lg-4 d-flex bg-white">
                        <label for="" class="col font-weight-bold">Venda :</label>
                        <input  type="text" 
                                class="form-control col" 
                                id="rota" 
                                name="pedido['quarta']['rota']"
                                value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : 'Sem Rota' ?>" 
                                data-mascara_validacao="false" 
                                maxlength="40" 
                                required
                        >
                    </td>
                    <?php for($j = 0; $j < count($produtos); $j++):?>
                        <td class="col-lg-2 d-flex bg-white">
                            <label for="" class="col font-weight-bold"> <?php echo $produtos[$j]['codigo']; ?></label>
                            <input  type="text" 
                                class="form-control col" 
                                id="pedido" 
                                name="pedido['quarta']['<?php echo $produtos[$j]['codigo']; ?>']" data-mascara_validacao="numero"
                                data-podeZero="true" 
                                maxlength="2" 
                                data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : intval(0) ?>"
                                required
                            >
                        </td>
                    <?php endfor;?>            
                </tr>
               <!-- SOBRA -->
                <tr role="row" class="d-flex flex-column flex-lg-row text-center">
                    <td class="col-lg-4 d-flex bg-light">
                        <label for="" class="col font-weight-bold">Sobra :</label>
                        <input  type="text" 
                                class="form-control col" 
                                id="rota" 
                                name="pedido['quinta']['rota']"
                                value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : 'Sem Rota' ?>" 
                                data-mascara_validacao="false" 
                                maxlength="40" 
                                required
                        >
                    </td>
                    <?php for($j = 0; $j < count($produtos); $j++):?>
                        <td class="col-lg-2 d-flex bg-light">
                            <label for="" class="col font-weight-bold"> <?php echo $produtos[$j]['codigo']; ?></label>
                            <input  type="text" 
                                class="form-control col" 
                                id="pedido" 
                                name="pedido['quinta']['<?php echo $produtos[$j]['codigo']; ?>']" data-mascara_validacao="numero"
                                data-podeZero="true" 
                                maxlength="2" 
                                data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : intval(0) ?>"
                                required
                            >
                        </td>
                    <?php endfor;?>            
                </tr>
                       
            </tbody>
        </table>
    </div>
<!-- </form> -->