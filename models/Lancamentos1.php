
<?php

class Lancamentos extends model {
        
    public function adicionar($descricao, $valortotal, $formapgto, $condpgto, $nroparc, $dtvenc, $valorpago, $dtquit, $status, $dtop, $nropedido, $mov, $sintetica, $analitica,
                                $cc, $bandeira, $favorecido, $observ, $empresa){
        
        if(!empty($descricao) && !empty($valortotal) && !empty($formapgto) && !empty($condpgto) && !empty($nroparc) && !empty($dtvenc) && !empty($valorpago) &&
           !empty($dtquit) && !empty($status) && !empty($dtop) && !empty($nropedido) && !empty($mov) && !empty($sintetica) && !empty($analitica) && !empty($cc) &&
           !empty($bandeira) && !empty($favorecido) && !empty($observ) && !empty($empresa)){
           
            $p = new Permissoes();
            $ipcliente = $p->pegaIPcliente();
            $alteracoes = ucwords($_SESSION["nomeFuncionario"])." - $ipcliente - ".date('d/m/Y H:i:s')." - CADASTRO";

            $sqlA = "INSERT INTO fluxo (id, id_empresa, nro_pedido, nro_nf, dt_emissao_nf, movimentacao, sintetica, analitica, detalhe, conta_corrente, bandeira, favorecido, dt_operacao, ".
                    "valor_total, forma_pgto, cond_pgto, nro_parcela, dt_vencimento, valor_pago, dt_quitacao, status, observacao, dt_entrada_sistema, quem_lancou, alteracoes, situacao) ". 
                    "VALUES ";
            $sqlAux = "";
                foreach ($descricao as $idLanc => $valLanc){
                    
                   $desc =  ucwords(addslashes($descricao[$idLanc]));
                   $fav =  ucwords(addslashes($favorecido[$idLanc]));
                   $obs =  ucwords(addslashes($observ[$idLanc]));
                   //echo addslashes($dtop[$idLanc]);exit;
                   $dataOP = explode("/",addslashes($dtop[$idLanc]));
                   $dataOP = $dataOP[2]."-".$dataOP[1]."-".$dataOP[0];
                   
                   $dataVC = explode("/",addslashes($dtvenc[$idLanc]));
                   $dataVC = $dataVC[2]."-".$dataVC[1]."-".$dataVC[0];
                   
                   $dataQT = explode("/",addslashes($dtquit[$idLanc]));
                   $dataQT = $dataQT[2]."-".$dataQT[1]."-".$dataQT[0];
                   
                   $dataEN = date('Y-m-d');
                     
                   $valtot = floatval(str_replace(",",".",addslashes($valortotal[$idLanc])));
                   $valpgto = floatval(str_replace(",",".",addslashes($valorpago[$idLanc])));
                           
                   $sqlAux = $sqlAux."(DEFAULT, '$empresa', '$nropedido[$idLanc]','','','$mov[$idLanc]','$sintetica[$idLanc]', '$analitica[$idLanc]', '$desc', ".
                                      "'$cc[$idLanc]','$bandeira[$idLanc]', '$fav', '$dataOP', '$valtot', '$formapgto[$idLanc]',  ".  
                                      "'$condpgto[$idLanc]','$nroparc[$idLanc]', '$dataVC', '$valpgto', '$dataQT', '$status[$idLanc]',  ". 
                                      "'$obs','$dataEN', '".ucwords($_SESSION["nomeFuncionario"])."', '$alteracoes', 'ativo'), ";

                }
                $sqlAux = substr_replace($sqlAux,"", strrpos($sqlAux, ","));
                $sqlA = $sqlA.$sqlAux.";";
                self::db()->query($sqlA);
        }           
    }
    
    public function pegarListaLancamentoAbertos($empresa){
        $array = array();
        if(!empty($empresa)){
            $sqlA = "SELECT * FROM fluxo WHERE id_empresa = '$empresa' AND status = 'A Quitar' AND situacao = 'ativo' ORDER BY dt_vencimento ASC";
            $sqlA = self::db()->query($sqlA);
            if($sqlA->rowCount()>0){
                $array = $sqlA->fetchAll();
            }
        }
        
        return $array;
    }
    
    public function nomeDasColunas(){
       $array = array();
       
       $sql = "SHOW COLUMNS FROM fluxo";      
       $sql = self::db()->query($sql);
       if($sql->rowCount()>0){
         $sql = $sql->fetchAll(); 
         foreach ($sql as $chave => $valor){
            $array[$chave] = array("nomecol" => utf8_encode(ucwords($valor["Field"])), "tipo" => $valor["Type"]);        
         }
       }
//       print_r($array);exit;
       return $array;
    }
    
    public function quitarItensAbertos($ids,$valtots,$dtquit,$alteracoes,$empresa){
        
        if(!empty($ids) && !empty($valtots) && !empty($dtquit) && !empty($alteracoes) && !empty($empresa)){
           $resp = 0;
            $p = new Permissoes();
            $ipcliente = $p->pegaIPcliente();
            //$alteracoes = ucwords($_SESSION["nomeFuncionario"])." - $ipcliente - ".date('d/m/Y H:i:s')." - QUITACAO";
            
            $dataQT = explode("/",addslashes($dtquit));
            $dataQT = $dataQT[2]."-".$dataQT[1]."-".$dataQT[0];
            $status = 'Quitado';
            $sqlAux = "";
            
            foreach ($ids as $idChave => $idVal){

               $id =  addslashes($idVal);
               $valorpago = floatval(addslashes($valtots[$idChave]));
               $alter = addslashes($alteracoes[$idChave])." | ".ucwords($_SESSION["nomeFuncionario"])." - $ipcliente - ".date('d/m/Y H:i:s')." - QUITACAO";

    $sqlAux = $sqlAux."UPDATE fluxo SET valor_pago = '$valorpago', dt_quitacao = '$dataQT', status = '$status',  alteracoes = '$alter' WHERE id = '$id' AND id_empresa = '$empresa'; ";    

            }
                      
            $resp = self::db()->query($sqlAux);
            
            if($resp->rowCount()<=0){
                $resp = 0;
            }else{
                $resp = $resp->rowCount();
            }
            return $resp;
        }           
    }
    
    
    public function editarItem($info,$empresa){
        
        if(!empty($info) && !empty($empresa)){
            $resp = 0;
            $p = new Permissoes();
            $ipcliente = $p->pegaIPcliente();
               
            $id     = addslashes($info[0]);
            $sint   = addslashes($info[1]);
            $anal   = addslashes($info[2]);
            $desc   = addslashes($info[3]);
            $cc     = addslashes($info[4]);
            $fav    = addslashes($info[5]);
            $vtot   = floatval(addslashes($info[6]));
            $forma  = addslashes($info[7]);
            $cond   = addslashes($info[8]);
            $dtvenc = addslashes($info[9]);
            $vpago  = floatval(addslashes($info[10]));
            $observ = addslashes($info[11]);
            $altera = addslashes($info[12])." | ".ucwords($_SESSION["nomeFuncionario"])." - $ipcliente - ".date('d/m/Y H:i:s')." - ALTERACOES >> ".addslashes($info[13]);
            
            
            $sql = "UPDATE fluxo SET sintetica = '$sint', analitica = '$anal', detalhe = '$desc', conta_corrente = '$cc', favorecido = '$fav', valor_total = '$vtot', ".
                                    "forma_pgto = '$forma', cond_pgto = '$cond', dt_vencimento = '$dtvenc', valor_pago = '$vpago', observacao = '$observ', alteracoes = '$altera' ".
                   "WHERE id = '$id' AND id_empresa = '$empresa'";
                      
            $resp = self::db()->query($sql);
            
            if($resp->rowCount()<=0){
                $resp = 0;
            }else{
                $resp = $resp->rowCount();
            }
            return $resp;
        }           
    }
}