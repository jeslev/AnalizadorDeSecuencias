<?php
include("procs/MotifsTree.php");

if( isset($_POST['motif']) && !empty($_POST['motif']) ){
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link rel="icon" href="icono.png">
		<title>Gráfica de secuencia para <?php echo $_POST['motif']?></title>
    
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
            //echo 'motif: '.$motif.'<br>';
            //echo 'distancia: '.$distancia.'<br>';
            //echo 'radioPB: '.$radioPB.'<br>';
            //echo 'longitud: '.count($secuencia).'<br>';
            //echo 'secuencia: '; foreach($secuencia as $s) echo $s.'<br>'; echo '<br>';
	//Fase 1
            $motifs = new MotifsTree($motif,count($secuencia),$secuencia,$distancia,$radioPB, $_POST['optionType']);
            $motifs->generateMotifsPaths();
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
    $('#grafica').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Conservación de nuclétidos alrededor del motif'
        },
        subtitle: {
            text: 'By: <a href="http://www.uni.edu.pe">' +
                'Biología Computacional 2015-1</a>'
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
                text: 'Frecuencias'
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
                pointPadding: -0.34,
                borderWidth: 1

            }
        },
        series: [        
        {
            type: 'column',    
            name: 'Frecuencia de nucléotido',
            color: '#44A0F0',
            pointStart: -<?php echo $radioPB ?>,
            data: [<?php echo $motifs->getStringMeans() ?>]          
        },
        {                    
            name: 'Ecuación Ideal',            
            data: [<?php echo $motifs->getNormalValues() ?>]
        }        
           ]
    });
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

          <a href="index.php" class="navbar-brand">INICTEL-UNI / Biología Computacional</a>
        </div>
          <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="index.php">Laboratorio Microgravedad</a></li>
            </ul>
          
          </div>
      </div>
    </nav>

    <div class="container" style="overflow:scroll;">
        <div class="panel panel-default">
            <div class="panel-heading"><h4><b>Resultados obtenidos</b></h4></div>
                <div class="panel-body">

                <?php echo '<b>R</b> = '.$motifs->getMejorR().'<br><br>'?>
                <?php echo '<b>motifs</b> = '.$motif.'<br><br>'?>
                <?php echo '<b>Secuencias</b>:<br>'; for($i=0;$i<count($secuencia);$i++) echo '<b>'.$nombresSeq[$i].'</b> = '.$secuencia[$i].'<br><br>'?>

                </div>        
        </div> 
        <div class="panel panel-default" >
            <div class="panel-heading"><h4><b>Gráfica de conservación para el motif</b></h4></div>
                <div class="panel-body" id="grafica">

                </div>        
        </div>
        
        
        <div class="panel panel-default">
            <div class="panel-heading"><h4><b>Secuencias de Motif</b></h4></div>
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
                    $posY = array();
                    for($k=0;$k<count($secuencia);$k++) $posY[] = 50+100*$k;
                    $posX = $motifs->getColaPosiciones(); 
                    for($i=0;$i<count($secuencia);$i++){ 
                    
                        //echo '<b>'.$nombresSeq[$i].'</b><br><br>';                
                        $iniX = 150;
                        $finX = $iniX+strlen($secuencia[$i]);
                        $inifinY = $posY[$i];
                        $postextoY = $posY[$i]-10;
                        echo
                        //" context.beginPath();
                         " 
                          context.strokeStyle = '#444040';
                          context.fillText('".$nombresSeq[$i]."',10,".$postextoY.");
                          context.moveTo(".$iniX.", ".$inifinY.");
                          context.lineTo(".$finX.", ".$inifinY.");
                          context.lineWidth = 4;
                          ";
                          
                            if($i>0){
                            echo   "
                                    context.moveTo(".$iniX.", ".$inifinY.");
                                    context.lineTo(".$finX.", ".$inifinY.");
                                    ";      
                         }
                          
                    }
                    echo "context.stroke(); </script>";   
                ?>
                </div>        
        </div>
            
    </div>
 
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
