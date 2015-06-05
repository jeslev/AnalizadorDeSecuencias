
<?php
include("Comparator.php");
error_reporting(E_ALL);

class MotifsTree {
	private $numFam;
	private $seqs = array();
	private $lens = array();
	private $motifsFound = array();
	private $distPercent;
	private $txtpatr1="TTTW";//"GRCGHCVSWNTGTCTG";
	
	function __construct($num, $arraySeq){
		$this->numFam = $num;
		$this->seqs = $arraySeq;
		$this->findMotifs();
	}

	private function findMotifs(){
		foreach ($this->seqs as $seq) {
			$this->lens[] = strlen($seq);
			$tmp = new Comparator($seq,$this->txtpatr1);
			$this->motifsFound[] = $tmp->getMatches();		
		}
	}

	public function getMotifs(){
		return $this->motifsFound;
	}


	private function getBorders($pos, $d, $txtlen){
		$len = ($pos*$d*1.0)/100.0;
		$left = max($pos-$len, 0);
		$right = min($pos+$len,$txtlen-1);
		return array($left, $right);
	}

	public function generateMotifsWays($d){
		$this->distPercent = $d;
		$result = array();
		foreach($this->motifsFound[0] as $motif){
			$result[$motif] = array(); 
			$result[$motif]=$this->recursiveBuilding($d, $motif, 1);			
		}
		return $result;
	}

	private function recursiveBuilding($d, $pos, $lvl){
		$data =array();
		if( $lvl>=$this->numFam) return $data;
		$params = $this->getBorders($pos,$d, $this->lens[$lvl]);
		foreach($this->motifsFound[$lvl] as $motif){
			if ( $motif > $params[0] && $motif < $params[1])
				$data[$motif] = $this->recursiveBuilding($d,$motif,$lvl+1);
		}
		return $data;
	}

}
?>