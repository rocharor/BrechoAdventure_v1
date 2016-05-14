$('.btn-cadastro').click(function(e){
	e.preventDefault();
	$('#cadastro').modal('show');
});

$('#cadastro').on('shown.bs.modal', function (e) {
    $('#nome_cad').focus();
});

$('.act-cadastro').click(function(e){
    e.preventDefault()

    var nome = $('#nome_cad').val();
    var apelido = $('#apelido_cad').val();
    var email = $('#email_cad').val();
    var senha1 = $('#senha1_cad').val();
    var senha2 = $('#senha2_cad').val();

    if(nome == ''){
        $('#nome_cad').parent().addClass('has-error');
        alert('Campo nome é obrigatório');
        erro = true;
        return false;
    }
    if(apelido == ''){
        $('#apelido_cad').parent().addClass('has-error');
        alert('Campo apelido é obrigatório');
        erro = true;
        return false;
    }
    if(email == ''){
        $('#email_cad').parent().addClass('has-error');
        alert('Campo email é obrigatório');
        erro = true;
        return false;
    }
    if(senha1 == '' || senha2 == ''){
        alert('Campo senha é obrigatório');
        erro = true;
        return false;
    }
    if(senha1 != senha2 ){
        alert('Os campos de senha não estão iguais');
        erro = true;
        return false;
    }
    
    $.ajax({
        url: url_cadastro,
        dataType: 'json',
        type: 'POST',
        data: {	'nome':nome,
        		'apelido':apelido,
        		'email': email,
        		'senha':senha1},
        success: function(retorno){
            if(retorno.sucesso == true){
                alert(retorno.msg);
                //$('.form-control').val('');
                //$('#login').modal('hide');
                window.location.reload();
            }else{            	
                alert(retorno.msg);
            }
        },
        error: function(retorno){
            alert('Erro no sistema! cod-02')
        }
    })
});

/**
 * ESQUECI SENHA
 */

$('.btn-esqueci-senha').click(function(e){
	e.preventDefault();
	$('#login').modal('hide');
	$('#esqueci_senha').modal('show');
});

$('#esqueci_senha').on('shown.bs.modal', function (e) {
    $('#email_reenviar_senha').focus();
});

$('.act-reenviar-senha').click(function(e){
    e.preventDefault()
    console.dir('aki')
    /*var email = $('#email_login').val();
    var senha = $('#senha_login').val();

    if(email == ''){
        $('#email_login').parent().addClass('has-error');
        alert('Campo email é obrigatório');
        return false;
    }

    if(senha == ''){
        $('#senha_login').parent().addClass('has-error');
        alert('Campo senha é obrigatório');
        return false;
    }

    $.ajax({
        url: url_login,
        dataType: 'json',
        type: 'POST',
        data: {'email': email,
               'senha':senha},
        success: function(retorno){
            if(retorno.sucesso == true){
                alert(retorno.mensagem);
                $('.form-control').val('');
                $('#login').modal('hide');
                window.location.reload();
            }else{
            	$('.msg_login').append('Usuário ou senha inválidos;')
                alert(retorno.mensagem +' cod-01')
            }
        },
        error: function(retorno){
            alert('Erro no sistema! cod-02')
        }
    })*/
});