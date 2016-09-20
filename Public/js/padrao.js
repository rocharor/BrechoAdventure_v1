$('.act-buscar').click(function() {
	
	var valorBusca = $('.busca').val();
	
	if(valorBusca.length < 3){
		alert('Digite pelo menos 3 caracteres');
		return false;
	}	
	
	url_busca = '/busca/'+valorBusca+'/';
	
	window.open(url_busca,'_self');
});

$('.busca').keypress(function(e){	
	if(e.keyCode == 13){
		$('.act-buscar').click();
	}
});



var alertaPagina = function(texto,classe){

	if(texto == 'undefined' || classe == 'undefined'){
		return false;
	}

	switch (classe) {
	case 'success':
		icone = 'glyphicon-warning-sign'
		break;
	case 'error':
		icone = 'glyphicon-warning-sign'
		break;
	case 'warning':
		icone = 'glyphicon-warning-sign'
		break;
	default:
		icone = '';
		break;
	}

	$.notify({
        //icon: "glyphicon " + icone,
        message: texto,
    }, {
        element: 'body',
        type: classe,
        allow_dismiss: true,
        placement: {
            from: "top",
            align: "center"
        },
        offset: 20,
        spacing: 10,
        z_index: 1031,
        delay: 4000,
        timer: 1000,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        }
    });
}

var alertaComponente = function(id_componente,texto,classe,posicao){

	if (id_componente == 'undefined' || texto == 'undefined' || classe == 'undefined') {
		return false;
	}

	if(posicao == 'undefined'){
		posicao = 'top';
	}

	$("#"+id_componente).notify(
			texto,
            {
                clickToHide: true,
                autoHide: true,
                autoHideDelay: 5000,
                arrowShow: true,
                arrowSize: 5,
                position: posicao,            
                style: 'bootstrap',
                className: classe,
                showAnimation: 'slideDown',
                showDuration: 400,
                hideAnimation: 'slideUp',
                hideDuration: 200,
                gap: 2}
    );
}