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

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

include '../config.inc.php';
include 'header.php';
include 'topmain.php';

echo "<title>$title - Status Summary</title>\n";

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

echo '<div class="row">
        <div class="col-xs-12">
          <div class="box">

          <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">';

echo "            <table class='table table-hover' width=60% align=center border=0 cellpadding=0 cellspacing=0>\n";
echo "              <tr><th>Status Summary</th></tr>\n";
echo "              <tr>
                      <th>&nbsp;</th>\n";
echo "                <th>Status Name</th>\n";
echo "                <th>Color</th>\n";
echo "                <th>In/Out</th>\n";
echo "                <th>Edit</th>\n";
echo "                <th>Delete</th>
                    </tr>\n";

$row_count = 0;

$query = "select * from ".$db_prefix."punchlist";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {

  $punchitem = "".$row['punchitems']."";
  $color = "".$row['color']."";
  $in_or_out = "".$row['in_or_out']."";

  $row_count++;
  $row_color = ($row_count % 2) ? $color2 : $color1;

  if ($in_or_out == '1') {
    $in_or_out_tmp = 'In';
  } elseif ($in_or_out == '0') {
    $in_or_out_tmp = 'Out';
  }

  echo "              <tr class=table_border bgcolor='$row_color'><td>&nbsp;$row_count</td>\n";
  echo "                <td>&nbsp;<a class=footer_links title='Edit Status: $punchitem'
                      href='statusedit.php?statusname=$punchitem'>$punchitem</a></td>\n";
  echo "                <td style='color:$color';>&nbsp;$color</td>\n";
  echo "                <td>$in_or_out_tmp</td>\n";

  if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

  echo "                <td><a style='color:#27408b;text-decoration:underline;' title=\"Edit Status: $punchitem\"
                      href=\"statusedit.php?statusname=$punchitem\">Edit</a></td>\n";
  echo "                <td><a style='color:#27408b;text-decoration:underline;' title=\"Delete Status: $punchitem\"
                      href=\"statusdelete.php?statusname=$punchitem\">Delete</a></td></tr>\n";
  } else {
  echo "                <td><a title=\"Edit Status: $punchitem\" href=\"statusedit.php?statusname=$punchitem\">
                      <img border=0 src='../images/icons/application_edit.png' /></a></td>\n";
  echo "                <td><a title=\"Delete Status: $punchitem\" href=\"statusdelete.php?statusname=$punchitem\">
                      <img border=0 src='../images/icons/delete.png' /></a></td></tr>\n";
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
