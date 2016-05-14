
$('.act-editar-anuncio').click(function(e){
    e.preventDefault();

    var id = $(this).data('id');
    $('.fotos').html('');
    $('.carregando').removeClass('hide');
    $('#update_produto').modal();
    $('.act-salvar-alteracao').attr('data-id',id)

    $.ajax({url:'',
        type:'POST',
        dataType:'json',
        data:{'act':'busca_anuncio',
              'id':id},
        success:function(retorno){
            $('.carregando').addClass('hide');
            $('#titulo_produto_upd').val(retorno.titulo);
            $('#categoria_produto_upd').val(retorno.categoria);
            $('#desc_produto_upd').val(retorno.descricao);
            $('#valor_produto_upd').val(retorno.valor);
            $('#'+retorno.estado+'_upd').attr('checked',true);

            var html = '';
            var imagens = retorno.nm_imagem.split('|');

            if(retorno.nm_imagem == ''){
                var qt = 0;
            }else{
                var qt = imagens.length;
            }

            for (var i in imagens){
                if(imagens[i] == '')
                    return false;

                html += "<a  class='delete_img' onClick='deletaFoto("+id+","+i+")'><img src='../../"+retorno.caminho_imagem+imagens[i]+"' onmouseover=this.src='../../"+retorno.caminho_imagem+"img_delete.png' onmouseout=this.src='../../"+retorno.caminho_imagem+imagens[i]+"' style='width: 100px; height: 100px; padding: 10px'></a>";
            }

            /*while(qt < 3){  console.log(qt)
                html2 = "<input type='file' class='form-control' id='foto"+qt+"' style='width: 400px;' />"
                $('.inputs').append(html2)
                qt++;
            }  */
            $('.fotos').append(html)
        },
        error:function(){

        }
    })
});

$('.act-excluir-anuncio').click(function(e){

    var id = $(this).data('id');
    if(confirm('Deseja realmente excluir este anúncio?')){
        $.ajax({url:'',
            type:'POST',
            dataType:'json',
            data:{'act':'exclui_anuncio',
                  'id':id},
            success:function(retorno){
                if(retorno.sucesso == true){
                    alert(retorno.mensagem);
                    window.location.reload();
                }else{
                    alert(retorno.mensagem);
                }
            },
            error:function(){
                alert('Erro no sistema! cod-G1');
            }
        })
    }
});

$('.act-salvar-alteracao').click(function(e){
    e.preventDefault();

    var titulo    = $('#titulo_produto_upd').val();
    var categoria = $('#categoria_produto_upd').val();
    var descricao = $('#desc_produto_upd').val();
    var estado    = $("input[name='tipo_produto_upd']:checked").val()
    var valor     = $('#valor_produto_upd').val();

    var id = $(this).data('id');

    var erro = false;

    /*if(nome == ''){
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
    } */

    if(!erro){
        addCarregando();
        var dados = {'id':id,
                     'titulo':titulo,
                     'categoria':categoria,
                     'descricao':descricao,
                     'estado':estado,
                     'valor':valor
                     };

        $.ajax({url:'',
            type:'POST',
            dataType:'json',
            data:{'act':'salvar_alteracao',
                  'dados':dados},
            success:function(retorno){
                 if(retorno.sucesso == true){
                    removeCarregando('body');
                    alert(retorno.mensagem);
                    window.location.reload();
                }else{
                    removeCarregando('body');
                    alert(retorno.mensagem);
                }
            },
            error:function(){
                removeCarregando();
                alert('Erro no sistema! cod-G1');
            }
        })
    }
});

var deletaFoto = function(id_anuncio,nu_foto){
    var dados = {'id_anuncio':id_anuncio,
                 'nu_foto':nu_foto
                 };
    if(confirm('Deseja realmente excluir esta foto?')){
        $.ajax({url:'',
            type:'POST',
            dataType:'json',
            data:{'act':'deletar_foto',
                  'dados':dados},
            success:function(retorno){
                 if(retorno.sucesso == true){
                    alert(retorno.mensagem);

                    var html = '';
                    var imagens = retorno.dados.nm_imagem.split('|');

                    for (var i in imagens){
                        if(imagens[i] == '')
                            return false;
                        else
                            html += "<a  class='delete_img' onClick='deletaFoto("+retorno.dados.id+","+i+")'><img src='../../"+retorno.dados.caminho_imagem+imagens[i]+"' onmouseover=this.src='../../"+retorno.dados.caminho_imagem+"img_delete.png' onmouseout=this.src='../../"+retorno.dados.caminho_imagem+imagens[i]+"' style='width: 100px; height: 100px; padding: 10px'></a>";
                    }
                    $('.fotos').html(html);

                }else{
                    alert(retorno.mensagem);
                }
            },
            error:function(){
                alert('Erro no sistema! cod-G1');
            }
        })
    }
}
