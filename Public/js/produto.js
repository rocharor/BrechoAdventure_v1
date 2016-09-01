$('.abre-descricao').click(function(e){
	e.preventDefault();
	$('#modal_descricao').modal();
});

$('.act-descricao').click(function(e){
    e.preventDefault();

    var produto_id = $(this).data('id');

    $.ajax({
        url: '/Produto/getDescricaoProduto/',
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
					$('.produto_fotos').append("<div class='item'><img src="+url+"/imagens/produtos/"+fotos[i]+" alt='' style='width:100%; height:400px'></div>")
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

            $('#modal_descricao').modal();
            
        },
        error: function(retorno){
            alert('Erro no sistema! cod-02')
        }
    })
});








