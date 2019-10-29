<?php
class Desenvolvimento extends model {

    protected $table = "desenvolvimento";
    protected $permissoes;
    protected $shared;

    public function __construct() {
        $this->permissoes = new Permissoes();
        $this->shared = new Shared($this->table);
    }
    
    // public function buscaTabela($request) {
        
    //     $existe = false;

    //     echo 'lalalala'; exit;

    //     if( !empty($request) ){
    //         $nomeTabela = addslashes( $request['tabela'] );
    //         $arrayAux = array();

    //         $sql = "SHOW TABLES";
    //         $sql = self::db()->query($sql);
            
    //         if($sql->rowCount()>0){
    //             $tabelas = $sql->fetchAll(PDO::FETCH_ASSOC);
                
    //             foreach ($tabelas as $key => $value) {
    //                 if( trim(strtolower( $value['Tables_in_pnp'] )) == trim(strtolower( $nomeTabela ))  ){
    //                     $existe = true;
    //                 }
    //             }
    //         }
    //     }
    //     return $existe; 
    // }

    public function buscaTabelasBD() {
        $sql = "SHOW TABLES";
        $sql = self::db()->query($sql);
        
        $nome = $GLOBALS['config']['db'];
        // echo print_r($GLOBALS); exit;
         
        if($sql->rowCount()>0){
            $tabelas = $sql->fetchAll(PDO::FETCH_ASSOC);
            // print_r($tabelas); exit;
            $tabelasDB = array();
            foreach ($tabelas as $key => $value) {
                $tabAtual = '';
                $tabAtual = trim(strtolower( $value['Tables_in_'.$nome] ));
                
                $sql = self::db()->query("SHOW FULL COLUMNS FROM " . $tabAtual);
                $result = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach ($result as $chave => $valor ) {
                    // $tabelasDB[$tabAtual][] = [
                    //     'nomecampo' => $valor['Field'],
                    //     'tipotamanho' => $valor['Type'],
                    //     'obrigatorio' => $valor['Null'],
                    //     'comentario' => json_decode($valor['Comment'])
                    // ];
                    if ($valor['Null'] == 'NO'){
                        $tabelasDB[$tabAtual][] = "`".$valor['Field']."` ".$valor['Type']." NOT NULL COMMENT `".$valor['Comment']."`";
                         
                    }else{
                        $tabelasDB[$tabAtual][] = "`".$valor['Field']."` ".$valor['Type']." NULL COMMENT `".$valor['Comment']."`";
                    } 
                       
                }
            }
            // print_r($tabelasDB); exit;
            return $tabelasDB;
        }
    }

    public function criaTabela($request) {
        
        if( !empty($request) ){
            $nomeTabela = addslashes( $request['tabela'] );
            $primeira = $request['query1'] ;
            $segunda =  $request['query2'] ;
            $terceira = $request['query3'] ;

            // print_r($primeira); exit;
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
    
    public function editaTabela($request) {
        
        if( !empty($request) ){
            $nomeTabela = addslashes( $request['tabela'] );
            $primeira = $request['query1'] ;
            
            // echo $primeira; exit;
            self::db()->query('START TRANSACTION;');

            self::db()->query($primeira);
            $erro1 = self::db()->errorInfo();

            if( empty($erro1[2]) ){
            
                self::db()->query('COMMIT;');
                return true;

            }else{

                self::db()->query('ROLLBACK;');
                return false;
            }
        }
    }



    

}