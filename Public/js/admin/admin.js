var filtroAdmin = function(tipo,filtro){
	var url_filtro = url + '/admin/'+tipo+'/'+filtro+'/';
	window.open(url_filtro,'_self');
	
}

/*$('.act-deletar-produto').click(function(){
	
	console.dir('deletar');
	var produto_id = $this.value();
	$.ajax({
        url: '',
        dataType: 'json',
        type: 'POST',
        data: {	'nome':nome },
        success: function(retorno){
            if(retorno.sucesso == true){                
                window.location.reload();
            }else{            	
                alert(retorno.msg);
            }
        },
        error: function(retorno){
            alert('Erro no sistema! cod-02')
        }
    })
});
*/

$('.act-aprovar-produto').click(function(e){
	e.preventDefault();
	
	var $checks = $('.chk_aprovar:checked');

    var $arrProdutos = [];
    $checks.each(function(){
        $arrProdutos.push($(this).val());
    });
    
    $.ajax({
        url: url+'/admin/aprovar/',
        dataType: 'json',
        type: 'POST',
        data: {'arrChecks':$arrProdutos},
        success: function(retorno){
        	/**/
			console.dir(retorno);
            if(retorno){                
            	alert('Produto aprovado com sucesso.');
            	window.location.reload();
            }else{            	
                alert('Erro ao aprovar produto.');
            }
        },
        error: function(retorno){
            alert('Erro no sistema! cod-02')
        }
    });

});