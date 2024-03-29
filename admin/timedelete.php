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

/**
 * This module will delete a punch made by an employee and place that change in an aduit log.
 */

include '../config.inc.php';
// include 'header_date.php';
include 'header.php';
include 'topmain.php';
include 'leftmain-time.php';

echo "<title>$title - Delete Time</title>\n";

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
    echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
    echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Administration</td></tr>\n";
    echo "  <tr class=right_main_text>\n";
    echo "    <td align=center valign=top scope=row>\n";
    echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
    echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
    echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=admin'><u>here</u></a> to login.</td></tr>\n";
    echo "      </table><br /></td></tr></table>\n";
    exit;
}

if ($request == 'GET') { // Display employee select interface for deleting an employee's time
    if (!isset($_GET['username'])) { // Make sure there is a logged in user
        echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
        echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Error!</td></tr>\n";
        echo "  <tr class=right_main_text>\n";
        echo "    <td align=center valign=top scope=row>\n";
        echo "      <table width=300 border=0 cellpadding=5 cellspacing=0>\n";
        echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
        echo "        <tr class=right_main_text><td align=center>Go back to the <a class=admin_headings href='timeadmin.php'>Modify Time</a> page to
                        delete a time.
                    </td></tr>\n";
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

    $get_user = stripslashes($get_user);



	/*
    echo "    <td align=left class=right_main scope=col>\n";
    echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
    echo "        <tr class=right_main_text>\n";
    echo "          <td valign=top>\n";
    echo "            <br />\n";
	*/

	echo '<div class="row">
	    <div id="float_window" class="col-md-10">
	      <div class="box box-info"> ';
	echo '<div class="box-header with-border">
	                 <h3 class="box-title"><i class="fa fa-clock-o"></i> Eliminar Tiempos</h3>
	               </div><div class="box-body">';

    echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
    echo "                <input type='hidden' name='date_format' value='$js_datefmt'>\n";
    echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
    echo "               <div class='form-group'><label>Nombre de usuario: $username</label><input type='hidden' name='post_username' value=\"$username\"></div>\n";
    echo "              <div class='form-group'><label>Nombre de acceso: $displayname</label><input type='hidden' name='post_displayname' value=\"$displayname\"></div>\n";

    echo "              <div class='form-group' style='display: -webkit-box;'>
                          <label style='margin-right:2.4rem;'>Fecha:&nbsp;*</label>
                            <div class='input-group'>
                            <div class='input-group-addon'>
                              <i class='fa fa-calendar'></i>
                            </div>
                              <input type='date' size='10' maxlength='10' name='post_date' style='color: #444;border: #d2d6de;border-style: solid;border-width: thin;height: 33px;width: 149px;padding-left: 10px;' required>
                              <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                              return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                            </div>
                        </div>\n";
    echo '<div class="box-footer">
                <button type="button" id="formButtons" onclick="location=\'timeadmin.php\'" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                  <i class="fa fa-ban"></i>
                  Cancelar
                </button>

                <button id="formButtons" type="submit" name="submit" value="Delete Time" class="btn btn-danger pull-right">
                  <i class="fa fa-remove"></i>
                  Eliminar tiempo
                </button>
              </div></form>';
    echo '</div></div></div></div>';
        include '../theme/templates/endmaincontent.inc';
    include '../footer.php';
	include '../theme/templates/controlsidebar.inc';
	include '../theme/templates/endmain.inc';
	include '../theme/templates/adminfooterscripts.inc';
    exit;
} elseif ($request == 'POST') { // Display interface for deleting the selected employee's time.



    @$get_user = stripslashes($_POST['get_user']);
    $post_username = stripslashes($_POST['post_username']);
    $post_displayname = stripslashes($_POST['post_displayname']);
    $post_date = $_POST['post_date'];
    @$final_username = $_POST['final_username'];
    @$final_inout = $_POST['final_inout'];
    @$final_notes = $_POST['final_notes'];
    @$final_mysql_timestamp = $_POST['final_mysql_timestamp'];
    @$final_num_rows = $_POST['final_num_rows'];
    @$final_time = $_POST['final_time'];
    @$delete_time_checkbox = $_POST['delete_time_checkbox'];
    @$timestamp = $_POST['timestamp'];
    @$calc = $_POST['calc'];
    $row_count = '0';

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
            echo "Something is fishy here. 1\n";
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
            echo "Something is fishy here. 2\n";
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
            echo "Something is fishy here. 3\n";
            exit;
        }
    }

    // end post validation //

    $get_user = stripslashes($get_user);
    $post_username = stripslashes($post_username);
    $post_displayname = stripslashes($post_displayname);

    // begin post validation //

    if ($get_user != $post_username) {
        exit;
    }

    // end post validation //


    // begin post validation //

    if (empty($post_date)) {
        $evil_post = '1';
        echo '            <div id="float_window" class="col-md-10"><div class="alert alert-warning alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                            Se requiere una fecha válida
                          </div></div>';

    }


    if (isset($evil_post)) { // Display error message
        echo "            <br />\n";

        echo '<div class="row">
            <div id="float_window" class="col-md-10">
              <div class="box box-info"> ';
        echo '<div class="box-header with-border">
                         <h3 class="box-title"><i class="fa fa-clock-o"></i> Eliminar Tiempos</h3>
                       </div><div class="box-body">';

          echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
          echo "                <input type='hidden' name='date_format' value='$js_datefmt'>\n";
          echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
          echo "               <div class='form-group'><label>Nombre de usuario: $post_username</label><input type='hidden' name='post_username' value=\"$post_username\"></div>\n";
          echo "              <div class='form-group'><label>Nombre de acceso: $post_displayname</label><input type='hidden' name='post_displayname' value=\"$post_displayname\"></div>\n";

          echo "    <div class='form-group'>
                     <label class='table_rows_output'>
                       &nbsp;*Fecha:
                     </label>
                     <input type='date' size='10' required='true' maxlength='10' name='post_date' style='color:#000000'>&nbsp;&nbsp;
                       <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                       return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                     </div>";
         echo       '<div class="box-footer">
                      <button type="button" id="formButtons" onclick="location=\'timeadmin.php\'" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                       <i class="fa fa-ban"></i>
                       Cancelar
                    </button>

                    <button id="formButtons" type="submit" name="submit" value="Delete time" class="btn btn-danger pull-right">
                     <i class="fa fa-trash"></i>
                     Eliminar
                   </button>
                 </div></form>';

          echo '</div></div></div></div>';
              include '../theme/templates/endmaincontent.inc';
          include '../footer.php';
        include '../theme/templates/controlsidebar.inc';
        include '../theme/templates/endmain.inc';
        include '../theme/templates/adminfooterscripts.inc';
          exit;
        // end post validation //
    } else {
       // Display delete employee's time interface
        if (isset($_POST['delete_time_checkbox']) && (((!empty($_POST['post_why'])) && ($require_time_admin_edit_reason == "yes")) || ($require_time_admin_edit_reason == "no"))) { // Display successful time delete
          echo '       <div id="float_window" class="col-md-10"><div class="alert alert-success alert-dismissible">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                       <h4><i class="icon fa fa-check-circle"></i>Hora eliminada!</h4>
                          La hora registrasa ha sido eliminada satisfactoriamente.
                       </div></div>';

            echo '<div class="row">
                <div id="float_window" class="col-md-10">
                  <div class="box box-info"> ';
            echo '<div class="box-header with-border">
                             <h3 class="box-title"><i class="fa fa-clock-o"></i> Eliminar Tiempo</h3>
                           </div><div class="box-body">';

            echo "            <form name='form' action='$self' method='post'>\n";
            echo "            <table align=center class=table width=60% border=0 cellpadding=3 cellspacing=0>\n";
            echo "                <tr>
                                    <td class='table_rows' nowrap width=1% style='padding-right:5px;padding-left:5px;' class='table_rows_output'>
                                      Eliminado
                                    </td>\n";
            echo "                  <td class='table_rows' nowrap width=7% align=center style='padding-left:20px;'>
                                      Entrada/Salida
                                    </td>\n";
            echo "                  <td class='table_rows' nowrap style='padding-left:20px;' width=4% align=right class='table_rows_output'>
                                      Hora
                                    </td>\n";
            echo "                  <td class='table_rows' style='padding-left:25px;' class='table_rows_output'>
                                      Notas
                                    </td>
                                  </tr>\n";

            // begin post validation //
            if (!is_numeric($final_num_rows)) {
                exit;
            }
            // end post validation //

            $tmp_tmp_username = array();

            // determine who the authenticated user is for audit log

            if (isset($_SESSION['valid_user'])) {
                $user = $_SESSION['valid_user'];
            } elseif (isset($_SESSION['time_admin_valid_user'])) {
                $user = $_SESSION['time_admin_valid_user'];
            } else {
                $user = "";
            }

            // configure current time to insert for audit log

            //$time = time();
            // $time_hour = gmdate('H',$time);
            // $time_min = gmdate('i',$time);
            // $time_sec = gmdate('s',$time);
            // $time_month = gmdate('m',$time);
            // $time_day = gmdate('d',$time);
            // $time_year = gmdate('Y',$time);
            // $time_tz_stamp = time ($time_hour, $time_min, $time_sec, $time_month, $time_day, $time_year);

            $time_tz_stamp = time();
            // Escape admin reason for SQL
            $post_why = $_POST['post_why'];
            if (empty($post_why)) {
                @$post_why = "";
            } else {
              //  $post_why = ereg_replace("[^[:alnum:] \,\.\?-]", "", $post_why);
		            @$post_why = preg_replace('/' . "[^[:alnum:] \,\.\?-]" . '/', "", $post_why);
            }

            for ($x=0;$x<$final_num_rows;$x++) {
                // begin post validation //
                $final_username[$x] = stripslashes($final_username[$x]);
                $tmp_username = stripslashes($tmp_username);

                $final_username[$x] = stripslashes($final_username[$x]);
                if ($final_username[$x] != $tmp_username) {
                    echo "Something is fishy here. 4\n";
                    exit;
                }
                /* Why is this section commented out, we should look into it at some point
                if ((strlen($final_mysql_timestamp[$x]) != "10") || (!is_integer($final_mysql_timestamp[$x]))) {
                    echo "Something is fishy here.\n";
                    exit;
                }
                */

                $query_sel = "select * from ".$db_prefix."punchlist where punchitems = '".$final_inout[$x]."'";
                $result_sel = mysqli_query($GLOBALS["___mysqli_ston"], $query_sel);

                while ($row=mysqli_fetch_array($result_sel)) {
                    $punchitems = "".$row['punchitems']."";
                }
                ((mysqli_free_result($result_sel) || (is_object($result_sel) && (get_class($result_sel) == "mysqli_result"))) ? true : false);
                if (!isset($punchitems)) {
                    echo "Something is fishy here. 5\n";
                    exit;
                }

		            // $final_notes[$x] = ereg_replace("[^[:alnum:] \,\.\?-]","",$final_notes[$x]);
	              $final_notes[$x] = preg_replace('/' . "[^[:alnum:] \,\.\?-]" . '/',"",$final_notes[$x]);
                $final_username[$x] = addslashes($final_username[$x]);

                $query5 = "select * from ".$db_prefix."info where (empfullname = '".$final_username[$x]."') and (timestamp = '".$final_mysql_timestamp[$x]."') and (`inout` = '".$final_inout[$x]."') and (notes = '".$final_notes[$x]."')";
                $result5 = mysqli_query($GLOBALS["___mysqli_ston"], $query5);
                @$tmp_num_rows = mysqli_num_rows($result5);

                if ((isset($tmp_num_rows)) && (@$tmp_num_rows != '1')) {
                    echo "Something is fishy here. 6\n";
                    exit;
                }
                // end post validation //

                $row_color = ($row_count % 2) ? $color1 : $color2;

                if (@$delete_time_checkbox[$x] == '1') { // Delete times that have been checked
                    // begin post validation //
                    $tmp_time[$x] = date("$timefmt", $final_mysql_timestamp[$x] + $tzo);
                    if ($tmp_time[$x] != $final_time[$x]) {
                        echo "Something is fishy here. 7\n";
                        exit;
                    }
                    // end post validation //

                    /* Again why is this commented out, we need to look into it
                    if (!get_magic_quotes_gpc()) {
                        $final_username[$x] = addslashes($final_username[$x]);
                    }
                    */

                    $query = "select * from ".$db_prefix."employees where empfullname = '".$final_username[$x]."'";
                    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

                    while ($row=mysqli_fetch_array($result)) {
                        $tmp_empfullname_1 = stripslashes("".$row['empfullname']."");
                        $tmp_tstamp_1 = "".$row['tstamp']."";
                    }

                    $tmp_tmp_username[$x] = stripslashes($final_username[$x]);

                    if (($tmp_empfullname_1 == $tmp_tmp_username[$x]) && ($tmp_tstamp_1 == $final_mysql_timestamp[$x])) {
                        $query2 = "select * from ".$db_prefix."info where fullname = '".$final_username[$x]."' order by timestamp desc limit 1,1";
                        $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);

                        while ($row2=mysqli_fetch_array($result2)) {
                            $tmp_empfullname_2 = stripslashes("".$row2['fullname']."");
                            $tmp_empfullname_2 = addslashes($tmp_empfullname_2);
                            $tmp_tstamp_2 = "".$row2['timestamp']."";
                        }

                        $query3 = "update ".$db_prefix."employees set empfullname = '".$tmp_empfullname_2."', tstamp = '".$tmp_tstamp_2."' where empfullname = '".$tmp_empfullname_2."'";
                        $result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);
                    }

                    // Delete the time from the info table for $post_username
                    $query4 = "delete from ".$db_prefix."info where fullname = '".$final_username[$x]."' and timestamp = '".$final_mysql_timestamp[$x]."'";
                    $result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);

                    // Add the changes to the audit table
                    if (strtolower($ip_logging) == "yes") {
                        $query6 = "insert into ".$db_prefix."audit (modified_by_ip, modified_by_user, modified_when, modified_from, modified_to, modified_why, user_modified) values ('".$connecting_ip."', '".$user."', '".$time_tz_stamp."', '".$final_mysql_timestamp[$x]."', '0', '".$post_why."', '".$final_username[$x]."')";
                        $result6 = mysqli_query($GLOBALS["___mysqli_ston"], $query6);
                    } else {
                        $query6 = "insert into ".$db_prefix."audit (modified_by_user, modified_when, modified_from, modified_to, modified_why, user_modified) values ('".$user."', '".$time_tz_stamp."', '".$final_mysql_timestamp[$x]."', '0', '".$post_why."', '".$final_username[$x]."')";
                        $result6 = mysqli_query($GLOBALS["___mysqli_ston"], $query6);
                    }
                    echo "              <tr class=display_row height=20>\n";
                    echo "                <td nowrap bgcolor='$row_color' width=5% align=center>
                                            <i class='glyphicon glyphicon-ok text-green'></i>\n";
                    echo "                <td nowrap bgcolor='$row_color' align=center width=7% style='padding-left:5px;'>$final_inout[$x]</td>\n";
                    echo "                <td nowrap align=center style='padding-left:20px;' width=4% bgcolor='$row_color'>$final_time[$x]</td>\n";
                    echo "                <td style='padding-left:25px;' bgcolor='$row_color'>$final_notes[$x]</td>\n";
                    echo "              </tr></table>\n";
                    $row_count++;
                }
            }
            echo "   <div class='box-footer'>
                      <button type='button' id='formButtons' onclick='location=\"timeadmin.php\"' class='btn btn-success pull-right'>
                        Aceptar
                        <i class='fa fa-check'></i>
                      </button>
                    </div>\n";
            echo '</div></div></div></div>';
            include '../theme/templates/endmaincontent.inc';
            include '../footer.php';
            include '../theme/templates/controlsidebar.inc';
            include '../theme/templates/endmain.inc';
            include '../theme/templates/adminfooterscripts.inc';
            exit;
        } elseif ((!isset($_POST['delete_time_checkbox']) || (empty($_POST['post_why']) && ($require_time_admin_edit_reason == "yes"))) && (isset($_POST['tmp_var']))) { // Validate that the admin has given all the needed information
            $post_why = $_POST['post_why'];
            // begin post validation //

            if ($_POST['tmp_var'] != '1') {
                echo "Something is fishy here. 8\n";
                exit;
            }
            $tmp_calc = intval($calc);
            $tmp_timestamp = intval($timestamp);
            if ((strlen($tmp_calc) != "10") || (!is_integer($tmp_calc))) {
                echo "Something is fishy here.\n";
                exit;
            }
            if ((strlen($tmp_timestamp) != "10") || (!is_integer($tmp_timestamp))) {
                echo "Something is fishy here. 9\n";
                exit;
            }
            // end post validation //

            if (get_magic_quotes_gpc()) {
                $post_username = stripslashes($post_username);
            }
            $post_username = addslashes($post_username);

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

            $post_username = stripslashes($post_username);

            echo '<div class="row">
                    <div id="float_window" class="col-md-10">
                      <div class="box box-info"> ';
      echo '
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-tag"></i> Please choose a time or times to delete</h3>
	  <small>A reason for the deletion is required.</small>
        </div>
        <div class="box-body">';
            echo " \n";
            echo "  <tr>\n";
            if (empty($delete_time_checkbox)) {
                echo "                 Please choose a time or times to delete.\n";
            }
            if (empty($post_why) && ($require_time_admin_edit_reason == "yes")) {
                echo "                 A reason for the deletion is required.\n";
            }
            echo "            <form name='form' action='$self' method='post'>\n";
            echo "            <table class='table' align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
            echo "              <tr>\n";
            echo "                <th class=rightside_heading nowrap halign=left colspan=4><img src='../images/icons/clock_delete.png' />&nbsp;&nbsp;&nbsp;Delete Time for $post_username on $post_date</th></tr>\n";
            echo "              <tr><td height=15></td></tr>\n";
            echo "                <tr><td nowrap width=1% style='padding-right:5px;padding-left:5px;' class=column_headings>Delete ?</td>\n";
            echo "                  <td nowrap width=7% align=left class=column_headings>In/Out</td>\n";
            echo "                  <td nowrap style='padding-left:20px;' width=4% align=right class=column_headings>Time</td>\n";
            echo "                  <td style='padding-left:25px;' class=column_headings><u>Notes</u></td></tr>\n";

            for ($x=0;$x<$num_rows;$x++) {
                $row_color = ($row_count % 2) ? $color1 : $color2;
                $time[$x] = date("$timefmt", $mysql_timestamp[$x] + $tzo);
                $username[$x] = stripslashes($username[$x]);

                echo "              <tr class=display_row>\n";
                echo "                <td nowrap width=1% style='padding-right:5px;padding-left:0px;' align=center><input type='checkbox' name='delete_time_checkbox[$x]' value='1'></td>\n";
                echo "                <td nowrap align=left style='width:7%;padding-left:5px;background-color:$row_color;color:".$row["color"]."'>$inout[$x]</td>\n";
                echo "                <td nowrap align=right style='padding-left:20px;' width=4% bgcolor='$row_color'>$time[$x]</td>\n";
                echo "                <td style='padding-left:25px;' bgcolor='$row_color'>$notes[$x]</td>\n";
                echo "              </tr>\n";
                echo "              <input type='hidden' name='final_username[$x]' value=\"$username[$x]\">\n";
                echo "              <input type='hidden' name='final_inout[$x]' value=\"$inout[$x]\">\n";
                echo "              <input type='hidden' name='final_notes[$x]' value=\"$notes[$x]\">\n";
                echo "              <input type='hidden' name='final_mysql_timestamp[$x]' value=\"$mysql_timestamp[$x]\">\n";
                echo "              <input type='hidden' name='final_time[$x]' value=\"$time[$x]\">\n";
                $row_count++;
            }
            echo "              <tr><td height=15></td></tr>\n";
            if ($require_time_admin_edit_reason == "yes") {
                echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Reason For Deletion:</td><td colspan=2 width=80% style='padding-left:20px;'><input type='text' size='25' maxlength='250' name='post_why' value='$post_why'>&nbsp;* Required</td></tr>\n";
            } else if ($require_time_admin_edit_reason == "no") {
                echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Reason For Deletion:</td><td colspan=2 width=80% style='padding-left:20px;'><input type='text' size='25' maxlength='250' name='post_why' value='$post_why'> </td></tr>\n";
            }
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
            echo "              <tr><td width=30><input type='image' name='submit' value='Delete Time' align='middle' src='../images/buttons/next_button.png'></td><td><a href='timeadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form>\n";
            echo '</div></div></div></div>';
            include '../theme/templates/endmaincontent.inc';
            include '../footer.php';
            include '../theme/templates/controlsidebar.inc';
            include '../theme/templates/endmain.inc';
            include '../theme/templates/adminfooterscripts.inc';

            exit;
        } else { // Present interface for the date selection for the admin
            // configure timestamp to insert/update //
            // if ($calendar_style == "euro") {
            //     @$post_date = "$day/$month/$year";
            // } elseif ($calendar_style == "amer") {
            //     @$post_date = "$month/$day/$year";
            // }

            $row_count = '0';
            $timestamp = strtotime($post_date) - @$tzo;
            // Why is this commented out, look into? $calc = $timestamp + 86400 - @$tzo;
            $calc = $timestamp + 86400;
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

        if (!isset($time_set)) { // Display error message if no time can be found for the employee on the requested date

            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> No time for was found in the system for $post_username on $post_date.</td></tr>\n";
            echo "            </table>\n";
            echo "            <br />\n";
            echo '<div class="row">
                <div id="float_window" class="col-md-10">
                  <div class="box box-info"> ';
            echo '<div class="box-header with-border">
                             <h3 class="box-title"><i class="fa fa-clock-o"></i> Delete Time FLAG 3</h3>
                           </div><div class="box-body">';

              echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
              echo "                <input type='hidden' name='date_format' value='$js_datefmt'>\n";
              echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
              echo "               <div class='form-group'><label>Nombre de usuario: $post_username</label><input type='hidden' name='post_username' value=\"$post_username\"></div>\n";
              echo "              <div class='form-group'><label>Nombre de acceso: $post_displayname</label><input type='hidden' name='post_displayname' value=\"$post_displayname\"></div>\n";

              echo "    <div class='form-group'>
                          <label>Fecha:</label>
                          <input type='date' size='10' maxlength='10' name='post_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
                          <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                          return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                           </div>";
              echo '<div class="box-footer">
                          <button type="submit" name="submit" value="Delete Time" class="btn btn-danger">Delete Time</button>
                          <button type="submit" name="cancel" class="btn btn-default pull-right"><a href="timeadmin.php">Cancel</a></button>
                        </div></form>';

              echo '</div></div></div></div>';
                  include '../theme/templates/endmaincontent.inc';
              include '../footer.php';
            include '../theme/templates/controlsidebar.inc';
            include '../theme/templates/endmain.inc';
            include '../theme/templates/adminfooterscripts.inc';
              exit;
        }
        echo '<div class="row">
                <div id="float_window" class="col-md-10">
                  <div class="box box-info"> ';
    echo '  <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-tag"></i> Eliminar Tiempo</h3>
            </div>
              <div class="box-body">';
        echo "            <form name='form' action='$self' method='post'>\n";
        echo "            <table align=center class='table'>\n";
        echo "              <tr>\n";

        // configure date to display correctly //
        // if ($calendar_style == "euro") {
        //     $post_date = "$day/$month/$year";
        // }

        echo "                <th class=rightside_heading nowrap align=center colspan=4>
                                <i class='fa fa-trash'></i>
                                Selecciona la hora a elminiar del usuario $post_username en la fecha $post_date
                              </th>
                            </tr>\n";
        if (isset($time_set)) { // Confirm the admin wants to delete the time.
            echo "                <tr>
                                    <td class='table_rows' nowrap width=1% style='padding-right:5px;padding-left:5px;' class='table_rows_output'>
                                      ¿Desea eliminar?
                                    </td>\n";
            echo "                  <td class='table_rows' nowrap width=7% align=left class='table_rows_output'>
                                      Entrada/Salida
                                    </td>\n";
            echo "                  <td class='table_rows' nowrap style='padding-left:20px;' width=4% align=right class='table_rows_output'>
                                      Hora
                                    </td>\n";
            echo "                  <td class='table_rows' style='padding-left:25px;' class='table_rows_output'>
                                      Notas
                                    </td>
                                  </tr>\n";

            for ($x=0;$x<$num_rows;$x++) {
                $row_color = ($row_count % 2) ? $color1 : $color2;
                $time[$x] = date("$timefmt", $mysql_timestamp[$x] + $tzo);
                $username[$x] = stripslashes($username[$x]);

                echo "              <tr class=display_row>\n";
                echo "                <td nowrap width=1% style='padding-right:125px;padding-left:0px;' align=center><input type='checkbox' name='delete_time_checkbox[$x]' value='1'></td>\n";
                echo "                <td nowrap align=left style='width:7%;padding-left:5px;background-color:$row_color;color:".$row["color"]."'>$inout[$x]</td>\n";
                echo "                <td nowrap align=right style='padding-left:20px;' width=4% bgcolor='$row_color'>$time[$x]</td>\n";
                echo "                <td style='padding-left:25px;' bgcolor='$row_color'>$notes[$x]</td>\n";
                echo "              </tr>\n";
                echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
                echo "              <input type='hidden' name='final_username[$x]' value=\"$username[$x]\">\n";
                echo "              <input type='hidden' name='final_inout[$x]' value=\"$inout[$x]\">\n";
                echo "              <input type='hidden' name='final_notes[$x]' value=\"$notes[$x]\">\n";
                echo "              <input type='hidden' name='final_mysql_timestamp[$x]' value=\"$mysql_timestamp[$x]\">\n";
                echo "              <input type='hidden' name='final_time[$x]' value=\"$time[$x]\">\n";
                $row_count++;
            }
            if ($require_time_admin_edit_reason == "yes") {
                echo "              <tr>
                                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                                        &nbsp;*Razón por la que se elimina:
                                      </td>

                                      <td colspan=2 width=80% style='padding-left:20px;'>
                                        <input type='text' size='25' placeholder='razón' required='true' maxlength='250' name='post_why'>
                                      </td>
                                    </tr>
                                    </table>\n";
                echo "        <div class='required_fields' align='right'>
                                *&nbsp;Campos requeridos&nbsp
                              </div>\n";
            } else if ($require_time_admin_edit_reason == "no") {
                echo "              <tr>
                                      <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                                        &nbsp;*Razón por la que se elimina:
                                      </td>

                                      <td colspan=2 width=80% style='padding-left:20px;'>
                                        <input type='text' size='25' required='true' maxlength='250' name='post_why'>
                                      </td>
                                    </tr>
                                  </table>\n";
                echo "        <div class='required_fields' align='right'>
                                *&nbsp;Campos requeridos&nbsp
                              </div>\n";

            }
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

            echo       '<div class="box-footer">
                         <button type="button" id="formButtons" onclick="location=\'timeadmin.php\'" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                          <i class="fa fa-ban"></i>
                          Cancelar
                       </button>

                       <button id="formButtons" type="submit" name="submit" value="Delete time" class="btn btn-danger pull-right">
                        <i class="fa fa-trash"></i>
                        Eliminar
                      </button>
                    </div></form>';
            echo '</div></div></div></div>';
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
