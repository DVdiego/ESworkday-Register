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

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

include '../config.inc.php';
if ($request !== 'POST') {include 'header_get.php';include 'topmain.php';include 'leftmain.php';}
echo "<title>$title - Delete Group</title>\n";
/*FLAG*/
//se puede mejorar el mensaje de aviso inferior.
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

    if ((!isset($_GET['groupname'])) && (!isset($_GET['officename']))) {

    echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
    echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Error!</td></tr>\n";
    echo "  <tr class=right_main_text>\n";
    echo "    <td align=center valign=top scope=row>\n";
    echo "      <table width=300 border=0 cellpadding=5 cellspacing=0>\n";
    echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
    echo "        <tr class=right_main_text><td align=center>Go back to the <a class=admin_headings href='groupadmin.php'>Group Summary</a> page to edit groups.
                </td></tr>\n";
    echo "      </table><br />
              </td>
            </tr>
          </table>\n"; exit;
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

    if ($user_cnt > 0) {
      echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
      echo "              <tr>\n";
        if ($user_cnt == 1) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-info"></i>¡Cuidado!</h4>
                   El grupo contiene' . $user_cnt . ' usuario. El usuario debe de ser movido a otro grupo para
                   poder eliminar el grupo.
                </div></div></tr>';

        } else {
          echo ' <div id="float_window" class="col-md-10"><div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-info"></i>¡Cuidado!</h4>
                       El grupo contiene' . $user_cnt . ' usuarios. Los usuarios deben de ser movidos a otro grupo para
                       poder eliminar el grupo.
                    </div></div></tr>';
        }
      echo "            </table>\n";

    }
    echo '<div class="row">
              <div id="float_window" class="col-md-10">
                  <div class="box box-info"> ';
    echo '           <div class="box-header with-border">
                       <h3 class="box-title"><i class="fa fa-users"></i> Delete Groups</h3>
                     </div>
                     <div class="box-body">';
    echo "              <table class=table>\n";
    echo "              <form name='form' action='$self' method='post'>\n";
    echo "                <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
    echo "                   <tr><td height=15></td></tr>\n";
    echo "                   <tr>
                              <td class=table_rows height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                                Nombre del grupo:
                              </td>

                              <td align=left width=80% style='font-weight: bold;padding-left:20px;' class=table_rows>
                                <input type='hidden' name='post_groupname' value=\"$groupname\">
                                  $get_group
                              </td>
                            </tr>\n";
    echo "
                            <tr>
                              <td class=table_rows height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                                Oficina:
                              </td>

                              <td align=left width=80% style='padding-left:20px;' class=table_rows width=66%>
                                <input type='hidden' name='post_officename' value=\"$officename\">
                                  $get_office
                              </td>
                            </tr>\n";
    echo "
                            <tr>
                              <td class=table_rows height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                                Nombre del grupo:
                              </td>

                              <td align=left width=80% style='padding-left:20px;' class=table_rows>
                                <input type='hidden' name='user_cnt' value=\"$user_cnt\">
                                  $user_cnt
                              </td>
                            </tr>\n";
    echo "                   <tr><td height=15></td></tr>\n";
    echo "                </table>\n";
    echo "                <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    if ($user_cnt == 0) {
        echo "              <tr><td height=40></td></tr></table>\n";
        echo "              <input type='hidden' name='group_name_no_users'>\n";
        echo "              <input type='hidden' name='office_name_no_users'>\n";
    } elseif ($user_cnt == 1) {
    echo "                  <tr>
                              <td class=table_rows height=53>
                                ¿Oficina para el usuario?&nbsp;&nbsp;&nbsp;\n";

    } else {
    echo "                  <tr>
                              <td class=table_rows height=53>
                                ¿Oficnia para los usuarios?&nbsp;&nbsp;&nbsp;\n";
    }

    if ($user_cnt > '0') {
    echo "                    <select name='office_name' onchange='group_names();'>\n";
    echo "                    </select>&nbsp;&nbsp;&nbsp;¿A qué grupo?\n";
    echo "                    <select name='group_name' onfocus='group_names();'>
                                <option selected></option>\n";
    echo "                    </select></td>
                            </tr>
                          </table>\n";
    }

    echo "                <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "                  <input type='hidden' name='post_officeid' value=\"$officeid\">\n";
    echo "                  <input type='hidden' name='post_groupid' value=\"$groupid\">\n";
    echo "                  <tr>
                              <td width=30>
                                <button id='formButtons' type='submit' name='submit' value='Delete Group' class='btn btn-danger'>
																  Eliminar grupo
															  </button>
                              </td>

                              <td>
                                <button id='formButtons' class='btn btn-default pull-right'>
																 <a href='groupadmin.php'>
																	Cancelar
																 </a>
															  </button>
                              </td>
                            </tr>
                          </table>
                        </form>
                      </table>\n";
    echo '         </div>
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
    /*FLAG*/ // puede haber problema al agregar leftmain cuando request POST
    include 'header_post.php';include 'topmain.php';

    $post_officename = $_POST['post_officename'];
    $post_officeid = $_POST['post_officeid'];
    @$group_name = $_POST['group_name'];
    @$office_name = $_POST['office_name'];
    @$group_name_no_users = $_POST['group_name_no_users'];
    @$office_name_no_users = $_POST['office_name_no_users'];
    $post_groupname = $_POST['post_groupname'];
    $post_groupid = $_POST['post_groupid'];
    $user_cnt = $_POST['user_cnt'];

    // begin post validation //

    if ((!empty($post_officename)) || (!empty($post_officeid)) || ($office_name != 'no_office_users')) {
      $query = "select * from ".$db_prefix."offices where officename = '".$post_officename."' and officeid = '".$post_officeid."'";
      $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
      while ($row=mysqli_fetch_array($result)) {
      $officename = "".$row['officename']."";
      $officeid = "".$row['officeid']."";
      }
      ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
    }
    if ((!isset($officename)) || (!isset($officeid))) {echo "Office name is not defined for this group.\n"; exit;}

    if ((!empty($post_groupname)) || (!empty($post_groupid)) || ($group_name != 'no_group_users')) {
    $query = "select * from ".$db_prefix."groups where groupname = '".$post_groupname."' and groupid = '".$post_groupid."'";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
    while ($row=mysqli_fetch_array($result)) {
    $groupname = "".$row['groupname']."";
    $groupid = "".$row['groupid']."";
    }
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
    }
    if ((!isset($groupname)) || (!isset($groupid))) {echo "Group name is not defined for this group.\n"; exit;}

    if (!empty($office_name)) {
    $query = "select * from ".$db_prefix."offices where officename = '".$office_name."'";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
    while ($row=mysqli_fetch_array($result)) {
    $tmp_officename = "".$row['officename']."";
    $tmp_officeid = "".$row['officeid']."";
    }
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
    if ((!isset($tmp_officename)) || (!isset($tmp_officeid))) {echo "Office name is not defined for this group.\n"; exit;}
    }

    if (!empty($group_name)) {
    $query = "select * from ".$db_prefix."groups where groupname = '".$group_name."'";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
    while ($row=mysqli_fetch_array($result)) {
    $tmp_groupname = "".$row['groupname']."";
    $tmp_groupid = "".$row['groupid']."";
    }
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
    if ((!isset($tmp_groupname)) || (!isset($tmp_groupid))) {echo "Group name is not defined for this group.\n"; exit;}
    }

    if (isset($office_name_no_users)) {
      if (!empty($office_name_no_users)) {echo "Something is fishy here.\n"; exit;}
    }
    if (isset($group_name_no_users)) {
      if (!empty($group_name_no_users)) {echo "Something is fishy here.\n"; exit;}
    }

    $query = "select * from ".$db_prefix."employees where office = '".$post_officename."' and groups = '".$post_groupname."'";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
    @$tmp_user_cnt = mysqli_num_rows($result);

    if ($user_cnt != $tmp_user_cnt) {echo "Posted user count does not equal actual user count for this group.\n"; exit;}

    // end post validation //


    /*FLAG*/ //no entiendo, si lo pongo en otra parte da problemas con las vistas,se descuadra el formculario.
    include 'leftmain.php';
    echo '<div class="row">
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';
    echo '     <div class="box-header with-border">
                       <h3 class="box-title"><i class="fa fa-users"></i> Delete Groups</h3>
               </div>
               <div class="box-body">';
    echo "          <table class=table>\n";
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";

    if (((isset($office_name)) && (empty($office_name))) || ((isset($group_name)) && (empty($group_name))) ||
    (($group_name == $post_groupname) && ($office_name == $post_officename))) {
    } else {
    echo '                <div id="float_window" class="col-md-10"><div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h4><i class="icon fa fa-check-circle"></i>¡Eliminado con éxito!</h4>
                                          El grupo se ha eliminado correctamente.
                                      </div></div>';
    echo "            </tr>
                      </table>\n";
    }

    if (((isset($office_name)) && (empty($office_name))) || ((isset($group_name)) && (empty($group_name)))) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                   Para eliminar el grupo debe elegir otro grupo para mover a los usuaros pertenecientes al grupo a eliminar.
                   El grupo contiene' . $user_cnt . ' usuario. El usuario debe de ser movito a otro grupo para
                   poder eliminar el grupo.
                </div></div></tr>';
    } elseif (($group_name == $post_groupname) && ($office_name == $post_officename)) {
      echo ' <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                   Para eliminar el grupo debe elegir otro grupo para mover a los usuaros pertenecientes al grupo a eliminar.
                   El grupo contiene' . $user_cnt . ' usuario. El usuario debe de ser movito a otro grupo para
                   poder eliminar el grupo.
                </div></div></tr>';
      echo "    </table>\n";
    }

    echo "            <br />\n";
    echo "            <form name='form' action='$self' method='post'>\n";
    echo "              <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
    echo "                <tr><td height=15></td></tr>\n";

    if (((isset($office_name)) && (empty($office_name))) || ((isset($group_name)) && (empty($group_name))) ||
    (($group_name == $post_groupname) && ($office_name == $post_officename))) {

        echo "              <tr>
                              <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>
                                Nombre del grupo:
                              </td>

                              <td align=left width=80% style='font-weight: bold;padding-left:20px;' class=table_rows>
                                <input type='hidden' name='post_groupname' value=\"$post_groupname\">$post_groupname
                              </td>
                            </tr>\n";
        echo "
                            <tr>
                              <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>
                                Oficina:
                              </td>

                              <td align=left width=80% style='font-weight: bold;padding-left:20px;' class=table_rows>
                                <input type='hidden' name='post_officename' value=\"$post_officename\">$post_officename
                              </td>
                            </tr>\n";
        echo "
                            <tr>
                              <td class=table_rows height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                                Número de usuarios:
                              </td>

                              <td align=left width=80% style='padding-left:20px;' class=table_rows>
                                <input type='hidden' name='user_cnt' value=\"$user_cnt\">$user_cnt
                              </td>
                            </tr>\n";
        echo "
                            <tr>
                              <td height=15></td>
                            </tr>\n";
        echo "            </table>\n";
        echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";

        if ($user_cnt == 0) {
            echo "            <tr><td height=40></td>\n";
          } elseif ($user_cnt == 1) {
          echo "              <tr>
                                <td class=table_rows height=53>
                                  ¿Oficina para el usuario?&nbsp;&nbsp;&nbsp;\n";
          } else {
          echo "              <tr>
                                <td class=table_rows height=53>
                                  ¿Oficina para los usuarios?&nbsp;&nbsp;&nbsp;\n";
        }

        if ($user_cnt > '0') {
          echo "                <select name='office_name' onchange='group_names();'>\n";
          echo "                </select>&nbsp;&nbsp;&nbsp;¿A qué grupo?\n";
          echo "                <select name='group_name' onfocus='group_names();'>
                                  <option selected></option>\n";
          echo "                </select></td>
                              </tr>
                          </table>\n";
        }

        echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <input type='hidden' name='post_officeid' value=\"$post_officeid\">\n";
        echo "              <input type='hidden' name='post_groupid' value=\"$post_groupid\">\n";
        echo "              <tr>
                              <td width=30>
                                <button id='formButtons' type='submit' name='submit' value='Delete Group' class='btn btn-danger'>
                                  Eliminar grupo
                                </button>
                              </td>

                              <td>
                                <button id='formButtons' class='btn btn-default pull-right'>
                                  <a href='groupadmin.php'>
                                    Cancelar
                                  </a>
                                </button>
                              </td>
                            </tr>
                          </table>
                      </form>
                      </table>\n";
        echo '
                      </div>
                   </div>
                 </div>
             </div>';
        include '../theme/templates/endmaincontent.inc';
        include '../theme/templates/controlsidebar.inc';
        include '../theme/templates/endmain.inc';
        include '../theme/templates/adminfooterscripts.inc';
        include '../footer.php';exit;
    } else {

      if ($user_cnt > '0') {
        $query4 = "update ".$db_prefix."employees set office = ('".$office_name."'), groups = ('".$group_name."') where office = ('".$post_officename."')
                   and groups = ('".$post_groupname."')";
        $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);
      }

      $query5 = "delete from ".$db_prefix."groups where groupid = '".$post_groupid."'";
      $result5 = mysqli_query($GLOBALS["___mysqli_ston"], $query5);

      echo "              <tr>
                            <td class=table_rows height=25 width=20% style='padding-left:32px;font-weight: bold;' nowrap>
                              Nombre del grupo:
                            </td>

                            <td align=left width=80% style='padding-left:20px;font-weight: bold;' class=table_rows>
                              $post_groupname
                            </td>
                          </tr>\n";
      echo "
                          <tr>
                            <td class=table_rows height=25 width=20% style='font-weight: bold;padding-left:32px;' nowrap>
                              Oficina:
                            </td>

                            <td align=left width=80% style='font-weight: bold;padding-left:20px;' class=table_rows>
                              $post_officename
                            </td>
                          </tr>\n";
      echo "
                          <tr>
                            <td class=table_rows height=25 width=20% style='padding-left:32px;font-weight: bold;' nowrap>
                              Número de usuarios:
                            </td>

                            <td align=left width=80% style='padding-left:20px;' class=table_rows>
                              $user_cnt
                            </td>
                          </tr>\n";
      echo "              <tr><td height=15></td></tr>\n";
      echo "            </table>\n";
      echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
      echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
      echo "              <tr>
                            <td>
                              <button id='formButtons' class='btn btn-success'>
    													  <a href='groupadmin.php' style='font-weight: bold;color: white;' >
    														 Aceptar
    													  </a>
    												  </button>
                            </td>
                          </tr>
                        </table>
                    </form>
                    </table>\n";
      echo '
                    </div>
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
