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
echo "<title>$title - Change Password</title>\n";

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

if ($request == 'GET') {

if (!isset($_GET['username'])) {

echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Error!</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table width=300 border=0 cellpadding=5 cellspacing=0>\n";
echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Go back to the <a class=admin_headings href='useradmin.php'>User Summary</a>
            page to change passwords.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}

$get_user = $_GET['username'];
@$get_office = $_GET['officename'];

if (get_magic_quotes_gpc()) {$get_user = stripslashes($get_user);}

/*
echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
echo "  <tr valign=top>\n";
echo "    <td class=left_main width=180 align=left scope=col>\n";
echo "      <table class=hide width=100% border=0 cellpadding=1 cellspacing=0>\n";

// display links in top left of each page //

echo "        <tr><td class=left_rows height=11></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle>Users</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/user.png' alt='User Summary' />&nbsp;&nbsp;
                <a class=admin_headings href='useradmin.php'>User Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows_indent height=18 align=left valign=middle><img src='../images/icons/arrow_right.png' alt='Edit User' />&nbsp;&nbsp;
                <a class=admin_headings href=\"useredit.php?username=$get_user&officename=$get_office\">Edit User</a></td></tr>\n";
echo "        <tr><td class=current_left_rows_indent height=18 align=left valign=middle><img src='../images/icons/arrow_right.png' alt='Change Password' />
                &nbsp;&nbsp;<a class=admin_headings href=\"chngpasswd.php?username=$get_user&officename=$get_office\">Change Password</a></td></tr>\n";
echo "        <tr><td class=left_rows_indent height=18 align=left valign=middle><img src='../images/icons/arrow_right.png' alt='Delete User' />
                &nbsp;&nbsp;<a class=admin_headings href=\"userdelete.php?username=$get_user&officename=$get_office\">Delete User</a></td></tr>\n";
echo "        <tr><td class=left_rows_border_top height=18 align=left valign=middle><img src='../images/icons/user_add.png' alt='Create New User' />
                &nbsp;&nbsp;<a class=admin_headings href='usercreate.php'>Create New User</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/magnifier.png' alt='User Search' />&nbsp;&nbsp;
                <a class=admin_headings href='usersearch.php'>User Search</a></td></tr>\n";
echo "        <tr><td class=left_rows height=33></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle>Offices</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/brick.png' alt='Office Summary' />&nbsp;&nbsp;
                <a class=admin_headings href='officeadmin.php'>Office Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/brick_add.png' alt='Create New Office' />&nbsp;&nbsp;
                <a class=admin_headings href='officecreate.php'>Create New Office</a></td></tr>\n";
echo "        <tr><td class=left_rows height=33></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle>Groups</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/group.png' alt='Group Summary' />&nbsp;&nbsp;
                <a class=admin_headings href='groupadmin.php'>Group Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/group_add.png' alt='Create New Group' />&nbsp;&nbsp;
                <a class=admin_headings href='groupcreate.php'>Create New Group</a></td></tr>\n";
echo "        <tr><td class=left_rows height=33></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle colspan=2>In/Out Status</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/application.png' alt='Status Summary' />
                &nbsp;&nbsp;<a class=admin_headings href='statusadmin.php'>Status Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/application_add.png' alt='Create Status' />&nbsp;&nbsp;
                <a class=admin_headings href='statuscreate.php'>Create Status</a></td></tr>\n";
echo "        <tr><td class=left_rows height=33></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle colspan=2>Miscellaneous</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/clock.png' alt='Modify Time' />
                &nbsp;&nbsp;<a class=admin_headings href='timeadmin.php'>Modify Time</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/application_edit.png' alt='Edit System Settings' />
                &nbsp;&nbsp;<a class=admin_headings href='sysedit.php'>Edit System Settings</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/database_go.png'
                alt='Manage Database' />&nbsp;&nbsp;&nbsp;<a class=admin_headings href='database_management.php'>Manage Database</a></td></tr>\n";
echo "      </table></td>\n";
echo "    <td align=left class=right_main scope=col>\n";
echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
echo "        <tr class=right_main_text>\n";
echo "          <td valign=top>\n";
echo "            <br />\n";
*/

$get_user = addslashes($get_user);

$query = "select empfullname from ".$db_prefix."employees where empfullname = '".$get_user."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row=mysqli_fetch_array($result)) {
$username = stripslashes("".$row['empfullname']."");
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
if (!isset($username)) {echo "username is not defined for this user.\n"; exit;}

if (!empty($get_office)) {
$query = "select * from ".$db_prefix."offices where officename = '".$get_office."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row=mysqli_fetch_array($result)) {
$getoffice = "".$row['officename']."";
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
}
if (!isset($getoffice)) {echo "Office is not defined for this user. Go back and associate this user with an office.\n"; exit;}
echo '<div class="row">
    <div id="float_window" class="col-md-10">
      <div class="box box-info"> ';
echo '<div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-lock"></i> Cambiar contraseña de '. $username .'</h3>
               </div><div class="box-body">';
echo "            <table class=table>\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        Nombre de usuario:
                      </td>

                      <td style='padding-left:20px;' align=left class=table_rows width=80%>
                        <input type='hidden' name='post_username' value=\"$username\">$username
                      </td>
                    </tr>\n";

echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        Contraseña nueva
                      </td>

                      <td colspan=2 style='padding-left:20px;'>
                        <input type='password' size='25' maxlength='25' name='new_password'>
                      </td>
                    </tr>\n";

echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        Confirmar contraseña:
                      </td>

                      <td colspan=2 style='padding-left:20px;'>
                        <input type='password' size='25' maxlength='25'name='confirm_password'>
                      </td>
                    </tr>\n";
echo "            </table>\n";
echo "            <input type='hidden' name='get_office' value='$get_office'>\n";
echo '            <div class="box-footer">
                    <button id="formButtons" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                      <i class="fa fa-ban"></i>
                      <a href="useradmin.php">
                        Cancelar
                      </a>
                    </button>

                    <button id="formButtons" type="submit" name="submit" value="Change Password" class="btn btn-info pull-right">
                      Cambiar contraseña
                      <i classfa fa-save"></i>
                    </button>
                  </div></form>';
echo '</div></div></div></div>';

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
$get_office = $_POST['get_office'];

// begin post validation //

if (!empty($get_office)) {
$query = "select * from ".$db_prefix."offices where officename = '".$get_office."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row=mysqli_fetch_array($result)) {
$getoffice = "".$row['officename']."";
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
}
if (!isset($getoffice)) {echo "Office is not defined for this user. Go back and associate this user with an office.\n"; exit;}

// end post validation //

echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
echo "  <tr valign=top>\n";
echo "    <td class=left_main width=180 align=left scope=col>\n";
echo "      <table class=hide width=100% border=0 cellpadding=1 cellspacing=0>\n";
echo "        <tr><td class=left_rows height=11></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle>Users</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/user.png' alt='User Summary' />&nbsp;&nbsp;
                <a class=admin_headings href='useradmin.php'>User Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows_indent height=18 align=left valign=middle><img src='../images/icons/arrow_right.png' alt='Edit User' />&nbsp;&nbsp;
                <a class=admin_headings href=\"useredit.php?username=$post_username&officename=$get_office\">Edit User</a></td></tr>\n";
echo "        <tr><td class=current_left_rows_indent height=18 align=left valign=middle><img src='../images/icons/arrow_right.png' alt='Change Password' />
                &nbsp;&nbsp;<a class=admin_headings href=\"chngpasswd.php?username=$post_username&officename=$get_office\">Change Password</a></td>
                </tr>\n";
echo "        <tr><td class=left_rows_indent height=18 align=left valign=middle><img src='../images/icons/arrow_right.png' alt='Delete User' />&nbsp;&nbsp;
                <a class=admin_headings href=\"userdelete.php?username=$post_username&officename=$get_office\">Delete User</a></td></tr>\n";
echo "        <tr><td class=left_rows_border_top height=18 align=left valign=middle><img src='../images/icons/user_add.png' alt='Create New User' />
                &nbsp;&nbsp;<a class=admin_headings href='usercreate.php'>Create New User</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/magnifier.png' alt='User Search' />&nbsp;&nbsp;
                <a class=admin_headings href='usersearch.php'>User Search</a></td></tr>\n";
echo "        <tr><td class=left_rows height=33></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle>Offices</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/brick.png' alt='Office Summary' />&nbsp;&nbsp;
                <a class=admin_headings href='officeadmin.php'>Office Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/brick_add.png' alt='Create New Office' />&nbsp;&nbsp;
                <a class=admin_headings href='officecreate.php'>Create New Office</a></td></tr>\n";
echo "        <tr><td class=left_rows height=33></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle>Groups</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/group.png' alt='Group Summary' />&nbsp;&nbsp;
                <a class=admin_headings href='groupadmin.php'>Group Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/group_add.png' alt='Create New Group' />&nbsp;&nbsp;
                <a class=admin_headings href='groupcreate.php'>Create New Group</a></td></tr>\n";
echo "        <tr><td class=left_rows height=33></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle colspan=2>In/Out Status</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/application.png' alt='Status Summary' />
                &nbsp;&nbsp;<a class=admin_headings href='statusadmin.php'>Status Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/application_add.png' alt='Create Status' />&nbsp;&nbsp;
                <a class=admin_headings href='statuscreate.php'>Create Status</a></td></tr>\n";
echo "        <tr><td class=left_rows height=33></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle colspan=2>Miscellaneous</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/clock.png' alt='Modify Time' />
                &nbsp;&nbsp;<a class=admin_headings href='timeadmin.php'>Modify Time</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/application_edit.png' alt='Edit System Settings' />
                &nbsp;&nbsp;<a class=admin_headings href='sysedit.php'>Edit System Settings</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/database_go.png'
                alt='Manage Database' />&nbsp;&nbsp;&nbsp;<a class=admin_headings href='database_management.php'>Manage Database</a></td></tr>\n";
echo "      </table></td>\n";
echo "    <td align=left class=right_main scope=col>\n";
echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
echo "        <tr class=right_main_text>\n";
echo "          <td valign=top>\n";
echo "            <br />\n";

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
echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                  No están permitidos caracteres alfanuméricos, acentos, apostrofes, comas y espacios para crear un grupo.
                  </div>';
}
elseif ($new_password !== $confirm_password) {
$evil_password = '1';
echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                    Las contraseñas no coinciden.
                  </div>';
}

// end post validation //

if (isset($evil_password)){
  echo "<br />\n";
  echo '<div class="row">
      <div id="float_window" class="col-md-10">
        <div class="box box-info"> ';
  echo '<div class="box-header with-border">
                   <h3 class="box-title"><i class="fa fa-lock"></i> Cambiar contraseña de '. $username .'</h3>
                 </div><div class="box-body">';
  echo "            <table class=table>\n";
  echo "            <form name='form' action='$self' method='post'>\n";
  echo "              <tr>
                        <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                          Nombre de usuario:
                        </td>

                        <td style='padding-left:20px;' align=left class=table_rows width=80%>
                          <input type='hidden' name='post_username' value=\"$username\">$username
                        </td>
                      </tr>\n";

  echo "              <tr>
                        <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                          Contraseña nueva
                        </td>

                        <td colspan=2 style='padding-left:20px;'>
                          <input type='password' size='25' maxlength='25' name='new_password'>
                        </td>
                      </tr>\n";

  echo "              <tr>
                        <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                          Confirmar contraseña:
                        </td>

                        <td colspan=2 style='padding-left:20px;'>
                          <input type='password' size='25' maxlength='25'name='confirm_password'>
                        </td>
                      </tr>\n";
  echo "            </table>\n";
  echo "            <input type='hidden' name='get_office' value='$get_office'>\n";
  echo '            <div class="box-footer">
                      <button id="formButtons" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                        <i class="fa fa-ban"></i>
                        <a href="useradmin.php">
                          Cancelar
                        </a>
                      </button>

                      <button id="formButtons" type="submit" name="submit" value="Change Password" class="btn btn-info pull-right">
                        Cambiar contraseña
                        <i classfa fa-save"></i>
                      </button>
                    </div></form>';
  echo '</div></div></div></div>';

  include '../theme/templates/endmaincontent.inc';
 include '../footer.php';
 include '../theme/templates/controlsidebar.inc';
 include '../theme/templates/endmain.inc';
 include '../theme/templates/adminfooterscripts.inc';
exit;

} else {

$new_password = password_hash($new_password, PASSWORD_DEFAULT, ['cost' => 10]);
$confirm_password = password_hash($confirm_password, PASSWORD_DEFAULT, ['cost' => 10]);

$post_username = addslashes($post_username);

$query = "update ".$db_prefix."employees set employee_passwd = ('".$new_password."') where empfullname = ('".$post_username."')";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

$post_username = stripslashes($post_username);

echo '       <div id="float_alert" class="col-md-10"><div class="alert alert-success alert-dismissible">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <h4><i class="icon fa fa-check-circle"></i>¡Contraseña cambiada!</h4>
                La contraseña de '. $post_username .' ha sido cambiada satisfactoriamente.
             </div></div>';
echo         '<div class="row">
                <div id="float_window" class="col-md-10">
                  <div class="box box-info"> ';
echo '              <div class="box-header with-border">
                      <h3 class="box-title"><i class="fa fa-lock"></i> Cambiar contraseña de '. $username .'</h3>
                      </div><div class="box-body">';
echo "                <table class=table>\n";
echo "              <tr>
                      <td class=table_rows_output width=20% height=25 style='padding-left:32px;' nowrap>
                        Nombre de usuario:
                      </td>

                      <td align=left class=table_rows width=80% style='padding-left:20px;'>
                        <input type='hidden' name='post_username' value=\"$post_username\">$post_username
                      </td>
                    </tr>\n";

echo "              <tr>
                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                        Contraseña nueva
                      </td>

                      <td align=left class=table_rows colspan=2 style='padding-left:20px;' width=80%>
                        ***privado***
                      </td>
                    </tr>\n";
echo "            </table>\n";
echo "            <div class='box-footer'>
                    <button id='formButtons' class='btn btn-success pull-right'>
                      <a href='useradmin.php' >
                        Aceptar
                      </a>
                      <i class='fa fa-check'></i>
                    </button>
                  </div>\n";
echo '</div></div></div></div>';
include '../theme/templates/endmaincontent.inc';
include '../footer.php';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';

exit;
}
}
?>
