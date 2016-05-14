
$('.act-favorito').click(function(){

   var status = $(this).attr('data-status');
   var produto_id = $(this).attr('data-produto-id');
   var usuario_id = $(this).parent().attr('data-usuario-id');
   $.ajax({
        url: url_favorito,
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

