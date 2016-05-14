$('.abre-descricao').click(function(e){
	e.preventDefault();
	$('#modal_descricao').modal();
});

$('.act-descricao').click(function(e){
    e.preventDefault();

    var produto_id = $(this).data('id');

    $.ajax({
        url: url_descricao,
        dataType: 'json',
        type: 'POST',
        data: {'produto_id': produto_id},
        success: function(retorno){

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







/**
* Atualiza produtos
*/
var atualizaAnuncios = function(categoria){
    var filtros = $('.chk_filtro');
    window.location.href = '/brechoAdventure/site/produtos/'+categoria;

    addCarregando();
    setTimeout (
        function (){
           removeCarregando();
        },
    500);

    /*$.ajax({
        url:'/brechoadventure/site/produtos/',
        type:'POST',
        data:{'dados':categoria,
        'act':'filtro'},
        success:function(){

        }
    })*/
}

