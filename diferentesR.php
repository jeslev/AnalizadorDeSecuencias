<?php
session_start();
include("procs/MotifsTree.php");
include("procs/DatosMotif.php");
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
		<title>Muestra de las mejores opciones para <?php echo $_POST['motif']?></title>
    
        <?php
        	$motif = $_POST['motif'];
            $distancia = $_POST['distancia'];
            $radioPB = $_POST['radioPB'];
            $maxlen = $_POST['maxLen'];
            $secuencia = array();
            $nombresSeq = array();
            
            for($i=0; $i<=12; $i++){
                if( isset($_POST['lblSeq'.$i]) ){
                    $secuencia[] = $_POST['lblSeq'.$i];
                }
                if(isset($_POST['selecSeq'.$i])){
                    $nombresSeq[] = $_POST['selecSeq'.$i];
                }
            }
            $motifs = unserialize($_SESSION['mejores_motifTree']);            
            $arrayCola = $motifs->getArrayCola();
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
            <?php 
                $sizeCola = 0;
                while ($sizeCola < count($arrayCola)) {
            ?>
            $(<?php echo "'#grafica".$sizeCola."'"; ?>).highcharts({
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
                    data: [<?php $MeanString = '';	   
	                            for($i = 0; $i<count($arrayCola[$sizeCola][1]); $i++){
	                                if($i!=0) 
	                                    $MeanString=$MeanString.', ';
	                                $MeanString = $MeanString.$arrayCola[$sizeCola][1][$i]; 
	                            }
	                            echo $MeanString; 
	                       ?>]          
                },
                {                    
                    name: 'Perfect Equation',            
                    data: [<?php $normalValuesString = '';
	                            for($i = 0; $i<count($arrayCola[$sizeCola][0]); $i++){
	                                if($i!=0) 
	                                    $normalValuesString=$normalValuesString.', ';
	                                $normalValuesString = $normalValuesString.$arrayCola[$sizeCola][0][$i]; 
	                            }
	                            echo  $normalValuesString;
	                      ?>],            
                }        
                ]
            });
            <?php 
                $sizeCola++;
            }?>
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

          <a href="index.php" class="navbar-brand">INICTEL-UNI / Computational Biology</a>
        </div>
          <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="index.php">Microgravity Lab</a></li>
            </ul>
          
          </div>
      </div>
    </nav>
<!------------------------------------------------------------------------------------------------------------------>
    <div class="container" >
          <?php 
            for($varx=0; $varx<$sizeCola; $varx++){
          ?>
    	  <div class="panel panel-default" >
            <div class="panel-heading"><h4><b>Motif Conservation - Graphic</b></h4></div>
                <div class="panel-body" id="<?php echo 'grafica'.$varx?>">                               
                </div>
                <!------------------------------------------------------------>
           <div class="panel-footer" style="overflow:scroll;">
                <?php echo '<b>R = '.$arrayCola[$varx][3].'</b><br><br>'; ?>
                <canvas id="<?php echo 'myCanvas'.$varx; ?>" width='20000' height="<?php echo count($secuencia)*105; ?>"></canvas>
                <?php
                    echo"
                    <script>
                      var canvas = document.getElementById('myCanvas".$varx."');
                      var context = canvas.getContext('2d');
                    ";
                ?>
                <?php 
                    $colorsCanvas = array("#ff0000","#00ff00","#0000ff","#ff00ff","#ffff00","#00ffff","#55bb55","#5555bb","#bb5555","#bbccdd","#ddccbb","#ccddbb");
                    $posY = array();
                    for($k=0;$k<count($secuencia);$k++) $posY[] = 50+100*$k;
                    $posX = $arrayCola[$varx][2]; 
                    if( strcmp($_POST['optionType'], "zonapromotora") == 0){
                        for($tt=0;$tt<sizeof($posX);$tt++){
                            $posX[$tt] = strlen($secuencia[$tt])-$posX[$tt];
                        }
                    }
                    for($i=0;$i<count($secuencia);$i++){ 
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
                          context.fillText('".$nombresSeq[$i]." (".strlen($secuencia[$i]).") - Found in: ".$arrayCola[$varx][2][$i]."',10,".$postextoY.");
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
                <!-------------------------------------------------------------->
            </div>
          <?php
          }
          ?>
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
