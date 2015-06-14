$(document).ready(function () {

            $("#agregaFam").click(function () {
                if( ($("#panelFam .form-group").length+1) > 12) {
                    alert("Solo se pueden usar hasta 12 familias");
                    return false;
                }
                var id = ($('#panelFam .form-group').length + 1).toString();
                var texto = "  <!--Elemento de 1 secuencia-->\
                    <div class=\"form-group\" >\
                        <div class=\"col-xs-12\">\
                          <label>Drosophila Melanogaster</label>\
                          <input type=\"text\" class=\"form-control\" id=\"lblSeq"+id+"\" name=\"lblSeq"+id+"\" placeholder=\"Secuencia\">\
                          <div style=\"position:relative;\">\
                            <a class='btn btn-primary' href='javascript:;'>Desde archivo\
                              <input type=\"file\" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:\"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)\";opacity:0;background-color:transparent;color:transparent;' name=\"file_source\" size=\"40\"  onchange='$(\"#upload-file-info\").html($(this).val());'>\
                            </a>\
                            &nbsp;\
                            <span class='label label-info' id=\"upload-file-info\"></span>\
                          </div>\
                    </div>\
                  </div> <!--Final de elemento de secuencia-->";
                $('#panelFam').append(texto);
            });

        });
