
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
	private $allThePaths = array();

	function __construct($num, $arraySeq,$d){
		$this->numFam = $num;
		$this->seqs = $arraySeq;
		$this->distPercent = $d;
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

	public function generateMotifsPaths(){
		
		foreach($this->motifsFound[0] as $motif){
			$path = new SplQueue();
			$path->push($motif);
			$this->recursiveBuilding($this->distPercent, $motif, 1, $path);
		}
	}

	private function recursiveBuilding($d, $pos, $lvl, $path){
		
		if( $lvl>=$this->numFam) {
			$finalPath = array();

			foreach ($path as $elem) {
				$finalPath[] = $elem;
			}
			$this->allThePaths[] = $finalPath;
			return ;
		}

		$params = $this->getBorders($pos,$d, $this->lens[$lvl]);
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

}
?>