$('.act-excluir-produto').click(function(e){

    var produto_id = $(this).attr('data-produto-id');

    if(confirm('Deseja realmente excluir este produto?')){
        $.ajax({
        	url: url_deletar,
            type:'POST',
            dataType:'json',
            data:{'produto_id':produto_id},
            success:function(retorno){
                if(retorno.sucesso == true){
                    alert(retorno.msg);
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

$("#valor_produto_update").maskMoney({prefix:'R$ ',thousands:'.',decimal:','});

$('.act-excluir-foto').click(function(){
	
	var produto_id = $('.act-excluir-foto').attr('data-produto-id');
	var nm_foto = $(this).attr('data-foto');
	
	$.ajax({url:url_deletar_foto,
        type:'POST',
        dataType:'json',
        data:{'nm_foto':nm_foto, 
        	  'produto_id':produto_id},
        success:function(retorno){
        	if(retorno.sucesso = true){
        		alert(retorno.msg);
        		window.location.reload();
        	}else{
        		alert(retorno.msg);
        	}
        },
        error:function(){
            alert('Erro no sistema!');
        }
	});
	
}); 





$('.act-alterar-produto').click(function(e){
    e.preventDefault();

    var produto_id = $(this).attr('data-produto-id');
    var titulo    = $('#titulo_produto_update').val();
    var categoria = $('#categoria_produto_update').val();
    var descricao = $('#desc_produto_update').val();
    var estado    = $("input[name='tipo_produto_update']:checked").val()
    var valor     = $('#valor_produto_update').val();    

    var erro = false;

    
    if(titulo == ''){
        $('#titulo_produto_update').parent().addClass('has-error');
        alert('Campo titulo é obrigatório');
        erro = true;
        return false;
    }
    if(categoria == ''){
        $('#categoria_produto_update').parent().addClass('has-error');
        alert('Campo categoria é obrigatório');
        erro = true;
        return false;
    }
    if(descricao == ''){
        $('#desc_produto_update').parent().addClass('has-error');
        alert('Campo descricao é obrigatório');
        erro = true;
        return false;
    }
    if(estado == ''){
        $("input[name='tipo_produto_update']").parent().addClass('has-error');
        alert('Campo estado é obrigatório');
        erro = true;
        return false;
    }
    if(valor == ''){
        $('#valor_produto_update').parent().addClass('has-error');
        alert('Campo valor é obrigatório');
        erro = true;
        return false;
    }  
    

    if(!erro){

        $.ajax({url:url_alterar_produto,
            type:'POST',
            dataType:'json',
            data:{'produto_id':produto_id,
                  'titulo':titulo,
                  'categoria':categoria,
                  'descricao':descricao,
                  'estado':estado,
                  'valor':valor
                },
            success:function(retorno){
                 if(retorno.sucesso == true){
                    alert(retorno.mensagem);
                    window.location.reload();
                }else{
                    alert(retorno.mensagem);
                }
            },
            error:function(){
                alert('Erro no sistema!');
            }
        })
    }
});


