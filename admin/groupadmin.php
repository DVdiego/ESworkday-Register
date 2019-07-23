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

echo "<title>$title - Group Summary</title>\n";

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

include 'leftmain.php';

//Plantilla superior de los usuarios.
include 'groupsummary.php';

echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-users"></i> Grupos</h3>
            </div>
            <div class="box-body">';

echo "          <table class='table table-hover'>\n";
echo "              <tr>
                      <th>&nbsp;</th>\n";
echo "                <th>Nombre del grupo</th>\n";
echo "                <th>Forma parte de la oficina</th>\n";
echo "                <th>Usuarios</th>\n";
echo "                <th>Editar</th>\n";
echo "                <th>Eliminar</th>
                  </tr>\n";

$row_count = 0;

$query = "select * from ".$db_prefix."groups, ".$db_prefix."offices where ".$db_prefix."groups.officeid = ".$db_prefix."offices.officeid
          order by ".$db_prefix."offices.officename, ".$db_prefix."groups.groupname";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {

    $query2 = "select groups from ".$db_prefix."employees where groups = '".$row['groupname']."' and office = '".$row['officename']."'";
    $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
    @$user_cnt = mysqli_num_rows($result2);

    $parent_office = "".$row['officename']."";

    $row_count++;
    $row_color = ($row_count % 2) ? $color2 : $color1;

    echo "              <tr class=table_border bgcolor='$row_color'><td>&nbsp;$row_count</td>\n";
    echo "                <td>&nbsp;<a class=footer_links title='Editar grupo: ".$row["groupname"]."'
                           href=\"groupedit.php?groupname=".$row["groupname"]."&officename=$parent_office\">".$row["groupname"]."</a></td>\n";
    echo "                <td>&nbsp;$parent_office</td>\n";
    echo "                <td>&nbsp;$user_cnt</td>\n";

    if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

    echo "                <td>
                            <button type='button' id='formButtons' onclick='location=\"groupedit.php?groupname=".$row["groupname"]."&officename=$parent_office\"' class='btn btn-info'>
                              <i class='fa fa-edit'></i>
                            </button>
                          </td>\n";
    echo "                <td>
                            <button type='button' id='formButtons' onclick='location=\"groupdelete.php?groupname=".$row["groupname"]."&officename=$parent_office\"' class='btn btn-danger'>
                              <i class='fa fa-trash'></i>
                            </button>
                          </td>
                        </tr>\n";
    } else {
    echo "                <td>
                            <button type='button' id='formButtons' onclick='location=\"groupedit.php?groupname=".$row["groupname"]."&officename=$parent_office\"' class='btn btn-info'>
                              <i class='fa fa-edit'></i>
                            </button>
                          </td>\n";
    echo "                <td>
                            <button type='button' id='formButtons' onclick='location=\"groupdelete.php?groupname=".$row["groupname"]."&officename=$parent_office\"' class='btn btn-danger'>
                              <i class='fa fa-trash'></i>
                            </button>
                          </td>
                        </tr>\n";
    }
}
echo "          </table>\n";
echo '      </div>
          </div>
        </div>
      </div>';
include '../theme/templates/endmaincontent.inc';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
include '../footer.php';exit;

?>
