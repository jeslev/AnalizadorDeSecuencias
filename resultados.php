<!DOCTYPE html>
<?php 
include("procs/DatosMotif.php");
include("procs/MotifsTree.php");

if( isset($_POST['motif']) && !empty($_POST['motif']) ){
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

          <a href="index.php" class="navbar-brand">INICTEL-UNI / Biología Computacional</a>
        </div>
          <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="index.php">Laboratorio Microgravedad</a></li>
            </ul>
          
          </div>
      </div>
    </nav>

    <!--jumbotron-->
    <div class="jumbotron">
      <div class    ="container text-center">
        <h2>Realizando consulta</h2>
        <p> Por favor espere mientras se procesa su consulta.</p>
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
            <th>Código Motif</th>
            <th>Nombre Motif</th>
            <th>Secuencia Motif</th>
            <th>Drosophila Melanogaster</th>
            <?php for($i=0;$i<$tot;$i++){ ?>
            <th><?php echo $ortologos[$_POST['selecSeq'.($posSeq[$i])]-1]; ?></th>
            <?php } ?>
            <th></th>
          </tr>
          </thead>

          <tbody>
          <?php 
            
            foreach($listaMotif as $detalleMotif){
              $motifTree = new MotifsTree($detalleMotif[2],count($secuencias),$secuencias,intval($_POST['distancia']),intval($_POST['nPares']));
              $motifTree->generateMotifsPaths();
              $motifTree->getMotifs();
              $stringAcep = $motifTree->getStringMeans();
              //echo $stringAcep.'sdfsadf<br>';
              if(strlen($stringAcep)>0){              
          ?>
          <tr>
            <th><?php echo $detalleMotif[0] ?></th>
            <th><?php echo $detalleMotif[1] ?></th>
            <th><?php echo $detalleMotif[2] ?></th>
            <th><?php echo $_POST['lblSeq1']; ?></th>
            <?php for($i=0;$i<$tot;$i++){ ?>
            <th><?php echo $_POST['lblSeq'.$posSeq[$i]]; ?></th>
            <?php } ?>
            <th>
                <form action="grafica-secuencia.php" method="post"><!--<input type="submit" value="Graficar">-->
                <input type="hidden" class="form-control" value="<?php echo $_POST['distancia'];?>" name='distancia' id='distancia'>
                <input type="hidden" class="form-control" value="<?php echo $_POST['nPares'];?>" name='radioPB' id='radioPB'>
                <input type="hidden" class="form-control" value="<?php echo $detalleMotif[2];?>" name='motif' id='motif'>
                <input type="hidden" class="form-control" value="<?php echo $_POST['lblSeq1'];?>" name='lblSeq1' id='lblSeq1'>
                <?php for($j=0;$j<$tot;$j++){ ?>
                <input type="hidden" class="form-control" value="<?php echo $_POST['lblSeq'.$posSeq[$j]];?>" name="<?php echo 'lblSeq'.$posSeq[$j]; ?>" id="<?php echo 'lblSeq'.$posSeq[$j]; ?>" >
                <input type="hidden" class="form-control" value="<?php echo $ortologos[$_POST['selecSeq'.($posSeq[$j])]-1];?>" name="<?php echo 'selecSeq'.$posSeq[$j]; ?>" id="<?php echo 'selecSeq'.$posSeq[$j];?>">
               <?php
                    }               ?>
                <center><button type="submit" class="btn btn-success btn-large">Graficar</button><center>
                </form>
            </th>
          </tr>
          <?php 
            } 
          }
          ?>
          </tbody>

        </table>
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