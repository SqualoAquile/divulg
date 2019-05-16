<link href="<?php echo BASE_URL;?>/assets/css/lancamentos.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo BASE_URL;?>/assets/js/lancamentos.js" type="text/javascript"></script>
<script type="text/javascript">var baselink = '<?php echo BASE_URL;?>'</script>

<h1 class="titulo_lc">LANÇAMENTOS FLUXO DE CAIXA</h1>



    
    <div class="input-pai">
        <div class="input-filho">
            <div class="radio-50">
                <label for="idespesa"><input type="radio" id="idespesa" name="movimentacao" value="despesa"/>Despesa</label>
            </div> 
            <div class="radio-50">
                <label for="ireceita"><input type="radio" id="ireceita" name="movimentacao" value="receita" />Receita</label>
            </div>
            <input type="button" value="Limpar Campos" class="botao_lcAux" onclick="limparPreenchimento()"/><br/>
        </div>
    </div>
    <div class="input-pai">
        <div class="input-filho">
            <input type="text" name="dtop" onfocus="(this.type='date')" onblur="if(this.value==''){this.type='text'}" id="idtop" placeholder="Data da Operação" class="input-block" />
        </div>
        <div class="input-filho">
            <input type="text" name="nropedido" id="inropedido" placeholder="Número do pedido" class="input-block"/>
        </div>
    </div>
    <div class="input-pai">
        <div class="input-filho">
           <select id="isintetica" name="sintetica"  class="select-block">
               <option value="" selected >Conta Sintética</option>
           </select>
        </div>
        <div class="input-filho">
            <select id="ianalitica" name="analitica"  class="select-block">
               <option value="" selected >Conta Analítica</option>
           </select>
        </div>
    </div>
    <div class="input-pai">
        <div class="input-filho">
            <input type="text" name="detalhe" id="idetalhe" placeholder="Descrição do Lançamento" class="input-block"/>
        </div>
        <div class="input-filho">
            <input type="text" name="favorecido" id="ifavorecido" placeholder="Favorecido do Lançamento" class="input-block"/>
        </div>
    </div>
    <div class="input-pai">
        <div class="input-filho">
            <input type="text" name="valortotal" id="ivalortotal" placeholder="Valor Total" class="input-block"/>
        </div>
        <div class="input-filho">
            <select id="icc" name="cc" class="select-block">
                <option value="" selected disabled>Conta Corrente</option>
                    <?php foreach ($listaContasCorrentes as $cc):?>
                        <option value="<?php echo $cc["id"]?>" ><?php echo ucwords($cc["nome"]);?></option>
                    <?php endforeach;?>
            </select>
        </div>
    </div>
    <div class="input-pai">
        <div class="input-filho">
            <select id="iformapgto" name="formapgto"  class="select-block">
                <option value="" selected disabled>Forma de Pagamento</option>
                <?php foreach ($listaFormasPgto as $fp):?>
                <option value="<?php echo $fp["id"]?>" ><?php echo utf8_encode(ucwords($fp["nome"]));?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-filho">
            <select id="icondpgto" name="condpgto"  class="select-block">
                <option value="" selected >Condição de Pagamento</option>
            </select>
        </div>
    </div>
    <div class="input-pai" id="infocartao">
        <div class="input-filho">
            <select id="iadmcartao" name="admcartao"  class="select-block">
                <option value="" selected disabled>Administradora de Cartão</option>
                <?php foreach ($listaAdministradoras as $adm):?>
                      <option value="<?php echo $adm["id"]?>" ><?php echo ucwords($adm["nome"]);?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-filho">
            <select id="ibandeira" name="bandeira"  class="select-block">
                <option value="" selected disabled>Bandeira</option>
                
            </select>
        </div>
    </div>
    <div class="input-pai" id="infoparcela">
        <div class="input-filho">
            <select id="inroparcela" name="nroparcela"  class="select-block">
                <option value="" selected disabled>Núm. Parcelas</option>
                <?php for($i=0; $i<=12; $i++ ):?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php endfor;?>
            </select>
        </div>
        <div class="input-filho">
            <select id="idiavenc" name="diavenc"  class="select-block">
                <option value="" selected disabled>Dia Vencimento</option>
                <?php for($i=1; $i<=31; $i++ ):?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php endfor;?>
            </select>
        </div>
    </div>
    <div class="input-pai" id="infocusto">
        <div class="input-filho">
            <input type="text" name="txcobrada" id="itxcobrada" placeholder="Taxa Total Cobrada" class="input-block"/>
        </div>
        <div class="input-filho">
            <input type="text" name="custofin" id="icustofin" placeholder="Custo Total Operação Financeira" class="input-block"/>
        </div>
    </div>
    <div class="input-pai">
        <div class="input-filho">
            <input type="text" name="observ" id="iobserv" placeholder="Observação do Lançamento" class="input-block"/>
        </div>
    </div>
    
    <div class="input-pai">
        <div class="input-filho">
            <input type="button" value="Incluir" class="botao_lcAux" onclick="confirmaPreenchimento()"/>
        </div>
    </div>

    <div class="edicaoinfo">
        <div class="input-pai" id="editainfo">
            <div class="input-pai">
                <div class="input-filho"><label class="tituloEdicao">Editar Informações do Item</label></div>
                <div class="input-filho" id="btns-edita">
                    <input type="button" value="Editar Item" class="botao_lcAux" onclick="confirmaEdicao()" id="btn-edita1"/>
                    <input type="button" value="Cancelar Edição" class="botao_lcAux" onclick="cancelaEdicao()" id="btn-edita2"/>
                </div>
            </div>
            <div class="input-pai">
                <div class="input-filho">
                    <label for="idtvencaux"><b>Data de Vencimento do Item:</b> 
                        <input type="text" name="dtvencaux" id="idtvencaux" data-dtop="" onfocus="(this.type='date')" onblur="if(this.value==''){this.type='text'}"                                                         placeholder="Data de Vencimento do Item" class="input-block" />
                    </label>
                </div>
                <div class="input-filho">           
                    <label for="ivtotaux"><b>Valor Total do Item:</b> <input type="text" name="vtotaux" id="ivtotaux" placeholder="Valor Total do Item" class="input-block"/></label>
                </div>
            </div>
            <div class="input-pai">
                <div class="input-filho">
                    <label for="iobservaux"><b>Observação do Item:</b> <input type="text" name="observaux" id="iobservaux" placeholder="Observação do Item" class="input-block"/></label>
                </div>
            </div>
            <input type="hidden" name="linhaid" id="ilinhaid" value=""/>
        </div>
    </div>    
    <div class="itenscaixa">   
        <div class="input-pai"><div class="input-filho"><label class="label-cabecalho">Composição do Lançamento</label></div></div>  

        <form method="POST">    
            <table class="table_add" id="tabelaenvio">
                <thead>
                    <th>Ação</th>
                    <th>Descrição Lançamto.</th>
                    <th>Valor Total</th>
                    <th>Forma Pagamento.</th>
                    <th>Cond. Pgto.</th>
                    <th>Parcelas</th>
                    <th>Data Venc.</th>
                    <th>Valor Pago</th>
                    <th>Data Quit.</th>
                    <th>Status</th>
                    <th>Data Oper.</th>
                    <th>Nro. Pedido</th>
                    <th>Conta</th>
                    <th>Conta Sintética</th>
                    <th>Conta Analítica</th>
                    <th>Conta Corrente</th>
                    <th>Bandeira</th>
                    <th>Favorecido</th>           
                    <th>Observação</th>
                </thead>
                <tbody>

                </tbody>    
            </table>  
            
            <div class="input-pai">
                <div class="input-pai"><div class="input-filho"><label class="resumo">Resumo do Lançamento</label></div></div>
                <div class="input-pai"><div class="input-filho"><label class="rtitulo" id="ireceita"><i>Receita  (R$):</i>  0,00</label></div></div>    
                <div class="input-pai"><div class="input-filho"><label class="rtitulo" id="idespesa"><i>Despesa (R$):</i>  0,00</label></div></div>                                      
                <div class="input-pai"><div class="input-filho"><label class="resumo" id="itotal"  ><i>Total   (R$):</i>  0,00</label></div></div>                                       
            </div>

            <input type="submit" value="Lançar" class="botao_lc" onclick="testeEnvio()"/>
        </form>
   </div>