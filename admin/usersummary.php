<?php
//Plantilla, barra superior del resumen
$user_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees
                           order by empfullname");
@$user_count_rows = mysqli_num_rows($user_count);


$admin_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where admin = '1'");
@$admin_count_rows = mysqli_num_rows($admin_count);

$time_admin_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where time_admin = '1'");
@$time_admin_count_rows = mysqli_num_rows($time_admin_count);

$reports_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where reports = '1'");
@$reports_count_rows = mysqli_num_rows($reports_count);

//Restando el usuario oculto del sistema.
$user_count_rows--;
$time_admin_count_rows--;
$reports_count_rows--;
$admin_count_rows--;

$remaining_users = $max_users_version - $user_count_rows;

echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info">';

echo '      <div class="box-header">';
echo '        <h3 class="box-title"><i class="fa fa-list"></i> Resumen de la plantilla</h3>
            </div>';

echo '
            <div class="box-body">';
echo "        <table class='table table-hover'>\n";
echo "          <tr>
                  <td>
                    <i class='fa fa-users text-green'></i>&nbsp;
                    Usuarios Agregados: $user_count_rows
                    </td>
                    <td>
                    <i class='fa fa-user-secret text-orange'></i>&nbsp;
                    Super Admin: $admin_count_rows
                    </td>
                    <td>
                    <i class='fa fa-user text-red'></i>&nbsp;
                    Admin Registros : $time_admin_count_rows
                    </td>
                    <td>
                    <i class='fa fa-user text-blue'></i>&nbsp;
                    Admin Reportes: $reports_count_rows</td>
                    </td>
                    <td>
                    <i class='fa fa-users '></i>&nbsp;
                    Usuarios Restantes: $remaining_users
                    </td></tr>\n";

echo "        </table>
            </div>

          </div>
        </div>
      </div>\n"; //div class="row"



?>
