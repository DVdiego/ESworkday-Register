 Eliminar oficina<?php
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


if ($request !== 'POST') {include 'header_get.php';include 'topmain.php'; include 'leftmain.php';}
echo "<title>$title -  Eliminar oficina</title>\n";

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

if (!isset($_GET['officename'])) {

    echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
    echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Error!</td></tr>\n";
    echo "  <tr class=right_main_text>\n";
    echo "    <td align=center valign=top scope=row>\n";
    echo "      <table width=300 border=0 cellpadding=5 cellspacing=0>\n";
    echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
    echo "        <tr class=right_main_text><td align=center>Go back to the <a class=admin_headings href='officeadmin.php'>Office Summary</a> page to edit
                offices.</td></tr>\n";
    echo "      </table><br/>
              </td>
              </tr>
            </table>\n"; exit;
}

$get_office = $_GET['officename'];

$query = "select * from ".$db_prefix."offices where officename = '".$get_office."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {

    $officename = "".$row['officename']."";
    $officeid = "".$row['officeid']."";
}

if (!isset($officename)) {
    echo "Office name is not defined for this group.\n";
    exit;
 }

$query2 = "select office from ".$db_prefix."employees where office = '".$get_office."'";
$result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
@$user_cnt = mysqli_num_rows($result2);

$query3 = "select * from ".$db_prefix."groups where officeid = '".$officeid."'";
$result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);
@$group_cnt = mysqli_num_rows($result3);

if ($user_cnt > 0) {
  echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
  if ($user_cnt == 1) {
  echo ' <div class="col-md-8"><div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                    Esta oficina contiene ' . $user_cnt . ' usuario. El usuario debe de ser movido a otro grupo de otra oficina antes de poder eliminar la oficina.
                </div></div>';
  } else {
  echo ' <div class="col-md-8"><div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                      Esta oficina contiene ' . $user_cnt . ' usuarios. Los usuarioa deben de ser movidos a otro grupo de otra oficina antes de poder eliminar la oficina.
                  </div></div>';
  }
echo "            </table>\n";
echo "            <br />\n";
echo '<div class="row">
          <div class="col-md-8">
              <div class="box box-info"> ';
echo '           <div class="box-header with-border">
                   <h3 class="box-title"><i class="fa fa-suitcase"></i> Eliminar oficina</h3>
                 </div>
                 <div class="box-body">';
echo "              <table class=table>\n";
echo "                <form name='form' action='$self' method='post'>\n";echo "                  </tr>\n";

echo "                  <tr><td height=15></td></tr>\n";
echo "                  <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Nombre de la oficina:</td>
                            <td align=left class=table_rows width=80% style='padding-left:20px;'><input type='hidden' name='post_officename' value=\"$officename\">$get_office</td>
                        </tr>\n";
echo "                  <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Número de grupos:</td><td align=left width=80%
                         style='padding-left:20px;' class=table_rows><input type='hidden' name='group_cnt' value=\"$group_cnt\">$group_cnt</td>
                       </tr>\n";
echo "                  <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Número de usuaros:</td>
                          <td align=left class=table_rows width=80% style='padding-left:20px;'>
                            <input type='hidden' name='user_cnt' value=\"$user_cnt\">$user_cnt</td>
                        </tr>\n";
echo "                <tr><td height=15></td>
                      </tr>\n";
echo "                <input type='hidden' name='post_officeid' value=\"$officeid\">\n";
echo "                <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";

  if ($user_cnt == 1) {
  echo "                <tr><td class=table_rows height=53>Elige la oficina a la que se mueve el usuario&nbsp;&nbsp;&nbsp;&nbsp;\n";
  } else {
  echo "                <tr><td class=table_rows height=53>Elige la oficina a la que se mueven los usuarios&nbsp;&nbsp;&nbsp;&nbsp;\n";
  }

echo "                  <select name='office_name' onchange='group_names();'>
                           <option selected>Choose One</option>\n";
echo "                  </select>&nbsp;&nbsp;&nbsp;¿a qué grupo?\n";
echo "                  <select name='group_name'>\n";
echo "                  </select>
                       </td></tr>
                     </table>\n";

echo "               <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
echo "                 <tr>
                          <td width=30>
                            <button id='formButtons' type='submit' class='btn btn-danger' value='Delete Ofice'>
                              Eliminar
                            </button>
                          </td>

                          <td>
                            <button id='formButtons' class='btn btn-default pull-right'>
                              <a href='officeadmin.php'>
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

} elseif ($user_cnt == '0') {

  echo '<div class="row">
            <div class="col-md-8">
                <div class="box box-info"> ';
  echo '           <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-suitcase"></i> Eliminar oficina</h3>
                   </div>
                   <div class="box-body">';
echo "               <table class=table>\n";
echo "                 <form name='form' action='$self' method='post'>\n";

echo "                  <tr><td height=15></td></tr>\n";
echo "                  <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Nombre de la oficina:</td><td align=left class=table_rows
                        width=80% style='padding-left:20px;'><input type='hidden' name='post_officename' value=\"$officename\">$get_office</td>
                        </tr>\n";
echo "                  <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Número de grupos:</td><td align=left width=80%
                        style='padding-left:20px;'class=table_rows><input type='hidden' name='group_cnt' value=\"$group_cnt\">$group_cnt</td>
                        </tr>\n";
echo "                  <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Número de usuarios:</td><td align=left width=80%
                        style='padding-left:20px;' class=table_rows><input type='hidden' name='user_cnt' value=\"$user_cnt\">$user_cnt</td>
                        </tr>\n";
echo "                  <input type='hidden' name='post_officeid' value=\"$officeid\">\n";
echo "                  <tr><td height=15></td></tr>\n";
echo "                  <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
echo "                    <tr><td height=40>&nbsp;</td></tr>\n";
echo "                    <input type='hidden' name='group_name' value='no_group_users'>\n";
echo "                    <input type='hidden' name='office_name' value='no_office_users'>\n";
echo "                    <tr>
                            <td width=30>
                              <button id='formButtons' type='submit' class='btn btn-danger' value='Delete Office'>
                                Eliminar
                              </button
                              <input type='image' name='submit' value='Delete Office' src='../images/buttons/next_button.png'>
                            </td>

                            <td>
                              <button id='formButtons' class='btn btn-default pull-right'>
                                <a href='officeadmin.php'>
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

} exit;
}

elseif ($request == 'POST') {

include 'header_post.php';include 'topmain.php';

$post_officename = $_POST['post_officename'];
@$office_name = $_POST['office_name'];
@$group_name = $_POST['group_name'];
$post_officeid = $_POST['post_officeid'];
$group_cnt = $_POST['group_cnt'];
$user_cnt = $_POST['user_cnt'];

// begin post validation //

if ((!empty($post_officename)) || (!empty($post_officeid))) {
  $query = "select * from ".$db_prefix."offices where officename = '".$post_officename."' and officeid = '".$post_officeid."'";
  $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row=mysqli_fetch_array($result)) {
  $officename = "".$row['officename']."";
  $officeid = "".$row['officeid']."";
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
}

if ((!isset($officename)) || (!isset($officeid))) {echo "Office name is not defined.\n"; exit;}

if ((!empty($office_name)) && ($office_name != 'no_office_users')) {
  $query = "select * from ".$db_prefix."offices where officename = '".$office_name."'";
  $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row=mysqli_fetch_array($result)) {
  $tmp_officename = "".$row['officename']."";
  $tmp_officeid = "".$row['officeid']."";
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
if ((!isset($tmp_officename)) || (!isset($tmp_officeid))) {echo "Office name is not defined for this group.\n"; exit;}
}

if ((!empty($group_name)) && ($group_name != 'no_group_users')) {
  $query = "select * from ".$db_prefix."groups where groupname = '".$group_name."'";
  $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row=mysqli_fetch_array($result)) {
  $tmp_groupname = "".$row['groupname']."";
  $tmp_groupid = "".$row['groupid']."";
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
if ((!isset($tmp_groupname)) || (!isset($tmp_groupid))) {echo "Office name is not defined for this group.\n"; exit;}
}

$query2 = "select office from ".$db_prefix."employees where office = '".$post_officename."'";
$result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
@$tmp_user_cnt = mysqli_num_rows($result2);

$query3 = "select * from ".$db_prefix."groups where officeid = '".$post_officeid."'";
$result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);
@$tmp_group_cnt = mysqli_num_rows($result3);

if ($user_cnt != $tmp_user_cnt) {echo "Posted user count does not equal actual user count for this office.\n"; exit;}
if ($group_cnt != $tmp_group_cnt) {echo "Posted group count does not equal actual group count for this office.\n"; exit;}

// end post validation //



include 'leftmain.php';
echo '<div class="row">
        <div class="col-md-8">
          <div class="box box-info"> ';
echo '     <div class="box-header with-border">
                   <h3 class="box-title"><i class="fa fa-suitcase"></i> Eliminar oficina</h3>
           </div>
           <div class="box-body">';
echo "        <table class=table>\n";
echo "            <form name='form' action='$self' method='post'>\n";

if ((empty($office_name)) || (empty($group_name))) {
                echo "<table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "  <tr>\n";
                echo "  <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td>\n";
                echo "  <td class=table_rows_red nowrap>To delete this office, you must choose to move its' current users to another
                        office <b>AND</b> group.</td></tr>\n";
                echo "</table>\n";
                echo "  <br />\n";

} elseif ($office_name == $post_officename) {
                echo "<table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "  <tr>\n";
                echo "  <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td>\n";
                echo "  <td class=table_rows_red nowrap>To delete this office, you must choose to move its' current users to a <b>DIFFERENT</b>
                        office and group.</td></tr>\n";
                echo "</table>\n";
                echo "<br />\n";

} else {
                echo "<table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "  <tr>\n";
                echo "  <td class=table_rows width=20 align=center><img src='../images/icons/accept.png' /></td>
                        <td class=table_rows_green>Office deleted successfully.</td></tr>\n";
                echo "</table>\n";
                echo "<br />\n";
}
            echo "    <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";

            echo "      <tr><td height=15></td>
                        </tr>\n";

if ((empty($office_name)) || (empty($group_name)) || ($office_name == $post_officename)) {

              echo "    <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Office Name:</td><td align=left class=table_rows
                          width=80% style='padding-left:20px;'><input type='hidden' name='post_officename'
                          value=\"$post_officename\">$post_officename</td></tr>\n";
              echo "    <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Group Count:</td><td align=left
                          class=table_rows width=80% style='padding-left:20px;'><input type='hidden' name='group_cnt'
                          value=\"$group_cnt\">$group_cnt</td></tr>\n";
              echo "    <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>User Count:</td><td align=left
                          class=table_rows width=80% style='padding-left:20px;'><input type='hidden' name='user_cnt'
                          value=\"$user_cnt\">$user_cnt</td></tr>\n";
              echo "    <tr><td height=15></td></tr>\n";
              echo "    <input type='hidden' name='post_officeid' value=\"$post_officeid\">\n";
              echo "  </table>\n";
              echo "  <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";

  if ($user_cnt == 1) {
                echo "   <tr><td class=table_rows height=53>Move this user to which office?&nbsp;&nbsp;&nbsp;&nbsp;\n";
  } else {
                echo "   <tr><td class=table_rows height=53>Move these users to which office?&nbsp;&nbsp;&nbsp;&nbsp;\n";
  }

              echo "      select name='office_name' onchange='group_names();'>
                           <option selected>Choose One</option>\n";
              echo "      </select>&nbsp;&nbsp;&nbsp;Which Group?\n";
              echo "      <select name='group_name' onfocus='group_names();'>\n";
              echo "      </select>
                         </td></tr>
                       </table>\n";

              echo "   <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
              echo "          <tr><td width=30><input type='image' name='submit' value='Delete Office'
                                src='../images/buttons/next_button.png'></td><td><a href='officeadmin.php'>
                                <img src='../images/buttons/cancel_button.png' border='0'></td></tr>
                        </table>
                    </form>\n";
  echo '          </table>
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

  if ($user_cnt > 0) {
  $query4 = "update ".$db_prefix."employees set office = ('".$office_name."'), groups = ('".$group_name."') where office = ('".$post_officename."')";
  $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);
  }

  $query5 = "delete from ".$db_prefix."offices where officeid = '".$post_officeid."'";
  $result5 = mysqli_query($GLOBALS["___mysqli_ston"], $query5);

  $query6 = "delete from ".$db_prefix."groups where officeid = '".$post_officeid."'";
  $result6 = mysqli_query($GLOBALS["___mysqli_ston"], $query6);

  echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Office Name:</td><td align=left class=table_rows
                        width=80% style='padding-left:20px;'>$post_officename</td></tr>\n";
  echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Group Count:</td><td align=left
                        class=table_rows width=80% style='padding-left:20px;'>$group_cnt</td></tr>\n";
  echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>User Count:</td><td align=left
                        class=table_rows width=80% style='padding-left:20px;'>$user_cnt</td></tr>\n";
  echo "              <tr><td height=15></td></tr>\n";
  echo "            </table>\n";
  echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
  echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
  echo "              <tr><td><a href='officeadmin.php'><img src='../images/buttons/done_button.png' border='0'></td></tr>
                    </table>
                  </form>\n";

echo '          </table>
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
