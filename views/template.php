<?php
   $menus = [
      [
         "text" => "Home",
         "icon" => "fas fa-tachometer-alt",
         "permissao" => "%",
         "link" => "/home"
      ],
      [
         "text" => "Cadastros",
         "icon" => "fas fa-save",
         "permissao" => "%",
         "link" => "/#",
         "filhos" => [
            [
               "text" => "Clientes",
               "icon" => "fas fa-handshake",
               "permissao" => "clientes_ver",
               "link" => "/clientes"
            ],
            [
               "text" => "Fornecedores",
               "icon" => "fas fa-truck",
               "permissao" => "fornecedores_ver",
               "link" => "/fornecedores"
            ],
            [
               "text" => "Funcionários",
               "icon" => "fas fa-users",
               "permissao" => "funcionarios_ver",
               "link" => "/funcionarios"
            ],
            [
               "text" => "Produtos",
               "icon" => "fas fa-boxes",
               "permissao" => "produtos_ver",
               "link" => "/produtos"
            ],
            [
               "text" => "Serviços",
               "icon" => "fas fa-hammer",
               "permissao" => "servicos_ver",
               "link" => "/servicos"
            ],
            [
               "text" => "Serviços Complementares",
               "icon" => "fas fa-toolbox",
               "permissao" => "servicoscomplementares_ver",
               "link" => "/servicoscomplementares"
            ],
            [
               "text" => "Genérico",
               "icon" => "fas fa-receipt",
               "permissao" => "generico_ver",
               "link" => "/generico"
            ],
         ]
      ],
      [
         "text" => "Financeiro",
         "icon" => "fas fa-money-bill-alt",
         "permissao" => "%",
         "link" => "/#",
         "filhos" => [
            [
               "text" => "Administradoras de Cartão",
               "icon" => "fas fa-credit-card",
               "permissao" => "administradoras_ver",
               "link" => "/administradoras"
            ],
            [
               "text" => "Lançamentos de Caixa",
               "icon" => "fas fa-cart-plus",
               "permissao" => "fluxocaixa_add",
               "link" => "/fluxocaixa/adicionar"
            ],
            [
               "text" => "Controle de Caixa",
               "icon" => "fas fa-calculator",
               "permissao" => "fluxocaixa_ver",
               "link" => "/fluxocaixa"
            ],
            [
               "text" => "Controle de Saldos",
               "icon" => "fas fa-chart-line",
               "permissao" => "controlesaldos_ver",
               "link" => "/controlesaldos"
            ]
         ]
      ],
      [
         "text" => "Operações",
         "icon" => "fas fa-business-time",
         "permissao" => "%",
         "link" => "/#",
         "filhos" => [
            [
               "text" => "Orçamentos",
               "icon" => "fas fa-tasks",
               "permissao" => "orcamentos_ver",
               "link" => "/orcamentos"
            ],
            [
               "text" => "Ordens de Serviço",
               "icon" => "fas fa-file-signature",
               "permissao" => "ordemservico_ver",
               "link" => "/ordemservico"
            ]
         ]
      ],
      [
         "text" => "Relatórios",
         "icon" => "fas fa-table",
         "permissao" => "%",
         "link" => "/#",
         "filhos" => [
            [
               "text" => "Fluxo de Caixa",
               "icon" => "fas fa-chart-line",
               "permissao" => "relatoriofluxocaixa_ver",
               "link" => "/relatoriofluxocaixa"
            ],
            [
               "text" => "Saldos",
               "icon" => "fas fa-dollar-sign",
               "permissao" => "relatoriosaldos_ver",
               "link" => "/relatoriosaldos"
            ],
            [
               "text" => "Orçamentos",
               "icon" => "fas fa-tasks",
               "permissao" => "relatorioorcamentos_ver",
               "link" => "/relatorioorcamentos"
            ],
            [
               "text" => "Produtos e Serviços",
               "icon" => "fas fa-boxes",
               "permissao" => "relatorioorcamentositens_ver",
               "link" => "/relatorioorcamentositens"
            ],
            [
               "text" => "Ordens de Serviço",
               "icon" => "fas fa-file-signature",
               "permissao" => "relatorioordensservico_ver",
               "link" => "/relatorioordensservico"
            ]
         ]
      ],
      [
         "text" => "Configurações",
         "icon" => "fas fa-cogs",
         "permissao" => "%",
         "link" => "/#",
         "filhos" => [
            [
               "text" => "Usuários",
               "icon" => "fas fa-user",
               "permissao" => "usuarios_ver",
               "link" => "/usuarios"
            ],
            [
               "text" => "Permissões",
               "icon" => "fas fa-check-double",
               "permissao" => "permissoes_ver",
               "link" => "/permissoes"
            ],
            [
               "text" => "Parâmetros",
               "icon" => "fas fa-cog",
               "permissao" => "parametros_ver",
               "link" => "/parametros"
            ],            
         ]
      ]

   ];
?>
<!doctype html>
<html lang="pt-BR" class="h-100">
   <head>
      <title>Painel - <?php echo NOME_EMPRESA;?></title>
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
      
      <link href="<?php echo BASE_URL?>/assets/css/style.css" rel="stylesheet" type="text/css"/>
      <link href="<?php echo BASE_URL?>/assets/css/vendor/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css"/>

      <style>
         .form-control.is-invalid ~ .invalid-feedback:nth-of-type(2) {
            display: none!important;
         }
         
         .navbar-brand > img {
            width: 200px;
         }
         @media (max-width: 425px) {
            .navbar-brand > img {
               width: 100px;
            }
         }

      </style>

      <script src="<?php echo BASE_URL?>/assets/js/vendor/jquery-3.3.1.min.js" type="text/javascript"></script>
      <script src="<?php echo BASE_URL?>/assets/js/vendor/jquery.mask.js" type="text/javascript"></script>
      <script src="<?php echo BASE_URL?>/assets/js/vendor/jquery.dataTables.min.js" type="text/javascript"></script>
      <script src="<?php echo BASE_URL?>/assets/js/vendor/jquery.unevent.js" type="text/javascript"></script>
      <script src="<?php echo BASE_URL?>/assets/js/vendor/bootstrap-datepicker.min.js" type="text/javascript"></script>
      <script src="<?php echo BASE_URL?>/assets/js/vendor/bootstrap-datepicker.pt-BR.min.js" type="text/javascript"></script>
      <script src="<?php echo BASE_URL?>/assets/js/vendor/popper.min.js" type="text/javascript"></script>
      <script src="<?php echo BASE_URL?>/assets/js/vendor/bootstrap.min.js" type="text/javascript"></script>
      <script src="<?php echo BASE_URL?>/assets/js/vendor/jquery.highlight.js" type="text/javascript"></script>
      <script src="<?php echo BASE_URL?>/assets/js/vendor/dataTables.searchHighlight.min.js" type="text/javascript"></script>
      <script src="<?php echo BASE_URL;?>/assets/js/vendor/loader.js" type="text/javascript"></script>
      <script src="<?php echo BASE_URL;?>/assets/js/vendor/Chart.bundle.min.js" type="text/javascript"></script>
      <script src="<?php echo BASE_URL?>/assets/js/validacoes.js" type="text/javascript"></script>
      <script src="<?php echo BASE_URL?>/assets/js/principal.js" type="text/javascript"></script>
      
   </head>
   <body class="d-flex flex-column h-100 bg-light <?php echo $viewName ?>">
      <nav id="nav" class="navbar bg-white shadow-sm fixed-top flex-nowrap">
         <ul class="nav align-items-center">
            <li>
               <button class="hamburger hamburger--collapse d-flex" tabindex="-1" id="menu-toggle" type="button" aria-label="Menu" aria-controls="sidebar-wrapper">
                  <span class="hamburger-box">
                     <span class="hamburger-inner"></span>
                  </span>
               </button>
            </li>
            <li class="flex-grow-0"> <!--Nome da Empresa-->
               <!-- <a class="navbar-brand mx-3" href="<?php //echo BASE_URL ?>/home"><?php //echo trim(NOME_EMPRESA);?></a> -->
               <a class="navbar-brand mx-3" href="<?php echo BASE_URL ?>/home">
                  <img class="" src="<?php echo BASE_URL?>/assets/images/IDFX.png">
               </a>
            </li>
         </ul>
         <ul class="navbar-nav">
            <li class="nav-item dropdown">
               <a class="nav-link dropdown-toggle text-truncate" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span>Olá, </span>
                  <?php
                     $nome = $infoUser["nomeUsuario"];
                     $nome = explode(" ", $infoUser["nomeUsuario"]);
                     $nome = $nome[0];
                  ?>
                  <span><?php echo ucfirst($nome) ?></span>
               </a>
               <div class="dropdown-menu text-truncate dropdown-menu-right position-absolute" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item text-danger" onclick="return confirm('Confirmar sua saída?')" href="<?php echo BASE_URL;?>/login/sair">Sair</a>
               </div>
            </li>
         </ul>
      </nav>
      <div id="wrapper">
         <aside id="sidebar-wrapper" class="shadow-lg bg-white">
            <ul class="nav flex-column sidebar-nav">
               <?php foreach ($menus as $key => $value): ?> <!--Verifica se o funcionario tem permissao, do contrário nem exibe os módulos-->
                  <?php $indexFilhosComPermissao = 0 ?>
                  <?php if($value["permissao"] == "%" || in_array($value["permissao"], $infoUser["permissoesUsuario"])): ?>
                     <?php
                        // Menu com Dropdown
                        // Monta o HTML através do PHP, exibindo os sub-itens (exemplo: Cadastros-> Clientes, Fornecedores, etc)
                        if (isset($value["filhos"])) {
                           
                           $filhos = "";
                           foreach ($value["filhos"] as $keyFilho => $valueFilho) {
                              if(in_array($valueFilho['permissao'],$infoUser["permissoesUsuario"])){

                                 $filhos .= '
                                    <li class="nav-item">
                                       <a class="nav-link" href="' . BASE_URL . $valueFilho["link"] . '">
                                          <div class="py-2 px-3">
                                             <i class="' . $valueFilho["icon"] . ' mr-2"></i>
                                             <span>' . $valueFilho["text"] . '</span>
                                          </div>
                                       </a>
                                    </li>
                                 ';
                                 
                                 $indexFilhosComPermissao++;
                              }
                           }

                           $navItemDropdownClass = "dropdown position-static";
                           $navLinkDropdownClass = "dropdown-toggle";
                           $navLinkDropdownAttrs = 'data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"';
                           $dropdownMenu = '
                              <ul class="dropdown-menu float-none border-0 rounded-0 position-static mt-0 py-0">' . $filhos . '</ul>
                           ';
                        } else {
                           $navItemDropdownClass = "";
                           $navLinkDropdownClass = "";
                           $navLinkDropdownAttrs = "";
                           $dropdownMenu = "";
                        }
                     ?>
                     <?php if (!isset($value["filhos"]) || $indexFilhosComPermissao > 0): ?>
                        <li class="nav-item <?php echo $navItemDropdownClass ?>">
                           <a class="nav-link my-2 <?php echo $navLinkDropdownClass ?>" href="<?php echo BASE_URL . $value["link"] ?>" <?php echo $navLinkDropdownAttrs ?>>
                              <i class="<?php echo $value["icon"] ?>"></i>
                              <span><?php echo $value["text"] ?></span>
                           </a>
                           <?php echo $dropdownMenu ?>
                        </li>
                     <?php endif ?>
                  <?php endif ?>
               <?php endforeach ?>
            </ul>
         </aside>
         <main id="page-content-wrapper">
            <div class="container-fluid">
               <?php $this->loadViewInTemplate($viewName, $viewData); ?>
            </div>
         </main>
      </div>
      <footer class="py-3 bg-white mt-auto shadow-sm">
         <div class="container-fluid">
            <div class="text-muted text-center small">Todos os direitos reservados <strong>Leme</strong><br/>Estamos à disposição: <a href="mailto:contato@useleme.com.br" tabindex="-1" class="text-muted font-italic">contato@useleme.com.br</a></div>
         </div>
      </footer>
   </body> 
</html>