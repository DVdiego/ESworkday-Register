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
echo "<title>$title - Edit Group</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

if (!isset($_SESSION['valid_user'])) {

echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Administration</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table width=300 border=0 cellpadding=5 cellspacing=0>\n";
echo "        <tr class=right_main_text><td align=center>You are currently not logged in.</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=admin'><u>here</u></a> to login.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}
include 'leftmain.php';

if ($request == 'GET') {

    if ((!isset($_GET['groupname'])) && (!isset($_GET['officename']))) {

      echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
      echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Error!</td></tr>\n";
      echo "  <tr class=right_main_text>\n";
      echo "    <td align=center valign=top scope=row>\n";
      echo "      <table width=300 border=0 cellpadding=5 cellspacing=0>\n";
      echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
      echo "        <tr class=right_main_text><td align=center>Go back to the <a class=admin_headings href='groupadmin.php'>Group Summary</a> page to edit groups.
                  </td></tr>\n";
      echo "      </table><br /></td></tr></table>\n"; exit;
    }

    $get_group = $_GET['groupname'];
    $get_office = $_GET['officename'];


    $query = "select * from ".$db_prefix."groups, ".$db_prefix."offices where officename = '".$get_office."' and groupname = '".$get_group."'";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    while ($row=mysqli_fetch_array($result)) {

      $officename = "".$row['officename']."";
      $officeid = "".$row['officeid']."";
      $groupname = "".$row['groupname']."";
      $groupid = "".$row['groupid']."";
    }

    if (!isset($officename)) {echo "Office name is not defined for this group.\n"; exit;}
    if (!isset($groupname)) {echo "Group name is not defined for this group.\n"; exit;}

    $query2 = "select * from ".$db_prefix."employees where office = '".$get_office."' and groups = '".$get_group."'";
    $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
    @$user_cnt = mysqli_num_rows($result2);



    echo '<div class="row">
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-users"></i> Editar Grupo ' . $get_group . ' </h3>
                </div>
                <div class="box-body">';
    echo "            <form name='form' action='$self' method='post'>\n";
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
    echo "              <tr><td height=15></td></tr>\n";
    echo "              <tr>
                          <td class=table_rows height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                            &nbsp;*Nombre del grupo:
                          </td>

                          <td colspan=2 width=80% style='padding-left:20px;'>
                            <input type='text' size='25' maxlength='50' name='post_groupname' value=\"$get_group\">
                          </td>
                        </tr>\n";

    // query to populate dropdown with office names //

    $query3 = "select * from ".$db_prefix."offices
               order by officename asc";

    $result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);

    echo "              <tr>
                          <td class=table_rows height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                            Oficina a la que pertenece:
                          </td>

                          <td colspan=2 width=80% style='padding-left:20px;'><select name='post_officename'>\n";

    while ($row=mysqli_fetch_array($result3)) {
        if ("".$row['officename']."" == $get_office) {
        echo "                    <option selected>".$row['officename']."</option>\n";
        } else {
        echo "                    <option>".$row['officename']."</option>\n";
        }
    }
    echo "                  </select></td></tr>\n";
    echo "              <tr>
                          <td class=table_rows height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                            Número de usuarios:
                          </td>

                          <td align=left width=80% style='padding-left:20px;' class=table_rows>
                            <input type='hidden' name='user_cnt' value=\"$user_cnt\">$user_cnt
                          </td>
                        </tr>\n";
  echo "              <tr>
												<td class=table_rows align=right colspan=3 style='font-size: 11px;'>*&nbsp;Campos requeridos&nbsp;
												</td>
											</tr>\n";
    echo "            </table>\n";
    echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td height=40></td></tr>\n";
    echo "            </table>\n";
    echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <input type='hidden' name='orig_officeid' value=\"$officeid\">\n";
    echo "              <input type='hidden' name='post_groupid' value=\"$groupid\">\n";
    echo "              <input type='hidden' name='get_group' value=\"$get_group\">\n";
    echo "              <input type='hidden' name='get_office' value=\"$get_office\">\n";
    echo "              <tr>
                          <td>
                          <div class='box-footer'>
                            <button type='button' onClick='location=\"groupadmin.php\"' id='formButtons' class='btn btn-default pull-right' style='margin: 0px 10px 0px 10px;'>
                              <i class='fa fa-ban'></i>
                              Cancelar
                            </button>

                            <button id='formButtons' type='submit' name='submit' value='Edit Group' class='btn btn-success pull-right'>
                              <i class='fa fa-edit'></i>
                              Editar Grupo
                            </button>
                          </div>
                          </td>
                        </tr>
                      </table>
                    </form>\n";

    $user_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where groups = ('".$get_group."') and office = ('".$get_office."')
                               order by empfullname");
    @$user_count_rows = mysqli_num_rows($user_count);

    $admin_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where admin = '1' and groups = ('".$get_group."')
                                and office = ('".$get_office."')");
    @$admin_count_rows = mysqli_num_rows($admin_count);

    $time_admin_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where time_admin = '1' and groups = ('".$get_group."')
                                     and office = ('".$get_office."')");
    @$time_admin_count_rows = mysqli_num_rows($time_admin_count);

    $reports_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where reports = '1' and groups = ('".$get_group."')
                                  and office = ('".$get_office."')");
    @$reports_count_rows = mysqli_num_rows($reports_count);

    if ($user_count_rows > '0') {

      echo "            <br/><br/><hr id='form-padding' class='margin-padding' />\n";
      echo '          <div class="box-body table-responsive no-padding">'; /*FLAG*///
      echo "            <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>\n";
      echo "              <tr>
                            <th class=table_heading_no_color nowrap width=100% halign=left>
                              Miembros del grupo $get_group de la oficina $get_office </th>
                            </tr>\n";
      echo "              <tr>
                            <td height=40 class=table_rows nowrap halign=left>
                              <i class='fa fa-users text-green'></i>
                              &nbsp;&nbsp;Usuarios totales: $user_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <i class='fa fa-user-secret text-orange'></i>
                              &nbsp;&nbsp; Administradores: $admin_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <i class='fa fa-user text-red'></i>
                              &nbsp;&nbsp; Administradores de tiempo: $time_admin_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <i class='fa fa-user text-blue'></i>
                              &nbsp;&nbsp;Reportadores: $reports_count_rows</td></tr>\n";
      echo "            </table>\n";

      echo "            <table class='table table-hover' width=90% align=center border=0 cellpadding=0 cellspacing=0>\n";
      echo "              <tr><th class=table_heading nowrap width=3% align=left>&nbsp;</th>\n";
      echo "                <th class=table_heading nowrap width=23% align=left>Nombre de usuario</th>\n";
      echo "                <th class=table_heading nowrap width=23% align=left>Nombre de acceso</th>\n";
      //echo "                <th class=table_heading nowrap width=28% align=left>Email Address</th>\n";
      echo "                <th class=table_heading width=6% align=center>Deshabilitada</th>\n";
      echo "                <th class=table_heading width=6% align=center>Administrador</th>\n";
      echo "                <th class=table_heading width=6% align=center>A Tiempos</th>\n";
      echo "                <th class=table_heading nowrap width=6% align=center>Reportador</th>\n";
      echo "                <th class=table_heading nowrap width=6% align=center>Editar</th>\n";
      echo "                <th class=table_heading width=6% align=center>Cambiar contraseña</th>\n";
      echo "                <th class=table_heading nowrap width=6% align=center>Eliminar</th>
                          </tr>\n";

      $row_count = 0;

      $query = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
                where groups = ('".$get_group."') and office = ('".$get_office."') order by empfullname";
      $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

      while ($row=mysqli_fetch_array($result)) {

        $empfullname = stripslashes("".$row['empfullname']."");
        $displayname = stripslashes("".$row['displayname']."");

        $row_count++;
        $row_color = ($row_count % 2) ? $color2 : $color1;

        echo "              <tr class=table_border bgcolor='$row_color'><td class=table_rows width=3%>&nbsp;$row_count</td>\n";
        echo "                <td class=table_rows width=23%>&nbsp;<a title=\"Edit User: $empfullname\" class=footer_links
                              href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">$empfullname</a></td>\n";
        echo "                <td class=table_rows width=23%>&nbsp;$displayname</td>\n";
        //echo "                <td class=table_rows width=28%>&nbsp;".$row["email"]."</td>\n";

        if ("".$row["disabled"]."" == 1) {
          echo "              <td align='center'><i class='glyphicon glyphicon-remove text-red'></i></td>\n";
        } else {
          $disabled = "";
          echo "              <td class=table_rows width=3% align=center>".$disabled."</td>\n";
        }
        if ("".$row["admin"]."" == 1) {
          echo "              <td align='center'><i class='glyphicon glyphicon-ok text-green'></i></td>\n";
        } else {
          $admin = "";
          echo "              <td class=table_rows width=3% align=center>".$admin."</td>\n";
        }
        if ("".$row["time_admin"]."" == 1) {
          echo "              <td align='center'><i class='glyphicon glyphicon-ok text-green'></i></td>\n";
        } else {
          $time_admin = "";
          echo "              <td class=table_rows width=3% align=center>".$time_admin."</td>\n";
        }
        if ("".$row["reports"]."" == 1) {
          echo "              <td align='center'><i class='glyphicon glyphicon-ok text-green'></i></td>\n";
        } else {
          $reports = "";
          echo "              <td class=table_rows width=3% align=center>".$reports."</td>\n";
        }

        if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

        echo "                <td class=table_rows width=3% align=center>
                                <a style='color:#27408b;text-decoration:underline;'
                                title=\"Edit User: $empfullname\"
                                href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">Edit</a></td>\n";
        echo "                <td class=table_rows width=3% align=center><a style='color:#27408b;text-decoration:underline;'
                                title=\"Change Password: $empfullname\"
                                href=\"chngpasswd.php?username=$empfullname&officename=".$row["office"]."\">Chg Pwd</a></td>\n";
        echo "                <td class=table_rows width=3% align=center><a style='color:#27408b;text-decoration:underline;'
                                title=\"Delete User: $empfullname\"
                                href=\"userdelete.php?username=$empfullname&officename=".$row["office"]."\">Delete</a></td>
                            </tr>\n";
        } else {
        echo "                <td class=table_rows width=3% align=center>
                                <a title=\"Editar usuario: $empfullname\"
                                  href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">
                                  <i class='glyphicon glyphicon-pencil'></i>
                                </a>
                              </td>\n";
        echo "                <td class=table_rows width=3% align=center>
                                <a title=\"Cambiar contraseña: $empfullname\"
                                  href=\"chngpasswd.php?username=$empfullname&officename=".$row["office"]."\">
                                  <i class='fa fa-lock text-yellow'></i>
                                </a>
                              </td>\n";
        echo "                <td class=table_rows width=3% align=center>
                                <a title=\"Eliminar usuario: $empfullname\"
                                  href=\"userdelete.php?username=$empfullname&officename=".$row["office"]."\">
                                  <i class='glyphicon glyphicon-minus-sign text-red'></i>
                                </a>
                              </td>
                            </tr>\n";
        }
      }
  }
    if ($user_count_rows > '0') {
      echo "            </table>\n";
      echo '          </div>';
      echo '      </div>
                </div>
              </div>
            </div>';
      include '../theme/templates/endmaincontent.inc';
      include '../theme/templates/controlsidebar.inc';
      include '../theme/templates/endmain.inc';
      include '../theme/templates/adminfooterscripts.inc';
      include '../footer.php';exit;
    } elseif ($user_count_rows == '0') {
      echo "            \n";
      echo '        </div>';
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

elseif ($request == 'POST') {

    $post_officename = $_POST['post_officename'];
    @$post_officeid = $_POST['post_officeid'];
    $orig_officeid = $_POST['orig_officeid'];
    $post_groupname = $_POST['post_groupname'];
    @$post_groupid = $_POST['post_groupid'];
    $get_group = $_POST['get_group'];
    $get_office = $_POST['get_office'];
    $user_cnt = $_POST['user_cnt'];
    $post_groupname = stripslashes($post_groupname);
    $post_groupname = addslashes($post_groupname);

    $string = strstr($post_groupname, "\'");

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

    if (!empty($get_group)) {
      $query = "select * from ".$db_prefix."groups where groupname = '".$get_group."'";
      $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
      while ($row=mysqli_fetch_array($result)) {
      $getgroup = "".$row['groupname']."";
      }
      ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
    }
    if (!isset($getgroup)) {echo "Group is not defined for this user. Go back and associate this user with a group.\n"; exit;}

    if (!empty($post_officename)) {
      $query = "select * from ".$db_prefix."offices where officename = '".$post_officename."'";
      $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
      while ($row=mysqli_fetch_array($result)) {
      $officename = "".$row['officename']."";
      $tmp_officeid = "".$row['officeid']."";
      }
      ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
    }
    if (!isset($officename)) {echo "Office name is not defined for this group.\n"; exit;}

    if (!empty($post_officeid)) {
      $query = "select * from ".$db_prefix."offices where officeid = '".$post_officeid."'";
      $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
      while ($row=mysqli_fetch_array($result)) {
        $post_officeid = "".$row['officeid']."";
        $post_officeid = $tmp_officeid;
      }
      ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
      if (!isset($post_officeid)) {
        echo "Office id is not defined for this group.\n";
        exit;}

    } else {
      $post_officeid = $tmp_officeid;
    }

    if (!empty($orig_officeid)) {
      $query = "select * from ".$db_prefix."offices where officeid = '".$orig_officeid."'";
      $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
      while ($row=mysqli_fetch_array($result)) {
      $origofficeid = "".$row['officeid']."";
      }
      ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
    }
    if (!isset($origofficeid)) {echo "Office name is not defined for this group.\n"; exit;}

    if (!empty($post_groupid)) {
      $query = "select * from ".$db_prefix."groups where groupid = '".$post_groupid."'";
      $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
      while ($row=mysqli_fetch_array($result)) {
      $groupid = "".$row['groupid']."";
      }
      ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
    }
    if (!isset($groupid)) {echo "Group id is not defined for this group.\n"; exit;}

    $query = "select * from ".$db_prefix."employees where office = '".$get_office."' and groups = '".$get_group."'";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
    @$tmp_user_cnt = mysqli_num_rows($result);

    if ($user_cnt != $tmp_user_cnt) {echo "Posted user count does not equal actual user count for this group.\n"; exit;}



    if (empty($string)) {

    $query = "select * from ".$db_prefix."groups where groupname = '".$post_groupname."' and officeid = '".$post_officeid."'";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    while ($row=mysqli_fetch_array($result)) {
    $dupe = '1';
      }
    }

    // if ((empty($post_groupname)) || (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_groupname)) || (!empty($string))) {
    if ((empty($post_groupname)) || (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_groupname)) || (!empty($string))) {
    $evil_group = '1';
    }



    // display links in top left of each page //



    // end post validation //

    if ((isset($evil_group)) || (isset($dupe))) {

        if (empty($post_groupname)) {
          echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                            Se requiere un nombre de grupo.
                            </div>';
        }
        // elseif (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_groupname)) {
        elseif (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_groupname)) {
          echo ' <div id="float_alert" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                          No se permiten comillas, barras o espacios para el nombre del grupo.
                      </div></div>';
        }
        elseif (!empty($string)) {
          echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                            No están permitidos caracteres alfanuméricos, acentos, apostrofes, comas y espacios para el nombre de un grupo.
                            </div>';
        }
        elseif (isset($dupe)) {
          echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                            El grupo ya existe. Por favor introduzca otro nombre.
                            </div>';
        }
        if (!empty($string)) {$post_groupname = stripslashes($post_groupname);}

        echo "<br/>";
        echo '<div class="row">
                <div id="float_window" class="col-md-10">
                  <div class="box box-info"> ';
        echo '      <div class="box-header with-border">
                         <h3 class="box-title"><i class="fa fa-users"></i> Editar Grupo ' . $get_group . '</h3>
                    </div>
                    <div class="box-body">';

        echo "         <form name='form' action='$self' method='post'>\n";
        echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
        echo "              <tr><td height=15></td></tr>\n";

        echo "              <tr>
                              <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                                &nbsp;*Nuevo nombre del grupo:
                              </td>

                              <td colspan=2 width=80% style='padding-left:20px;'>
                                <input type='text' size='25' maxlength='50' name='post_groupname' value=\"$get_group\">
                              </td>
                            </tr>\n";

        // query to populate dropdown with office names //

        $query3 = "select * from ".$db_prefix."offices
                   order by officename asc";

        $result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);

        echo "              <tr>
                              <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                                Oficina a la que pertenece:
                              </td>

                              <td colspan=2 width=80% style='padding-left:20px;'><select name='post_officename'>\n";

        while ($row=mysqli_fetch_array($result3)) {
          if ("".$row['officename']."" == $post_officename) {
          $post_officeid = "".$row['officeid']."";
          echo "                    <option selected>".$row['officename']."</option>\n";
          } else {
          echo "                    <option>".$row['officename']."</option>\n";
          }
        }
        echo "                  </select></td></tr>\n";
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
        echo "              <tr><td height=40></td></tr>\n";
        echo "            </table>\n";
        echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <input type='hidden' name='orig_officeid' value=\"$orig_officeid\">\n";
        echo "              <input type='hidden' name='post_officeid' value=\"$post_officeid\">\n";
        echo "              <input type='hidden' name='post_groupid' value=\"$post_groupid\">\n";
        echo "              <input type='hidden' name='get_group' value=\"$get_group\">\n";
        echo "              <input type='hidden' name='get_office' value=\"$get_office\">\n";
        echo "              <input type='hidden' name='user_cnt' value=\"$user_cnt\">\n";
        echo "
                            <tr>
                            <td>
                              <div class='box-footer'>
                                <button type='button' onClick='location=\"groupadmin.php\"' id='formButtons' class='btn btn-default pull-right' style='margin: 0px 10px 0px 10px;'>
                                  <i class='fa fa-ban'></i>
                                  Cancelar
                                </button>

                                <button id='formButtons' type='submit' name='submit' value='Edit Group' class='btn btn-success pull-right'>
                                  <i class='fa fa-edit'></i>
                                  Editar Grupo
                                </button>
                              </div>
                            </td>
                            </tr>
                          </table>
                        </form>\n";

        $user_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where groups = ('".$get_group."') and office = ('".$get_office."')
                                   order by empfullname");
        @$user_count_rows = mysqli_num_rows($user_count);

        $admin_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where admin = '1' and groups = ('".$get_group."')
                                    and office = ('".$get_office."')");
        @$admin_count_rows = mysqli_num_rows($admin_count);

        $time_admin_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where time_admin = '1' and groups = ('".$get_group."')
                                         and office = ('".$get_office."')");
        @$time_admin_count_rows = mysqli_num_rows($time_admin_count);

        $reports_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where reports = '1' and groups = ('".$get_group."')
                                      and office = ('".$get_office."')");
        @$reports_count_rows = mysqli_num_rows($reports_count);

        if ($user_count_rows > '0') {

          echo "            <br/><br/><hr id='form-padding' class='margin-padding' />\n";
          echo '          <div class="box-body table-responsive no-padding">'; /*FLAG*///
          echo "            <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>\n";
          echo "              <tr>
                                <th class=table_heading_no_color nowrap width=100% halign=left>
                                  Miembros del grupo $get_group de la oficina $get_office </th>
                                </tr>\n";
          echo "              <tr>
                                <td height=40 class=table_rows nowrap halign=left>
                                  <i class='fa fa-users text-green'></i>
                                  &nbsp;&nbsp;Usuarios totales: $user_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <i class='fa fa-user-secret text-orange'></i>
                                  &nbsp;&nbsp; Administradores: $admin_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <i class='fa fa-user text-red'></i>
                                  &nbsp;&nbsp; Administradores de tiempo: $time_admin_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <i class='fa fa-user text-blue'></i>
                                  &nbsp;&nbsp;Reportadores: $reports_count_rows</td></tr>\n";
          echo "            </table>\n";

          echo "            <table class='table table-hover' width=90% align=center border=0 cellpadding=0 cellspacing=0>\n";
          echo "              <tr><th class=table_heading nowrap width=3% align=left>&nbsp;</th>\n";
          echo "                <th class=table_heading nowrap width=23% align=left>Nombre de usuario</th>\n";
          echo "                <th class=table_heading nowrap width=23% align=left>Nombre de acceso</th>\n";
          //echo "                <th class=table_heading nowrap width=28% align=left>Email Address</th>\n";
          echo "                <th class=table_heading width=6% align=center>Deshabilitada</th>\n";
          echo "                <th class=table_heading width=6% align=center>Administrador</th>\n";
          echo "                <th class=table_heading width=6% align=center>A Tiempos</th>\n";
          echo "                <th class=table_heading nowrap width=6% align=center>Reportador</th>\n";
          echo "                <th class=table_heading nowrap width=6% align=center>Editar</th>\n";
          echo "                <th class=table_heading width=6% align=center>Cambiar contraseña</th>\n";
          echo "                <th class=table_heading nowrap width=6% align=center>Eliminar</th>
                              </tr>\n";

          $row_count = 0;

          $query = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
                    where groups = ('".$get_group."') and office = ('".$get_office."') order by empfullname";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

          while ($row=mysqli_fetch_array($result)) {

            $empfullname = stripslashes("".$row['empfullname']."");
            $displayname = stripslashes("".$row['displayname']."");

            $row_count++;
            $row_color = ($row_count % 2) ? $color2 : $color1;

            echo "              <tr class=table_border bgcolor='$row_color'><td class=table_rows width=3%>&nbsp;$row_count</td>\n";
            echo "                <td class=table_rows width=23%>&nbsp;<a title=\"Edit User: $empfullname\" class=footer_links
                                  href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">$empfullname</a></td>\n";
            echo "                <td class=table_rows width=23%>&nbsp;$displayname</td>\n";
            //echo "                <td class=table_rows width=28%>&nbsp;".$row["email"]."</td>\n";

            if ("".$row["disabled"]."" == 1) {
              echo "              <td align='center'><i class='glyphicon glyphicon-remove text-red'></i></td>\n";
            } else {
              $disabled = "";
              echo "              <td class=table_rows width=3% align=center>".$disabled."</td>\n";
            }
            if ("".$row["admin"]."" == 1) {
              echo "              <td align='center'><i class='glyphicon glyphicon-ok text-green'></i></td>\n";
            } else {
              $admin = "";
              echo "              <td class=table_rows width=3% align=center>".$admin."</td>\n";
            }
            if ("".$row["time_admin"]."" == 1) {
              echo "              <td align='center'><i class='glyphicon glyphicon-ok text-green'></i></td>\n";
            } else {
              $time_admin = "";
              echo "              <td class=table_rows width=3% align=center>".$time_admin."</td>\n";
            }
            if ("".$row["reports"]."" == 1) {
              echo "              <td align='center'><i class='glyphicon glyphicon-ok text-green'></i></td>\n";
            } else {
              $reports = "";
              echo "              <td class=table_rows width=3% align=center>".$reports."</td>\n";
            }

            if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

            echo "                <td class=table_rows width=3% align=center>
                                    <a style='color:#27408b;text-decoration:underline;'
                                    title=\"Edit User: $empfullname\"
                                    href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">Edit</a></td>\n";
            echo "                <td class=table_rows width=3% align=center><a style='color:#27408b;text-decoration:underline;'
                                    title=\"Change Password: $empfullname\"
                                    href=\"chngpasswd.php?username=$empfullname&officename=".$row["office"]."\">Chg Pwd</a></td>\n";
            echo "                <td class=table_rows width=3% align=center><a style='color:#27408b;text-decoration:underline;'
                                    title=\"Delete User: $empfullname\"
                                    href=\"userdelete.php?username=$empfullname&officename=".$row["office"]."\">Delete</a></td>
                                </tr>\n";
            } else {
            echo "                <td class=table_rows width=3% align=center>
                                    <a title=\"Editar usuario: $empfullname\"
                                      href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">
                                      <i class='glyphicon glyphicon-pencil'></i>
                                    </a>
                                  </td>\n";
            echo "                <td class=table_rows width=3% align=center>
                                    <a title=\"Cambiar contraseña: $empfullname\"
                                      href=\"chngpasswd.php?username=$empfullname&officename=".$row["office"]."\">
                                      <i class='fa fa-lock text-yellow'></i>
                                    </a>
                                  </td>\n";
            echo "                <td class=table_rows width=3% align=center>
                                    <a title=\"Eliminar usuario: $empfullname\"
                                      href=\"userdelete.php?username=$empfullname&officename=".$row["office"]."\">
                                      <i class='glyphicon glyphicon-minus-sign text-red'></i>
                                    </a>
                                  </td>
                                </tr>\n";
            }
          }
        }
        if ($user_count_rows > '0') {
          echo "            </table>\n";
          echo '        </div>';
          echo '      </div>
                    </div>
                  </div>
                </div>';
          include '../theme/templates/endmaincontent.inc';
          include '../theme/templates/controlsidebar.inc';
          include '../theme/templates/endmain.inc';
          include '../theme/templates/adminfooterscripts.inc';
          include '../footer.php';exit;
        } elseif ($user_count_rows == '0') {
          echo "            \n";
          echo '        </div>';
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

    } else {

        $query4 = "update ".$db_prefix."employees set groups = ('".$post_groupname."'), office = ('".$post_officename."')
                   where groups = ('".$get_group."') and office = ('".$get_office."')";
        $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);

        $query5 = "update ".$db_prefix."groups set groupname = ('".$post_groupname."'), officeid = ('".$post_officeid."')
                   where groupname = ('".$get_group."') and officeid = ('".$orig_officeid."')";
        $result5 = mysqli_query($GLOBALS["___mysqli_ston"], $query5);

        echo '<div class="row">';
        echo '                <div id="float_alert" class="col-md-10"><div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h4><i class="icon fa fa-check-circle"></i>Edición con éxito!</h4>
                                              El grupo se ha editado correctamente.
                                          </div></div>';
        echo '        <div id="float_window" class="col-md-10">
                  <div class="box box-info"> ';
        echo '      <div class="box-header with-border">
                         <h3 class="box-title"><i class="fa fa-users"></i> Editar Grupo</h3>
                    </div>
                    <div class="box-body">';
        echo "            <table align=center class=table>\n";
        echo "              <tr><td height=15></td></tr>\n";
        echo "              <tr>
                              <td class=table_rows height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                                Nuevo nombre del grupo:
                              </td>

                              <td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>
                                $post_groupname
                              </td>
                            </tr>\n";
        echo "
                            <tr>
                              <td class=table_rows height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                                Oficina a la que pertenece:
                              </td>

                              <td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>
                                $post_officename
                              </td>
                            </tr>\n";
        echo "
                            <tr>
                              <td class=table_rows height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                                Núumero de usuarios:
                              </td>

                              <td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>
                                $user_cnt
                              </td>
                            </tr>\n";
        echo "              <tr><td height=15></td></tr>\n";
        echo "            </table>\n";
        echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
        echo "              <tr>
                              <td>
                              <div class='box-footer'>
                                <button type='button' onClick='location=\"groupadmin.php\"' id='formButtons' class='btn btn-success pull-right'>
                                  <i class='fa fa-check'></i>
                                  Aceptar
                                </button>
                              </div>
                              </td>
                            </tr>
                          </table>\n";

        $user_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where groups = ('".$post_groupname."') and office = ('".$post_officename."')
                                   order by empfullname");
        @$user_count_rows = mysqli_num_rows($user_count);

        $admin_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where admin = '1' and groups = ('".$post_groupname."') and
                                    office = ('".$post_officename."')");
        @$admin_count_rows = mysqli_num_rows($admin_count);

        $time_admin_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where time_admin = '1' and groups = ('".$post_groupname."') and
                                         office = ('".$post_officename."')");
        @$time_admin_count_rows = mysqli_num_rows($time_admin_count);

        $reports_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where reports = '1' and groups = ('".$post_groupname."') and
                                      office = ('".$post_officename."')");
        @$reports_count_rows = mysqli_num_rows($reports_count);

        if ($user_count_rows > '0') {

          echo "            <br/><br/><hr id='form-padding' class='margin-padding' />\n";
          echo '          <div class="box-body table-responsive no-padding">';
          echo "            <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>\n";
          echo "              <tr><th class=table_heading_no_color nowrap width=100% halign=left>Members of $post_groupname Group in $post_officename
                                Office</th></tr>\n";
          echo "              <tr><td height=40 class=table_rows nowrap halign=left><img src='../images/icons/user_green.png' />&nbsp;&nbsp;Total
                                Users: $user_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='../images/icons/user_orange.png' />&nbsp;&nbsp;
                                Sys Admin Users: $admin_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='../images/icons/user_red.png' />&nbsp;&nbsp;
                                Time Admin Users: $time_admin_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='../images/icons/user_suit.png' />&nbsp;
                                &nbsp;Reports Users: $reports_count_rows</td></tr>\n";
          echo "            </table>\n";
          echo "            <table class='table table-hover' width=90% align=center border=0 cellpadding=0 cellspacing=0>\n";
          echo "              <tr><th class=table_heading nowrap width=3% align=left>&nbsp;</th>\n";
          echo "                <th class=table_heading nowrap width=23% align=left>Username</th>\n";
          echo "                <th class=table_heading nowrap width=23% align=left>Display Name</th>\n";
          //echo "                <th class=table_heading nowrap width=28% align=left>Email Address</th>\n";
          echo "                <th class=table_heading width=3% align=center>Disabled</th>\n";
          echo "                <th class=table_heading width=3% align=center>Sys Admin</th>\n";
          echo "                <th class=table_heading width=3% align=center>Time Admin</th>\n";
          echo "                <th class=table_heading nowrap width=3% align=center>Reports</th>\n";
          echo "                <th class=table_heading nowrap width=3% align=center>Edit</th>\n";
          echo "                <th class=table_heading width=5% align=center>Change Passwd</th>\n";
          echo "                <th class=table_heading nowrap width=3% align=center>Delete</th></tr>\n";

          $row_count = 0;

          $query = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
                    where groups = ('".$post_groupname."') order by empfullname";
          $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

          while ($row=mysqli_fetch_array($result)) {

            $empfullname = stripslashes("".$row['empfullname']."");
            $displayname = stripslashes("".$row['displayname']."");

            $row_count++;
            $row_color = ($row_count % 2) ? $color2 : $color1;

            echo "              <tr class='table table-hover' bgcolor='$row_color'><td class=table_rows width=3%>&nbsp;$row_count</td>\n";
            echo "                <td class=table_rows width=24%>&nbsp;<a class=footer_links title=\"Edit User: $empfullname\"
                                href=\"useredit.php?username=$empfullname\">$empfullname</a></td>\n";
            echo "                <td class=table_rows width=24%>&nbsp;$displayname</td>\n";
            //echo "                <td class=table_rows width=29%>&nbsp;".$row["email"]."</td>\n";

            if ("".$row["disabled"]."" == 1) {
              echo "                <td class=table_rows width=3% align=center><img src='../images/icons/cross.png' /></td>\n";
            } else {
              $disabled = "";
              echo "                <td class=table_rows width=3% align=center>".$disabled."</td>\n";
            }
            if ("".$row["admin"]."" == 1) {
              echo "                <td class=table_rows width=3% align=center><img src='../images/icons/accept.png' /></td>\n";
            } else {
              $admin = "";
              echo "                <td class=table_rows width=3% align=center>".$admin."</td>\n";
            }
            if ("".$row["time_admin"]."" == 1) {
              echo "                <td class=table_rows width=3% align=center><img src='../images/icons/accept.png' /></td>\n";
            } else {
              $time_admin = "";
              echo "                <td class=table_rows width=3% align=center>".$time_admin."</td>\n";
            }
            if ("".$row["reports"]."" == 1) {
              echo "                <td class=table_rows width=3% align=center><img src='../images/icons/accept.png' /></td>\n";
            } else {
              $reports = "";
              echo "                <td class=table_rows width=3% align=center>".$reports."</td>\n";
            }

            if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

            echo "                <td class=table_rows width=3% align=center><a style='color:#27408b;text-decoration:underline;'
                                title=\"Edit User: $empfullname\"
                                href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">Edit</a></td>\n";
            echo "                <td class=table_rows width=3% align=center><a style='color:#27408b;text-decoration:underline;'
                                title=\"Change Password: $empfullname\"
                                href=\"chngpasswd.php?username=$empfullname&officename=".$row["office"]."\">Chg Pwd</a></td>\n";
            echo "                <td class=table_rows width=3% align=center><a style='color:#27408b;text-decoration:underline;'
                                title=\"Delete User: $empfullname\"
                                href=\"userdelete.php?username=$empfullname&officename=".$row["office"]."\">Delete</a></td></tr>\n";
            } else {
            echo "                <td class=table_rows width=3% align=center><a title=\"Edit User: $empfullname\"
                                href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">
                                <img border=0 src='../images/icons/application_edit.png' /></a></td>\n";
            echo "                <td class=table_rows width=3% align=center><a title=\"Change Password: $empfullname\"
                                href=\"chngpasswd.php?username=$empfullname&officename=".$row["office"]."\"><img border=0
                                src='../images/icons/lock_edit.png' /></a></td>\n";
            echo "                <td class=table_rows width=3% align=center><a title=\"Delete User: $empfullname\"
                                href=\"userdelete.php?username=$empfullname&officename=".$row["office"]."\">
                                <img border=0 src='../images/icons/delete.png' /></a></td></tr>\n";
            }
          }
        }
        if ($user_count_rows > '0') {
          echo "            </table>\n";
          echo '        </div>';
          echo '      </div>
                    </div>
                  </div>
                </div>';
          include '../theme/templates/endmaincontent.inc';
          include '../theme/templates/controlsidebar.inc';
          include '../theme/templates/endmain.inc';
          include '../theme/templates/adminfooterscripts.inc';
          include '../footer.php';exit;
        } elseif ($user_count_rows == '0') {
          echo "            \n";
          echo '        </div>';
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
}
?>
