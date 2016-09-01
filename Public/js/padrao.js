$('.act-buscar').click(function() {
	
	var valorBusca = $('.busca').val();
	
	if(valorBusca.length < 3){
		alert('Digite pelo menos 3 caracteres');
		return false;
	}	
	
	url_busca = '/busca/'+valorBusca+'/';
	
	window.open(url_busca,'_self');
});