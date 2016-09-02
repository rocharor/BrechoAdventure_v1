$('.btn-login').click(function(e){
	e.preventDefault();
	$('.msg_login').html('');
	$('#login').modal('show');
});

$('#login').on('shown.bs.modal', function (e) {
    $('#email_login').focus();
});

$('.act-login').click(function(e){
    e.preventDefault()

    var email = $('#email_login').val();
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
        url: '/login/',
        dataType: 'json',
        type: 'POST',
        data: {'email': email,
               'senha':senha},
        success: function(retorno){
            if(retorno.sucesso == true){
                window.location.reload();
            }else{
            	$('.msg_login').append("<b style='color:#f00'>"+retorno.msg+"</b><br />");
            }
        },
        error: function(retorno){
            alert('Erro no sistema! cod-02')
        }
    })
});

$('.act-deslogar').click(function(e){
    e.preventDefault()

    $.ajax({
        url: '/Login/deslogar/',
        type: 'POST',
        success: function(retorno){        	
        	window.open('/','_self');
        },
        error: function(retorno){
            alert('Erro no sistema! cod-02')
        }
    })
})

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
    
});