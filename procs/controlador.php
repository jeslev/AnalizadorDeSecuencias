<?php
include("MotifsTree.php");


//muestra valores de tablas en formulario de motifs
if(isset($_GET['action']) && !empty($_GET['action']) ){
	$action = $_GET['action'];
	switch ($action) {
		case 'listarMotifs':
			listarMotifs();
			break;	
		default:			
			break;
	}
}

function listarMotifs(){
	$html="<div class=\"col-xs-12\">
    <div class=\"form-group\">  
    <table id=\"tabla_listar\" class=\"table table-striped table-bordered display \" cellspacing=\"0\" width=\"100%\">
        <thead><th> Codigo </th><th> Nombre </th>  <th> Consenso</th></thead>
        <tbody>";
    $row=1;
    if (($handle = fopen("motifs.csv", "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
	        $num = count($data);
	        $row++;
	        $html.="<tr>";
	        
	        for ($c=0; $c < $num-1; $c++) {
	            $html.="<td>".$data[$c] . "</td>\n";
	        }
	        $html.="</tr>\n";
	    }
	    $html." </tbody></table></div></div>";
	    echo $html;
	    fclose($handle);
	}
	else{
		echo "No se encontraron datos en el servidor";
	}	
}

?>
