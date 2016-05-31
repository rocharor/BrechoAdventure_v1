$('.btn-enviar').click(function(e){
    e.preventDefault()

    var nome = $('#nome').val();
    var email = $('#email').val();
    var tipo = $('#tipo:selected').val();
    var mensagem = $('#mensagem').val();

    if(nome == ''){
        $('#email_login').parent().addClass('has-error');
        alert('Campo nome é obrigatório');
        return false;
    }

    if(email == ''){
        $('#email_login').parent().addClass('has-error');
        alert('Campo email é obrigatório');
        return false;
    }

    if(mensagem == ''){
        $('#senha_login').parent().addClass('has-error');
        alert('Campo mensagem é obrigatório');
        return false;
    }

    $('.carregando').removeClass('hide');
    $('.form-control').attr('disabled',true);
    $('.btn-contato').attr('disabled',true);
    $('.btn-contato').removeClass('btn-enviar');

    $('#form').submit();
});
