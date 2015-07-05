
<?php
include("Comparator.php");
error_reporting(E_ALL);

//maneja las busquedas de 1 consensus en 1 array de secuencias
class MotifsTree {
	private $numFam;
	private $seqs = array();
	private $lens = array();
	private $motifsFound = array();
	private $distPercent;
	private $txtpatr1;//  "NNNNNNATTANYNNNN" ;//"TTTW";//"GRCGHCVSWNTGTCTG";
	private $allThePaths = array();
	private $lenConsensus;
	private $radioPB;
	private $zonaPromotora;
	private $mejorSecuencia = array();
	private $mejorR = 0;
	private $mejorMaxY = array();
	private $normalYValues = array();
    private $mejorMean;
	private	$mejorDesviacion;
	private $colaPosiciones = array();
    private $priorityQueue;
    private $arrayCola = array();
	
	/*Constructor
	     - numfam = Numero de familias(secuencias) a analizar
	     - seq = Array de las secuencias
	     - disPercent = Porcentaje de distancia para realizar combinaciones
	     - radioPb = Radio de pares de bases para analizar
	     - lenConsensus = Longitud de la secuencia consensus
	*/
	function __construct($motifVar, $num, $arraySeq,$d, $radioPb, $zonaProm){
		$this->numFam = $num;
		$this->txtpatr1 = $motifVar;
		$this->seqs = $arraySeq;
		$this->distPercent = $d;
		$this->radioPB = $radioPb;
		$this->lenConsensus = strlen($this->txtpatr1);
		$this->zonaPromotora = $zonaProm;
		$this->priorityQueue = new SplPriorityQueue();
        $this->findMotifs();		
	}
	/*Para cada secuencia realiza la busqueda de coincidencias de consensus
	  como expresion regular.
	    - motifsFound = array de posiciones de todas las secuencias (array de arrays)
	*/
	private function findMotifs(){
		foreach ($this->seqs as $seq) {
			$this->lens[] = strlen($seq);
			$tmp = new Comparator($seq,$this->txtpatr1); //NOAMLR: creo que este comentario está de mas: extender para todos los consensus
			$this->motifsFound[] = $tmp->getMatches();
		}
	}

	/* Retorna array de posiciones de coincidencia */
	public function getMotifs(){
		return $this->motifsFound;
	}

	private function getBorders($pos, $d, $txtlen, $prelen){
		$nvar=0;
		if($this->zonaPromotora=="zonapromotora"){
		    $len = ( ($prelen - $pos) *$d*1.0)/100.0;
		    $nvar =$prelen -$txtlen;
			}
		else
		    $len = ($pos*$d*1.0)/100.0;
		$left = max($nvar+$pos-$len, 0);
		$right = min($nvar+$pos+$len,$txtlen-1);
		return array($left, $right);
	}
	
	/*
	Generamos todos los 'caminos'
	Recorremos solo motifsFound[0] porque generamos combinaciones desde la primera familia (indice 0)
	*/
	public function generateMotifsPaths(){
		foreach($this->motifsFound[0] as $motif){
			$path = new SplQueue();
			$path->push($motif);
			$this->recursiveBuilding($this->distPercent, $motif, 1, $path);
		}
		$auxiliar = $this->priorityQueue;
        $auxiliar->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
        //$this->priorityQueue->setExtractFlags(SplPriorityQueue::EXTR_PRIORITY);    
        while ($auxiliar->valid()) {
            //echo "<br/>";
            //print_r($auxiliar->current());
            $actual = $auxiliar->current();
            $this->arrayCola[] = array($actual['data'][0], $actual['data'][1], $actual['data'][2], $actual['priority']*-1);
            $auxiliar->next();
        }		
	}
	
	/*
	Funcion recursiva para generar todas las combinaciones
	El resultado es almacenado en allThePaths
	*/
	private function recursiveBuilding($d, $pos, $lvl, $path){
		
		$result=array();

		if( $lvl>=$this->numFam) {
			$finalPath = array();

			foreach ($path as $elem) {
				$finalPath[] = $elem;
			}
			$result=$this->getStringPaths($finalPath);
	//		foreach($result as $element)	echo $element."<br>";
			$maxY=array();
			$this->contATGC($result, 0, $this->radioPB, $maxY);
			$maxY[] = 1;
			
			$this->contATGC($result, $this->radioPB+$this->lenConsensus, 2*$this->radioPB+$this->lenConsensus, $maxY);
			
			//echo var_dump($maxY);
			
			$XValuesNormal = array();
			$XValues = array();
			
			for($i=-($this->radioPB+5);$i<=($this->radioPB+5);$i++){
                $XValuesNormal[] = $i;
            }        			
			for($i=-$this->radioPB; $i<=$this->radioPB; $i++){
				$XValues[] = $i;
			}
			//$desviationEstandar = $this->standardDeviation($XValues);
			$mean = $this->meanValue($XValues, $maxY);
			$desviationEstandar = $this->standardDeviation($XValues, $mean);
			
			$actualR = $this->getRValue($XValues, $maxY, $mean, $desviationEstandar);
//			$xxx=1;  foreach ($maxY as $m){ echo $m.", ";  $xxx++;  }echo "<br>";
			if($this->mejorR < $actualR){
			    $this->mejorMean = $mean;
			    $this->mejorDesviacion = $desviationEstandar;
			    $this->mejorR = $actualR;
			    $this->normalYValues = $this->calcularNormalValues($XValuesNormal, $mean, $desviationEstandar); 
			    $this->mejorMaxY = $maxY;
                $this->colaPosiciones = array();
                $ii = 0;
			    foreach($finalPath as $posicion){
                    if($this->zonaPromotora=="zonapromotora")
			            $this->colaPosiciones[] = strlen($this->seqs[$ii]) - $posicion;
			        else
			            $this->colaPosiciones[] = $posicion;
			        $ii++;
			    }
			    $this->priorityQueue->insert(array($this->normalYValues, $this->mejorMaxY, $this->colaPosiciones), -1*$this->mejorR);
			    if($this->priorityQueue->count()>5){
			        $this->priorityQueue->extract();
			    }
			}
			return;
		}

		$params = $this->getBorders($pos,$d, $this->lens[$lvl],$this->lens[$lvl-1]);
		foreach($this->motifsFound[$lvl] as $motif){
			if ($motif > $params[0] && $motif < $params[1]){
				$tmp_path = $path;
				$tmp_path->push($motif);
				$this->recursiveBuilding($d,$motif,$lvl+1, $tmp_path);
				$tmp_path->pop();
			}
		}
	}

   // public function getPriorityQueue(){
        
   // }
    
    public function getArrayCola(){      
        return $this->arrayCola;
    }

	public function contATGC($result, $start, $end, &$maxY){
		for($i=$start;$i<$end;$i++){
			$contA=0; $contT=0; $contG=0; $contC=0;
			foreach($result as $element){
				if($element[$i]=='A') $contA++;
				if($element[$i]=='T') $contT++;
				if($element[$i]=='G') $contG++;
				if($element[$i]=='C') $contC++;
			}
			//echo $contA.' '.$contT.' '.$contG.' '.$contC.' '.'<br>';
			//$maxY[] = max(max($contA, $contT),max($contG, $contC))/($contA+$contT+$contG+$contC);
			$maxY[] = max(max($contA, $contT),max($contG, $contC))/(count($result));
		}
	} 

	public function standardDeviation(&$sample, $mean){
	    if(is_array($sample)){
//			echo "MEDIA: ".$mean."<br>";
			foreach($sample as $key => $num) $devs[$key] = pow($num - $mean, 2);
			return sqrt(array_sum($devs) / (count($devs) - 1));
		}
	}

	public function calcularNormalValues(&$Xaxis, $media, $sigma){
		$fX = array();
		//devuelve los valores escalados hacia 1		
		for($i=0;$i<count($Xaxis);$i++)
			$fX[] = pow(M_E, -(0.5/pow($sigma, 2))*pow($Xaxis[$i]-$media, 2));
		//$xxx=1;  echo 'sdfasdfasdfasf '.count($fX).': '; foreach ($fX as $m){ echo $m.", ";  $xxx++;  }echo "<br>";
		return $fX;
	}

	public function meanValue(&$pesos, &$maxY){
		$numerador = 0;
		$denominador = array_sum($maxY);
		for($i=0;$i<count($maxY);$i++)
			$numerador += $pesos[$i]*$maxY[$i];
		return $numerador/$denominador;
	}

    public function covarianza(&$X, &$Y, $meanX, $meanY){
        $res = 0;
        for($i=0; $i<count($X); $i++)
            $res+= ($X[$i]-$meanX)*($Y[$i]-$meanY);
        return $res/(count($X)-1);
    }

    public function getRValue(&$valsX, &$freqY, $meanVal, $desviStan){
        $X = $freqY;
        $Y = $this->calcularNormalValues($valsX, $meanVal, $desviStan);
        $meanX = $meanVal;
        $meanY = $this->meanValue($valsX, $Y);
        $sigmaXY = $this->covarianza($X, $Y, $meanX, $meanY);
        $sigmaX = $this->standardDeviation($X,$meanX);
        $sigmaY = $this->standardDeviation($Y,$meanY);
        return ($sigmaXY)/($sigmaX*$sigmaY);               
    }


	public function getPaths(){
		return $this->allThePaths;
	}
	public function getColaPosiciones(){
    	return $this->colaPosiciones;
	}
	
	/*	Del array de combinaciones se generan los caminos en forma listada */
	public function getStringPaths($path){
			$result = $this->getOnlyPath($path);
		return $result;

	}
	
	public function getStringMeans(){
	    $MeanString = '';	   
	    for($i = 0; $i<count($this->mejorMaxY); $i++){
	        if($i!=0) 
	            $MeanString=$MeanString.', ';
	        $MeanString = $MeanString.$this->mejorMaxY[$i]; 
	    }	  	    
	    return $MeanString;
	}

    /*Retorna los valores Y de la función normal, para la gráfica*/
    public function getNormalValues(){
	    $normalValuesString = '';
	    for($i = 0; $i<count($this->normalYValues); $i++){
	        if($i!=0) 
	            $normalValuesString=$normalValuesString.', ';
	        $normalValuesString = $normalValuesString.$this->normalYValues[$i]; 
	    }
        return $normalValuesString;
	}
	
	
	//Retorna el mejor parámetro de correlación.
	public function getMejorR(){
        return $this->mejorR;
	}
	
	

	/*
	Se itera el array de combinaciones para generar el listado de caminos
	*/
	private function getOnlyPath($path){
		$index = 0;
		$result = array();
		foreach ($path as $elem){
			$left = $elem;
			$right = $elem+$this->lenConsensus;
			$seqlen = strlen($this->seqs[$index]);
			
			//Faltan datos a la izquierda
			$realDisLeft = $left- $this->radioPB;
			$stringLeft = '';
			for($i=0; $i< (-$realDisLeft); $i++)
			    $stringLeft = $stringLeft.'X';
			
			//faltan datos a la derecha    
			$realDisRight = $right + $this->radioPB;
			$stringRight = '';
			for($i=$seqlen; $i<abs($realDisRight); $i++)
			    $stringRight = $stringRight.'X'; 
			
			
			
			$left = max(0, $left- $this->radioPB);
			$right = min($right + $this->radioPB, $seqlen);
			$stringToAnalyze = substr($this->seqs[$index],$left,$right-$left);
			
			
			
			$result [] = $stringLeft.$stringToAnalyze.$stringRight;
			$index = $index+1;
		}
		return $result;
	}
	
	
	/*
   	Función que obtiene la recta que más se ajusta a los puntos y el margen error que distan los puntos de la recta aproximada.    
   	- pos: Array de enteros en el cual cada uno indica la posición del motif dentro de la cadena. No importa si dista de la izquierda o de la derecha, es decir no importa si se trata de zona promotora o intron.
   	       Por ejemplo, pos[1] tiene la posición de motif en la primera cadena evaluada, pos[2] en la segunda cadena evaludada, ...
   	*/
   	public function valoraCamino($pos) {
      		$n = count($pos); // Longitud de la cadena. Cantidad de valores que hay en la cadena, el cual es el número de familias evaluadas.
      		
      		$sx = 0; // Valor de la sumatoria de los valores de x.
      		$sxx = 0; // Valor de la sumatoria de los cuadrados de x.
      		$sy = 0; // Valor de la sumtaroria de los valores de y.
	        $sxy = 0; // Valor de la sumatoria de los valores de x por y.
	        
		for ( $i = 0; $i < $n; $i++ ) {
         		$sx = $sx + 10 * $i; // Se agrega de 10 en 10 porque brindará un pendiente de mayor exactitud.
         		$sy = $sy + $pos[$i];
         		$sxx = $sx + 100 * $i * $i;
         		$sxy = $sxy + 10 * $i * $pos[$i];
      		}

      		$ds = $sx * $sx - $n * $sxx; // Determinante del sistema.
      		$dm = $sy * $sx - $n * $sxy; // Determinante de la pendiente.
      		$dd = $sx * $sxy - $sy * $sxx; // Determinante del desfase.

      		$m = $dm / $ds; // Pendiente de la recta que más se asemeja.
      		$df = $dd / $ds; // Desfase de la recta, es decir, y=m*x+d.

      		$er = 0.0; // Margen de error de la aproximación.
      		for ( $i = 0; $i < $n; $i++ ) {
         		$er = $er + abs( $pos[$i] - ( $m * ( $i * 10 ) + $df ) );
      		}
      
      		return array( $m, // Pendiente, pues se valora el camino con menor pendiente.
            	$er ); // Error, pues no sirve un camino con pendiente 0 si los puntos son demasiados dispersos.
   	}
}
?>
