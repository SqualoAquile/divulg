<?php
class homeController extends controller{
  
    public function __construct() {

      $usuario = new Usuarios();
      
      // verifica se tem permissão para ver esse módulo
      // if(in_array("relatoriofluxocaixa_ver", $_SESSION["permissoesUsuario"]) == false){
         
      // }
      // Verificar se está logado ou nao
      if( $usuario->isLogged() == false){
          header("Location: " . BASE_URL . "/login"); 
      }
      // else{
      //     header("Location: " . BASE_URL . "/home"); 
      // }
    }
     
    public function index() {
        
      /////// FINANCEIRO
      $relatorioFinanceiro =  new Relatoriofluxocaixa();
      $sharedFinanceiro = new Shared('fluxocaixa');
      
      $dados['infoUser'] = $_SESSION;
      $dados["colunas"] = $sharedFinanceiro->nomeDasColunas();
      $dados["meta"] = $relatorioFinanceiro->meta();
      
      // $dir = dirname(__FILE__).'\bd';
      // echo "<p>Full path to this dir: " . $dir . "</p>";
      // file_put_contents('bd', 'testando a escrita de um arquivo');

      // exit;
      $this->loadTemplate('home', $dados);
    }

}   
?>