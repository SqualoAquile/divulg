<?php $modulo = str_replace("-form", "", basename(__FILE__, ".php")) ?>

<script src="<?php echo BASE_URL?>/assets/js/relatoriofluxocaixa.js" type="text/javascript"></script>

<?php
// Constroi o cabeçalho
require "_header_browser_relatorios.php";
require "_graficosNOVO.php";
?>

<div class="collapse mb-5 show" id="collapseMeta">
    <h5 class="text-center my-1 mb-3">Acompanhamento da Meta</h5>
            <div class="col-lg">
            <div class="row">

                <div class="col-lg">
                    <div class="card card-body h-100 text-dark text-center my-1 shadow justify-content-between ">
                        <p class="card-title">Faturamento atual do mês</p>
                        <p class="card-text h2"><?php echo $meta[1]?></p>
                    </div>
                </div>

                <div class="col-lg">
                    <div class="card card-body h-100 text-success text-center my-1 shadow justify-content-between">
                        <p class="card-title"> Meta Mensal</p>
                        <p class="card-text h2"><?php echo $meta[0]?></p>
                    </div>
                </div>

                <div class="col-lg">
                    <div class="card card-body h-100 text-danger text-center my-1 shadow justify-content-between">
                    <p class="card-title"> Diferença </p>
                    <p class="card-text h2"><?php echo $meta[3]?></p>
                    </div>
                </div>

                <div class="col-lg">
                    <div class="card card-body h-100 text-center my-1 shadow justify-content-between">
                        <p class="card-title"> Dias para o fim do mês </p>
                        <p class="card-text h2"><?php echo $meta[4]?></p>
                    </div>
                </div>

                <div class="col-lg">
                    <div class="card card-body h-100 text-dark text-center my-1 shadow justify-content-between">
                        <p class="card-title"> Faturamento médio para atingir a meta </p>
                        <p class="card-text h2"><?php echo $meta[5]?>/dia</h5>
                    </div>
                </div>
                
            </div>
    </div>
</div>

<div class="collapse mb-5" id="collapseFluxocaixaResumo">
    <div class="card card-body">
        <div class="row" id="somasResumo">
            <div class="col-lg">
                <div class="row d-none d-lg-flex">
                    <div class="col">
                        <h5 class="my-4 text-center">
                        Operações Realizadas <span class="badge badge-primary badge-pill" data-id="totalQ"></span>
                        </h5>
                    </div>
                    <div class="col">
                        <h5 class="my-4 text-center">
                        Operações a Realizar <span class="badge badge-primary badge-pill" data-id="totalAQ"></span>
                        </h5>
                    </div>
                    <div class="col">
                        <h5 class="my-4 text-center">
                        Previsão do Mês <span class="badge badge-primary badge-pill" data-id="total"></span>
                        </h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg">
                        <h5 class="my-4 text-center d-lg-none">
                        Operações Realizadas <span class="badge badge-primary badge-pill" data-id="totalQ"></span>
                        </h5>
                        <div class="card card-body py-1 text-success text-center my-3">
                            <p class="m-0">Receitas Realizadas</p>
                            <h5 id="receitasQuitadas"></h5>
                        </div>
                        <div class="card card-body py-1 text-danger text-center my-3">
                            <p class="m-0">Despesas Realizadas</p>
                            <h5 id="despesasQuitadas"></h5>
                        </div>
                        <div class="card card-body py-1 text-center" id = "cardResultadoRealizado">
                            <p class="m-0">Resultado Realizado</p>
                            <h5 id="resultadoRealizado"></h5>
                        </div>
                    </div>

                    <div class="col-lg">
                        <h5 class="text-center my-4 d-lg-none">
                        Operações a Realizar <span class="badge badge-primary badge-pill" data-id="totalAQ"></span>
                        </h5>
                        <div class="card card-body py-1 text-success text-center my-3">
                            <p class="m-0">Receitas a Realizar</p>
                            <h5 id="receitasAQuitar"></h5>
                        </div>
                        <div class="card card-body py-1 text-danger text-center my-3">
                            <p class="m-0">Despesas a Realizar</p>
                            <h5 id="despesasAQuitar"></h5>
                        </div>
                        <div class="card card-body py-1 text-center" id = "cardResultadoARealizar">
                            <p class="m-0">Resultado A Realizar</p>
                            <h5 id="resultadoARealizar"></h5>
                        </div>
                    </div>

                    <div class="col-lg">
                        <h5 class="text-center my-4 d-lg-none">
                        Previsão do Mês <span class="badge badge-primary badge-pill" data-id="total"></span>
                        </h5>
                        <div class="card card-body py-1 text-success text-center my-3">
                            <p class="m-0">Previsão de Receitas</p>
                            <h5 id="previsaoReceitas"></h5>
                        </div>
                        <div class="card card-body py-1 text-danger text-center my-3">
                            <p class="m-0">Previsão de Despesas</p>
                            <h5 id="previsaoDespesas"></h5>
                        </div>
                        <div class="card card-body py-1 text-center my-3" id = "cardPrevisaoResultados">
                            <p class="m-0">Previsão de Resultado</p>
                            <h5 id="previsaoResultados"></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require "_table_datatable.php" ?>

<script type="text/javascript">
    var baselink = '<?php echo BASE_URL;?>',
        currentModule = '<?php echo $modulo ?>'  // usa o nome da tabela como nome do módulo, necessário para outras interações
</script>