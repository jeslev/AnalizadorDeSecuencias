
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
	private $txtpatr1="TTTW";//"GRCGHCVSWNTGTCTG";
	private $allThePaths = array();
	private $lenConsensus;
	private $radioPB;

	/*
	Constructor
	 - numfam = Numero de familias(secuencias) a analizar
	 - seq = Array de las secuencias
	 - disPercent = Porcentaje de distancia para realizar combinaciones
	 - radioPb = Radio de pares de bases para analizar
	 - lenConsensus = Longitud de la secuencia consensus
	*/
	function __construct($num, $arraySeq,$d, $radioPB){
		$this->numFam = $num;
		$this->seqs = $arraySeq;
		$this->distPercent = $d;
		$this->radioPB = $radioPB;
		$this->lenConsensus = strlen($this->txtpatr1);
		$this->findMotifs();
	}

	/*
	Para cada secuencia realiza la busqueda de coincidencias de consensus
	como expresion regular.
	- motifsFound = array de posiciones de todas las secuencias (array de arrays)
	*/
	private function findMotifs(){
		foreach ($this->seqs as $seq) {
			$this->lens[] = strlen($seq);
			$tmp = new Comparator($seq,$this->txtpatr1); //extender para todos los consensus
			$this->motifsFound[] = $tmp->getMatches();		
		}
	}

	/* Retorna array de posiciones de coincidencia */
	public function getMotifs(){
		return $this->motifsFound;
	}


	private function getBorders($pos, $d, $txtlen, $prelen){
		$len = ( ($prelen - $pos) *$d*1.0)/100.0;
		$left = max($pos-$len, 0);
		$right = min($pos+$len,$txtlen-1);
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
	}

	/*
	Funcion recursiva para generar todas las combinaciones
	El resultado es almacenado en allThePaths
	*/
	private function recursiveBuilding($d, $pos, $lvl, $path){
		
		if( $lvl>=$this->numFam) {
			$finalPath = array();

			foreach ($path as $elem) {
				$finalPath[] = $elem;
			}
			$this->allThePaths[] = $finalPath;
			return ;
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

	public function getPaths(){
		return $this->allThePaths;
	}
	/*
	Del array de combinaciones se generan los caminos en forma listada
	*/
	public function getStringPaths(){
		$result = array();
		foreach ($this->allThePaths as $path) {
			$result[] = $this->getOnlyPath($path);
		}
		return $result;

	}

	/*
	Se itera el array de combinaciones para generar el listado de caminos
	*/
	private function getOnlyPath($path){
		$index = 0;
		$result = array();
		foreach ($path as $elem) {
			$left = $elem;
			$right = $elem+$this->lenConsensus;
			$seqlen = strlen($this->seqs[$index]);
			$left = max(0, $left- $this->radioPB);
			$right = min($right + $this->radioPB, $seqlen);
			$stringToAnalyze = substr($this->seqs[$index],$left, $right-$left);
			$result [] = $stringToAnalyze;
			$index = $index+1;
		}
		return $result;
	}
}
?>