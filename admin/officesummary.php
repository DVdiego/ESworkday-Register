<?php

  /*-----------------------------------------
            RESUMEN DE LAS OFICINAS
  -----------------------------------------*/

  //Obtengo las oficinas por id
  $offices_count = mysqli_query($GLOBALS["___mysqli_ston"], "select * from ". $db_prefix ."offices");
  @$offices_count_rows = mysqli_num_rows($offices_count);

  /*
    Obtengo las oficinas que tienen grupos con una consulta con JOIN.
    COmo pueden existir varios grupos que pertenezcan a la misma oficina,
    añado la claúsula DISTINCT a la consulta que solo me devuelve valores
    únicos, no obtiene los repetidos
  */
  $offices_with_groups = "select distinct offices.officeid FROM groups JOIN offices ON offices.officeid = groups.officeid";
  $result_offices_groups = mysqli_query($GLOBALS["___mysqli_ston"], $offices_with_groups);
  $num_offices_groups = mysqli_num_rows($result_offices_groups);

  //Empiezo a diseñar la tabla que muestra el resultado.
  echo '<div class="row">
          <div id="float_window" class="col-md-10">
            <div class="box box-info">';
  echo '      <div class="box-header">';
  echo '        <h3 class="box-title"><i class="fa fa-list"></i> Resumen de oficinas</h3>
              </div>';

  echo '      <div class="box-body">';
  echo '        <table class="table table-hover">
                  <tr>
                    <td>
                      <i class="fa fa-suitcase text-green"></i>&nbsp;
                      Oficinas totales: '. $offices_count_rows .' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <i class="fa fa-suitcase text-red"></i>&nbsp;
                      Oficinas sin grupos: '. $num_offices_groups .'
                    </td>
                  </tr>
                </table>';
  echo '      </div>';
  echo '    </div>
          </div>
        </div>'; //Cierres del primer echo






?>
