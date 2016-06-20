$('.act-buscar').click(function() {
	/**/
	console.dir('akii');	
	var valorBusca = $('.busca').val();
	
	if(valorBusca.length < 3){
		alert('Digite pelo menos 3 caracteres');
		return false;
	}	
	
	url_busca = url +'/busca/'+valorBusca+'/';
	
	window.open(url_busca,'_self');
});