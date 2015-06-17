<!DOCTYPE html>
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
      <div class="container text-center">
        <h2>Realizando consulta</h2>
        <p> Por favor espere mientras se procesa su consulta.</p>
      </div>
    </div>


    <div class="container">
      <?php var_dump($_POST); 
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
        for($i=2;$i<=12;$i++){
          if( isset($_POST['lblSeq'.$i]) )  $tot=$tot+1;          
        }
      ?>
      <br>
      <?php echo $_POST['distancia']?>
      
      <div class="table-responsive" style="overflow:auto;">
        <table class="table table-stripped table-bordered">
          <thead><tr>
            <th>Código Motif</th>
            <th>Nombre Motif</th>
            <th>Secuencia Motif</th>
            <th>Drosophila Melanogaster</th>
            <?php for($i=2;$i<$tot+2;$i++){ ?>
            <th><?php echo $ortologos[$_POST['selecSeq'.($i)]-1];?></th>
            <?php } ?>
            <th></th>
          </tr></thead>
      <?php include('procs/calcular.php');?>

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
