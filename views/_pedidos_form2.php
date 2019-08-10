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
    <h3 class="mt-5 mb-4 font-weight-bold">* Operação do Dia</h3>
    <div class="table-responsive mb-lg-5 mb-3" >
        <table id="operacao" class="table" style="max-height: 500px; overflow-y: auto; overflow-x:auto; width: 100%;">
            <tbody >
                <!-- SOBRA D-1  -->
                <tr role="row" class="d-flex flex-column flex-lg-row text-center border-2" id='sobrad1'>
                    <td class="col-lg-2 d-flex bg-light">
                        <label for="" class="col font-weight-bold">Sobra (D-1)</label>
                    </td>
                    <?php for($j = 0; $j < count($produtos); $j++):?>
                        <td class="col-lg-2 d-flex bg-light">
                            <label for="" class="col font-weight-bold"><?php echo $produtos[$j]['codigo']; ?></label>
                            <input  type="text" 
                                    class="form-control col" 
                                    id="sobrad1" 
                                    name="sobrad1['<?php echo $produtos[$j]['codigo']; ?>']" data-mascara_validacao="numero"
                                    data-podeZero="true" 
                                    maxlength="2" 
                                    data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                    value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : intval(0) ?>" 
                                    required
                                    readonly
                            >
                        </td>
                    <?php endfor;?> 

                </tr>
                <!-- PEDIDO  -->
                <tr role="row" class="d-flex flex-column flex-lg-row text-center border-2" id='pedido'>
                    <td class="col-lg-2 d-flex bg-white">
                        <label for="" class="col font-weight-bold">Pedido</label>
                       
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
                 <!-- ENTREGA    -->
                 <tr role="row" class="d-flex flex-column flex-lg-row text-center border-2" id='entrega'>
                    <td class="col-lg-2 d-flex bg-light">
                        <label for="" class="col font-weight-bold">Entrega</label>
                    </td>
                    <?php for($j = 0; $j < count($produtos); $j++):?>
                        <td class="col-lg-2 d-flex bg-light">
                            <label for="" class="col font-weight-bold"><?php echo $produtos[$j]['codigo']; ?></label>
                            <input  type="text" 
                                    class="form-control col" 
                                    id="entrega" 
                                    name="entrega['<?php echo $produtos[$j]['codigo']; ?>']" data-mascara_validacao="numero"
                                    data-podeZero="true" 
                                    maxlength="2" 
                                    data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                    value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : intval(0) ?>" 
                                    required
                            >
                        </td>
                    <?php endfor;?> 

                </tr>
                <!-- VENDA -->
                <tr role="row" class="d-flex flex-column flex-lg-row text-center" id='venda'>
                    <td class="col-lg-2 d-flex bg-white">
                        <label for="" class="col font-weight-bold">Venda</label>
                        
                    </td>
                    <?php for($j = 0; $j < count($produtos); $j++):?>
                        <td class="col-lg-2 d-flex bg-white">
                            <label for="" class="col font-weight-bold"> <?php echo $produtos[$j]['codigo']; ?></label>
                            <input  type="text" 
                                class="form-control col" 
                                id="venda" 
                                name="venda['<?php echo $produtos[$j]['codigo']; ?>']" data-mascara_validacao="numero"
                                data-podeZero="true" 
                                maxlength="2" 
                                data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : intval(0) ?>"
                                required
                                readonly
                            >
                        </td>
                    <?php endfor;?>            
                </tr>
                 <!-- SOBRA D-0    -->
                <tr role="row" class="d-flex flex-column flex-lg-row text-center" id='sobrad0'>
                    <td class="col-lg-2 d-flex bg-light">
                        <label for="" class="col font-weight-bold">Sobra (D-0)</label>
                        
                    </td>
                    <?php for($j = 0; $j < count($produtos); $j++):?>
                        <td class="col-lg-2 d-flex bg-light">
                            <label for="" class="col font-weight-bold"> <?php echo $produtos[$j]['codigo']; ?></label>
                            <input  type="text" 
                                class="form-control col" 
                                id="sobrad0" 
                                name="sobrad0['<?php echo $produtos[$j]['codigo']; ?>']" data-mascara_validacao="numero"
                                data-podeZero="true" 
                                maxlength="2" 
                                data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : intval(0) ?>"
                                required
                            >
                        </td>
                    <?php endfor;?>            
                </tr>
               <!-- SOBRA D-2 -->
                <tr role="row" class="d-flex flex-column flex-lg-row text-center" id='sobrad2'>
                    <td class="col-lg-2 d-flex bg-white">
                        <label for="" class="col font-weight-bold">Sobra (D-2)</label>
                        
                    </td>
                    <?php for($j = 0; $j < count($produtos); $j++):?>
                        <td class="col-lg-2 d-flex bg-white">
                            <label for="" class="col font-weight-bold"> <?php echo $produtos[$j]['codigo']; ?></label>
                            <input  type="text" 
                                class="form-control col" 
                                id="sobrad2" 
                                name="sobrad2['<?php echo $produtos[$j]['codigo']; ?>']" data-mascara_validacao="numero"
                                data-podeZero="true" 
                                maxlength="2" 
                                data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : intval(0) ?>"
                                required
                            >
                        </td>
                    <?php endfor;?>            
                </tr>
                <!-- DOAÇÃO / CANCELAMENTO -->
                <tr role="row" class="d-flex flex-column flex-lg-row text-center" id='doacao'>
                    <td class="col-lg-2 d-flex bg-light">
                        <label for="" class="col font-weight-bold">Doação / Estorno</label>
                        
                    </td>
                    <?php for($j = 0; $j < count($produtos); $j++):?>
                        <td class="col-lg-2 d-flex bg-light">
                            <label for="" class="col font-weight-bold"> <?php echo $produtos[$j]['codigo']; ?></label>
                            <input  type="text" 
                                class="form-control col" 
                                id="doacao" 
                                name="doacao['<?php echo $produtos[$j]['codigo']; ?>']" data-mascara_validacao="numero"
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