$(function () {
    // console.log('funcionarios - ver js');


   $('#folhas_select').hide();
   $('#folhas_select_add').hide();

   $('input').attr('disabled', 'disabled');
   $('textarea').attr('disabled', 'disabled');
   $('select').attr('disabled', 'disabled');
   $('.btn-primary').hide();

   
   
    $(document).on('mousemove', function(){
        $('a.editar-contato.btn.btn-sm.btn-primary').hide();
        $('a.excluir-contato.btn.btn-sm.btn-danger').hide();
    });
   
   
   
});

