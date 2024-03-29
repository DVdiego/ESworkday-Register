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
$current_page = "timerpt.php";

include '../config.inc.php';

if ($use_reports_password == "yes") {

    if (!isset($_SESSION['valid_reports_user'])) {

    //echo "<title>$title</title>\n";
    include '../admin/header.php';
    include 'topmain.php';
    include 'leftmain.php';

    echo "<table class='table' width=100% border=0 cellpadding=7 cellspacing=1>\n";
    echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Reports</td></tr>\n";
    echo "  <tr class=right_main_text>\n";
    echo "    <td align=center valign=top scope=row>\n";
    echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
    echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
    echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=reports'><u>here</u></a> to login.</td></tr>\n";
    echo "      </table><br /></td></tr></table>\n"; exit;


    }
}

echo "<title>$title - Daily Time Report</title>\n";

if ($request == 'GET') {

    include 'header_get_reports.php';

    if ($use_reports_password == "yes") {
      include '../admin/topmain.php';
      include 'leftmain.php';
    } else {
      include 'topmain.php';
      include 'leftmain.php';
    }


    echo ' <div class="row">
     <div id="float_window" class="col-md-10">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-book"></i> Daily Time Report</h3>
                </div>
                <div class="box-body">';

    echo "            <form name='form' action='$self' method='post' onsubmit=\"return isFromOrToDate();\">\n";

    echo "              <input type='hidden' name='date_format' value='$js_datefmt'>\n";
    if ($username_dropdown_only == "yes") {

        $query = "select * from ".$db_prefix."employees order by empfullname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        echo "             <div class='form-group'><label> Username: </label>
                          <select name='user_name' class='form-control select2 pull-right' style='width: 50%;'>\n";
        echo "                    <option value ='All'>All</option>\n";

        while ($row=mysqli_fetch_array($result)) {
          $tmp_empfullname = stripslashes("".$row['empfullname']."");
          echo "                    <option>$tmp_empfullname</option>\n";
        }

        echo "                  </select></div> &nbsp;*\n";
        ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
    } else {

    echo "<div class='form-group'><label style='padding-right: 40px;'>Elija una oficina: </label> <select name='office_name' class='form-control select2 pull-right' style='width: 50%;' onchange='group_names();'></select></div>";

    echo "<div class='form-group'><label style='padding-right: 39px;'>Elija un grupo: </label> <select name='group_name' class='form-control select2 pull-right' style='width: 50%;' onchange='user_names();'></select></div>\n";

    echo "             <div class='form-group'><label style='padding-right: 15px;'>Elija un nombre de usuario: </label> <select name='user_name' class='form-control select2 pull-right' style='width: 50%;'></select></div>\n";

    }


    echo "              <div class='form-group' style='display: -webkit-box;'>
                          <label style='padding-right: 10px;'>From Date:</label>
                            <div class='input-group'>
                              <input type='date' size='10' maxlength='10' name='from_date' style='color: #444;border: #d2d6de;border-style: solid;border-width: thin;height: 33px;width: 149px;padding-left: 10px;' required>
                              <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                              return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                            </div>
                        </div>\n";
    echo "              <div class='form-group' style='display: -webkit-box;'>
                          <label style='padding-right: 27px;'>To Date:</label>
                            <div class='input-group'>
                              <input type='date' size='10' maxlength='10' name='to_date' style='color: #444;border: #d2d6de;border-style: solid;border-width: thin;height: 33px;width: 149px;padding-left: 10px;' required>
                              <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                              return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                            </div>
                        </div>\n";
    // echo "              <div class='form-group'><label>From Date:" .($tmp_datefmt)."</label> <div class='input-group date'><i class='fa fa-calendar'></i><input type='text' maxlength='10' name='from_date' id='datepicker' class='form-control'> &nbsp;*&nbsp;&nbsp; </div></div>\n";
    //
    //
    // echo "              <div class='form-group'><label>To Date:" .($tmp_datefmt)."</label> <div class='input-group date'>
    //                     <i class='fa fa-calendar'></i><input type='text' maxlength='10' name='to_date' id='datepicker1' class='form-control'> &nbsp;*&nbsp;&nbsp;
    //                      </div></div>";

    		     /* debug */


    echo "              <div class='form-group'><div class='radio'>
                            <label>Export to CSV? (link to CSV file will be in the top right of the next page)</label></div> \n";
         if (strtolower($export_csv) == "yes") {
         echo "    <div class='radio'><label><input type='radio' name='csv' value='1' checked>&nbsp;Yes</label></div>\n";
         echo "    <div class='radio'><label><input type='radio' name='csv' value='0'> &nbsp;No </label></div></div>\n";
         } else {
         echo "    <div class='radio'><label><input type='radio' name='csv' value='1'> Yes</label></div>   <div class='radio'><label><input type='radio' name='csv' value='0' checked>No</label></div></div>\n";
         }


         if (strtolower($ip_logging) == "yes") {
         echo "              <div class='form-group'><div class='radio'><label>Display connecting ip address information?</label></div>\n";
         if ($display_ip == "yes") {
    echo "              <div class='radio'><label><input type='radio' name='tmp_display_ip' value='1' checked>Yes</label></div> <div class='radio'><label><input type='radio' name='tmp_display_ip' value='0'>No</label></div></div>\n";
         } else {
    echo "              <div class='radio'><label><input type='radio' name='tmp_display_ip' value='1' >Yes </label></div>
                  <div class='radio'><label><input type='radio' name='tmp_display_ip' value='0' checked> No</label></div></div>\n";
         }
         }


    		     /* debug */
    echo '<div class="box-footer">
    <a href="index.php"><button type="submit" name="submit" value="Edit Time" class="btn btn-default pull-right"><i class="fa fa-ban"></i>  Cancel</button></a>
    <button type="submit" class="btn btn-success">Next <i class="fa fa-arrow-right"></i></button></div>
    </div></form>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->

            </div>
            <!-- /.col (right) -->
          </div>
          <!-- /.row -->';




    include '../theme/templates/endmaincontent.inc';
    include '../footer.php';
    include '../theme/templates/controlsidebar.inc';
    include '../theme/templates/endmain.inc';
    include '../theme/templates/reportsfooterscripts.inc';
    exit;

} else {

    include 'header_post_reports.php';

    @$office_name = $_POST['office_name'];
    @$group_name = $_POST['group_name'];
    $fullname = stripslashes($_POST['user_name']);
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    @$tmp_display_ip = $_POST['tmp_display_ip'];
    @$tmp_csv = $_POST['csv'];



    $fullname = addslashes($fullname);

    // begin post error checking //

    if ($fullname != "All") {
        $query = "select * from ".$db_prefix."employees where empfullname = '".$fullname."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        while ($row=mysqli_fetch_array($result)) {
          $empfullname = stripslashes("".$row['empfullname']."");
          $displayname = stripslashes("".$row['displayname']."");
        }
        if (!isset($empfullname)) {echo "Something is fishy here.\n"; exit;}
    }
    $fullname = stripslashes($fullname);

    if (($office_name != "All") && (!empty($office_name))) {
        $query = "select officename from ".$db_prefix."offices where officename = '".$office_name."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        while ($row=mysqli_fetch_array($result)) {
          $getoffice = "".$row['officename']."";
        }
        if (!isset($getoffice)) {echo "Something smells fishy here.\n"; exit;}
    }
    if (($group_name != "All") && (!empty($group_name))) {
        $query = "select groupname from ".$db_prefix."groups where groupname = '".$group_name."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        while ($row=mysqli_fetch_array($result)) {
          $getgroup = "".$row['groupname']."";
        }
        if (!isset($getgroup)) {echo "Something smells fishy here.\n"; exit;}
    }

    if (isset($tmp_display_ip)) {
        if (($tmp_display_ip != '1') && (!empty($tmp_display_ip))) {
            $evil_post = '1';
            if ($use_reports_password == "yes") {
                include '../admin/topmain.php';
                include 'leftmain.php';
            } else {
                include 'topmain.php';
                include 'leftmain.php';
            }
                echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
                echo "  <tr valign=top>\n";
                echo "    <td>\n";
                echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
                echo "        <tr class=right_main_text>\n";
                echo "          <td valign=top>\n";
                echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "              <tr>\n";
                echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                                    Choose \"yes\" or \"no\" to the \"<b>Display connecting ip address information?</b>\" question.</td></tr>\n";
                echo "            </table>\n";
          }
    }elseif (isset($tmp_csv)) {
        if (($tmp_csv != '1') && (!empty($tmp_csv))) {
            $evil_post = '1';
            if ($use_reports_password == "yes") {
                include '../admin/topmain.php';
                include 'leftmain.php';
            } else {
                include 'topmain.php';
                include 'leftmain.php';
            }
                echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
                echo "  <tr valign=top>\n";
                echo "    <td>\n";
                echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
                echo "        <tr class=right_main_text>\n";
                echo "          <td valign=top>\n";
                echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "              <tr>\n";
                echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                                    Choose \"yes\" or \"no\" to the \"<b>Export to CSV?</b>\" question.</td></tr>\n";
                echo "            </table>\n";
            }
    }

    if (!isset($evil_post)) {
        if (empty($from_date)) {
            $evil_post = '1';
            if ($use_reports_password == "yes") {
                include '../admin/topmain.php';
                include 'leftmain.php';
            } else {
                include 'topmain.php';
                include 'leftmain.php';
            }
            echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
            echo "  <tr valign=top>\n";
            echo "    <td>\n";
            echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
            echo "        <tr class=right_main_text>\n";
            echo "          <td valign=top>\n";
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                                A valid From Date is required.</td></tr>\n";
            echo "            </table>\n";
        }
        //elseif (!eregi ("^([0-9]?[0-9])+[-|/|.]+([0-9]?[0-9])+[-|/|.]+(([0-9]{2})|([0-9]{4}))$", $from_date, $date_regs)) {
        elseif (!preg_match('/' . "^([0-9]?[0-9])+[-|\/|.]+([0-9]?[0-9])+[-|\/|.]+(([0-9]{2})|([0-9]{4}))$" . '/i', $from_date, $date_regs)) {
            $evil_post = '1';
            if ($use_reports_password == "yes") {
                include '../admin/topmain.php';
                include 'leftmain.php';
            } else {
                include 'topmain.php';
                include 'leftmain.php';
            }
            echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
            echo "  <tr valign=top>\n";
            echo "    <td>\n";
            echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
            echo "        <tr class=right_main_text>\n";
            echo "          <td valign=top>\n";
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                                A valid From Date is required.</td></tr>\n";
            echo "            </table>\n";

        } else {

            if ($calendar_style == "amer") {
                    if (isset($date_regs)) {$from_month = $date_regs[1]; $from_day = $date_regs[2]; $from_year = $date_regs[3];}
                    if ($from_month > 12 || $from_day > 31) {
                        $evil_post = '1';
                        if ($use_reports_password == "yes") {
                            include '../admin/topmain.php';
                            include 'leftmain.php';
                        } else {
                            include 'topmain.php';
                            include 'leftmain.php';
                        }
                        echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
                        echo "  <tr valign=top>\n";
                        echo "    <td>\n";
                        echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
                        echo "        <tr class=right_main_text>\n";
                        echo "          <td valign=top>\n";
                        echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                        echo "              <tr>\n";
                        echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                                            A valid From Date is required.</td></tr>\n";
                        echo "            </table>\n";
                    }
                }elseif ($calendar_style == "euro") {
                    if (isset($date_regs)) {$from_month = $date_regs[2]; $from_day = $date_regs[1]; $from_year = $date_regs[3];}
                    if ($from_month > 12 || $from_day > 31) {
                        $evil_post = '1';
                        if ($use_reports_password == "yes") {
                            include '../admin/topmain.php';
                            include 'leftmain.php';
                        } else {
                            include 'topmain.php';
                            include 'leftmain.php';
                        }
                          echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
                          echo "  <tr valign=top>\n";
                          echo "    <td>\n";
                          echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
                          echo "        <tr class=right_main_text>\n";
                          echo "          <td valign=top>\n";
                          echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                          echo "              <tr>\n";
                          echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                                              A valid From Date is required.</td></tr>\n";
                          echo "            </table>\n";
                    }
                }
          }
    }

//entra solo si hay algún fallo y evil_post es false
    if (!isset($evil_post)) {
        if (empty($to_date)) {
            $evil_post = '1';
            if ($use_reports_password == "yes") {
                include '../admin/topmain.php';
                include 'leftmain.php';
            } else {
                include 'topmain.php';
                include 'leftmain.php';
            }
              echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
              echo "  <tr valign=top>\n";
              echo "    <td>\n";
              echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
              echo "        <tr class=right_main_text>\n";
              echo "          <td valign=top>\n";
              echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
              echo "              <tr>\n";
              echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                                  A valid To Date is required.</td></tr>\n";
              echo "            </table>\n";
        }
        // elseif (!eregi ("^([0-9]?[0-9])+[-|/|.]+([0-9]?[0-9])+[-|/|.]+(([0-9]{2})|([0-9]{4}))$", $to_date, $date_regs)) {
        elseif (!preg_match('/' . "^([0-9]?[0-9])+[-|\/|.]+([0-9]?[0-9])+[-|\/|.]+(([0-9]{2})|([0-9]{4}))$" . '/i', $to_date, $date_regs)) {
              $evil_post = '1';
              if ($use_reports_password == "yes") {
                  include '../admin/topmain.php';
                  include 'leftmain.php';
              } else {
                  include 'topmain.php';
                  include 'leftmain.php';
              }
              echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
              echo "  <tr valign=top>\n";
              echo "    <td>\n";
              echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
              echo "        <tr class=right_main_text>\n";
              echo "          <td valign=top>\n";
              echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
              echo "              <tr>\n";
              echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                                  A valid To Date is required.</td></tr>\n";
              echo "            </table>\n";

        } else {

            if ($calendar_style == "amer") {
                if (isset($date_regs)) {$to_month = $date_regs[1]; $to_day = $date_regs[2]; $to_year = $date_regs[3];}
                if ($to_month > 12 || $to_day > 31) {
                $evil_post = '1';
                if ($use_reports_password == "yes") {
                include '../admin/topmain.php';
                include 'leftmain.php';
                } else {
                include 'topmain.php';
                include 'leftmain.php';
                }
                echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
                echo "  <tr valign=top>\n";
                echo "    <td>\n";
                echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
                echo "        <tr class=right_main_text>\n";
                echo "          <td valign=top>\n";
                echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "              <tr>\n";
                echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                                    A valid To Date is required.</td></tr>\n";
                echo "            </table>\n";
                }
            }elseif ($calendar_style == "euro") {
                if (isset($date_regs)) {$to_month = $date_regs[2]; $to_day = $date_regs[1]; $to_year = $date_regs[3];}
                    if ($to_month > 12 || $to_day > 31) {
                        $evil_post = '1';
                        if ($use_reports_password == "yes") {
                        include '../admin/topmain.php';
                        include 'leftmain.php';
                        } else {
                        include 'topmain.php';
                        include 'leftmain.php';
                        }
                        echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
                        echo "  <tr valign=top>\n";
                        echo "    <td>\n";
                        echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
                        echo "        <tr class=right_main_text>\n";
                        echo "          <td valign=top>\n";
                        echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                        echo "              <tr>\n";
                        echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                                            A valid To Date is required.</td></tr>\n";
                        echo "            </table>\n";
                    }
            }
      }
    }

    if (isset($evil_post)) {
        echo "            <br />\n";

        echo "            <form name='form' action='$self' method='post' onsubmit=\"return isFromOrToDate();\">\n";
        echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
        echo "              <tr>\n";
        echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/report.png' />&nbsp;&nbsp;&nbsp;Daily
                            Time Report</th></tr>\n";
        echo "              <tr><td height=15></td></tr>\n";
        echo "              <input type='hidden' name='date_format' value='$js_datefmt'>\n";
        if ($username_dropdown_only == "yes") {

        $query = "select * from ".$db_prefix."employees order by empfullname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        echo "              <tr><td>Username:</td><td colspan=2>
                          <select name='user_name'>\n";
        echo "                    <option value ='All'>All</option>\n";

        while ($row=mysqli_fetch_array($result)) {
          $empfullname_tmp = stripslashes("".$row['empfullname']."");
          echo "                    <option>$empfullname_tmp</option>\n";
        }

        echo "                  </select>&nbsp;*</td></tr>\n";
        ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
        } else {

        echo "              <tr><td>Elija una oficina:</td><td colspan=2>
                              <select name='office_name' onchange='group_names();'>\n";
        echo "                      </select></td></tr>\n";
        echo "              <tr><td>Elija un grupo:</td><td colspan=2>
                              <select name='group_name' onfocus='group_names();'>
                                  <option selected>$group_name</option>\n";
        echo "                      </select></td></tr>\n";
        echo "              <tr><td>Elija un nombre de usuario:</td><td colspan=2>
                              <select name='user_name' onfocus='user_names();'>
                                  <option selected>$fullname</option>\n";
        echo "                      </select></td></tr>\n";
        }
        echo "              <tr><td>From Date: ($tmp_datefmt)</td><td>
                              <input type='text' size='10' maxlength='10' name='from_date' value='$from_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
                              <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                              return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" >Pick Date</a></td><tr>\n";
        echo "              <tr><td>To Date: ($tmp_datefmt)</td><td>
                              <input type='text' size='10' maxlength='10' name='to_date' value='$to_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
                              <a href=\"#\" onclick=\"form.to_date.value='';cal.select(document.forms['form'].to_date,'to_date_anchor','$js_datefmt');
                              return false;\" name=\"to_date_anchor\" id=\"to_date_anchor\">Pick Date</a></td><tr>\n";
        echo "              <tr><td colspan=3>*&nbsp;required&nbsp;</td></tr>\n";
        echo "            </table>\n";
        echo "            <div id=\"mydiv\">&nbsp;</div>\n";
        echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr><td>1.&nbsp;&nbsp;&nbsp;Export to CSV? (link to CSV file will be in the top right of
                              the next page)</td></tr>\n";
        if ($tmp_csv == "1") {
        echo "              <tr><td><input type='radio' name='csv' value='1'
                              checked>&nbsp;Yes<input type='radio' name='csv' value='0'>&nbsp;No</td></tr>\n";
        } else {
        echo "              <tr><td><input type='radio' name='csv' value='1' >&nbsp;Yes
                              <input type='radio' name='csv' value='0' checked>&nbsp;No</td></tr>\n";
        }
        if ($display_ip == "yes") {
        echo "              <tr><td>2.&nbsp;&nbsp;&nbsp;Display connecting ip address information?
                              </td></tr>\n";
        if ($tmp_display_ip == "1") {
        echo "              <tr><td><input type='radio' name='tmp_display_ip' value='1'
                              checked>&nbsp;Yes<input type='radio' name='tmp_display_ip' value='0'>&nbsp;No</td></tr>\n";
        } else {
        echo "              <tr><td><input type='radio' name='tmp_display_ip' value='1' >&nbsp;Yes
                              <input type='radio' name='tmp_display_ip' value='0' checked>&nbsp;No</td></tr>\n";
        }
        }
        echo "              <tr><td height=10></td></tr>\n";
        echo "            </table>\n";
        echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr><td width=30><input type='image' name='submit' value='Edit Time' align='middle'
                              src='../images/buttons/next_button.png'></td><td><a href='index.php'><img src='../images/buttons/cancel_button.png'
                              border='0'></td></tr></table></form>\n";
        include '../footer.php';
        include '../theme/templates/controlsidebar.inc';
        include '../theme/templates/endmain.inc';
        include '../theme/templates/adminfooterscripts.inc';
        exit;
    }

    // end post error checking //

    if (!empty($from_date)) {
        // $from_date = "$from_month/$from_day/$from_year";
        $from_date = str_replace("/", "-", $from_date);
        $from_timestamp = strtotime($from_date) - @$tzo;
        $from_date = $_POST['from_date'];
    }



    if (!empty($to_date)) {
        // $to_date = "$to_month/$to_day/$to_year";
        $to_date = str_replace("/", "-", $to_date);
        $to_timestamp = strtotime($to_date) + 86400 - @$tzo;
        $to_date = $_POST['to_date'];
    }


    // $time = time();
    // $rpt_hour = gmdate('H',$time);
    // $rpt_min = gmdate('i',$time);
    // $rpt_sec = gmdate('s',$time);
    // $rpt_month = gmdate('m',$time);
    // $rpt_day = gmdate('d',$time);
    // $rpt_year = gmdate('Y',$time);
    //$rpt_stamp = time ($rpt_hour, $rpt_min, $rpt_sec, $rpt_month, $rpt_day, $rpt_year);
    $rpt_stamp = time();



    $rpt_stamp = $rpt_stamp + @$tzo;
    $rpt_time = date($timefmt, $rpt_stamp);
    $rpt_date = date($datefmt, $rpt_stamp);

    $tmp_fullname = stripslashes($fullname);
    if ((strtolower($user_or_display) == "display") && ($tmp_fullname != "All")) {
    $tmp_fullname = stripslashes($displayname);
    }
    if (($office_name == "All") && ($group_name == "All") && ($tmp_fullname == 'All')) {$tmp_fullname = "Offices: All --> Groups: All --> Users: All";}
    elseif ((empty($office_name)) && (empty($group_name)) && ($tmp_fullname == 'All'))  {$tmp_fullname = "All Users";}
    elseif ((empty($office_name)) && (empty($group_name)) && ($tmp_fullname != 'All'))  {$tmp_fullname = $tmp_fullname;}
    elseif (($office_name != "All") && ($group_name == "All") && ($tmp_fullname == 'All')) {$tmp_fullname = "Office: $office_name --> Groups: All -->
     Users: All";}
    elseif (($office_name != "All") && ($group_name != "All") && ($tmp_fullname == 'All')) {$tmp_fullname = "Office: $office_name --> Group: $group_name -->
     Users: All";}
    $rpt_name="$tmp_fullname";

    echo "            <table width=100% align=center class=misc_items border=0 cellpadding=3 cellspacing=0>\n";
    echo "              <tr><td width=80% style='font-size:9px;color:#000000;padding-left:10px;'>Run on: $rpt_time, $rpt_date</td><td nowrap
                          style='font-size:9px;color:#000000;'>$rpt_name</td></tr>\n";
    echo "               <tr><td width=80%></td><td nowrap style='font-size:9px;color:#000000;'>Date Range: $from_date - $to_date</td></tr>\n";
    if (!empty($tmp_csv)) {
      echo "               <tr class=notprint><td width=80%></td><td nowrap style='font-size:9px;color:#000000;'><a style='color:#27408b;font-size:9px;
                             text-decoration:underline;'
                             href=\"get_csv.php?rpt=timerpt&display_ip=$tmp_display_ip&csv=$tmp_csv&office=$office_name&group=$group_name&fullname=$fullname&from=$from_timestamp&to=$to_timestamp&tzo=$tzo\">Download CSV File</a></td></tr>\n";
    }
    echo "            </table>\n";

    $employees_cnt = 0;
    $employees_empfullname = array();
    $employees_displayname = array();
    $row_count = 0;
    $page_count = 0;

    // retrieve a list of users //

    $fullname = addslashes($fullname);

    if (strtolower($user_or_display) == "display") {

        if (($office_name == "All") && ($group_name == "All") && ($fullname == "All")) {

            $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL order by displayname asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname == 'All')) {

            $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL order by displayname asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname != 'All')) {

            $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL and empfullname = '".$fullname."' order by
                      displayname asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        } elseif (($office_name != "All") && ($group_name == "All") && ($fullname == "All")) {

            $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and tstamp IS NOT NULL order by
                      displayname asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        } elseif (($office_name != "All") && ($group_name != "All") && ($fullname == "All")) {

            $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and groups = '".$group_name."'  and
                      tstamp IS NOT NULL order by displayname asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        } elseif (($office_name != "All") && ($group_name != "All") && ($fullname != "All")) {

            $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and groups = '".$group_name."' and
                      empfullname = '".$fullname."' and tstamp IS NOT NULL order by displayname asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        }

    } else {

        if (($office_name == "All") && ($group_name == "All") && ($fullname == "All")) {

            $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL order by empfullname asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname == 'All')) {

            $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL order by empfullname asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname != 'All')) {

            $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL and empfullname = '".$fullname."' order by
                      empfullname asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        } elseif (($office_name != "All") && ($group_name == "All") && ($fullname == "All")) {

            $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and tstamp IS NOT NULL order by
                      empfullname asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        } elseif (($office_name != "All") && ($group_name != "All") && ($fullname == "All")) {

            $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and groups = '".$group_name."'  and
                      tstamp IS NOT NULL order by empfullname asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        } elseif (($office_name != "All") && ($group_name != "All") && ($fullname != "All")) {

            $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and groups = '".$group_name."' and
                      empfullname = '".$fullname."' and tstamp IS NOT NULL order by empfullname asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        }
    }

    while ($row=mysqli_fetch_array($result)) {

      $employees_empfullname[] = stripslashes("".$row['empfullname']."");
      $employees_displayname[] = stripslashes("".$row['displayname']."");
      $employees_cnt++;
    }



    for ($x=0;$x<$employees_cnt;$x++) {

        $fullname = stripslashes($fullname);
        if (($employees_empfullname[$x] == $fullname) || ($fullname == "All")) {

            $row_color = $color2; // Initial row color

            $employees_empfullname[$x] = addslashes($employees_empfullname[$x]);
            $employees_displayname[$x] = addslashes($employees_displayname[$x]);

            $query = "select ".$db_prefix."info.fullname, ".$db_prefix."info.`inout`, ".$db_prefix."info.timestamp, ".$db_prefix."info.notes,
                      ".$db_prefix."info.ipaddress, ".$db_prefix."punchlist.in_or_out, ".$db_prefix."punchlist.punchitems, ".$db_prefix."punchlist.color, ".$db_prefix."employees.empDNI
                      from ".$db_prefix."info, ".$db_prefix."punchlist, ".$db_prefix."employees
                      where ".$db_prefix."info.fullname = '".$employees_empfullname[$x]."' and ".$db_prefix."info.timestamp >= '".$from_timestamp."'
                      and ".$db_prefix."info.timestamp <= '".$to_timestamp."' and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems
                      and ".$db_prefix."employees.empfullname = '".$employees_empfullname[$x]."' and ".$db_prefix."employees.empfullname <> '".$root."'
                      order by ".$db_prefix."info.timestamp asc";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);


            while ($row=mysqli_fetch_array($result)) {

                $display_stamp = "".$row["timestamp"]."";
                $time = date($timefmt, $display_stamp);
                $date = date($datefmt, $display_stamp);

                if ($row_count == 0) {
                    if ($page_count == 0) {

                        echo "            <table class=misc_items width=100% border=0 cellpadding=2 cellspacing=0>\n";
                        echo "              <tr class=notprint>\n";
                        echo "                <td nowrap width=30% align=left style='padding-left:10px;padding-right:10px;font-size:11px;color:#27408b;
                        text-decoration:underline;'>Name</td>\n";
                        echo "                <td nowrap width=10% align=left style='padding-left:10px;font-size:11px;color:#27408b;
                        text-decoration:underline;'>DNI</td>\n";
                        echo "                <td nowrap width=10% align=left style='padding-left:10px;font-size:11px;color:#27408b;
                        text-decoration:underline;'>In/Out</td>\n";
                        echo "                <td nowrap width=5% align=right style='padding-right:10px;font-size:11px;color:#27408b;
                        text-decoration:underline;'>Time</td>\n";
                        echo "                <td nowrap width=5% align=right style='padding-left:10px;font-size:11px;color:#27408b;
                        text-decoration:underline;'>Date</td>\n";
                        if ($tmp_display_ip == "1") {
                            echo "                <td nowrap width=15% align=left style='padding-left:10px;font-size:11px;color:#27408b;
                            text-decoration:underline;'>Originating IP</td>\n";
                        }
                        echo "                <td style='padding-left:10px;'><a style='font-size:11px;color:#27408b;text-decoration:underline;'>Notes</td>\n";

                    } else {

                        // display report name and page number of printed report above the column headings of each printed page //

                        $temp_page_count = $page_count + 1;
                        echo "              <tr><td colspan=2 class=notdisplay style='font-size:9px;color:#000000;padding-left:10px;'>Run on: $rpt_time,
                          $rpt_date (page $temp_page_count)</td><td class=notdisplay nowrap style='font-size:9px;color:#000000;'
                          align=right colspan=4>$rpt_name</td></tr>\n";
                        echo "              <tr><td class=notdisplay align=right colspan=6 nowrap style='font-size:9px;color:#000000;'>
                          Date Range: $from_date - $to_date</td></tr>\n";
                    }
                    // echo "              <tr class=notdisplay>\n";
                    // echo "                <td nowrap width=20% align=left style='padding-left:10px;padding-right:10px;font-size:11px;color:#27408b;
                    //     text-decoration:underline;'>Name</td>\n";
                    // echo "                <td nowrap width=7% align=left
                    //     style='padding-left:10px;font-size:11px;color:#27408b;text-decoration:underline;'>In/Out</td>\n";
                    // echo "                <td nowrap width=5% align=right
                    //     style='padding-right:10px;font-size:11px;color:#27408b;text-decoration:underline;'>Time</td>\n";
                    // echo "                <td nowrap width=5% align=right
                    //     style='padding-left:10px;font-size:11px;color:#27408b;text-decoration:underline;'>Date</td>\n";
                    // if ($tmp_display_ip == "1") {
                    //     echo "                <td nowrap width=15% align=left
                    //         style='padding-left:10px;font-size:11px;color:#27408b;text-decoration:underline;'>Originating IP</td>\n";
                    // }
                    // echo "                <td style='padding-left:10px;'><a style='font-size:11px;color:#27408b;text-decoration:underline;'>Notes</td>\n";
                    // echo "              </tr>\n";
                }

                // begin alternating row colors //

                $row_color = ($row_count % 2) ? $color1 : $color2;

                // display the query results //

                $display_stamp = $display_stamp + @$tzo;
                $time = date($timefmt, $display_stamp);
                $date = date($datefmt, $display_stamp);

                if (strtolower($user_or_display) == "display") {
                    echo stripslashes("              <tr class=display_row><td nowrap width=30% bgcolor='$row_color' style='padding-left:10px;
                          padding-right:10px;'>$employees_displayname[$x]</td>\n");
                } else {
                    echo stripslashes("              <tr class=display_row><td nowrap width=30% bgcolor='$row_color' style='padding-left:10px;
                          padding-right:10px;'>$employees_empfullname[$x]</td>\n");
                }
                echo "                <td nowrap align=left width=10% style='padding-left:10px;'>".$row["empDNI"]."</td>\n";
                echo "                <td nowrap align=left width=10% style='background-color:$row_color;color:".$row["color"].";
                      padding-left:10px;'>".$row["inout"]."</td>\n";
                echo "                <td nowrap align=right width=5% bgcolor='$row_color' style='padding-right:10px;'>".$time."</td>\n";
                echo "                <td nowrap align=right width=5% bgcolor='$row_color' style='padding-left:10px;'>".$date."</td>\n";
                if ($tmp_display_ip == "1") {
                    echo "                <td nowrap align=left width=15% style='background-color:$row_color;color:".$row["color"].";
                          padding-left:10px;'>".$row["ipaddress"]."</td>\n";
                }
                echo stripslashes("                <td bgcolor='$row_color' style='padding-left:10px;'>".$row["notes"]."</td>\n");

                if(!isset($tmp_dni)){

                  $tmp_dni = "".$row["empDNI"]."";
                }


                if($tmp_dni != "".$row["empDNI"].""){
                  echo "<hr class='separator-reports'>\n";

                }
                echo "              </tr>\n";


                if(!isset($tmp_dni)){

                  $tmp_dni = "".$row["empDNI"]."";
                  echo " nuevo tem: $tmp_dni";
                }


                if($tmp_dni != "".$row["empDNI"].""){
                  echo "<tr>";
                  echo "<hr class='separator-reports'>\n";
                  echo " tem $tmp_dni ---- ".$row["empDNI"];
                  echo "<br>";
                  echo "</tr>";

                }

                $tmp_dni = "".$row["empDNI"]."";
                $row_count++;

                // output 40 rows per printed page //

                if ($row_count == 40) {
                    echo "              <tr style=\"page-break-before:always;\"></tr>\n";
                    $row_count = 0;
                    $page_count++;
                }
            }
        }
    }
}
exit;
?>
