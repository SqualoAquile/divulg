$(function () {

    $('input[name=tipo_func]').on('change', function(){
        // console.log('valor', $(this).val())
        if( $('#CLT').is(':checked') == true ){
            verCamposClt();
        }else{
            esconderCamposClt();
        }
    });

    ///// férias
    $('#ferias_dias').attr('readonly','readonly');

    $('#folhas_select_add').addClass('d-none');

    $('#btn_add').on( 'click', function(){
        console.log('apertei o btn_add')
        if( $('#folhas_select_add').is(':visible') == true ){
            $('#folhas_select_add').addClass('d-none');
        }else{
            $('#folhas_select_add').removeClass('d-none');
        }
    });

    $('#folhas').on('change', function(){
        // testa se tem algum elemento que NÃO tá preenchido
        // console.log( $('#folhas').find(':selected').val() )
        if( $(this).find(':selected').val() != '' ){
           
            $('#btn_ver').attr('href', baselink+'/funcionarios/lerpdf/'+$(this).find(':selected').val());
            
            aux = window.location.href;
            aux = aux.split('/');
            idfunc = aux[ aux.length - 1 ];
            var rash = $(this).find(':selected').val();
            var nomeaux = $(this).find(':selected').text();
            nomeaux = nomeaux.replace('/','-');
            // console.log(nomeaux)
            // console.log(baselink+'/funcionarios/excluirpdf/'+idfunc+'/'+$(this).find(':selected').val())
            $('#btn_excluir').attr('href', baselink+'/funcionarios/excluirpdf/'+idfunc+'/'+rash+'/'+nomeaux);
           
        }
        
    });

    $('#btn_adicionar').on('click', function(){
       var data = new FormData();
       var arquivo = $('#arq')[0].files;
        
        if ( arquivo.length <= 0 ){
            alert('Nenhum arquivo foi selecionado');
            $('#arq').val('');
            return false;
            
        }else if(  $('#mes').val() == ''  ){
            alert('Preencha o mês referência.');
            $('#mes').val('');
            return false;

        }else if(  arquivo.item(0).type != 'application/pdf' ){            
           alert('O arquivo selecionado deve ser um PDF');
           $('#arq').val('');
           return false;

       }else if( parseInt( arquivo.item(0).size ) > parseInt( 1048576 )  ){
            alert('O arquivo deve ter um tamanho menor do que 1 Mb');
            $('#arq').val('');
            return false;
       }else {
            var idfunc = '', aux = '';
            aux = window.location.href;
            aux = aux.split('/');
            idfunc = aux[ aux.length - 1 ];

            data.append( 'id_funcionario', idfunc );
            data.append( 'titulo',  $('#mes').val() );
            data.append( 'arq', arquivo[0] );

            $.ajax( {
                url: baselink + '/ajax/adicionaArquivo',
                type:"POST",
                data: data,
                contentType: false,
                processData: false,
                success: function( data ) {
                    
                    if(data == 'true' ){
                        alert('PDF adicionado com sucesso!');
                        // Toast({
                        //     message: 'PDF adicionado com sucesso!',
                        //     class: 'alert-success'
                        // });

                        document.location.reload(true);

                    }else{

                        Toast({
                            message: 'O PDF não foi adicionado! Tente Novamente.',
                            class: 'alert-danger'
                        });

                    }
                    
                    
                }
            });
           
       }

       

       

       

    });

    $('#btn_ver').on('click', function(){
        if( $('#folhas').find(':selected').val() == '' ){
            return false;
        }
    });
    
    $('#btn_excluir').on('click', function(){
        if( $('#folhas').find(':selected').val() == '' ){
            return false;
        }else{

        }
    });
    
    $('#insalubridade').change(function(){
        var insab = $(this).find(':selected').val();
        var valor = $('#valor_insab');
        
        if (insab != '' && insab != undefined){
            if( insab == 'SIM'){
                if( valor.attr('data-anterior') == '' ){
                    valor.val('');        
                    valor.removeAttr('readonly'); 
                }else{
                    valor.val(valor.attr('data-anterior'));        
                    valor.removeAttr('readonly'); 
                }
            }else{
                valor.val('0,00');  
                valor.attr('readonly', 'readonly');
            }
        }else{
            valor.val('');  
            valor.attr('readonly', 'readonly');    
        }
        
    });

    $('#estado_civil').change(function(){
        var est = $(this).find(':selected').val();
        var infoest = $('#info_estado_civil');
        
        if (est != '' && est != undefined){
            if( est == 'Casado(a)'){
                if( infoest.attr('data-anterior') == '' ){
                    infoest.val('');        
                    infoest.removeAttr('readonly'); 
                }else{
                    infoest.val(infoest.attr('data-anterior'));        
                    infoest.removeAttr('readonly'); 
                }
            }else{
                infoest.val('');  
                infoest.attr('readonly', 'readonly');
            }
        }else{
            infoest.val('');  
            infoest.attr('readonly', 'readonly');    
        } 
    });

    $('#filhos').change(function(){
        var insab = $(this).find(':selected').val();
        var valor = $('#info_filhos');
        
        if (insab != '' && insab != undefined){
            if( insab == 'SIM'){
                if( valor.attr('data-anterior') == '' ){
                    valor.val('');        
                    valor.removeAttr('readonly'); 
                }else{
                    valor.val(valor.attr('data-anterior'));        
                    valor.removeAttr('readonly'); 
                }    
            }else{
                valor.val('');  
                valor.attr('readonly', 'readonly');
            }
        }else{
            valor.val('');  
            valor.attr('readonly', 'readonly');    
        }
        
    });

    /////// INICIANDO OS CAMPOS NECESSÁRIOS

    $('#info_estado_civil').attr('placeholder', 'escrceva o nome e cpf do cônjuge.');
    $('#info_filhos').attr('placeholder', 'escrceva o nome e cpf de todos os filhos com até 14 anos.');

    ///// campos da clt
    if( $('#nome').attr('data-anterior') == '' ){
        //// ESTAMOS NO ADICIONAR
       
        //campos do form
        $('#FREELANCER').click();
        $('input[name=tipo_func]').change();
        esconderCamposClt();
        
        /// férias

        //aruivos de folha ponto
        $('#folhas_select').addClass('d-none');
        

    }else{
        //// ESTAMOS NO EDITAR
        if( $('#data_demissao').attr('data-anterior') == '00/00/0000' ){
            $('#data_demissao').val('');
            $('#data_demissao').attr('data-anterior', '');
        }

        // campos do form
        if( $('#CLT').is(':checked') == true ){
            // verCamposClt();
            $('#insalubridade').trigger('change');
            $('#estado_civil').trigger('change');
            $('#filhos').trigger('change');

        }else{
            esconderCamposClt();
        }

        

        // férias

        // arquivos folha ponto
        $('#folhas_select').removeClass('d-none');

    }
});

function esconderCamposClt(){
    var $camposCLT = [];
        $camposCLT = [
            $('#insalubridade'), $('#valor_insab'), $('#pis'), $('#ctps'), $('#serie'), $('#nro_ponto'), $('#naturalidade'), $('#nacionalidade'), $('#estado_civil'), $('#info_estado_civil'), $('#filhos'), $('#info_filhos')
        ];
        
        $camposCLT.forEach(function(valor,chave){
            valor
                .val('')
                .removeAttr('required')
                .parent().parent()
                .addClass('d-none')
            // console.log(valor.attr('type'), valor.attr('name'))
        });
 }

 function verCamposClt(){
    var $camposCLT = [];
        $camposCLT = [
            $('#insalubridade'), $('#valor_insab'), $('#pis'), $('#ctps'), $('#serie'), $('#nro_ponto'), $('#naturalidade'), $('#nacionalidade'), $('#estado_civil'), $('#info_estado_civil'), $('#filhos'), $('#info_filhos')
        ];
        // console.log($camposCLT);
        
        $camposCLT.forEach(function(valor,chave){
            valor
                .val('')
                .removeClass('is-valid is-invalid')
                .attr('required', 'required')
                .parent().parent()
                .removeClass('d-none');
                // console.log(valor.attr('type'), valor.attr('name'))
        });

        $('#insalubridade').change();
        $('#estado_civil').change();
        $('#filhos').change();
        
 }