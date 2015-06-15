
$( document ).ready(function() {
    fn_listar_tabla();  
});

function fn_listar_tabla(){
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
	$('#resultado').modal('show');
    /*var value=$("#inPatron").val(); 	
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
	});*/

};