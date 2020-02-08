<script type="text/javascript">
    var baselink = '<?php echo BASE_URL;?>',
        currentModule = '<?php echo str_replace(array("-add", "-edt"), "", basename(__FILE__, ".php")) ?>',
        campoPesquisa = '', // aqui vai o campo de id-usuario caso seja necessário filtrar o datatable somente para os registros referentes ao usuário logado
        valorPesquisa = '<?php echo in_array('podetudo_ver', $_SESSION['permissoesUsuario']) ? "" : $_SESSION["idUsuario"]; ?>';
</script>
<script src="<?php echo BASE_URL?>/assets/js/home.js" type="text/javascript"></script>

<?php if (isset($_SESSION["returnMessage"])): ?>

  <div class="alert <?php echo $_SESSION["returnMessage"]["class"] ?> alert-dismissible">

    <?php echo $_SESSION["returnMessage"]["mensagem"] ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>

  </div>

<?php endif?>   

   
<?php if( !in_array("podetudo_ver", $infoUser["permissoesUsuario"]) ): ?>

    <h2 class="display-4 font-weight-bold py-4">Bom dia, <?php echo ucfirst($_SESSION['nomeUsuario'])?> !</h2>

      <?php if ( !empty($infoParametros['aviso_home_func']) ):?>
        <div class="row my-2">  
          <div class="col-lg">
            <div class="alert alert-danger text-center w-100" style="height: auto; max-height: 300px;">
              <?php echo ucfirst( $infoParametros['aviso_home_func'] ); ?>
            </div>
          </div>
        </div>  
      <?php endif;?>
    <div class="row my-2">
        <div class="col-lg">
          <img src="<?php echo BASE_URL?>/assets/images/motivacional.jpeg" class="img-fluid w-100" style="height: auto; max-height: 600px; object-fit: fill;">
        </div>
    </div>  
    <div class="row my-2">
      
    </div>
    <div class="row my-2">
        <div class="col-lg">
        <div class="alert alert-info text-center" role="alert">Informações da Empresa! </div>
        </div>
    </div>  

<?php else:?>

    <?php if( in_array("relatoriofluxocaixa_ver", $infoUser["permissoesUsuario"]) ): ?>

      <script src="<?php echo BASE_URL?>/assets/js/home.js" type="text/javascript"></script>

          <ul class="nav nav-tabs mt-2" id="myTab" role="tablist" >
              <li class="nav-item">
                  <a class="nav-link active" id="financ-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><h2>Financeiro</h2> </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" id="dashequipe-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><h2>DashBoard Equipe</h2> </a>
              </li>
          </ul>

          <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="financ-tab">
              <!-- inicio financeiro -->
                  <div class="card my-1">
                      <div class="card-header bg-dark">
                          <div class="row">
                              <div class="col-lg">
                                  <h4 class="text-weight-bold text-white">Previsão de Despesas e Receitas Hoje e Próximos 7 e 15 Dias</h4>
                              </div>
                          </div>        
                      </div>
                  </div>

                  <div class="card card-body py-2 ">
                    <div class="row">
                      <div class="col-lg">
                        <div class="card text-center mb-0 alert alert-danger">
                          <div class="card-body">
                            <small class="card-title">Hoje</small>
                            <h5 class="card-text" id="despesa_hoje">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0 alert alert-danger">
                          <div class="card-body">
                            <small class="card-title">Próximos 7 dias</small>
                            <h5 class="card-text" id="despesa_7dias">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0 alert alert-danger">
                          <div class="card-body">
                            <small class="card-title">Próximos 15 dias</small>
                            <h5 class="card-text" id="despesa_15dias">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg">
                        <div class="card text-center mb-0 alert alert-success">
                          <div class="card-body">
                            <small class="card-title">Hoje</small>
                            <h5 class="card-text" id="receita_hoje">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0 alert alert-success">
                          <div class="card-body">
                            <small class="card-title">+ 7 dias</small>
                            <h5 class="card-text" id="receita_7dias">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0 alert alert-success">
                          <div class="card-body">
                            <small class="card-title">+ 15 dias</small>
                            <h5 class="card-text" id="receita_15dias">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                  <!-- teste de outras disposição -->
                  <div class="card my-1">
                      <div class="card-header bg-dark">
                          <div class="row">
                              <div class="col-lg">
                                  <h4 class="text-weight-bold text-white" id="titulo_desp">Previsão de Despesas</h4>
                              </div>
                          </div>        
                      </div>
                  </div>

                  <div class="card card-body py-2 my-1 alert-danger">
                    <div class="row">
                      <!-- <div class="col-lg">
                        <div class="card text-center mb-3">
                          <div class="card-body">
                            <small class="card-title">Hoje</small>
                            <h5 class="card-text" id="despesa_hoje">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-3">
                          <div class="card-body">
                            <small class="card-title">Próximos 7 dias</small>
                            <h5 class="card-text" id="despesa_7dias">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-3">
                          <div class="card-body">
                            <small class="card-title">Próximos 15 dias</small>
                            <h5 class="card-text" id="despesa_15dias">R$ 0,00</h5>
                          </div>
                        </div>
                      </div> -->
                      <div class="col-lg">
                        <div class="card text-center mb-0">
                          <div class="card-body">
                            <small class="card-title">até dia 10/m0</small>
                            <h5 class="card-text" id="despesa_d10m0">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0">
                          <div class="card-body">
                            <small class="card-title">até dia 25/m0</small>
                            <h5 class="card-text" id="despesa_d25m0">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0">
                          <div class="card-body">
                            <small class="card-title">até dia 10/m1</small>
                            <h5 class="card-text" id="despesa_d10m1">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0">
                          <div class="card-body">
                            <small class="card-title">até dia 25/m1</small>
                            <h5 class="card-text" id="despesa_d25m1">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0">
                          <div class="card-body">
                            <small class="card-title">até dia 10/m2</small>
                            <h5 class="card-text" id="despesa_d10m2">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0">
                          <div class="card-body">
                            <small class="card-title">até dia 25/m2</small>
                            <h5 class="card-text" id="despesa_d25m2">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>

                  <div class="card my-2">
                      <div class="card-header bg-dark">
                          <div class="row">
                              <div class="col-lg">
                                  <h4 class="text-weight-bold text-white" id="titulo_rece" >Previsão de Receitas</h4>
                              </div>
                          </div>        
                      </div>
                  </div>


                  <div class="card card-body py-2 my-1 alert-success">
                    <div class="row">
                      <!-- <div class="col-lg">
                        <div class="card text-center mb-3">
                          <div class="card-body">
                            <small class="card-title">Hoje</small>
                            <h5 class="card-text" id="receita_hoje">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-3">
                          <div class="card-body">
                            <small class="card-title">+ 7 dias</small>
                            <h5 class="card-text" id="receita_7dias">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-3">
                          <div class="card-body">
                            <small class="card-title">+ 15 dias</small>
                            <h5 class="card-text" id="receita_15dias">R$ 0,00</h5>
                          </div>
                        </div>
                      </div> -->
                      <div class="col-lg">
                        <div class="card text-center mb-0">
                          <div class="card-body">
                            <small class="card-title">até dia 10/m0</small>
                            <h5 class="card-text" id="receita_d10m0">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0">
                          <div class="card-body">
                            <small class="card-title">até dia 25/m0</small>
                            <h5 class="card-text" id="receita_d25m0">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0">
                          <div class="card-body">
                            <small class="card-title">até dia 10/m1</small>
                            <h5 class="card-text" id="receita_d10m1">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0">
                          <div class="card-body">
                            <small class="card-title">até dia 25/m1</small>
                            <h5 class="card-text" id="receita_d25m1">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0">
                          <div class="card-body">
                            <small class="card-title">até dia 10/m2</small>
                            <h5 class="card-text" id="receita_d10m2">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg">
                        <div class="card text-center mb-0">
                          <div class="card-body">
                            <small class="card-title">até dia 25/m2</small>
                            <h5 class="card-text" id="receita_d25m2">R$ 0,00</h5>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
              
              <!-- fim financeiro -->
              </div>
              <!-- inicio dashboard equipe -->
              <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="dashequipe-tab">
                  <h2 class="display-4 font-weight-bold py-4">Bom dia, <?php echo ucfirst($_SESSION['nomeUsuario'])?> !</h2>
                  <?php if ( !empty($infoParametros['aviso_home_func']) ):?>
                    <div class="row my-2">  
                      <div class="col-lg">
                        <div class="alert alert-danger text-center w-100" style="height: auto; max-height: 300px;">
                          <?php echo ucfirst( $infoParametros['aviso_home_func'] ); ?>
                        </div>
                      </div>
                    </div>  
                  <?php endif;?>
                  <div class="row my-2">
                    <div class="col-lg">
                      <img src="<?php echo BASE_URL?>/assets/images/motivacional.jpeg" class="img-fluid w-100" style="height: auto; max-height: 600px; object-fit: fill;">
                    </div>
                  </div>  
                  <div class="row my-2">
                  </div>
                  <div class="row my-2">
                    <div class="col-lg">
                      <div class="alert alert-info text-center" role="alert">Informações da Empresa! </div>
                    </div>
                  </div>  
              </div>
              <!-- fim dashboard equipe -->
          </div>  
    
    <?php endif ?>

<?php endif ?>
