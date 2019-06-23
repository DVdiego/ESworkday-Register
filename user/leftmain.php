<?php


include '../theme/templates/leftnavstart.inc';

//user moved here from topmain
if (isset($_SESSION['valid_user'])) {
$logged_in_user = $_SESSION['valid_user'];
echo '
      <div class="user-panel">
        <div class="pull-left image">
          <h3><i class="fa fa-user-secret text-orange"></i></h3>
        </div>
        <div class="pull-left info">
          <p>'.$logged_in_user.'</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Sesión iniciada</a>
        </div>
      </div>';
}

else if (isset($_SESSION['time_admin_valid_user'])) {
    $logged_in_user = $_SESSION['time_admin_valid_user'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user-secret text-red"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Sesión iniciada</a>
            </div>
          </div>';

} else if (isset($_SESSION['valid_reports_user'])) {
    $logged_in_user = $_SESSION['valid_reports_user'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user-plus"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Sesión iniciada</a>
            </div>
          </div>';
} else if (isset($_SESSION['valid_report_employee'])) {
    $logged_in_user = $_SESSION['valid_report_employee'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Sesión iniciada</a>
            </div>
          </div>';
}else if (isset($_SESSION['valid_profile'])) {
    $logged_in_user = $_SESSION['valid_profile'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user text-blue"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Sesión iniciada</a>
            </div>
          </div>';
}

// end user moved here from topmain


echo '<ul class="sidebar-menu"><li class="header">MENÚ DE EMPLEADO</li>';

echo "<li class='treeview'>
        <a href=\"useredit.php?username=".$_SESSION['valid_profile']."\">
          <i class='fa fa-user'></i><span>Editar Perfil</span>
        </a>
      </li>\n";

echo "<li class='treeview'>
      <a class=admin_headings href=\"chngpasswd.php?username=".$_SESSION['valid_profile']."\"><i class='fa fa-lock'></i><span>Cambiar Contraseña</span></a></li>\n";

echo "<li class='treeview'><a href=\"user_reports.php?username=".$_SESSION['valid_profile']."\"><i class='fa fa-file-o'></i><span>Consultar Registros</span></a></li>\n";

echo '</ul>';

include '../theme/templates/leftnavend.inc';
include '../theme/templates/beginmaincontent.inc';

?>
