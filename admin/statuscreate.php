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
//include 'header_colorpick.php';
include 'header_colorpicker.php';
include 'topmain.php';
echo "<title>$title - Create Status</title>\n";

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

  echo '<div class="row">
          <div id="float_window" class="col-md-10">
            <div class="box box-info"> ';
  echo '      <div class="box-header with-border">
                   <h3 class="box-title"><i class="fa fa-exchange"></i> Create Status</h3>
              </div>
              <div class="box-body">';
echo "          <form name='form' action='$self' method='post'>\n";
echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";

echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Status Name:</td><td colspan=2 width=80%
                      style='padding-left:20px;'><input type='text'
                      size='20' maxlength='50' name='post_statusname'>&nbsp;*</td>
                    </tr>\n";

/*
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Color:</td><td colspan=2 width=80%
                      style='padding-left:20px;'><input type='text'
                      size='20' maxlength='7' name='post_color'>&nbsp;*&nbsp;&nbsp;<a href=\"#\"
                      onclick=\"cp.select(document.forms['form'].post_color,'pick');return false;\" name=\"pick\" id=\"pick\"
                      style='font-size:11px;color:#27408b;'>Pick Color</a></td><
                    /tr>\n";
                    */
echo "              <tr>
                      <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Color html:</td>
                      <td colspan=2 width=80% style='padding-left:20px;'>
                      <input type='color' name='post_color' value='#ff0000'>&nbsp;*</td>
                    </tr>\n";

echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Is Status considered '<b>In</b>' or '<b>Out</b>'?</td>\n";
echo "                  <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='create_status' value='1'>In
                      <input checked type='radio' name='create_status' value='0'>Out</td></tr>\n";
echo "              <tr><td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
//echo "            <script language=\"javascript\">cp.writeDiv()</script>\n";
echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
echo "              <tr><td height=40></td></tr>\n";
echo "            </table>\n";
echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Create Status' src='../images/buttons/next_button.png'></td>
                    <td><a href='statusadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr>
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

    $post_statusname = $_POST['post_statusname'];
    $post_color = $_POST['post_color'];
    $create_status = $_POST['create_status'];


    // begin post validation //

    if (($create_status !== '0') && ($create_status !== '1')) {exit;}

    $post_statusname = stripslashes($post_statusname);
    $post_statusname = addslashes($post_statusname);

    $string = strstr($post_statusname, "\'");
    $string2 = strstr($post_statusname, "\"");

    if (empty($string)) {
      $query = "select punchitems from ".$db_prefix."punchlist where punchitems = '".$post_statusname."'";
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
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-exchange"></i>  Create Status</h3>
                </div>
                <div class="box-body">';

    echo "         <form name='form' action='$self' method='post'>\n";
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";

    echo "              <tr><td height=15></td></tr>\n";
    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Status Name:</td><td colspan=2 width=80%
                          style='padding-left:20px;'><input type='text'
                          size='20' maxlength='50' name='post_statusname' value=\"$post_statusname\">&nbsp;*</td></tr>\n";
    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Color:</td><td colspan=2 width=80%
                          style='padding-left:20px;'><input type='text'
                          size='20' maxlength='7' name='post_color' value=\"$post_color\">&nbsp;*&nbsp;&nbsp;<a href=\"#\"
                          onclick=\"cp.select(document.forms['form'].post_color,'pick');return false;\" name=\"pick\" id=\"pick\"
                          style='font-size:11px;color:#27408b;'>Pick Color</a></td></tr>\n";
    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Is Status considered '<b>In</b>' or '<b>Out</b>'?</td>\n";

    if ($create_status == '1') {
    echo "                  <td class=table_rows align=left width=80% style='padding-left:20px;'><input checked type='radio' name='create_status' value='1'>In
                          <input type='radio' name='create_status' value='0'>Out</td></tr>\n";
    } elseif ($create_status == '0') {
    echo "                  <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='create_status' value='1'>In
                          <input checked type='radio' name='create_status' value='0'>Out</td></tr>\n";
    }

    if (!empty($string)) {$post_statusname = stripslashes($post_statusname);}
    if (!empty($string2)) {$post_statusname = stripslashes($post_statusname);}

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

    $query = "insert into ".$db_prefix."punchlist (punchitems, color, in_or_out) values ('".$post_statusname."', '".$post_color."', '".$create_status."')";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);


    echo '<div class="row">
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-exchange"></i> Create Status</h3>
                </div>
                <div class="box-body">';

    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr>\n";
    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/accept.png' /></td>
                          <td class=table_rows_green>&nbsp;Status created successfully.</td></tr>\n";
    echo "            </table>\n";
    echo "            <br />\n";
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";

    echo "              <tr><td height=15></td></tr>\n";
    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Status Name:</td><td align=left class=table_rows
                          colspan=2 width=80% style='padding-left:20px;'>$post_statusname</td></tr>\n";
    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Color:</td><td align=left class=table_rows
                          colspan=2 width=80% style='padding-left:20px;'>$post_color</td></tr>\n";

    if ($create_status == '1') {
      $create_status_tmp = 'In';
      } else {
      $create_status_tmp = 'Out';
    }

    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Is Status considered '<b>In</b>' or '<b>Out</b>'?</td>
                          <td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$create_status_tmp</td></tr>\n";
    echo "              <tr><td height=15></td></tr>\n";
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
