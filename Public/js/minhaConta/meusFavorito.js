
$('.act-favorito').click(function(){

   var status = $(this).attr('data-status');
   var produto_id = $(this).attr('data-produto-id');
   var usuario_id = $(this).parent().attr('data-usuario-id');
   $.ajax({
        url: url+'/minha-conta/meus-favoritos/setFavorito/',
        dataType: 'json',
        type: 'POST',
        data: {'usuario_id':usuario_id,
               'status': status,
               'produto_id':produto_id},
        success: function(retorno){
            if(status == 0){
                var link = $('.favorito-ativo-'+produto_id);
                var div_imagem = $('.favorito-ativo-'+produto_id).find('.img-ativo');
                link.attr('data-status',1).addClass('favorito-inativo-'+produto_id).removeClass('favorito-ativo-'+produto_id);
                div_imagem.addClass('img-inativo').removeClass('img-ativo');
            }else{
                var link = $('.favorito-inativo-'+produto_id);
                var div_imagem = $('.favorito-inativo-'+produto_id).find('.img-inativo');
                link.attr('data-status',0).addClass('favorito-ativo-'+produto_id).removeClass('favorito-inativo-'+produto_id);
                div_imagem.addClass('img-ativo').removeClass('img-inativo');
            }
        },
        error: function(retorno){
            alert('Erro no sistema! cod-02')
        }
    });
});

$('.act-favorito-deslogado').click(function(){
   alert('Necessário estar logado para favoritar.');
});

$('.act-excluir-favorito').click(function(e){
	e.preventDefault();
	if(confirm('Deseja realmente excluir este item dos favoritos?')){	
		
		var produto_id = $(this).attr('data-produto-id');
		
		$.ajax({
	        url: url+'/minha-conta/meus-favoritos/setFavorito/',
	        dataType: 'json',
	        type: 'POST',
	        data: {'status': 0,
	               'produto_id':produto_id},
	        success: function(retorno){
	        	window.location.reload();
	        },
	        error: function(retorno){
	            alert('Erro no sistema! cod-02')
	        }
	    });	
	}
	
});

$('.act-ver-favorito').click(function(e){
    e.preventDefault();

    var produto_id = $(this).attr('data-produto-id');

    $.ajax({
        url: url+'/minha-conta/produto/getDescricaoProduto/',
        dataType: 'json',
        type: 'POST',
        data: {'produto_id': produto_id},
        success: function(retorno){  
			var fotos = retorno.nm_imagem.split('|');
			$('.produto_fotos').html('');
			$('.indicadores').html('');
			for(var i in fotos){
				if(i == 0){
					$('.produto_fotos').append("<div class='item active '><img src="+url+"/imagens/produtos/"+fotos[i]+" alt='' style='width:100%; height:400px'></div>")
					$('.indicadores').append("<li data-target='#carousel-example-generic' data-slide-to='"+i+"' class='active'></li>")
				}else{
					$('.produto_fotos').append("<div class='item'><img src="+url+"/imagens/produtos/"+fotos[i]+" alt='' style='width:100%; height:400px'></div>");
					$('.indicadores').append("<li data-target='#carousel-example-generic' data-slide-to='"+i+"' class=''></li>")
				}
			}        	
        	
        	$('.produto_titulo').html(retorno.titulo)
            $('.produto_descricao').html(retorno.descricao)
            $('.produto_estado').html(retorno.estado)
            $('.produto_valor').html('R$ '+retorno.valor)

            $('.produto_nome').html(retorno.nome)
            $('.produto_email').html(retorno.email)
            $('.produto_telefone').html(retorno.fixo+" / "+retorno.cel)

            $('#modal_descricao_favorito').modal();
            
        },
        error: function(retorno){
            alert('Erro no sistema! cod-02')
        }
    })
});


