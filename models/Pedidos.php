<?php
class Pedidos extends model {

    protected $table = "pedidos";
    protected $permissoes;
    protected $shared;

    public function __construct() {
        $this->permissoes = new Permissoes();
        $this->shared = new Shared($this->table);
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
        // print_r($request); exit;
        $operacao = array();  
        // separando as informações vindas do $_POST e montando a requisição da tabela auxiliar
        for ($i = 0; $i < 7; $i++ ){
            
            switch ($i) {
                case 0:
                    $operacao['doacao'] = array_pop($request);
                    break;
                case 1:
                    $operacao['sobrad2'] = array_pop($request);
                    break;
                case 2:
                    $operacao['sobrad0'] = array_pop($request);
                    break;
                case 3:
                    $operacao['venda'] = array_pop($request);
                    break;
                case 4:
                    $operacao['entrega'] = array_pop($request);
                    break;
                case 5:
                    $operacao['pedido'] = array_pop($request);
                    break;            
                case 6:
                    $operacao['sobrad1'] = array_pop($request);
                    break;
                default:
                    # code...
                    break;
            }
        }   

        // print_r($operacao); exit;

        $ipcliente = $this->permissoes->pegaIPcliente();
        $request["alteracoes"] = ucwords($_SESSION["nomeUsuario"])." - $ipcliente - ".date('d/m/Y H:i:s')." - CADASTRO";
        
        $request["situacao"] = "ativo";

        $keys = implode(",", array_keys($request));

        $values = "'" . implode("','", array_values($this->shared->formataDadosParaBD($request))) . "'";
        
        $sql = "INSERT INTO " . $this->table . " (" . $keys . ") VALUES (" . $values . ")";

        // print_r($request); exit;

        self::db()->query($sql);

        $erro = self::db()->errorInfo();
        $idNovo = self::db()->lastInsertId();
        
        if (empty($erro[2]) && !empty($idNovo)){
            // o registro foi inserido com sucesso na tabela principal
            // busca do cadastro dos sabores
            $sqlS = "SELECT codigo, sabor FROM produtos WHERE situacao = 'ativo'";

            $sqlS = self::db()->query($sqlS);
            
            $aux = array();
            $produtos = array();

            if($sqlS->rowCount() > 0){  
                $aux = $sqlS->fetchAll(PDO::FETCH_ASSOC);
                foreach ($aux as $key => $value) {
                    $produtos[ "'".$value["codigo"]."'" ] = $value["sabor"];     
                }
            }
            // print_r($produtos); exit;    
            // montar a query que vai registrar pedidositens
            $sql1 = "INSERT INTO `pedidositens` (`id`, `id_usuario`, `id_pedido`, `vendedor`, `data_pedido`, `data_entrega`, `dia_semana`, `rota_endereco`, `sabor`, `codigo`, `qtd_sobrad1`, `qtd_pedido`, `qtd_entrega`, `qtd_venda`, `qtd_sobrad0`, `qtd_sobrad2`, `qtd_doacao`, `alteracoes`, `situacao`) VALUES ";
            $dtpedido = explode("/", $request['data_pedido'] );
            $dtpedido = $dtpedido[2]."-".$dtpedido[1]."-".$dtpedido[0];

            $dtentrega = explode("/", $request['data_entrega'] );
            $dtentrega = $dtentrega[2]."-".$dtentrega[1]."-".$dtentrega[0];
            

            foreach ($operacao['pedido'] as $key => $value) {
                $sql1 .= "(DEFAULT, 
                    '$request[id_vendedor]', 
                    '$idNovo', 
                    '$request[vendedor]', 
                    '$dtpedido', 
                    '$dtentrega', 
                    '$request[dia_semana]', 
                    '$request[rota_endereco]','".
                    $produtos[$key]."',".
                    $key .",'" .  
                    $operacao['sobrad1'][$key] . "','". 
                    $operacao['pedido'][$key]."','". 
                    $operacao['entrega'][$key]."','". 
                    $operacao['venda'][$key]."','". 
                    $operacao['sobrad0'][$key]."','". 
                    $operacao['sobrad2'][$key]."','". 
                    $operacao['doacao'][$key]."',
                    '$request[alteracoes]', 
                    'ativo' ), ";          
            }
            $sql1 = substr( $sql1, 0, strlen($sql1) - 2);
            // print_r($sql1); exit;

            self::db()->query($sql1);

            $erro2 = self::db()->errorInfo();

            if (empty($erro2[2])){
                
                $_SESSION["returnMessage"] = [
                    "mensagem" => "Registro inserido com sucesso!",
                    "class" => "alert-success"
                ];
                return;

            }else{

            // o registro foi inserido na tabela principal e não foi inserido na tabela auxiliar
            // apagar o registro na tabela principal

            $sqlA = "DELETE * FROM 'pedidositens' WHERE id_pedido = '$idNovo'";
                
                self::db()->query($sqlA);
                
                $_SESSION["returnMessage"] = [
                    "mensagem" => "Houve uma falha, tente novamente! <br /> ",
                    "class" => "alert-danger"
                ];
                return;

            }    
            
            
        } else {
            // testar se o registro não foi inserido, caso tenha sido deve ser apagado
            if(!empty($idNovo)){
                 // o registro foi inserido na tabela principal e não foi inserido na tabela auxiliar
                // apagar o registro na tabela principal
                $sqlA = "DELETE * FROM 'pedidositens' WHERE id_pedido = '$idNovo'";
                
                self::db()->query($sqlA);
                
                $_SESSION["returnMessage"] = [
                    "mensagem" => "Houve uma falha, tente novamente! <br /> ",
                    "class" => "alert-danger"
                ];
                return;

            }else{
                // o registro não foi inserido em nenhuma tabela
                $_SESSION["returnMessage"] = [
                    "mensagem" => "Houve uma falha, tente novamente! <br /> ".$erro[2],
                    "class" => "alert-danger"
                ];
                return;
            }
        }
    }

    public function editar($id, $request) {
      
        // print_r($request); exit;
        // separando as informações vindas do $_POST
         $operacao = array();  
         // separando as informações vindas do $_POST e montando a requisição da tabela auxiliar
         for ($i = 0; $i < 7; $i++ ){
             
             switch ($i) {
                 case 0:
                     $operacao['doacao'] = array_pop($request);
                     break;
                 case 1:
                     $operacao['sobrad2'] = array_pop($request);
                     break;
                 case 2:
                     $operacao['sobrad0'] = array_pop($request);
                     break;
                 case 3:
                     $operacao['venda'] = array_pop($request);
                     break;
                 case 4:
                     $operacao['entrega'] = array_pop($request);
                     break;
                 case 5:
                     $operacao['pedido'] = array_pop($request);
                     break;            
                 case 6:
                     $operacao['sobrad1'] = array_pop($request);
                     break;
                 default:
                     # code...
                     break;
             }
         }   
 
        //testa se tem intes no pedido com esse id
        $sqlZ = "SELECT COUNT(*) as itens FROM `pedidositens` WHERE `id_pedido`= '$id' AND `situacao`='ativo' ";
        $sqlZ = self::db()->query($sqlZ);
        $itensPedido = $sqlZ->fetch();
        $itensPedido = intval($itensPedido['itens']);
         
        if(!empty($id) && $itensPedido > 0 ){
            
            $idPedido = addslashes(trim($id));

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

            //montagem da query de update da tabela principal
            $sql1 = "UPDATE " . $this->table . " SET " . $output . " WHERE id='" . $idPedido . "'";
            
            //montagem da query de delete da tabela secundaria
            $sql2 = "DELETE FROM `pedidositens` WHERE `id_pedido`= '$idPedido' ";
            
             // busca do cadastro dos sabores
             $sqlS = "SELECT codigo, sabor FROM produtos WHERE situacao = 'ativo'";

             $sqlS = self::db()->query($sqlS);
             
             $aux = array();
             $produtos = array();
 
             if($sqlS->rowCount() > 0){  
                 $aux = $sqlS->fetchAll(PDO::FETCH_ASSOC);
                 foreach ($aux as $key => $value) {
                     $produtos[ "'".$value["codigo"]."'" ] = $value["sabor"];     
                 }
             }

            //montagem de insert da tabela secundaria
            $sql3 = "INSERT INTO `pedidositens` (`id`, `id_usuario`, `id_pedido`, `vendedor`, `data_pedido`, `data_entrega`, `dia_semana`, `rota_endereco`, `sabor`, `codigo`, `qtd_sobrad1`, `qtd_pedido`, `qtd_entrega`, `qtd_venda`, `qtd_sobrad0`, `qtd_sobrad2`, `qtd_doacao`, `alteracoes`, `situacao`) VALUES ";
            
            $dtpedido = $request['data_pedido'];
            $dtentrega = $request['data_entrega'];

            foreach ($operacao['pedido'] as $key => $value) {
                $sql3 .= "(DEFAULT, 
                    '$request[id_vendedor]', 
                    '$idPedido', 
                    '$request[vendedor]', 
                    '$dtpedido', 
                    '$dtentrega',
                    '$request[dia_semana]', 
                    '$request[rota_endereco]','".
                    $produtos[$key]."',".
                    $key .",'" .  
                    $operacao['sobrad1'][$key] . "','". 
                    $operacao['pedido'][$key]."','". 
                    $operacao['entrega'][$key]."','". 
                    $operacao['venda'][$key]."','". 
                    $operacao['sobrad0'][$key]."','". 
                    $operacao['sobrad2'][$key]."','". 
                    $operacao['doacao'][$key]."',
                    '$request[alteracoes]', 
                    'ativo' ), ";  
                           
            }
            $sql3 = substr( $sql3, 0, strlen($sql3) - 2);
            
            // echo 'quant linhas deletadas:   '.$itensPedido.'<br>';
            // echo 'quant linhas inseridas:   '.count($operacao['pedido']).'<br>'; 
            // echo 'query 1: '. $sql1. '<br><br><br>';
            // echo 'query 2: '. $sql2. '<br><br><br>';
            // echo 'query 3: '. $sql3. '<br><br><br>'; 

            // montadas as 3 querys começa a transaction 
            self::db()->query('START TRANSACTION;');

            // query para update da tabela principal
            $updatePrimaria = self::db()->query($sql1);
            
            // query para delete da tabela secundária
            $deleteSecundaria = self::db()->query($sql2);
            
            // query para insert da tabela secundária
            $insertSecundaria = self::db()->query($sql3);
           
        //    echo 'linhas update:  '.$updatePrimaria->rowCount().'<br><br>';
        //    echo 'linhas delete:  '.$deleteSecundaria->rowCount().'<br><br>';
        //    echo 'linhas insert:  '.$insertSecundaria->rowCount().'<br><br>';exit;

            if( $updatePrimaria->rowCount() == 1 && 
                $deleteSecundaria->rowCount() == $itensPedido && 
                $insertSecundaria->rowCount() == count($operacao['pedido']) ){
            
                self::db()->query('COMMIT;');
                $_SESSION["returnMessage"] = [
                    "mensagem" => "Registro editado com sucesso!",
                    "class" => "alert-success"
                ];
                return;

            }else{

                self::db()->query('ROLLBACK;');
                $_SESSION["returnMessage"] = [
                    "mensagem" => "Houve uma falha, tente novamente! <br /> ",
                    "class" => "alert-danger"
                ];
                return;
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
        $sql1 = "SELECT 'id', 'nome' FROM 'generico' WHERE situacao = 'ativo' AND nome LIKE '%$termo%' ORDER BY nome ASC";

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

    public function jaExistePedido($dtentrega, $idVend){

        // echo "aquiiii"; exit;
        $array = array();
        $dtentrega = explode("/", $dtentrega);
        $dtentrega = $dtentrega[2].'-'.$dtentrega[1].'-'.$dtentrega[0];
        
        $sql1 = "SELECT * FROM ". $this->table ." WHERE situacao = 'ativo' AND data_entrega = '$dtentrega' AND id_vendedor = '$idVend'";

        $sql1 = self::db()->query($sql1);

        if($sql1->rowCount() > 0){  
            return true;
        }else{
            return false;
        }

    }

    public function sobrad0ontem($dtOntem, $idVend){
        // echo "dtOntem:  ".$dtOntem."    -    idvend: ".$idVend; exit;
        
        $array = array();
        $dtOntem = explode("/", $dtOntem);
        $dtOntem = $dtOntem[2].'-'.$dtOntem[1].'-'.$dtOntem[0];
        // 
        $sql1 = "SELECT codigo, qtd_sobrad0 FROM pedidositens WHERE situacao = 'ativo' AND data_entrega = '$dtOntem' AND id_usuario = '$idVend'";

        // echo $sql1; exit;
        $sql1 = self::db()->query($sql1);
        
        $sobrad0 = array();
        $retorno =  array();

        if($sql1->rowCount() > 0){  
            
            $sobrad0 = $sql1->fetchAll(PDO::FETCH_ASSOC);
            // print_r($sobrad0); exit;
            $tot = 0;
            foreach ($sobrad0 as $key => $value) {
                $tot = intval( $tot +  intval($value["qtd_sobrad0"] ) );
                $retorno[] = array(
                    "codigo" => $value["codigo"],
                    "qtd" => $value["qtd_sobrad0"]
                );     
            }

            // echo $tot; exit;
            // print_r($retorno); exit;
        }
        if ($tot > 0 ){
            $array = $retorno;
        }
    //    print_r($array); exit; 
       return $array;
    }

}