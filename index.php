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

    <!--post-->
    <?php
      //validar de nuevo
      if( isset($_POST['distancia']) ) {
        /*echo $_POST['distancia'];
        echo $_POST['nPares'];
        echo $_POST['optionType'];
        echo $_POST["inPatron"];
        echo $_POST['lblSeq1'];
        for($i=2;$i<=12;$i++){
          $nm = 'lblSeq'.$i;
          $nm2= 'selecSeq'.$i;
          if(isset($_POST[$nm])) echo $_POST[$nm]."<br>";
          if(isset($_POST[$nm2])) echo $_POST[$nm2]."<br>";
        }*/
      }
    ?>

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

          <a href="#" class="navbar-brand">INICTEL-UNI / Biología Computacional</a>
        </div>
          <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#">Laboratorio Microgravedad</a></li>
            </ul>
          
          </div>
      </div>
    </nav>

    <!--jumbotron-->
    <div class="jumbotron">
      <div class="container text-center">
        <h1>Analizador de Motifs</h1>
        <p> Programa de reconocimiento de motifs en familia de drosophila.</p>
      </div>
    </div>





    <form role="form" action="index.php" method='post' onsubmit="return fn_enviar();" id="frm_enviar" name="frm_enviar">

    <div class="container">
      <!-- Entrada -->
      <div class="panel panel-default">
        <div class="panel-body">
            <h3>Entradas</h3>
   
            <div class="col-xs-8">
              <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Secuencias de Drosophila</h3>
                </div>
                <div class="panel-body" id="panelFam">

                  <!--Elemento de 1 secuencia-->
                  <div class="form-group">
                    <div class="col-xs-12">

                          <label>Drosophila Melanogaster</label>
                          <input type="text" class="form-control" id="lblSeq1" name="lblSeq1" placeholder="Secuencia">
                          <div style="position:relative;">
                            <a class='btn btn-primary' href='javascript:;'>Desde archivo
                              <input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source1" id="file_source1" size="300" 
                              onchange="
                                $('#upload-file-info1').html($(this).val());
                                var file = document.getElementById('file_source1');
                                   if (!file) {
                                      alert('No se encontró el archivo de entrada');
                                    }
                                    else if (!file.files) {
                                      alert('El navegador no soporta la lectura de archivos. Por favor ingrese manualmente');
                                    }
                                    else if (!file.files[0]) {
                                      alert('No ha escogido ningun archivo aun');               
                                    }else{
                                    input = file.files[0];
                                  var reader = new FileReader();
                                  reader.readAsText(input,'UTF-8');
                                  reader.onload = function (evt) {
                                    $('#lblSeq1').val(evt.target.result);
                                  }
                                  reader.onerror = function (evt) {
                                    $('#lblSeq1').val('Error al leer el archivo');
                                  }
                                }
                              ">
                            </a>
                            &nbsp;
                            <span class='label label-info' id="upload-file-info1"></span>
                          </div>                    
                    </div>
                  </div> <!--Final de elemento de secuencia-->

                </div>
                  <div class="form-group">
                      <label></label>
                      <button type="button" class="btn btn-success" id="agregaFam">Agregar familia</button>      
                  </div> 
                </div>

            </div>
           <div class="col-xs-4">
              <div class="panel panel-danger">
                <div class="panel-heading">
                  <h3 class="panel-title">Parámetros</h3>
                </div>
                <div class="panel-body">
                  
                    <div class="form-group">
                      <label>Distancia de búsqueda</label>
                      <input type="text" class="form-control" id="distancia"  name="distancia" placeholder="(%)">
                    </div>
                    <div class="form-group">
                      <label >Radio nro de pares</label>
                      <input type="text" class="form-control" id="nPares" name="nPares" placeholder="(número)">
                  </div>
                  <div class="form-group">
                    <label>Tipo de análisis:</label>
                    <div class="radio">
                      <label>
                        <input type="radio" name="optionType" id="zonapromotora" value="zonapromotora" checked>Zona Promotora
                      </label>
                    </div>
                    <div class="radio">
                      <label>
                        <input type="radio" name="optionType" id="primerintron" value="primerintron">Primer Intrón
                      </label>
                    </div>
                  </div> 
                  <div class="form-group">
                      <label></label>
                      <button type="submit" class="btn btn-success btn-large" >Calcular</button>       
                  </div>
                </div>
              </div>
            </div>

        </div>
      </div>
    </div>



    <div class="container">
      <!--Buscar en tabla de motifs-->
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="col-xs-4">
            <div class="form-group">
              <label><h3 class="text-center">Motif</h3></label>
              <input type="text" class="form-control" id="inPatron" name="inPatron" value="todos">
            </div>
          </div>
          <div class="form-group">
            <div id="div_resultado_listar">
                <!--Tabla-->

            </div>
          </div>
        </div>
      </div>
    </div>

    </form>
  
    <?php if( isset($_POST['distancia'])) { //modal para mostrar datos de confirmacion, agregar boton de eliminar campo?>
    <div class="container">
      <div class="modal fade" id="modalResultados" name="modalResultados">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Resultados</h4>
            </div>
            <div class="modal-body">
              <p>One fine body&hellip;</p>
              <?php //include("procs/calcular.php");?>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
    </div>
    <?php } ?>

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
