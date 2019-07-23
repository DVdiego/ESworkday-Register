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
}

// end user moved here from topmain


echo '<ul class="sidebar-menu">
        <li class="header">MENÚ DE ADMINISTRACIÓN</li>';


echo'   <li class="treeview">
          <a href="#">
            <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i><span>  Horarios de trabajo</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="timeadmin.php"><i class="fa fa-clock-o"></i> Modificar horario</a></li>
            <li><a href="time_punch_out.php"><i class="fa fa-long-arrow-left"></i> Modificar horarios múltiples</a></li>
            <li><a href="time_punch_employees.php"><i class="fa fa-exchange"></i> Registrar múltiples horarios</a></li>
          </ul>
        </li>';

echo'   <li class="treeview">
          <a href="#">
            <i class="fa fa-building"></i> <span>Oficina</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="officeadmin.php"><i class="fa fa-list"></i> Oficinas disponibles</a></li>
            <li><a href="officecreate.php"><i class="fa fa-plus"></i> Crear oficina</a></li>
          </ul>
        </li>';

echo '  <li class="treeview">
          <a href="#">
            <i class="fa fa-users"></i> <span>Grupos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="groupadmin.php"><i class="fa fa-list"></i> Grupos disponibles</a></li>
	          <li><a href="groupcreate.php"><i class="fa fa-plus"></i> Crear grupo</a></li>
          </ul>
        </li>';

echo'   <li class="treeview">
          <a href="#">
            <i class="fa fa-user"></i> <span>Usuarios</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="useradmin.php"><i class="fa fa-list"></i> Usuarios disponibles</a></li>
            <li><a href="usercreate.php"><i class="fa fa-plus"></i> Crear usuario</a></li>
	          <li><a href="usersearch.php"><i class="fa fa-search"></i> Buscar usuario</a></li>
          </ul>
       </li>';

echo'   <li class="treeview">
          <a href="#">
            <i class="fa fa-sign-out"></i> <span>Estrada - Salida</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="statusadmin.php"><i class="fa fa-list"></i> Estados disponibles</a></li>
	          <li><a href="statuscreate.php"><i class="fa fa-plus"></i> Crear estado</a></li>
          </ul>
        </li>';

echo "  <li class='treeview'>
          <a href='#'>
            <i class='fa fa-dashboard'></i> <span>Otros servicios</span>
            <span class='pull-right-container'>
              <i class='fa fa-angle-left pull-right'></i>
            </span>
          </a>
          <ul class='treeview-menu'>
            <li><a href='sysedit.php'><i class='fa fa-code'></i> Ajustes del sistema</a></li>
	          <li><a href='database_management.php'><i class='fa fa-database'></i> Base de datos</a></li>
          </ul>
        </li>";

echo "</ul>";




include '../theme/templates/leftnavend.inc';
include '../theme/templates/beginmaincontent.inc';

?>
