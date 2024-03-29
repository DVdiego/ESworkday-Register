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
echo "<title>$title - Edit Office</title>\n";

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
include 'leftmain.php'; //esta despues de verficar la sesión para que no cargue el menú lateral sino esta autendicado.

if ($request == 'GET') {

  if (!isset($_GET['officename'])) {

  echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
  echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Error!</td></tr>\n";
  echo "  <tr class=right_main_text>\n";
  echo "    <td align=center valign=top scope=row>\n";
  echo "      <table width=300 border=0 cellpadding=5 cellspacing=0>\n";
  echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
  echo "        <tr class=right_main_text><td align=center>Go back to the <a class=admin_headings href='officeadmin.php'>Office Summary</a> page to edit
              offices.</td></tr>\n";
  echo "      </table><br /></td></tr></table>\n"; exit;
  }

  $get_office = $_GET['officename'];

  $query = "select * from ".$db_prefix."offices where officename = '".$get_office."'";
  $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

  while ($row=mysqli_fetch_array($result)) {

    $officename = "".$row['officename']."";
    $officeid = "".$row['officeid']."";
  }

  if (!isset($officename)) {echo "Office name is not defined.\n"; exit;}
  if (!isset($officeid)) {echo "Office name is not defined.\n"; exit;}

  $query2 = "select * from ".$db_prefix."employees where office = '".$get_office."'";
  $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
  @$user_cnt = mysqli_num_rows($result2);

  $query3 = "select * from ".$db_prefix."groups where officeid = '".$officeid."'";
  $result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);
  @$group_cnt = mysqli_num_rows($result3);


  echo '<div class="row">
          <div id="float_window" class="col-md-10">
            <div class="box box-info"> ';
  echo '      <div class="box-header with-border">
                   <h3 class="box-title"><i class="fa fa-suitcase"></i> Editar Oficina ' . $get_office . '</h3>
              </div>
              <div class="box-body">';
  echo "         <form name='form' action='$self' method='post'>\n";
  echo "            <table class=table_hover>\n";
  echo "              <tr>\n";
  echo "                <th class=rightside_heading nowrap halign=left colspan=3>";
  echo "              </tr>\n";
  echo "              <tr><td height=15></td></tr>\n";
  echo "              <tr>
                        <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                          &nbsp;*Nombre de la oficina:
                        </td>

                        <td align=left class='table_rows' width=80% style='font-family:Tahoma;font-size:14px;padding-left:20px;'>
                          <input type='text' size='25' maxlength='50' name='post_officename' value='$officename'>
                        </td>
                      </tr>\n";
  echo "              <tr>
                        <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                          Número de grupos:
                        </td>

                        <td align=left width=80% class=table_rows style='padding-left:20px;'>
                          <input type='hidden' name='group_cnt' value=\"$group_cnt\">$group_cnt
                        </td>
                      </tr>\n";
  echo "              <tr>
                        <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                          Número de usuarios:
                        </td>

                        <td align=left width=80% class=table_rows style='padding-left:20px;'>
                          <input type='hidden' name='user_cnt' value=\"$user_cnt\">$user_cnt
                        </td>
                      </tr>\n";
  echo "              <tr>
												<td class=table_rows align=right colspan=3 style='font-size: 11px;'>*&nbsp;Campos requeridos&nbsp;
												</td>
											</tr>\n";
  echo "            </table>\n";
  echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
  echo "            </table>\n";
  echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
  echo "              <input type='hidden' name='post_officeid' value=\"$officeid\">\n";
  echo "              <input type='hidden' name='get_office' value=\"$get_office\">\n";
  echo "            </table>";
  echo              '<div class="box-footer">
                      <button type="button" id="formButtons" onclick="location=\'officeadmin.php\'" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                        <i class="fa fa-ban"></i>
                        Cancelar
                      </button>

                      <button id="formButtons" type="submit" name="submit" value="Edit Office" class="btn btn-success pull-right">
                        <i class="fa fa-edit"></i>
                        Editar oficina
                      </button>
                    </div>';

  if ($group_cnt == '0') {
    echo "  </form>\n";
  }

  if ($group_cnt != '0') {

    echo "  </form>\n";
    echo "             <br/><br/><hr id='form-padding' class='margin-padding' />\n";
    echo '<div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-group"></i>Grupos de la oficina ' . $get_office . '</h3>
          </div>';
    echo "            <table width=60% align=center height=40 border=0 cellpadding=0 cellspacing=0>\n";

    echo "              <tr>
                          <td height=40 class=table_rows nowrap halign=left>
                            <i class='fa fa-users' />&nbsp;&nbsp;Número de grupos: $group_cnt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td>
                            <i class='fa fa-user' />&nbsp;&nbsp;Número de usuarios: $user_cnt
                          </td>
                        </tr>\n";
    echo "            </table>\n<br />";

    echo "            <table class=table_border width=60% align=center border=0 cellpadding=0 cellspacing=0>\n";

    echo "              <tr>
                          <td class=table_heading nowrap width=5% align=left>&nbsp;</td>\n";
    echo "                <th class=table_heading nowrap width=80% align=left>Nombre</th>\n";
    echo "                <th class=table_heading nowrap width=5% align='center'>Usuarios</th>\n";
    echo "                <th class=table_heading nowrap width=5% align='center'>Editar</th>\n";
    echo "                <th class=table_heading nowrap width=5% align='center'>Eliminar</th>
                        </tr>\n";

    $row_count = 0;

    $query = "select * from ".$db_prefix."groups where officeid = ('".$officeid."') order by groupname";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    while ($row=mysqli_fetch_array($result)) {

      $tmp_group = "".$row['groupname']."";

      $query3 = "select * from ".$db_prefix."employees where office = '".$officename."' and groups = '".$tmp_group."'";
      $result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);
      @$group_user_cnt = mysqli_num_rows($result3);

      $row_count++;
      $row_color = ($row_count % 2) ? $color2 : $color1;

      echo "             <tr class=table_border bgcolor='$row_color'><td class=table_rows width=3%>&nbsp;$row_count</td>\n";
      echo "              <td class=table_rows width=87% align=left>&nbsp;<a class=footer_links title=\"Edit Group: ".$row["groupname"]."\"
                           href=\"groupedit.php?groupname=".$row["groupname"]."&officename=$get_office\">$tmp_group</a></td>\n";
      echo "              <td class=table_rows width=4% align=center><input type='hidden' name='group_user_cnt'
                           value=\"$group_user_cnt\">$group_user_cnt</td>\n";

      if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

      echo "              <td class=table_rows width=3% align=center><a style='color:#27408b;text-decoration:underline;'
                           title=\"Edit Group: ".$row["groupname"]."\" href=\"groupedit.php?groupname=$tmp_group&officename=$get_office\" >
                           Edit</a></td>\n";
      echo "              <td class=table_rows width=3% align=center><a style='color:#27408b;text-decoration:underline;'
                           title=\"Delete Group: ".$row["groupname"]."\" href=\"groupdelete.php?groupname=$tmp_group&officename=$get_office\" >
                           Delete</a></td>
                         </tr>\n";
      } else {
        echo "                  <td>
                                  <button type='button' id='formButtons' style='margin:10px 10px 10px 10px;' onclick='location=\"groupedit.php?groupname=$tmp_group&officename=$get_office\"' class='btn btn-info'>
                                    <i class='fa fa-edit'></i>
                                  </button>
                                </td>\n";
        echo "                  <td>
                                  <button type='button' id='formButtons' style='margin:10px 10px 10px 10px;' onclick='location=\"groupdelete.php?groupname=$tmp_group&officename=$get_office\"' class='btn btn-danger'>
                                    <i class='fa fa-trash'></i>
                                  </button>
                                  </td>
                                </tr>\n";
      }
    }
    echo "            </table>\n";
    echo "            <br/><br/>\n";
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

}elseif ($request == 'POST') {

  $post_officename = $_POST['post_officename'];
  $post_officeid = $_POST['post_officeid'];
  $get_office = $_POST['get_office'];
  $group_cnt = $_POST['group_cnt'];
  $user_cnt = $_POST['user_cnt'];
  @$group_user_cnt = $_POST['group_user_cnt'];

  // begin post validation //

  if (!empty($get_office)) {
    $query = "select * from ".$db_prefix."offices where officename = '".$get_office."'";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
    while ($row=mysqli_fetch_array($result)) {
    $getoffice = "".$row['officename']."";
    }
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
    if (!isset($getoffice)) {echo "Office is not defined.\n"; exit;}
  }

  if (!empty($post_officeid)) {
    $query = "select * from ".$db_prefix."offices where officeid = '".$post_officeid."'";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
    while ($row=mysqli_fetch_array($result)) {
    $post_officeid = "".$row['officeid']."";
    }
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
    if (!isset($post_officeid)) {echo "Office id is not defined.\n"; exit;}
  }

  $query2 = "select office from ".$db_prefix."employees where office = '".$get_office."'";
  $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
  @$tmp_user_cnt = mysqli_num_rows($result2);

  $query3 = "select * from ".$db_prefix."groups where officeid = '".$post_officeid."'";
  $result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);
  @$tmp_group_cnt = mysqli_num_rows($result3);

  if ($user_cnt != $tmp_user_cnt) {echo "Posted user count does not equal actual user count for this office.\n"; exit;}
  if ($group_cnt != $tmp_group_cnt) {echo "Posted group count does not equal actual group count for this office.\n"; exit;}

  // if ((empty($post_officename)) || (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_officename))) {
  if ((empty($post_officename)) || (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_officename))) {
    echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
    echo "        <tr class=right_main_text>\n";
    echo "          <td valign=top>\n";

    if (empty($post_officename)) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                     Se requiere un nombre de oficina.
                  </div></div>';
    }
    // elseif (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_officename)) {
    elseif (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_officename)) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                      No están permitidos caracteres alfanuméricos, acentos, apostrofes, comas y espacios para crear un usuario.
                  </div></div>';
    }
    echo '<div class="row">
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-suitcase"></i> Editar Oficina ' . $get_office . '</h3>
                </div>
                <div class="box-body">';
    echo "        <form name='form' action='$self' method='post'>\n";
    echo "            <table class=table_hover>\n";
    echo "                <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                            &nbsp;*Nombre de la oficina:
                          </td>

                          <td align='left' class='table_rows' width=80%>
                            <input type='text' size='25' maxlength='50' name='post_officename' placeholder='Nombre oficina'>
                          </td>
                        </tr>\n";
    echo "              <tr>
                          <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                            Número de grupos:
                          </td>

                          <td align='left' width=80% style='padding-left:20px;' class='table_rows'>
                            <input type='hidden' name='group_cnt' value=\"$group_cnt\">$group_cnt
                          </td>
                        </tr>\n";
    echo "
                        <tr>
                          <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                            Número de usuarios:
                          </td>

                          <td align='left' style='padding-left:20px;' class='table_rows'>
                            <input type='hidden' name='user_cnt' value=\"$user_cnt\">$user_cnt
                          </td>
                        </tr>\n";
    echo "              <tr>
													<td class=table_rows align=right colspan=3 style='font-size: 11px;'>*&nbsp;Campos requeridos&nbsp;
													</td>
												</tr>\n";
    echo "            </table>\n";

    echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <input type='hidden' name='post_officeid' value=\"$post_officeid\">\n";
    echo "              <input type='hidden' name='get_office' value=\"$get_office\">\n";
    echo "            </table>";
    echo              '<div class="box-footer">
                        <button type="button" id="formButtons" onclick="location=\'officeadmin.php\'" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                          <i class="fa fa-ban"></i>
                          Cancelar
                        </button>

                        <button id="formButtons" type="submit" name="submit" value="Edit Office" class="btn btn-success pull-right">
                          <i class="fa fa-edit"></i>
                          Editar oficina
                        </button>
                      </div>';
    echo '				   </div>
                  	</div>
                  </div>
                </div>';

    if ($group_cnt == '0') {
      echo "</form>\n";
    }

    if ($group_cnt != '0') {

      echo "</form>\n";
      echo "            <br/><br/><hr id='form-padding' class='margin-padding' />\n";
      echo '<div class="box-header with-border">
                       <h3 class="box-title"><i class="fa fa-group"></i>Grupos de la oficina ' . $get_office . '</h3>
            </div>';
      echo "            <table width=60% align=center height=40 border=0 cellpadding=0 cellspacing=0>\n";
      echo "              <tr>
                            <td height=40 class=table_rows nowrap halign=left>
                              <i class='fa fa-users'/>&nbsp;&nbsp;Número de grupos: $group_cnt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <i class='fa fa-user' />&nbsp;&nbsp;Numero de usuarios: $user_cnt
                            </td>
                          </tr>\n";
      echo "            </table>\n";
      echo "            <table class=table_border width=60% align=center border=0 cellpadding=0 cellspacing=0>\n";
      echo "              <tr>\n<br />";

      echo "                <th class=table_heading nowrap width=3% align=left>&nbsp;</th>\n";
      echo "                <th class=table_heading nowrap width=87% align=left>Nombre</th>\n";
      echo "                <th class=table_heading nowrap width=4% align=center>Usuarios</th>\n";
      echo "                <th class=table_heading nowrap width=3% align=center>Editar</th>\n";
      echo "                <th class=table_heading nowrap width=3% align=center>Eliminar</th>\n";
      echo "              </tr>\n";


      $row_count = 0;

      $query = "select * from ".$db_prefix."groups where officeid = ('".$post_officeid."') order by groupname";
      $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      while ($row=mysqli_fetch_array($result)) {

        $tmp_group = "".$row['groupname']."";

        $query3 = "select * from ".$db_prefix."employees where office = '".$get_office."' and groups = '".$tmp_group."'";
        $result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);
        @$group_user_cnt = mysqli_num_rows($result3);

        $row_count++;
        $row_color = ($row_count % 2) ? $color2 : $color1;

        echo "              <tr class=table_border bgcolor='$row_color'><td class=table_rows width=3%>&nbsp;$row_count</td>\n";
        echo "                <td class=table_rows width=87% align=left>&nbsp;<a class=footer_links
                            href=\"groupedit.php?groupname=".$row["groupname"]."&officename=$get_office\">$tmp_group</a></td>\n";
        echo "                <td class=table_rows width=4% align=center>$group_user_cnt</td>\n";

        if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

        echo "                <td class=table_rows width=3% align=center><a style='color:#27408b;text-decoration:underline;'
                            title=\"Edit Group: ".$row["groupname"]."\" href=\"groupedit.php?groupname=$tmp_group&officename=$get_office\" >
                            Edit</a></td>\n";
        echo "                <td class=table_rows width=3% align=center><a style='color:#27408b;text-decoration:underline;'
                            title=\"Delete Group: ".$row["groupname"]."\" href=\"groupdelete.php?groupname=$tmp_group&officename=$get_office\" >
                            Delete</a></td></tr>\n";
        } else {
        echo "                  <td>
                                  <button type='button' id='formButtons' style='margin:10px 10px 10px 10px;' onclick='location=\"groupedit.php?groupname=$tmp_group&officename=$get_office\"' class='btn btn-info'>
                                    <i class='fa fa-edit'></i>
                                  </button>
                                </td>\n";
        echo "                  <td>
                                  <button type='button' id='formButtons' style='margin:10px 10px 10px 10px;' onclick='location=\"groupdelete.php?groupname=$tmp_group&officename=$get_office\"' class='btn btn-danger'>
                                    <i class='fa fa-trash'></i>
                                  </button>
                                  </td>
                                </tr>\n";
      }
      }
      echo "            </table>\n";
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

  } else {

  ///////////////////////////////////////////////////////////////////////////////////////////////

    $officeid_query = "select * from ".$db_prefix."offices where officename = ('".$post_officename."')";
    $officeid_result = mysqli_query($GLOBALS["___mysqli_ston"], $officeid_query);
    while ($row=mysqli_fetch_array($officeid_result)) {
      $post_officeid = "".$row['officeid']."";
    }

    $query4 = "update ".$db_prefix."employees set office = ('".$post_officename."') where office = ('".$get_office."')";
    $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);

    $query5 = "update ".$db_prefix."offices set officename = ('".$post_officename."') where officename = ('".$get_office."')";
    $result5 = mysqli_query($GLOBALS["___mysqli_ston"], $query5);

    echo ' <div id="float_window" class="col-md-10"><div class="alert alert-success alert-dismissible">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                              <h4><i class="icon fa fa-info-circle"></i>¡Éxito al modificar!</h4>
                                Los datos de la oficina ' . $post_officename . ' se han modificado satisfactoriamente.
                            </div></div>';

    echo '<div class="row">
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-suitcase"></i> Editar Oficina</h3>
                </div>';
    echo '         <div class="box-body">';
    echo "         <table class=table_hover>\n";

    echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
    echo "              <tr>
                          <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                            Nombre de la oficina:
                          </td>

                          <td align=left class=table_rows width=80% style='padding-left:20px;'>$post_officename
                          </td>
                        </tr>\n";
    echo "              <tr>
                          <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                            Número de grupos:
                          </td>

                          <td align=left class=table_rows width=80% style='padding-left:20px;'>$group_cnt
                          </td>
                        </tr>\n";

    echo "              <tr>
                          <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                            Número de usuarios:
                          </td>

                          <td align=left class=table_rows width=80% style='padding-left:20px;'>$user_cnt
                          </td>
                        </tr>\n";
    echo "            </table>\n";
    echo '						<div class="box-footer">
													<button id="formButtons" onclick="location=\'officeadmin.php\'" class="btn btn-success pull-right">
															Aceptar
														<i class="fa fa-check"></i>
													</button>
		          				</div>';


    if ($group_cnt == '0') {
      echo "</table>\n";
    }

    if ($group_cnt != '0') {

      echo "</table>\n";
      echo "             <br/><br/>
                          <table>
                            <hr id='form-padding' class='margin-padding' />\n";
      echo '<div class="box-header with-border">
                       <h3 class="box-title"><i class="fa fa-group"></i>Grupos de la oficina ' . $post_officename . '</h3>
            </div>';
      echo "            <table width=60% align=center height=40 border=0 cellpadding=0 cellspacing=0>\n";
      echo "              <tr>
                            <td height=40 class=table_rows nowrap halign=left>
                              <i class='fa fa-users' /> &nbsp;&nbsp;Número de grupos: $group_cnt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <i class='fa fa-user' /> &nbsp;&nbsp;Número de usuarios: $user_cnt
                            </td>
                          </tr>\n";
      echo "            </table>\n <br />";
      echo "            <table class=table_border width=60% align=center border=0 cellpadding=0 cellspacing=0>\n";
      echo "              <tr>\n";
      echo "                <th class=table_heading nowrap width=6% align=left>&nbsp;</th>\n";
      echo "                <th class=table_heading nowrap width=87% align=left>Nombre</th>\n";
      echo "                <th class=table_heading nowrap width=6% align=center>Usuarios</th>\n";
      echo "                <th class=table_heading nowrap width=6% align=center>Editar</th>\n";
      echo "                <th class=table_heading nowrap width=6% align=center>Eliminar</th>\n";
      echo "              </tr>\n";

      $row_count = 0;

      $query = "select * from ".$db_prefix."groups where officeid = ('".$post_officeid."') order by groupname";
      $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      while ($row=mysqli_fetch_array($result)) {

        $tmp_group = "".$row['groupname']."";

        $query3 = "select * from ".$db_prefix."employees where office = '".$post_officename."' and groups = '".$tmp_group."'";
        $result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);
        @$group_user_cnt = mysqli_num_rows($result3);

        $row_count++;
        $row_color = ($row_count % 2) ? $color2 : $color1;

        echo "             <tr class=table_border bgcolor='$row_color'><td class=table_rows width=3%>&nbsp;$row_count</td>\n";
        echo "                <td class=table_rows width=87% align=left>&nbsp;<a class=footer_links
                              href=\"groupedit.php?groupname=".$row["groupname"]."&officename=$post_officename\">$tmp_group</a></td>\n";
        echo "                <td class=table_rows width=4% align=center>$group_user_cnt</td>\n";

        if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

        echo "                <td class=table_rows width=3% align=center><a style='color:#27408b;text-decoration:underline;'
                              title=\"Edit Group: ".$row["groupname"]."\" href=\"groupedit.php?groupname=$tmp_group&officename=$post_officename\" >
                              Edit</a></td>\n";
        echo "                <td class=table_rows width=3% align=center><a style='color:#27408b;text-decoration:underline;'
                              title=\"Delete Group: ".$row["groupname"]."\" href=\"groupdelete.php?groupname=$tmp_group&officename=$post_officename\" >
                              Delete</a></td>
                          </tr>\n";
        } else {
          echo "                  <td>
                                    <button type='button' id='formButtons' style='margin:10px 10px 10px 10px;' onclick='location=\"groupedit.php?groupname=$tmp_group&officename=$get_office\"' class='btn btn-info'>
                                      <i class='fa fa-edit'></i>
                                    </button>
                                  </td>\n";
          echo "                  <td>
                                    <button type='button' id='formButtons' style='margin:10px 10px 10px 10px;' onclick='location=\"groupdelete.php?groupname=$tmp_group&officename=$get_office\"' class='btn btn-danger'>
                                      <i class='fa fa-trash'></i>
                                    </button>
                                    </td>
                                  </tr>\n";
        }
      }
      echo "           </table>\n";
      echo "            <br/><br/>\n";
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
}
?>
