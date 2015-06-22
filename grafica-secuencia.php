<?php
include("procs/MotifsTree.php");
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Gráfica de secuencia del Motif</title>
    
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
${demo.css}
		</style>
		<script type="text/javascript">
$(function () {
    $('#container').highcharts({
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
            pointFormat: '{series.name} produced <b>{point.y:,.0f}</b><br/>warheads in {point.x}'
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

        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        <?php echo '<br>R = '.$motifs->getMejorR().'<br>'?>
        <?php echo '<br>motifs = '.$motif.'<br>'?>
        <?php foreach($secuencia as $seq) echo '<br>secuencia = '.$seq.'<br>'?>
	</body>
</html>
