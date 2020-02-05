<?php
class Relatoriofluxocaixa extends model {

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

        $ipcliente = $this->permissoes->pegaIPcliente();

        $sql = '';
        foreach ($request as $linha => $arrayRegistro) {

            $arrayRegistro["alteracoes"] = ucwords($_SESSION["nomeUsuario"])." - $ipcliente - ".date('d/m/Y H:i:s')." - CADASTRO";
            $arrayRegistro["situacao"] = "ativo";
            
            $keys = implode(",", array_keys($arrayRegistro));
            $values = "'" . implode("','", array_values($this->shared->formataDadosParaBD($arrayRegistro))) . "'";
            
            $sql .= "INSERT INTO " . $this->table . " (" . $keys . ") VALUES (" . $values . ");";               
        }
        
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
                if( gmp_div_r(intval($dataInicio[2]), intval(4)) == intval(0) ){
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

    public function meta() {
        $retorno = [];

        $sql = "SELECT valor as valor FROM parametros WHERE parametro='meta'";
        $sql = self::db()->query($sql);

        if($sql->rowCount()>0){
            $sql = $sql->fetch();
            $meta = $sql['valor'];
            $meta1 = strval($meta);
            $meta = str_replace(".","",$meta);
            $meta = floatval($meta);
        }else{
            $meta = floatval(0);
        }

        $mes = date('m');
        $ano = date('Y');
        
        // último dia do mês atual
        $dia = date("t");

        $dt1 = $ano . '-' . $mes . '-01';
        $dt2 = $ano . '-' . $mes . '-' . $dia;

        $sql = 
        "SELECT SUM(valor_total) as valor 
        FROM fluxocaixa 
        WHERE despesa_receita = 'Receita' 
        AND status = 'Quitado' AND situacao='ativo' 
        AND data_quitacao BETWEEN '$dt1' AND '$dt2' 
        ";

        $sql = self::db()->query($sql);

        if($sql->rowCount()>0){
            $sql = $sql->fetch();
            $atingido = $sql['valor'];          
            $atingido1 = strval(number_format(floatval($atingido),2,",","."));
            $atingido = floatval($atingido);
        }else{
            $atingido = floatval(0);
            $atingido1 = floatval(0);
        }

        if ($meta>0 and $atingido>0){
            $metaAtingida = floatval($atingido)/floatval($meta);
            $metaAtingida = floatval($metaAtingida)*100;
        }else{
            $metaAtingida = floatval(0);
        }

        $metaAtingida = round($metaAtingida);
        $meta1 = '11';
        $retorno[0] = $meta1;                   //meta
        $retorno[1] = $atingido1;               //faturamento atingido
        $retorno[2] = $metaAtingida;            //porcentagem da meta atingida
        $retorno[3] = number_format(($meta - $atingido),2,",",".");        //diferença
        $retorno[4] = date("t") - date("j");    //dias que faltam pra acabar o mês
        if ( $retorno[4] > 0  ){
            $retorno[5] = number_format((($meta - $atingido)/$retorno[4]),2,",",".");  //faturamento médio para atingir a média
        }else{
            $retorno[5] = number_format((($meta - $atingido)),2,",",".");  //faturamento médio para 
        }
        
        return $retorno;
    }

    public function graficoFluxoCaixaRealizado($interval_datas){
        
        if(count($interval_datas) == 1){
			$dt1 = 	$interval_datas[0];
			$dt2 =  $interval_datas[0];
		}else{
			$dt1 = $interval_datas[0];
			$dt2 = $interval_datas[count($interval_datas)-1];
		}
     
        $sql1 = "SELECT data_quitacao , SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Despesa' AND status = 'Quitado' AND data_quitacao BETWEEN '$dt1' AND '$dt2' GROUP BY data_quitacao";
        
        $sql1 = self::db()->query($sql1);
        
        if($sql1->rowCount()>0){
            $despesaBruta = $sql1->fetchAll();
        }else{
            $despesaBruta = array();
        }

        $despesas = array();
        if (count($despesaBruta) > 0 ){
            for($i = 0; $i < count($despesaBruta); $i++){
                for($j = 0; $j < count($interval_datas); $j++ ){
                    if($interval_datas[$j] == $despesaBruta[$i][0]){
                        
                        $despesas[$interval_datas[$j]] = floatval($despesaBruta[$i][1]);
                    }
                }
            }

        }else{

            for($j = 0; $j < count($interval_datas); $j++ ){
                $despesas[$interval_datas[$j]] = floatval(0);
            }
        }

		// Verifica se as datas do $interval_datas existem no array $despesas
		for($j = 0; $j < count($interval_datas); $j++ ){
			if (array_key_exists($interval_datas[$j],$despesas) == 0){
				$despesas[$interval_datas[$j]] = 0;
			}
        }

		// Ordena o array pela ordem das keys
		ksort($despesas);

		//reescreve as chave do array , para datas no padrão brasileiro
		foreach ($despesas as $key => $value) {
			$aux = explode('-',$key);
			$aux = $aux[2].'/'.$aux[1].'/'.$aux[0];
			unset($despesas[$key]);
			$despesas[$aux] = $value;
			
        }

        ///// RECEITAS

        $sql2 = "SELECT data_quitacao , SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Receita' AND status = 'Quitado' AND data_quitacao BETWEEN '$dt1' AND '$dt2' GROUP BY data_quitacao";
        
        $sql2 = self::db()->query($sql2);
        
        if($sql2->rowCount()>0){
            $receitaBruta = $sql2->fetchAll();
        }else{
            $receitaBruta = array();
        }

        $receitas = array();
        if (count($receitaBruta) > 0 ){
            for($i = 0; $i < count($receitaBruta); $i++){
                for($j = 0; $j < count($interval_datas); $j++ ){
                    if($interval_datas[$j] == $receitaBruta[$i][0]){
                        
                        $receitas[$interval_datas[$j]] = floatval($receitaBruta[$i][1]);
                    }
                }
            }

        }else{

            for($j = 0; $j < count($interval_datas); $j++ ){
                $receitas[$interval_datas[$j]] = floatval(0);
            }
        }

		// Verifica se as datas do $interval_datas existem no array $despesas
		for($j = 0; $j < count($interval_datas); $j++ ){
			if (array_key_exists($interval_datas[$j],$receitas) == 0){
				$receitas[$interval_datas[$j]] = 0;
			}
        }

		// Ordena o array pela ordem das keys
		ksort($receitas);

		//reescreve as chave do array , para datas no padrão brasileiro
		foreach ($receitas as $key => $value) {
			$aux = explode('-',$key);
			$aux = $aux[2].'/'.$aux[1].'/'.$aux[0];
			unset($receitas[$key]);
			$receitas[$aux] = $value;
			
        }

		$data = array();
		$data[0] = $despesas;
        $data[1] = $receitas;
        
        // print_r($data); exit;
		return $data; 
		
    }
    
    public function graficoFluxoCaixaPrevisto($interval_datas){
        
        if(count($interval_datas) == 1){
			$dt1 = 	$interval_datas[0];
			$dt2 =  $interval_datas[0];
		}else{
			$dt1 = $interval_datas[0];
			$dt2 = $interval_datas[count($interval_datas)-1];
		}
     
        $sql1 = "SELECT data_vencimento , SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Despesa' AND status = 'A Quitar' AND data_vencimento BETWEEN '$dt1' AND '$dt2' GROUP BY data_vencimento";
        
        $sql1 = self::db()->query($sql1);
        
        if($sql1->rowCount()>0){
            $despesaBruta = $sql1->fetchAll();
        }else{
            $despesaBruta = array();
        }

        $despesas = array();
        if (count($despesaBruta) > 0 ){
            for($i = 0; $i < count($despesaBruta); $i++){
                for($j = 0; $j < count($interval_datas); $j++ ){
                    if($interval_datas[$j] == $despesaBruta[$i][0]){
                        
                        $despesas[$interval_datas[$j]] = floatval($despesaBruta[$i][1]);
                    }
                }
            }

        }else{

            for($j = 0; $j < count($interval_datas); $j++ ){
                $despesas[$interval_datas[$j]] = floatval(0);
            }
        }

		// Verifica se as datas do $interval_datas existem no array $despesas
		for($j = 0; $j < count($interval_datas); $j++ ){
			if (array_key_exists($interval_datas[$j],$despesas) == 0){
				$despesas[$interval_datas[$j]] = 0;
			}
        }

		// Ordena o array pela ordem das keys
		ksort($despesas);

		//reescreve as chave do array , para datas no padrão brasileiro
		foreach ($despesas as $key => $value) {
			$aux = explode('-',$key);
			$aux = $aux[2].'/'.$aux[1].'/'.$aux[0];
			unset($despesas[$key]);
			$despesas[$aux] = $value;
			
        }

        ///// RECEITAS

        $sql2 = "SELECT data_vencimento , SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Receita' AND status = 'A Quitar' AND data_vencimento BETWEEN '$dt1' AND '$dt2' GROUP BY data_vencimento";
        
        $sql2 = self::db()->query($sql2);
        
        if($sql2->rowCount()>0){
            $receitaBruta = $sql2->fetchAll();
        }else{
            $receitaBruta = array();
        }

        $receitas = array();
        if (count($receitaBruta) > 0 ){
            for($i = 0; $i < count($receitaBruta); $i++){
                for($j = 0; $j < count($interval_datas); $j++ ){
                    if($interval_datas[$j] == $receitaBruta[$i][0]){
                        
                        $receitas[$interval_datas[$j]] = floatval($receitaBruta[$i][1]);
                    }
                }
            }

        }else{

            for($j = 0; $j < count($interval_datas); $j++ ){
                $receitas[$interval_datas[$j]] = floatval(0);
            }
        }

		// Verifica se as datas do $interval_datas existem no array $despesas
		for($j = 0; $j < count($interval_datas); $j++ ){
			if (array_key_exists($interval_datas[$j],$receitas) == 0){
				$receitas[$interval_datas[$j]] = 0;
			}
        }

		// Ordena o array pela ordem das keys
		ksort($receitas);

		//reescreve as chave do array , para datas no padrão brasileiro
		foreach ($receitas as $key => $value) {
			$aux = explode('-',$key);
			$aux = $aux[2].'/'.$aux[1].'/'.$aux[0];
			unset($receitas[$key]);
			$receitas[$aux] = $value;
			
        }

		$data = array();
		$data[0] = $despesas;
        $data[1] = $receitas;
        
        // print_r($data); exit;
		return $data; 
		
    }

    
    public function CardsDashBoardFinanceiro($request){
        // print_r($request); exit;
        $intervaloVar = $request['intervaloVar'];
        $intervaloFix = $request['intervaloFix'];

        if(count($intervaloVar) == 1){
			$dt1 = 	$intervaloVar[0];
            $dt2 =  $intervaloVar[0];
            $dt3 =  $intervaloVar[0];
		}else{
			$dt1 = $intervaloVar[0];
            $dt2 = $intervaloVar[count($intervaloVar)-9];
            $dt3 = $intervaloVar[count($intervaloVar)-1];
        }
        
        if(!empty($intervaloFix)){
            $d0m0 = $intervaloFix[0];
            $d10m0 = $intervaloFix[1];
            $d25m0 = $intervaloFix[2];
            $d10m1 = $intervaloFix[3];
            $d25m1 = $intervaloFix[4];
            $d10m2 = $intervaloFix[5];
            $d25m2 = $intervaloFix[6];
		}
        // echo $dt1." -- ".$dt2." -- ".$dt3." -- ".$dt1F." -- ".$dt2F." -- ".$dt3F." -- ".$dt4F." -- ".$dt5F; exit;
        // Despesas //////////
        $despesas = array();

        //previsto de hoje
        $sql1 = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Despesa' AND status = 'A Quitar' AND data_vencimento BETWEEN '$dt1' AND '$dt1'";

        $sql1 = self::db()->query($sql1);
        
        if($sql1->rowCount()>0){
            $aux = $sql1->fetch();
            $despesas['d0'] = floatval( $aux[0] );
        }else{
            $despesas['d0'] = floatval(0);
        }

        //previsto de 7 pra frente
        $sql2 = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Despesa' AND status = 'A Quitar' AND data_vencimento BETWEEN '$dt1' AND '$dt2'";
        
        
        $sql2 = self::db()->query($sql2);
        
        if($sql2->rowCount()>0){
            $aux = $sql2->fetch();
            $despesas['d7'] = floatval( $aux[0] );
        }else{
            $despesas['d7'] = floatval(0);
        }

        //previsto de 15 pra frente
        $sql3 = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Despesa' AND status = 'A Quitar' AND data_vencimento BETWEEN '$dt1' AND '$dt3'";
        
        $sql3 = self::db()->query($sql3);
        
        if($sql3->rowCount()>0){
            $aux = $sql3->fetch();
            $despesas['d15'] = floatval( $aux[0] );
        }else{
            $despesas['d15'] = floatval(0);
        }

        //previsto ate dia 10 mês vigente
        $sql4 = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Despesa' AND status = 'A Quitar' AND data_vencimento BETWEEN '$d0m0' AND '$d10m0'";
        
        $sql4 = self::db()->query($sql4);
        
        if($sql4->rowCount()>0){
            $aux = $sql4->fetch();
            $despesas['d10m0'] = floatval( $aux[0] );
        }else{
            $despesas['d10m0'] = floatval(0);
        }

        //previsto ate dia 25 do mês vigente
        $sql5 = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Despesa' AND status = 'A Quitar' AND data_vencimento BETWEEN '$d0m0' AND '$d25m0'";
        
        $sql5 = self::db()->query($sql5);
        
        if($sql5->rowCount()>0){
            $aux = $sql5->fetch();
            $despesas['d25m0'] = floatval( $aux[0] );
        }else{
            $despesas['d25m0'] = floatval(0);
        }
        
        //previsto ate dia 10 do mês seguinte
        $sql6 = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Despesa' AND status = 'A Quitar' AND data_vencimento BETWEEN '$d0m0' AND '$d10m1'";
        
        $sql6 = self::db()->query($sql6);
        
        if($sql6->rowCount()>0){
            $aux = $sql6->fetch();
            $despesas['d10m1'] = floatval( $aux[0] );
        }else{
            $despesas['d10m1'] = floatval(0);
        }

        //previsto ate dia 25 do mês seguinte
        $sql7 = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Despesa' AND status = 'A Quitar' AND data_vencimento BETWEEN '$d0m0' AND '$d25m1'";
        
        $sql7 = self::db()->query($sql7);
        
        if($sql7->rowCount()>0){
            $aux = $sql7->fetch();
            $despesas['d25m1'] = floatval( $aux[0] );
        }else{
            $despesas['d25m1'] = floatval(0);
        }

        //previsto ate dia 10 dois meses pra frente
        $sql8 = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Despesa' AND status = 'A Quitar' AND data_vencimento BETWEEN '$d0m0' AND '$d10m2'";
        
        $sql8 = self::db()->query($sql8);
        
        if($sql8->rowCount()>0){
            $aux = $sql8->fetch();
            $despesas['d10m2'] = floatval( $aux[0] );
        }else{
            $despesas['d10m2'] = floatval(0);
        }

        //previsto ate dia 25 dois meses pra frente
        $sql8 = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Despesa' AND status = 'A Quitar' AND data_vencimento BETWEEN '$d0m0' AND '$d25m2'";
        
        $sql8 = self::db()->query($sql8);
        
        if($sql8->rowCount()>0){
            $aux = $sql8->fetch();
            $despesas['d25m2'] = floatval( $aux[0] );
        }else{
            $despesas['d25m2'] = floatval(0);
        }

        // print_r($despesas); exit;

        ///// RECEITAS ////////////////

        $receitas = array();

        //previsto de hoje
        $sql1r = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Receita' AND status = 'A Quitar' AND data_vencimento BETWEEN '$dt1' AND '$dt1'";

        $sql1r = self::db()->query($sql1r);
        
        if($sql1r->rowCount()>0){
            $aux = $sql1r->fetch();
            $receitas['d0'] = floatval( $aux[0] );
        }else{
            $receitas['d0'] = floatval(0);
        }

        //previsto de 7 dias pra frente
        $sql2r = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Receita' AND status = 'A Quitar' AND data_vencimento BETWEEN '$dt1' AND '$dt2'";
        
        $sql2r = self::db()->query($sql2r);
        
        if($sql2r->rowCount()>0){
            $aux = $sql2r->fetch();
            $receitas['d7'] = floatval( $aux[0] );
        }else{
            $receitas['d7'] = floatval(0);
        }

        //previsto de 15 dias pra frente
        $sql3r = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Receita' AND status = 'A Quitar' AND data_vencimento BETWEEN '$dt1' AND '$dt3'";
        
        $sql3r = self::db()->query($sql3r);
        
        if($sql3r->rowCount()>0){
            $aux = $sql3r->fetch();
            $receitas['d15'] = floatval( $aux[0] );
        }else{
            $receitas['d15'] = floatval(0);
        }

        //previsto ate dia 10 do mês vigente
        $sql4r = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Receita' AND status = 'A Quitar' AND data_vencimento BETWEEN '$d0m0' AND '$d10m0'";
        
        $sql4r = self::db()->query($sql4r);
        
        if($sql4r->rowCount()>0){
            $aux = $sql4r->fetch();
            $receitas['d10m0'] = floatval( $aux[0] );
        }else{
            $receitas['d10m0'] = floatval(0);
        }

        //previsto ate dia 25 do mês vigente
        $sql5r = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Receita' AND status = 'A Quitar' AND data_vencimento BETWEEN '$d0m0' AND '$d25m0'";
        
        $sql5r = self::db()->query($sql5r);
        
        if($sql5r->rowCount()>0){
            $aux = $sql5r->fetch();
            $receitas['d25m0'] = floatval( $aux[0] );
        }else{
            $receitas['d25m0'] = floatval(0);
        }

        //previsto ate dia 10 do mês seguinte
        $sql6r = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Receita' AND status = 'A Quitar' AND data_vencimento BETWEEN '$d0m0' AND '$d10m1'";
        
        $sql6r = self::db()->query($sql6r);
        
        if($sql6r->rowCount()>0){
            $aux = $sql6r->fetch();
            $receitas['d10m1'] = floatval( $aux[0] );
        }else{
            $receitas['d10m1'] = floatval(0);
        }

        //previsto ate dia 25 do mês seguinte
        $sql7r = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Receita' AND status = 'A Quitar' AND data_vencimento BETWEEN '$d0m0' AND '$d25m1'";
        
        $sql7r = self::db()->query($sql7r);
        
        if($sql7r->rowCount()>0){
            $aux = $sql7r->fetch();
            $receitas['d25m1'] = floatval( $aux[0] );
        }else{
            $receitas['d25m1'] = floatval(0);
        }

        //previsto ate dia 10 de dois meses pra frente
        $sql8r = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Receita' AND status = 'A Quitar' AND data_vencimento BETWEEN '$d0m0' AND '$d10m2'";
        
        $sql8r = self::db()->query($sql8r);
        
        if($sql8r->rowCount()>0){
            $aux = $sql8r->fetch();
            $receitas['d10m2'] = floatval( $aux[0] );
        }else{
            $receitas['d10m2'] = floatval(0);
        }

        //previsto ate dia 25 de dois meses pra frente
        $sql9r = "SELECT SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Receita' AND status = 'A Quitar' AND data_vencimento BETWEEN '$d0m0' AND '$d25m2'";
        
        $sql9r = self::db()->query($sql9r);
        
        if($sql9r->rowCount()>0){
            $aux = $sql9r->fetch();
            $receitas['d25m2'] = floatval( $aux[0] );
        }else{
            $receitas['d25m2'] = floatval(0);
        }

        // print_r($receitas); exit;
		$data = array();
		$data[0] = $despesas;
        $data[1] = $receitas;
        
        // print_r($data); exit;
		return $data; 
		
    }
    public function graficoReceitaDespesaAnalitica($interval_datas){
        
        if(count($interval_datas) == 1){
			$dt1 = 	$interval_datas[0];
			$dt2 =  $interval_datas[0];
		}else{
			$dt1 = $interval_datas[0];
			$dt2 = $interval_datas[count($interval_datas)-1];
		}
     
        $sql1 = "SELECT conta_analitica , SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Despesa' AND status = 'Quitado' AND data_quitacao BETWEEN '$dt1' AND '$dt2' GROUP BY conta_analitica";
        
        $sql1 = self::db()->query($sql1);
        
        if($sql1->rowCount()>0){
            $despesasAnaliticas = $sql1->fetchAll();
        }else{
            $despesasAnaliticas = array();
        }

        if (count($despesasAnaliticas) > 0 ){
            for($i = 0; $i < count($despesasAnaliticas); $i++){
                $despesas[$despesasAnaliticas[$i][0]] = floatval($despesasAnaliticas[$i][1]);        
            }
        }else{
            for($i = 0; $i < count($despesasAnaliticas); $i++){
                $despesas[$despesasAnaliticas[$i][0]] = floatval($despesasAnaliticas[$i][1]);        
            }
        }

        ///// RECEITAS

        $sql2 = "SELECT conta_analitica , SUM(valor_total) as total FROM `fluxocaixa` WHERE situacao='ativo' AND despesa_receita = 'Receita' AND status = 'Quitado' AND data_quitacao BETWEEN '$dt1' AND '$dt2' GROUP BY conta_analitica";

        $sql2 = self::db()->query($sql2);
        
        if($sql2->rowCount()>0){
            $receitasAnalitica = $sql2->fetchAll();
        }else{
            $receitasAnalitica = array();
        }

        if (count($receitasAnalitica) > 0 ){
            for($i = 0; $i < count($receitasAnalitica); $i++){
                $receitas[$receitasAnalitica[$i][0]] = floatval($receitasAnalitica[$i][1]);        
            }
        }else{
            for($i = 0; $i < count($receitasAnalitica); $i++){
                $receitas[$receitasAnalitica[$i][0]] = floatval($receitasAnalitica[$i][1]);        
            }
        }

		$data = array();
		$data[0] = $despesas;
        $data[1] = $receitas;
        
        // print_r($data); exit;
		return $data; 
		
    }
    

}