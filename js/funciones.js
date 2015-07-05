$(document).ready(
    


  //agrega campos de secuencia al agregar familia
  function () {

            /*actualiza titulo estado*/

            $("#tituloEstado").html("<h2>Computation finished</h2><p>Analyse the below table for more details.</p>");

            $("#agregaFam").click(function () {
                if( ($("#panelFam .form-group").length+1) > 12) {
                    alert("It's not allowed to use more than 12 families");
                    return false;
                }
                var id = ($('#panelFam .form-group').length + 1).toString();
                var texto = " <!--Elemento de 1 secuencia-->"+
                  "<div class=\"form-group\">"+
                   "<div class=\"col-xs-12\">"+
                          "<select class=\"form-control\" id=\"selecSeq"+id+"\" name=\"selecSeq"+id+"\"><option selected value=\"-1\">Choose an Ortholog</option>";

                var ortologos = [
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
                                  "Drosophila grimshawi"];
                for (var i = 2; i <= 12; i++) {
                   texto = texto + "<option value=\""+(i-1)+"\">"+ortologos[i-2]+"</option>";
                };
                texto = texto+"</select>"+
                          "<input type=\"text\" class=\"form-control\" id=\"lblSeq"+id+"\" name=\"lblSeq"+id+"\" placeholder=\"Sequence\">"+
                          "<div style=\"position:relative;\">"+
                            "<a class='btn btn-primary' href='javascript:;'>Upload from file"+
                              "<input type=\"file\" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:\"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)\";opacity:0;background-color:transparent;color:transparent;' name=\"file_source"+id+"\" id=\"file_source"+id+"\" size=\"300\""+ 
                              "onchange=\""+
                                "$('#upload-file-info"+id+"').html($(this).val());"+
                                "var file = document.getElementById('file_source"+id+"');"+
                                   "if (!file) {   alert('File not found');   }"+
                                    "else if (!file.files) {alert('Your browser does not support file upload. Please insert your sequence manually'); }"+
                                    "else if (!file.files[0]) {alert('No file has been chosen'); } "+             
                                    "else{"+
                                    "input = file.files[0];"+
                                  "var reader = new FileReader();"+
                                  "reader.readAsText(input,'UTF-8');"+
                                  "reader.onload = (function (evt){ $('#lblSeq"+id+"').val(evt.target.result); });"+
                                  "reader.onerror = (function (evt){$('#lblSeq"+id+"').val('An error has occured while uploading your file'); });"+
                                    "}"+
                              "\">"+
                            "</a>"+
                            "&nbsp;"+
                            "<span class='label label-info' id=\"upload-file-info"+id+"\"></span>"+
                          "</div>"+                    
                    "</div>"+
                  "</div> <!--Final de elemento de secuencia--> ";
                              
                $('#panelFam').append(texto);
            });

            $("#borrarFam").click(function () {
                if ($('#panelFam .form-group').length == 1) {
                    alert("No more families can be removed");
                    return false;
                }

                $("#panelFam .form-group:last").remove();
            });

        });

/*j(function($) {
    $.fn.checkFileType = function(options) {
        var defaults = {
            allowedExtensions: [],
            success: function() {},
            error: function() {}
        };
        options = $.extend(defaults, options);

        return this.each(function() {

            $(this).on('change', function() {
                var value = $(this).val(),
                    file = value.toLowerCase(),
                    extension = file.substring(file.lastIndexOf('.') + 1);

                if ($.inArray(extension, options.allowedExtensions) == -1) {
                    options.error();
                    $(this).focus();
                } else {
                    options.success();

                }

            });

        });
    };

})(jQuery);

//verifica .txt en los archivos de entrada
//posible modificacion: es necesario que sea .txt?
$(function() {
    $('#file_source1').checkFileType({
        allowedExtensions: ['txt'],
        success: function() {
            alert('Exitoso');
        },
        error: function() {
            alert('Error');
        }
    });

});*/
