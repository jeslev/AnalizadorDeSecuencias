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
        	//DnaJ-1_melanogaster
        	$motif = $_POST['motif'];
            $distancia = $_POST['distancia'];
            $radioPB = $_POST['radioPB'];
            $secuencia = array();
            for($i=1; $i<=12; $i++){
                if( isset($_POST['lblSeq'.$i]) ){
                    $secuencia[] = $_POST['lblSeq'.$i];
                }
            }          

	//Fase 1
            $motifs = new MotifsTree($motif,count($secuencia),$secuencia,$distancia,$radioPB);
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
                <?php foreach($secuencia as $seq) echo '<b>secuencia</b> = '.$seq.'<br><br>'?>

                </div>        
        </div> 
        <div class="panel panel-default" >
            <div class="panel-heading"><h4><b>Gráfica de conservación para el motif</b></h4></div>
                <div class="panel-body" id="grafica">



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