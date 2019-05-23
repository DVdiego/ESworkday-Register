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
if ($request !== 'POST') {include 'header_get.php';include 'topmain.php'; include 'leftmain.php';}
echo "<title>$title - Edit User</title>\n";

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

//comprueba que el usuario que se va a editar es el mismo que inicio sesión

if ($request == 'GET') {


  if($_SESSION['valid_profile'] != $_GET['username']){
    echo " ".$_SESSION['valid_profile'] ." - ".$_GET['username']."";
    exit;

  }

if (!isset($_GET['username'])) {

      echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
      echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Error!</td></tr>\n";
      echo "  <tr class=right_main_text>\n";
      echo "    <td align=center valign=top scope=row>\n";
      echo "      <table width=300 border=0 cellpadding=5 cellspacing=0>\n";
      echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
      echo "        <tr class=right_main_text><td align=center>Go back to the <a class=admin_headings href='index.php'>User Summary</a> page to edit users.
                      </td></tr>\n";
      echo "      </table><br /></td></tr></table>\n"; exit;
}

$get_user = $_GET['username'];
@$get_office = $_GET['officename'];

if (get_magic_quotes_gpc()) {$get_user = stripslashes($get_user);}


$get_user = addslashes($get_user);

$row_count = 0;



if($login_with_fullname == "yes"){
  $query = "select * from ".$db_prefix."employees where empfullname = '".$get_user."' order by empfullname";
}elseif ($login_with_displayname == "yes"){
  $query = "select * from ".$db_prefix."employees where displayname = '".$get_user."' order by empfullname";
}elseif ($login_with_dni == "yes"){
  $query = "select * from ".$db_prefix."employees where empDNI = '".$get_user."' order by empfullname";
}

$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

while ($row=mysqli_fetch_array($result)) {

$row_count++;
$row_color = ($row_count % 2) ? $color2 : $color1;

$username = stripslashes("".$row['empfullname']."");
$displayname = stripslashes("".$row['displayname']."");
$user_email = "".$row['email']."";
$groups_tmp = "".$row['groups']."";
$office = "".$row['office']."";
$admin = "".$row['admin']."";
$reports = "".$row['reports']."";
$time_admin = "".$row['time_admin']."";
$disabled = "".$row['disabled']."";
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

// make sure you cannot edit the admin perms for the last admin user in the system!! //

if (!empty($admin)) {
  $admin_count = mysqli_query($GLOBALS["___mysqli_ston"], "select empfullname from ".$db_prefix."employees where admin = '1'");
  @$admin_count_rows = mysqli_num_rows($admin_count);
  if (@$admin_count_rows == "1") {
    $evil = "1";
  }
}
if (isset($evil)) {

echo "            <table  class=table>\n";
echo "              <tr>\n";
echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Cannot edit the Sys Admin properties of this user as this user is the last Sys Admin User in the system. Go back and give another user
                    Sys Admin privileges before attempting to edit the Sys Admin properties of this user.</td></tr>\n";
echo "            </table>\n";
}

echo '<div class="row">
        <div class="col-md-8">
          <div class="box box-info"> ';
echo '      <div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-user"></i> Edit User</h3>
            </div>
            <div class="box-body">';
echo "            <br />\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class=table>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Username:</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'><input type='hidden' name='post_username' value=\"$username\">$username</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Display Name:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>
                      <input type='text' size='25' maxlength='50' name='display_name' value=\"$displayname\">&nbsp;*</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Email Address:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>
                      <input type='text' size='25' maxlength='75' name='email_addy' value='$user_email'>&nbsp;*</td></tr>\n";

echo "              <tr><td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
if (isset($evil)) {
  echo "<input type='hidden' name='evil' value='$evil'>\n";
}
echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
echo "              <tr><td height=40>&nbsp;</td></tr>\n";
echo "                  <input type='hidden' name='get_office' value='$get_office'>\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Edit User' align='middle'
                      src='../images/buttons/next_button.png'></td><td><a href='index.php'><img src='../images/buttons/cancel_button.png'
                      border='0'></td></tr></table></form>\n";
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

    include '../admin/header_post.php';include 'topmain.php'; include 'leftmain.php';

    $post_username = stripslashes($_POST['post_username']);
    $display_name = stripslashes($_POST['display_name']);
    $email_addy = $_POST['email_addy'];
    // $office_name = $_POST['office_name'];
    // @$get_office = $_POST['get_office'];
    // @$group_name = $_POST['group_name'];
    // @$admin_perms = $_POST['admin_perms'];
    // $reports_perms = $_POST['reports_perms'];
    // $time_admin_perms = $_POST['time_admin_perms'];
    // $post_disabled = $_POST['disabled'];
    @$evil = $_POST['evil'];

    if (isset($evil)) {
      if ($evil != '1') {echo "Something is fishy here."; exit;}
    }

    if (isset($evil)) {$admin_perms = "1";}
    $post_username = addslashes($post_username);

    if (!empty($post_username)) {
        $query = "select * from ".$db_prefix."employees where empfullname = '".$post_username."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        while ($row=mysqli_fetch_array($result)) {
          $tmp_username = "".$row['empfullname']."";
        }
        if (!isset($tmp_username)) {echo "$tmp_username, $post_username. Something is fishy here.\n"; exit;}
    }

    $post_username = stripslashes($post_username);
    $tmp_post_username = stripslashes($post_username);
    $string = strstr($display_name, "\"");



    if ((!preg_match('/' . "^([[:alnum:]]|Å|Ä|Ö| |-|'|,)+$" . '/i', $display_name))
    || (empty($display_name)) || (empty($email_addy))
    || (!preg_match('/' . "^([[:alnum:]]|_|\.|-)+@([[:alnum:]]|\.|-)+(\.)([a-z]{2,4})$" . '/i', $email_addy))
    || (!empty($string))) {

        // begin post validation //

        if (empty($display_name)) {
        echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr>\n";
        echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                            A Display Name is required.</td></tr>\n";
        echo "            </table>\n";
        }
        elseif (empty($email_addy)) {
        echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr>\n";
        echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                            An Email Address is required.</td></tr>\n";
        echo "            </table>\n";
        }
        elseif (!empty($string)) {
        echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr>\n";
        echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                            Double Quotes are not allowed when creating an Username.</td></tr>\n";
        echo "            </table>\n";
        }
        // elseif (!eregi ("^([[:alnum:]]| |-|'|,)+$", $display_name)) {
        //elseif (!preg_match('/' . "^([[:alnum:]]| |-|'|,)+$" . '/i', $display_name)) {
          elseif (!preg_match('/' . "^([[:alnum:]]|Å|Ä|Ö| |-|'|,)+$" . '/i', $display_name)) {
        echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr>\n";
        echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                            Alphanumeric characters, hyphens, apostrophes, commas, and spaces are allowed when creating a Display Name.</td></tr>\n";
        echo "            </table>\n";
        }
        // elseif (!eregi ("^([[:alnum:]]|_|\.|-)+@([[:alnum:]]|\.|-)+(\.)([a-z]{2,4})$", $email_addy)) {
        elseif (!preg_match('/' . "^([[:alnum:]]|_|\.|-)+@([[:alnum:]]|\.|-)+(\.)([a-z]{2,4})$" . '/i', $email_addy)) {
        echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr>\n";
        echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                            Alphanumeric characters, underscores, periods, and hyphens are allowed when creating an Email Address.</td></tr>\n";
        echo "            </table>\n";
        }



        // end post validation //

        if (!empty($string)) {$display_name = stripslashes($display_name);}
        echo '<div class="row">
                <div class="col-md-8">
                  <div class="box box-info"> ';
        echo '      <div class="box-header with-border">
                         <h3 class="box-title"><i class="fa fa-user"></i> Edit User</h3>
                    </div>
                    <div class="box-body">';
        echo "            <br />\n";
        echo "            <form name='form' action='$self' method='post'>\n";
        echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
        echo "              <tr><td height=15></td></tr>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Username:</td><td align=left class=table_rows
                              colspan=2 width=80% style='padding-left:20px;'><input type='hidden' name='post_username'
                              value=\"$post_username\">$tmp_post_username</td></tr>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Display Name:</td><td colspan=2 width=80%
                              style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>
                              <input type='text' size='25' maxlength='50' name='display_name' value=\"$display_name\">&nbsp;*</td></tr>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Email Address:</td><td colspan=2 width=80%
                              style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'>
                              <input type='text' size='25' maxlength='75' name='email_addy' value='$email_addy'>&nbsp;*</td></tr>\n";


        echo "              <tr><td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>*&nbsp&nbsp;;required</td></tr>\n";
        echo "            </table>\n";
        if (isset($evil)) {
          echo "<input type='hidden' name='evil' value='$evil'>\n";
        }
        echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr><td height=40>&nbsp;</td></tr>\n";
        echo "                  <input type='hidden' name='get_office' value='$get_office'>\n";
        echo "              <tr><td width=30><input type='image' name='submit' value='Edit User' align='middle'
                              src='../images/buttons/next_button.png'></td><td><a href='index.php'><img src='../images/buttons/cancel_button.png'
                              border='0'></td></tr></table></form>\n";
                              echo '      </div>
                                        </div>
                                      </div>
                                    </div>';
                              include '../theme/templates/endmaincontent.inc';
                              include '../theme/templates/controlsidebar.inc';
                              include '../theme/templates/endmain.inc';
                              include '../theme/templates/adminfooterscripts.inc';
                              include '../footer.php';exit;
        $post_username = stripslashes($post_username);
        $display_name = stripslashes($display_name);
        exit;
    }

    $post_username = stripslashes($post_username);
    $display_name = stripslashes($display_name);
    $post_username = addslashes($post_username);
    $display_name = addslashes($display_name);

    $query3 = "update ".$db_prefix."employees set displayname = ('".$display_name."'), email = ('".$email_addy."')
               where empfullname = ('".$post_username."')";
    $result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);


    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr>\n";
    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/accept.png' /></td>
                    <td class=table_rows_green>&nbsp;User properties updated successfully.</td></tr>\n";

    echo "            </table>\n";
    echo '<div class="row">
            <div class="col-md-8">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-user"></i> Edit User</h3>
                </div>
                <div class="box-body">';
    echo "            <br />\n";
    echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
    echo "              <tr><td height=15></td></tr>\n";

    $query4 = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
    	  where empfullname = '".$post_username."'
              order by empfullname";
    $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);

    while ($row=mysqli_fetch_array($result4)) {

    $username = stripslashes("".$row['empfullname']."");
    $displayname = stripslashes("".$row['displayname']."");
    $user_email = "".$row['email']."";
    $office = "".$row['office']."";
    $groups = "".$row['groups']."";
    $admin = "".$row['admin']."";
    $reports = "".$row['reports']."";
    $time_admin = "".$row['time_admin']."";
    $disabled = "".$row['disabled']."";
    }
    ((mysqli_free_result($result4) || (is_object($result4) && (get_class($result4) == "mysqli_result"))) ? true : false);

    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Username:</td><td align=left class=table_rows
                          colspan=2 width=80% style='padding-left:20px;'>$username</td></tr>\n";
    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Display Name:</td><td align=left class=table_rows
                          colspan=2 width=80% style='padding-left:20px;'>$displayname</td></tr>\n";
    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Email Address:</td><td align=left class=table_rows
                          colspan=2 width=80% style='padding-left:20px;'>$user_email</td></tr>\n";
    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Office:</td><td align=left class=table_rows
                          colspan=2 width=80% style='padding-left:20px;'>$office</td></tr>\n";
    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Group:</td><td align=left class=table_rows
                          colspan=2 width=80% style='padding-left:20px;'>$groups</td></tr>\n";

    echo "              <tr><td height=15></td></tr>\n";
    echo "            </table>\n";
    echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
    echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
    echo "              <tr><td><a href='index.php'><img src='../images/buttons/done_button.png' border='0'></a></td></tr>
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
?>
