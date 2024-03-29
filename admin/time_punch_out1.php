<?php
/***************************************************************************
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

/**
 * This module will punch out all employees currently punch in, or only
 * punch out those employees of specific offices or groups. Only a user
 * who has the time admin permissions can access this module.
 */

session_start();

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
include 'leftmain.php';

include '../scripts/dropdown_get.php';
echo "
<body onload='office_names();'>
   <title>
      $title - Punch Out Employees
   </title>";


// Ensure a valid log-in
if ((!isset($_SESSION['valid_user'])) && (!isset($_SESSION['time_admin_valid_user']))) {
    echo "
      <table width=100% border=0 cellpadding=7 cellspacing=1>
        <tr class=right_main_text>
           <td height=10 align=center valign=top scope=row class=title_underline>
              WorkTime Control Administration
           </td>
        </tr>
        <tr class=right_main_text>
          <td align=center valign=top scope=row>
            <table width=200 border=0 cellpadding=5 cellspacing=0>
              <tr class=right_main_text>
                 <td align=center>
                    You are not presently logged in, or do not have permission to view this page.
                 </td>
              </tr>
              <tr class=right_main_text>
                 <td align=center>
                    Click
                    <a class=admin_headings href='../login.php?login_action=admin'>
                       <u>here</u>
                    </a> to login.
                 </td>
              </tr>
            </table>
            <br />
          </td>
        </tr>
      </table>";
    exit;
}

// Retrieve and setup time information
if (($timefmt == "G:i") || ($timefmt == "H:i")) {
  $timefmt_24hr = '1';
  $timefmt_24hr_text = '24 hr format';
  $timefmt_size = '5';
} else {
  $timefmt_24hr = '0';
  $timefmt_24hr_text = '12 hr format';
  $timefmt_size = '8';
}



if ($request == 'POST') { // Validate user input
    $post_punch_out = $_POST['punch_out'];
    $post_office = $_POST['office_name'];
    $post_group = $_POST['group_name'];

    //$post_confirmed_punch = $_POST['confirmed_punch'];
  // if(empty($post_confirmed_punch)){
  //   $post_confirmed_punch = True;
  // }
    $post_confirmed_punch = True;
    $post_date = $_POST['post_date'];
    $post_time = $_POST['post_time'];
    $post_statusname = $_POST['post_statusname'];


    // Begin Input Validation
    if (($post_punch_out == "office") && empty($post_office)) { // Ensure an office has been selected when punching out an office.
        echo "<td valign=top>
                  <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                     <tr>
                        <td class=table_rows_red>
                           Office not selected for an office punch out.
                        </td>
                     </tr>
               </td>
            </tr>";
        $input_invalid = True;
    } else if (($post_punch_out == "group") && empty($post_group)) { // Ensure a group has been selected when punching out a group
        echo "<td valign=top>
                  <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                     <tr>
                        <td class=table_rows_red>
                           Group not selected for a group punch out.
                        </td>
                     </tr>
               </td>
            </tr>";
        $input_invalid = True;
    } else if (empty($post_punch_out)) { // Ensure a punch selection has been made
        echo "<td valign=top>
                  <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                     <tr>
                        <td class=table_rows_red>
                           Punch employee selection not made.
                        </td>
                     </tr>
               </td>
            </tr>";
        $input_invalid = True;
    } else if (empty($post_statusname) || ($post_statusname == "1")) { // Ensure an out status is selected
        echo "<td valign=top>
                  <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                     <tr>
                        <td class=table_rows_red>
                           Punch status selection not made.
                        </td>
                     </tr>
               </td>
            </tr>";
        $input_invalid = True;
    }

    //if ((empty($post_date)) || (empty($post_time)) ||  (!eregi("^([0-9]{1,2})[-,/,.]([0-9]{1,2})[-,/,.](([0-9]{2})|([0-9]{4}))$", $post_date))) {
    if ((empty($post_date)) || (empty($post_time))
    //|| (!preg_match("/^([0-9]{1,2})[\-\/\.]([0-9]{1,2})[\-\/\.](([0-9]{2})|([0-9]{4}))$/i", $post_date))
    ){

          $input_invalid = True;
          if (empty($post_date)) {
              echo "
                 <td valign=top>
                    <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                       <tr>
                          <td class=table_rows_red>
                             A valid date is required.
                          </td>
                       </tr>
                 </td>
              </tr>";
          } elseif (empty($post_time)) {
              echo "
                 <td valign=top>
                    <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                       <tr>
                          <td class=table_rows_red>
                             A valid time is required.
                          </td>
                       </tr>
                 </td>
              </tr>";
          }
          // elseif
//	(!eregi ("^([0-9]{1,2})[-,/,.]([0-9]{1,2})[-,/,.](([0-9]{2})|([0-9]{4}))$", $post_date)) {
        //   (!preg_match("/^([0-9]{1,2})[\-\/\.]([0-9]{1,2})[\-\/\.](([0-9]{2})|([0-9]{4}))$/i", $post_date)) {
        //
        //     echo "
        //        <td valign=top>
        //           <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
        //              <tr>
        //                 <td class=table_rows_red>
        //                    A valid date is required.
        //                 </td>
        //              </tr>
        //        </td>
        //     </tr>";
        // }
    } elseif ($timefmt_24hr == '0') {
//        if ((!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])+([a|p]+m)$", $post_time, $time_regs)) && (!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])+( [a|p]+m)$", $post_time, $time_regs))) {
          if ((!preg_match("/^([0-9]?[0-9])+:+([0-9]+[0-9])+([a|p]+m)$/i", $post_time, $time_regs)) && (!preg_match("/^([0-9]?[0-9])+:+([0-9]+[0-9])+( [a|p]+m)$/i", $post_time, $time_regs))) {

 // if ((!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])+([a|p]+m)$", $post_time, $time_regs)) && (!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])+( [a|p]+m)$", $post_time, $time_regs))) {
            $input_invalid = True;
            echo "
               <td valign=top>
                  <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                     <tr>
                        <td class=table_rows_red>
                           A valid time is required.
                        </td>
                     </tr>
               </td>
            </tr>";
        } else {
            if (isset($time_regs)) {
                $h = $time_regs[1];
                $m = $time_regs[2];
            }
            $h = $time_regs[1]; $m = $time_regs[2];
            if (($h > 12) || ($m > 59)) {
                $input_invalid = True;
                echo "
               <td valign=top>
                  <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                     <tr>
                        <td class=table_rows_red>
                           A valid time is required.
                        </td>
                     </tr>
               </td>
            </tr>";
            }
        }
    } elseif ($timefmt_24hr == '1') {
        // if (!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])$", $post_time, $time_regs)) {
      if (!preg_match("/^([0-9]?[0-9])+:+([0-9]+[0-9])$/i", $post_time, $time_regs)) {
            $input_invalid = True;
            echo "
               <td valign=top>
                  <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                     <tr>
                        <td class=table_rows_red>
                           A valid time is required.
                        </td>
                     </tr>
               </td>
            </tr>";
        } else {
            if (isset($time_regs)) {
                $h = $time_regs[1];
                $m = $time_regs[2];
            }
            $h = $time_regs[1]; $m = $time_regs[2];
            if (($h > 24) || ($m > 59)) {
                $input_invalid = True;
                echo "
               <td valign=top>
                  <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                     <tr>
                        <td class=table_rows_red>
                           A valid time is required.
                        </td>
                     </tr>
               </td>
            </tr>";
            }
        }
    }

    //if (eregi ("^([0-9]{1,2})[-,/,.]([0-9]{1,2})[-,/,.](([0-9]{2})|([0-9]{4}))$", $post_date, $date_regs)) {
    // if (preg_match("/^([0-9]{1,2})[-\,\/,.]([0-9]{1,2})[-\,\/,.](([0-9]{2})|([0-9]{4}))$/i", $post_date, $date_regs)) {
    //
    //     if ($calendar_style == "amer") {
    //         if (isset($date_regs)) { // Format the date to American style
    //             $month = $date_regs[1];
    //             $day = $date_regs[2];
    //             $year = $date_regs[3];
    //         }
    //
    //         if ($month > 12 || $day > 31) { // Ensure valid date
    //             $input_invalid = True;
    //             if (!isset($evil_post)) {
    //                 echo "
    //            <td valign=top>
    //               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
    //                  <tr>
    //                     <td class=table_rows_red>
    //                        A valid date is required.
    //                     </td>
    //                  </tr>
    //            </td>
    //            </tr>";
    //             }
    //         }
    //     } elseif ($calendar_style == "euro") {
    //         if (isset($date_regs)) { // Format the date to European style
    //             $month = $date_regs[2];
    //             $day = $date_regs[1];
    //             $year = $date_regs[3];
    //         }
    //         if ($month > 12 || $day > 31) { // Ensure valid date
    //             $input_invalid = True;
    //             if (!isset($evil_post)) {
    //                 echo "
    //            <td valign=top>
    //               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
    //                  <tr>
    //                     <td class=table_rows_red>
    //                        A valid date is required.
    //                     </td>
    //                  </tr>
    //            </td>
    //         </tr>";
    //             }
    //         }
    //     }
    // }

    // End Input Validation
    if (empty($input_invalid)) { // Create message to display for confirmation and successful punch
        if ($post_punch_out == "office") {
            $post_group = ""; // Ensure they are not set
            $punch_message = "all employees in office $post_office on $post_date at $post_time to a status of $post_statusname.";
        } else if ($post_punch_out == "group") {
            $punch_message = "all employees in office $post_office of group $post_group on $post_date at $post_time to a status of $post_statusname.";
        } else {
            $post_office = $post_group = ""; // Ensure they are not set
            $punch_message = "all employees on $post_date at $post_time to a status of $post_statusname.";
        }
    }
}


echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info"> ';
echo '      <div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-suitcase"></i> Check out Employees</h3>
            </div>
            <div class="box-body">';
if ($request == 'GET' || isset($input_invalid)) { // Output Office/Group Punch Selection Interface


    echo "     <form name='form' action='$self' method='post' onsubmit='return isDate();'>\n";
    echo "            <table align=center class=table width=100% border=0 cellpadding=3 cellspacing=0>\n";
    echo "                <tr class=right_main_text>
                           <td class=table_rows height=25 width=25% style='padding-left:32px;' nowrap>
                              Punch Out Employees From:
                              <span style='padding-left:20px;'>
                                 *
                              </span>
                           </td>

                          <td>
                          <div class='radio'>
                            <label><input type='radio' name='punch_out' value='office'>Selected Office</label>
                          </div>
                          <div class='radio'>
                            <label><input type='radio' name='punch_out' value='group'>Selected Group</label>
                          </div>
                          <div class='radio'>
                            <label><input type='radio' name='punch_out' value='everyone'>All Check In Employees</label>
                          </div>
                          </td>

                        </tr>
                        <tr class=right_main_text>
                           <td align=right class=table_rows height=25 width=25% style='padding-left:32px;' nowrap>
                              Elija una oficina:
                           </td>
                           <td class=table_rows height=25 width=25% style='padding-left:32px;' nowrap>
                              <select name='office_name' onchange='group_names();'>
                              </select>
                           </td>
                        </tr>
                        <tr>
                           <td height=11> </td>
                        </tr>
                        <tr class=right_main_text>
                           <td align=right class=table_rows height=25 width=25% style='padding-left:32px;' nowrap>
                              Elija un grupo:
                           </td>
                           <td class=table_rows height=25 width=25% style='padding-left:32px;' nowrap>
                              <select name='group_name' onchange='user_names();'>
                              </select>
                           </td>
                        </tr>
                        <tr>
                           <td height=11> </td>
                        </tr>
                        <tr>
                           <td align=right class=table_rows height=25 width=25% style='padding-left:32px;' nowrap>
                              Punch Out Status:
                           </td>
                           <td colspan=2 width=80% style='padding-left:20px;'>
                              <select name='post_statusname'>
                                 <option value ='1'>
                                    Choose One
                                 </option>";
    // Retrieve Punch Status'
    $query = "SELECT * FROM ".$db_prefix."punchlist WHERE (in_or_out = 0) ORDER BY punchitems ASC";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    while ($row = mysqli_fetch_array($result)) {
        echo "                        <option>".$row['punchitems']."</option>";
    }
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
    echo "
                              </select>
                              &nbsp; *
                           </td>
                        </tr>
                        <tr>
                           <td height=11> </td>
                        </tr>
                        <input type='hidden' name='date_format' value='$js_datefmt'>";
echo "<tr>";
echo "    <div class='form-group'>
            <label>Fecha:</label>
            <input type='date' size='10' maxlength='10' name='post_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
            <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
            return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
             </div>";

echo "</tr>";
echo "<tr><td>";
echo'               <div class="bootstrap-timepicker">
 	                    <div class="form-group">
 	                      <label>Hora Registro: ('.$timefmt_24hr_text.')</label>';

echo'    	              <div class="input-group">
 	                         <input type="text" size="10" maxlength="10" class="form-control timepicker" name="post_time">';
echo'   	                      <div class="input-group-addon">
 	                               <i class="fa fa-clock-o"></i>
	                              </div>
 	                      </div>
 	                    </div>
 	                 </div>';
echo "</tr></td>";
echo "                  <input type='hidden' name='timefmt_24hr' value='$timefmt_24hr'>
                        <input type='hidden' name='timefmt_24hr_text' value='$timefmt_24hr_text'>
                        <input type='hidden' name='timefmt_size' value=\"$timefmt_size\">
                        <tr>
                           <td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>
                              * &nbsp; required &nbsp;
                           </td>
                        </tr>

                        <tr>
                           <td>
                              <input type='image' name='submit' value='Edit Time' src='../images/buttons/next_button.png'>
                              <a href='timeadmin.php'>
                                 <img src='../images/buttons/cancel_button.png' border='0'>
                              </a>
                           </td>
                        </tr>
                      </table>
                     </form>";


} else if ($request == 'POST' && empty($post_confirmed_punch)) { // Output Confirmation Punch Interface

    echo "<form name='form' action='$self' method='post' onsubmit=\"return isDate();\">\n";
    echo "   <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
    echo "           <tr>
                   <td height=11> </td>
               </tr>
               <tr class=right_main_text>
                  <td valign=top>
                       Click next to punch $punch_message
                  </td>
               </tr>
               <tr>
                  <td height=11> </td>
               </tr>
               <tr>
                  <td>
                     <input type='image' name='submit' value='Edit Time' src='../images/buttons/next_button.png'>
                     <a href='time_punch_out.php'>
                        <img src='../images/buttons/cancel_button.png' border='0'>
                     </a>
                  </td>
               </tr>
               <input type='hidden' name='punch_out' value='$post_punch_out'>
               <input type='hidden' name='office_name' value='$post_office'>
               <input type='hidden' name='group_name' value='$post_group'>
               <input type='hidden' name='post_date' value='$post_date'>
               <input type='hidden' name='post_time' value='$post_time'>
               <input type='hidden' name='post_statusname' value='$post_statusname'>
               <input type='hidden' name='confirmed_punch' value='True'>

            </table>
          </form> ";

} else { // Complete Punch Out Request

    // Determine who the authenticated user is for audit log
    if (isset($_SESSION['valid_user'])) {
        $user = $_SESSION['valid_user'];
    } elseif (isset($_SESSION['time_admin_valid_user'])) {
        $user = $_SESSION['time_admin_valid_user'];
    } else {
        $user = "";
    }
    // Create selected time stamp
    // if ($calendar_style == "euro") {
    //     $post_date = "$day/$month/$year";
    // } elseif ($calendar_style == "amer") {
    //     $post_date = "$month/$day/$year";
    // }
    $timestamp = strtotime($post_date . " " . $post_time) - $tzo;

    // Build an SQL statment of all the in status' available.
    $query = "SELECT * FROM ".$db_prefix."punchlist WHERE (in_or_out = 1) ORDER BY punchitems ASC";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    $sql_in_status_statement = "";
    $number_of_in_statuss = mysqli_num_rows($result);
    $count = 1;
    while ($row = mysqli_fetch_array($result)) {
        $sql_in_status_statement .= "(info.inout = '".$row['punchitems']."')";
        if ($count < $number_of_in_statuss) {
            $sql_in_status_statement .= " OR ";
        }
        $count++;
    }
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

    // Get employee's punched in
    if ((! empty($post_office)) && (! empty($post_group))) {
        $query = "SELECT DISTINCT employees.empfullname FROM employees, info WHERE ((employees.tstamp = info.timestamp) AND ($sql_in_status_statement) AND (employees.office = '".$post_office."') AND (employees.groups = '".$post_group."'))";
    } else if ((! empty($post_office)) && empty($post_group)) {
        $query = "SELECT DISTINCT employees.empfullname FROM employees, info WHERE ((employees.tstamp = info.timestamp) AND ($sql_in_status_statement) AND (employees.office = '".$post_office."'))";
    } else {
        $query = "SELECT DISTINCT employees.empfullname FROM employees, info WHERE ((employees.tstamp = info.timestamp) AND ($sql_in_status_statement))";
    }
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    // Punch out each employee selected
    $passes = 0;
    $admin_note = 'Time Admin Mass Employee Punch Out';
    while ($row = mysqli_fetch_array($result)) {
        $passes++;
        // Configure the current time to insert for audit log
        // $time = time();
        // $time_hour = gmdate('H', $time);
        // $time_min = gmdate('i', $time);
        // $time_sec = gmdate('s', $time) + $passes; // Ensures audit time stamps vary
        // $time_month = gmdate('m', $time);
        // $time_day = gmdate('d', $time);
        // $time_year = gmdate('Y', $time);
        // $time_tz_stamp = time($time_hour, $time_min, $time_sec, $time_month, $time_day, $time_year);
        setlocale(LC_ALL, 'es_ES.UTF-8');
        $time_tz_stamp = time();
        $employee = "".$row['empfullname']."";
        if (strtolower($ip_logging) == "yes") {
            $query = "INSERT INTO ".$db_prefix."info (fullname, `inout`, timestamp, notes, ipaddress) VALUES ('".$employee."', '".$post_statusname."', '".$timestamp."', '".$admin_note."', '".$connecting_ip."')";
            mysqli_query($GLOBALS["___mysqli_ston"], $query);
            $query = "INSERT INTO ".$db_prefix."audit (modified_by_ip, modified_by_user, modified_when, modified_from, modified_to, modified_why, user_modified) VALUES ('".$connecting_ip."', '".$user."', '".$time_tz_stamp."', '0', '".$timestamp."', '".$admin_note."', '".$employee."')";
            mysqli_query($GLOBALS["___mysqli_ston"], $query);
        } else {
            $query = "INSERT INTO ".$db_prefix."info (fullname, `inout`, timestamp, notes) VALUES ('".$employee."', '".$post_statusname."', '".$timestamp."', '".$admin_note."')";
            mysqli_query($GLOBALS["___mysqli_ston"], $query);
            $query = "INSERT INTO ".$db_prefix."audit (modified_by_user, modified_when, modified_from, modified_to, modified_why, user_modified) VALUES ('".$user."', '".$time_tz_stamp."', '0', '".$timestamp."', '".$admin_note."', '".$employee."')";
            mysqli_query($GLOBALS["___mysqli_ston"], $query);
        }
        // Determine if we need to update the employee's current status
        $query = "SELECT * FROM ".$db_prefix."employees WHERE empfullname = '".$employee."'";
        $result_status = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        while ($status_row = mysqli_fetch_array($result_status)) {
            $employees_table_timestamp = "".$status_row['tstamp']."";
        }
        ((mysqli_free_result($result_status) || (is_object($result_status) && (get_class($result_status) == "mysqli_result"))) ? true : false);
        if ($timestamp > $employees_table_timestamp) {
            $query = "UPDATE ".$db_prefix."employees SET tstamp = '".$timestamp."' WHERE empfullname = '".$employee."'";
            mysqli_query($GLOBALS["___mysqli_ston"], $query);
        }
    }
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);


    echo "<form name='form' action='$self' method='post'>\n";
    echo "   <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
    echo "
               <tr>
                   <td height=11> </td>
               </tr>
               <tr class=right_main_text>
                  <td valign=top>
                       Successful punch of $punch_message
                  </td>
               </tr>
               <tr>
                   <td height=11> </td>
               </tr>
               <input type='hidden' name='punch_out' value='$post_punch_out'>
               <input type='hidden' name='office_name' value='$post_office'>
               <input type='hidden' name='group_name' value='$post_group'>
               <input type='hidden' name='post_date' value='$post_date'>
               <input type='hidden' name='post_time' value='$post_time'>
               <input type='hidden' name='post_statusname' value='$post_statusname'>
               <input type='hidden' name='confirmed_punch' value='True'>
           </table>
         </form>

         <table width=60% border=0 cellpadding=0 cellspacing=3>
            <tr>
               <td height=20 align=left>
                  &nbsp;
               </td>
            </tr>
            <tr>
               <td>
                  <a href='timeadmin.php'>
                    <img src='../images/buttons/done_button.png' border='0'>
                  </a>
               </td>
            </tr>
        </table>";
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
?>
