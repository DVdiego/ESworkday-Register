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
include 'leftmain.php';
echo "<title>$title - Change Password</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

if (!isset($_SESSION['valid_profile'])) {

echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Administration</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=admin'><u>here</u></a> to login.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}


if ($request == 'GET') {

  if($_SESSION['valid_profile'] != $_GET['username']){
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

if (!isset($_GET['username'])) {

echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Error!</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table width=300 border=0 cellpadding=5 cellspacing=0>\n";
echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Go back to the <a class=admin_headings href='index.php'>User Summary</a>
            page to change passwords.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}

$get_user = $_GET['username'];


if (get_magic_quotes_gpc()) {$get_user = stripslashes($get_user);}


$get_user = addslashes($get_user);


if($login_with_fullname == "yes"){
  $query = "select empfullname from ".$db_prefix."employees where empfullname = '".$get_user."'";
}elseif ($login_with_displayname == "yes"){
  $query = "select empfullname from ".$db_prefix."employees where displayname = '".$get_user."'";
}elseif ($login_with_dni == "yes"){
  $query = "select empfullname from ".$db_prefix."employees where empDNI = '".$get_user."'";
}


$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {
$username = stripslashes("".$row['empfullname']."");
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
if (!isset($username)) {echo "username is not defined for this user.\n"; exit;}

echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info"> ';
echo '      <div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-lock"></i> Cambiar Contraseña</h3>
            </div>
            <div class="box-body">';

echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class=table>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Usuario:</td><td style='padding-left:20px;'
                      align=left class=table_rows width=80%><input type='hidden' name='post_username' value=\"$username\">$username</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Nueva Contraseña:</td><td colspan=2
                      style='padding-left:20px;'><input type='password' size='25' maxlength='25' name='new_password'></td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Confirmar Contraseña:</td><td colspan=2
                      style='padding-left:20px;'><input type='password' size='25' maxlength='25'name='confirm_password'>
                      </td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
// echo "            <input type='hidden' name='get_office' value='$get_office'>\n";
echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
echo "              <tr><td height=40>&nbsp;</td></tr>\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Change Password'
                  src='../images/buttons/next_button.png'></td>
                  <td><a href='index.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form>\n";


echo '      </div>
          </div>
        </div>
      </div>';
	      include '../theme/templates/endmaincontent.inc';
	      include '../footer.php';
	      include '../theme/templates/controlsidebar.inc';
	      include '../theme/templates/endmain.inc';
	      include '../theme/templates/adminfooterscripts.inc';
exit;
}

elseif ($request == 'POST') {

  $post_username = stripslashes($_POST['post_username']);
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];


  $post_username = addslashes($post_username);

  // begin post validation //

  if (!empty($post_username)) {
      $query = "select * from ".$db_prefix."employees where empfullname = '".$post_username."'";
      $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
      while ($row=mysqli_fetch_array($result)) {
        $username = "".$row['empfullname']."";
      }
      ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
      if (!isset($username)) {echo "username is not defined for this user.\n"; exit;}
  }

  $post_username = stripslashes($post_username);

  //if (!eregi ("^([[:alnum:]]|~|\!|@|#|\$|%|\^|&|\*|\(|\)|-|\+|`|_|\=|\{|\}|\[|\]|\||\:|\<|\>|\.|,|\?)+$", $new_password)) {
  //if (!eregi ("^([[:alnum:]]|~|\!|@|#|\$|%|\^|&|\*|\(|\)|-|\+|`|_|\=|[{]|[}]|\[|\]|\||\:|\<|\>|\.|,|\?)+$", $new_password)) {

  if (preg_match("/^[\s\\/;'\"-]*$/i", $new_password)) {


  $evil_password = '1';
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        Single and double quotes, backward and forward slashes, semicolons, and spaces are not allowed when creating a Password.</td></tr>\n";
    echo "            </table>\n";
  }
  elseif ($new_password !== $confirm_password) {
    $evil_password = '1';
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        Passwords do not match.</td></tr>\n";
    echo "            </table>\n";
  }

  // end post validation //

  if (isset($evil_password)) {

      echo "            <br />\n";
      echo '<div class="row">
              <div id="float_window" class="col-md-10">
                <div class="box box-info"> ';
      echo '      <div class="box-header with-border">
                       <h3 class="box-title"><i class="fa fa-lock"></i> Cambiar Contraseña</h3>
                  </div>
                  <div class="box-body">';
      echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
      echo "            <form name='form' action='$self' method='post'>\n";
      echo "              <tr><th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/lock_edit.png' />&nbsp;&nbsp;&nbsp;Change
                          Password</th></tr>\n";
      echo "              <tr><td height=15></td></tr>\n";
      echo "              <tr><td class=table_rows width=20% height=25 style='padding-left:32px;' nowrap>Usuario:</td><td align=left class=table_rows width=80%
                            style='padding-left:20px;'><input type='hidden' name='post_username' value=\"$post_username\">$post_username</td></tr>\n";
      echo "              <tr><td class=table_rows width=20% height=25 style='padding-left:32px;' nowrap>Nueva Contraseña:</td><td colspan=2
                            style='padding-left:20px;' width=80%><input type='password' size='25' maxlength='25' name='new_password'></td></tr>\n";
      echo "              <tr><td class=table_rows width=20% height=25 style='padding-left:32px;' nowrap>Confirmar Contraseña:</td><td colspan=2
                            style='padding-left:20px;' width=80%><input type='password' size='25' maxlength='25'name='confirm_password'>
                            </td></tr>\n";
      echo "              <tr><td height=15></td></tr>\n";
      echo "            </table>\n";
      echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
      echo "              <tr><td height=40>&nbsp;</td></tr>\n";
      echo "              <input type='hidden' name='get_office' value=\"$get_office\">\n";
      echo "              <tr><td width=30><input type='image' name='submit' value='Change Password'
                            src='../images/buttons/next_button.png'></td><td><a href='index.php'>
                            <img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form>\n";
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

            $new_password = password_hash($new_password, PASSWORD_DEFAULT, ['cost' => 10]);
            $confirm_password = password_hash($confirm_password, PASSWORD_DEFAULT, ['cost' => 10]);

            $post_username = addslashes($post_username);

            $query = "update ".$db_prefix."employees set employee_passwd = ('".$new_password."') where empfullname = ('".$post_username."')";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

            $post_username = stripslashes($post_username);
            echo '<div class="row">
                    <div id="float_window" class="col-md-10">
                      <div class="box box-info"> ';
            echo '      <div class="box-header with-border">
                             <h3 class="box-title"><i class="fa fa-lock"></i> Cambiar Contraseña</h3>
                        </div>
                        <div class="box-body">';

            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr><td class=table_rows width=20 align=center><img src='../images/icons/accept.png' /></td>
                            <td class=table_rows_green>&nbsp;Password changed successfully.</td></tr>\n";
            echo "            </table>\n";
            echo "            <br />\n";
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
            echo "              <tr><th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/lock_edit.png' />&nbsp;&nbsp;&nbsp;Change
                                  Password</th></tr>\n";
            echo "              <tr><td height=15></td></tr>\n";
            echo "              <tr><td class=table_rows width=20% height=25 style='padding-left:32px;' nowrap>Usuario:</td><td align=left class=table_rows width=80%
                                  style='padding-left:20px;'><input type='hidden' name='post_username' value=\"$post_username\">$post_username</td></tr>\n";
            echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Nueva Contraseña:</td><td align=left class=table_rows
                                  colspan=2 style='padding-left:20px;' width=80%>***hidden***</td></tr>\n";
            echo "              <tr><td height=15></td></tr>\n";
            echo "            </table>\n";
            echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr><td height=40>&nbsp;</td></tr>\n";
            echo "              <tr><td><a href='index.php'><img src='../images/buttons/done_button.png' border='0'></td></tr>
                        </table>\n";
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
}
?>
