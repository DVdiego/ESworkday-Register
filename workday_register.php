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

/**
* This module creates the interface for an employee to punch their status.
*/

include 'config.inc.php';

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

// set cookie if 'Remember Me?' checkbox is checked, or reset cookie if 'Reset Cookie?' is checked //
if ($request == 'POST') {
    @$remember_me = $_POST['remember_me'];
    @$reset_cookie = $_POST['reset_cookie'];
    @$fullname = stripslashes($_POST['left_fullname']);
    @$displayname = stripslashes($_POST['left_displayname']);
    if ((isset($remember_me)) && ($remember_me != '1')) {
        echo "Something is fishy here.";
        exit;
    }
    if ((isset($reset_cookie)) && ($reset_cookie != '1')) {
        echo "Something is fishy here.";
        exit;
    }



    if (isset($remember_me)) {
        if ($show_display_name == "yes") {
            setcookie("remember_me", stripslashes($displayname), time() + (60 * 60 * 24 * 365 * 2));
        } elseif ($show_display_name == "no") {
            setcookie("remember_me", stripslashes($fullname), time() + (60 * 60 * 24* 365 * 2));
        }
    } elseif (isset($reset_cookie)) {
        setcookie("remember_me", "", time() - 3600);
    }
    ob_end_flush();
}






include './theme/templates/leftnavstart.inc';

echo "<div class='user-panel'>
                <img id='logo-leftmain' src='images/logos/logo.png' >
      </div>";
//user moved here from topmain
if (isset($_SESSION['valid_user'])) {
$logged_in_user = $_SESSION['valid_user'];
echo '
      <div class="user-panel">
        <div class="pull-left image">
          <h3><i class="fa fa-user-secret text-orange"></i></h3>
        </div>
        <div class="pull-left info">
          <p>'.$logged_in_user.'</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Logged in</a>
        </div>
      </div>';
}

else if (isset($_SESSION['time_admin_valid_user'])) {
    $logged_in_user = $_SESSION['time_admin_valid_user'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user-secret text-red"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Logged in</a>
            </div>
          </div>';

} else if (isset($_SESSION['valid_reports_user'])) {
    $logged_in_user = $_SESSION['valid_reports_user'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user-plus"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Logged in</a>
            </div>
          </div>';
} else if (isset($_SESSION['valid_report_employee'])) {
    $logged_in_user = $_SESSION['valid_report_employee'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Logged in</a>
            </div>
          </div>';
}else if (isset($_SESSION['valid_profile'])) {
    $logged_in_user = $_SESSION['valid_profile'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user text-blue"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Logged in</a>
            </div>
          </div>';
}

// end user moved here from topmain


include './theme/templates/leftnavend.inc';
include './theme/templates/beginmaincontent.inc';


echo " <div class='row'>
      <!-- Left Side Interface For Employee's To Punch -->
          <div class='row'>
             <div id='register' class='col-sm-12 col-md-12 col-lg-12' >";

// display form to submit signin/signout information //


echo "          <form role='form' name='timeclock' action='$self' method='post'>";

echo '               <div class="box box-primary">
                        <div class="box-header with-border">
                          <h3 class="box-title">Please punch in below:</h3>
                        </div>
                       <!-- /.box-header -->';
echo "                  <div class='box-body'>
                          <div class='row'>";
/*FLAG*/ //muestra una opción u otra, login o seleccionar id
if($show_select_login == "yes"){

  echo "                    <div class='col-sm-6 col-md-6 col-lg-6'>
                                <div class='form-group'>
                                    <label>Name:</label>";

  // query to populate dropdown with employee names //
  if ($show_display_name == "yes") {
      $query = "select displayname from ".$db_prefix."employees where disabled <> '1'  and empfullname <> 'admin' order by displayname";
      $emp_name_result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
      echo "          <select multiple class='form-control' name='left_displayname' size='6' tabindex=1>
                                <option value =''>
                                   ...
                                </option>";

      while ($row = mysqli_fetch_array($emp_name_result)) {
          $abc = stripslashes("".$row['displayname']."");

          if ((isset($_COOKIE['remember_me'])) && (stripslashes($_COOKIE['remember_me']) == $abc)) {
              echo "
                                <option selected>
                                   $abc
                                </option>";
          } else {
              echo "
                                <option>
                                   $abc
                                </option>";
          }
      }

      echo "        </select>
                </div>
             </div>";
      ((mysqli_free_result($emp_name_result) || (is_object($emp_name_result) && (get_class($emp_name_result) == "mysqli_result"))) ? true : false);
  } else { // Display full employee names
      $query = "select empfullname from ".$db_prefix."employees where disabled <> '1'  and empfullname <> 'admin' order by empfullname";
      $emp_name_result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
      echo "
                             <select multiple class='form-control' name='left_fullname'>
                                <option value =''>
                                   ...
                                </option>";

      while ($row = mysqli_fetch_array($emp_name_result)) {
          $def = stripslashes("".$row['empfullname']."");
          if ((isset($_COOKIE['remember_me'])) && (stripslashes($_COOKIE['remember_me']) == $def)) {
              echo "
                                <option selected>
                                   $def
                                </option>";
          } else {
              echo "
                                <option>
                                   $def
                                </option>";
          }
      }

      echo "      </select>
              </div>
            </div>";
      ((mysqli_free_result($emp_name_result) || (is_object($emp_name_result) && (get_class($emp_name_result) == "mysqli_result"))) ? true : false);
  }


  echo "<div class='col-sm-6 col-md-6 col-lg-6'>";

}else {


  echo "<div class='col-sm-6 col-md-6 col-lg-6'>";
  if ($show_display_name == "yes") {

    echo "<div class='form-group'>
            <label>Displayname:</label>
            <input type='text' name='left_displayname' maxlength='25' class='form-control' placeholder='Displayname'>
          </div>";
  }else{

    echo "<div class='form-group'>
            <label>Username:</label>
            <input type='text' name='left_fullname' maxlength='25' class='form-control' placeholder='Username'>
          </div>";
  }

}



// determine whether to use encrypted passwords or not //
if ($use_passwd == "yes") {
    echo "<div class='form-group'>
            <label>Password:</label>
            <input type='password' name='employee_passwd' maxlength='25' class='form-control' placeholder='Password'>
			    </div>";
}

echo "    <div class='form-group'>Status:";

// query to populate dropdown with punchlist items //
$query = "select punchitems from ".$db_prefix."punchlist";
$punchlist_result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

echo "        <select class='form-control' name='left_inout'>
                <option value =''>
                  ...
                </option>";

while ($row = mysqli_fetch_array($punchlist_result)) {
echo "          <option> ".$row['punchitems']."
                </option>";
}

echo "        </select>
          </div>";
((mysqli_free_result( $punchlist_result ) || (is_object( $punchlist_result ) && (get_class( $punchlist_result ) == "mysqli_result"))) ? true : false);


echo "  </div>
      </div>";

echo "<div class='row'>";
echo "<div class='col-sm-6 col-md-6 col-lg-6'>";
echo "    <div class='form-group'>
            <label>Notes:</label>
            <input type='text' name='left_notes' maxlength='250' class='form-control'>
          </div>";

echo "</div>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col-sm-6 col-md-6 col-lg-6'>";

if (! isset($_COOKIE['remember_me'])) {
echo "<div class='checkbox'>
        <label>
          <input type='checkbox' name='remember_me' value='1'> Remember Me?
        </label>
      </div>";
} elseif (isset($_COOKIE['remember_me'])) {
    echo "
                     <div class='checkbox'>

                                    <label><input type='checkbox' name='reset_cookie' value='1'> Reset Cookie? </label>
                               </div>   ";
}
echo "</div>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col-sm-6 col-md-6 col-lg-6'>";
echo "<div class='form-group'>
                        <button type='submit' class='btn btn-lg btn-primary'>Punch Status</button>
                         </div>
        </div></form>";
echo "</div>";
echo "</div>";
// End leftnav here and put the rest in main.

////////////////////// display links in top left of each page //////////////////////////
/*
if ($links == "none") { // Display any links listed

} else {
    echo "<ul class='sidebar-menu'><li class='header'>LINKS</li>";
    for ($x = 0; $x < count($display_links); $x++) {
        echo "
              <li><a href='$links[$x]'><i class='fa fa-link'></i>$display_links[$x]</a></li>";
    }
    echo '</ul>';
}
*/
//////////////////////////////////////////////////////////////////



echo "    </div>
        </div>
      </div><!-- /.Left Side Interface For Employee's To Punch  -->";



////////////////////////////////CONTROL DE ERRORES////////////////////////////////////////////
echo '<div class="row">
	     <!-- extra messages -->';



if ($request == 'POST') { // Process employee's punch information
    // signin/signout data passed over from worktime.php //
    $inout = $_POST['left_inout'];
//    $notes = ereg_replace("[^[:alnum:] \,\.\?-]","",strtolower($_POST['left_notes']));
//revisar filtrado de carcateres
    $pattern = strtolower($_POST['left_notes']);
    $notes = preg_replace(" / [^ a-zA-Z0-9] / "," ",$pattern);

    // begin post validation //
    if ($use_passwd == "yes") {
        $employee_passwd = password_hash($_POST['employee_passwd'], PASSWORD_DEFAULT, ['cost' => 10]);
    }

    $query = "select punchitems from ".$db_prefix."punchlist";
    $punchlist_result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    while ($row = mysqli_fetch_array($punchlist_result)) {
        $tmp_inout = "".$row['punchitems']."";
    }

    if (! isset($tmp_inout)) {
	    echo '<div id="float_window" class="col-md-10">
              <div class="callout callout-danger">
                  <h4><i class="fa fa-bullhorn"></i> Error</h4>
                  <p>Status is not in the database.</p>
              </div>
            </div>';exit;
    }
    // end post validation //
    if ($show_display_name == "yes") {
        if (isset($displayname)) {
            $displayname = addslashes($displayname);
            $query = "select displayname from ".$db_prefix."employees where displayname = '".$displayname."'";
            $emp_name_result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

            while ($row = mysqli_fetch_array($emp_name_result)) {
                $tmp_displayname = "".$row['displayname']."";
            }
            if ((!isset($tmp_displayname)) && (!empty($displayname))) {

                echo '<div id="float_window" class="col-md-10">
                        <div class="callout callout-danger">
                          <h4><i class="fa fa-bullhorn"></i> Error</h4>
                          <p>Username is not in the database.</p>
                        </div>
                      </div>';
                exit;
            }
            $displayname = stripslashes($displayname);
        }
    } elseif ($show_display_name == "no") {
        if (isset($fullname)) {
            $fullname = addslashes($fullname);
            $query = "select empfullname from ".$db_prefix."employees where empfullname = '".$fullname."'";
            $emp_name_result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

            while ($row = mysqli_fetch_array($emp_name_result)) {
                $tmp_empfullname = "".$row['empfullname']."";
            }
            if ((!isset($tmp_empfullname)) && (!empty($fullname))) {
              echo '<div id="float_window" class="col-md-10">
                      <div class="callout callout-danger">
                        <h4><i class="fa fa-bullhorn"></i> Error</h4>
                        <p>Username is not in the database.</p>
                      </div>
                    </div>';
                exit;
            }
            $fullname = stripslashes($fullname);
        }
    }

    if ($show_display_name == "yes") {
        if (! $displayname && ! $inout) {
    	    echo '<div id="float_window" class="col-md-10">
                  <div class="callout callout-danger">
                    <h4><i class="fa fa-bullhorn"></i> Error</h4>
                    <p>You have not chosen a username or a status. Please try again..</p>
                  </div>
                </div>';
            // Return the employee back to the punch interface after 5 seconds
            echo "<head>
                      <meta http-equiv='refresh' content=5;url=index.php>
                   </head>";
            exit;
        }

        if (! $displayname) {
      	    echo '<div id="float_window" class="col-md-10">
                    <div class="callout callout-danger">
                      <h4><i class="fa fa-bullhorn"></i> Error</h4>
                      <p>You have not chosen a username. Please try again.</p>
                    </div>
                  </div>';

            // Return the employee back to the punch interface after 5 seconds
            echo "<head>
                      <meta http-equiv='refresh' content=5;url=index.php>
                  </head>";
            exit;
        }
    } elseif ($show_display_name == "no") {

        if (! $fullname && ! $inout) {
    	    echo '<div id="float_window" class="col-md-10">
                  <div class="callout callout-danger">
                    <h4><i class="fa fa-bullhorn"></i> Error</h4>
                    <p>You have not chosen a username or a status. Please try again.</p>
                  </div>
                </div>';


            // Return the employee back to the punch interface after 5 seconds
            echo "
                 <head>
                    <meta http-equiv='refresh' content=5;url=index.php>
                 </head>";
            exit;
        }

        if (! $fullname) {
        	    echo '<div id="float_window" class="col-md-10">
                      <div class="callout callout-danger">
                        <h4><i class="fa fa-bullhorn"></i> Error</h4>
                        <p>You have not chosen a username. Please try again.</p>
                      </div>
                    </div>';


            // Return the employee back to the punch interface after 5 seconds
            echo "
                 <head>
                    <meta http-equiv='refresh' content=5;url=index.php>
                 </head>";
            exit;
        }
    }

    if (! $inout) {
	    echo '<div id="float_window" class="col-md-10">
              <div class="callout callout-danger">
                <h4><i class="fa fa-bullhorn"></i> Error</h4>
                <p>You have not chosen a status. Please try again.</p>
              </div>
            </div>';


        // Return the employee back to the punch interface after 5 seconds
        echo "
               <head>
                  <meta http-equiv='refresh' content=5;url=index.php>
               </head>";
        exit;
    }


/// /*FLAG*/ //desde aqui puede estar el error. cambie query  `inout` por inout

    // Get all the possible punch status names
    $query = "select punchitems from ".$db_prefix."punchlist";
    $punchlist_result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
    // We need to get the full name if we're only displaying the display name
    if ($show_display_name == "yes") {
        $query = "select empfullname from ".$db_prefix."employees where displayname = '".$displayname."'";
        $sel_result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        while ($row = mysqli_fetch_array($sel_result)) {
            $fullname = stripslashes("".$row["empfullname"]."");
            $fullname = addslashes($fullname);
        }
    }

    // Get the current punch name of that employee
    $query = "select * from ".$db_prefix."info where fullname = '".$fullname."'";
    $query = mysqli_query($GLOBALS["___mysqli_ston"], $query);
    // Find the last entry for the employee
    $largestStamp = 0;
    $currentPunchName = "";
    while ($row = mysqli_fetch_array($query)) {
        if ($row['timestamp'] > $largestStamp) {
            $currentPunchName = $row['inout']; /*FLAG*/
            $largestStamp = $row['timestamp'];
        }
    }
    // Get the selected status
    $query = "select in_or_out from ".$db_prefix."punchlist where punchitems = '".$inout."'";
    $query = mysqli_query($GLOBALS["___mysqli_ston"], $query);
    $row = mysqli_fetch_array($query); //probar con parámetro MYSQLI_BOTH
    $selectedStatus = $row['in_or_out']; // The first one should the be the current status code.
    //$selectedStatus = $inout;

    if ($currentPunchName == "") {
        $currentStatus = "NEVER CLOCKED IN YET";
    } else { // Iterate through to find the current status of individual logging in
        while ($punchName = mysqli_fetch_array($punchlist_result)) {
            if ($currentPunchName == $punchName['punchitems']) {
                $query = "select in_or_out from ".$db_prefix."punchlist where punchitems =  '".$currentPunchName."'";
                $query = mysqli_query($GLOBALS["___mysqli_ston"], $query);
                $row = mysqli_fetch_array($query);
                $currentStatus = $row['in_or_out']; // The first one should the be the current status code.
                break;
            }
        }
    }

    // Verify that the employee is not selecting the same status as his current status
    if ($selectedStatus == $currentStatus) {
	    echo '<div id="float_window" class="col-md-10">
              <div class="callout callout-danger">
                <h4><i class="fa fa-bullhorn"></i> Error</h4>
                <p>The current punch status for '.$fullname.' is '.$currentPunchName.' . Please use a different status.</p>
              </div>
            </div>';
        // Return the employee back to the punch interface after 5 seconds
        echo "
             <head>
                <meta http-equiv='refresh' content=5;url=index.php>
             </head>";
        exit;
    }





    if ($use_passwd == "yes") { // Verify that the employee password is correct, if required
        $sel_query = "select empfullname, employee_passwd from ".$db_prefix."employees where empfullname = '".$fullname."'";
        $sel_result = mysqli_query($GLOBALS["___mysqli_ston"], $sel_query);

        while ($row=mysqli_fetch_array($sel_result)) {
            $tmp_password = "".$row["employee_passwd"]."";
        }

        if (password_verify($employee_passwd,$tmp_password)) {
    	    echo '<div id="float_window" class="col-md-10">
                  <div class="callout callout-danger">
                    <h4><i class="fa fa-bullhorn"></i> Error</h4>
                    <p>You have entered the wrong password for '.$fullname.'. Please try again.</p>
                  </div>
                </div>';


            // Return the employee back to the punch interface after 5 seconds
            echo "
                 <head>
                    <meta http-equiv='refresh' content=5;url=index.php>
                 </head>";
            exit;
        }
    }



    @$fullname = addslashes($fullname);
    @$displayname = addslashes($displayname);

    // configure timestamp to insert/update //
/*
    $time = time();
    $hour = gmdate('H',$time);
    $min = gmdate('i',$time);
    $sec = gmdate('s',$time);
    $month = gmdate('m',$time);
    $day = gmdate('d',$time);
    $year = gmdate('Y',$time);
    //$tz_stamp = mktime ($hour, $min, $sec, $month, $day, $year);
    */
  // testing better ways
//$tz_stamp = time($hour, $min, $sec, $month, $day, $year);
    $tz_stamp = time();
    if ($show_display_name == "yes") {
        $sel_query = "select empfullname from ".$db_prefix."employees where displayname = '".$displayname."'";
        $sel_result = mysqli_query($GLOBALS["___mysqli_ston"], $sel_query);

        while ($row=mysqli_fetch_array($sel_result)) {
            $fullname = stripslashes("".$row["empfullname"]."");
            $fullname = addslashes($fullname);
        }
    }

    if(empty($_POST['latitude']) || empty($_POST['longitude'])){

      $lat = 0;
      $lon = 0;
    }else {
      $lat = $_POST['latitude'];
      $lon = $_POST['longitude'];
    }

    if (strtolower($ip_logging) == "yes") {
        $query = "insert into ".$db_prefix."info (fullname, `inout`, timestamp, notes, ipaddress, latitude, longitude) values ('".$fullname."', '".$inout."', '".$tz_stamp."', '".$notes."', '".$connecting_ip."','" . $lat . "','" . $lon . "')";
    } else {
        $query = "insert into ".$db_prefix."info (fullname, `inout`, timestamp, notes, latitude, longitude) values ('".$fullname."', '".$inout."', '".$tz_stamp."', '".$notes."','" . $lat . "','" . $lon . "')";
    }

    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    $update_query = "update ".$db_prefix."employees set tstamp = '".$tz_stamp."' where empfullname = '".$fullname."'";
    $other_result = mysqli_query($GLOBALS["___mysqli_ston"], $update_query);
	    echo '<div id="float_window" class="col-md-10">
              <div class="callout callout-success">
                <h4><i class="fa fa-bullhorn"></i> </h4>
                <p> Status changed successfully for '.$fullname.' to a status of '.$inout.'.</p>
              </div>
            </div>';

    // Return the employee back to the punch interface after 5 seconds
    echo "
         <head>
          <meta http-equiv='refresh' content=5;url=index.php>
        </head>";
}

// Determine if we should add the message of the day
if (! isset($_GET['printer_friendly']) && ($message_of_the_day != "none")) {
	echo '
		<!-- Message Of The Day Display -->
	        <div id="float_window" class="col-md-10">
		<div class="callout callout-success">
                <h4>Mensaje:</h4>

                <p>'.htmlspecialchars($message_of_the_day).'</p>
              </div>
	      </div>
	      ';


} else if (! isset($_GET['printer_friendly']) && ($message_of_the_day == "none")) {
    echo " ";
}

      // if (! isset($_GET['printer_friendly'])) {
      //
      //
      // 	echo ' <div class="col-md-4">
      //   <a href="worktime.php?printer_friendly=true" class="btn btn-app">
      //                 <i class="glyphicon glyphicon-print"></i> Printer Friendly Page
      //               </a>
      //         </div>';
      // }


echo '
	</div>
	<!-- /.extra messages -->
	';
?>
<script>
  window.addEventListener('load',function() {
      if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(funcExito, funcError, {});
      } else {
          alert('No geolocation supported');
      }
  },false);

    function funcExito(result) {

      var latitude = document.querySelector('input[name="latitude"]');
      var longitude = document.querySelector('input[name="longitude"]');

      latitude.value = result.coords.latitude;
      longitude.value = result.coords.longitude;
    }

    function funcError(err) {
      alert(err.message);
    }
</script>
<script language=\"javascript\" src=\"../scripts/reverse_geolocation.js\"></script>
