<?php
class DatosMotif{
	
	private $buscar;
	private $resultado = array();

	function __construct($seq){
		$this->buscar = $seq;
		$this->searchMotif();
	}

	function searchMotif(){		

		if( strcmp($this->buscar, "todos") ==0){
			/*encuentra todos los motifs y sus datos */
		    if (($handle = fopen("procs/motifs.csv", "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
			        $datos = array();
			        $num = count($data);
			        for ($c=0; $c < $num-1; $c++) {
			            $datos[] = $data[$c];
			        }
			        $this->resultado[] = $datos;
			    }
			    fclose($handle);
			}
		}else{
			/* busca un motif especifico */
			if (($handle = fopen("procs/motifs.csv", "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
			        $num = count($data);
			        if(strcmp($data[2], $this->buscar)==0){ //verifica si es el que buscamos
				        $datos = array();
				        for ($c=0; $c < $num-1; $c++) {
				            $datos[] = $data[$c];
				        }
				        $this->resultado[] = $datos;
				        break;
				    }
			    }
			    fclose($handle);
			}

		}

	}

	public function obtenerResultados(){
		return $this->resultado;
	}

}
?>
