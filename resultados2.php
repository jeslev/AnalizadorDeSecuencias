<!DOCTYPE html>
<?php 
session_start();
include("procs/DatosMotif.php");
include("procs/MotifsTree.php");
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
if( isset($_POST['motif']) && !empty($_POST['motif']) ){
  $_SESSION['encoded_motifTree'] = array();
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>INICTEL - UNI</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="css/jquery.dataTables.css" rel="stylesheet">

    <link rel="icon" href="icono.png">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <style>
    body{
      padding-top: -40px;
    }
  </style>

  <body>

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

    <!--jumbotron-->
    <div class="jumbotron">
      <div class="container text-center">
      <table align="center">
        <tr>
          <td colspan="3"><img src="http://bolsatrabajo.uni.edu.pe/images/uni2.png" WIDTH=120 HEIGHT=110 style="  margin-right: 40%" /></td>
          <td colspan="7"> <div id="tituloEstado" name="tituloEstado">
          <h2>Query processing</h2>
        <p>Please wait while your query is processed.</p></div>
        </td>
          <td colspan="3"><img src="http://didt.inictel-uni.edu.pe/didt/wp-content/uploads/2014/02/Logo-INICTEL-UNI.png" WIDTH=120 HEIGHT=110 style="  margin-left: 40%"/></td>
        </tr>
      </table>
      </div>

    </div>


    <div class="container">
      <?php 
        $ortologos = array(
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
        $tot = 0;
        $posSeq = array();
        //$posSeqNombre = array();
        for($i=2;$i<=12;$i++)
          if( isset($_POST['lblSeq'.$i]) ){
            $tot=$tot+1;
            $posSeq[] = $i;
          }          
      ?>
      <br>
      
      <?php 
        $obtenerMotifs = new DatosMotif($_POST['motif']);
        $listaMotif = $obtenerMotifs->obtenerResultados();
        //echo var_dump($listaMotif);
        $secuencias = array();
        $secuencias[] = $_POST['lblSeq1'];
        for($i=0;$i<$tot;$i++){
            $secuencias[] = $_POST['lblSeq'.$posSeq[$i]];
        }
      ?>
      <div class="table-responsive" style="overflow:auto;">
        <table class="table table-stripped table-bordered">
          <thead><tr>
            <th>Motif Code</th>
            <th>Motif Name</th>
            <th>Motif Sequence</th>
            <th>Drosophila Melanogaster</th>
            <?php for($i=0;$i<$tot;$i++){ ?>
            <th><?php echo $ortologos[$_POST['selecSeq'.($posSeq[$i])]-1]; ?></th>
            <?php } ?>
            <th>Options</th>
          </tr>
          </thead>

          <tbody>
          <?php 
            $cantFilas = 0;
            foreach($listaMotif as $detalleMotif){
              $motifTree = new MotifsTree($detalleMotif[2],count($secuencias),$secuencias,intval($_POST['distancia']),intval($_POST['nPares']), $_POST['optionType']);
              $motifTree->generateMotifsPathsSlope();
              $stringAcep = $motifTree->getSlopeData();
              if($stringAcep[1]!=1000000){ 
                    $_SESSION['encoded_motifTree'][] = serialize($motifTree);
                    $cantFilas++;             
          ?>
          <tr>
            <th><?php echo $detalleMotif[0] ?></th>
            <th><?php echo $detalleMotif[1] ?></th>
            <th><?php echo $detalleMotif[2] ?></th>
           <!-- <th><?php echo $_POST['lblSeq1']; ?></th> -->
            <?php 
                $posiciones = $motifTree->getColaPosiciones();
                foreach($posiciones as $posicionesMotif){
            ?>
            <th><?php //echo $_POST['lblSeq'.$posSeq[$i]]; 
                    echo $posicionesMotif;
                ?>
            </th>
            <?php } ?>
            <th>
                <form action="grafica2.php" method="post"><!--<input type="submit" value="Graficar">-->
                <input type="hidden" class="form-control" value="<?php echo $_POST['distancia'];?>" name='distancia' id='distancia'>
                <input type="hidden" class="form-control" value="<?php echo $_POST['nPares'];?>" name='radioPB' id='radioPB'>
                <input type="hidden" class="form-control" value="<?php echo $detalleMotif[2];?>" name='motif' id='motif'>
                <input type="hidden" class="form-control" value="<?php echo $_POST['optionType'];?>" name='optionType' id='optionType'>
                <input type="hidden" class="form-control" value="<?php echo $_POST['lblSeq1'];?>" name='lblSeq1' id='lblSeq1'>
                <?php for($j=0;$j<$tot;$j++){ ?>
                <input type="hidden" class="form-control" value="<?php echo $_POST['lblSeq'.$posSeq[$j]];?>" name="<?php echo 'lblSeq'.$posSeq[$j]; ?>" id="<?php echo 'lblSeq'.$posSeq[$j]; ?>" >
                <input type="hidden" class="form-control" value="Drosophila melanogaster" name="<?php echo 'selecSeq1'; ?>" id="<?php echo 'selecSeq1'; ?>">
                <input type="hidden" class="form-control" value="<?php echo $ortologos[$_POST['selecSeq'.($posSeq[$j])]-1];?>" name="<?php echo 'selecSeq'.$posSeq[$j]; ?>" id="<?php echo 'selecSeq'.$posSeq[$j];?>">
                <input type="hidden" class="form-control" value="<?php echo ($cantFilas-1);?>" id="numFila" name="numFila">
               <?php
                    }               ?>
                <center><button type="submit" class="btn btn-success btn-large">Graph</button><center>
                </form>
            </th>
          </tr>
          <?php 
            } 
          }
          ?>
          </tbody>

        </table>
        
        <?php
            if($cantFilas == 0){
        ?>   
        <h2><center>No match is found</center><h2/> 
        <?php    
            }
        ?>
        
      </div>


      <p>
      </p>
    </div>

  <div class="container">
    <footer class="footer">
      <p>&copy;INICTEL - UNI 2015</p>
    </footer>
  </div>   
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <!--Library form's-->
    <script src="js/funciones.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>      
    <script src="js/dataTables.bootstrap.js"></script>      
    <script src="js/control.js"></script>      

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
