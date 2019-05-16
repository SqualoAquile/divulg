<?php
class lancamentosController extends controller{

    protected $table = "fluxo";

    protected $shared;

    public function __construct() {
    
       $func = new Funcionarios();
       $this->shared = new Shared($this->table);
       
       //verifica se está logado
       if($func->isLogged() == false){
           header("Location: ".BASE_URL."/login"); 
       }
       //verifica se tem permissão para ver esse módulo
       if(in_array("lancamentos_ver",$_SESSION["permissoesFuncionario"]) == FALSE){
           header("Location: ".BASE_URL."/home"); 
       }
    }
         
    public function index() {
        
        if(in_array("lancamentos_ver",$_SESSION["permissoesFuncionario"]) == FALSE || in_array("lancamentos_add",$_SESSION["permissoesFuncionario"]) == FALSE){
            header("Location: ".BASE_URL."/home"); 
        }
        
        $array = array();
        $dados['infoFunc'] = $_SESSION;
        $a = new Administradoras();
        $cc = new Contascorrentes();
        $fp = new Formaspgto();
        $lc = new Lancamentos();
        

        if(isset($_POST["descricao"]) && !empty($_POST["descricao"]) && count($_POST["descricao"]) > 0){
                    
            if(count($_POST["descricao"]) == count($_POST["valortotal"])  &&  count($_POST["descricao"]) == count($_POST["formapgto"])  && 
                count($_POST["descricao"]) == count($_POST["condpgto"])   &&  count($_POST["descricao"]) == count($_POST["nroparc"])    &&
                count($_POST["descricao"]) == count($_POST["dtvenc"])     &&  count($_POST["descricao"]) == count($_POST["valorpago"])  &&  
                count($_POST["descricao"]) == count($_POST["dtquit"])     &&  count($_POST["descricao"]) == count($_POST["status"])     &&  
                count($_POST["descricao"]) == count($_POST["dtop"])       &&  count($_POST["descricao"]) == count($_POST["nropedido"])  &&  
                count($_POST["descricao"]) == count($_POST["mov"])        &&  count($_POST["descricao"]) == count($_POST["sintetica"])  &&  
                count($_POST["descricao"]) == count($_POST["analitica"])  &&  count($_POST["descricao"]) == count($_POST["cc"])         &&  
                count($_POST["descricao"]) == count($_POST["bandeira"])   &&  count($_POST["descricao"]) == count($_POST["favorecido"]) &&  
                count($_POST["descricao"]) == count($_POST["observ"])){
                
                $descricao = $_POST["descricao"];
                $valortotal = $_POST["valortotal"];
                $formapgto = $_POST["formapgto"];
                $condpgto = $_POST["condpgto"];
                $nroparc = $_POST["nroparc"];
                $dtvenc = $_POST["dtvenc"];
                $valorpago = $_POST["valorpago"];
                $dtquit = $_POST["dtquit"];
                $status = $_POST["status"];
                $dtop = $_POST["dtop"];
                $nropedido = $_POST["nropedido"];
                $mov = $_POST["mov"];
                $sintetica = $_POST["sintetica"];
                $analitica = $_POST["analitica"];
                $cc = $_POST["cc"];
                $bandeira = $_POST["bandeira"];
                $favorecido = $_POST["favorecido"];
                $observ = $_POST["observ"];
                    
                $lc-> adicionar($descricao, $valortotal, $formapgto, $condpgto, $nroparc, $dtvenc, $valorpago, $dtquit, $status, $dtop, $nropedido, $mov, $sintetica, $analitica,
                                $cc, $bandeira, $favorecido, $observ, $_SESSION["idEmpresaFuncionario"]);
                header("Location: ".BASE_URL."/lancamentos");
            }
        }else{
            
            $dados["listaAdministradoras"]  = $this->shared->pegarListas("administradoras");
            $dados["listaContasCorrentes"]  = $cc->pegarListaCC($_SESSION["idEmpresaFuncionario"]);
            $dados["listaFormasPgto"]  = $fp->pegarListaFormasPgto($_SESSION["idEmpresaFuncionario"]);
            $this->loadTemplate("lancamentos",$dados);
        }  
    }
    
//    public function editar($id) {
//        if(in_array("administradoras_edt",$_SESSION["permissoesFuncionario"]) == FALSE || empty($id) || !isset($id)){
//            header("Location: ".BASE_URL."/admcartoes"); 
//        }
//        $array = array();
//        $dados['infoFunc'] = $_SESSION;
//        $a = new Administradoras();
//        
//        $id = addslashes($id); // id da administradora de cartão
//        if(isset($_POST["nome"]) && !empty($_POST["nome"]) && isset($_POST["bandeira"]) && isset($_POST["infos"]) && isset($_POST["txant"]) && isset($_POST["txcre"])){
//            
//            $nome = addslashes($_POST["nome"]); //nome da administradora
//            $bandeiras = $_POST["bandeira"]; // [idCadBandAceitas] => [idCadBandeiras]
//            $informacoes = $_POST["infos"];
//            $txantecipacoes = $_POST["txant"];
//            $txcreditos = $_POST["txcre"];
//            $alter = addslashes($_POST["alter"]);
//            
//            $a->editar($id, $nome,$bandeiras,$informacoes,$txantecipacoes,$txcreditos,$alter,$_SESSION["idEmpresaFuncionario"]);
//            header("Location: ".BASE_URL."/admcartoes");
//        }else{
//            
//            $dados["infoAdm"] = $a->pegarInfoAdm($id,$_SESSION["idEmpresaFuncionario"]);
//            $dados["listaBandeiras"] = $a->pegarListaBandeiras($_SESSION["idEmpresaFuncionario"]);
//            $dados["bandeirasAceitas"] = $a->pegarBandeirasAceitas($id,$_SESSION["idEmpresaFuncionario"]);
//            $this->loadTemplate("admcartoes-edt",$dados);
//        } 
//
//    }

//    public function excluir($id) {
//        if(in_array("administradoras_exc",$_SESSION["permissoesFuncionario"]) == FALSE || empty($id) || !isset($id)){
//            header("Location: ".BASE_URL."/admcartoes"); 
//        }
//        
//        $a = new Administradoras();
//        $id = addslashes($id);
//
//        $a->excluir($id,$_SESSION["idEmpresaFuncionario"]);
//        header("Location: ".BASE_URL."/admcartoes");  
//      }
    }   
  
?>

