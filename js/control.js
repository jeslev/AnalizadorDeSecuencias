
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

function checkStr(seq){
	var res = 1;
	if(seq.length==0) res=0;
	for(var x=0; x<seq.length;x++){
		if(seq[x]=='C' || seq[x]=='T' || seq[x]=='G' || seq[x]=='A' ||
			seq[x]=='c' || seq[x]=='t' || seq[x]=='g' || seq[x]=='a') res=1;
		else res = 0;
	}
	return res;
};

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
};


function fn_enviar(){

	//obtener datos
	var motifPatron=$("#inPatron").val(); 	//recoge motif
	if ( motifPatron.length == 0) { motifPatron="todos";};

	var distanciaporc = $("#distancia").val(); //recoge distancia
	var nropb = $("#nPares").val(); //recoge nro pares de base
	var tipoBusq = $('input[name=optionType]:checked', '#frm_enviar').val()
	
	var seqMelanogaster = $("#lblSeq1").val();
	//alert(motifPatron+" "+tipoBusq+" "+seqMelanogaster);
	
	var tipoFam = [0];
	var seqsFam = [seqMelanogaster];
	var nroTotalFam = $("#panelFam .form-group").length-1;    
	for (var i = 0; i < nroTotalFam; i++) {
		var tmpseq = $("#lblSeq"+(i+2)).val();
		var tmpind = $("#selecSeq"+(i+2) ).val();
		seqsFam.push(tmpseq);
		tipoFam.push(tmpind);
	};

	//validar
	if(!distanciaporc || !isNumber(distanciaporc) || distanciaporc<0 || distanciaporc>100) alert("Porcentaje de distancia no valido.");
	else if(!nropb || !isNumber(nropb) || nropb<0) alert("Número de bases invalido.");
	else {
		var res = 1;
		res = checkStr(seqsFam[0]);
		if(res==0){
				alert("Secuencia #1 no es valida.");
				return false;
		}
		var checkedTipo = [0];
		var checkedFam = [seqsFam[0]];
		for (var i = 1; i< seqsFam.length; i++) {
			if( tipoFam[i]==-1){
				alert("No se especifico ortólogo para familia #"+(i+1));
				return false;
			}
			res = checkStr(seqsFam[i]);
			if(res==0){
				alert("Secuencia #"+(i+1)+" no es valida.");
				return false;
			}
			checkedTipo.push(tipoFam[i]);
			checkedFam.push(seqsFam[i]);
		};
		//si datos son validos
		if(res==1){
		
			$('#resultado').modal('show');
		}
	}
	/*var str = $("#frm_enviar").serialize();    	
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

