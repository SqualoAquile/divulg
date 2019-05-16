<?php
class Fluxocaixa extends model {

    protected $table = "fluxocaixa";
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

    public function quitar($request) {

        $data_quitacao = $request["data_quitacao"];
        $arr_quitar_id = $request["aquitares"];
        
        if (!empty($data_quitacao) && !empty($arr_quitar_id)) {

            $dtaux = explode("/", $data_quitacao);
            if (count($dtaux) == 3) {
                $data_quitacao = $dtaux[2] . "-" . $dtaux[1] . "-" . $dtaux[0];
            }
            
            $ipcliente = $this->permissoes->pegaIPcliente();
            $alteracoes = " | " . ucwords($_SESSION["nomeUsuario"])." - $ipcliente - ".date('d/m/Y H:i:s')." - QUITAÇÃO";

            $idImploded = implode(",", $arr_quitar_id);

            $stringUpdate = "UPDATE " . $this->table . " SET status='Quitado', data_quitacao='" . $data_quitacao . "', alteracoes=CONCAT(alteracoes, '" . $alteracoes . "') WHERE id IN (" . $idImploded . ")";
            
            self::db()->query($stringUpdate);

            return self::db()->errorInfo();
        }
    }

    public function excluirChecados($request) {

        $checados = $request["checados"];
        
        if (!empty($checados)) {
            
            $ipcliente = $this->permissoes->pegaIPcliente();
            $alteracoes = " | " . ucwords($_SESSION["nomeUsuario"])." - $ipcliente - ".date('d/m/Y H:i:s')." - EXCLUSÃO";

            $idImploded = implode(",", $checados);

            $stringUpdate = "UPDATE " . $this->table . " SET situacao='excluido', alteracoes=CONCAT(alteracoes, '" . $alteracoes . "') WHERE id IN (" . $idImploded . ")";
            
            self::db()->query($stringUpdate);

            return self::db()->errorInfo();
        }
    }

    public function inlineEdit($request) {
        
        $id = $request["id"];
        $valor_total = $request["valor_total"];
        $data_vencimento = $request["data_vencimento"];
        $observacao = $request["observacao"];

        $dtaux = explode("/", $data_vencimento);
        if (count($dtaux) == 3) {
            $data_vencimento = $dtaux[2] . "-" . $dtaux[1] . "-" . $dtaux[0];
        }

        $valor_total = preg_replace('/\./', '', $valor_total);
        $valor_total = preg_replace('/\,/', '.', $valor_total);

        if (!empty($id)) {

            $ipcliente = $this->permissoes->pegaIPcliente();
            $hist = explode("##", addslashes($request['alteracoes']));
            $alteracoes = $hist[0] . " | " . ucwords($_SESSION["nomeUsuario"]) . " - $ipcliente - " . date('d/m/Y H:i:s') . " - ALTERAÇÃO >> " . $hist[1];

            $stringUpdate = "UPDATE " . $this->table . " SET valor_total='" . $valor_total . "', data_vencimento='" . $data_vencimento . "', observacao='" . $observacao . "', alteracoes=CONCAT(alteracoes, '" . $alteracoes . "') WHERE id=" . $id;

            self::db()->query($stringUpdate);

            return self::db()->errorInfo();
        }
    }

    public function adicionar($request) {
        // print_r($request); exit;
        $ipcliente = $this->permissoes->pegaIPcliente();

        $sql = '';
        foreach ($request as $linha => $arrayRegistro) {

            $arrayRegistro["alteracoes"] = ucwords($_SESSION["nomeUsuario"])." - $ipcliente - ".date('d/m/Y H:i:s')." - CADASTRO";
            $arrayRegistro["situacao"] = "ativo";
            
            $keys = implode(",", array_keys($arrayRegistro));
            $values = "'" . implode("','", array_values($this->shared->formataDadosParaBD($arrayRegistro))) . "'";
            
            $sql .= "INSERT INTO " . $this->table . " (" . $keys . ") VALUES (" . $values . ");";               
        }
        // echo $sql; exit;
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

    public function receitasDespesas($infoConsulta){
        
        $array = array();
        if(!empty($infoConsulta) && isset($infoConsulta)){
            $data1 = '';
            $data2 = '';
            $tabela = addslashes(trim($infoConsulta['tabela']));
            $campo = addslashes(trim($infoConsulta['campo']));
            $dataInicio = addslashes(trim($infoConsulta['dataInicio']));
            $dataInicio = explode("/",$dataInicio);
            $data1 = $dataInicio[2]."-".$dataInicio[1]."-".$dataInicio[0];
            
            if( $dataInicio[1] == '2'){
                if( is_int(intval($dataInicio[2]) / intval(4)) ){
                    $data2 = $dataInicio[2]."-".$dataInicio[1]."-29";
                }else{
                    $data2 = $dataInicio[2]."-".$dataInicio[1]."-28";
                }
            }else if($dataInicio[1] == '1' || $dataInicio[1] == '3' || $dataInicio[1] == '5'  ||
                     $dataInicio[1] == '7' || $dataInicio[1] == '8' || $dataInicio[1] == '10' || $dataInicio[1] == '12'){

                $data2 = $dataInicio[2]."-".$dataInicio[1]."-31";

            }else{

                $data2 = $dataInicio[2]."-".$dataInicio[1]."-30";
            }

            $sql1 = "SELECT SUM(valor_total) as despesatotal FROM fluxocaixa WHERE despesa_receita = 'Despesa' AND data_quitacao BETWEEN '$data1' AND '$data2'";
            $sql1 = self::db()->query($sql1);

            if($sql1->rowCount() > 0){  
                $sql1 = $sql1->fetch();
                $despesa = floatval($sql1['despesatotal']);
            }else{
                $despesa = floatval(0);
            }    

            $sql2 = "SELECT SUM(valor_total) as receitatotal FROM fluxocaixa WHERE despesa_receita = 'Receita' AND data_quitacao BETWEEN '$data1' AND '$data2'";
            $sql2 = self::db()->query($sql2);

            if($sql2->rowCount() > 0){  
                $sql2 = $sql2->fetch();
                $receita = floatval($sql2['receitatotal']);
            }else{
                $receita = floatval(0);
            }    

            $resultado = floatval($receita - $despesa);
            $array['Receita'] = $receita;
            $array['Despesa'] = $despesa;
            $array['Resultado'] = $resultado;

        }
        return $array;
    }

    public function buscaDespId($request){
        
        $array = array();
        if(!empty($request) && isset($request)){
            
            $idOrdemServico = addslashes(trim($request['idOrdemServico']));
            $idOrcamento = addslashes(trim($request['idOrcamento']));

            // busca as despesas relacionadas a O.S. lançadas no fluxo de caixa
            $sql1 = "SELECT SUM(valor_total) as despesatotal FROM fluxocaixa WHERE despesa_receita = 'Despesa' AND situacao = 'ativo' AND nro_pedido = '$idOrdemServico'";
            $sql1 = self::db()->query($sql1);

            if($sql1->rowCount() > 0){  
                $sql1 = $sql1->fetch();
                $despesaIdFluxoCaixa = floatval($sql1['despesatotal']);
            }else{
                $despesaIdFluxoCaixa = floatval(0);
            }
            
            // echo '  despesaCaixa: '.$despesaIdFluxoCaixa; 
            // busca o custo total do orçamento
            $sql2 = "SELECT custo_total FROM orcamentos WHERE situacao = 'ativo' AND id = '$idOrcamento'";
            $sql2 = self::db()->query($sql2);

            if($sql2->rowCount() > 0){  
                $sql2 = $sql2->fetch();
                $custoTotalOrcamento = floatval($sql2['custo_total']);
            }else{
                $custoTotalOrcamento = floatval(0);
            }    

            // echo '  custo orçamento: '.$custoTotalOrcamento; 
            // busca o custo total da O.S. para saber se as despesa já foram incrementadas
            $sql3 = "SELECT custo_total FROM ordemservico WHERE situacao = 'ativo' AND id = '$idOrdemServico'";
            $sql3 = self::db()->query($sql3);

            if($sql3->rowCount() > 0){  
                $sql3 = $sql3->fetch();
                $custoTotalOS = floatval($sql3['custo_total']);
            }else{
                $custoTotalOS = floatval(0);
            }    
            
            // echo '  custo os: '.$custoTotalOS;

            if( $custoTotalOrcamento == $custoTotalOS ){
                // se o custo da OS tá igual ao custo do orçamento, verifica se a despesa é diferente de 0, e retorna ela pro ajax pra ser somada na view da OS
                if( $despesaIdFluxoCaixa > floatval(0) ){
                    // cusutos iguais e despesa maior que zero
                    $array['DespesaId'] = $despesaIdFluxoCaixa;

                }else{
                    // custos iguais e despsa menor ou igual a zero
                    $array['DespesaId'] = floatval(0);
                }

            }else{
                // se os custos são diferentes, verificar se a diferença é igual a despesa vinda do fluxo de caixa, se sim não precisa enviar despesa, se não for, mandar a diferença de despesas
                $diferencaCustos = abs( round( round($custoTotalOS , 2) - round( $custoTotalOrcamento, 2) , 2) );
                // echo '  diferença entre os custos:  '.$diferencaCustos; 
                if( $diferencaCustos > floatval(0) ){
                    
                    $difCustoDespesa = round( abs( round( $despesaIdFluxoCaixa, 2) - $diferencaCustos ) , 2); 
                    
                    // echo '  diferença entre os custos e despesa:  '.$difCustoDespesa;

                    if( $difCustoDespesa > floatval(0) ){
                        //diferença de custos maior que zero e a diferença de despesasFluxo e diferença entre os custos é maior do que zero, significa que o custo já foi incrementado antes e foi lançado algum custo novo no fluxo de caixa referente a OS
                        $array['DespesaId'] = $difCustoDespesa;

                    }else{
                        //diferença de custos maior que zero e a diferença de despesasFluxo e diferença entre os custos é maior do que zero, significa que o custo já foi incrementado antes e não precisa ser atualizado
                        $array['DespesaId'] = floatval(0);
                    }
                    

                }else{
                    //diferença de custos menor ou igual a zero, significa que o custo não foi incrementado antes e não precisa ser atualizado
                    $array['DespesaId'] = floatval(0);
                }
            }
        }

        // echo '  despesa id retornada:  '.$array['DespesaId']; exit;
        return $array;
    }

    public function excluiRegistroFluxo($request){
        
        $array = array();
        if(!empty($request) && isset($request)){
            // print_r($request); exit;
            $idOrdemServico = addslashes(trim($request['idOrdemServico']));
            $tituloOrcamento = addslashes(trim($request['tituloOrcamento']));

            // busca as despesas relacionadas a O.S. lançadas no fluxo de caixa
            $sql1 = "DELETE FROM fluxocaixa WHERE detalhe LIKE '%$tituloOrcamento%' AND despesa_receita = 'Receita' AND conta_analitica = 'Venda' AND nro_pedido = '$idOrdemServico' AND situacao = 'ativo'";
            
            $sql1 = self::db()->query($sql1);
            
            $erro = self::db()->errorInfo();
            if (empty($erro[2])){

                $array['exclusaoReceita'] = true;

            } else {
                $array['exclusaoReceita'] = false;
            }

            // busca as despesas relacionadas a O.S. lançadas no fluxo de caixa
            $sql2 = "DELETE FROM fluxocaixa WHERE detalhe LIKE '%$tituloOrcamento%' AND despesa_receita = 'Despesa' AND conta_analitica = 'Despesa Financeira' AND nro_pedido = '$idOrdemServico' AND situacao = 'ativo'";
            
            $sql2 = self::db()->query($sql2);
            
            $erro = self::db()->errorInfo();
            if (empty($erro[2])){

                $array['exclusaoDespesa'] = true;

            } else {
                $array['exclusaoDespesa'] = false;
            }
        }        
        return $array;
    }

    public function buscaVencidos($interval_datas){
        
        $array = array();
        if(!empty($interval_datas) && isset($interval_datas)){
            
            if(count($interval_datas) == 1){
                $dt1 = 	$interval_datas[0];
                $dt2 =  $interval_datas[0];
            }else{
                $dt1 = $interval_datas[0];
                $dt2 = $interval_datas[count($interval_datas)-1];
            }
            // busca as despesas relacionadas a O.S. lançadas no fluxo de caixa
            $sql1 = "SELECT `despesa_receita`, `conta_analitica`, `detalhe`, `valor_total`, `data_vencimento` FROM `fluxocaixa` WHERE `situacao` = 'ativo' AND `status` = 'A Quitar' AND `data_vencimento` < NOW() ORDER BY `data_vencimento`  ASC";

            $sql1 = self::db()->query($sql1);
            $vencidas = array();

            if($sql1->rowCount() > 0){  
                $vencidas = $sql1->fetchAll(PDO::FETCH_ASSOC);
            }
            
            // busca o custo total do orçamento
            $sql2 = "SELECT `despesa_receita`, `conta_analitica`, `detalhe`, `valor_total`, `data_vencimento` FROM `fluxocaixa` WHERE `situacao` = 'ativo' AND `status` = 'A Quitar' AND `data_vencimento` BETWEEN '$dt1' AND '$dt2' ORDER BY `data_vencimento`  ASC";

            $sql2 = self::db()->query($sql2);

            $proximo = array();

            if($sql2->rowCount() > 0){  
                $proximo = $sql2->fetchAll(PDO::FETCH_ASSOC);
            }

        }

        $array['vencidas'] = $vencidas;
        $array['proximo'] = $proximo;

       return $array;
    }
}