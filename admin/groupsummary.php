<?php

  /*-----------------------------------------
            RESUMEN DE LOS GRUPOS
  -----------------------------------------*/

  //Obtengo los grupos por id
  $groups_count = mysqli_query($GLOBALS["___mysqli_ston"], "select * from ". $db_prefix ."groups");
  @$groups_count_rows = mysqli_num_rows($groups_count);

  /*
    Obtengo los grupos que tiene usuarios con una consulta con JOIN.
    Como pueden existir varios usuarios que pertenezcan a un grupo,
    añado la cláusula DISTINCT a la consulta que solo me devuelve valores
    únicos, no obtiene los repetidos.
  */
  $groups_with_users = "select distinct groups.groupname FROM groups JOIN employees ON groups.groupname = employees.groups";
  $result_groups_users = mysqli_query($GLOBALS["___mysqli_ston"], $groups_with_users);
  $num_groups_users = mysqli_num_rows($result_groups_users);

  //Empiezo a diseñar la tabla que muestra el resultado.
  echo '<div class="row">
          <div id="float_window" class="col-md-10">
            <div class="box box-info">';
  echo '      <div class="box-header">';
  echo '        <h3 class="box-title"><i class="fa fa-list"></i> Resumen de grupos</h3>
              </div>';

  echo '      <div class="box-body">';
  echo '        <table class="table table-hover">
                  <tr>
                    <td>
                      <i class="fa fa-users text-green"></i>&nbsp;
                      Grupos totales: '. $groups_count_rows .' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <i class="fa fa-users text-red"></i>&nbsp;
                      Grupos sin usuarios: '. $num_groups_users .'
                    </td>
                  </tr>
                </table>';
  echo '      </div>';
  echo '    </div>
          </div>
        </div>'; //Cierres del primer echo

 ?>
