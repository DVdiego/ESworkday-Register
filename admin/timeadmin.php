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

/**
 * This module creates the administration interface.
 */

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
include 'leftmain.php';

echo "<title>$title - Modificar tiempos de empleados</title>\n";

// Ensure a valid login
if ((!isset($_SESSION['valid_user'])) && (!isset($_SESSION['time_admin_valid_user']))) {
    echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
    echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Administration</td></tr>\n";
    echo "  <tr class=right_main_text>\n";
    echo "    <td align=center valign=top scope=row>\n";
    echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
    echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
    echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=admin'><u>here</u></a> to login.</td></tr>\n";
    echo "      </table><br /></td></tr></table>\n";
    exit;
}

//Plantilla superior de los usuarios.
include 'usersummary.php';

echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info">';
echo '      <div class="box-header">';
echo '        <h3 class="box-title"><i class="fa fa-archive"></i> Modificar tiempos de empleados</h3>
            </div>';
echo "            <table class='table table-hover'>\n";
echo "              <tr>\n";
echo "                <th>&nbsp;</th>\n";
echo "                <th>Nombre de usuario</th>\n";
echo "                <th>Nombre de acceso</th>\n";
echo "                <th>Oficina</th>\n";
echo "                <th>Grupo</th>\n";
echo "                <th>Deshabilitado</th>\n";
echo "                <th>Añadir</th>\n";
echo "                <th>Editar</th>\n";
echo "                <th>Eliminar</td>\n";
echo "              </tr>\n";

$row_count = 0;

$query = "select empfullname, displayname, email, groups, office, admin, reports, disabled from ".$db_prefix."employees WHERE `empfullname` <> '".$root."' order by empfullname";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {
    $empfullname = stripslashes("".$row['empfullname']."");
    $displayname = stripslashes("".$row['displayname']."");

    $row_count++;
    $row_color = ($row_count % 2) ? $color2 : $color1;

//    echo "              <tr>&nbsp;$row_count</td>\n";
    echo "              <tr class=table_border bgcolor='$row_color'><td> </td>\n";
    echo "                <td>&nbsp;<a title=\"Edit Time For: $empfullname\" href=\"timeedit.php?username=$empfullname\">$empfullname</a></td>\n";
    echo "                <td>&nbsp;$displayname</td>\n";
    echo "                <td>&nbsp;".$row['office']."</td>\n";
    echo "                <td>&nbsp;".$row['groups']."</td>\n";

    if ("".$row["disabled"]."" == 1) {
        echo "                <td><img src='../images/icons/cross.png' /></td>\n";
    } else {
        $disabled = "";
        echo "                <td>".$disabled."</td>\n";
    }

    if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {
        echo "                <td>
                                <a style='color:#27408b;text-decoration:underline;' title=\"Añadir tiempo para: $empfullname\" href=\"timeadd.php?username=$empfullname\">Add</a></td>\n";
        echo "                <td>
                                <a style='color:#27408b;text-decoration:underline;' title=\"Editar tiempo para: $empfullname\" href=\"timeedit.php?username=$empfullname\">Edit</a></td>\n";
        echo "                <td>
                                <a style='color:#27408b;text-decoration:underline;' title=\"Eliminar tiempo para: $empfullname\" href=\"timedelete.php?username=$empfullname\"> Delete</a></td></tr>\n";
    } else {
        echo "                <td>
                                <a title=\"Añadir tiempo para: $empfullname\" href=\"timeadd.php?username=$empfullname\">
                                  <button type='button' id='formButtons' onclick='location=\"timeadd.php?statusname=$empfullname\"' class='btn btn-success'>
                                    <i class='fa fa-plus'></i>
                                  </button>
                                </a>
                              </td>\n";
        echo "                <td>
                                <a title=\"Editar tiempo para: $empfullname\" href=\"timeedit.php?username=$empfullname\">
                                  <button type='button' id='formButtons' onclick='location=\"timeedit.php?statusname=$empfullname\"' class='btn btn-info'>
                                    <i class='fa fa-edit'></i>
                                  </button>
                                </a>
                              </td>\n";
        echo "                <td>
                                <a title=\"Eliminar tiempo para: $empfullname\" href=\"timedelete.php?username=$empfullname\">
                                  <button type='button' id='formButtons' onclick='location=\"timedelete.php?statusname=$empfullname\"' class='btn btn-danger'>
                                    <i class='fa fa-trash'></i>
                                  </button>
                                </a>
                              </td>
                            </tr>\n";
    }
}
echo "
</table>";

// echo "<table class='table table-hover'>
//    <tr>
//       <td>
//         <a title='Punch out employees' href='time_punch_out.php'>
//           <img border=0 src='../images/icons/clock.png' />
//           Punch out employee's with a current status of in
//         </a>
//      </td>
//      <td>
//        <a title='Punch multiple employees' href='time_punch_employees.php'>
//          <img border=0 src='../images/icons/clock.png' />
//          Punch out employee's
//        </a>
//     </td>
//   </tr>
// </table>";

echo "
</div></div></div>";
include '../footer.php';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
exit;
?>
