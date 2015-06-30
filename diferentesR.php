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
        	echo $radioPB.'<br>';
            $secuencia = array();
            $nombresSeq = array();
            
            $motifs = unserialize($_SESSION['mejores_motifTree']);            
            echo var_dump($motifs);
            //$cola = $motifs->getPriorityQueue();
            $motifs->getPriorityQueue();
            //echo var_dump($cola);
            //$cola->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
            //while ($cola->valid()) {
            //    echo "<br/>";
            //    print_r($cola->current());
            //    $cola->next();
            //}
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
                while ($sizeCola<=5) {
                    //El while de la linea 40 debería estar acá.. 
                    $sizeCola++;                    
            ?>
            $(<?php echo "'#grafica".$sizeCola."'"; ?>).highcharts({
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
                        pointPadding: 0.55,
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

          <a href="index.php" class="navbar-brand">INICTEL-UNI / Biología Computacional</a>
        </div>
          <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="index.php">Laboratorio Microgravedad</a></li>
            </ul>
          
          </div>
      </div>
    </nav>

<!------------------------------------------------------------------------------------------------------------------>

    <div class="container" >
          <?php 
            for($i=1; $i<=$sizeCola; $i++){
          ?>
    	  <div class="panel panel-default" >
            <div class="panel-heading"><h4><b>Gráfica de conservación para el motif</b></h4></div>
                <div class="panel-body" id="<?php echo 'grafica'.$i?>">

                </div>        
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
