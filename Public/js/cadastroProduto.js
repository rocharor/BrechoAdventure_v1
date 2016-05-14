$('.act-cadastrar-produto').click(function(e){
    e.preventDefault()

    var titulo = $('[name=titulo_produto]').val();
    var categoria = $('[name=categoria_produto]').val();
    var descricao = $('[name=desc_produto]').val();
    var tipo = $('.tipo_produto:checked').val();
    var foto1 = $('[name=foto1]').val();
    var foto2 = $('[name=foto2]').val();
    var foto3 = $('[name=foto3]').val();
    var valor = $('[name=valor_produto]').val();

    var $form = $('[name=form]');

    $('div').removeClass('has-error');
    $('label').css('color','#000');

    if(titulo == ''){
        $('[name=titulo_produto]').parent().addClass('has-error');
        alert('Campo titulo é obrigatório');
        erro = true;
        return false;
    }

    if(categoria == ''){
        $('[name=categoria_produto]').parent().addClass('has-error');
        alert('Campo categoria é obrigatório');
        erro = true;
        return false;
    }
    if(descricao == ''){
        $('[name=desc_produto]').parent().addClass('has-error');
        alert('Campo descricao é obrigatório');
        erro = true;
        return false;
    }
    if(tipo == null){
        $('.tipo_produto').parent().find('label').css('color','#f00');
        alert('Campo tipo é obrigatório');
        erro = true;
        return false;
    }
    if(valor == '' ){
        $('[name=valor_produto]').parent().addClass('has-error');
        alert('Campo valor é obrigatório');
        erro = true;
        return false;
    }

    $form.submit();

    /*$.ajax({
        url: url_cadastro_produto,
        dataType: 'json',
        type: 'POST',
        data: {titulo: titulo,
               categoria: categoria,
               descricao: descricao,
               tipo: tipo,
               valor: valor,
               foto1: foto1,
               foto2: foto2,
               foto3: foto3
        },
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
    })*/
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

$("#valor_produto").maskMoney({prefix:'R$ ',thousands:'.',decimal:','});