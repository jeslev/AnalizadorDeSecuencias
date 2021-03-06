<?php
error_reporting(E_ALL);
class Comparator {
		
	private $text;
	private $pattern;
	private $result;
	/*
	Constructor
	- text = Secuencia para analizar
	- pattern = Consensus que se buscara
	*/
	function __construct($seq, $pattern){	
		$this->text =$seq;
		$this->pattern=$pattern;
		$this->doSearch();	
	}
	
	
	/*
	Busca las coincidencias de consensus,
	por medio de expresiones regulares
	$matches[0] = retorna valores de coincidencia al 100%
	$matches[1] = retorna valores de coincidencia parcial (por parentesis)
	result = guarda posiciones de las coincidencias encontradas
	*/
	private function doSearch(){ 
		$sp = $this->pattern;
		$replacements = array(
						"A"=>"A",
						"T"=>"T",
						"G"=>"G",
						"C"=>"C",
						"R"=>"[GA]",
						"Y"=>"[TC]",
						"K"=>"[GT]",
						"M"=>"[AC]",
						"S"=>"[GC]",
						"W"=>"[AT]",
						"B"=>"[GCT]",
						"D"=>"[AGT]",
						"H"=>"[ACT]",
						"V"=>"[ACG]",
						"N"=>"[AGTC]" );
		$patronReg = "*";
		$letters = str_split($sp);
		foreach ($letters as $letter) {
			$patronReg = $patronReg.$replacements[$letter];	
		}
		$patronReg = $patronReg."*";
		preg_match_all($patronReg, $this->text, $matches, PREG_OFFSET_CAPTURE);
		$this->result = $matches[0];
	}
	
	/*
	Devuelve posiciones
	$match[0] devuelve cadena
	$match[1] devuelve posicion de cadena
	*/
	public function getMatches(){
		$res = array();
		foreach ($this->result as $match) {
			$res[] = $match[1];
		}
		return $res;
	}
	public function getSequence() {
		return $this->text;
	}
	
	public function setSequence($x){	
		$this->text=$x;		
	}
	
	public function getPattern() {
		return $this->pattern;
	}
	
	public function setPattern($x){
		$this->pattern=$x;		
	}
}
?>

