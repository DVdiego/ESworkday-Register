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


echo "<title>$title - Resumen oficinas</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

if (!isset($_SESSION['valid_user'])) {

  echo ' <div class="col-md-4"><div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> WorkTime Control Administration</h4>
                You are not presently logged in, or do not have permission to view this page. Click <a href="../login.php?login_action=admin"><u>here</u></a> to login.
              </div>
          </div>';exit;
}

include 'leftmain.php';

//Plantilla superior de los usuarios.
include 'usersummary.php';

echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-suitcase"></i> Oficinas</h3>
            </div>
            <div class="box-body">';

echo "          <table class='table table-hover'>\n";
echo "                <tr>
                        <th>&nbsp;</th>\n";
echo "                  <th>Nombre de oficina</th>\n";
echo "                  <th>Grupos</th>\n";
echo "                  <th>usuarios</th>\n";
echo "                  <th>Editar</th>\n";
echo "                  <th>Eliminar</th>
                      </tr>\n";

$row_count = 0;

$query = "select * from ".$db_prefix."offices order by officename";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {

    $query2 = "select office from ".$db_prefix."employees where office = '".$row['officename']."'";
    $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
    @$user_cnt = mysqli_num_rows($result2);

    $query3 = "select * from ".$db_prefix."groups where officeid = '".$row['officeid']."'";
    $result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);
    @$group_cnt = mysqli_num_rows($result3);

    $row_count++;
    $row_color = ($row_count % 2) ? $color2 : $color1;

    echo "                <tr class=table_border bgcolor='$row_color'>
                            <td>&nbsp;$row_count</td>\n";
    echo "                  <td>&nbsp;<a class=footer_links title='Editar oficina: ".$row["officename"]."'
                            href=\"officeedit.php?officename=".$row["officename"]."\">".$row["officename"]."</a></td>\n";
    echo "                  <td>$group_cnt</td>\n";
    echo "                  <td>$user_cnt</td>\n";

       if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

    echo "                  <td><a style='color:#27408b;text-decoration:underline;'
                            href=\"officeedit.php?officename=".$row["officename"]."\" title=\"Editar oficina: ".$row["officename"]."\">
                            Edit</a></td>\n";
    echo "                  <td><a style='color:#27408b;text-decoration:underline;'
                            href=\"officedelete.php?officename=".$row["officename"]."\" title=\"Eliminar oficina: ".$row["officename"]."\">
                            Delete</a></td>
                          </tr>\n";
      } else {

    echo "                  <td>
                              <button type='button' id='formButtons' onclick='location=\"officeedit.php?officename=".$row["officename"]."\"' class='btn btn-info'>
                                <i class='fa fa-edit'></i>
                              </button>
                            </td>\n";
    echo "                  <td>
                              <button type='button' id='formButtons' onclick='location=\"officedelete.php?officename=".$row["officename"]."\"' class='btn btn-danger'>
                                <i class='fa fa-trash'></i>
                              </button>
                            </td>
                          </tr>\n";
      }
}
echo "            </table>\n";

echo '    </div>
        </div>
       </div>
     </div>';
include '../theme/templates/endmaincontent.inc';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
include '../footer.php';exit;
?>
