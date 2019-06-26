<?php
/***************************************************************************
 *   Copyright (C) 2006 by Ken Papizan                                     *
 *   Copyright (C) 2008 by WorkTime Control Team                               *
 *   http://sourceforge.net/projects/WorkTime Control                          *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA.             *
 ***************************************************************************/

session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
include 'leftmain.php';
echo "<title>$title - User Summary</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

if (!isset($_SESSION['valid_user'])) {

echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Administration</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=admin'><u>here</u></a> to login.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}

//Plantilla superior de los usuarios.
include 'usersummary.php';



echo '     <div class="row">
              <div id="float_window" class="col-md-10">
                <div class="box">';
echo '      <div class="box-header">';
echo '        <h3 class="box-title"><i class="fa fa-user-secret"></i> Usuarios con Permisos de Administración</h3>
            </div>';

echo'                  <div class="box-body table-responsive no-padding">';
echo "             <table class='table table-hover'>\n";
echo "              <tr>\n";
echo "                <th>&nbsp;</th>\n";
echo "                <th>Nombre de usuario</th>\n";
echo "                <th>Nombre de acceso</th>\n";
// echo "                <th>Email Address</th>\n";
echo "                <th>Oficina</th>\n";
echo "                <th>Grupo</th>\n";
echo "                <th>Deshabilitada</th>\n";
echo "                <th>Administador</th>\n";
echo "                <th>A. Tiempos</th>\n";
echo "                <th>Reportador</th>\n";
echo "                <th>Editar</th>\n";
echo "                <th>Cambiar contraseña</th>\n";
echo "                <th>Eliminar</th>\n";
echo "              </tr>\n";

$row_count = 0;

$query_permissions ="select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees WHERE `empfullname` != '".$root."' AND empfullname IN(select empfullname ".$db_prefix."from employees WHERE `admin` = 1 OR `reports` = 1 OR `time_admin` = 1 order by empfullname)";


$result_permissions = mysqli_query($GLOBALS["___mysqli_ston"], $query_permissions);

while ($row=mysqli_fetch_array($result_permissions)) {

$empfullname = stripslashes("".$row['empfullname']."");
$displayname = stripslashes("".$row['displayname']."");

$row_count++;
$row_color = ($row_count % 2) ? $color2 : $color1;

echo "              <tr class=table_border bgcolor='$row_color'><td>&nbsp;$row_count</td>\n";
echo "                <td>&nbsp;<a title=\"Edit User: $empfullname\" class=footer_links
                    href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">$empfullname</a></td>\n";
echo "                <td>&nbsp;$displayname</td>\n";
// echo "                <td>&nbsp;".$row["email"]."</td>\n";
echo "                <td>&nbsp;".$row['office']."</td>\n";
echo "                <td>&nbsp;".$row['groups']."</td>\n";

if ("".$row["disabled"]."" == 1) {
  echo "                <td align='center'><i class='glyphicon glyphicon-remove text-red'></i></td>\n";
} else {
  $disabled = "";
  echo "                <td>".$disabled."</td>\n";
}
if ("".$row["admin"]."" == 1) {
  echo "                <td align='center'><i class='glyphicon glyphicon-ok text-green'></i></td>\n";
} else {
  $admin = "";
  echo "                <td>".$admin."</td>\n";
}
if ("".$row["time_admin"]."" == 1) {
  echo "                <td align='center'><i class='glyphicon glyphicon-ok text-green'></i></td>\n";
} else {
  $time_admin = "";
  echo "                <td>".$time_admin."</td>\n";
}
if ("".$row["reports"]."" == 1) {
  echo "                <td align='center'><i class='glyphicon glyphicon-ok text-green'></i></td>\n";
} else {
  $reports = "";
  echo "                <td>".$reports."</td>\n";
}

  if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

  echo "                <td><a
                      title=\"Editar usuario: $empfullname\"
                      href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">Edit</a></td>\n";
  echo "                <td><a
                      title=\"Cambiar contraseña: $empfullname\"
                      href=\"chngpasswd.php?username=$empfullname&officename=".$row["office"]."\">Chg Pwd</a></td>\n";
  echo "                <td><a
                      title=\"Eliminar usuario: $empfullname\"
                      href=\"userdelete.php?username=$empfullname&officename=".$row["office"]."\">Delete</a></td></tr>\n";

  } else {

  echo "                <td align='center'><a title=\"Editar usuario: $empfullname\"
                      href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">
                      <i class='glyphicon glyphicon-pencil'></i></a></td>\n";
  echo "                <td align='center'><a title=\"Cambiar contraseña: $empfullname\"
                      href=\"chngpasswd.php?username=$empfullname&officename=".$row["office"]."\"><i class='fa fa-lock text-yellow'></i></a></td>\n";
  echo "                <td align='center'><a title=\"Eliminar usuario: $empfullname\"
                      href=\"userdelete.php?username=$empfullname&officename=".$row["office"]."\">
                      <i class='glyphicon glyphicon-minus-sign text-red'></i></a></td></tr>\n";
  }
}
echo "          </table></div></div></div></div>\n";

echo '     <div class="row">
              <div id="float_window" class="col-md-10">
                <div class="box">';
echo '      <div class="box-header">';
echo '        <h3 class="box-title"><i class="fa fa-user"></i> Usuarios</h3>
            </div>';

echo'                  <div class="box-body table-responsive no-padding">';
echo "             <table class='table table-hover'>\n";
echo "              <tr>\n";
echo "                <th>&nbsp;</th>\n";
echo "                <th>Nombre de usuario</th>\n";
echo "                <th>Nombre de acceso</th>\n";
// echo "                <th>Email Address</th>\n";
echo "                <th>Oficina</th>\n";
echo "                <th>Grupo</th>\n";
echo "                <th>Deshabilitada</th>\n";
echo "                <th>Editar</th>\n";
echo "                <th>Cambiar contraseña</th>\n";
echo "                <th>Eliminar</th>\n";
echo "              </tr>\n";

$row_count = 0;

$query = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees  WHERE `admin` = 0 AND `reports` = 0 AND `time_admin` = 0 order by empfullname";

$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {

$empfullname = stripslashes("".$row['empfullname']."");
$displayname = stripslashes("".$row['displayname']."");

$row_count++;
$row_color = ($row_count % 2) ? $color2 : $color1;

echo "              <tr class=table_border bgcolor='$row_color'><td>&nbsp;$row_count</td>\n";
echo "                <td>&nbsp;<a title=\"Edit User: $empfullname\" class=footer_links
                    href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">$empfullname</a></td>\n";
echo "                <td>&nbsp;$displayname</td>\n";
// echo "                <td>&nbsp;".$row["email"]."</td>\n";
echo "                <td>&nbsp;".$row['office']."</td>\n";
echo "                <td>&nbsp;".$row['groups']."</td>\n";

if ("".$row["disabled"]."" == 1) {
  echo "                <td align='center'><i class='glyphicon glyphicon-remove text-red'></i></td>\n";
} else {
  $disabled = "";
  echo "                <td>".$disabled."</td>\n";
}


  if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

  echo "                <td><a
                      title=\"Editar usuario: $empfullname\"
                      href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">Edit</a></td>\n";
  echo "                <td><a
                      title=\"Cambiar contraseña: $empfullname\"
                      href=\"chngpasswd.php?username=$empfullname&officename=".$row["office"]."\">Chg Pwd</a></td>\n";
  echo "                <td><a
                      title=\"Eliminar usuario: $empfullname\"
                      href=\"userdelete.php?username=$empfullname&officename=".$row["office"]."\">Delete</a></td></tr>\n";

  } else {

  echo "                <td align='center'><a title=\"Editar usuario: $empfullname\"
                      href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">
                      <i class='glyphicon glyphicon-pencil'></i></a></td>\n";
  echo "                <td align='center'><a title=\"Cambiar contraseña: $empfullname\"
                      href=\"chngpasswd.php?username=$empfullname&officename=".$row["office"]."\"><i class='fa fa-lock text-yellow'></i></a></td>\n";
  echo "                <td align='center'><a title=\"Eliminar usuario: $empfullname\"
                      href=\"userdelete.php?username=$empfullname&officename=".$row["office"]."\">
                      <i class='glyphicon glyphicon-minus-sign text-red'></i></a></td></tr>\n";
  }
}
echo "          </table></div></div></div></div>\n";
include '../footer.php';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
exit;
?>
