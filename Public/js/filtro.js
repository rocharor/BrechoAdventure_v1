$('.act-filtro').click(function(e) {
	var $checks = [];
	$('.act-filtro:checked').each(function() {
		$checks.push($(this).val());
	});
	
	if($checks.length == 0){
		var url_filtro = '/produto/todosProdutos/pg/1/';
	}else{
		var url_filtro = '/produto/todosProdutos/categoria/' + $checks.join(',')+"/";
	}
	
	
	window.open(url_filtro,'_self');
});