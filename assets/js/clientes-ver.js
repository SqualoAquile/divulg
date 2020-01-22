$(function () {
    $('input').attr('disabled', 'disabled');
    $('textarea').attr('disabled', 'disabled');
    $('select').attr('disabled', 'disabled');
    $('.btn-primary').hide();
   
    $(document).on('mousemove', function(){
        $('a.editar-contato.btn.btn-sm.btn-primary').hide();
        $('a.excluir-contato.btn.btn-sm.btn-danger').hide();
    });

    
    if ( $('#pf').is(':checked') == true ){
        $('#contatos-form').hide();
    }else{
        $('#contatos-form').show();
    }
});