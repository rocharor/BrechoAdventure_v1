
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

    var dados = {'email':email,'senha':senha};

    $.ajax({
        url: '/brechoAdventure/site/topo/',
        dataType: 'json',
        type: 'POST',
        data: {'act': 'login',
               'dados':dados},
        success: function(retorno){
            if(retorno.sucesso == true){
                alert(retorno.mensagem);
                $('.form-control').val('');
                $('#login').modal('hide');
                window.location.reload();
            }
            else
                alert(retorno.mensagem +' cod-01')
        },
        error: function(retorno){
            alert('Erro no sistema! cod-02')
        }
    })
});

$('.act-reenviar-senha').click(function(e){
    e.preventDefault();

    var email = $('#email_reenviar_senha').val();
    var dados = {'email':email};
    $('.carregando').removeClass('hide');

    $.ajax({
        url: '/brechoAdventure/site/topo/',
        dataType: 'json',
        type: 'POST',
        data: {'act': 'esqueci_senha',
               'dados': dados},
        success: function(retorno){
            if(retorno.sucesso == true){
                alert(retorno.mensagem);
                $('.form-control').val('');
                $('#esqueci_senha').modal('hide');
                window.location.reload();
            }
            else
                alert(retorno.mensagem);
                $('.carregando').addClass('hide');
        },
        error: function(retorno){
            alert('Erro no sistema! cod-G1')
        }
    })
});

$('.act-cadastro').click(function(e){
    e.preventDefault();

    var nome          = $('#nome_cad').val();
    var email         = $('#email_cad').val();
    var dt_nascimento = $('#dt_nascimento_cad').val();
    var endereco      = $('#endereco_cad').val();
    var numero        = $('#numero_cad').val();
    var complemento   = $('#complemento_cad').val();
    var bairro        = $('#bairro_cad').val();
    var cidade        = $('#cidade_cad').val();
    var uf            = $('#uf_cad').val();
    var cep           = $('#cep_cad').val();
    var tel_fixo      = $('#tel_cad').val();
    var tel_cel       = $('#cel_cad').val();
    var foto          = $('#foto_cad').val();//usado para validação
    var senha1        = $('#senha1_cad').val();
    var senha2        = $('#senha2_cad').val();

    var $foto = $('#foto_cad');//objeto

    var erro = false;

    if(nome == ''){
        $('#nome_cad').parent().addClass('has-error');
        alert('Campo nome é obrigatório');
        erro = true;
        return false;
    }
    if(email == ''){
        $('#email_cad').parent().addClass('has-error');
        alert('Campo email é obrigatório');
        erro = true;
        return false;
    }
    if(endereco == ''){
        $('#endereco_cad').parent().addClass('has-error');
        alert('Campo endereço é obrigatório');
        erro = true;
        return false;
    }
    if(numero == ''){
        $('#numero_cad').parent().addClass('has-error');
        alert('Campo numero é obrigatório');
        erro = true;
        return false;
    }
    if(bairro == ''){
        $('#bairro_cad').parent().addClass('has-error');
        alert('Campo bairro é obrigatório');
        erro = true;
        return false;
    }
    if(cidade == ''){
        $('#cidade_cad').parent().addClass('has-error');
        alert('Campo cidade é obrigatório');
        erro = true;
        return false;
    }
    if(uf == ''){
        $('#uf_cad').parent().addClass('has-error');
        alert('Campo uf é obrigatório');
        erro = true;
        return false;
    }
    if(cep == ''){
        $('#cep_cad').parent().addClass('has-error');
        alert('Campo cep é obrigatório');
        erro = true;
        return false;
    }
    if(tel_fixo == ''){
        $('#tel_cad').parent().addClass('has-error');
        alert('Campo "Telefone fixo" é obrigatório');
        erro = true;
        return false;
    }
    if(tel_cel == ''){
        $('#cel_cad').parent().addClass('has-error');
        alert('Campo "Telefone cel" é obrigatório');
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

    if(!erro){
        if(foto == ''){
            var dados = {1:nome,
                         2:email,
                         3:dt_nascimento,
                         4:endereco,
                         5:numero,
                         6:complemento,
                         7:bairro,
                         8:cidade,
                         9:uf,
                         10:cep,
                         11:tel_fixo,
                         12:tel_cel,
                         13:senha1};
            $.ajax({
                url: '/brechoAdventure/site/topo/',
                dataType: 'json',
                type: 'POST',
                data: {'act': 'cadastro',
                       'dados': dados},
                success: function(retorno){
                    if(retorno.sucesso == true){
                        alert(retorno.mensagem);
                        $('.form-control').val('');
                        $('#cadastro').modal('hide');
                        window.location.reload();
                    }
                    else
                        alert(retorno.mensagem)
                },
                error: function(retorno){
                    alert('Erro no sistema! cod-G1')
                }
            })
        }else{
            var dados = 'cadastro'+'|'+nome+'|'+email+'|'+dt_nascimento+'|'+endereco+'|'+numero+'|'+complemento+'|'+bairro+'|'+cidade+'|'+uf+'|'+cep+'|'+tel_fixo+'|'+tel_cel+'|'+senha1;

            $foto = $foto[0].files[0];

            var form_data = new FormData();
            form_data.append('arquivo',$foto);
            form_data.append('dados',dados);
            form_data.append('act','cadastro');

            $.ajax({
                url: '/brechoAdventure/site/topo/',
                dataType: 'json',
                type: 'POST',
                data: form_data,
                processData: false,
                contentType: false,
                success: function(retorno){
                    if(retorno.sucesso == true){
                        alert(retorno.mensagem);
                        $('.form-control').val('');
                        $('#cadastro').modal('hide');
                        window.location.reload();
                    }
                    else
                        alert(retorno.mensagem)
                },
                error: function(retorno){
                    alert('Erro ao salvar cadastro! cod-G1')
                }
            })
        }
    }

});

$('.act-logoff').click(function(e){
    e.preventDefault();

    $.ajax({
        url: '/brechoAdventure/site/topo/',
        type: 'POST',
        dataType: 'json',
        data: {'act': 'logoff'},
        success: function(retorno){
            if(retorno.sucesso == true){
                alert(retorno.mensagem);
                window.location.href = '/brechoAdventure/site/';
            }
        },
        error: function(retorno){
            alert('Erro no sistema! cod-g1')
        }
    });

});

$('.act-salvar-produto').click(function(e){
    e.preventDefault();

    var titulo = $('#titulo_produto').val();
    var categoria = $('#categoria_produto').val();
    var descricao = $('#desc_produto').val();
    var tipo = $("input[name='tipo_produto']:checked").val()
    var valor = $('#valor_produto').val();
    var foto1 = $('#foto1').val();
    var foto2 = $('#foto2').val();
    var foto3 = $('#foto3').val();

    var erro = false;

    if(titulo == ''){
        $('#titulo_produto').parent().addClass('has-error');
        alert('Campo "Titulo" é obrigatório');
        return false;
    }
    if(categoria == ''){
        $('#categoria_produto').parent().addClass('has-error');
        alert('Campo "Categoria" é obrigatório');
        return false;
    }
    if(descricao == ''){
        $('#desc_produto').parent().addClass('has-error');
        alert('Campo "Descrição" é obrigatório');
        erro = true;
        return false;
    }
    if(tipo == 'undefined'){
        $("input[name='tipo_produto']").parent().addClass('has-error');
        alert('Campo "Tipo" é obrigatório');
        erro = true;
        return false;
    }
    if(foto1 == ''){
        $('#foto1').parent().addClass('has-error');
        alert('Campo "Foto 1" é obrigatório');
        erro = true;
        return false;
    }
    if(valor == ''){
        $('#valor_produto').parent().addClass('has-error');
        alert('Campo "Valor" é obrigatório');
        erro = true;
        return false;
    }
    if(!erro){
        $foto1 = $('#foto1')[0].files[0];

        var dados = titulo+'|'+categoria+'|'+descricao+'|'+tipo+'|'+valor;

        var form_data = new FormData();
        form_data.append('act','inserir_produto');
        form_data.append('dados',dados);
        form_data.append('foto1',$foto1);

        if(foto2 != ''){
            $foto2 = $('#foto2')[0].files[0];
            form_data.append('foto2',$foto2);

        }

        if(foto3 != ''){
            $foto3 = $('#foto3')[0].files[0];
            form_data.append('foto3',$foto3);
        }

        $.ajax({
            url: '/brechoAdventure/site/topo/',
            type: 'POST',
            dataType: 'json',
            data: form_data,
            processData: false,
            contentType: false,
            success: function(retorno){
                if(retorno.sucesso == true){
                    alert(retorno.mensagem);
                    window.location.reload();
                }
            },
            error: function(retorno){
                alert('Erro no sistema! cod-g1')
            }
        });
    }

});

var addCarregando = function() {

    $('body').append( '<div class="box-sombra">' +
                        '   <div class="box-sombra-interno">' +
                        '       <div class="box-sombra-interno-sombra"></div>' +
                        '       <div class="box-sombra-interno-box">' +
                        '           <div class="progress">' +
                        '               <div class="progress-bar progress-bar-success" style="width: 0%;"></div>' +
                        '           </div>' +
                        '       </div>' +
                        '   </div>' +
                        '</div>'
                      );
};

var removeCarregando = function() {
    $('.progress-bar').css('width', '100%');
    setTimeout (
        function (){
           $('.box-sombra').addClass('hide');
        },
    400);
};

