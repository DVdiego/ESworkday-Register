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
$current_page = "time_punch_out.php";
include '../config.inc.php';


echo "

   <title>
      $title - Punch Out Employees
   </title>";


// Ensure a valid log-in
if (!isset($_SESSION['valid_user'])) {
  include 'header.php';
  include 'topmain.php';

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


include 'header.php';
include 'topmain.php';
include 'leftmain.php';





if ($request == 'POST') { // Validate user input

  include 'header_post_reports.php';
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



if ($request == 'GET' || isset($input_invalid)) { // Output Office/Group Punch Selection Interface
include 'header_get_reports.php';
  echo '<div class="row">
          <div id="float_window" class="col-md-10">
            <div class="box box-info"> ';
  echo '      <div class="box-header with-border">
                   <h3 class="box-title"><i class="fa fa-suitcase"></i> Check out Employees</h3>
              </div>
              <div class="box-body">';

    echo "     <form name='form' action='$self' method='post' onsubmit='return isDate();'>\n";
echo"
                          <div class='row' style='display: inline-flex;'>
                              <div class='form-group' style='margin-left: 17px;'>
                                <label style='padding-top: 35px;'>Punch Out Employees From:</labe>
                              </div>
                              <div class='form-group' style=' margin-left: 40px;'>
                                <div class='radio'>
                                  <label><input type='radio' name='punch_out' value='office'>Selected Office</label>
                                </div>
                                <div class='radio'>
                                  <label><input type='radio' name='punch_out' value='group'>Selected Group</label>
                                </div>
                                <div class='radio'>
                                  <label><input type='radio' name='punch_out' value='everyone'>All Check In Employees</label>
                                </div>
                              </div>
                          </div>";

echo "                <div class='form-group'>
                        <label style='margin-right:38px'>Choose Office: </label>
                          <select name='office_name' class='form-control select2 pull-right' style='width: 50%;' onchange='group_names();'>
                          </select>
                      </div>";

echo "                <div class='form-group'>
                        <label style='margin-right:35px'>Choose Group: </label>
                          <select name='group_name' class='form-control select2 pull-right' style='width: 50%;' onchange='user_names();'>

                          </select>
                      </div>\n";

echo "                <div class='form-group'>
                        <label style='margin-right:10px'>Choose Username: </label>
                          <select name='user_name' class='form-control select2 pull-right' style='width: 50%;'>

                          </select>
                      </div>\n";
echo "               <div class='form-group' style='display: flex;'><label>Status:</label>";

                      // query to populate dropdown with punchlist items //
$query = "select punchitems from ".$db_prefix."punchlist where (in_or_out = 0) ORDER BY punchitems ASC";
$punchlist_result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

echo "                  <select class='form-control' name='post_statusname' style='margin-left: 7px;width: 149px;'>
                            <option value =''>
                              ...
                            </option>";

while ($row = mysqli_fetch_array($punchlist_result)) {
echo "                      <option> ".$row['punchitems']."
                            </option>";
}

echo "                 </select>
                    </div>";
((mysqli_free_result( $punchlist_result ) || (is_object( $punchlist_result ) && (get_class( $punchlist_result ) == "mysqli_result"))) ? true : false);


echo "              <div lass='form-group' style='display: -webkit-box;'>
                      <label style='margin-right:10px'>Fecha:</label>
                        <div class='input-group'>
                            <div class='input-group-addon'>
                              <i class='fa fa-calendar'></i>
                            </div>
                          <input type='date' size='10' maxlength='10' name='post_date' style='color: #444;border: #d2d6de;border-style: solid;border-width: thin;height: 33px;width: 149px;padding-left: 10px;' required>
                          <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                          return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                        </div>
                    </div>\n";

echo"               <div class='bootstrap-timepicker'>
                      <div class='form-group' style='display: flex;'>
                        <label style='margin-right:15px'>Time: </label>";
echo"    	                <div class='input-group'>
                            <div class='input-group-addon'>
                              <i class='fa fa-clock-o'></i>
                            </div>
                            <input type='text' size='10' maxlength='10' class='form-control timepicker' name='post_time' style='width: 150px;' required>";
echo"
                          </div>
                     </div>
                   </div>";

echo "             <div class='form-group'>
                    <label>Notes:</label>
                      <input type='text' name='post_notes' maxlength='250' class='form-control' style=' width: 98%;' >
                  </div>";
echo "						<div class='box-footer'>
                    <button type='button' id='formButtons' onclick='location=\"timeadmin.php\"' class='btn btn-default pull-right' style='margin: 0px 10px 0px 10px;'>
                      <i class='fa fa-ban'></i> Cancelar
                    </button>
										<button id='formButtons' type='submit' name='submit'  class='btn btn-success pull-right'>Siguiente <i class='fa fa-arrow-right'></i></button><a href='usercreate.php'></a>
									</div>";


echo " </form>\n";
echo"           <!-- /.box-body -->
         </div>
         <!-- /.box -->
       </div>
       <!-- /.col (right) -->
     </div>
     <!-- /.row -->";




include '../theme/templates/endmaincontent.inc';
include '../footer.php';
include '../theme/templates/controlsidebar.inc';
include '../theme/templates/endmain.inc';
include '../theme/templates/reportsfooterscripts.inc';
exit;




} else if ($request == 'POST' && empty($post_confirmed_punch)) { // Output Confirmation Punch Interface


    echo '<div class="row">
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';
    echo '      <div class="box-header with-border">
                     <h3 class="box-title"><i class="fa fa-suitcase"></i> Check out Employees</h3>
                </div>
                <div class="box-body">';

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

          echo"           <!-- /.box-body -->
                   </div>
                   <!-- /.box -->
                 </div>
                 <!-- /.col (right) -->
               </div>
               <!-- /.row -->";




          include '../theme/templates/endmaincontent.inc';
          include '../footer.php';
          include '../theme/templates/controlsidebar.inc';
          include '../theme/templates/endmain.inc';
          include '../theme/templates/reportsfooterscripts.inc';
          exit;

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


    if(empty($post_notes)){

      //$admin_note = 'Time Admin Mass Employee Punch Out';
      $admin_note = 'Registro multiple de estado: Salida';
    }else {
      $admin_note = $post_notes;
    }

    while ($row = mysqli_fetch_array($result)) {
        $passes++;

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


      echo '<div class="row">
              <div id="float_window" class="col-md-10">
                <div class="box box-info"> ';
      echo '      <div class="box-header with-border">
                       <h3 class="box-title"><i class="fa fa-suitcase"></i> Check out Employees</h3>
                  </div>
                  <div class="box-body">';
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

        echo"           <!-- /.box-body -->
                 </div>
                 <!-- /.box -->
               </div>
               <!-- /.col (right) -->
             </div>
             <!-- /.row -->";




        include '../theme/templates/endmaincontent.inc';
        include '../footer.php';
        include '../theme/templates/controlsidebar.inc';
        include '../theme/templates/endmain.inc';
        include '../theme/templates/reportsfooterscripts.inc';
        exit;
}

?>
