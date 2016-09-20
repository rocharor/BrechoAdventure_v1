/*=======================
AÇÕES PARA ALTERAR FOTO
=======================*/

/**
* acão para quando clica no link alterar foto;
*/
$('.act-alter-foto').click(function(e){
    e.preventDefault();

   $('#foto_upd').trigger('click');
});

/**
* executa quando escolhe a imagem;
*
*/
var altera_imagem = function(){

    var nm_imagem = $('#foto_upd')[0].files[0].name;

    $('.act-alter-foto').addClass('hide');
    $('.act-enviar-imagem').removeClass('hide');

    $('.nm_imagem').append(nm_imagem);
}

/**
* Faz o update da imagem
*/
$('.act-enviar-imagem').click(function(e){
    e.preventDefault();

    var $foto = $('#foto_upd')[0].files[0];
    var form_data = new FormData();
    form_data.append('arquivo',$foto);
    form_data.append('act','update_foto');
    $.ajax({
        url: '/minha-conta/perfil/updateFoto/',
        type: 'post',
        dataType: 'json',
        processData: false,
        contentType: false,
        data: form_data,
        success: function(retorno){
            if(retorno.sucesso == true){
                alert(retorno.mensagem);
                window.location.reload();
            }else{
                alert(retorno.mensagem);
                $('.act-alter-foto').removeClass('hide');
                $('.act-enviar-imagem').addClass('hide');
                $('.nm_imagem').html('');
            }

        },
        error: function(retorno){

        }
    })
})


/**
* Salva dados do formulario
*/
$('.act-update').click(function(e){
    e.preventDefault()

    var nome          = $('#nome_upd').val();
    var apelido          = $('#apelido_upd').val();
    var email         = $('#email_upd').val();
    var dt_nascimento = $('#dt_nascimento_upd').val();
    var endereco      = $('#endereco_upd').val();
    var numero        = $('#numero_upd').val();
    var complemento   = $('#complemento_upd').val();
    var bairro        = $('#bairro_upd').val();
    var cidade        = $('#cidade_upd').val();
    var uf            = $('#uf_upd').val();
    var cep           = $('#cep_upd').val();
    var tel_fixo      = $('#tel_upd').val();
    var tel_cel       = $('#cel_upd').val();

    var erro = false;

    if(nome == ''){
        $('#nome_upd').parent().addClass('has-error');
        alert('Campo nome é obrigatório');
        erro = true;
        return false;
    }
    
    if(email == ''){
        $('#email_upd').parent().addClass('has-error');
        alert('Campo email é obrigatório');
        erro = true;
        return false;
    }
    
    if(dt_nascimento == ''){
        $('#dt_nascimento_upd').parent().addClass('has-error');
        alert('Campo "Data de nascimento" é obrigatório');
        erro = true;
        return false;
    }
    
    if(tel_fixo == '' && tel_cel == ''){
        $('#tel_upd').parent().addClass('has-error');
        $('#cel_upd').parent().addClass('has-error');
        alert('Necessário pelo menos 1 número de telefone');
        erro = true;
        return false;
    }

    if(!erro){
        var dados = {'nome':nome,
        			 'apelido':apelido,
                     'email':email,
                     'dt_nascimento':dt_nascimento,
                     'endereco':endereco,
                     'numero':numero,
                     'complemento':complemento,
                     'bairro':bairro,
                     'cidade':cidade,
                     'uf':uf,
                     'cep':cep,
                     'telefone_fixo':tel_fixo,
                     'telefone_cel':tel_cel
                    };
        $.ajax({
            url:'/minha-conta/perfil/updatePerfil/',
            dataType: 'json',
            type: 'POST',
            data: {'dados': dados},
            success: function(retorno){
                if(retorno.sucesso == true){
                    alert(retorno.mensagem);
                }
                else
                    alert(retorno.mensagem)
            },
            error: function(retorno){
                alert('Erro no sistema! cod-G1')
            }
        })

    }
});


/**
* Mascara dos campos
*/
$('#cep_upd').mask('99999-999');
$('#tel_upd').mask('(99) 9999-9999');
$('#cel_upd').mask('(99) 99999-9999');
$('#dt_nascimento_upd').mask('99/99/9999');