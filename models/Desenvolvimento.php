<?php
class Desenvolvimento extends model {

    protected $table = "desenvolvimento";
    protected $permissoes;
    protected $shared;

    public function __construct() {
        $this->permissoes = new Permissoes();
        $this->shared = new Shared($this->table);
    }
    
    public function buscaTabela($request) {
        $existe = false;
        if( !empty($request) ){
            $nomeTabela = addslashes( $request['tabela'] );
            $arrayAux = array();

            $sql = "SHOW TABLES";
            $sql = self::db()->query($sql);
            
            if($sql->rowCount()>0){
                $tabelas = $sql->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($tabelas as $key => $value) {
                    if( trim(strtolower( $value['Tables_in_pnp'] )) == trim(strtolower( $nomeTabela ))  ){
                        $existe = true;
                    }
                }
            }
        }
        return $existe; 
    }

    public function criaTabela($request) {
        
        if( !empty($request) ){
            $nomeTabela = addslashes( $request['tabela'] );
            $primeira = $request['query1'] ;
            $segunda =  $request['query2'] ;
            $terceira = $request['query3'] ;

            self::db()->query('START TRANSACTION;');

            self::db()->query($primeira);
            $erro1 = self::db()->errorInfo();
            
            self::db()->query($segunda);
            $erro2 = self::db()->errorInfo();

            self::db()->query($terceira);
            $erro3 = self::db()->errorInfo();

            if( empty($erro1[2]) && empty($erro2[2]) && empty($erro3[2])  ){
            
                self::db()->query('COMMIT;');

                // código para criar os arquivos padrão do MVC
                // controller
                $caminho = __FILE__;
                $controllerGenerico = str_replace('\models\Desenvolvimento.php','\controllers\genericoController.php', $caminho);
                $controllerNovo = str_replace('generico', $nomeTabela, $controllerGenerico);
                copy($controllerGenerico, $controllerNovo);

                $arq = file_get_contents($controllerNovo, FILE_TEXT);
                $arq = str_replace( 'class genericoController extends', 'class '.$nomeTabela.'Controller extends' , $arq);
                $arq = str_replace( 'protected $table = "generico"', 'protected $table = "'.$nomeTabela.'"', $arq); 
                file_put_contents($controllerNovo, $arq, FILE_TEXT);

                // model
                $modelGenerico = str_replace('Desenvolvimento', 'Generico', $caminho);
                $modelNovo = str_replace('Generico', ucfirst( $nomeTabela ), $modelGenerico);
                copy($modelGenerico, $modelNovo);

                $arq2 = file_get_contents($modelNovo, FILE_TEXT);
                $arq2 = str_replace( 'class Generico extends', 'class '.ucfirst($nomeTabela).' extends' , $arq2);
                $arq2 = str_replace( 'protected $table = "generico"', 'protected $table = "'.$nomeTabela.'"', $arq2); 
                file_put_contents($modelNovo, $arq2, FILE_TEXT);

                // views
                $viewGenerico = str_replace('\models\Desenvolvimento.php','\views\generico.php', $caminho);
                $viewNovo = str_replace('generico', $nomeTabela, $viewGenerico);
                copy($viewGenerico, $viewNovo);

                $viewFormGenerico = str_replace('\models\Desenvolvimento.php','\views\generico-form.php', $caminho);
                $viewFormNovo = str_replace('generico-form', $nomeTabela.'-form', $viewFormGenerico);
                copy($viewFormGenerico, $viewFormNovo);
                
                // insert permissoes no bancos
                $sql = "INSERT INTO `permissoesparametros`(`id`, `nome`) VALUES (DEFAULT, '".$nomeTabela."_ver'), (DEFAULT, '".$nomeTabela."_add'), (DEFAULT, '".$nomeTabela."_edt'), (DEFAULT, '".$nomeTabela."_exc')";

                self::db()->query($sql);
                // inserir no template                 
                return true;

            }else{

                self::db()->query('ROLLBACK;');
                return false;
            }
        }
    }
    



    public function infoItem($id) {
        $array = array();
        $arrayAux = array();

        $id = addslashes(trim($id));
        $sql = "SELECT * FROM " . $this->table . " WHERE id='$id' AND situacao = 'ativo'";      
        $sql = self::db()->query($sql);

        if($sql->rowCount()>0){
            $array = $sql->fetch(PDO::FETCH_ASSOC);
            $array = $this->shared->formataDadosDoBD($array);
        }
        
        return $array; 
    }

    public function adicionar($request) {
        
        $ipcliente = $this->permissoes->pegaIPcliente();
        $request["alteracoes"] = ucwords($_SESSION["nomeUsuario"])." - $ipcliente - ".date('d/m/Y H:i:s')." - CADASTRO";
        
        $request["situacao"] = "ativo";

        $keys = implode(",", array_keys($request));

        $values = "'" . implode("','", array_values($this->shared->formataDadosParaBD($request))) . "'";

        $sql = "INSERT INTO " . $this->table . " (" . $keys . ") VALUES (" . $values . ")";
        
        self::db()->query($sql);

        $erro = self::db()->errorInfo();

        if (empty($erro[2])){

            $_SESSION["returnMessage"] = [
                "mensagem" => "Registro inserido com sucesso!",
                "class" => "alert-success"
            ];
        } else {
            $_SESSION["returnMessage"] = [
                "mensagem" => "Houve uma falha, tente novamente! <br /> ".$erro[2],
                "class" => "alert-danger"
            ];
        }
    }

    public function editar($id, $request) {

        if(!empty($id)){

            $id = addslashes(trim($id));

            $ipcliente = $this->permissoes->pegaIPcliente();
            $hist = explode("##", addslashes($request['alteracoes']));

            if(!empty($hist[1])){ 
                $request['alteracoes'] = $hist[0]." | ".ucwords($_SESSION["nomeUsuario"])." - $ipcliente - ".date('d/m/Y H:i:s')." - ALTERAÇÃO >> ".$hist[1];
            }else{
                $_SESSION["returnMessage"] = [
                    "mensagem" => "Houve uma falha, tente novamente! <br /> Registro sem histórico de alteração.",
                    "class" => "alert-danger"
                ];
                return false;
            }

            $request = $this->shared->formataDadosParaBD($request);

            // Cria a estrutura key = 'valor' para preparar a query do sql
            $output = implode(', ', array_map(
                function ($value, $key) {
                    return sprintf("%s='%s'", $key, $value);
                },
                $request, //value
                array_keys($request)  //key
            ));

            $sql = "UPDATE " . $this->table . " SET " . $output . " WHERE id='" . $id . "'";
             
            self::db()->query($sql);

            $erro = self::db()->errorInfo();

            if (empty($erro[2])){

                $_SESSION["returnMessage"] = [
                    "mensagem" => "Registro alterado com sucesso!",
                    "class" => "alert-success"
                ];
            } else {
                $_SESSION["returnMessage"] = [
                    "mensagem" => "Houve uma falha, tente novamente! <br /> ".$erro[2],
                    "class" => "alert-danger"
                ];
            }
        }
    }
    
    public function excluir($id){
        if(!empty($id)) {

            $id = addslashes(trim($id));

            //se não achar nenhum usuario associado ao grupo - pode deletar, ou seja, tornar o cadastro situacao=excluído
            $sql = "SELECT alteracoes FROM ". $this->table ." WHERE id = '$id' AND situacao = 'ativo'";
            $sql = self::db()->query($sql);
            
            if($sql->rowCount() > 0){  

                $sql = $sql->fetch();
                $palter = $sql["alteracoes"];
                $ipcliente = $this->permissoes->pegaIPcliente();
                $palter = $palter." | ".ucwords($_SESSION["nomeUsuario"])." - $ipcliente - ".date('d/m/Y H:i:s')." - EXCLUSÃO";

                $sqlA = "UPDATE ". $this->table ." SET alteracoes = '$palter', situacao = 'excluido' WHERE id = '$id' ";
                self::db()->query($sqlA);

                $erro = self::db()->errorInfo();

                if (empty($erro[2])){

                    $_SESSION["returnMessage"] = [
                        "mensagem" => "Registro deletado com sucesso!",
                        "class" => "alert-success"
                    ];
                } else {
                    $_SESSION["returnMessage"] = [
                        "mensagem" => "Houve uma falha, tente novamente! <br /> ".$erro[2],
                        "class" => "alert-danger"
                    ];
                }
            }
        }
    }

    public function nomeClientes($termo){
        // echo "aquiiii"; exit;
        $array = array();
        // 
        $sql1 = "SELECT `id`, `nome` FROM `generico` WHERE situacao = 'ativo' AND nome LIKE '%$termo%' ORDER BY nome ASC";

        $sql1 = self::db()->query($sql1);
        $nomesAux = array();
        $nomes = array();
        if($sql1->rowCount() > 0){  
            
            $nomesAux = $sql1->fetchAll(PDO::FETCH_ASSOC);

            foreach ($nomesAux as $key => $value) {
                $nomes[] = array(
                    "id" => $value["id"],
                    "label" => $value["nome"],
                    "value" => $value["nome"]
                );     
            }

        }

        // fazer foreach e criar um array que cada elemento tenha id: label: e value:
        // print_r($nomes); exit; 
        $array = $nomes;

       return $array;
    }

}