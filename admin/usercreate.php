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

if ($request !== 'POST') {include 'header_get.php';include 'topmain.php';}

if (!isset($_SESSION['valid_user'])) {


	echo ' <div class="col-md-12" style="padding-top: 30px;">
					<div class="alert alert-danger alert-dismissible">
          	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
							&times;
						</button>

						<h4>
							<i class="icon fa fa-warning"></i>
							WorkTime Control Administración
						</h4>

								Actualmente no has iniciado sesión o no tienes permiso para ver esta página. Haga clic <a href="../login.php?login_action=admin"><u>aquí</u></a> para ingresar.
          </div>
				</div>';

 exit;
}




if ($request !== 'POST') {include 'leftmain.php';}
echo "<title>$title - Crear Usuario</title>\n";



$query_users = "select * from ".$db_prefix."employees";
$result_users = mysqli_query($GLOBALS["___mysqli_ston"], $query_users);

if(mysqli_num_rows($result_users)>=$max_users_version+1){
	echo ' <div class="col-md-12" style="padding-top: 30px;">
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
							&times;
						</button>

						<h4>
							<i class="icon fa fa-warning"></i>
							WorkTime Control Administración
						</h4>

								Se ha alcanzado el límite de usuarios permitidos de la licencia contratada "'.strtoupper($version_worktime).'". Si desea agregar más usuarios comuníquese con iSoftSolutions.
					</div>
				</div>';

				include '../theme/templates/endmaincontent.inc';
				include '../footer.php';
				include '../theme/templates/controlsidebar.inc';
				include '../theme/templates/endmain.inc';
				include '../theme/templates/adminfooterscripts.inc';

 exit;



}



if ($request == 'GET') {


			echo '<div class="row">
		        <div id="float_window" class="col-md-10">
		          <div class="box box-info"> ';
		    echo '<div class="box-header with-border">
			                 <h3 class="box-title"><i class="fa fa-user-plus"></i> Crear Usuario</h3>
			               </div><div class="box-body">';

		echo "            <form name='form' action='$self' method='post'>\n";
		echo "            <table align=center class=table>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>Nombre completo:&nbsp;*
													</td>

													<td class=table_rows width=80% style='padding-left:20px;'>
														<input type='text' size='25' maxlength='50' name='post_username' required>
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>Usuario:&nbsp;*
													</td>

													<td class=table_rows width=80% style='padding-left:20px;'>
		                      	<input type='text' size='25' maxlength='50' name='display_name' required>
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>DNI:&nbsp;*
													</td>

													<td class=table_rows width=80% style='padding-left:20px;'>
												 		<input type='text' size='25' maxlength='10' name='user_dni' required>
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>Contraseña:
													</td>

													<td class=table_rows width=80% style='padding-left:20px;'>
														<input type='password' size='25' maxlength='25' name='password' required>
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>Confirmar contraseña:
													</td>

													<td class=table_rows width=80% style='padding-left:20px;'>
		                      	<input type='password' size='25' maxlength='25' name='confirm_password' required>
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>Dirección de email:&nbsp;*
													</td>

													<td class=table_rows width=80% style='padding-left:20px;'>
		                      	<input type='text' size='25' maxlength='75' name='email_addy' required>
													</td>
												</tr>\n";

		$query = "select * from ".$db_prefix."contracts order by type_contracts asc";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>Tipo de contrato:&nbsp;*
													</td>

													<td class=table_rows width=80% style='padding-left:20px;'>
		                      	<select required='true' name='type_contracts'>\n";
		echo "                        <option value=''>Elige uno</option>\n";

		while ($row=mysqli_fetch_array($result)) {
		  echo "                        <option value='" .$row['contractid']. "'>".$row['type_contracts']."</option>\n";
		}
		echo "                </select></td>
		                    </tr>\n";
		((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);




		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														Oficina:&nbsp;*
													</td>

													<td class=table_rows width=80% style='padding-left:20px;'>
		                      	<select name='office_name' onchange='group_names();'>\n";
		echo "                      </select>
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														Grupo de trabajo:&nbsp;*
													</td>

													<td class=table_rows width=80% style='padding-left:20px;'>
		                      <select name='group_name'>\n";
		echo "                      </select>
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														¿Permisos de administrador?
													</td>\n";

		echo "                <td class='table_rows' width=80% style='padding-left:20px;'>
														<input type='radio' name='admin_perms' value='1'>&nbsp;Si
		                    		<input type='radio' name='admin_perms' value='0' checked>&nbsp;No
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														¿Administrador de tiempos?
													</td>\n";

		echo "                <td class='table_rows' width=80% style='padding-left:20px;'>
														<input type='radio' name='time_admin_perms' value='1'>&nbsp;Si
		                    		<input type='radio' name='time_admin_perms' value='0' checked>&nbsp;No
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														¿Permisos de reportador?
													</td>\n";

		echo "                <td class='table_rows' width=80% style='padding-left:20px;'>
														<input type='radio' name='reports_perms' value='1'>&nbsp;Si
		                    		<input type='radio' name='reports_perms' value='0' checked>&nbsp;No
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														¿Cuenta deshabilitada?
													</td>\n";

		echo "                <td class='table_rows' width=80% style='padding-left:20px;'>
														<input type='radio' name='disabled' value='1'>&nbsp;Si
		                    		<input type='radio' name='disabled' value='0' checked>&nbsp;No
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows align=right colspan=3 style='font-size: 11px;'>*&nbsp;Campos requeridos&nbsp;
													</td>
												</tr>\n";
		echo "            </table>\n";
		echo "						<div class='box-footer'>
												<button type='button' id='formButtons' onclick='location=\"useradmin.php\"' class='btn btn-default pull-right' style='margin: 0px 10px 0px 10px;'>
													<i class='fa fa-ban'></i>
													Cancelar
												</button>

												<button id='formButtons' type='submit' name='submit' value='Create User' class='btn btn-success pull-right'>
													<i class='fa fa-plus'></i>
													Crear usuario
												</button>
											</div>";
	  echo "			</div></form>";




						      echo '</div></div></div></div>';
				      include '../theme/templates/endmaincontent.inc';
				      include '../footer.php';
				      include '../theme/templates/controlsidebar.inc';
				      include '../theme/templates/endmain.inc';
				      include '../theme/templates/adminfooterscripts.inc';
}

elseif ($request == 'POST') {

		include 'header_post.php'; include 'topmain.php'; include 'leftmain.php';

		$post_username = stripslashes($_POST['post_username']);
		@$user_dni = stripslashes($_POST['user_dni']);

		$display_name = stripslashes($_POST['display_name']);
		$password = stripslashes($_POST['password']);
		$confirm_password = stripslashes($_POST['confirm_password']);
		$email_addy = stripslashes($_POST['email_addy']);
		$type_contracts = $_POST['type_contracts'];
		$office_name = $_POST['office_name'];
		@$group_name = $_POST['group_name'];
		$admin_perms = $_POST['admin_perms'];
		$reports_perms = $_POST['reports_perms'];
		$time_admin_perms = $_POST['time_admin_perms'];
		$post_disabled = $_POST['disabled'];

		$post_username = addslashes($post_username);
		$display_name = addslashes($display_name);

		$query5 = "select empfullname from ".$db_prefix."employees where empfullname = '".$post_username."' order by empfullname";
		$result5 = mysqli_query($GLOBALS["___mysqli_ston"], $query5);

		while ($row=mysqli_fetch_array($result5)) {
		  $tmp_username = "".$row['empfullname']."";
		}
		((mysqli_free_result($result5) || (is_object($result5) && (get_class($result5) == "mysqli_result"))) ? true : false);

		$post_username = stripslashes($post_username);
		$display_name = stripslashes($display_name);

		$string = strstr($post_username, "\"");
		$string2 = strstr($display_name, "\"");

		if ((@$tmp_username == $post_username) || ($password !== $confirm_password) ||
		    (!preg_match('/' . "^([[:alnum:]]| |-|'|,)+$" . '/i', $post_username)) || (!preg_match('/' . "^([[:alnum:]]|Å|Ä|Ö|å|ä|ö| |-|'|,)+$" . '/i', $display_name)) || (empty($post_username)) ||
		    (empty($display_name)) || (empty($email_addy)) || (empty($office_name)) || (empty($group_name)) ||
		    (!preg_match('/' . "^([[:alnum:]]|~|\!|@|#|\$|%|\^|&|\*|\(|\)|-|\+|`|_|\=|[{]|[}]|\[|\]|\||\:|\<|\>|\.|,|\?)+$" . '/i', $password)) ||
		    (!preg_match('/' . "^([[:alnum:]]|_|\.|-)+@([[:alnum:]]|\.|-)+(\.)([a-z]{2,4})$" . '/i', $email_addy)) || (($admin_perms != '1') && (!empty($admin_perms))) ||
		    (($reports_perms != '1') && (!empty($reports_perms))) || (($time_admin_perms != '1') && (!empty($time_admin_perms))) ||
		    (($post_disabled != '1') && (!empty($post_disabled))) || (!empty($string)) || (!empty($string2))){

				    if (@tmp_username == $post_username) {
				        $tmp_username = stripslashes($tmp_username);
				    }

				// begin post validation //

				if (empty($user_dni)) {


				}
				elseif (empty($display_name)) {

					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                 Se requiere un nombre de usuario.
				              </div></div>';
				}
				elseif (empty($display_name)) {

					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                 Se requiere un nombre de usuario.
				              </div></div>';
				}
				elseif (!empty($string)) {

					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                  El campo nombre completo no puede estar vacío
				              </div></div>';
				}
				elseif (!empty($string2)) {

					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                  El campo nombre de usuario no puede estar vacío
				              </div></div>';
				}
				elseif (empty($email_addy)) {

					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                  Se requiere introducir el email.
				              </div></div>';
				}
				elseif (empty($type_contracts)) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                  Se requiere introducir el tipo de contrato.
				              </div></div>';
				}
				elseif (empty($office_name)) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                  Se requiere introducir una oficina.
				              </div></div>';
				}
				elseif (empty($group_name)) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                  Se requiere introducir un grupo de trabajo.
				              </div></div>';
				}
				elseif (@$tmp_username == $post_username) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                  El usuario ya existe. Introduce otro nombre.
				              </div></div>';



				 } elseif (!preg_match('/' . "^([[:alnum:]]| |-|'|,)+$" . '/i', $post_username)) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
													No están permitidos caracteres alfanuméricos, acentos, apostrofes, comas y espacios para crear un usuario.
				              </div></div>';



				 } elseif (!preg_match('/' . "^([[:alnum:]]|Å|Ä|Ö|å|ä|ö| |-|'|,)+$" . '/i', $display_name)) {
					echo ' <div  id="float_window"class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                	<h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
													No están permitidos caracteres alfanuméricos, acentos, apostrofes, comas y espacios para crear un usuario.
				              </div></div>';
				} elseif (!preg_match('/' . "^([[:alnum:]]|~|\!|@|#|\$|%|\^|&|\*|\(|\)|-|\+|`|_|\=|[{]|[}]|\[|\]|\||\:|\<|\>|\.|,|\?)+$" . '/i', $password)) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                	<h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
													No se permiten comillas, barras o espacios para crear una contraseña.
				              </div></div>';
				} elseif ($password != $confirm_password) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                Las contraseñas no coinciden
				              </div></div>';

				} elseif (!preg_match('/' . "^([[:alnum:]]|_|\.|-)+@([[:alnum:]]|\.|-)+(\.)([a-z]{2,4})$" . '/i', $email_addy)) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
												No están permitidos caracteres alfanumnéricos, barrabajas o guines para crear un Email.
				              </div></div>';
				} elseif (($admin_perms != '1') && (!empty($admin_perms))) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                Elige \"si\" o \"no\" para otorgar permisos de administrador.
				              </div></div>';
				} elseif (($reports_perms != '1') && (!empty($reports_perms))) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                Elige \"si\" o \"no\" para otorgar permisos de reportador.
				              </div></div>';
				} elseif (($time_admin_perms != '1') && (!empty($time_admin_perms))) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                Elige \"si\" o \"no\" àra otorgar persimos para modificar los tiempos.
				              </div></div>';
				} elseif (($post_disabled != '1') && (!empty($post_disabled))) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                 Elige \"si\" o \"no\" para deshabilitar la cuenta.
				              </div></div>';
				}elseif (!empty($type_contracts)) {
					$query = "select * from ".$db_prefix."contracts where type_contracts = '".$type_contracts."'";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
					while ($row=mysqli_fetch_array($result)) {
						$tmp_type_contracts = "".$row['type_contracts']."";
					}
					((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
					if (!isset($tmp_type_contracts)) {
						echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
					                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
					                 	Se requiere introducir el tipo de contrato del empleado.
					              </div></div>';
					}
				}
				/*
				if (!empty($user_dni)) {
					$query = "select * from". $db_prefix. "contracts where type_contract = '". $user_dni. "'";
					$result = mysqli_query($GLOBALS["––___mysqli_ston"], $query);
					while ($row = mysqli_fetch_array[$result]) {
						$tmp_type_contracts = "".$row['type_contract']."";
					}
				}
				*/
				elseif (!empty($office_name)) {
				$query = "select * from ".$db_prefix."offices where officename = '".$office_name."'";
				$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
				while ($row=mysqli_fetch_array($result)) {
				$tmp_officename = "".$row['officename']."";
				}
				((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
				if (!isset($tmp_officename)) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                 	Se requiere introducir el nombre de la oficina.
				              </div></div>';
				}
				}

				elseif (!empty($group_name)) {
				$query = "select * from ".$db_prefix."groups where groupname = '".$group_name."'";
				$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
				while ($row=mysqli_fetch_array($result)) {
				$tmp_groupname = "".$row['groupname']."";
				}
				((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
				if (!isset($tmp_groupname)) {
					echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
				                 	Se requiere introducir el nombre del grupo.
				              </div></div>';
				}
				}

				// end post validation //

				if (!empty($string)) {$post_username = stripslashes($post_username);}
				if (!empty($string2)) {$display_name = stripslashes($display_name);}

				$password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
				$confirm_password = password_hash($confirm_password, PASSWORD_DEFAULT, ['cost' => 10]);



				echo '<div class="row">
				    <div id="float_window" class="col-md-10">
				      <div class="box box-info"> ';
				echo '<div class="box-header with-border">
				                 <h3 class="box-title"><i class="fa fa-user-plus"></i> Crear usuario</h3>
				               </div><div class="box-body">';
				echo "            <form name='form' action='$self' method='post'>\n";
				echo "            <table class=table>\n";
				echo "              <tr>
															<td class=table_rows_output  height=25 width=20% style='padding-left:32px;' nowrap>
																Nombre completo:&nbsp;*
															</td>

															<td class='table_rows' width=80% style='padding-left:20px;'>
				                      	<input type='text' size='25' maxlength='50' name='post_username' value=\"$post_username\">
															</td>
														</tr>\n";
				echo "              <tr>
															<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
																Usuario:&nbsp;*
															</td>

															<td class='table_rows' width=80% style='padding-left:20px;'>
				                      	<input type='text' size='25' maxlength='50' name='display_name' value=\"$display_name\">
															</td>
														</tr>\n";
				echo "              <tr>
															<td class=table_rows_output  height=25 width=20% style='padding-left:32px;' nowrap>
																DNI:&nbsp;*
															</td>

															<td class='table_rows' width=80% style='padding-left:20px;'>
				                      	<input type='text' size='25' maxlength='50' name='user_dni' value=\"$user_dni\">
															</td>
														</tr>\n";


				if (!empty($string)) {$post_username = addslashes($post_username);}
				if (!empty($string2)) {$displayname = addslashes($display_name);}

				echo "              <tr>
															<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
																Contraseña:
															</td>

															<td class='table_rows' width=80% style='padding-left:20px;'>
																<input type='password' size='25' maxlength='25' name='password'>
															</td>
														</tr>\n";
				echo "              <tr>
															<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
																Confirmar contraseña:
															</td>

															<td class='table_rows' width=80% style='padding-left:20px;'>
				                      	<input type='password' size='25' maxlength='25' name='confirm_password'>
															</td>
														</tr>\n";
				echo "              <tr>
															<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
																Dirección de email:&nbsp;*
															</td>

															<td class='table_rows' width=80% style='padding-left:20px;'>
				                      	<input type='text' size='25' maxlength='75' name='email_addy' value=\"$email_addy\">
															</td>
														</tr>\n";

				$query = "select * from ".$db_prefix."contracts order by type_contracts asc";
				$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

				echo "              <tr>
															<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
																Tipo de contrato:&nbsp;*
															</td>

															<td class='table_rows' width=80% style='padding-left:20px;'>
															<select required='true' name='type_contracts'>\n";
				echo "                        <option  value='' >Elige uno</option>\n";

				while ($row=mysqli_fetch_array($result)) {

						if ("".$row['type_contracts']."" == $type_contracts) {
							echo "                  <option selected>".$row['type_contracts']."</option>\n";
						}else {
							echo "                  <option>".$row['type_contracts']."</option>\n";
						}


				}
				echo "                </select></td>
														</tr>\n";
				((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);


				echo "              <tr>
															<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
																Oficina:&nbsp;*
															</td>

															<td class='table_rows' width=80% style='padding-left:20px;'>
				                      	<select name='office_name' onchange='group_names();'>\n";
				echo "                		</select>
															</td>
														</tr>\n";
				echo "              <tr>
															<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
																Grupo de trabajo:&nbsp;*
															</td>

															<td class='table_rows' width=80% style='padding-left:20px;'>
				                      	<select name='group_name' onfocus='group_names();'>
				                        	<option selected>$group_name</option>\n";
				echo "                	</select>
															</td>
														</tr>\n";

				echo "              <tr>
															<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
																¿Permisos de administrador?
															</td>\n";
				if ($admin_perms == "1") {
				echo "                <td class='table_rows' width=80% style='padding-left:20px;'>
																<input type='radio' name='admin_perms' value='1' checked>&nbsp;Si
																<input type='radio' name='admin_perms' value='0'>&nbsp;No
															</td>
														</tr>\n";
				} else {
				echo "                <td class='table_rows' width=80% style='padding-left:20px;'>
																<input type='radio' name='admin_perms' value='1'>&nbsp;Si
				                    		<input type='radio' name='admin_perms' value='0' checked>&nbsp;No
															</td>
														</tr>\n";
				}

				echo "              <tr>
															<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
																¿Administrador de tiempos?
															</td>\n";
				if ($time_admin_perms == "1") {
				echo "                <td class='table_rows' width=80% style='padding-left:20px;'>
																<input type='radio' name='time_admin_perms' value='1' checked>&nbsp;Si
																<input type='radio' name='time_admin_perms' value='0'>&nbsp;No
															</td>
														</tr>\n";
				} else {
				echo "                <td class='table_rows' width=80% style='padding-left:20px;'>
																<input type='radio' name='time_admin_perms' value='1'>&nbsp;Si
				                    		<input type='radio' name='time_admin_perms' value='0' checked>&nbsp;No
															</td>
														</tr>\n";
				}
				echo "              <tr>
															<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
																¿Permisos de reportador?
															</td>\n";
				if ($reports_perms == "1") {
				echo "                <td class='table_rows' width=80% style='padding-left:20px;'>
																<input type='radio' name='reports_perms' value='1' checked>&nbsp;Si
																<input type='radio' name='reports_perms' value='0'>&nbsp;No
															</td>
														</tr>\n";
				} else {
				echo "                <td class='table_rows' width=80% style='padding-left:20px;'>
																<input type='radio' name='reports_perms' value='1'>&nbsp;Si
				                    		<input type='radio' name='reports_perms' value='0' checked>&nbsp;No
															</td>
														</tr>\n";
				}
				echo "              <tr>
															<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
																¿Cuenta deshabilitada?
															</td>\n";
				if ($post_disabled == "1") {
				echo "                <td class='table_rows' width=80% style='padding-left:20px;'>
																<input type='radio' name='disabled' value='1' checked>&nbsp;Si
																<input type='radio' name='disabled' value='0'>&nbsp;No
															</td>
														</tr>\n";
				} else {
				echo "                <td class='table_rows' width=80% style='padding-left:20px;'>
																<input type='radio' name='disabled' value='1'>&nbsp;Si
				                    		<input type='radio' name='disabled' value='0' checked>&nbsp;No
															</td>
														</tr>\n";
				}
				echo "              <tr>
															<td class=table_rows align=right colspan=3 style='font-size: 11px;'>*&nbsp;Campos requeridos&nbsp;
															</td>
														</tr>\n";
				echo "            </table>\n";
								      echo '<div class="box-footer">
																<button type="button" id="formButtons" onclick="location=\'useradmin.php\'" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
																	<i class="fa fa-ban"></i>
																	Cancelar
																</button>

																<button id="formButtons" type="submit" name="submit" value="Create User" class="btn btn-success pull-right">
																	<i class="fa fa-plus"></i>
																	Crear usuario
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

		$post_username = addslashes($post_username);
		$display_name = addslashes($display_name);

		$password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
		$confirm_password = password_hash($confirm_password, PASSWORD_DEFAULT, ['cost' => 10]);

		$query3 = "insert into ".$db_prefix."employees (empfullname, tstamp ,empDNI, displayname, employee_passwd, email, contract ,groups, office, admin, reports, time_admin, profile, disabled)
		           values ('".$post_username."', 0,'".$user_dni."', '".$display_name."', '".$password."', '".$email_addy."', '".$type_contracts."','".$group_name."', '".$office_name."', '".$admin_perms."',
		           '".$reports_perms."', '".$time_admin_perms."', 1, '".$post_disabled."')";
		$result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);

		/*
		echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
		echo "  <tr valign=top>\n";
		echo "    <td class=left_main width=180 align=left scope=col>\n";
		echo "      <table class=hide width=100% border=0 cellpadding=1 cellspacing=0>\n";
		echo "        <tr><td class=left_rows height=11></td></tr>\n";
		echo "        <tr><td class=left_rows_headings height=18 valign=middle>Users</td></tr>\n";
		echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/user.png' alt='User Summary' />&nbsp;&nbsp;
		                <a class=admin_headings href='useradmin.php'>User Summary</a></td></tr>\n";
		echo "        <tr><td class=current_left_rows height=18 align=left valign=middle><img src='../images/icons/user_add.png' alt='Create New User' />
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

		echo '       <div id="float_alert" class="col-md-10"><div class="alert alert-success alert-dismissible">
		             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		             <h4><i class="icon fa fa-check-circle"></i>¡Usuario creado!</h4>
		                El usuario ha sido creado satisfactoriamente
		             </div></div>';
		echo '<div class="row">
		    <div id="float_window" class="col-md-10">
		      <div class="box box-info"> ';
		echo '<div class="box-header with-border">
		                 <h3 class="box-title"><i class="fa fa-user-plus"></i> Crear Usuario.</h3>
		               </div><div class="box-body">';

		echo "            <table class=table>\n";
		$query4 = "select empfullname, empDNI, displayname, email, contract ,groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
			  where empfullname = '".$post_username."'
		          order by empfullname";
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

		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														Nombre completo:
													</td>

													<td class='table_rows' width=80% style='padding-left:20px;'>$username
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														Usuario:
													</td>

													<td class='table_rows' width=80% style='padding-left:20px;'>$displayname
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														DNI:
													</td>

													<td class='table_rows' width=80% style='padding-left:20px;'>$user_dni
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														Contraseña:
													</td>

													<td class='table_rows' width=80% style='padding-left:20px;'>***privado***
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														Dirección de Email:
													</td>

													<td class='table_rows' width=80% style='padding-left:20px;'>$user_email
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														Contrato:
													</td>

													<td class='table_rows' width=80% style='padding-left:20px;'>$user_contract
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														Oficina:
													</td>

													<td class='table_rows' width=80% style='padding-left:20px;'>$office
													</td>
												</tr>\n";
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														Grupo de trabajo:
													</td>

													<td class='table_rows' width=80% style='padding-left:20px;'>$groups
													</td>
												</tr>\n";

		if ($admin == "1") {$admin = "Yes";}
		else {$admin = "No";}
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														¿Permisos de administrador?
													</td>

													<td class='table_rows' width=80% style='padding-left:20px;'>$admin
													</td>
												</tr>\n";
		if ($time_admin == "1") {$time_admin = "Yes";}
		else {$time_admin = "No";}
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														¿Administrador de tiempos?
													</td>

													<td class='table_rows' width=80% style='padding-left:20px;'>$time_admin
													</td>
												</tr>\n";
		if ($reports == "1") {$reports = "Yes";}
		else {$reports = "No";}
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														¿Permisos de reportador?
													</td>

													<td class='table_rows' width=80% style='padding-left:20px;'>$reports
													</td>
												</tr>\n";
		if ($disabled == "1") {$disabled = "Yes";}
		else {$disabled = "No";}
		echo "              <tr>
													<td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
														¿Cuenta deshabilitada?
													</td>

													<td class='table_rows' width=80% style='padding-left:20px;'>$disabled
													</td>
												</tr>\n";
		echo "            </table>\n";
		echo '						<div class="box-footer">
													<button id="formButtons" onclick="location=\'useradmin.php\'" class="btn btn-success pull-right">
															Aceptar
														<i class="fa fa-check"></i>
													</button>
		          				</div>';
		echo '				</div>
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
?>
