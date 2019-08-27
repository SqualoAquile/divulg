<?php
class producaoController extends controller{

    // Protected - estas variaveis só podem ser usadas nesse arquivo
    protected $table = "pedidositens";
    protected $colunas;
    
    protected $model;
    protected $shared;
    protected $usuario;

    public function __construct() {
        // Instanciando as classes usadas no controller
        $this->shared = new Shared($this->table);
        // $tabela = ucfirst($this->table);
        // $this->model = new $tabela();
        $this->usuario = new Usuarios();
    
        $this->colunas = $this->shared->nomeDasColunas();

        // verifica se tem permissão para ver esse módulo
        if(in_array("producao_ver", $_SESSION["permissoesUsuario"]) == false){
            header("Location: " . BASE_URL . "/home"); 
        }
        // Verificar se está logado ou nao
        if($this->usuario->isLogged() == false){
            header("Location: " . BASE_URL . "/login"); 
        }
    }
     
    public function index() {
        
        $ped = new Pedidos();
        $dados['producao_semana'] = $ped->producaoSemana();
        // print_r($dados['producao_semana']['semana']); //exit;
        // echo '<br><br><br>';
        // print_r($dados['producao_semana']['operacao']); exit;
        // echo '<br><br><br>';
        // print_r($dados['producao_semana']['sabores']); //exit;

        // $prod = new Produtos();
        // $dados["produtos"] = $prod->todosProdutos();
        // print_r($dados['produtos']); exit;

        $dados['infoUser'] = $_SESSION;
        
        $dados["viewInfo"] = ["title" => "Produção da Semana"];

        $this->loadTemplate('producao', $dados);
    }
    
}   
?>