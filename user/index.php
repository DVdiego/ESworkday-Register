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

if ($use_reports_password == "yes") {

	if (!isset($_SESSION['valid_profile'])) {

		include 'header.php';
		include 'topmain.php';
		include 'leftmain.php';

	}
}

include 'header.php';

if ($use_reports_password == "yes") {
	include 'topmain.php';
	include 'leftmain.php';
} else {
	include 'topmain.php';
	include 'leftmain.php';
}



echo '<div class="row">
		<div id="float_window" class="col-md-10">
			<div class="box box-info"> ';
echo '<div class="box-header with-border">
								 <h3 class="box-title"><i class="fa fa-user"></i> Perfil de usuario</h3>
							 </div><div class="box-body">';

echo "            <table class=table>\n";

if($login_with_fullname == "yes"){

	$query4 = "select empfullname, empDNI, displayname, email, contract ,groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
			where empfullname = '".$_SESSION['valid_profile']."' order by empfullname";
}elseif ($login_with_displayname == "yes"){

	$query4 = "select empfullname, empDNI, displayname, email, contract ,groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
			where empfullname = '".$_SESSION['valid_profile']."' order by empfullname";
}elseif ($login_with_dni == "yes"){

	$query4 = "select empfullname, empDNI, displayname, email, contract ,groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
			where empfullname = '".$_SESSION['valid_profile']."' order by empfullname";
}

$result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);

while ($row=mysqli_fetch_array($result4)) {

	$username = stripslashes("".$row['empfullname']."");
	$displayname = stripslashes("".$row['displayname']."");
	$user_dni = "".$row['empDNI']."";
	$user_email = "".$row['email']."";
	$user_contract = "".$row['contract']."";
	$office = "".$row['office']."";
	$groups = "".$row['groups']."";
	$admin = "".$row['admin']."";
	$reports = "".$row['reports']."";
	$time_admin = "".$row['time_admin']."";
	$disabled = "".$row['disabled']."";
}
((mysqli_free_result($result4) || (is_object($result4) && (get_class($result4) == "mysqli_result"))) ? true : false);

echo "              <tr><td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>Usuario:</td><td align=left class=table_rows
											colspan=2 width=80% style='padding-left:20px;'>$username</td></tr>\n";
echo "              <tr><td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>Nombre Empleado:</td><td align=left class=table_rows
											colspan=2 width=80% style='padding-left:20px;'>$displayname</td></tr>\n";
echo "              <tr><td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>DNI:</td><td align=left class=table_rows
											colspan=2 width=80% style='padding-left:20px;'>$user_dni</td></tr>\n";
echo "              <tr><td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>Email:</td><td align=left class=table_rows
											colspan=2 width=80% style='padding-left:20px;'>$user_email</td></tr>\n";
echo "              <tr><td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>Contrato:</td><td align=left class=table_rows
											colspan=2 width=80% style='padding-left:20px;'>$user_contract</td></tr>\n";
echo "              <tr><td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>Oficina:</td><td align=left class=table_rows
											colspan=2 width=80% style='padding-left:20px;'>$office</td></tr>\n";
echo "              <tr><td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>Grupo:</td><td align=left class=table_rows
											colspan=2 width=80% style='padding-left:20px;'>$groups</td></tr>\n";
echo "              <tr><td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>Contraseña:</td><td align=left class=table_rows
											colspan=2 width=80% style='padding-left:20px;'>***privado***</td></tr>\n";
echo "            </table>\n";
echo '				</div>
					</div>
				</div>
			</div>';


include '../footer.php';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
?>
