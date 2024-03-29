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

include '../config.inc.php';
if ($request !== 'POST') {include 'header_get.php';include 'topmain.php'; include 'leftmain.php';}
echo "<title>$title - Buscar Usuario</title>\n";

if (!isset($_SESSION['valid_user'])) {


	echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> WorkTime Control Administration</h4>
                You are not presently logged in, or do not have permission to view this page. Click <a class=admin_headings href="../login.php?login_action=admin"><u>here</u></a> to login.
              </div></div>';


exit;
}

if ($request !== 'POST') {

	/*
echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
echo "  <tr valign=top>\n";
echo "    <td class=left_main width=180 align=left scope=col>\n";
echo "      <table class=hide width=100% border=0 cellpadding=1 cellspacing=0>\n";
echo "        <tr><td class=left_rows height=11></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle>Users</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/user.png' alt='User Summary' />&nbsp;&nbsp;
                <a class=admin_headings href='useradmin.php'>User Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/user_add.png' alt='Create New User' />&nbsp;&nbsp;
                <a class=admin_headings href='usercreate.php'>Create New User</a></td></tr>\n";
echo "        <tr><td class=current_left_rows height=18 align=left valign=middle><img src='../images/icons/magnifier.png' alt='User Search' />&nbsp;&nbsp;
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

	echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info"> ';
    echo '<div class="box-header with-border">
	                 <h3 class="box-title"><i class="fa fa-search-plus"></i> Buscar Usuario</h3>
	               </div><div class="box-body">';

echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table align=center class=table>\n";
echo "              <tr>
											<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
												Nombre de usuario:
											</td>

											<td colspan=2 width=80% style='padding-left:20px;'>
												<input type='text' size='25' maxlength='50' name='post_username' onFocus=\"javascript:form.display_name.disabled=true;form.email_addy.disabled=true;
                      			form.display_name.style.background='#eeeeee';form.email_addy.style.background='#eeeeee';\" >
											</td>
										</tr>\n";

echo "              <tr>
											<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
												Nombre de acceso:
											</td>

											<td colspan=2 width=80% style='padding-left:20px;'>
												<input type='text' size='25' maxlength='50' name='display_name' onFocus=\"javascript:form.post_username.disabled=true;form.email_addy.disabled=true;
                      			form.post_username.style.background='#eeeeee';form.email_addy.style.background='#eeeeee';\">
											</td>
										</tr>\n";

echo "              <tr>
											<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
												Dirección de Email:
											</td>

											<td colspan=2 width=80% style='padding-left:20px;'>
												<input type='text' size='25' maxlength='75' name='email_addy' onFocus=\"javascript:form.post_username.disabled=true;form.display_name.disabled=true;
                      			form.post_username.style.background='#eeeeee';form.display_name.style.background='#eeeeee';\">
											</td>
										</tr>\n";

echo "              <tr>
											<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
												Oficina:
											</td>

											<td colspan=2 width=80% style='padding-left:20px;'>
                      	<select name='office_name' onchange='group_names();'>\n";
echo "                      </select>
											</td>
										</tr>\n";

echo "              <tr>
											<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
												Grupo de trabajo:
											</td>

											<td colspan=2 width=80% style='padding-left:20px;'>
                      	<select name='group_name'>\n";
echo "                      </select>
											</td>
										</tr>\n";
echo "            </table>\n";

		      echo '<div class="box-footer">
											<button type="button" onclick="location=\'useradmin.php\'" id="formButtons" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
												<i class="fa fa-ban"></i>
												Cancelar
											</button>

											<button id="formButtons" type="submit" name="submit" value="Search User" class="btn btn-info pull-right">
												<i class="fa fa-search"></i>
												Buscar
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

include 'header_post.php';include 'topmain.php'; include 'leftmain.php';

@$post_username = stripslashes($_POST['post_username']);
@$display_name = stripslashes($_POST['display_name']);
@$email_addy = $_POST['email_addy'];
@$office_name = $_POST['office_name'];
@$group_name = $_POST['group_name'];

//$post_username = addslashes($post_username);
//$display_name = addslashes($display_name);
//$office_name = addslashes($office_name);
//$group_name = addslashes($group_name);

// begin post validation //

// if ((!eregi ("^([[:alnum:]]| |-|'|,)+$", $post_username)) || (!eregi ("^([[:alnum:]]| |-|'|,)+$", $display_name)) ||
// (!eregi ("^([[:alnum:]]|_|\.|-|@)+$", $email_addy))) {

if ((!preg_match('/' . "^([[:alnum:]]| |-|'|,)+$" . '/i', $post_username)) || (!preg_match('/' . "^([[:alnum:]]| |-|'|,)+$" . '/i', $display_name)) ||
	    (!preg_match('/' . "^([[:alnum:]]|_|.|-|@)+$" . '/i', $email_addy))) {


	/*
echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
echo "  <tr valign=top>\n";
echo "    <td class=left_main width=180 align=left scope=col>\n";
echo "      <table class=hide width=100% border=0 cellpadding=1 cellspacing=0>\n";
echo "        <tr><td class=left_rows height=11></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle>Users</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/user.png' alt='User Summary' />&nbsp;&nbsp;
                <a class=admin_headings href='useradmin.php'>User Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/user_add.png' alt='Create New User' />&nbsp;&nbsp;
                <a class=admin_headings href='usercreate.php'>Create New User</a></td></tr>\n";
echo "        <tr><td class=current_left_rows height=18 align=left valign=middle><img src='../images/icons/magnifier.png' alt='User Search' />&nbsp;&nbsp;
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

	*/
// if (!eregi ("^([[:alnum:]]| |-|'|,)+$", $post_username)) {
if (!preg_match('/' . "^([[:alnum:]]| |-|'|,)+$" . '/i', $post_username)) {
if ($post_username == "") {} else {

	echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
								Se permiten caracteres alfanuméricos, guiones, apóstrofes, comas y espacios al buscar un nombre de usuario.
              </div></div>';

$evil_input = "1";
}}
//if (!eregi ("^([[:alnum:]]| |-|'|,)+$", $display_name)) {
if (!preg_match('/^([[:alnum:]]|\s|\-|\'|\,)+$/i', $display_name)) {
if ($display_name == "") {} else {

	echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Se permiten caracteres alfanuméricos, guiones, apóstrofes, comas y espacios al buscar un nombre de acceso.
              </div></div>';

$evil_input = "1";
}}
// if (!eregi ("^([[:alnum:]]|_|\.|-|@)+$", $email_addy)) {
if (!preg_match('/^([[:alnum:]]|_|.|-|@)+$/', $email_addy)) {
if ($email_addy == "") {} else {

	echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Se permiten caracteres alfanuméricos, guiones, apóstrofes, comas y espacios al buscar una dirección de email.
              </div></div>';
$evil_input = "1";
}}
if (($post_username == "") && ($display_name == "") && ($email_addy == "")) {

	echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Se requiere un nombre de usuario, un nombre acceso, una dirección de email, una oficina o un grupo.
              </div></div>';


$evil_input = "1";
}

if (!empty($office_name)) {
$query = "select * from ".$db_prefix."offices where officename = '".$office_name."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_officename = "".$row['officename']."";
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
if (!isset($tmp_officename)) {echo "Office is not defined.\n"; exit;}
}

if (!empty($group_name)) {
$query = "select * from ".$db_prefix."groups where groupname = '".$group_name."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_groupname = "".$row['groupname']."";
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
if (!isset($tmp_officename)) {echo "Group is not defined.\n"; exit;}
}

// end post validation //

if (isset($evil_input)) {

	echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info"> ';
    echo '<div class="box-header with-border">
	                 <h3 class="box-title"><i class="fa fa-search-plus"></i> Buscar Usuario</h3>
	               </div><div class="box-body">';

echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table align=center class=table>\n";
echo "              <tr>
											<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
												Nombre de usuario:
											</td>

											<td colspan=2 width=80% style='padding-left:20px;'>
												<input type='text' size='25' maxlength='50' name='post_username' value='$post_username' onFocus=\"javascript:form.display_name.disabled=true;form.email_addy.disabled=true;
                      			form.display_name.style.background='#eeeeee';form.email_addy.style.background='#eeeeee';\">
											</td>
										</tr>\n";

echo "              <tr>
											<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
												Nombre de acceso:
											</td>

											<td colspan=2 width=80% style='padding-left:20px;'>
												<input type='text' size='25' maxlength='50' name='display_name' value='$display_name' onFocus=\"javascript:form.post_username.disabled=true;form.email_addy.disabled=true;
                      			form.post_username.style.background='#eeeeee';form.email_addy.style.background='#eeeeee';\">
											</td>
										</tr>\n";

echo "              <tr>
											<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
												Dirección de Email:
											</td>

											<td colspan=2 width=80% style='padding-left:20px;'>
												<input type='text size='25' maxlength='75' name='email_addy' value='$email_addy' onFocus=\"javascript:form.post_username.disabled=true;form.display_name.disabled=true;
                      			form.post_username.style.background='#eeeeee';form.display_name.style.background='#eeeeee';\">
											</td>
										</tr>\n";

echo "              <tr>
											<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
												Oficina:
											</td>

											<td colspan=2 width=80% style='padding-left:20px;'>
                      	<select name='office_name' onchange='group_names();'>\n";
echo "                      </select>
											</td>
										</tr>\n";

echo "              <tr>
											<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
												Grupo de trabajo:
											</td>

											<td colspan=2 width=80% style='padding-left:20px;'>
                      	<select name='group_name' onfocus='group_names();'>
                        	<option selected>$group_name</option>\n";
echo "                      </select>
											</td>
										</tr>\n";
echo "            </table>\n";
				      echo '<div class="box-footer">
																<button type="button" onclick="location=\'useradmin.php\'" id="formButtons" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
																	<i class="fa fa-ban"></i>
																		Cancelar
																</button>

																<button id="formButtons" type="submit" name="submit" value="Search User" class="btn btn-info pull-right">
																	<i class="fa fa-search"></i>
																	Buscar
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

$post_username = addslashes($post_username);
$display_name = addslashes($display_name);
$office_name = addslashes($office_name);
$group_name = addslashes($group_name);

if (!empty($post_username)) {
$tmp_var = $post_username;
$tmp_var2 = "Username";

  if ((!empty($office_name)) && (!empty($group_name))) {
  $query4 = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
            where empfullname LIKE '%".$post_username."%' and office = '".$office_name."' and groups = '".$group_name."'
            order by empfullname";
  $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);
  }
  elseif (!empty($office_name)) {
  $query4 = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
            where empfullname LIKE '%".$post_username."%' and office = '".$office_name."'
            order by empfullname";
  $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);
  }
  elseif (empty($office_name)) {
  $query4 = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
            where empfullname LIKE '%".$post_username."%'
            order by empfullname";
  $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);
  }
}

elseif (!empty($display_name)) {
$tmp_var = $display_name;
$tmp_var2 = "Display Name";

  if ((!empty($office_name)) && (!empty($group_name))) {
  $query4 = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
            where displayname LIKE '%".$display_name."%' and office = '".$office_name."' and groups = '".$group_name."'
            order by empfullname";
  $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);
  }
  elseif (!empty($office_name)) {
  $query4 = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
            where displayname LIKE '%".$display_name."%' and office = '".$office_name."'
            order by empfullname";
  $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);
  }
  elseif (empty($office_name)) {
  $query4 = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
            where displayname LIKE '%".$display_name."%'
            order by empfullname";
  $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);
  }
}

elseif (!empty($email_addy)) {
$tmp_var = $email_addy;
$tmp_var2 = "Email Address";

  if ((!empty($office_name)) && (!empty($group_name))) {
  $query4 = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
            where email LIKE '%".$email_addy."%' and office = '".$office_name."' and groups = '".$group_name."'
            order by empfullname";
  $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);
  }
  elseif (!empty($office_name)) {
  $query4 = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
            where email LIKE '%".$email_addy."%' and office = '".$office_name."'
            order by empfullname";
  $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);
  }
  elseif (empty($office_name)) {
  $query4 = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
            where email LIKE '%".$email_addy."%'
            order by empfullname";
  $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);
  }
}

$tmp_var = stripslashes($tmp_var);
$tmp_var2 = stripslashes($tmp_var2);
$row_count = "0";

while ($row=mysqli_fetch_array($result4)) {

@$user_count_rows = mysqli_num_rows($user_count);
@$admin_count_rows = mysqli_num_rows($admin_count);
@$reports_count_rows = mysqli_num_rows($reports_count);

$row_count++;

if ($row_count == "1") {

	echo '<div class="row">
				<div id="float_window" class="col-md-10">
					<div class="box box-info"> ';
		echo '<div class="box-header with-border">
									 <h3 class="box-title"><i class="fa fa-search-plus"></i> Buscar Usuario</h3>
								 </div><div class="box-body">';

echo "            <table class='table'>
											<td>
												Resultados de la búsqueda de \"$tmp_var\" in $tmp_var2
											</td>
										</tr>\n";
echo "            </table>\n";

echo "            <table class=table>\n";
echo "              <tr>\n";
echo "                <th>&nbsp;</th>\n";
echo "                <th>Usuario</th>\n";
echo "                <th>Acceso</th>\n";
echo "                <th>Oficina</th>\n";
echo "                <th>Grupo</th>\n";
echo "                <th>Deshabilitada</th>\n";
echo "                <th>Admin</th>\n";
echo "                <th>A.Tiempos</th>\n";
echo "                <th>Reportador</th>\n";
echo "                <th>Editar</th>\n";
echo "                <th>Contraseña</th>\n";
echo "                <th>Eliminar</th>\n";
echo "              </tr>\n";

}

$row_color = ($row_count % 2) ? $color2 : $color1;
$empfullname = stripslashes("".$row['empfullname']."");
$displayname = stripslashes("".$row['displayname']."");

echo "              <tr><td>&nbsp;$row_count</td>\n";
echo "                <td>&nbsp;<a class=footer_links title=\"Edit User: $empfullname\"
                    href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">$empfullname</a></td>\n";
echo "                <td>$displayname</td>\n";
// echo "                <td>".$row["email"]."</td>\n";
echo "                <td>".$row['office']."</td>\n";
echo "                <td>".$row['groups']."</td>\n";

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


echo "              <td align='center'>
                    	<a title=\"Editar usuario: $empfullname\"
												href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">
                    		<i class='glyphicon glyphicon-pencil'></i>
											</a>
										</td>\n";
echo "              <td align='center'>
                    	<a title=\"Cambiar contraseña: $empfullname\"
                    		href=\"chngpasswd.php?username=$empfullname&officename=".$row["office"]."\">
                    		<i class='fa fa-lock text-yellow'></i>
											</a>
										</td>\n";
echo "              <td align='center'>
                    	<a title=\"Eliminar usuario: $empfullname\"
												href=\"userdelete.php?username=$empfullname&officename=".$row["office"]."\">
                    		<i class='glyphicon glyphicon-minus-sign text-red'></i>
											</a>
										</td>\n";
echo "              </tr>\n";
}
((mysqli_free_result($result4) || (is_object($result4) && (get_class($result4) == "mysqli_result"))) ? true : false);

if ($row_count == "0") {

	$post_username = stripslashes($post_username);

	echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
	            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	            <h4><i class="icon fa fa-warning"></i> Alert!</h4>
		            No se ha encontrado ningún usuario. Por fovor, intentelo de nuevo.
	          </div></div>';


	echo '<div class="row">
	    <div id="float_window" class="col-md-10">
	      <div class="box box-info"> ';
	echo '<div class="box-header with-border">
	                 <h3 class="box-title"><i class="fa fa-search-plus"></i> Buscar Usuario</h3>
	               </div><div class="box-body">';
	echo "            <form name='form' action='$self' method='post'>\n";
	echo "            <table align=center class=table>\n";
	echo "              <tr>
												<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
													Nombre de usuario:
												</td>

												<td colspan=2 width=80% style='padding-left:20px;'>
													<input type='text' size='25' maxlength='50' name='post_username'
	                      			onFocus=\"javascript:form.display_name.disabled=true;form.email_addy.disabled=true;
	                      			form.display_name.style.background='#eeeeee';form.email_addy.style.background='#eeeeee';\">
												</td>
											</tr>\n";

	echo "              <tr>
												<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
													Nombre de acceso:
												</td>

												<td colspan=2 width=80% style='padding-left:20px;'>
													<input type='text' size='25' maxlength='50' name='display_name'
	                      			onFocus=\"javascript:form.post_username.disabled=true;form.email_addy.disabled=true;
	                      			form.post_username.style.background='#eeeeee';form.email_addy.style.background='#eeeeee';\">
												</td>
											</tr>\n";

	echo "              <tr>
												<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
													Dirección de Email:
												</td>

												<td colspan=2 width=80% style='padding-left:20px;'>
													<input type='text size='25' maxlength='75' name='email_addy'
													 		onFocus=\"javascript:form.post_username.disabled=true;form.display_name.disabled=true;
	                      			form.post_username.style.background='#eeeeee';form.display_name.style.background='#eeeeee';\">
												</td>
											</tr>\n";

	echo "              <tr>
												<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
													Oficina:
												</td>

												<td colspan=2 width=80% style='padding-left:20px;'>
	                      	<select name='office_name' onchange='group_names();'>\n";
	echo "                      </select>
												</td>
											</tr>\n";

	echo "              <tr>
												<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
													Grupo de trabajo:
												</td>

												<td colspan=2 width=80% style='padding-left:20px;'>
	                      	<select name='group_name' onfocus='group_names();'>
	                        	<option selected>$group_name</option>\n";
	echo "                      </select>
												</td>
											</tr>\n";
echo "            	</table>\n";
	echo '<div class="box-footer">
									<button type="button" onclick="location=\'useradmin.php\'" id="formButtons" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
										<i class="fa fa-ban"></i>
										Cancelar
									</button>

									<button id="formButtons" type="submit" name="submit" value="Search User" class="btn btn-info pull-right">
										<i class="fa fa-search"></i>
										Buscar
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

echo "            </table>\n";
echo '</div></div></div></div>';
include '../theme/templates/endmaincontent.inc';
include '../footer.php';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
 exit;
}}}}
?>
