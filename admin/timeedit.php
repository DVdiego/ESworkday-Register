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
 * This currently only works with american calendar m/d/yyyy and us time   *
 *  03:30 am need to fix this.... maybe function validateDate is the key   *
 									   */

/* Suggesting the following solution insted of regex for date and time


if ($calendar_style == "euro") {
$mydateformat="d/m/Y";}
else
{$mydateformat="m/d/Y";}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
 $d = DateTime::createFromFormat($format, $date);
 return $d && $d->format($format) == $date;
 }

 if (validateDate(''.$_POST['post_date'].'', ''.$mydateformat.'')) {
// have not tested the post_date part yet
//for more se http://php.net/manual/en/function.checkdate.php
// if (validateDate('28/02/2012', ''.$mydateformat.'')) {

    // something when true
 echo "True";
  } else {
  // something else when false
 echo "False";
 }

*/

session_start();

#include 'config.inc.php';
// use a different config.inc in Europe for now...
include '../config.inc.php';
//include 'header_date.php';
include 'header.php';
include 'topmain.php';
include 'leftmain-time.php';

/**
 * This module will edit an employee's punch time, and update the current
 * employee's status if need be. An audit trail of the edit is maintained.
 */

echo "<title>$title - Edit Time</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

if (($timefmt == "G:i") || ($timefmt == "H:i")) {
  $timefmt_24hr = '1';
  $timefmt_24hr_text = '24 hr format';
  $timefmt_size = '5';
} else {
  $timefmt_24hr = '0';
  $timefmt_24hr_text = '12 hr format';
  $timefmt_size = '8';
}

// Ensure the user has access rights to editing time.
if ((!isset($_SESSION['valid_user'])) && (!isset($_SESSION['time_admin_valid_user']))) {
    echo "<table class='table' width=100% border=0 cellpadding=7 cellspacing=1>\n";
    echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Administration</td></tr>\n";
    echo "  <tr class=right_main_text>\n";
    echo "    <td align=center valign=top scope=row>\n";
    echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
    echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
    echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=admin'><u>here</u></a> to login.</td></tr>\n";
    echo "      </table><br /></td></tr></table>\n";
    exit;
}

if ($request == 'GET') { // Display employee select interface for editing an employee's time
    if (!isset($_GET['username'])) { // Make sure there is a logged in user
        echo "<table class='table' width=100% border=0 cellpadding=7 cellspacing=1>\n";
        echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Error!</td></tr>\n";
        echo "  <tr class=right_main_text>\n";
        echo "    <td align=center valign=top scope=row>\n";
        echo "      <table width=300 border=0 cellpadding=5 cellspacing=0>\n";
        echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
        echo "        <tr class=right_main_text><td align=center>Go back to the <a class=admin_headings href='timeadmin.php'>Modify Time</a> page to edit a time.</td></tr>\n";
        echo "      </table><br /></td></tr></table>\n";
        exit;
    }


    $get_user = addslashes($get_user);

    $query = "select * from ".$db_prefix."employees where empfullname = '".$get_user."' order by empfullname";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    while ($row=mysqli_fetch_array($result)) {
        $username = stripslashes("".$row['empfullname']."");
        $displayname = stripslashes("".$row['displayname']."");
    }
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

    $get_user = stripslashes($_GET['username']);


echo '<div class="row">
    <div class="col-md-6">
      <div class="box box-info"> ';
echo '<div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-clock-o"></i> Edit Time</h3>
               </div><div class="box-body">';


    echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
    echo "                <input type='hidden' name='date_format' value='$js_datefmt'>\n";
    echo "              <div class='form-group'><label>Username:</label> <div class='input-group'> <input type='hidden' name='post_username' value=\"$username\">$username\n";
    echo '</div></div>';
    echo "              <div class='form-group'><label>Display Name:</label> <div class='input-group'><input type='hidden' name='post_displayname' value=\"$displayname\">$displayname\n";
    echo '</div></div>';

    // echo "              <div class='form-group'><label>From Date: " .($tmp_datefmt)."</label> <div class='input-group date'><i class='fa fa-calendar'></i><input type='text' maxlength='10' name='post_date' id='datepicker' class='form-control'> &nbsp;*&nbsp;&nbsp; </div></div>\n";
    // echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
    // echo "                <input type='hidden' name='timefmt_24hr' value=\"$timefmt_24hr\">\n";
    // echo "                <input type='hidden' name='timefmt_24hr_text' value=\"$timefmt_24hr_text\">\n";
    // echo "                <input type='hidden' name='timefmt_size' value=\"$timefmt_size\">\n";
    // echo "                 *&nbsp;required&nbsp;\n";
    echo "    <div class='form-group'>
                <label>Fecha:</label>
                <input type='date' size='10' maxlength='10' name='post_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
                <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                 </div>";
    echo '<div class="box-footer">
                <button type="submit" name="submit" value="Edit Time" class="btn btn-info">Edit Time</button>
                <button type="submit" name="cancel" class="btn btn-default pull-right"><a href="timeadmin.php">Cancel</a></button>
              </div></form>';

    echo '</div></div></div></div>';
        include '../theme/templates/endmaincontent.inc';
    include '../footer.php';
	include '../theme/templates/controlsidebar.inc';
	include '../theme/templates/endmain.inc';
	include '../theme/templates/adminfooterscripts.inc';
    exit;
} elseif ($request == 'POST') { // Display interface for editing the selected employee's time.
    //$get_user = stripslashes($_POST['get_user']);

    $post_username = stripslashes($_POST['post_username']);
    $post_displayname = stripslashes($_POST['post_displayname']);
    $post_date = $_POST['post_date'];
    @$final_username = $_POST['final_username'];
    @$final_inout = $_POST['final_inout'];
    @$final_notes = $_POST['final_notes'];
    @$final_mysql_timestamp = $_POST['final_mysql_timestamp'];
    @$final_num_rows = $_POST['final_num_rows'];
    @$final_time = $_POST['final_time'];
    @$edit_time_textbox = $_POST['edit_time_textbox'];
    @$timestamp = $_POST['timestamp'];
    @$calc = $_POST['calc'];
    $row_count = '0';
    $cnt = '0';
    #$post_why = $_POST['post_why'];
    $post_why = "edit user time";

    $get_user = addslashes($get_user);
    $post_username = addslashes($post_username);
    $post_displayname = addslashes($post_displayname);

    // begin post validation //

    if (!empty($get_user)) {
        $query = "select * from ".$db_prefix."employees where empfullname = '".$get_user."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        while ($row=mysqli_fetch_array($result)) {
            $tmp_get_user = "".$row['empfullname']."";
        }
        if (!isset($tmp_get_user)) {
            echo "Something is fishy here.\n";
            exit;
        }
    }

    if (!empty($post_username)) {
        $query = "select * from ".$db_prefix."employees where empfullname = '".$post_username."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        while ($row=mysqli_fetch_array($result)) {
            $tmp_username = "".$row['empfullname']."";
        }
        if (!isset($tmp_username)) {
            echo "Something is fishy here.\n";
            exit;
        }
    }

    if (!empty($post_displayname)) {
        $query = "select * from ".$db_prefix."employees where empfullname = '".$post_username."' and displayname = '".$post_displayname."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        while ($row=mysqli_fetch_array($result)) {
            $tmp_post_displayname = "".$row['displayname']."";
        }
        if (!isset($tmp_post_displayname)) {
            echo "Something is fishy here.\n";
            exit;
        }
    }

    // end post validation //

    $get_user = stripslashes($get_user);
    $post_username = stripslashes($post_username);
    $post_displayname = stripslashes($post_displayname);

    // begin post validation //

/*FLAG*/
    // if ($get_user != $post_username) {
    //
    //     exit;
    // }

    // end post validation //

	/*
    echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
    echo "  <tr valign=top>\n";
    echo "    <td class=left_main width=180 align=left scope=col>\n";
    echo "      <table width=100% border=0 cellpadding=1 cellspacing=0>\n";
    echo "        <tr><td class=left_rows height=11></td></tr>\n";
    echo "        <tr><td class=left_rows_headings height=18 valign=middle>Users</td></tr>\n";
    echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/user.png' alt='User Summary' />&nbsp;&nbsp; <a class=admin_headings href='useradmin.php'>User Summary</a></td></tr>\n";
    echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/user_add.png' alt='Create New User' />&nbsp;&nbsp; <a class=admin_headings href='usercreate.php'>Create New User</a></td></tr>\n";
    echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/magnifier.png' alt='User Search' />&nbsp;&nbsp; <a class=admin_headings href='usersearch.php'>User Search</a></td></tr>\n";
    echo "        <tr><td class=left_rows height=33></td></tr>\n";
    echo "        <tr><td class=left_rows_headings height=18 valign=middle>Offices</td></tr>\n";
    echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/brick.png' alt='Office Summary' />&nbsp;&nbsp; <a class=admin_headings href='officeadmin.php'>Office Summary</a></td></tr>\n";
    echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/brick_add.png' alt='Create New Office' />&nbsp;&nbsp; <a class=admin_headings href='officecreate.php'>Create New Office</a></td></tr>\n";
    echo "        <tr><td class=left_rows height=33></td></tr>\n";
    echo "        <tr><td class=left_rows_headings height=18 valign=middle>Groups</td></tr>\n";
    echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/group.png' alt='Group Summary' />&nbsp;&nbsp; <a class=admin_headings href='groupadmin.php'>Group Summary</a></td></tr>\n";
    echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/group_add.png' alt='Create New Group' />&nbsp;&nbsp; <a class=admin_headings href='groupcreate.php'>Create New Group</a></td></tr>\n";
    echo "        <tr><td class=left_rows height=33></td></tr>\n";
    echo "        <tr><td class=left_rows_headings height=18 valign=middle colspan=2>In/Out Status</td></tr>\n";
    echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/application.png' alt='Status Summary' /> &nbsp;&nbsp;<a class=admin_headings href='statusadmin.php'>Status Summary</a></td></tr>\n";
    echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/application_add.png' alt='Create Status' />&nbsp;&nbsp; <a class=admin_headings href='statuscreate.php'>Create Status</a></td></tr>\n";
    echo "        <tr><td class=left_rows height=33></td></tr>\n";
    echo "        <tr><td class=left_rows_headings height=18 valign=middle colspan=2>Miscellaneous</td></tr>\n";
    echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/clock.png' alt='Modify Time' /> &nbsp;&nbsp;<a class=admin_headings href='timeadmin.php'>Modify Time</a></td></tr>\n";
    echo "        <tr><td class=left_rows_indent height=18 align=left valign=middle><img src='../images/icons/arrow_right.png' alt='Add Time' /> &nbsp;&nbsp;<a class=admin_headings href=\"timeadd.php?username=$get_user\">Add Time</a></td></tr>\n";
    echo "        <tr><td class=current_left_rows_indent height=18 align=left valign=middle><img src='../images/icons/arrow_right.png' alt='Edit Time' /> &nbsp;&nbsp;<a class=admin_headings href=\"timeedit.php?username=$get_user\">Edit Time</a></td></tr>\n";
    echo "        <tr><td class=left_rows_indent height=18 align=left valign=middle><img src='../images/icons/arrow_right.png' alt='Delete Time' /> &nbsp;&nbsp;<a class=admin_headings href=\"timedelete.php?username=$get_user\">Delete Time</a></td></tr>\n";
    echo "        <tr><td class=left_rows_border_top height=18 align=left valign=middle><img src='../images/icons/application_edit.png' alt='Edit System Settings' /> &nbsp;&nbsp;<a class=admin_headings href='sysedit.php'>Edit System Settings</a></td></tr>\n";
    echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/database_go.png' alt='Manage Database' />&nbsp;&nbsp;&nbsp;<a class=admin_headings href='database_management.php'>Manage Database</a></td></tr>\n";
    echo "      </table></td>\n";
    echo "    <td align=left class=right_main scope=col>\n";
    echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
    echo "        <tr class=right_main_text>\n";
    echo "          <td valign=top>\n";
    echo "            <br />\n";
*/

    // begin post validation //

    if (empty($post_date)) {
        $evil_post = '1';
        echo "            <table align=center class=table width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr>\n";
        echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid Date is required.</td></tr>\n";
        echo "            </table>\n";
//    } elseif (eregi ("^([0-9]{1,2})[-,/,.]([0-9]{1,2})[-,/,.](([0-9]{2})|([0-9]{4}))$", $post_date, $date_regs)) {
    }
    // elseif (preg_match('/' . "^([0-9]{1,2})[-\,\/,.]([0-9]{1,2})[-\,\/,.](([0-9]{2})|([0-9]{4}))$" . '/i', $post_date, $date_regs)) {
    //     if ($calendar_style == "amer") {
    //         if (isset($date_regs)) {
    //             $month = $date_regs[1];
    //             $day = $date_regs[2];
    //             $year = $date_regs[3];
    //         }
    //         if ($month > 12 || $day > 31) {
    //             $evil_post = '1';
    //             if (!isset($evil_post)) {
    //                 echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    //                 echo "              <tr>\n";
    //                 echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid Date is required.</td></tr>\n";
    //                 echo "            </table>\n";
    //             }
    //         }
    //     } elseif ($calendar_style == "euro") {
    //         if (isset($date_regs)) {
    //             $month = $date_regs[2];
    //             $day = $date_regs[1];
    //             $year = $date_regs[3];
    //         }
    //         if ($month > 12 || $day > 31) {
    //             $evil_post = '1';
    //             if (!isset($evil_post)) {
    //                 echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
    //                 echo "              <tr>\n";
    //                 echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid Date is required.</td></tr>\n";
    //                 echo "            </table>\n";
    //             }
    //         }
    //     }
    // }

    if (isset($evil_post)) { // Display error message

        // echo "            <br />\n";
        // echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
        // echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
        // echo "              <tr>\n";
        // echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/clock_add.png' />&nbsp;&nbsp;&nbsp;Edit Time </th></tr>\n";
        // echo "              <tr><td height=15></td></tr>\n";
        // echo "              <input type='hidden' name='date_format' value='$js_datefmt'>\n";
        // echo "              <tr><td class=table_rows height=25 style='padding-left:32px;' width=20% nowrap>Username:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'> <input type='hidden' name='post_username' value=\"$post_username\">$post_username</td></tr>\n";
        // echo "              <tr><td class=table_rows height=25 style='padding-left:32px;' width=20% nowrap>Display Name:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'> <input type='hidden' name='post_displayname' value=\"$post_displayname\">$post_displayname</td></tr>\n";
        // echo "              <tr><td class=table_rows height=25 style='padding-left:32px;' width=20% nowrap>Date: ($tmp_datefmt)</td><td colspan=2 width=80% style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'><input type='text' size='10' maxlength='10' name='post_date' value='$post_date'>&nbsp;*&nbsp;&nbsp;&nbsp;<a href=\"#\" onclick=\"cal.select(document.forms['form'].post_date,'post_date_anchor','$js_datefmt'); return false;\" name=\"post_date_anchor\" id=\"post_date_anchor\" style='font-size:11px;color:#27408b;'>Pick Date</a></td><tr>\n";
        // echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
        // echo "              <tr><td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>*&nbsp;required&nbsp;</td></tr>\n";
        // echo "            </table>\n";
        // echo "            <div style=\"position:absolute;visibility:hidden;background-color:#ffffff;layer-background-color:#ffffff;\" id=\"mydiv\" height=200>&nbsp;</div>\n";
        // echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
        // echo "              <tr><td height=40>&nbsp;</td></tr>\n";
        // echo "              <tr><td width=30><input type='image' name='submit' value='Edit Time' align='middle' src='../images/buttons/next_button.png'></td><td><a href='timeadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr>
        //                 </table></form>\n";


        echo '<div class="row">
            <div class="col-md-6">
              <div class="box box-info"> ';
        echo '<div class="box-header with-border">
                         <h3 class="box-title"><i class="fa fa-clock-o"></i> Edit Time</h3>
                       </div><div class="box-body">';

        echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
        echo "                <input type='hidden' name='date_format' value='$js_datefmt'>\n";
        echo "              <div class='form-group'><label>Username:</label> <div class='input-group'> <input type='hidden' name='post_username' value=\"$post_username\">$post_username\n";
        echo '</div></div>';
        echo "              <div class='form-group'><label>Display Name:</label> <div class='input-group'><input type='hidden' name='post_displayname' value=\"$post_displayname\">$post_displayname\n";
        echo '</div></div>';

        echo "              <div class='form-group'><label>From Date: " .($tmp_datefmt)."</label> <div class='input-group date'><i class='fa fa-calendar'></i><input type='text' maxlength='10' name='post_date' id='datepicker' class='form-control'> &nbsp;*&nbsp;&nbsp; </div></div>\n";
        echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
        echo "                <input type='hidden' name='timefmt_24hr' value=\"$timefmt_24hr\">\n";
        echo "                <input type='hidden' name='timefmt_24hr_text' value=\"$timefmt_24hr_text\">\n";
        echo "                <input type='hidden' name='timefmt_size' value=\"$timefmt_size\">\n";
        echo "                 *&nbsp;required&nbsp;\n";
        echo '<div class="box-footer">
                    <button type="submit" name="submit" value="Edit Time" class="btn btn-info">Edit Time</button>
                    <button type="submit" name="cancel" class="btn btn-default pull-right"><a href="timeadmin.php">Cancel</a></button>
                  </div></form>';

        echo "</div></div></div></div>";
        include '../theme/templates/endmaincontent.inc';
        include '../footer.php';
        include '../theme/templates/controlsidebar.inc';
        include '../theme/templates/endmain.inc';
        include '../theme/templates/adminfooterscripts.inc';
        exit;

    } else {

      // Commit changes to employee's time and audit log
        if (isset($_POST['tmp_var'])) { // begin post validation //
            if ($_POST['tmp_var'] != '1') {
                echo "Something is fishy here.\n";
                exit;
            }
            $tmp2_calc = intval($calc);
            $tmp2_timestamp = intval($timestamp);
            if ((strlen($tmp2_calc) != "10") || (!is_integer($tmp2_calc))) {
                echo "Something is fishy here.\n";
                exit;
            }
            if ((strlen($tmp2_timestamp) != "10") || (!is_integer($tmp2_timestamp))) {
                echo "Something is fishy here.\n";
                exit;
            }
            if (!is_numeric($final_num_rows)) {
                exit;
            }
            // end post validation //

            for ($x=0;$x<$final_num_rows;$x++) {

                $final_username[$x] = stripslashes($final_username[$x]);
                $tmp_username = stripslashes($tmp_username);

                if ($final_username[$x] != $tmp_username) {
                    echo "Something is fishy here.\n";
                    exit;
                }

                $final_mysql_timestamp[$x] = intval($final_mysql_timestamp[$x]);

                if ((strlen($final_mysql_timestamp[$x]) != "10") || (!is_integer($final_mysql_timestamp[$x]))) {
                    echo "Something is fishy here.\n";
                    exit;
                }

                $query_sel = "select * from ".$db_prefix."punchlist where punchitems = '".$final_inout[$x]."'";
                $result_sel = mysqli_query($GLOBALS["___mysqli_ston"], $query_sel);

                while ($row=mysqli_fetch_array($result_sel)) {
                    $punchitems = "".$row['punchitems']."";
                }
                ((mysqli_free_result($result_sel) || (is_object($result_sel) && (get_class($result_sel) == "mysqli_result"))) ? true : false);

                if (!isset($punchitems)) {
                    echo "Something is fishy here.\n";
                    exit;
                }

 //               $final_notes[$x] = ereg_replace("[^[:alnum:] \,\.\?-]","",$final_notes[$x]);
		            $final_notes[$x] = preg_replace('/' . "[^[:alnum:] \,\.\?-]" . '/',"",$final_notes[$x]);
                $final_username[$x] = addslashes($final_username[$x]);

                $query5 = "select * from ".$db_prefix."info where (fullname = '".$final_username[$x]."') and (timestamp = '".$final_mysql_timestamp[$x]."') and (`inout` = '".$final_inout[$x]."')";
                $result5 = mysqli_query($GLOBALS["___mysqli_ston"], $query5);
                @$tmp_num_rows = mysqli_num_rows($result5);

                if ((isset($tmp_num_rows)) && (@$tmp_num_rows != '1')) {
                    echo "Something is fishy here.\n";
                    exit;
                }

                if (!empty($edit_time_textbox[$x])) { // configure timestamp to insert/update //

                    // if ($calendar_style == "euro") {
                    //   $post_date = "$day/$month/$year";
                    //
                    // } elseif ($calendar_style == "amer") {
                    //  //   $post_date = "$month/$day/$year";
                    // }

                    $tmp_timestamp = strtotime($post_date) - @$tzo;
                    $tmp_calc = $timestamp + 86400 - @$tzo;

                    if (($tmp_timestamp != $timestamp) || ($tmp_calc != $calc)) {
                        echo "Something is fishy here.\n";
                        exit;
                    }
                    // end post validation //
                    if ($timefmt_24hr == '0') {
//                        if ((!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])+([a|p]+m)$", $edit_time_textbox[$x], $time_regs)) && (!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])+( [a|p]+m)$", $edit_time_textbox[$x], $time_regs))) {

                        if ((!preg_match('/' . "^([0-9]?[0-9])+:+([0-9]+[0-9])+([a|p]+m)$" . '/i', $edit_time_textbox[$x], $time_regs)) && (!preg_match('/' . "^([0-9]?[0-9])+:+([0-9]+[0-9])+( [a|p]+m)$" . '/i', $edit_time_textbox[$x], $time_regs))) {

                            $evil_time = '1';
                        } else {
                            if (isset($time_regs)) {
                                $h = $time_regs[1];
                                $m = $time_regs[2];
                            }
                            $h = $time_regs[1]; $m = $time_regs[2];
                            if (($h > 12) || ($m > 59)) {
                                $evil_time = '1';
                            }
                        }

                    } elseif ($timefmt_24hr == '1') {
                      //  if (!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])$", $edit_time_textbox[$x], $time_regs)) {
  			                if (!preg_match('/' . "^([0-9]?[0-9])+:+([0-9]+[0-9])$" . '/i', $edit_time_textbox[$x], $time_regs)) {
                              $evil_time = '1';
                          } else {
                              if (isset($time_regs)) {
                                  $h = $time_regs[1];
                                  $m = $time_regs[2];
                              }
                              $h = $time_regs[1]; $m = $time_regs[2];
                              if (($h > 24) || ($m > 59)) {
                                  $evil_time = '1';
                              }
                          }
                    }
                }
            }


            for ($x=0;$x<$final_num_rows;$x++) {
                if (empty($edit_time_textbox[$x])) {
                    $cnt++;
                }
            }
            if ($cnt == $final_num_rows) {
                $evil_time = '1';
            }

            if (isset($evil_time) || (($require_time_admin_edit_reason == "yes") && empty($post_why))) { // Display error message



              echo '<div class="row">
                  <div class="col-md-6">
                    <div class="box box-info"> ';
              echo '<div class="box-header with-border">
                               <h3 class="box-title"><i class="fa fa-clock-o"></i> Edit Time</h3>
                             </div><div class="box-body">';

                echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "              <tr>\n";
                if (isset($evil_time)) {
                    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid Time is required.</td></tr>\n";
                }
                if (empty($post_why) && ($require_time_admin_edit_reason == "yes")) {
                    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A reason for the modification is required.</td></tr>\n";
                }
                echo "            </table>\n";
                echo "            <br />\n";
                echo "            <form name='form' action='$self' method='post'>\n";
                echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
                echo "              <tr>\n";

                // configure date to display correctly //

                // if ($calendar_style == "euro") {
                //
                //     $post_date = "$day/$month/$year";
                // }

                echo "                <th class=rightside_heading nowrap halign=left colspan=4><img src='../images/icons/clock_edit.png' />&nbsp;&nbsp;&nbsp;Edit Time for $post_username on $post_date</th></tr>\n";
                echo "              <tr><td height=15></td></tr>\n";
                echo "                <tr><td nowrap width=1% class=column_headings style='padding-right:5px;padding-left:10px;'><b>New Time<b></td>\n";
                echo "                  <td nowrap width=7% align=left style='padding-left:15px;' class=column_headings>In/Out</td>\n";
                echo "                  <td nowrap style='padding-left:20px;' width=4% align=left class=column_headings>Current Time</td>\n";
                echo "                  <td style='padding-left:25px;' class=column_headings><u>Notes</u></td></tr>\n";

                for ($x=0;$x<$final_num_rows;$x++) {
                    $row_color = ($row_count % 2) ? $color1 : $color2;
                    $final_username[$x] = stripslashes($final_username[$x]);
                    echo "              <tr class=display_row>\n";


                    // echo "                <td nowrap width=1% style='padding-right:5px;padding-left:10px;' class=table_rows><input type='text' size='7' maxlength='$timefmt_size' name='edit_time_textbox[$x]' value=\"$edit_time_textbox[$x]\"></td>\n";

                    echo "                <td nowrap width=1% style='padding-right:5px;padding-left:10px;' class=table_rows>";
                    echo'    <div class="bootstrap-timepicker">
                        	                   <div class="form-group">';


                    		      echo'    	                     <div class="input-group">';
                    		      echo "                      <input type='text' size='10' class='form-control timepicker' maxlength='$timefmt_size' name='edit_time_textbox[$x]'>";
                    echo'    	                       <div class="input-group-addon">
                        	                         <i class="fa fa-clock-o"></i>
                        	                       </div>
                        	                     </div>
                        	                     <!-- /.input group -->
                        	                   </div>
                        	                   <!-- /.form group -->
                        	                 </div>
                        	               ';

                    echo "                <td nowrap align=left style='width:7%;padding-left:15px;background-color:$row_color;color:".$row["color"]."'>$final_inout[$x]</td>\n";
                    echo "                <td nowrap align=left style='padding-left:20px;' width=4% bgcolor='$row_color'>$final_time[$x]</td>\n";
                    echo "                <td style='padding-left:25px;' bgcolor='$row_color'>$final_notes[$x]</td>\n";
                    echo "              </tr>\n";
                    echo "              <input type='hidden' name='final_username[$x]' value=\"$final_username[$x]\">\n";
                    echo "              <input type='hidden' name='final_inout[$x]' value=\"$final_inout[$x]\">\n";
                    echo "              <input type='hidden' name='final_notes[$x]' value=\"$final_notes[$x]\">\n";
                    echo "              <input type='hidden' name='final_time[$x]' value=\"$final_time[$x]\">\n";
                    echo "              <input type='hidden' name='final_mysql_timestamp[$x]' value=\"$final_mysql_timestamp[$x]\">\n";
                    $row_count++;
                }
                if  ($require_time_admin_edit_reason == "yes") {
                    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Reason For Modification:</td><td colspan=2 width=80% style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'><input type='text' size='25' maxlength='250' name='post_why' value='$post_why'>&nbsp;* Required</td></tr>\n";
                } else if  ($require_time_admin_edit_reason == "no") {
                    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Reason For Modification:</td><td colspan=2 width=80% style='font-family:Tahoma;font-size:10px;padding-left:20px;'><input type='text' size='25' maxlength='250' name='post_why' value='$post_why'></td></tr>\n";
                }
                echo "              <tr><td height=15></td></tr>\n";
                $tmp_var = '1';
                echo "            <input type='hidden' name='calc' value=\"$calc\">\n";
                echo "            <input type='hidden' name='timestamp' value=\"$timestamp\">\n";
                echo "            <input type='hidden' name='tmp_var' value=\"$tmp_var\">\n";
                echo "            <input type='hidden' name='post_username' value=\"$post_username\">\n";
                echo "            <input type='hidden' name='post_displayname' value=\"$post_displayname\">\n";
                echo "            <input type='hidden' name='post_date' value=\"$post_date\">\n";
                echo "            <input type='hidden' name='get_user' value=\"$get_user\">\n";
                echo "            <input type='hidden' name='final_num_rows' value=\"$final_num_rows\">\n";
                echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "              <tr><td height=40>&nbsp;</td></tr>\n";
                echo "              <tr><td width=30><input type='image' name='submit' value='Edit Time' align='middle' src='../images/buttons/next_button.png'></td><td><a href='timeadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr>
                                </table>
                          </form><\n";

                echo "</div></div></div></div>";
		            include '../theme/templates/endmaincontent.inc';
                include '../footer.php';
        				include '../theme/templates/controlsidebar.inc';
        				include '../theme/templates/endmain.inc';
        				include '../theme/templates/adminfooterscripts.inc';

                exit;
            } elseif (!isset($evil_time) && ((($require_time_admin_edit_reason == "yes") && (!empty($post_why))) || ($require_time_admin_edit_reason == "no"))) {
              // Commit employee's time, status changes and audit log to the database
              echo '<div class="row">
                  <div class="col-md-6">
                    <div class="box box-info"> ';
              echo '<div class="box-header with-border">
                               <h3 class="box-title"><i class="fa fa-clock-o"></i> Edit Time</h3>
                             </div><div class="box-body">';
                echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "              <tr>\n";
                echo "              <td class=table_rows width=20 align=center><img src='../images/icons/accept.png' /></td><td class=table_rows_green> &nbsp;Time edited successfully.</td></tr>\n";
                echo "            </table>\n";
                echo "            <br />\n";
                echo "            <form name='form' action='$self' method='post'>\n";
                echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
                echo "              <tr>\n";

                // configure date to display correctly //
                // if ($calendar_style == "euro") {
                //    $post_date = "$day/$month/$year";
                //
                // }
                echo "                <th class=rightside_heading nowrap halign=left colspan=5><img src='../images/icons/clock_edit.png' />&nbsp;&nbsp;&nbsp;Edited Time for $post_username on $post_date</th></tr>\n";
                echo "              <tr><td height=15></td></tr>\n";
                echo "                <tr><td width=1% class=table_rows style='padding-left:5px;padding-right:5px;'></td><td nowrap width=1% class=column_headings style='padding-right:5px;'><b>New Time<b></td>\n";
                echo "                  <td nowrap width=7% align=left style='padding-left:15px;' class=column_headings>In/Out</td>\n";
                echo "                  <td nowrap style='padding-left:20px;' width=4% align=left class=column_headings>Old Time</td>\n";
                echo "                  <td style='padding-left:25px;' class=column_headings><u>Notes</u></td></tr>\n";

                $newTimeStamp = array();

                // determine who the authenticated user is for audit log

                if (isset($_SESSION['valid_user'])) {
                    $user = $_SESSION['valid_user'];
                } elseif (isset($_SESSION['time_admin_valid_user'])) {
                    $user = $_SESSION['time_admin_valid_user'];
                } else {
                    $user = "";
                }

                // configure current time to insert for audit log
                // $time = time();
                // $time_hour = gmdate('H',$time);
                // $time_min = gmdate('i',$time);
                // $time_sec = gmdate('s',$time);
                // $time_month = gmdate('m',$time);
                // $time_day = gmdate('d',$time);
                // $time_year = gmdate('Y',$time);

                $time_tz_stamp = time ();

                // Escape admin reason for SQL
                if (empty($post_why)) {
                    $post_why = '';
                } else {
                //    $post_why = ereg_replace("[^[:alnum:] \,\.\?-]", "", $post_why);
		                  $post_why = preg_replace('/' . "[^[:alnum:] \,\.\?-]" . '/', "", $post_why);
                }

                for ($x=0;$x<$final_num_rows;$x++) {
                    if ($edit_time_textbox[$x] != '') {
                        $row_color = ($row_count % 2) ? $color1 : $color2;

                        $query = "select * from ".$db_prefix."employees where empfullname = '".$final_username[$x]."'";
                        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

                        while ($row=mysqli_fetch_array($result)) {
                            $currentEmployeeTimeStamp = "".$row['tstamp']."";
                        }

                        // configure timestamp to insert/update //

                        // if ($calendar_style == "euro") {
                        //     $post_date = "$day/$month/$year";
                        //
                        // } elseif ($calendar_style == "amer") {
                        //     $post_date = "$month/$day/$year";
                        // }

                        $newTimeStamp[$x] = strtotime($post_date . " " . $edit_time_textbox[$x]) - $tzo;

                        if ($newTimeStamp[$x] >= $currentEmployeeTimeStamp) { // Only update the current employee's status if it is different.
                            $query2 = "update ".$db_prefix."employees set tstamp = '".$newTimeStamp[$x]."' where empfullname = '".$final_username[$x]."'";
                            $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
                        } else { // Determine if we are changing the last status of the employee.
                            /*
                                Conditions for when we need to update the current status.
                                1) We are working on the last entry date
                                2) We are working on the last entry on that date
                            */
                            $currentDate = date($datefmt, $currentEmployeeTimeStamp);

                            if (($post_date == $currentDate) and ($x == ($final_num_rows - 1))) { // Have we met the conditions?
                                $query2 = "update ".$db_prefix."employees set tstamp = '".$newTimeStamp[$x]."' where empfullname = '".$final_username[$x]."'";
                                $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
                            }
                        }

                        // Update the employee's record
                        $query3 = "update ".$db_prefix."info set timestamp = '".$newTimeStamp[$x]."', ipaddress = '".$connecting_ip."' where ((fullname = '".$final_username[$x]."') and (`inout` = '".$final_inout[$x]."') and (timestamp = '".$final_mysql_timestamp[$x]."') and (notes = '".$final_notes[$x]."'))";
                        $result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);

                        // Add the changes made to the audit table
                        if (strtolower($ip_logging) == "yes") {
                            $query4 = "insert into ".$db_prefix."audit (modified_by_ip, modified_by_user, modified_when, modified_from, modified_to, modified_why, user_modified) values ('".$connecting_ip."', '".$user."', '".$time_tz_stamp."', '".$final_mysql_timestamp[$x]."', '".$newTimeStamp[$x]."', '".$post_why."', '".$final_username[$x]."')";
                            $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);
                        } else {
                            $query4 = "insert into ".$db_prefix."audit (modified_by_user, modified_when, modified_from, modified_to, modified_why, user_modified) values ('".$user."', '".$time_tz_stamp."', '".$final_mysql_timestamp[$x]."', '".$newTimeStamp[$x]."', '".$post_why."', '".$final_username[$x]."')";
                            $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);
                        }

                        echo "                <tr class=display_row><td width=1% align=center class=table_rows bgcolor='$row_color' style='padding-left:5px;padding-right:5px;'> <img src='../images/icons/accept.png' /></td><td nowrap width=1% class=table_rows style='padding-right:5px;' bgcolor='$row_color'> &nbsp;&nbsp;$edit_time_textbox[$x]</td>\n";
                        echo "                  <td nowrap width=7% align=left style='padding-left:15px;' class=table_rows bgcolor='$row_color'>$final_inout[$x]</td>\n";
                        echo "                  <td nowrap style='padding-left:20px;' width=4% align=left class=table_rows bgcolor='$row_color'>$final_time[$x]</td>\n";
                        echo "                  <td style='padding-left:25px;' class=table_rows bgcolor='$row_color'>$final_notes[$x]</td></tr>\n";
                        $row_count++;
                    }
                }
                echo "              <tr><td height=15></td></tr>\n";
                echo "            </table>\n";
                echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
                echo "              <tr><td><a href='timeadmin.php'><img src='../images/buttons/done_button.png' border='0'></td></tr></table>\n";

                echo "</div></div></div></div>";

                include '../theme/templates/endmaincontent.inc';
                include '../footer.php';
                include '../theme/templates/controlsidebar.inc';
                include '../theme/templates/endmain.inc';
                include '../theme/templates/adminfooterscripts.inc';
                exit;
            }
        } else { // Display time editing interface
            // configure timestamp to insert/update //

            // if ($calendar_style == "euro") {
            //     $post_date = "$day/$month/$year";
            //
            // } elseif ($calendar_style == "amer") {
            //     $post_date = "$month/$day/$year";
            // }

            $row_count = '0';
            $timestamp = strtotime($post_date) - @$tzo;
            $calc = $timestamp + 86400 - @$tzo;
            $post_username = stripslashes($post_username);
            $post_displayname = stripslashes($post_displayname);
            $post_username = addslashes($post_username);
            $post_displayname = addslashes($post_displayname);

            $query = "select * from ".$db_prefix."info where (fullname = '".$post_username."') and ((timestamp < '".$calc."') and (timestamp >= '".$timestamp."')) order by timestamp asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

            $username = array();
            $inout = array();
            $notes = array();
            $mysql_timestamp = array();

            while ($row=mysqli_fetch_array($result)) {
                $time_set = '1';
                $username[] = "".$row['fullname']."";
                $inout[] = "".$row['inout']."";
                $notes[] = "".$row['notes']."";
                $mysql_timestamp[] = "".$row['timestamp']."";
            }
            $num_rows = mysqli_num_rows($result);
        }

        $post_username = stripslashes($post_username);
        $post_displayname = stripslashes($post_displayname);

        if (!isset($time_set)) {
            // configure date to display correctly //
            // if ($calendar_style == "euro") {
            //  $post_date = "$day/$month/$year";
            //
            // }

            echo '<div class="row">
                <div class="col-md-6">
                  <div class="box box-info"> ';
            echo '<div class="box-header with-border">
                             <h3 class="box-title"><i class="fa fa-clock-o"></i> Edit Time</h3>
                           </div><div class="box-body">';

            echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> No time for was found in the system for $post_username on $post_date.</td></tr>\n";
            echo "            </table>\n";
            echo "            <br />\n";
            echo "                <input type='hidden' name='date_format' value='$js_datefmt'>\n";
            echo "              <div class='form-group'><label>Username:</label> <div class='input-group'> <input type='hidden' name='post_username' value=\"$post_username\">$post_username\n";
            echo '</div></div>';
            echo "              <div class='form-group'><label>Display Name:</label> <div class='input-group'><input type='hidden' name='post_displayname' value=\"$post_displayname\">$post_displayname\n";
            echo '</div></div>';

            echo "              <div class='form-group'><label>From Date: " .($tmp_datefmt)."</label> <div class='input-group date'><i class='fa fa-calendar'></i><input type='text' maxlength='10' name='post_date' id='datepicker' class='form-control'> &nbsp;*&nbsp;&nbsp; </div></div>\n";
            echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
            echo "                <input type='hidden' name='timefmt_24hr' value=\"$timefmt_24hr\">\n";
            echo "                <input type='hidden' name='timefmt_24hr_text' value=\"$timefmt_24hr_text\">\n";
            echo "                <input type='hidden' name='timefmt_size' value=\"$timefmt_size\">\n";
            echo "                 *&nbsp;required&nbsp;\n";
            echo '<div class="box-footer">
                        <button type="submit" name="submit" value="Edit Time" class="btn btn-info">Edit Time</button>
                        <button type="submit" name="cancel" class="btn btn-default pull-right"><a href="timeadmin.php">Cancel</a></button>
                      </div></form>';

            echo "</div></div></div></div>";


            // echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
            //
            // echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
            // echo "              <tr>\n";
            // echo "                <th class=rightside_heading nowrap halign=left colspan=4><img src='../images/icons/clock_edit.png' />&nbsp;&nbsp;&nbsp;Edit Time </th></tr>\n";
            // echo "              <tr><td height=15></td></tr>\n";
            // echo "                <input type='hidden' name='date_format' value='$js_datefmt'>\n";
            // echo "              <tr><td class=table_rows height=25 style='padding-left:32px;' width=20% nowrap>Username:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'> <input type='hidden' name='post_username' value=\"$post_username\">$post_username</td></tr>\n";
            // echo "              <tr><td class=table_rows height=25 style='padding-left:32px;' width=20% nowrap>Display Name:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'> <input type='hidden' name='post_displayname' value=\"$post_displayname\">$post_displayname</td></tr>\n";
            // echo "              <tr><td class=table_rows height=25 style='padding-left:32px;' width=20% nowrap>Date: ($tmp_datefmt)</td><td colspan=2 width=80% style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'><input type='text' size='10' maxlength='10' name='post_date' value='$post_date'>&nbsp;*&nbsp;&nbsp;&nbsp;<a href=\"#\" onclick=\"cal.select(document.forms['form'].post_date,'post_date_anchor','$js_datefmt'); return false;\" name=\"post_date_anchor\" id=\"post_date_anchor\" style='font-size:11px;color:#27408b;'>Pick Date</a></td><tr>\n";
            // echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
            // echo "              <tr><td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>*&nbsp;required&nbsp;</td></tr>\n";
            // echo "            </table>\n";
            // echo "            <div style=\"position:absolute;visibility:hidden;background-color:#ffffff;layer-background-color:#ffffff;\" id=\"mydiv\" height=200>&nbsp;</div>\n";
            // echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
            // echo "              <tr><td height=40>&nbsp;</td></tr>\n";
            // echo "              <tr><td width=30><input type='image' name='submit' value='Edit Time' align='middle' src='../images/buttons/next_button.png'></td><td><a href='timeadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form>\n";
            include '../theme/templates/endmaincontent.inc';
            include '../footer.php';
            include '../theme/templates/controlsidebar.inc';
            include '../theme/templates/endmain.inc';
            include '../theme/templates/adminfooterscripts.inc';
            exit;
        }
	echo '<div class="row">
    <div class="col-md-8">
      <div class="box box-info"> ';
echo '<div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-clock-o"></i> Please enter a time in the New Time box or boxes you wish to edit below</h3>
               </div><div class="box-body">';
        echo "            <form name='form' action='$self' method='post'>\n";
        echo "            <table class='table'>\n";
        echo "              <tr>\n";

        // configure date to display correctly //

        // if ($calendar_style == "euro") {
        //   $post_date = "$day/$month/$year";
        // }

        echo "                <th class=rightside_heading nowrap halign=left colspan=4><img src='../images/icons/clock_edit.png' />&nbsp;&nbsp;&nbsp;Edit Time for $post_username on $post_date</th></tr>\n";
        echo "              <tr><td height=15></td></tr>\n";

        if (isset($time_set)) {
            echo "                <tr><td nowrap width=1% class=column_headings style='padding-right:5px;padding-left:10px;'><b>New Time<b></td>\n";
            echo "                  <td nowrap width=7% align=left style='padding-left:15px;' class=column_headings>In/Out</td>\n";
            echo "                  <td nowrap style='padding-left:20px;' width=4% align=left class=column_headings>Current Time</td>\n";
            echo "                  <td style='padding-left:25px;' class=column_headings><u>Notes</u></td></tr>\n";

            for ($x=0;$x<$num_rows;$x++) {
                $row_color = ($row_count % 2) ? $color1 : $color2;
                $time[$x] = date("$timefmt", $mysql_timestamp[$x] + $tzo);
                $username[$x] = stripslashes($username[$x]);

//                echo "              <tr class=display_row>\n";
//                echo "                <td nowrap width=1% style='padding-right:5px;padding-left:10px;' class=table_rows><input type='text' size='7' maxlength='$timefmt_size' name='edit_time_textbox[$x]'></td>\n";

echo "              <tr class=display_row>\n";
echo "                <td nowrap width=1% style='padding-right:5px;padding-left:10px;' class=table_rows>";
echo'    <div class="bootstrap-timepicker">
    	                   <div class="form-group">';


		      echo'    	                     <div class="input-group">';
		      echo "                      <input type='text' size='10' class='form-control timepicker' maxlength='$timefmt_size' name='edit_time_textbox[$x]'>";
echo'    	                       <div class="input-group-addon">
    	                         <i class="fa fa-clock-o"></i>
    	                       </div>
    	                     </div>
    	                     <!-- /.input group -->
    	                   </div>
    	                   <!-- /.form group -->
    	                 </div>
    	               ';




                echo "                <td nowrap align=left style='width:7%;padding-left:15px;background-color:$row_color;color:".$row["color"]."'>$inout[$x]</td>\n";
                echo "                <td nowrap align=left style='padding-left:20px;' width=4% bgcolor='$row_color'>$time[$x]</td>\n";
                echo "                <td style='padding-left:25px;' bgcolor='$row_color'>$notes[$x]</td>\n";
                echo "              </tr>\n";
                echo "              <input type='hidden' name='final_username[$x]' value=\"$username[$x]\">\n";
                echo "              <input type='hidden' name='final_inout[$x]' value=\"$inout[$x]\">\n";
                echo "              <input type='hidden' name='final_notes[$x]' value=\"$notes[$x]\">\n";
                echo "              <input type='hidden' name='final_mysql_timestamp[$x]' value=\"$mysql_timestamp[$x]\">\n";
                echo "              <input type='hidden' name='final_time[$x]' value=\"$time[$x]\">\n";
                $row_count++;
            }
            if ($require_time_admin_edit_reason == "yes") {
                echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Reason For Modification:</td><td colspan=2 width=80% style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'><input type='text' size='25' maxlength='250' name='post_why' value='$post_why'>&nbsp;* Required</td></tr>\n";
            } else if ($require_time_admin_edit_reason == "no") {
                echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Reason For Modification:</td><td colspan=2 width=80% style='font-family:Tahoma;font-size:10px;padding-left:20px;'><input type='text' size='25' maxlength='250' name='post_why' value='$post_why'> </td></tr>\n";
            }
            echo "              <tr><td height=15></td></tr>\n";
            $tmp_var = '1';
            echo "            <input type='hidden' name='tmp_var' value=\"$tmp_var\">\n";
            echo "            <input type='hidden' name='post_username' value=\"$post_username\">\n";
            echo "            <input type='hidden' name='post_displayname' value=\"$post_displayname\">\n";
            echo "            <input type='hidden' name='post_date' value=\"$post_date\">\n";
            echo "            <input type='hidden' name='num_rows' value=\"$num_rows\">\n";
            echo "            <input type='hidden' name='calc' value=\"$calc\">\n";
            echo "            <input type='hidden' name='timestamp' value=\"$timestamp\">\n";
            echo "            <input type='hidden' name='get_user' value=\"$get_user\">\n";
            echo "            <input type='hidden' name='final_num_rows' value=\"$num_rows\">\n";
            echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr><td height=40>&nbsp;</td></tr>\n";
            echo "              <tr><td width=30><input type='image' name='submit' value='Edit Time' align='middle' src='../images/buttons/next_button.png'></td><td><a href='timeadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form>\n";
	    echo'</div></div></div></div>';
	    include '../theme/templates/endmaincontent.inc';
            include '../footer.php';
			include '../theme/templates/controlsidebar.inc';
			include '../theme/templates/endmain.inc';
			include '../theme/templates/adminfooterscripts.inc';
            exit;
        }
    }
}
?>
