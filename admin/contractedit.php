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
//include 'header_colorpick.php';
include 'header_colorpicker.php';
include 'topmain.php';
echo "<title>$title - Create Contract</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

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
include 'leftmain.php'; //esta despues de verficar la sesión para que no cargue el menú lateral sino esta autendicado.

if ($request == 'GET') {

$get_contract = $_GET['contract'];
if (get_magic_quotes_gpc()) {$get_contract = stripslashes($get_contract);}
$get_contract = addslashes($get_contract);
$row_count = 0;



$query = "SELECT * FROM ".$db_prefix."contracts WHERE `type_contracts` LIKE '".$get_contract."' ORDER BY `daily_hours` ASC";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {

$row_count++;
$row_color = ($row_count % 2) ? $color2 : $color1;

$type_contracts = stripslashes("".$row['type_contracts']."");

$overtime_cost = "".$row['overtime_cost']."";
$daily_hours = "".$row['daily_hours']."";

}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

  echo '<div class="row">
          <div class="col-md-8">
            <div class="box box-info"> ';
  echo '      <div class="box-header with-border">
                   <h3 class="box-title"><i class="fa fa-suitcase"></i> Create Contract</h3>
              </div>
              <div class="box-body">';
echo "          <form name='form' action='$self' method='post'>\n";
echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3>
                    <img src='../images/icons/application_add.png' />&nbsp;&nbsp;&nbsp;Create Contract</th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Contract Type Name:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>
                      <input type='text' size='20' maxlength='50' name='post_contractname' value='$type_contracts' >&nbsp;*</td>
                    </tr>\n";

echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Daily Hours:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>
                      <input type='text' size='20' maxlength='50' name='post_dailyhours' value='$daily_hours'>&nbsp;*</td>
                    </tr>\n";

echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Cost Overtime:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>
                      <input type='text' size='20' maxlength='50' name='post_overtime' value='$overtime_cost'>&nbsp;*</td>
                    </tr>\n";

echo "              <tr><td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
//echo "            <script language=\"javascript\">cp.writeDiv()</script>\n";
echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
echo "              <tr><td height=40></td></tr>\n";
echo "            </table>\n";
echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Create Contract' src='../images/buttons/next_button.png'></td>
                    <td><a href='contractadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr>
                  </table>
              </form>\n";

echo '      </div>
          </div>
        </div>
      </div>';
include '../theme/templates/endmaincontent.inc';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
include '../footer.php';exit;

}

elseif ($request == 'POST') {

    $post_contractname = $_POST['post_contractname'];
    $post_dailyhours = $_POST['post_dailyhours'];
    $post_overtime = $_POST['post_overtime'];


    // begin post validation //

    $post_contractname = stripslashes($post_contractname);
    $post_contractname = addslashes($post_contractname);

    $string = strstr($post_contractname, "\'");
    $string2 = strstr($post_contractname, "\"");

    if (empty($string)) {
      $query = "select type_contracts from ".$db_prefix."contracts where type_contracts = '".$post_contractname."'";
      $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      while ($row=mysqli_fetch_array($result)) {
        $dupe = '1';
        }
    }

    // if ((empty($post_statusname)) || (empty($post_color)) || (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_statusname)) || (isset($dupe)) || ((!eregi ("^(#[a-fA-F0-9]{6})+$", $post_color)) && (!eregi ("^([a-fA-F0-9]{6})+$", $post_color))) || (!empty($string)) || (!empty($string2))) {

    if ((empty($post_statusname)) || (empty($post_color)) || (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_statusname)) || (isset($dupe)) || ((!preg_match('/' . "^(#[a-fA-F0-9]{6})+$" . '/i', $post_color)) && (!preg_match('/' . "^([a-fA-F0-9]{6})+$" . '/i', $post_color))) || (!empty($string)) || (!empty($string2))) {

    if (empty($post_statusname)) {
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr>\n";
    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        A Status Name is required.</td></tr>\n";
    echo "            </table>\n";
    }
    elseif (empty($post_color)) {
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr>\n";
    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        A Color is required.</td></tr>\n";
    echo "            </table>\n";
    }elseif (!empty($string)) {
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        Apostrophes are not allowed.</td></tr>\n";
    echo "            </table>\n";
    }elseif (!empty($string2)) {
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        Double Quotes are not allowed.</td></tr>\n";
    echo "            </table>\n";
    // }elseif (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_statusname)) {
    }elseif (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_statusname)) {
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr>\n";
    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        Alphanumeric characters, hyphens, underscores, spaces, and periods are allowed when editing a Status Name.</td></tr>\n";
    echo "            </table>\n";
    }
    // elseif ((!eregi ("^(#[a-fA-F0-9]{6})+$", $post_color)) && (!eregi ("^([a-fA-F0-9]{6})+$", $post_color))) {
    elseif ((!preg_match('/' . "^(#[a-fA-F0-9]{6})+$" . '/i', $post_color)) && (!preg_match('/' . "^([a-fA-F0-9]{6})+$" . '/i', $post_color))) {
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr>\n";
    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        The '#' symbol followed by letters A-F, or numbers 0-9 are allowed when editing a Color.</td></tr>\n";
    echo "            </table>\n";
    }elseif (isset($dupe)) {
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        Status already exists. Create another status.</td></tr>\n";
    echo "            </table>\n";
    }

    if (!empty($string)) {$post_statusname = stripslashes($post_statusname);}
    if (!empty($string2)) {$post_statusname = stripslashes($post_statusname);}

    echo "            <br />\n";

    echo '<div class="row">
            <div class="col-md-8">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-suitcase"></i>  Create Status</h3>
                </div>
                <div class="box-body">';

    echo "         <form name='form' action='$self' method='post'>\n";
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
    echo "              <tr><th class=rightside_heading nowrap halign=left colspan=3>
                          <img src='../images/icons/application_add.png' />&nbsp;&nbsp;&nbsp;Create Contract</th></tr>\n";
    echo "              <tr><td height=15></td></tr>\n";

    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Contract Type Name:</td><td colspan=2 width=80%
                          style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'><input type='text'
                          size='20' maxlength='50' name='post_contractname' value=\"$post_contractname\">&nbsp;*</td></tr>\n";

    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Daily Hours:</td><td colspan=2 width=80%
                          style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>
                          <input type='text' size='20' maxlength='50' name='post_dailyhours' value=\"$post_dailyhours\">&nbsp;*</td>
                        </tr>\n";

    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Cost Overtime:</td><td colspan=2 width=80%
                          style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>
                          <input type='text' size='20' maxlength='50' name='post_overtime' value=\"$post_overtime\">&nbsp;*</td>
                        </tr>\n";


    echo "              <tr><td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>*&nbsp;required&nbsp;</td></tr>\n";
    echo "            </table>\n";
    echo "            <script language=\"javascript\">cp.writeDiv()</script>\n";
    echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td height=40></td></tr>\n";
    echo "            </table>\n";
    echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr>
                            <td width=30><input type='image' name='submit' value='Create Status' src='../images/buttons/next_button.png'></td>
                            <td><a href='statusadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td>
                        </tr>
                      </table>
                </form>\n";

    echo '      </div>
              </div>
            </div>
          </div>';
    include '../theme/templates/endmaincontent.inc';
    include '../theme/templates/controlsidebar.inc';
    include '../theme/templates/endmain.inc';
    include '../theme/templates/adminfooterscripts.inc';
    include '../footer.php';exit;

    } else {

    $query = "update ".$db_prefix."contracts (daily_hours, typre_contracts, overtime_cost) values ('".$post_contractname."', '".$post_dailyhours."', '".$post_overtime."')";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);


    echo '<div class="row">
            <div class="col-md-8">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-suitcase"></i> Create Contract</h3>
                </div>
                <div class="box-body">';

    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr>\n";
    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/accept.png' /></td>
                          <td class=table_rows_green>&nbsp;Contract created successfully.</td></tr>\n";
    echo "            </table>\n";
    echo "            <br />\n";
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
    echo "              <tr><th class=rightside_heading nowrap halign=left colspan=3>
                          <img src='../images/icons/application_add.png' />&nbsp;&nbsp;&nbsp;Create Status</th>\n";
    echo "              </tr>\n";
    echo "              <tr><td height=15></td></tr>\n";
    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Contract Type Name:</td><td colspan=2 width=80%
                          style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>$post_contractname</td>
                          </tr>\n";

    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Daily Hours:</td><td colspan=2 width=80%
                          style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>$post_dailyhours</td>
                        </tr>\n";

    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Cost Overtime:</td><td colspan=2 width=80%
                          style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>$post_overtime</td>
                        </tr>\n";
    echo "            </table>\n";
    echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
    echo "              <tr><td><a href='statusadmin.php'><img src='../images/buttons/done_button.png' border='0'></a></td></tr>
                      </table>\n";
    }
    echo '      </div>
              </div>
            </div>
          </div>';
    include '../theme/templates/endmaincontent.inc';
    include '../theme/templates/controlsidebar.inc';
    include '../theme/templates/endmain.inc';
    include '../theme/templates/adminfooterscripts.inc';
    include '../footer.php';exit;
}
?>
