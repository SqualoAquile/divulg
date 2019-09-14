// import { exists } from "fs";

$(function () {

    // Validação se o nome da tabela já existe
    $('#nome_tabela').blur(function () {
        
        if( $(this).val() !== '' ){

            $.ajax({
                url: baselink + '/ajax/buscaTabela',
                type: 'POST',
                data: {
                    tabela: $(this).val()
                },
                dataType: 'json',
                success: function (data) {
                    if( data === true ){
                        $('#nome_tabela').val('').focus();
                        alert('Essa tabela já existe no BD. Mude!');
                        return false;
                    }
                }
            });
        }
    });

    $('#tamanho_campo').blur(function(){
        if( $(this).val() !== ''){
            if( isNaN( parseFloat( $(this).val() ) ) ){
                $('#tamanho_campo').val('').blur();
                return false;
            }
           
        }
    });
    /////////////////////////////
    $('#btn_criar').on('click', function(){
        
        if( $('#nome_tabela').val() == ''){
            $('#nome_tabela').focus();
            return false;
        }else if( $('#lbl_brownser').val() == ''){
            $('#lbl_brownser').focus();
            return false;
        } else if( $('#lbl_form').val() == '' ){
            $('#lbl_form').focus();
            return false;
        }else if( $('#campos_tabela li').length <= 0 ){
            alert('A tabela deve ter pelo menos um campo.')
            $('#nome_campo').focus();
            return false;
        }else{
            if(confirm('Criar a Tabela?') === true){
                // todos os requisitos estão OK para criar a tabela
                var query1 = '', nomeTab = '', lblBr = '', lblFm = '';
                nomeTab = $('#nome_tabela').val().trim().toLowerCase();
                lblBr = $('#lbl_brownser').val().trim().toLowerCase();
                lblFm = $('#lbl_form').val().trim().toLowerCase();

                query1 = "CREATE TABLE `"+nomeTab+"` ("+"`id`"+" int(11) NOT NULL COMMENT "+`'{"label": "Ações", "form": "false", "type": "acoes", "ver": "true"}',`;
                    
                //for em todas as <li>
                for(var i = 0; i < $('#campos_tabela li').length; i++ ){
                    query1 += $('#campos_tabela li:eq('+i+')').find('div.col-lg-11').text() + ",";
                }
                
                query1 += "`alteracoes` text NOT NULL COMMENT "+`'{"ver":"false","form":"true", "type":"hidden", "mascara_validacao":"false"}',`+" `situacao` varchar(8) NOT NULL COMMENT"+` '{"ver": "false", "form": "false"}') ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='{ "labelBrowser":"`+lblBr+`", "labelForm":"`+lblFm+`"}'`;

                // console.log(query);

                var query2 = '', query3 = '';
                query2 = "ALTER TABLE `"+nomeTab+"` ADD PRIMARY KEY (`id`);";
                query3 = "ALTER TABLE `"+nomeTab+"` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT "+`'{"label": "Ações", "form": "false", "type": "acoes", "ver": "true"}'`;
                // console.log(query2);
                // console.log(query3); 

                if( query1 !== '' && query2 !== '' && query3 !== '' ){
                    // ajax para executar a query e criar a tabela
                    $.ajax({
                        url: baselink + '/ajax/criaTabela',
                        type: 'POST',
                        data: {
                            tabela: $('#nome_tabela').val(),
                            query1: query1,
                            query2: query2,
                            query3: query3
                        },
                        dataType: 'json',
                        success: function (data) {
                            if( data === true ){
                                
                                $('#nome_tabela, #lbl_brownser, #lbl_form, #form_tabela input, #form_tabela select').val('').blur();
                                $('#campos_tabela li').remove();
                                
                                Toast({
                                    message: 'Tabela criada com sucesso, Acerte o TEMPLATE!',
                                    class: 'alert-success'
                                });
                            }else{
                                Toast({
                                    message: 'A tabela não foio criada!',
                                    class: 'alert-danger'
                                });
                            }
                        }
                    });
                    // executar a função que cria o MVC dessa tabela
                }        
            }
        }
    });

    $('#btn_incluir').on('click', function(){
        // testa se tem algum elemento que NÃO tá preenchido
        if( $('#nome_tabela').val() == '' || $('#lbl_brownser').val() == '' || $('#lbl_form').val() == '' ){
            $('#nome_tabela').focus();
            return false;

        }else{
            if ( formPreenchido() != '' ){
                if( $('#campos_tabela li').length > 0 ){
                    // testar se já existe o nome
                    var nomelinha = [];
                    for(var i=0; i< $('#campos_tabela li').length; i++){
                        var aux = '', 
                        aux = $('#campos_tabela li:eq('+i+')').find('.col-lg-11').text();
                        nomelinha.push( aux.split('`')[1] );
                    }
                    if( nomelinha.indexOf( $('#nome_campo').val().trim() ) !== -1 ){
                        alert('Esse nome de campo já foi utilizado. Mude!');
                        $('#nome_campo').val('');
                        return false;
                    }else{
                        $('#campos_tabela').append(formPreenchido());
                    }
                }else{
                    $('#campos_tabela').append(formPreenchido());
                }
                
            }
        }
        
    });

});

function remove(elemento){
    // console.log('cliquei na lixeira');
    if(confirm('Quer excluir a linha?')){
        elemento.closest('li').remove();
    }
};

// retorna a linha que deve ser incluida na tabela
function formPreenchido(){
    var ok = true, coment = '';
    
    if( $('#nome_campo').val() == '' ){
        $('#nome_campo').focus();
        ok = false;
        return false;
    }else{
        coment += '`' + $('#nome_campo').val().trim().toLowerCase() + '` ';
    }
    
    if( $('#tipo_campo').val() != '' ){
        if( $('#tipo_campo').val() == 'varchar' || 
            $('#tipo_campo').val() == 'float' || 
            $('#tipo_campo').val() == 'int' ){
                if( $('#tamanho_campo').val() != '' ){
                    coment += $('#tipo_campo').val().trim().toLowerCase() + '(' + $('#tamanho_campo').val() + ')';
                }else{
                    $('#tamanho_campo').focus();
                    ok = false;
                    return false;
                }
        }else{
            coment += $('#tipo_campo').val().trim().toLowerCase() + ' ';
        }
    }else {
        $('#tipo_campo').focus();
        ok = false;
        return false;
    }

    if( $('#obrigatorio').is(':checked') ){
        coment += ' NOT NULL ';
    }else{
        coment += ' DEFAULT NULL ';
    }
    
    coment += " COMMENT '{ ";

    if( $('#label').val() == '' ){
        $('#label').focus();
        ok = false;
        return false;
    }else{

        coment += '"' + $('#label').attr('name') + '":"' + $('#label').val() + '", ';
    }

    if( $('#mascara_validacao').val() == '' ){
        $('#mascara_validacao').focus();
        ok = false;
        return false;
    }else{

        coment += '"' + $('#mascara_validacao').attr('name') + '":"' + $('#mascara_validacao').find(':selected').val() + '", ';
    }

    if( $('#column').val() == '' ){
        $('#column').focus();
        ok = false;
        return false;
    }else{

        coment += '"' + $('#column').attr('name') + '":"' + $('#column').val() + '", ';
    }

    if( $('#ordem_form').val() == '' ){
        $('#ordem_form').focus();
        ok = false;
        return false;
    }else{

        coment += '"' + $('#ordem_form').attr('name') + '":"' + $('#ordem_form').val() + '", ';
    }

    if( $('#type').val() == '' ){
        $('#type').focus();
        ok = false;
        return false;
    }else{

        coment += '"' + $('#type').attr('name') + '":"' + $('#type').find(':selected').val() + '", ';
    }

    if( $('#info_relacional').val() == '' ){
        var input = $("#type").find(':selected').val();
        if( input == 'radio' || input == 'checkbox' || input == 'relacional' || input == 'dropdown' ){
            $('#info_relacional').focus();
            ok = false;
            return false;       
        }
    }else{

        var input = $("#type").find(':selected').val();
        if( input == 'radio' ){
            var contAux ='', conteudo = '';
            contAux = $('#info_relacional').val();
            contAux = contAux.split(')');
            conteudo = '"options":{';
            for(var j=0; j < contAux.length-1; j++){
                var item = []; item = contAux[j].split(',');
                //colocar a vírgula depois da dupla
                if( j < contAux.length - 2 ){
                    conteudo += '"' + item[0].substr( 1 , item[0].length ) + '":"' + item[1] + '", ';
                }else{
                    conteudo += '"' + item[0].substr( 1 , item[0].length ) + '":"' + item[1] + '"';
                }
                
            }
            conteudo += '},';
                    
        }else if( input == 'checkbox' || input == 'relacional' || input == 'dropdown' ){
            var contAux ='', conteudo = '', tabela = '', campo = '';
            contAux = $('#info_relacional').val();
            contAux = contAux.split(',');
            conteudo = '"info_relacional":{';
            
            tabela = contAux[0].substr( 1 , contAux[0].length ).trim();
            campo = contAux[1].substr( 0 , contAux[1].length - 1 ).trim();

            conteudo += '"tabela":"'+tabela+'","campo":"'+campo+'"}, ';    
            
        }
        // console.log(conteudo);
        coment += conteudo;
    }

    $( '#form_tabela input[type=checkbox]' ).each(function() {
        if( $(this).attr('name') != 'obrigatorio' ){
            if( $(this).is(':checked') == true ){
                coment += '"' + $(this).attr('name') + '":"true", ';
            }else{
                coment += '"' + $(this).attr('name') + '":"false", ';
            }
        }
    });

    coment = coment.substr( 0 , coment.length - 2 );
    coment += " }'";

    var linha = '';
    if( ok == true ){    
        linha = `<li class="ui-state-default"><div class="row">
            <div class="col-lg-1"><i class="btn btn-sm btn-danger fas fa-trash-alt" onclick="remove(this)"></i></div>    
            <div class="col-lg-11">`+coment+`</div>
        </div></li>`;
    }
    
    return linha;
};



////////////////////////////
////////////////////////////
////////////////////////////

// // Retorna um array de contatos puxados do campo hidden com o atributo nome igual a contatos
// function Contatos() {
//     var returnContatos = [];
//     if ($('[name=contatos]') && $('[name=contatos]').val().length) {
//         var contatos = $('[name=contatos]').val().split('[');
//         for (var i = 0; i < contatos.length; i++) {
//             var contato = contatos[i];
//             if (contato.length) {
//                 contato = contato.replace(']', '');
//                 var dadosContato = contato.split(' * ');
//                 returnContatos.push(dadosContato);
//             }
//         }
//     };
//     return returnContatos;
// };

// // Escreve o html na tabela
// function Popula(values) {

//     if (!values) return;

//     var currentId = $formContatos.attr('data-current-id'),
//         tds = '';

//     // Coloca a tag html TD em volta de cada valor vindo do form de contatos
//     values.forEach(value => tds += `<td class="col-lg-2 text-truncate">` + value + `</td>`);

//     if (!currentId) {
//         // Se for undefined então o contato está sendo criado

//         // Auto incrementa os ID's dos contatos
//         lastInsertId += 1;

//         $('#contatos tbody')
//             .prepend('<tr class="d-flex flex-column flex-lg-row" data-id="' + lastInsertId + '">' + tds + botoes + '</tr>');

//     } else {
//         // Caso tenha algum valor é por que o contato está sendo editado

//         $('#contatos tbody tr[data-id="' + currentId + '"]')
//             .html(tds + botoes);

//         // Seta o data id como undefined para novos contatos poderem ser cadastrados
//         $formContatos.removeAttr('data-current-id');
//     }

//     $('.editar-contato').bind('click', Edit);
//     $('.excluir-contato').bind('click', Delete);
// };

// // Pega as linhas da tabela auxiliar e manipula o hidden de contatos
// function SetInput() {
//     var content = '';
//     $('#contatos tbody tr').each(function () {
//         var par = $(this).closest('tr');
//         var tdNome = par.children("td:nth-child(1)");
//         var tdSetor = par.children("td:nth-child(2)");
//         var tdTelefone = par.children("td:nth-child(3)");
//         var tdCelular = par.children("td:nth-child(4)");
//         var tdEmail = par.children("td:nth-child(5)");

//         content += '[' + tdNome.text() + ' * ' + tdSetor.text() + ' * ' + tdTelefone.text() + ' * ' + tdCelular.text() + ' * ' + tdEmail.text() + ']';
//     });

//     $('[name=contatos]')
//         .val(content)
//         .attr('data-anterior-aux', content)
//         .change();
// };

// // Delete contato da tabela e do hidden
// function Delete() {
//     var par = $(this).closest('tr');
//     par.remove();
//     SetInput();
// };

// // Seta no form o contato clicado para editar, desabilita os botoes de ações deste contato e seta o id desse contato
// // no form dos contatos
// function Edit() {

//     // Volta para válido todos os botoões de editar e excluir
//     $('table#contatos tbody tr .btn')
//         .removeClass('disabled');


//     var $par = $(this).closest('tr'),
//         tdNome = $par.children("td:nth-child(1)"),
//         tdSetor = $par.children("td:nth-child(2)"),
//         tdTelefone = $par.children("td:nth-child(3)"),
//         tdCelular = $par.children("td:nth-child(4)"),
//         tdEmail = $par.children("td:nth-child(5)");

//     // Desabilita ele mesmo e os botões irmãos de editar e excluir da linha atual
//     $par
//         .find('.btn')
//         .addClass('disabled');

//     $('input[name=contato_nome]').val(tdNome.text()).attr('data-anterior', tdNome.text()).focus();
//     $('input[name=contato_setor]').val(tdSetor.text()).attr('data-anterior', tdSetor.text());
//     $('input[name=contato_telefone]').val(tdTelefone.text()).attr('data-anterior', tdTelefone.text());
//     $('input[name=contato_celular]').val(tdCelular.text()).attr('data-anterior', tdCelular.text());
//     $('input[name=contato_email]').val(tdEmail.text()).attr('data-anterior', tdEmail.text());

//     $('table#contatos thead tr[role=form]')
//         .attr('data-current-id', $par.attr('data-id'))
//         .find('.is-valid, .is-invalid')
//         .removeClass('is-valid is-invalid');
// };




