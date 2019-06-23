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
 * This module will add time to an employee's record and place the add in the audit record as well.
 */

include '../config.inc.php';
// include 'header_date.php';
include 'header.php';
include 'topmain.php';
include 'leftmain-time.php';

echo "<title>$title - Añadir Tiempo</title>\n";

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

// Make sure they are a valid user
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

if ($request == 'GET') { // Display employee add time interface
    if (!isset($_GET['username'])) { // Ensure someone is logged in.
        echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
        echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Error!</td></tr>\n";
        echo "  <tr class=right_main_text>\n";
        echo "    <td align=center valign=top scope=row>\n";
        echo "      <table width=300 border=0 cellpadding=5 cellspacing=0>\n";
        echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
        echo "        <tr class=right_main_text><td align=center>Go back to the <a class=admin_headings href='timeadmin.php'>Modify Time</a> page to add a time.</td></tr>\n";
        echo "      </table><br /></td></tr></table>\n";
        exit;
    }

	/*
    $get_user = stripslashes($_GET['username']);

    disabled_acct($get_user);



*/
    $get_user = stripslashes($_GET['username']);
    $get_user = addslashes($get_user);



    $query = "select * from ".$db_prefix."employees where empfullname = '".$get_user."' order by empfullname";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    while ($row=mysqli_fetch_array($result)) {
        $username = stripslashes("".$row['empfullname']."");
        $displayname = stripslashes("".$row['displayname']."");
    }
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);



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
  echo '      <div class="box-header with-border">
                   <h3 class="box-title"><i class="fa fa-clock-o"></i> Añadir Tiempo</h3>
              </div>
              <div class="box-body">';

    echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
    echo "              <input type='hidden' name='date_format' value='$js_datefmt'>\n";

    echo '              <div class="form-grpup">
                          <label class="table_rows_output">
                            Nombre de usuario:
                          </label>';
    echo "                  <input type='hidden' name='post_username' value=\"$username\">$username\n";
    echo '                </div>';

    echo '              <div class="form-group">
                          <label class="table_rows_output">
                            Nombre de acceso:
                          </label>';
    echo "                <input type='hidden' name='post_displayname' value=\"$displayname\">$displayname\n";
    echo '                </div>';

    echo "    <div class='form-group'>
                <label class='table_rows_output'>
                  &nbsp;*Fecha:
                </label>
                <input type='date' size='10' maxlength='10' name='post_date' style='color:#000000'>&nbsp;&nbsp;
                <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
              </div>";


    echo"               <div class='bootstrap-timepicker'>
                          <div class='form-group' style='display: flex;'>
                            <label class='table_rows_output' style='margin-right:15px'>
                              &nbsp;*Hora:
                            </label>";
    echo"    	                <div class='input-group'>
                                <input type='text' size='10' maxlength='10' class='form-control timepicker' name='post_time' required>";
    echo"   	                    <div class='input-group-addon'>
                                    <i class='fa fa-clock-o'></i>
                                  </div>
    	                         </div>
    	                    </div>
    	                 </div>";

    echo "               <div class='form-group' style='display: flex;'>
                          <label class='table_rows_output'>
                            &nbsp;*Estado:
                          </label>";
    // query to populate dropdown with statuses //

    $query = "select punchitems from ".$db_prefix."punchlist";
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

    if ($require_time_admin_edit_reason == "yes") {
	    echo '<div class="form-group">
              <label class="table_rows_output">
                &nbsp;*Razón por la que se añade:
              </label>';
      echo "  <input type='text' size='25' maxlength='75' name='post_why'>\n";
	echo '</div>';
    } else if ($require_time_admin_edit_reason == "no") {
      echo '<div class="form-group">
              <label class="table_rows_output">
                &nbsp;*Razón por la que se añade:
              </label>';
      echo "  <input type='text' size='25' maxlength='75' name='post_why'>\n";
	echo '</div>';
    }

((mysqli_free_result( $punchlist_result ) || (is_object( $punchlist_result ) && (get_class( $punchlist_result ) == "mysqli_result"))) ? true : false);
    echo '<div class="form-group">
            <label class="table_rows_output">
              Notas:
            </label>
            <textarea id="comment" class="form-control" rows="5" name="post_notes"></textarea>';
    echo '</div>';
    echo "        <div class='required_fields' align='right'>
                    *&nbsp;Campos requeridos&nbsp
                  </div>\n";
    echo "            \n";

    echo "            \n";
    echo "              \n";

    echo '</div>';
    echo '<div class="box-footer">
                <button type="button" id="formButtons" onclick="location=\'timeadmin.php\'" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                  <i class="fa fa-ban"></i>
                  Cancelar
                </button>

                <button id="formButtons" type="submit" name="submit" value="Add Time" class="btn btn-success pull-right">
                  <i class="fa fa-plus"></i>
                  Añadir tiempo
                </button>
              </div></form>';
    echo '</div></div></div>';

    include '../theme/templates/endmaincontent.inc';
    include '../footer.php';
    include '../theme/templates/controlsidebar.inc';
    include '../theme/templates/endmain.inc';
    include '../theme/templates/reportsfooterscripts.inc';
    exit;
} elseif ($request == 'POST') { // Add the time for the employee
    @$get_user = stripslashes($_POST['get_user']);

    $post_username = stripslashes($_POST['post_username']);
    $post_displayname = stripslashes($_POST['post_displayname']);
    $post_date = $_POST['post_date'];
    $post_time = $_POST['post_time'];
    $post_statusname = $_POST['post_statusname'];
    $post_notes = $_POST['post_notes'];
    // $timefmt_24hr = $_POST['timefmt_24hr'];
    // $timefmt_24hr_text = $_POST['timefmt_24hr_text'];
    // $timefmt_size = $_POST['timefmt_size'];
    $date_format = $_POST['date_format'];
    $post_why = $_POST['post_why'];

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

    if (!empty($post_statusname)) {
        if ($post_statusname != '1') {
            $query = "select * from ".$db_prefix."punchlist where punchitems = '".$post_statusname."'";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

            while ($row=mysqli_fetch_array($result)) {
                $punchitems = "".$row['punchitems']."";
                $color = "".$row['color']."";
            }
            ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
            if (!isset($punchitems)) {
                echo "Something is fishy here.\n";
            exit;
            }
        } else {
            $punchitems = '1';
        }
    }

    if (($timefmt == "G:i") || ($timefmt == "H:i")) {
        $tmp_timefmt_24hr = '1';
        $tmp_timefmt_24hr_text = '24 hr format';
        $tmp_timefmt_size = '5';
    } else {
        $tmp_timefmt_24hr = '0';
        $tmp_timefmt_24hr_text = '12 hr format';
        $tmp_timefmt_size = '8';
    }

    if (($timefmt_24hr != $tmp_timefmt_24hr) || ($timefmt_24hr_text != $tmp_timefmt_24hr_text) || ($timefmt_size != $tmp_timefmt_size)) {
        echo "Something is fishy here.\n";
        exit;
    }
    if ($date_format != $js_datefmt) {
        echo "Something is fishy here.\n";
        exit;
    }

    // Escape input
//    $post_notes = ereg_replace("[^[:alnum:] \,\.\?-]", "", $post_notes);
    $post_notes = preg_replace('/' . "[^[:alnum:] \,\.\?-]" . '/', "", $post_notes);
    if ($post_notes == "") {
        $post_notes = " ";
    }

    // Escape admin reason for SQL
    if (empty($post_why)) {
        $post_why = '';
    } else {
//        $post_why = ereg_replace("[^[:alnum:] \,\.\?-]", "", $post_why);
	$post_why = preg_replace('/' . "[^[:alnum:] \,\.\?-]". '/', "", $post_why);
    }

    // end post validation //

    /* This whole section is commented out, we need to look into it.
    if ($get_user != $post_username) {
        exit;
    }
    if (($timefmt_24hr !== '0') && ($timefmt_24hr !== '1')) {
        exit;
    }
    if (($timefmt_24hr_text !== '24 hr format') && ($timefmt_24hr_text !== '12 hr format')) {
        exit;
    }
    if (($timefmt_size != '5') && ($timefmt_size != '7')) {
        exit;
    }
    */

    $get_user = stripslashes($get_user);
    $post_username = stripslashes($post_username);
    $post_displayname = stripslashes($post_displayname);

    /*

*/

//    if ((empty($post_date)) || (empty($post_time)) || ($post_statusname == '1') || (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_statusname)) || (!eregi ("^([0-9]{1,2})[-,/,.]([0-9]{1,2})[-,/,.](([0-9]{2})|([0-9]{4}))$", $post_date))) {

    if ((empty($post_date)) || (empty($post_time)) || ($post_statusname == '1') || (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_statusname))
    // ||  (!preg_match("/^([0-9]{1,2})[\-\/\.]([0-9]{1,2})[\-\/\.](([0-9]{2})|([0-9]{4}))$/i", $post_date))
    ) {

        $evil_post = '1';
        if (empty($post_date)) {
          echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                              Se requiere una fecha válida.
                            </div>';
        } elseif (empty($post_time)) {
          echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                              Se requiere una hora válido.
                            </div>';
        } elseif ($post_statusname == "1") {
          echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                              Se requiere escoger un estado.
                            </div>';
//        } elseif (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_statusname)) {
 } elseif (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_statusname)) {
   echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                     <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                       No están permitidos caracteres alfanuméricos, acentos, apostrofes, comas y espacios para crear un estado.
                     </div>';
 //       } elseif (!eregi ("^([0-9]{1,2})[-,/,.]([0-9]{1,2})[-,/,.](([0-9]{2})|([0-9]{4}))$", $post_date)) {
 }
 //elseif  (!preg_match("/^([0-9]{1,2})[\-\/\.]([0-9]{1,2})[\-\/\.](([0-9]{2})|([0-9]{4}))$/i", $post_date)) {
 //            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
 //            echo "              <tr>\n";
 //            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid Date is required. 2</td></tr>\n";
 //            echo "            </table>\n";
 //        }
}
//	elseif ($timefmt_24hr == '0') {
//        if ((!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])+([a|p]+m)$", $post_time, $time_regs)) && (!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])+( [a|p]+m)$", $post_time, $time_regs))) {
	elseif ($timefmt_24hr == '0') {
	        if ((!preg_match('/' . "^([0-9]?[0-9])+:+([0-9]+[0-9])+([a|p]+m)$" . '/i', $post_time, $time_regs)) && (!preg_match('/' . "^([0-9]?[0-9])+:+([0-9]+[0-9])+( [a|p]+m)$" . '/i', $post_time,
	                                                                                                     $time_regs))
	        ) {
            $evil_time = '1';
            echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                              <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                                Se requiere un hora válido.
                              </div>';
        } else {
            if (isset($time_regs)) {
                $h = $time_regs[1];
                $m = $time_regs[2];
            }
            $h = $time_regs[1]; $m = $time_regs[2];
            if (($h > 12) || ($m > 59)) {
                $evil_time = '1';
                echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                  <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                                    Se requiere un hora válido.
                                  </div>';
            }
        }
    }

//	elseif ($timefmt_24hr == '1') {
//        if (!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])$", $post_time, $time_regs)) {
	elseif ($timefmt_24hr == '1') {
	        if (!preg_match('/' . "^([0-9]?[0-9])+:+([0-9]+[0-9])$" . '/i', $post_time, $time_regs)) {
            $evil_time = '1';
            echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                              <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                                Se requiere un hora válido.
                              </div>';
        } else {
            if (isset($time_regs)) {
                $h = $time_regs[1];
                $m = $time_regs[2];
            }
            $h = $time_regs[1]; $m = $time_regs[2];
            if (($h > 24) || ($m > 59)) {
                $evil_time = '1';
                echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                  <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                                    Se requiere un hora válido.
                                  </div>';
            }
        }
    }

//    if (eregi ("^([0-9]{1,2})[-,/,.]([0-9]{1,2})[-,/,.](([0-9]{2})|([0-9]{4}))$", $post_date, $date_regs)) {
if (preg_match("/^([0-9]{1,2})[\-\/\.]([0-9]{1,2})[\-\/\.](([0-9]{2})|([0-9]{4}))$/i", $post_date, $date_regs)) {
        if ($calendar_style == "amer") {
            if (isset($date_regs)) {
                $month = $date_regs[1];
                $day = $date_regs[2];
                $year = $date_regs[3];
            }
            if ($month > 12 || $day > 31) {
                $evil_date = '1';
                if (!isset($evil_post)) {
                  echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                                      Se requiere una fecha válida.
                                    </div>';
                }
            }
        } elseif ($calendar_style == "euro") {
            if (isset($date_regs)) {
                $month = $date_regs[2];
                $day = $date_regs[1];
                $year = $date_regs[3];
            }
            if ($month > 12 || $day > 31) {
                $evil_date = '1';
                if (!isset($evil_post)) {
                  echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                                      Se requiere una fecha válida.
                                    </div>';
                }
            }
        }
    }

    if (($require_time_admin_edit_reason == "yes") && empty($post_why)) { // Ensure that the admin gives a reason for the addition
        $evil_why = True;
        echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                            Se requiere una razón por la cuál se añade la hora.
                          </div>';
    }

    if ((isset($evil_post)) || (isset($evil_date)) || (isset($evil_time)) || (isset($evil_why))) { // Display error message
        echo "            <br />\n";
          echo '<div class="row">
                  <div id="float_window" class="col-md-10">
                    <div class="box box-info"> ';
          echo '      <div class="box-header with-border">
                           <h3 class="box-title"><i class="fa fa-clock-o"></i> Añadir Tiempo</h3>
                      </div>
                      <div class="box-body">';

            echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
            echo "              <input type='hidden' name='date_format' value='$js_datefmt'>\n";
            echo '              <div class="form-group">
                                  <label class="table_rows_output">
                                    Nombre de usuario:
                                  </label>';
            echo "                <input type='hidden' name='post_username' value=\"$post_username\">$post_username\n";
            echo '              </div>';

            echo '              <div class="form-group">
                                  <label class="table_rows_output">
                                    Nombre de acceso:
                                  </label>';
            echo "                <input type='hidden' name='post_displayname' value=\"$post_displayname\">$post_displayname\n";
            echo '              </div>';

            echo "    <div class='form-group'>
                        <label class='table_rows_output'>
                          &nbsp;*Fecha:
                        </label>
                        <input type='date' size='10' maxlength='10' name='post_date' value=\"$post_date\" style='color:#000000'>
                        <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                        return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                      </div>";


            echo"               <div class='bootstrap-timepicker'>
                                  <div class='form-group' style='display: flex;'>
                                    <label class='table_rows_output' style='margin-right:15px'>
                                      &nbsp;*Hora:
                                    </label>";
            echo"    	                <div class='input-group'>
                                        <input type='text' size='10' maxlength='10' class='form-control timepicker' name='post_time' required>";
            echo"   	                    <div class='input-group-addon'>
                                            <i class='fa fa-clock-o'></i>
                                          </div>
            	                         </div>
              	                    </div>
              	                 </div>";

            echo "               <div class='form-group' style='display: flex;'>
                                   <label class='table_rows_output'>
                                     &nbsp;*Estado:
                                   </label>";

           // query to populate dropdown with statuses //

           $query = "select punchitems from ".$db_prefix."punchlist";
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
           if ($require_time_admin_edit_reason == "yes") {
             echo '<div class="form-group">
                     <label class="table_rows_output">
                       &nbsp;*Razón por la que se añade:
                     </label>';
             echo "  <input type='text' size='25' maxlength='250' name='post_why'>\n";
       	echo '</div>';
           } else if ($require_time_admin_edit_reason == "no") {
             echo '<div class="form-group">
                     <label class="table_rows_output">
                       &nbsp;*Razón por la que se añade:
                     </label>';
             echo "  <input type='text' size='25' maxlength='250' name='post_why'>\n";
       	echo '</div>';
           }

        ((mysqli_free_result( $punchlist_result ) || (is_object( $punchlist_result ) && (get_class( $punchlist_result ) == "mysqli_result"))) ? true : false);
           echo '<div class="form-group">
                   <label class="table_rows_output">
                     Notas:
                   </label>
                   <input type="text" name="post_notes" maxlength="250" class="form-control" style="width: 98%;" >';
           echo '</div>';
           echo "        <div class='required_fields' align='right'>
                           *&nbsp;Campos requeridos&nbsp
                         </div>\n";

           echo "            \n";
           echo "              \n";
           echo '<div class="box-footer">
                         <button type="button" id="formButtons" onclick="location=\'timeadmin.php\'" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                           <i class="fa fa-ban"></i>
                           Cancelar
                         </button>

                         <button id="formButtons" type="submit" name="submit" value="Add Time" class="btn btn-success pull-right">
                           <i class="fa fa-plus"></i>
                           Añadir tiempo
                         </button>
                     </div></form>';

           echo '</div></div></div></div>';
           include '../theme/templates/endmaincontent.inc';
           include '../footer.php';
       	include '../theme/templates/controlsidebar.inc';
       	include '../theme/templates/endmain.inc';
       	include '../theme/templates/reportsfooterscripts.inc';
           exit;
    } else { // Display add time interface
        $post_username = addslashes($post_username);
        $post_displayname = addslashes($post_displayname);


        // configure timestamp to insert/update


        $timestamp = strtotime($post_date . " " . $post_time) - $tzo;

        // check for duplicate time for $post_username
        $query = "select * from ".$db_prefix."info where fullname = '".$post_username."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        $post_username = stripslashes($post_username);
        $post_displayname = stripslashes($post_displayname);

        while ($row=mysqli_fetch_array($result)) {
            $info_table_timestamp = "".$row['timestamp']."";
            if ($timestamp == $info_table_timestamp) {
              echo '            <div id="float_alert" class="col-md-10 alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-warning"></i>¡Alerta!</h4>
                                  La hora de "<b>'. $post_statusname .'</b>" para el usuario <b>"'. $post_username .'</b>" ya existe en la fecha seleccionada.
                                  Por favor introduce otra hora diferente u otra fecha.
                                </div><br />';

                echo '<div class="row">
                        <div id="float_window" class="col-md-10">
                          <div class="box box-info"> ';
                echo '      <div class="box-header with-border">
                                 <h3 class="box-title"><i class="fa fa-clock-o"></i> Añadir Tiempo</h3>
                            </div>
                            <div class="box-body">';

                  echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
                  echo "              <input type='hidden' name='date_format' value='$js_datefmt'>\n";
                  echo '              <div class="form-group">
                                        <label class="table_rows_output">
                                          Nombre de usuario:
                                        </label>';
                  echo "                <input type='hidden' name='post_username' value=\"$post_username\">$post_username\n";
                  echo '              </div>';
                  echo '              <div class="form-group">
                                        <label class="table_rows_output">
                                          Nombre de acceso:
                                        </label>';
                  echo "                <input type='hidden' name='post_displayname' value=\"$post_displayname\">$post_displayname\n";
                  echo '              </div>';

                  echo "    <div class='form-group'>
                              <label class='table_rows_output'>
                                *&nbsp;Fecha:
                              </label>

                              <input type='date' size='10' maxlength='10' name='post_date' style='color:#27408b'>
                              <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                              return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                               </div>";


                  echo"               <div class='bootstrap-timepicker'>
                                       <div class='form-group' style='display: flex;'>
                                         <label class='table_rows_output' style='margin-right:15px'>
                                          &nbsp;*Hora:
                                         </label>";
                  echo"    	                <div class='input-group'>
                                             <input type='text' size='10' maxlength='10' class='form-control timepicker' name='post_time' required>";
                  echo"                          <div class='input-group-addon'>
                                                   <i class='fa fa-clock-o'></i>
                                                 </div>
                     	                      </div>
                               	           </div>
                               	          </div>";

                  echo "               <div class='form-group' style='display: flex;'>
                                        <label class='table_rows_output'>
                                          &nbsp;*Estado:
                                        </label>";


                 // query to populate dropdown with statuses //

                 $query = "select punchitems from ".$db_prefix."punchlist";
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
                 if ($require_time_admin_edit_reason == "yes") {
                   echo '<div class="form-group">
                           <label class="table_rows_output">
                             &nbsp;*Razón por la que se añade:
                           </label>';
                   echo "  <input type='text' size='25' maxlength='250' name='post_why'>\n";
             	echo '</div>';
                 } else if ($require_time_admin_edit_reason == "no") {
                   echo '<div class="form-group">
                           <label class="table_rows_output">
                             &nbsp;*Razón por la que se añade:
                           </label>';
                   echo "  <input type='text' size='25' maxlength='250' name='post_why'>\n";
             	echo '</div>';
                 }

            ((mysqli_free_result( $punchlist_result ) || (is_object( $punchlist_result ) && (get_class( $punchlist_result ) == "mysqli_result"))) ? true : false);
                     echo '<div class="form-group">
                             <label class="table_rows_output">
                               Notas:
                             </label>
                             <input type="text" name="post_notes" maxlength="250" class="form-control" style="width: 98%;" >';
                     echo '</div>';
                     echo "        <div class='required_fields' align='right'>
                                     *&nbsp;Campos requeridos&nbsp
                                   </div>\n";
                 echo "            \n";

                 echo "            \n";
                 echo "              \n";
                 echo '<div class="box-footer">
                             <button type="button" id="formButtons" onclick="location=\'timeadmin.php\'" class="btn btn-default pull-right" style="margin: 0px 10px 0px 10px;">
                               <i class="fa fa-ban"></i>
                               Cancelar
                             </button>

                             <button id="formButtons" type="submit" name="submit" value="Add Time" class="btn btn-success pull-right">
                               <i class="fa fa-plus"></i>
                               Añadir tiempo
                             </button>
                           </div></form>';

                 echo '</div></div></div></div>';
                 include '../theme/templates/endmaincontent.inc';
                 include '../footer.php';
              include '../theme/templates/controlsidebar.inc';
              include '../theme/templates/endmain.inc';
              include '../theme/templates/reportsfooterscripts.inc';
                 exit;
            }
        }
        ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

        // check to see if this would be the most recent time for $post_username. if so, run the update query for the employees table.
        $post_username = addslashes($post_username);
        $post_displayname = addslashes($post_displayname);

        $query = "select * from ".$db_prefix."employees where empfullname = '".$post_username."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        while ($row=mysqli_fetch_array($result)) {
            $employees_table_timestamp = "".$row['tstamp']."";
        }
        ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

        if ($timestamp > $employees_table_timestamp) {
            $update_query = "update ".$db_prefix."employees set tstamp = '".$timestamp."' where empfullname = '".$post_username."'";
            $update_result = mysqli_query($GLOBALS["___mysqli_ston"], $update_query);
        }

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
        // $time_hour = gmdate('H', $time);
        // $time_min = gmdate('i', $time);
        // $time_sec = gmdate('s', $time);
        // $time_month = gmdate('m', $time);
        // $time_day = gmdate('d', $time);
        // $time_year = gmdate('Y', $time);
        // $time_tz_stamp = time ($time_hour, $time_min, $time_sec, $time_month, $time_day, $time_year);

        $time_tz_stamp = time();


        // add the time to the info table for $post_username and audit log
        if (strtolower($ip_logging) == "yes") {
            $query = "insert into ".$db_prefix."info (fullname, `inout`, timestamp, notes, ipaddress) values ('".$post_username."', '".$post_statusname."', '".$timestamp."', '".$post_notes."', '".$connecting_ip."')";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
            $query2 = "insert into ".$db_prefix."audit (modified_by_ip, modified_by_user, modified_when, modified_from, modified_to, modified_why, user_modified) values ('".$connecting_ip."', '".$user."', '".$time_tz_stamp."', '0', '".$timestamp."', '".$post_why."', '".$post_username."')";
            $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
        } else {
            $query = "insert into ".$db_prefix."info (fullname, `inout`, timestamp, notes) values ('".$post_username."', '".$post_statusname."', '".$timestamp."', '".$post_notes."')";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
            $query2 = "insert into ".$db_prefix."audit (modified_by_user, modified_when, modified_from, modified_to, modified_why, user_modified) values ('".$user."', '".$time_tz_stamp."', '0', '".$timestamp."', '".$post_why."', '".$post_username."')";
            $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
        }

        $post_username = stripslashes($post_username);
        $post_displayname = stripslashes($post_displayname);
        $post_date = date($datefmt, $timestamp + $tzo);

        $date_format = strftime("%A %d de %B del %Y", $timestamp + $tzo);

        echo '       <div id="float_alert" class="col-md-10"><div class="alert alert-success alert-dismissible">
                     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                     <h4><i class="icon fa fa-check-circle"></i>Hora añadida!</h4>
                        La hora de "<b>'. $post_statusname .'</b>" ha sido añadida satisfactoriamente al usuario "<b>'. $post_username .'</b>".
                     </div></div>';
        echo '<div class="row">
          <div id="float_window" class="col-md-10">
            <div class="box box-info"> ';
        echo '<div class="box-header with-border">
                       <h3 class="box-title"><i class="fa fa-clock-o"></i> Añadir Tiempo</h3>
                     </div><div class="box-body">';
        echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate();\">\n";
        echo "            <table align=center class='table'>\n";
        echo "              <tr>
                              <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                                Nombre de usuario:
                              </td>

                              <td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$post_username
                              </td>
                            </tr>\n";
        echo "              <tr>
                              <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                                Nombre de acceso:
                              </td>

                              <td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$post_displayname
                              </td>
                            </tr>\n";
        echo "              <tr>
                              <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                                Fecha:
                              </td>

                              <td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$post_date
                              </td>
                            </tr>\n";

        echo "              <tr>
                              <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                                Hora:
                              </td>

                              <td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$post_time
                              </td>
                            </tr>\n";
        echo "              <tr>
                              <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                                Estado:
                              </td>

                              <td align=left class=table_rows colspan=2 width=80% style='color:$color;padding-left:20px;'>$post_statusname
                              </td>
                            </tr>\n";
        echo "              <tr>
                              <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                                Notas:
                              </td>

                              <td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$post_notes
                              </td>
                            </tr>\n";
        echo "              <tr>
                              <td class=table_rows_output height=25 width=20% style='padding-left:32px;' nowrap>
                                Razón por la que se añade:
                              </td>

                              <td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$post_why
                              </td>
                            </tr>\n";
        echo "            </table>\n";
        echo "            <div class='box-footer'>
                            <button type='button' id='formButtons' onclick='location=\"timeadmin.php\"' class='btn btn-success pull-right'>
                              Aceptar
                              <i class='fa fa-check'></i>
                            </button>
                          </div>\n";
	echo'</div></div></div></div>';
	include '../theme/templates/endmaincontent.inc';
  include '../footer.php';
	include '../theme/templates/controlsidebar.inc';
	include '../theme/templates/endmain.inc';
	include '../theme/templates/reportsfooterscripts.inc';
  exit;
    }
}
?>
