<style>
    #campos_tabela { list-style-type: none; margin: 1px; padding: 1px; width: 100%; }
    #campos_tabela li { margin: 0px; padding: 0.4em; padding-left: 1em; padding-right: 1em; font-size: 1em; height: auto; overflow-x: auto; white-space: nowrap;}
    input[type=checkbox] { width: 100%; height: auto;}
</style>
<script>
    var tabelasDB;
        tabelasDB = <?php echo json_encode($tabelasDB);?>;
        // console.log(tabelasDB);
</script>
<script src="<?php echo BASE_URL?>/assets/js/vendor/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL;?>/assets/js/create_table.js" type="text/javascript"></script>

<h2 class="mt-2 text-center bg-dark text-white">CRIAR Nova Tabela (falta fazer se a tabela for Parâmetro)</h2>
<!-- Cabeçalho de CRIAÇÃO da tabela - falta fazer se a tabela for parâmetro  e se tiver relatório -->
<!-- <div class="row mb-4">
    <div class="col-lg">
        <label for="nome_tabela" class="font-weight-bold" >* Nome da Tabela</label>
        <input 
            type="text" 
            class="form-control" 
            id="nome_tabela" 
            name="nome_tabela" 
            data-mascara_validacao="false"
            maxlength="40" 
            required
        >
    </div>
    <div class="col-lg">
        <div class="row">
            <div class="col-lg">
                <label for="lbl_brownser" class="font-weight-bold" >* Label Brownser</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="lbl_brownser" 
                    name="lbl_brownser" 
                    data-mascara_validacao="false"
                    maxlength="40" 
                    required
                >
            </div>
            <div class="col-lg">
                <label for="lbl_form" class="font-weight-bold" >* Label Form</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="lbl_form" 
                    name="lbl_form" 
                    data-mascara_validacao="false"
                    maxlength="40" 
                    required
                >
            </div>
        </div>
    </div>
    <div class="col-lg-2 d-flex align-self-end">
        <div id="btn_criar" class="btn btn-success btn-block">Criar</div>                
    </div>
</div> -->
<!-- Cabeçalho para EDIÇÃO da tabela -->
<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#criarTabela" aria-expanded="false" aria-controls="collapseExample">Criar Tabela</button>
    <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#editarTabela" aria-expanded="false" aria-controls="collapseExample">Editar Tabela</button>
</p>
<div class="collapse" id="criarTabela">
    <div class="card card-body">
        <div class="row mb-4">
        <div class="col-lg">
            <label for="nome_tabela" class="font-weight-bold" >* Nome da Tabela</label>
            <input 
                type="text" 
                class="form-control" 
                id="nome_tabela" 
                name="nome_tabela" 
                data-mascara_validacao="false"
                maxlength="40" 
                required
            >
        </div>
        <div class="col-lg">
            <div class="row">
                <div class="col-lg">
                    <label for="lbl_brownser" class="font-weight-bold" >* Label Brownser</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="lbl_brownser" 
                        name="lbl_brownser" 
                        data-mascara_validacao="false"
                        maxlength="40" 
                        required
                    >
                </div>
                <div class="col-lg">
                    <label for="lbl_form" class="font-weight-bold" >* Label Form</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="lbl_form" 
                        name="lbl_form" 
                        data-mascara_validacao="false"
                        maxlength="40" 
                        required
                    >
                </div>
            </div>
        </div>
        <div class="col-lg-2 d-flex align-self-end">
            <div id="btn_criar" class="btn btn-success btn-block">Criar</div>                
        </div>
    </div>
    </div>
</div>
<div class="collapse mb-3" id="editarTabela">
    <div class="card card-body">
        <div class="row">
            <div class="col-lg-6 mb-2">
                <label for="tabelas" class="font-weight-bold" >* Tabelas BD</label>
                <select id="tabelas" 
                        name="tabelas"
                        class="form-control"
                        data-anterior=""
                        data-mascara_validacao = "false"
                        required
                >
                    <option value="" selected >Selecione</option>
                    <?php foreach ($tabelasDB as $key => $value):?> 
                        <option value='<?php echo $key?>' ><?php echo ucfirst($key)?></option> 
                    <?php endforeach;?>      
                </select>
            </div>
            <div class="col-lg-3 d-flex align-self-center">
                <div id="btn_editar" class="btn btn-success btn-block">Editar</div>                
            </div>
            <div class="col-lg-3 d-flex align-self-center">
                <a href="<?php echo BASE_URL?>/modulo/adicionar" target="_blank" id="btn_form" class="btn btn-info btn-block">Ver Form</a>             
            </div>
        </div>
    </div>
</div>


<!-- Estrutura usada tanto para criação da tabela quanto para edição -->
<div class="row">
    <!-- formulário das configurações dos campos da tabela -->
    <div class="col-lg-3">        
        <div class="row">
            <div class="col-lg-12">
                <div id="form_tabela" class="row">
                    <div class="col-lg-6 mb-2">
                        <label for="nome_campo" class="font-weight-bold" >* Nome</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="nome_campo" 
                            name="nome_campo" 
                            data-mascara_validacao="false"
                            maxlength="40" 
                            required
                        >
                    </div>
                    <div class="col-lg-6 mb-2">
                        <label for="tipo_campo" class="font-weight-bold" >* Tipo Campo BD</label>
                        <select id="tipo_campo" 
                                name="tipo_campo"
                                class="form-control"
                                data-anterior=""
                                data-mascara_validacao = "false"
                                required
                        >
                            <option value="" selected >Selecione</option>
                            <option value="varchar" >Varchar</option>
                            <option value="float" >Float</option>
                            <option value="date" >Date</option>
                            <option value="int" >Int</option>
                            <option value="text" >Text</option>                        
                        </select>
                    </div>
                    <div class="col-lg-6 mb-2">
                        <label for="tamanho_campo" class="font-weight-bold" >* Tamanho</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="tamanho_campo" 
                            name="tamanho_campo" 
                            data-mascara_validacao="text"
                            maxlength="4" 
                            required
                        >
                    </div>
                    <div class="col-lg-6 mb-2">
                        <label for="label" class="font-weight-bold" >* Label</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="label" 
                            name="label" 
                            data-mascara_validacao="false"
                            maxlength="40" 
                            required
                        >
                    </div>
                    <div class="col-lg-6 mb-2">
                        <label for="mascara_validacao" class="font-weight-bold" >* Máscara</label>
                        <select id="mascara_validacao" 
                                name="mascara_validacao"
                                class="form-control"
                                data-anterior=""
                                data-mascara_validacao = "false"
                                required
                        >
                            <option value="" selected >Selecione</option>
                            <option value="false"       >false</option>
                            <option value="data"        >data</option>
                            <option value="nome"        >nome</option>
                            <option value="rg"          >rg</option>
                            <option value="cpf"         >cpf</option>
                            <option value="cnpj"        >cnpj</option>
                            <option value="email"       >email</option>
                            <option value="telefone"    >telefone</option>
                            <option value="celular"     >celular</option>                        
                            <option value="cep"         >cep</option>                        
                            <option value="porcentagem" >porcentagem</option>                        
                            <option value="monetario"   >monetario</option>                        
                            <option value="numero"      >numero</option>                        
                        </select>
                    </div>
                    <div class="col-lg-6 mb-2">
                    <label for="column" class="font-weight-bold" >* Column</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="column" 
                            name="column" 
                            data-mascara_validacao="numero"
                            maxlength="2" 
                            required
                        >
                    </div>
                    <div class="col-lg-6 mb-2">
                        <label for="ordem_form" class="font-weight-bold" >* Ordem Form</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="ordem_form" 
                            name="ordem_form" 
                            data-mascara_validacao="numero"
                            maxlength="2" 
                            required
                        >
                    </div>
                    <div class="col-lg-6 mb-2">
                        <label for="type" class="font-weight-bold" >* Tipo no Form</label>
                        <select id="type" 
                                name="type"
                                class="form-control"
                                data-anterior=""
                                data-mascara_validacao = "false"
                                required
                        >
                            <option value="" selected >Selecione</option>
                            <option value="text" >text</option>
                            <option value="acoes" >acoes</option>
                            <option value="hidden" >hidden</option>
                            <option value="textarea" >textarea</option>
                            <option value="radio" >radio</option>
                            <option value="checkbox" >checkbox</option>
                            <option value="relacional" >relacional</option>
                            <option value="dropdown" >dropdown</option>
                            <option value="table" >table</option>                
                        </select>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label for="info_relacional" class="font-weight-bold" >* Informações Relacional</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="info_relacional" 
                            name="info_relacional" 
                            data-mascara_validacao="false"
                            maxlength="100" 
                            required
                            placeholder="relacional ( tabela, campo ) - radio( valor , label)"
                        >
                    </div>
                    <div class="col-lg-12 mb-3 align-self-end">
                        <div class="widget row">
                            <fieldset>
                                <input name="ver" id="ver" value="ver" type="checkbox" class="form-check-input" />
                                <label class="form-check-label font-weight-bold mb-1" for="ver" >Ver Browser</label>
                            
                                <input name="form" id="form" value="form" type="checkbox" class="form-check-input" />
                                <label class="form-check-label font-weight-bold mb-1" for="form" >Ver Form</label>
                                   
                                <input name="obrigatorio" id="obrigatorio" value="obrigatorio" type="checkbox" class="form-check-input" />
                                <label class="form-check-label font-weight-bold mb-1" for="obrigatorio" >Obrigatório</label>

                                <input name="unico" id="unico" value="unico" type="checkbox" class="form-check-input" />
                                <label class="form-check-label font-weight-bold mb-1" for="unico" >Único</label>
                                
                                <input name="pode_zero" id="pode_zero" value="podezero" type="checkbox" class="form-check-input" />
                                <label class="form-check-label font-weight-bold mb-1" for="pode_zero" >Pode Zero</label>

                                <input name="filtro_faixa" id="filtro_faixa" value="filtrofaixa" type="checkbox" class="form-check-input" />
                                <label class="form-check-label font-weight-bold mb-1" for="filtro_faixa" >Filtro Faixa</label>
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3 d-flex justify-content-end">
                        <div class="col-lg-12 ">
                            <div id="btn_incluir" class="btn btn-primary btn-block">Incluir</div>
                        </div>
                    </div>    
                </div>   
            </div>   
        </div>
    </div>
    <!-- estrutura da tabela -->
    <div class="col-lg-9" >
         <div class="row">
            <div class="col-lg">
                <div class="mt-2 text-center bg-info text-white font-weight-bold"> Campos 'ID', 'ALTERACOES' e 'SITUACAO' são criados automaticamente </div>
            </div>
        </div>
        <div class="row" >
            <div class="col-lg-12">
                <h4 class="mt-2 text-center bg-dark text-white">Estrutura da Tabela </h4> 
            </div>
            <div class="col-lg-12" >
                <ul id="campos_tabela" class="col-lg-12" >
                </ul>
            </div>
        </div>
    </div>
</div>
