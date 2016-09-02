$('.act-excluir-produto').click(function(e) {

	var produto_id = $(this).attr('data-produto-id');

	if (confirm('Deseja realmente excluir este produto?')) {
		$.ajax({
			url : '/minha-conta/meus-produtos/deletarProduto/',
			type : 'POST',
			dataType : 'json',
			data : {
				'produto_id' : produto_id
			},
			success : function(retorno) {
				if (retorno.sucesso == true) {
					alert(retorno.msg);
					window.location.reload();
				} else {
					alert(retorno.mensagem);
				}
			},
			error : function() {
				alert('Erro no sistema! cod-G1');
			}
		})
	}
});

$('.act-excluir-foto').click(function() {

	if (confirm('Deseja realmente excluir esta foto?')) {
		var produto_id = $('.act-excluir-foto').attr('data-produto-id');
		var nm_foto = $(this).attr('data-foto');
		$.ajax({
			url :'/minha-conta/meus-produtos/deletarFoto/',
			type : 'POST',
			dataType : 'json',
			data : {
				'nm_foto' : nm_foto,
				'produto_id' : produto_id
			},
			success : function(retorno) {
				if (retorno.sucesso = true) {
					window.open('/minha-conta/meus-produtos/meusProdutosEditar/produto/'+produto_id,'_self');
				} else {
					alert(retorno.msg);
				}
			},
			error : function() {
				alert('Erro no sistema!');
			}
		});
	}
});

$('.act-alterar-produto').click(function(e) {
	e.preventDefault();

	var produto_id = $(this).attr('data-produto-id');
	var titulo = $('#titulo_produto_update').val();
	var categoria = $('#categoria_produto_update').val();
	var descricao = $('#desc_produto_update').val();
	var estado = $("input[name='tipo_produto_update']:checked").val()
	var valor = $('#valor_produto_update').val();

	var $form = $('[name=formEditarProduto]');

	if (titulo == '') {
		$('#titulo_produto_update').parent().addClass('has-error');
		alert('Campo titulo é obrigatório');
		return false;
	}
	if (categoria == '') {
		$('#categoria_produto_update').parent().addClass('has-error');
		alert('Campo categoria é obrigatório');
		return false;
	}
	if (descricao == '') {
		$('#desc_produto_update').parent().addClass('has-error');
		alert('Campo descricao é obrigatório');
		return false;
	}
	if (estado == '') {
		$("input[name='tipo_produto_update']").parent().addClass('has-error');
		alert('Campo estado é obrigatório');
		return false;
	}
	if (valor == '') {
		$('#valor_produto_update').parent().addClass('has-error');
		alert('Campo valor é obrigatório');
		return false;
	}

	$form.submit();
});

$("#valor_produto_update").maskMoney({
	prefix : 'R$ ',
	thousands : '.',
	decimal : ','
});