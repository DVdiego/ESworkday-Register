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
echo "<title>$title - Create Group</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

if (!isset($_SESSION['valid_user'])) {

echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>PHP Timeclock Administration</td></tr>\n";
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
          <div class="col-md-8">
            <div class="box box-info"> ';
  echo '      <div class="box-header with-border">
                   <h3 class="box-title"><i class="fa fa-users"></i> Create Group</h3>
              </div>
              <div class="box-body">';
echo "          <form name='form' action='$self' method='post'>\n";
echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/group_add.png' />&nbsp;&nbsp;&nbsp;Create Group
                    </th>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Group Name:</td><td colspan=2 align=left width=80%
                      style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>
                      <input type='text' size='25' maxlength='50' name='post_groupname'>&nbsp;*</td>
                    </tr>\n";

// query to populate dropdown with parent offices //

$query = "select * from ".$db_prefix."offices order by officename asc";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Parent Office:</td><td colspan=2 align=left width=80%
                      style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>
                      <select name='select_office_name'>\n";
echo "                        <option value ='1'>Choose One</option>\n";

while ($row=mysqli_fetch_array($result)) {
  echo "                        <option>".$row['officename']."</option>\n";
}
echo "                </select>&nbsp;*</td>
                    </tr>\n";
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

echo "              <tr><td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
echo "              <tr><td height=40>&nbsp;</td></tr>\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Create Group' align='middle'
                      src='../images/buttons/next_button.png'></td><td><a href='groupadmin.php'><img src='../images/buttons/cancel_button.png'
                      border='0'></td></tr>
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

$select_office_name = $_POST['select_office_name'];
$post_groupname = $_POST['post_groupname'];

/*FLAG*/
/*  puede ser necesaario implementar algun contenedor div para los mensajes de error, como estan sale bien pero se puede mejorar.
echo "    <td align=left class=right_main scope=col>\n";
echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
echo "        <tr class=right_main_text>\n";
echo "          <td valign=top>\n";
echo "            <br />\n";
*/
$post_groupname = stripslashes($post_groupname);
$select_office_name = stripslashes($select_office_name);
$post_groupname = addslashes($post_groupname);
$select_office_name = addslashes($select_office_name);

// begin post validation //

if (!empty($select_office_name)) {
$query = "select * from ".$db_prefix."offices where officename = '".$select_office_name."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row=mysqli_fetch_array($result)) {
$getoffice = "".$row['officename']."";
$officeid = "".$row['officeid']."";
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
}
if ((!isset($getoffice)) && ($select_office_name != '1')) {echo "Office is not defined for this user. Go back and associate this user with an office.\n";
exit;}

// check for duplicate groupnames with matching officeids //

$query = "select * from ".$db_prefix."groups where groupname = '".$post_groupname."' and officeid = '".@$officeid."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {
  $tmp_groupname = "".$row['groupname']."";
}

$string = strstr($post_groupname, "\'");
$string2 = strstr($post_groupname, "\"");

//if ((!empty($string)) || (empty($post_groupname)) || (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_groupname)) || ($select_office_name == '1') || (@$tmp_groupname == $post_groupname) || (!empty($string2))) {

if ((!empty($string)) || (empty($post_groupname)) || (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_groupname)) || ($select_office_name == '1') || (@$tmp_groupname == $post_groupname) || (!empty($string2))) {


    if (!empty($string)) {
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        Apostrophes are not allowed when creating a Group Name.</td></tr>\n";
    echo "            </table>\n";
    }elseif (!empty($string2)) {
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        Double Quotes are not allowed when creating a Group Name.</td></tr>\n";
    echo "            </table>\n";
    }elseif (empty($post_groupname)) {
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        A Group Name is required.</td></tr>\n";
    echo "            </table>\n";
    //}elseif (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_groupname)) {
    }elseif (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_groupname)) {
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        Alphanumeric characters, hyphens, underscores, spaces, and periods are allowed when creating a Group Name.</td></tr>\n";
    echo "            </table>\n";
    }elseif ($select_office_name == '1') {
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        A Parent Office must be chosen.</td></tr>\n";
    echo "            </table>\n";
    }elseif (@$tmp_groupname == $post_groupname) {
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                        Group already exists. Create another group.</td></tr>\n";
    echo "            </table>\n";
    }
    echo "            <br />\n";

    // end post validation //

    if (!empty($string)) {$post_groupname = stripslashes($post_groupname);}
    if (!empty($string2)) {$post_groupname = stripslashes($post_groupname);}

    echo '<div class="row">
            <div class="col-md-8">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-users"></i> Create Group</h3>
                </div>
                <div class="box-body">';
    echo "         <form name='form' action='$self' method='post'>\n";
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
    echo "              <tr>\n";
    echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/group_add.png' />&nbsp;&nbsp;&nbsp;Create Group
                         </th>\n";
    echo "              </tr>\n";
    echo "              <tr><td height=15></td></tr>\n";
    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Group Name:</td><td colspan=2 align=left width=80%
                          style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>
                          <input type='text' size='25' maxlength='50' name='post_groupname' value=\"$post_groupname\">&nbsp;*</td></tr>\n";

    if (!empty($string)) {$post_groupname = addslashes($post_groupname);}
    if (!empty($string2)) {$post_groupname = addslashes($post_groupname);}

    // query to populate dropdown with parent offices //

    $query = "select * from ".$db_prefix."offices order by officename asc";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Parent Office:</td><td colspan=2 align=left width=80%
                          style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>
                          <select name='select_office_name'>\n";
    echo "                        <option value ='1'>Choose One</option>\n";

    while ($row=mysqli_fetch_array($result)) {
      if ("".$row['officename']."" == $select_office_name) {
      echo "                        <option selected>".$row['officename']."</option>\n";
      } else {
      echo "                        <option>".$row['officename']."</option>\n";
      }
    }
    echo "                      </select>&nbsp;*</td></tr>\n";
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

    echo "              <tr><td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>*&nbsp;required&nbsp;</td></tr>\n";
    echo "            </table>\n";
    echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td height=40>&nbsp;</td></tr>\n";
    echo "              <tr><td width=30><input type='image' name='submit' value='Create Group' align='middle'
                          src='../images/buttons/next_button.png'></td><td><a href='groupadmin.php'><img src='../images/buttons/cancel_button.png'
                          border='0'></td></tr>
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

    $query = "insert into ".$db_prefix."groups (groupname, officeid) values ('".$post_groupname."', '".$officeid."')";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);


    echo '<div class="row">
            <div class="col-md-8">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-users"></i> Create Group</h3>
                </div>
                <div class="box-body">';

    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td class=table_rows width=20 align=center><img src='../images/icons/accept.png' /></td><td class=table_rows_green>
                        &nbsp;Group created successfully.</td></tr>\n";
    echo "            </table>\n";
    echo "            <br />\n";
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
    echo "              <tr>\n";
    echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/group_add.png' />&nbsp;&nbsp;&nbsp;Create Group
                         </th>\n";
    echo "              </tr>\n";
    echo "              <tr><td height=15></td></tr>\n";
    echo "              <tr><td class=table_rows width=20% height=25 style='padding-left:32px;' nowrap>Group Name:</td><td class=table_rows width=80%
                          style='padding-left:20px;' colspan=2>$post_groupname</td></tr>\n";
    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Parent Office:</td><td class=table_rows width=80%
                          style='padding-left:20px;' colspan=2>$select_office_name</td></tr>\n";
    echo "              <tr><td height=15></td></tr>\n";
    echo "            </table>\n";
    echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
    echo "              <tr><td><a href='groupcreate.php'><img src='../images/buttons/done_button.png' border='0'></td></tr>
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
