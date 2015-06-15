
$( document ).ready(function() {
    fn_listar_tabla();  
});

function fn_listar_tabla(){
	//var str = $("#frm_buscar").serialize();
	$.ajax({
		url: 'procs/controlador.php',
		type: 'get',
        data: {action: 'listarMotifs'},
		success: function(data){
			$("#div_resultado_listar").html(data);			
			$('#tabla_listar').dataTable();
			$('#tabla_listar tbody').on( 'click', 'tr', function () {
	        	$(this).toggleClass('selected');
	        	$('#inPatron').val($(this).find("td").eq(2).html());
	        	$('#div_resultado').html('');

   			} );
		},
		 error: function(xhr, status,errorThrown ) {
			alert( "Problema de conexion!" );
			console.log( "Error: " + errorThrown );
			console.log( "Status: " + status );
			console.dir( xhr );	
		}

	});
	
}


function fn_enviar(){    
	var value=$("#inPatron").val(); 	
	//$('#frm_enviar').append("<input type=\"hidden\" id=\"inPatron\" name=\"inPatron\" value=".value."");	
    var str = $("#frm_enviar").serialize();    	
    console.log(str);
	$.ajax({
		url: 'php/controlador.php',
		type: 'GET',
		data: str+'&inPatron='+value,
		success: function(data){
			$("#div_resultado").html(data);			
		}
	});

};




/*function fn_cerrar(){
	$.unblockUI({ 
		onUnblock: function(){
			$("#div_oculto").html("");
		}
	}); 
};
/*
function fn_mostrar_frm_agregar(){
	$("#div_oculto").load("ajax_form_agregar.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
			css:{
				top: '20%'
			}
		}); 
	});
};

function fn_mostrar_frm_modificar(ide_per){
	$("#div_oculto").load("ajax_form_modificar.php", {ide_per: ide_per}, function(){
		$.blockUI({
			message: $('#div_oculto'),
			css:{
				top: '20%'
			}
		}); 
	});
};

/*function fn_paginar(var_div, url){
	var div = $("#" + var_div);
	$(div).load(url);
	/*
	div.fadeOut("fast", function(){
		$(div).load(url, function(){
			$(div).fadeIn("fast");
		});
	});
	*/
//}
/*
function fn_eliminar(ide_per){
	var respuesta = confirm("Desea eliminar a esta persona?");
	if (respuesta){
		$.ajax({
			url: 'ajax_eliminar.php',
			data: 'ide_per=' + ide_per,
			type: 'post',
			success: function(data){
				if(data!="")
					alert(data);
				fn_buscar()
			}
		});
	}
}
*/
