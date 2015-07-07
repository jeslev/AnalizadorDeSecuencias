<?php
session_start();
include("procs/MotifsTree.php");
include("procs/DatosMotif.php");
//$mm = unserialize($_SESSION['encoded_motifTree']);
//var_dump($mm->getMotifs());
if( isset($_POST['motif']) && !empty($_POST['motif']) ){
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link rel="icon" href="icono.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <!--Library form's-->
        <script src="js/funciones.js"></script>
		<title>Sequence's Graphic for <?php echo $_POST['motif']?> Motif</title>
    
        <?php
        
            $ortologos = array(
                                  "inicio 0",
                                  "Drosophila melanogaster",
                                  "Drosophila simulans",
                                  "Drosophila sechellia",
                                  "Drosophila erecta",
                                  "Drosophila yakuba",
                                  "Drosophila ananassae",
                                  "Drosophila pseudoobscura pseudoobscura",
                                  "Drosophila persimilis",
                                  "Drosophila willistoni",
                                  "Drosophila virilis",
                                  "Drosophila mojavensis",
                                  "Drosophila grimshawi");
        
        	$motif = $_POST['motif'];
            $distancia = $_POST['distancia'];
            $radioPB = $_POST['radioPB'];
            $secuencia = array();
            $nombresSeq = array();
            for($i=1; $i<=12; $i++){
                if( isset($_POST['lblSeq'.$i]) ){
                    $secuencia[] = $_POST['lblSeq'.$i];
                }
                if(isset($_POST['selecSeq'.$i])){
                    $nombresSeq[] = $_POST['selecSeq'.$i];
                    //echo $i.' Nombre '.$_POST['selecSeq'.$i].'<br>';
                }
            }          
            $motifs = unserialize($_SESSION['encoded_motifTree'][$_POST['numFila']]);
            //$motifs->generateMotifsPaths();
            //Para ver que imprime.. el objeto.. si se observa no se pasa la cola de prioridades.
            $arrayCola = $motifs->getArrayCola();
            $_SESSION['mejores_motifTree'] = $_SESSION['encoded_motifTree'][$_POST['numFila']];
        ?>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style type="text/css">
        body{
            padding-top: 70px;
        }
            ${demo.css}
		</style>

		<script type="text/javascript">

        $(function () {
            <?php for($i=0;$i<6;$i++){?>
            $('#grafica').highcharts({
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Nucleotide Conservation around the Motif'
                },
                subtitle: {
                    text: 'By: <a href="http://www.uni.edu.pe">' +
                        'Computational Biology 2015-1</a>'
                },
                xAxis: {
                    allowDecimals: false,
                    tickInterval: 0.1,
                    labels: {         
                        formatter: function () {
                            return this.value; // clean, unformatted number for year
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Frequencies'
                    },
                    labels: {
                        formatter: function () {
                            return this.value;
                        }
                    }
                },
                tooltip: {
                    pointFormat: '{series.name} <br/>'
                },
                plotOptions: {
                    line: {
                        pointStart: -<?php echo $radioPB+5 ?>,
                        color: '#0AE85F',
                        marker: {
                            enabled: false,
                            symbol: 'circle',
                            radius: 0,
                            states: {
                                hover: {
                                    enabled: true
                                }
                            }
                        }
                    },
                    column: {
                        pointPadding: 0.55,
                        borderWidth: 1

                    }
                },
                series: [        
                {
                    type: 'column',    
                    name: 'Nucleotide Frequency',
                    color: '#44A0F0',
                    pointStart: -<?php echo $radioPB ?>,
                    data: [<?php echo $motifs->getStringMeans() ?>]          
                },
                {                    
                    name: 'Perfect Equation',            
                    data: [<?php echo $motifs->getNormalValues() ?>],            
                }        
                ]
            });
            <?php }?>
        });
		</script>		       

	</head>
	<body>
        <script src="Highcharts-4.1.6/js/highcharts.js"></script>
        <script src="Highcharts-4.1.6/js/modules/exporting.js"></script>
    <!-- Navbar -->

    <nav class="navbar navbar-inverse navbar-fixed-top" id="barraNav">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <a href="http://didt.inictel-uni.edu.pe/didt/microgravedad/" class="navbar-brand">INICTEL-UNI / Computational Biology </a>
        </div>
          <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="http://didt.inictel-uni.edu.pe/didt/microgravedad/">Microgravity Lab</a></li>
              <li><a href="about.html">About</a></li>
            </ul>
          
          </div>
      </div>
    </nav>

    <div class="container" style="overflow:scroll;">
        <div class="panel panel-default">
            <div class="panel-heading"><h4><b>Results</b></h4></div>
                <div class="panel-body">

                <?php echo '<b>R</b> = '.$motifs->getMejorR().'<br><br>';
                    $posX = $motifs->getColaPosiciones();
                    $bd = new DatosMotif($motif);
                    $res = $bd->obtenerResultados();
                    $maxlen = 0;
                    $nombreMotif = $res[0][0]." - ".$res[0][1];
                    for($i=0;$i<count($secuencia);$i++) {
                        if(strlen($secuencia[$i])>$maxlen) $maxlen = strlen($secuencia[$i]);
                    }
                ?>
                <?php echo '<b>Motif</b> = '.$nombreMotif.'<br><br>'?>
                <?php echo '<b>Consensus Sequence ('.strlen($motif).')</b> = '.$motif.'<br><br>'?>
                <?php echo '<b>Sequences</b>:<br>'; 
                    echo '<table border=0>';
                    for($i=0;$i<count($secuencia);$i++) {
                        if(strcmp($_POST['optionType'], "zonapromotora") == 0) $start = strlen($secuencia[$i]) - $posX[$i];
                        else $start=$posX[$i];
                        echo '<tr><td colspan="100"><b>'.$nombresSeq[$i].' ('.strlen($secuencia[$i]).')</b> =</td></tr>';
                        echo '<tr>';
                        for($j=0;$j< ($maxlen-strlen($secuencia[$i]));$j++) echo '<td>-</td>';
                        for($j=0;$j<$start;$j++) echo '<td>'.$secuencia[$i][$j].'</td>';
                        for($j=$start;$j<($start+strlen($motif));$j++) echo '<td style="background-color: yellow"><b>'.$secuencia[$i][$j].'</b></td>';
                        for($j=$start+strlen($motif);$j<strlen($secuencia[$i]);$j++) echo '<td>'.$secuencia[$i][$j].'</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                ?>

                </div>        
        </div> 
    </div>
    <div class="container" >
        <div class="panel panel-default" >
            <div class="panel-heading"><h4><b>Motif Conservation - Graphic</b></h4></div>
                <div class="panel-body" id="grafica">

                </div>        
        </div>
    </div>    
    <div class="container" style="overflow:scroll;">
        <div class="panel panel-default">
            <div class="panel-heading"><h4><b>Displacement of the Motif</b></h4></div>
                <div class="panel-body">
                
                <canvas id='myCanvas' width='20000' height="<?php echo count($secuencia)*105; ?>"></canvas>
                <?php
                    echo"
                    <script>
                      var canvas = document.getElementById('myCanvas');
                      var context = canvas.getContext('2d');
                    ";
                ?>
                
                <?php 
                    $colorsCanvas = array("#ff0000","#00ff00","#0000ff","#ff00ff","#ffff00","#00ffff","#55bb55","#5555bb","#bb5555","#bbccdd","#ddccbb","#ccddbb");
                    $posY = array();
                    for($k=0;$k<count($secuencia);$k++) $posY[] = 50+100*$k;
                    $posX = $motifs->getColaPosiciones(); 
                    if( strcmp($_POST['optionType'], "zonapromotora") == 0){
                        for($tt=0;$tt<sizeof($posX);$tt++){
                            $posX[$tt] = strlen($secuencia[$tt])-$posX[$tt];
                        }
                    }
                    //echo var_dump($posX);
                    for($i=0;$i<count($secuencia);$i++){ 
                    
                        //echo '<b>'.$nombresSeq[$i].'</b><br><br>';      
                        $desplazamiento =   ( $maxlen - strlen($secuencia[$i]));
                        if( strcmp($_POST['optionType'], "zonapromotora") != 0) $desplazamiento=0;
                        $iniX = $desplazamiento;
                        $finX = $iniX+strlen($secuencia[$i]);
                        $inifinY = $posY[$i];
                        $postextoY = $posY[$i]-10;
                        echo
                         " 
                          context.beginPath();
                          context.strokeStyle = '#444040';
                          context.fillText('".$nombresSeq[$i]."',10,".$postextoY.");
                          context.moveTo(".$iniX.", ".$inifinY.");
                          context.lineTo(".$finX.", ".$inifinY.");
                          context.lineWidth = 4;
                          context.stroke();
                          ";
                          
                            if($i>0){
                                $predesplazamiento =   ( $maxlen - strlen($secuencia[$i-1]));
                                if( strcmp($_POST['optionType'], "zonapromotora") != 0) $predesplazamiento=0;
                            echo   "
                                    context.beginPath();
                                    context.strokeStyle='".$colorsCanvas[$i-1]."';
                                    context.moveTo(".($posX[$i-1]+$predesplazamiento).", ".$posY[$i-1].");
                                    context.lineTo(".($posX[$i]+$desplazamiento).", ".$posY[$i].");
                                    context.stroke();
                                    ";      
                         }
                          
                    }
                    
                    echo "context.stroke(); </script>";   
                ?>
                </div>        
        </div>
            
    </div>
    <br><br>
    <form action="diferentesR.php" method="post"><!--<input type="submit" value="Graficar">-->
                <input type="hidden" class="form-control" value="<?php echo $_POST['distancia'];?>" name='distancia' id='distancia'>
                <input type="hidden" class="form-control" value="<?php echo $_POST['radioPB'];?>" name='radioPB' id='radioPB'>
                <input type="hidden" class="form-control" value="<?php echo $_POST['motif'];?>" name='motif' id='motif'>
                <input type="hidden" class="form-control" value="<?php echo $_POST['optionType'];?>" name='optionType' id='optionType'>
                <?php for($j=0;$j<count($secuencia);$j++){ ?>
                <input type="hidden" class="form-control" value="<?php echo $secuencia[$j];?>" name="<?php echo 'lblSeq'.$j; ?>" id="<?php echo 'lblSeq'.$j ?>" >
                <input type="hidden" class="form-control" value="<?php echo $nombresSeq[$j];?>" name="<?php echo 'selecSeq'.$j; ?>" id="<?php echo 'selecSeq'.$j; ?>">
                <input type="hidden" class="form-control" value="<?php echo $_POST['numFila'];?>" id="numFila" name="numFila">
                <input type="hidden" class="form-control" value="<?php echo $maxlen;?>" id="maxLen" name="maxLen">
               <?php
                    }               ?>
                <center><button type="submit" class="btn btn-success btn-large">More values of R</button><center>
    </form>
    <br><br>  
    
    
	</body>
</html>
<?php
}
else{
    echo "Ooops! Parece que te metiste a otro lado<br>";
    echo "Te llevaremos ahi, no te preocupes :)<br>";
    header("refresh:5;url=index.php");
}  
?>
