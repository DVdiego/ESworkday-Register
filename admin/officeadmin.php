<?php
/***************************************************************************
 *   Copyright (C) 2006 by Ken Papizan                                     *
 *   Copyright (C) 2008 by phpTimeClock Team                               *
 *   http://sourceforge.net/projects/phptimeclock                          *
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


echo "<title>$title - Office Summary</title>\n";

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

echo '<div class="row">
        <div class="col-xs-12">
          <div class="box">

          <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">';

echo "          <table class='table table-hover' width=60% align=center border=0 cellpadding=0 cellspacing=0>\n";
echo "               <tr><th>Office Summary</th></tr>\n";
echo "                <tr>
                        <th>&nbsp;</th>\n";
echo "                  <th>Office Name</th>\n";
echo "                  <th>Groups</th>\n";
echo "                  <th>Users</th>\n";
echo "                  <th>Edit</th>\n";
echo "                  <th>Delete</th>
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

    echo "                <tr class=table_border bgcolor='$row_color'><td>&nbsp;$row_count</td>\n";
    echo "                  <td>&nbsp;<a class=footer_links title='Edit Office: ".$row["officename"]."'
                            href=\"officeedit.php?officename=".$row["officename"]."\">".$row["officename"]."</a></td>\n";
    echo "                  <td>$group_cnt</td>\n";
    echo "                  <td>$user_cnt</td>\n";

       if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

    echo "                  <td><a style='color:#27408b;text-decoration:underline;'
                            href=\"officeedit.php?officename=".$row["officename"]."\" title=\"Edit Office: ".$row["officename"]."\">
                            Edit</a></td>\n";
    echo "                  <td><a style='color:#27408b;text-decoration:underline;'
                            href=\"officedelete.php?officename=".$row["officename"]."\" title=\"Delete Office: ".$row["officename"]."\">
                            Delete</a></td>
                          </tr>\n";
      } else {

    echo "                  <td><a href=\"officeedit.php?officename=".$row["officename"]."\"
                            title=\"Edit Office: ".$row["officename"]."\">
                            <img border=0 src='../images/icons/application_edit.png'/></a></td>\n";
    echo "                  <td><a href=\"officedelete.php?officename=".$row["officename"]."\"
                            title=\"Delete Office: ".$row["officename"]."\">
                            <img border=0 src='../images/icons/delete.png' /></a></td>
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
