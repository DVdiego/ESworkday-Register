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
$current_page = "total_hours.php";
setlocale(LC_ALL,'es_ES.UTF-8');
include '../config.inc.php';

if ($use_reports_password == "yes") {

if (!isset($_SESSION['valid_reports_user'])) {

include '../admin/header.php';
include '../admin/topmain.php';
include 'leftmain.php';
echo "<title>$title</title>\n";

echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>WorkTime Control Reports</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=reports'><u>here</u></a> to login.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}
}

echo "<title>$title -Informe de horas trabajadas</title>\n";

if ($request == 'GET') {

include 'header_get_reports.php';

if ($use_reports_password == "yes") {
include '../admin/topmain.php';
include 'leftmain.php';
} else {
include 'topmain.php';
include 'leftmain.php';
}

echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info"> ';
echo '      <div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-user"></i> Informe de horas trabajadas</h3>
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

            echo "<div class='form-group'><label style='padding-right: 100px;'>Elija una oficina: </label> <select name='office_name' class='form-control select2 pull-right' style='width: 50%;' onchange='group_names();'></select></div>";

            echo "<div class='form-group'><label style='padding-right: 113px;'>Elija un grupo: </label> <select name='group_name' class='form-control select2 pull-right' style='width: 50%;' onchange='user_names();'></select></div>\n";

            echo "             <div class='form-group'><label style='padding-right: 34px;'>Elija un nombre de usuario: </label> <select name='user_name' class='form-control select2 pull-right' style='width: 50%;'></select></div>\n";

            }


            echo "              <div id='dates' class='form-group'>
                                  <label style='padding-right: 127px;'>Fecha Inicio:</label>
                                    <div class='input-group'>

                                    <div class='input-group-addon'>
                                      <i class='fa fa-calendar'></i>
                                    </div>
                                      <input type='date' size='10' maxlength='10' name='from_date' style='color: #444;border: #d2d6de;border-style: solid;border-width: thin;height: 33px;width: 149px;padding-left: 10px;' required>
                                      <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                                      return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                                    </div>
                                </div>\n";
            echo "              <div id='dates' class='form-group'>
                                  <label style='padding-right: 142px;'>Fecha Fin:</label>
                                    <div class='input-group'>
                                    <div class='input-group-addon'>
                                      <i class='fa fa-calendar'></i>
                                    </div>
                                      <input type='date' size='10' maxlength='10' name='to_date' style='color: #444;border: #d2d6de;border-style: solid;border-width: thin;height: 33px;width: 149px;padding-left: 10px;' required>
                                      <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
                                      return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
                                    </div>
                                </div>\n";



echo "            <table align=left width=100% border=0 cellpadding=0 cellspacing=3>\n";
echo "              <tr><td class=table_rows height=25 valign=bottom>1.&nbsp;&nbsp;&nbsp;¿Exportar a .CSV (el enlace al archivo .CSV estará en la parte superior derecha de la página siguiente)</td></tr>\n";
if (strtolower($export_csv) == "yes") {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='csv' value='1' checked>&nbsp;Si
                      <input type='radio' name='csv' value='0'>&nbsp;No</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='csv' value='1'>&nbsp;Si
                      <input type='radio' name='csv' value='0' checked>&nbsp;No</td></tr>\n";
}
echo "              <tr><td class=table_rows height=25 valign=bottom>2.&nbsp;&nbsp;&nbsp;¿Visualizar el tiempo de cada usuario de forma separada?</td></tr>\n";
if ($paginate == "yes") {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_paginate' value='1' checked>&nbsp;Si
                      <input type='radio' name='tmp_paginate' value='0'>&nbsp;No</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_paginate' value='1'>&nbsp;Si
                      <input type='radio' name='tmp_paginate' value='0' checked>&nbsp;No</td></tr>\n";
}
echo "              <tr><td class=table_rows height=25 valign=bottom>3.&nbsp;&nbsp;&nbsp;¿Mostrar detalles de entrada/salida?</td></tr>\n";

if (strtolower($ip_logging) == "yes") {
    if ($show_details == 'yes') {
    echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_show_details' value='1'
                          checked onFocus=\"javascript:form.tmp_display_ip[0].disabled=false;form.tmp_display_ip[1].disabled=false;\">&nbsp;Si&nbsp;<input
                          type='radio' name='tmp_show_details' value='0' onFocus=\"javascript:form.tmp_display_ip[0].disabled=true;
                          form.tmp_display_ip[1].disabled=true;\">&nbsp;No</td></tr>\n";
    } else {
    echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_show_details' value='1'
                          onFocus=\"javascript:form.tmp_display_ip[0].disabled=false;form.tmp_display_ip[1].disabled=false;\">&nbsp;Si&nbsp;<input
                          type='radio' name='tmp_show_details' value='0' checked onFocus=\"javascript:form.tmp_display_ip[0].disabled=true;
                          form.tmp_display_ip[1].disabled=true;\">&nbsp;No</td></tr>\n";
    }
} else {
    if ($show_details == 'yes') {
    echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_show_details' value='1'
                          checked>&nbsp;Si&nbsp;<input type='radio' name='tmp_show_details' value='0'>&nbsp;No</td></tr>\n";
    } else {
    echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_show_details' value='1'
                          >&nbsp;Si&nbsp;<input type='radio' name='tmp_show_details' value='0' checked>&nbsp;No</td></tr>\n";
    }
}
if (strtolower($ip_logging) == "yes") {

    echo "              <tr><td class=table_rows height=25 valign=bottom>4.&nbsp;&nbsp;&nbsp;¿Desea mostrar la información de la dirección IP de conexión?
                          (Solo está disponible si la opción de \"Mostrar detalles de entrada/salida\", está configurada como \"Si\".)</td></tr>\n";
    if ($show_details == 'yes') {
    if ($display_ip == "yes") {
    echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_display_ip' value='1'
                          checked>&nbsp;Si&nbsp;<input type='radio' name='tmp_display_ip' value='0'>&nbsp;No</td></tr>\n";
    } else {
    echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_display_ip' value='1' >&nbsp;Si
                          <input type='radio' name='tmp_display_ip' value='0' checked>&nbsp;No</td></tr>\n";
    }
    } else {
    if ($display_ip == "yes") {
    echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_display_ip' value='1'
                          checked disabled>&nbsp;Si&nbsp;<input type='radio' name='tmp_display_ip' value='0' disabled>&nbsp;No</td></tr>\n";
    } else {
    echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_display_ip' value='1'
                          disabled>&nbsp;Si&nbsp;<input type='radio' name='tmp_display_ip' value='0' checked disabled>&nbsp;No</td></tr>\n";
    }
    }
}
if (strtolower($ip_logging) == "yes") {
echo "              <tr><td colspan=2 class=table_rows height=25 valign=bottom>5.&nbsp;&nbsp;&nbsp;¿Redondear el tiempo de cada usuario?</td></tr>\n";
} else {
echo "              <tr><td colspan=2 class=table_rows height=25 valign=bottom>4.&nbsp;&nbsp;&nbsp;¿Redondear el tiempo de cada usuario?</td></tr>\n";
}
if ($round_time == '1') {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='1'
                      checked>&nbsp;A los 5 minutos más cercanos (1/12 de una hora)</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='1'>
                      &nbsp;A los 5 minutos más cercanos (1/12 de una hora)</td></tr>\n";
}
if ($round_time == '2') {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='2'
                      checked>&nbsp;A los 10 minutos más cercanos (1/6 de hora).</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='2'>
                      &nbsp;A los 10 minutos más cercanos (1/6 de hora).</td></tr>\n";
}
if ($round_time == '3') {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='3'
                      checked>&nbsp;A los 15 minutos más cercanos (1/4 de hora).</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='3'>
                      &nbsp;A los 15 minutos más cercanos (1/4 de hora).</td></tr>\n";
}
if ($round_time == '4') {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='4'
                      checked>&nbsp;A los 20 minutos más cercanos (1/3 de hora).</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='4'>
                      &nbsp;A los 20 minutos más cercanos (1/3 de hora).</td></tr>\n";
}
if ($round_time == '5') {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='5'
                      checked>&nbsp;A los 30 minutos más cercanos (1/2 de hora).</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='5'>
                      &nbsp;A los 30 minutos más cercanos (1/2 de hora).</td></tr>\n";
}
if (empty($round_time)) {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value=0 checked>
                      &nbsp;No redondear</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value=0>
                      &nbsp;No redondear</td></tr>\n";
}
echo "              <tr><td height=10></td></tr>\n";

// Ask the user if he wishes to display the employee's who have empty hours.
if (strtolower($ip_logging) == "yes") {
  echo "              <tr><td class=table_rows height=25 valign=bottom>6.&nbsp;&nbsp;&nbsp;¿Mostrar registros de empleados con horas vacías?</td></tr>\n";
}
else {
  echo "              <tr><td class=table_rows height=25 valign=bottom>5.&nbsp;&nbsp;&nbsp;¿Mostrar registros de empleados con horas vacías?</td></tr>\n";
}
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='displayEmptyHours' checked value='1'> Si</input> <input type='radio' name='displayEmptyHours' value='0'> No</input></td></tr>\n";
echo "            </table>\n";
echo "            <table align=center width=100% border=0 cellpadding=0 cellspacing=3>\n";
echo "<tr><td>";
echo "<div class='box-footer'>
        <button type='button' id='formButtons' onclick='location=\"index.php\"' class='btn btn-default pull-right'>
          <i class='fa fa-ban'></i>
          Cancelar
        </button>

        <button id='formButtons' type='submit' class='btn btn-success'>
          Siguiente
          <i class='fa fa-arrow-right'></i>
        </button>
        </div>
      </div>";
echo "</td></tr>";
echo "</table></form>\n";
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

include 'header_post_reports.php';

@$office_name = $_POST['office_name'];
@$group_name = $_POST['group_name'];
$fullname = stripslashes($_POST['user_name']);
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$tmp_paginate = $_POST['tmp_paginate'];
$tmp_round_time = $_POST['tmp_round_time'];
$tmp_show_details = $_POST['tmp_show_details'];
@$tmp_display_ip = $_POST['tmp_display_ip'];
@$tmp_csv = $_POST['csv'];
$displayEmptyHours = $_POST['displayEmptyHours'];

$fullname = addslashes($fullname);

// begin post validation //

if ($fullname != "All") {
$query = "select empfullname, displayname from ".$db_prefix."employees where empfullname = '".$fullname."'";
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

if ((!empty($tmp_round_time)) && ($tmp_round_time != '1') && ($tmp_round_time != '2') && ($tmp_round_time != '3') && ($tmp_round_time != '4') &&
($tmp_round_time != '5')) {
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
                    Choose a rounding method.</td></tr>\n";
echo "            </table>\n";
}
if (($tmp_paginate != '1') && (!empty($tmp_paginate))) {
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
                    Choose \"yes\" or \"no\" to the \"<b>Paginate This Report?</b>\" question.</td></tr>\n";
echo "            </table>\n";
}
elseif (($tmp_show_details != '1') && (!empty($tmp_show_details))) {
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
                    Choose \"yes\" or \"no\" to the \"<b>Show Punch-in/out Details?</b>\" question.</td></tr>\n";
echo "            </table>\n";
}
elseif (isset($tmp_display_ip)) {
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
                    Choose \"yes\" or \"no\" to the \"<b>Show Punch-in/out Details?</b>\" question.</td></tr>\n";
echo "            </table>\n";
}}
elseif (isset($tmp_csv)) {
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
}}

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
// elseif (!eregi ("^([0-9]?[0-9])+[-|/|.]+([0-9]?[0-9])+[-|/|.]+(([0-9]{2})|([0-9]{4}))$", $from_date, $date_regs)) {
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
}}

elseif ($calendar_style == "euro") {
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
}}}}

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
}}

elseif ($calendar_style == "euro") {
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
}}}}

if (isset($evil_post)) {



echo "            <br />\n";
echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info"> ';
echo '      <div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-suitcase"></i>  Create Status</h3>
            </div>
            <div class="box-body">';
echo "            <form name='form' action='$self' method='post' onsubmit=\"return isFromOrToDate();\">\n";
echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/report.png' />&nbsp;&nbsp;&nbsp;
                   Informe de horas trabajadas</th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <input type='hidden' name='date_format' value='$js_datefmt'>\n";
if ($username_dropdown_only == "yes") {

$query = "select empfullname from ".$db_prefix."employees order by empfullname asc";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Username:</td><td colspan=2 align=left width=80%
                      style='padding-left:20px;'>
                  <select name='user_name'>\n";
echo "                    <option value ='All'>All</option>\n";

while ($row=mysqli_fetch_array($result)) {
  $empfullname_tmp = stripslashes("".$row['empfullname']."");
  echo "                    <option>$empfullname_tmp</option>\n";
}

echo "                  </select>&nbsp;*</td></tr>\n";
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
} else {

echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Elija una oficina:</td><td colspan=2 width=80%
                      style='padding-left:20px;'>
                      <select name='office_name' onchange='group_names();'>\n";
echo "                      </select></td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Elija un grupo:</td><td colspan=2 width=80%
                      style='padding-left:20px;'>
                      <select name='group_name' onfocus='group_names();'>
                          <option selected>$group_name</option>\n";
echo "                      </select></td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Elija un nombre de usuario:</td><td colspan=2 width=80%
                      style='padding-left:20px;'>
                      <select name='user_name' onfocus='user_names();'>
                          <option selected>$fullname</option>\n";
echo "                      </select></td></tr>\n";
}
// echo "              <tr><td class=table_rows style='padding-left:32px;' width=20% nowrap>From Date: ($tmp_datefmt)</td><td
//                       style='padding-left:20px;' width=80% >
//                       <input type='text' size='10' maxlength='10' name='from_date' value='$from_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
//                       <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
//                       return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'>Pick Date</a></td><tr>\n";
// echo "              <tr><td class=table_rows style='padding-left:32px;' width=20% nowrap>To Date: ($tmp_datefmt)</td><td
//                       style='padding-left:20px;' width=80% >
//                       <input type='text' size='10' maxlength='10' name='to_date' value='$to_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
//                       <a href=\"#\" onclick=\"form.to_date.value='';cal.select(document.forms['form'].to_date,'to_date_anchor','$js_datefmt');
//                       return false;\" name=\"to_date_anchor\" id=\"to_date_anchor\" style='font-size:11px;color:#27408b;'>Pick Date</a></td><tr>\n";
echo "<tr><td>";
echo "    <div class='form-group'>
            <label>Fecha inicio:</label>
            <input type='date' size='10' maxlength='10' name='from_date' value=\"$from_date\" style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
            <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt');
            return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'></a>
             </div>";

echo "    <div class='form-group'>
           <label>Fecha fin:</label>
           <input type='date' size='10' maxlength='10' name='to_date' value=\"$to_date\" style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
           <a href=\"#\" onclick=\"form.to_date.value='';cal.select(document.forms['form'].to_date,'to_date_anchor','$js_datefmt');
           return false;\" name=\"to_date_anchor\" id=\"to_date_anchor\" style='font-size:11px;color:#27408b;'></a>
            </div>";
echo "</tr></td>";
echo "              <tr><td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
// echo "            <div style=\"position:absolute;visibility:hidden;background-color:#ffffff;layer-background-color:#ffffff;\" id=\"mydiv\"
//                  height=200>&nbsp;</div>\n";
echo "            <table align=center width=100% border=0 cellpadding=0 cellspacing=3>\n";
echo "              <tr><td class=table_rows height=25 valign=bottom>1.&nbsp;&nbsp;&nbsp;Export to CSV? (link to CSV file will be in the top right of
                      the next page)</td></tr>\n";
if ($tmp_csv == "1") {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='csv' value='1'
                      checked>&nbsp;Si<input type='radio' name='csv' value='0'>&nbsp;No</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='csv' value='1' >&nbsp;Si
                      <input type='radio' name='csv' value='0' checked>&nbsp;No</td></tr>\n";
}
echo "              <tr><td class=table_rows valign=bottom height=25 valign=bottom>2.&nbsp;&nbsp;&nbsp;Paginate this report so each user's time is printed
                      on a separate page?</td></tr>\n";
if ($tmp_paginate == '1') {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_paginate' value='1' checked>&nbsp;Si
                      <input type='radio' name='tmp_paginate' value='0'>&nbsp;No</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_paginate' value='1'>&nbsp;Si
                      <input type='radio' name='tmp_paginate' value='0' checked>&nbsp;No</td></tr>\n";
}
echo "              <tr><td class=table_rows height=25 valign=bottom>3.&nbsp;&nbsp;&nbsp;Show punch-in/out details?</td></tr>\n";
if (strtolower($ip_logging) == "yes") {
if ($tmp_show_details == '1') {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_show_details' value='1'
                      checked onFocus=\"javascript:form.tmp_display_ip[0].disabled=false;form.tmp_display_ip[1].disabled=false;\">&nbsp;Si&nbsp;<input
                      type='radio' name='tmp_show_details' value='0' onFocus=\"javascript:form.tmp_display_ip[0].disabled=true;
                      form.tmp_display_ip[1].disabled=true;\">&nbsp;No</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_show_details' value='1'
                      onFocus=\"javascript:form.tmp_display_ip[0].disabled=false;form.tmp_display_ip[1].disabled=false;\">&nbsp;Si&nbsp;<input
                      type='radio' name='tmp_show_details' value='0' checked onFocus=\"javascript:form.tmp_display_ip[0].disabled=true;
                      form.tmp_display_ip[1].disabled=true;\">&nbsp;No</td></tr>\n";
}
} else {
if ($tmp_show_details == '1') {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_show_details' value='1'
                      checked>&nbsp;Si&nbsp;<input type='radio' name='tmp_show_details' value='0'>&nbsp;No</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_show_details' value='1'
                      >&nbsp;Si&nbsp;<input type='radio' name='tmp_show_details' value='0' checked>&nbsp;No</td></tr>\n";
}
}
if (strtolower($ip_logging) == "yes") {
echo "              <tr><td class=table_rows height=25 valign=bottom>4.&nbsp;&nbsp;&nbsp;Display connecting ip address information?
                      (only available if \"Show punch-in/out details?\" is set to \"Yes\".)</td></tr>\n";
if ($tmp_show_details == '1') {
if ($tmp_display_ip == "1") {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_display_ip' value='1'
                      checked>&nbsp;Si&nbsp;<input type='radio' name='tmp_display_ip' value='0'>&nbsp;No</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_display_ip' value='1' >&nbsp;Si
                      <input type='radio' name='tmp_display_ip' value='0' checked>&nbsp;No</td></tr>\n";
}
} else {
if ($tmp_display_ip == "1") {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_display_ip' value='1'
                      checked disabled>&nbsp;Si&nbsp;<input type='radio' name='tmp_display_ip' value='0' disabled>&nbsp;No</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_display_ip' value='1'
                      disabled>&nbsp;Si&nbsp;<input type='radio' name='tmp_display_ip' value='0' checked disabled>&nbsp;No</td></tr>\n";
}
}
}
if (strtolower($ip_logging) == "yes") {
echo "              <tr><td colspan=2 class=table_rows height=25 valign=bottom>5.&nbsp;&nbsp;&nbsp;Round each user's time?</td></tr>\n";
} else {
echo "              <tr><td colspan=2 class=table_rows height=25 valign=bottom>4.&nbsp;&nbsp;&nbsp;Round each user's time?</td></tr>\n";
}
if ($tmp_round_time == '1') {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='1'
                      checked>&nbsp;To the nearest 5 minutes (1/12th of an hour)</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='1'>&nbsp;To the
                      nearest 5 minutes (1/12th of an hour)</td></tr>\n";
}
if ($tmp_round_time == '2') {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='2'
                      checked>&nbsp;To the nearest 10 minutes (1/6th of an hour)</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='2'>&nbsp;To
                      the nearest 10 minutes (1/6th of an hour)</td></tr>\n";
}
if ($tmp_round_time == '3') {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='3'
                      checked>&nbsp;To the nearest 15 minutes (1/4th of an hour)</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='3'>&nbsp;To
                      the nearest 15 minutes (1/4th of an hour)</td></tr>\n";
}
if ($tmp_round_time == '4') {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='4'
                      checked>&nbsp;To the nearest 20 minutes (1/3rd of an hour)</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='4'>&nbsp;To
                      the nearest 20 minutes (1/3rd of an hour)</td></tr>\n";
}
if ($tmp_round_time == '5') {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='5'
                      checked>&nbsp;To the nearest 30 minutes (1/2 of an hour)</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value='5'>&nbsp;To
                      the nearest 30 minutes (1/2 of an hour)</td></tr>\n";
}
if (empty($tmp_round_time)) {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value=0 checked>&nbsp;Do
                      not round</td></tr>\n";
} else {
echo "              <tr><td class=table_rows align=left nowrap style='padding-left:15px;'><input type='radio' name='tmp_round_time' value=0>&nbsp;Do not
                      round</td></tr>\n";
}
echo "              <tr><td height=10></td></tr>\n";
echo "            </table>\n";
echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Edit Time' align='middle'
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

// end post validation //

if (!empty($from_date)) {
    //$from_date = "$from_month/$from_day/$from_year";

    $from_date = str_replace("/", "-", $from_date);
    $from_timestamp = strtotime($from_date . " " . $report_start_time) - $tzo;
    $from_date = $_POST['from_date'];
}

if (!empty($to_date)) {
    //$to_date = "$to_month/$to_day/$to_year";
    $to_date = str_replace("/", "-", $to_date);
    $to_timestamp = strtotime($to_date . " " . $report_end_time) - $tzo;
    $to_date = $_POST['to_date'];
}

//if (!empty($from_date)) {$from_timestamp = strtotime($from_date . " " . $report_start_time) - $tzo;}
//if (!empty($from_date)) {$to_timestamp = strtotime($to_date . " " . $report_end_time) - $tzo + 60;}

//if (!empty($from_date)) {$from_timestamp = strtotime($from_date) - @$tzo;}
//if (!empty($to_date)) {$to_timestamp = strtotime($to_date) + 86400 - @$tzo;}

// $time = time();
// $rpt_hour = gmdate('H',$time);
// $rpt_min = gmdate('i',$time);
// $rpt_sec = gmdate('s',$time);
// $rpt_month = gmdate('m',$time);
// $rpt_day = gmdate('d',$time);
// $rpt_year = gmdate('Y',$time);
$rpt_stamp = time ();

$rpt_stamp = $rpt_stamp + @$tzo;

$rpt_time = date($timefmt, $rpt_stamp);
$rpt_date = date($datefmt, $rpt_stamp);
$from_date_eur = strftime('%d/%m/%Y',strtotime($from_date));
$to_date_eur = strftime('%d/%m/%Y',strtotime($to_date));
$tmp_fullname = stripslashes($fullname);
if ((strtolower($user_or_display) == "display") && ($tmp_fullname != "All")) {
$tmp_fullname = stripslashes($displayname);
}
if (($office_name == "All") && ($group_name == "All") && ($tmp_fullname == 'All')) {$tmp_fullname = "Oficinas: Todas --> Grupos: Todos --> Usuarios: Todos";}
elseif ((empty($office_name)) && (empty($group_name)) && ($tmp_fullname == 'All'))  {$tmp_fullname = "All Users";}
elseif ((empty($office_name)) && (empty($group_name)) && ($tmp_fullname != 'All'))  {$tmp_fullname = $tmp_fullname;}
elseif (($office_name != "All") && ($group_name == "All") && ($tmp_fullname == 'All')) {$tmp_fullname = "Oficina: $office_name --> Grupos: Todos -->
 Usuarios: Todos";}
elseif (($office_name != "All") && ($group_name != "All") && ($tmp_fullname == 'All')) {$tmp_fullname = "Oficina: $office_name --> Grupo: $group_name -->
 Usuarios: Todos";}
$rpt_name="$tmp_fullname";
/*
  Tabla de datos arribas de los informes
*/
echo '  <div class="row" style="margin-top: 20px;">
          <div id="float_window" class="col-md-10">
            <div class="box box-info">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-list"></i> Datos</h3>
              </div>

              <div class="box-body">
                <table class="table table-hover">
                  <tr>
                    <td>
                      Fecha del informe: '. $rpt_time .', '. $rpt_date .'
                    </td>
                    <td>
                      '. $rpt_name .'
                    </td>
                    <td>
                      Rango de fechas: '. $from_date_eur .' to '. $to_date_eur .'
                    <td>
                  </tr>';
if(!empty($tmp_csv)){
echo '            <tr>
                    <td>
                      Descargar el fichero .CSV:
                      <a style="color:#27408b;font-size:16px;text-decoration:underline;"
                        href=\'get_csv.php?rpt=timerpt&display_ip='. $tmp_display_ip .'&csv='. $tmp_csv .'&office='. $office_name .'&group='. $group_name .'&fullname='. $fullname .'&from='. $from_timestamp .'&to=' .$to_timestamp .'&tzo=' .$tzo .'\'>
                         &nbsp;Descargar
                      </a>
                    </td>
                  </tr>';
}
echo'           </table>
              </div>
            </div>
          </div>
        </div>';
echo '<div class="row">
        <div id="float_window" class="col-md-10">
          <div class="box box-info">
            <div class="box-body">';
echo "<table width='100%' align='center' class='table table-hover'>\n";

$employees_cnt = 0;
$employees_empfullname = array();
$employees_displayname = array();
$info_cnt = 0;
$info_fullname = array();
$info_inout = array();
$info_timestamp = array();
$info_notes = array();
$info_date = array();
$x_info_date = array();
$info_start_time = array();
$info_end_time = array();
$punchlist_in_or_out = array();
$punchlist_punchitems = array();
$secs = 0;
$total_hours = 0;
$row_count = 0;
$page_count = 0;
$punch_cnt = 0;
$tmp_z = 0;

// retrieve a list of users //

$fullname = addslashes($fullname);

if (strtolower($user_or_display) == "display") {

    if (($office_name == "All") && ($group_name == "All") && ($fullname == "All")) {

        $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL and empfullname <> '".$root."'
                  order by displayname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname == 'All')) {

        $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL and empfullname <> '".$root."'
                  order by displayname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname != 'All')) {

        $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL and empfullname = '".$fullname."'
                  and empfullname <> '".$root."' order by displayname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    } elseif (($office_name != "All") && ($group_name == "All") && ($fullname == "All")) {

        $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and tstamp IS NOT NULL
                  and empfullname <> '".$root."' order by displayname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    } elseif (($office_name != "All") && ($group_name != "All") && ($fullname == "All")) {

        $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and groups = '".$group_name."'
                  and tstamp IS NOT NULL and empfullname <> '".$root."' order by displayname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    } elseif (($office_name != "All") && ($group_name != "All") && ($fullname != "All")) {

        $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and groups = '".$group_name."'
                  and empfullname = '".$fullname."' and empfullname <> '".$root."' and tstamp IS NOT NULL order by displayname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    }

} else {

    if (($office_name == "All") && ($group_name == "All") && ($fullname == "All")) {

        $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL and empfullname <> '".$root."'
                  order by empfullname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname == 'All')) {

        $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL and empfullname <> '".$root."'
                  order by empfullname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    } elseif ((empty($office_name)) && (empty($group_name)) && ($fullname != 'All')) {

        $query = "select empfullname, displayname from ".$db_prefix."employees WHERE tstamp IS NOT NULL and empfullname = '".$fullname."'
                  and empfullname <> '".$root."' order by empfullname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    } elseif (($office_name != "All") && ($group_name == "All") && ($fullname == "All")) {

        $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and tstamp IS NOT NULL
                  and empfullname <> '".$root."' order by empfullname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    } elseif (($office_name != "All") && ($group_name != "All") && ($fullname == "All")) {

        $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and groups = '".$group_name."'
                  and tstamp IS NOT NULL and empfullname <> '".$root."' order by empfullname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    } elseif (($office_name != "All") && ($group_name != "All") && ($fullname != "All")) {

        $query = "select empfullname, displayname from ".$db_prefix."employees where office = '".$office_name."' and groups = '".$group_name."'
                  and empfullname = '".$fullname."' and empfullname <> '".$root."' and tstamp IS NOT NULL order by empfullname asc";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    }
}

while ($row=mysqli_fetch_array($result)) {

  $employees_empfullname[] = stripslashes("".$row['empfullname']."");
  $employees_displayname[] = stripslashes("".$row['displayname']."");
  $employees_cnt++;
}

for ($x=0;$x<$employees_cnt;$x++) {

    $employees_empfullname[$x] = addslashes($employees_empfullname[$x]);
    $employees_displayname[$x] = addslashes($employees_displayname[$x]);

    $query = "select ".$db_prefix."info.fullname, ".$db_prefix."info.`inout`, ".$db_prefix."info.timestamp, ".$db_prefix."info.notes,
              ".$db_prefix."info.ipaddress, ".$db_prefix."punchlist.in_or_out, ".$db_prefix."punchlist.punchitems, ".$db_prefix."punchlist.color
              from ".$db_prefix."info, ".$db_prefix."punchlist, ".$db_prefix."employees
              where ".$db_prefix."info.fullname like ('".$employees_empfullname[$x]."') and ".$db_prefix."info.timestamp >= '".$from_timestamp."'
              and ".$db_prefix."info.timestamp < '".$to_timestamp."' and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems
              and ".$db_prefix."employees.empfullname = '".$employees_empfullname[$x]."' and ".$db_prefix."employees.empfullname <> '".$root."'
              order by ".$db_prefix."info.timestamp asc";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    while ($row=mysqli_fetch_array($result)) {

      $info_fullname[] = stripslashes("".$row['fullname']."");
      $info_inout[] = "".$row['inout']."";
      $info_timestamp[] = "".$row['timestamp']."" + $tzo;
      $info_notes[] = "".$row['notes']."";
      $info_ipaddress[] = "".$row['ipaddress']."";
      $punchlist_in_or_out[] = "".$row['in_or_out']."";
      $punchlist_punchitems[] = "".$row['punchitems']."";
      $punchlist_color[] = "".$row['color']."";
      $info_cnt++;
    }

    $employees_empfullname[$x] = stripslashes($employees_empfullname[$x]);
    $employees_displayname[$x] = stripslashes($employees_displayname[$x]);

	// If an employee has not worked in the time frame requested do not display an entry.
	if (($displayEmptyHours == '0') && ($info_cnt <= 0)) {
// 	  print "No time logged for: ".$employees_empfullname[$x]."<br>";
	  continue;
	}

 	$fullname = stripslashes($fullname);
	if (($employees_empfullname[$x] == $fullname) || ($fullname == "All")) {

	  if (strtolower($user_or_display) == "display") {
		echo "  <tr><td width=100% colspan=2 style=\"font-size:18px;color:#000000;border-style:solid;border-color:#888888;
		border-width:0px 0px 1px 0px;\">Registros de: <b>$employees_displayname[$x]</b></td></tr>\n";
	  } else {
		echo "  <tr><td width=100% colspan=2 style=\"font-size:18px;color:#000000;border-style:solid;border-color:#888888;
		border-width:0px 0px 1px 0px;\">Registros de: <b>$employees_empfullname[$x]</b></td></tr>\n";
	  }
	  echo "  <tr><td width=75% nowrap align=left style='color:#27408b;'><b>Fecha</b></td>\n";
	  echo "      <td width=25% nowrap align=left style='color:#27408b;'><b>Horas Trabajadas</b></td></tr>\n";
	  $row_color = $color2; // Initial row color

	  // Inform the user that this employee has not worked during this pay period.
	  if (($displayEmptyHours == '1') && ($info_cnt <= 0)) {
		echo "  <tr bgcolor=\"$color2\" align=\"left\"><td style=\"color:#000000;border-style:solid;border-color:#888888; border-width:1px 0px 0px 0px;\" nowrap>El empleado no ha trabajado</td>\n";
	  }

	  for ($y=0;$y<$info_cnt;$y++) {

//      $info_date[] = date($datefmt, $info_timestamp[$y]);
      $x_info_date[] = date($datefmt, $info_timestamp[$y]);
      $info_date[] = date('n/j/y', $info_timestamp[$y]);
      $info_start_time[] = strtotime($info_date[$y]);
      $info_end_time[] = $info_start_time[$y] + 86399;

      if (isset($tmp_info_date)) {
          if ($tmp_info_date == $info_date[$y]) {
              if (empty($punchlist_in_or_out[$y])) {
                  $punch_cnt++;
                    if ($status == "out") {
                        $secs = $secs + ($info_timestamp[$y] - $out_time);
                    } elseif ($status == "in") {
                        $secs = $secs + ($info_timestamp[$y] - $in_time);
                    }
                  $status = "out";
                  $out_time = $info_timestamp[$y];
                  if ($y == $info_cnt - 1) {
                      $hours = secsToHours($secs, $tmp_round_time);
                      $total_hours = $total_hours + $hours;
                      $row_color = $color2; // Initial row color
                      if (empty($y)) {
                          $yy = 0;
                          $date_formatted = date('l, ', $info_timestamp[$y]);
                      } else {
                          $yy = $y - 1;
                          $date_formatted = date('l, ', $info_timestamp[$y]);
                      }
                      echo "  <tr bgcolor=\"$row_color\" align=\"left\"><td style=\"color:#000000;border-style:solid;border-color:#888888;
                                border-width:1px 0px 0px 0px;\" nowrap>$date_formatted$x_info_date[$y]</td>\n";
                      if ($hours < 10) {
                          echo "      <td nowrap style='color:#000000;padding-left:31px;border-style:solid;border-color:#888888;
                                    border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                      } else {
                          echo "      <td nowrap style='color:#000000;padding-left:25px;border-style:solid;border-color:#888888;
                                    border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                      }
                      $row_color = ($row_color == $color1) ? $color2 : $color1;
                      $row_count++;
                      if ($tmp_show_details == "1") {
                          echo "  <tr><td width=100% colspan=2>\n";
                          echo "<table width=100% align=center class='table table-hover'>\n";
                          echo "  <th>Estado</th>
                                  <th>Horas</th>
                                  <th>Dirección IP</th>
                                  <th>Notas</th>\n";

                          for ($z=$tmp_z;$z<=$punch_cnt;$z++) {
                              $time_formatted = date($timefmt, $info_timestamp[$z]);
                              echo "  <tr bgcolor=\"$row_color\">\n";
                              echo "      <td align=left width=13% nowrap style=\"color:$punchlist_color[$z];\">$info_inout[$z]</td>\n";
                              echo "      <td nowrap align='center' width=10% style='padding-right:70px;'>$time_formatted</td>\n";
                              if (@$tmp_display_ip == "1") {
                                  echo "      <td nowrap width=15% align='center' style='padding-right:25px;
                                            color:$punchlist_color[$z];'>$info_ipaddress[$z]</td>\n";
                              }
                              echo "      <td width=77%>$info_notes[$z]</td></tr>\n";
                              $row_color = ($row_color == $color1) ? $color2 : $color1;
                              $row_count++;
                              $tmp_z++;
                          }
                          echo "</table></td></tr>\n";
                          echo '</div></div></div>'; //row - float_window - box-info - box-body
                          if ($row_count >= "40") {
                              $row_count = "0";
                              $page_count++;
                              $temp_page_count = $page_count + 1;
                              if (!empty($tmp_paginate)) {
                                  echo "<tr style='page-break-before:always;'>
                                          <td width=100% colspan=2>\n";
                                  echo '<div class="row">
                                          <div id="float_window" class="col-md-10">
                                            <div class="box box-info">
                                              <div class="box-body">';
                                  echo "<table width=100% align=center class='table table-hover'>\n";

                                  echo "  <tr><td class=notdisplay_rpt width=80% style='font-size:9px;color:#000000;'>Fecha del Informe FLAG 1: $rpt_time,
                                            $rpt_date (page $temp_page_count)</td>
                                            <td class=notdisplay_rpt nowrap style='font-size:9px;color:#000000;'>$rpt_name</td></tr>\n";
                                  echo "  <tr><td width=80%></td><td class=notdisplay_rpt nowrap
                                            style='font-size:9px;color:#000000;'>Rango de fechas: $from_date_eur - $to_date_eur</td></tr>\n";
                                  echo "</table></td></tr>\n";
                                  if (strtolower($user_or_display) == "display") {
                                      echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                                style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                                border-width:0px 0px 1px 0px;\"><b>$employees_displayname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                                  } else {
                                      echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                                style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                                border-width:0px 0px 1px 0px;\"><b>$employees_empfullname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                                  }
                                  echo "  <tr><td width=75% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Date</u></b></td>\n";
                                  echo "      <td width=25% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Hours
                                                  Worked</u></b></td></tr>\n";
                              }
                          }
                      }
                      $secs = 0;
                      $punch_cnt = 0;
                  }
              } else {
                  $punch_cnt++;
                  if ($y == $info_cnt - 1) {
                      if (($info_timestamp[$y] <= $rpt_stamp) && ($rpt_stamp < ($to_timestamp + $tzo)) && ($x_info_date[$y] == $rpt_date)) {
                          if ($status == "in") {
                              $secs = $secs + ($rpt_stamp - $info_timestamp[$y]) + ($info_timestamp[$y] - $in_time);
                          } elseif ($status == "out") {
                              $secs = $secs + ($rpt_stamp - $info_timestamp[$y]);
                          }
                          $currently_punched_in = '1';
                      } elseif (($info_timestamp[$y] <= $rpt_stamp) && ($x_info_date[$y] == $rpt_date)) {
                          if ($status == "in") {
                              $secs = $secs + (($to_timestamp + $tzo) - $info_timestamp[$y]) + ($info_timestamp[$y] - $in_time);
                          } elseif ($status == "out") {
                              $secs = $secs + (($to_timestamp + $tzo) - $info_timestamp[$y]);
                          }
                          $currently_punched_in = '1';
                      } else {
                          $secs = $secs + (($info_end_time[$y] + 1) - $info_timestamp[$y]);
                      }
                  } else {
                      if ($status == "in") {
                          $secs = $secs + ($info_timestamp[$y] - $in_time);
                      }
                      $in_time = $info_timestamp[$y];
                      $previous_days_end_time = $info_end_time[$y] + 1;
                  }
                  $status = "in";
                  if ($y == $info_cnt - 1) {
                      $hours = secsToHours($secs, $tmp_round_time);
                      $total_hours = $total_hours + $hours;
                      $row_color = $color2; // Initial row color
                      if ((empty($y)) || ($y == $info_cnt -1)) {
                          $yy = 0;
                          $date_formatted = date('l, ', $info_timestamp[$y]);
                      } else {
                          $yy = $y - 1;
                          $date_formatted = date('l, ', $info_timestamp[$y-1]);
                      }
                      echo "  <tr bgcolor=\"$row_color\" align=\"left\"><td style=\"color:#000000;border-style:solid;border-color:#888888;
                                border-width:1px 0px 0px 0px;\" nowrap>$date_formatted$x_info_date[$y]</td>\n";
                      if ($hours < 10) {
                          echo "      <td nowrap style='color:#000000;padding-left:31px;border-style:solid;border-color:#888888;
                                    border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                      } else {
                          echo "      <td nowrap style='color:#000000;padding-left:25px;border-style:solid;border-color:#888888;
                                    border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                      }
                      $row_color = ($row_color == $color1) ? $color2 : $color1;
                      $row_count++;
                      if ($tmp_show_details == "1") {
                          echo "  <tr><td width=100% colspan=2>\n";
                          echo "<table width=100% align='center' class='table table-hover'>\n";
                          echo "<th>Estado</th>
                                <th>Horas</th>
                                <th>Dirección IP</th>
                                <th>Notas</th>\n";
                          for ($z=$tmp_z;$z<=$punch_cnt;$z++) {
                              $time_formatted = date($timefmt, $info_timestamp[$z]);
                              echo "  <tr bgcolor=\"$row_color\" align=\"left\">\n";
                              echo "      <td align=left width=13% nowrap style=\"color:$punchlist_color[$z];\">$info_inout[$z]</td>\n";
                              echo "      <td nowrap align=right width=10% style='padding-right:25px;'>$time_formatted</td>\n";
                              if (@$tmp_display_ip == "1") {
                                  echo "      <td nowrap align=left width=15% style='padding-right:25px;
                                            color:$punchlist_color[$z];'>$info_ipaddress[$z]</td>\n";
                              }
                              echo "      <td width=77%>$info_notes[$z]</td></tr>\n";
                              $row_color = ($row_color == $color1) ? $color2 : $color1;
                              $row_count++;
                              $tmp_z++;
                          }
                          echo "</table></td></tr>\n";
                          echo '</div></div></div></div>'; //row - float_window - box-info - box-body
                          if ($row_count >= "40") {
                              $row_count = "0";
                              $page_count++;
                              $temp_page_count = $page_count + 1;
                              if (!empty($tmp_paginate)) {
                                echo '  <div class="row" style="margin-top: 20px;">
                                          <div id="float_window" class="col-md-10">
                                            <div class="box box-info">
                                              <div class="box-header">
                                                <h3 class="box-title"><i class="fa fa-list"></i> Datos</h3>
                                              </div>

                                              <div class="box-body">
                                                <table class="table table-hover">
                                                  <tr>
                                                    <td>
                                                      Fecha del informe: '. $rpt_time .', '. $rpt_date .' (página '. $temp_page_count .')
                                                    </td>
                                                    <td>
                                                      '. $rpt_name .'
                                                    </td>
                                                    <td>
                                                      Rango de fechas: '. $from_date_eur .' to '. $to_date_eur .'
                                                    <td>
                                                  </tr>';
                                echo'           </table>
                                              </div>
                                            </div>
                                          </div>
                                        </div>';
                                  echo "<tr style='page-break-before:always;'><td width=100% colspan=2>\n";
                                  echo '  <div class="row" style="margin-top: 20px;">
                                            <div id="float_window" class="col-md-10">
                                              <div class="box box-info">
                                                <div class="box-header">
                                                  <h3 class="box-title"><i class="fa fa-list"></i> Datos</h3>
                                                </div>

                                                <div class="box-body">
                                                  <table class="table table-hover">
                                                    <tr>
                                                      <td>
                                                        Fecha del informe: '. $rpt_time .', '. $rpt_date .' (página '. $temp_page_count .')
                                                      </td>
                                                      <td>
                                                        '. $rpt_name .'
                                                      </td>
                                                      <td>
                                                        Rango de fechas: '. $from_date_eur .' to '. $to_date_eur .'
                                                      <td>
                                                    </tr>';
                                  echo'           </table>
                                                </div>
                                              </div>
                                            </div>
                                          </div>';
                                  if (strtolower($user_or_display) == "display") {
                                      echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                                style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                                border-width:0px 0px 1px 0px;\"><b>$employees_displayname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                                  } else {
                                      echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                                style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                                border-width:0px 0px 1px 0px;\"><b>$employees_empfullname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                                  }
                                  echo "  <tr><td width=75% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Date</u></b></td>\n";
                                  echo "      <td width=25% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Hours
                                                  Worked</u></b></td></tr>\n";
                              }
                          }
                      }
                  $secs = 0;
                  $punch_cnt = 0;
                  }
              }
          } else {

              //// print totals for previous day ////

              //// if the previous has only a single In punch and no Out punches, configure the $secs ////

              if (isset($tmp_info_date)) {
                  if ($status == "out") {
                      $secs = $secs;
                  } elseif ($status == "in") {
                      $secs = $secs + ($previous_days_end_time - $in_time);
                  }
                  $hours = secsToHours($secs, $tmp_round_time);
                  $total_hours = $total_hours + $hours;
                  $row_color = $color2; // Initial row color
                  if (empty($y)) {
                      $yy = 0;
                      $date_formatted = date('l, ', $info_timestamp[$y]);
                  } else {
                      $yy = $y - 1;
                      $date_formatted = date('l, ', $info_timestamp[$y-1]);
                  }
                  echo "  <tr bgcolor=\"$row_color\" align=\"left\"><td style=\"color:#000000;border-style:solid;border-color:#888888;
                            border-width:1px 0px 0px 0px;\" nowrap>$date_formatted$x_info_date[$yy]</td>\n";
                  if ($hours < 10) {
                      echo "      <td nowrap style='color:#000000;padding-left:31px;border-style:solid;border-color:#888888;
                                border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                  } else {
                      echo "      <td nowrap style='color:#000000;padding-left:25px;border-style:solid;border-color:#888888;
                                border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                  }
                  $row_color = ($row_color == $color1) ? $color2 : $color1;
                  $row_count++;
                  if ($tmp_show_details == "1") {
                  echo "  <tr><td width=100% colspan=2>\n";
                  echo "<table width=100% align='center' class='table table-hover'>\n";
                  echo "<th>Estado</th>
                        <th>Horas</th>
                        <th>Dirección IP</th>
                        <th>Notas</th>\n";
                  for ($z=$tmp_z;$z<=$punch_cnt;$z++) {
                      $time_formatted = date($timefmt, $info_timestamp[$z]);
                      echo "  <tr bgcolor=\"$row_color\" align=\"left\">\n";
                      echo "      <td align=left width=13% nowrap style=\"color:$punchlist_color[$z];\">$info_inout[$z]</td>\n";
                      echo "      <td nowrap align=right width=10% style='padding-right:70px;'>$time_formatted</td>\n";
                      if (@$tmp_display_ip == "1") {
                          echo "      <td nowrap align=center width=15% style='padding-right:10px;
                                    color:$punchlist_color[$z];'>$info_ipaddress[$z]</td>\n";
                      }
                      echo "      <td width=77%>$info_notes[$z]</td></tr>\n";
                      $row_color = ($row_color == $color1) ? $color2 : $color1;
                      $row_count++;
                      $tmp_z++;
                  }
                  echo "</table></td></tr>\n";
                  if ($row_count >= "40") {
                      $row_count = "0";
                      $page_count++;
                      $temp_page_count = $page_count + 1;
                      if (!empty($tmp_paginate)) {
                        echo '  <div class="row" style="margin-top: 20px;">
                                  <div id="float_window" class="col-md-10">
                                    <div class="box box-info">
                                      <div class="box-header">
                                        <h3 class="box-title"><i class="fa fa-list"></i> Datos</h3>
                                      </div>

                                      <div class="box-body">
                                        <table class="table table-hover">
                                          <tr>
                                            <td>
                                              Fecha del informe: '. $rpt_time .', '. $rpt_date .' (página '. $temp_page_count .')
                                            </td>
                                            <td>
                                              '. $rpt_name .'
                                            </td>
                                            <td>
                                              Rango de fechas: '. $from_date_eur .' to '. $to_date_eur .'
                                            <td>
                                          </tr>';
                        echo'           </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>';
                          if (strtolower($user_or_display) == "display") {
                              echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                        style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                        border-width:0px 0px 1px 0px;\"><b>$employees_displayname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                          } else {
                              echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                        style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                        border-width:0px 0px 1px 0px;\"><b>$employees_empfullname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                          }
                          echo "  <tr><td width=75% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Date</u></b></td>\n";
                          echo "      <td width=25% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Hours
                                          Worked</u></b></td></tr>\n";
                      }
                  }

              }
              $secs = 0;
              unset($in_time);
              unset($out_time);
              unset($previous_days_end_time);
              unset($status);
              unset($tmp_info_date);
              unset($date_formatted);
              }
              $tmp_info_date = $info_date[$y];
              $previous_days_end_time = $info_end_time[$y] + 1;
              $punch_cnt++;
              if (empty($punchlist_in_or_out[$y])) {
                  $status = "out";
                  $secs = $info_timestamp[$y] - $info_start_time[$y];
                  $out_time = $info_timestamp[$y];
                  $previous_days_end_time = $info_end_time[$y] + 1;
                  if ($y == $info_cnt - 1) {
                      $hours = secsToHours($secs, $tmp_round_time);
                      $total_hours = $total_hours + $hours;
                      $row_color = $color2; // Initial row color
                      if (empty($y)) {
                          $yy = 0;
                          $date_formatted = date('l, ', $info_timestamp[$y]);
                      } else {
                          $yy = $y - 1;
                          $date_formatted = date('l, ', $info_timestamp[$y]);
                      }
                      echo "  <tr bgcolor=\"$row_color\" align=\"left\"><td style=\"color:#000000;border-style:solid;border-color:#888888;
                                border-width:1px 0px 0px 0px;\" nowrap>$date_formatted$x_info_date[$y]</td>\n";
                      if ($hours < 10) {
                          echo "      <td nowrap style='color:#000000;padding-left:31px;border-style:solid;border-color:#888888;
                                    border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                      } else {
                          echo "      <td nowrap style='color:#000000;padding-left:25px;border-style:solid;border-color:#888888;
                                    border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                      }
                      $row_color = ($row_color == $color1) ? $color2 : $color1;
                      $row_count++;
                      if ($tmp_show_details == "1") {
                          echo "  <tr><td width=100% colspan=2>\n";
                          echo "<table width=100% align='center' class='table table-hover'>\n";
                          for ($z=$tmp_z;$z<=$punch_cnt;$z++) {
                              $time_formatted = date($timefmt, $info_timestamp[$z]);
                              echo "  <tr bgcolor=\"$row_color\" align=\"left\">\n";
                              echo "      <td align=left width=13% nowrap style=\"color:$punchlist_color[$z];\">$info_inout[$z]</td>\n";
                              echo "      <td nowrap align=right width=10% style='padding-right:25px;'>$time_formatted</td>\n";
                              if (@$tmp_display_ip == "1") {
                                  echo "      <td nowrap align=left width=15% style='padding-right:25px;
                                            color:$punchlist_color[$z];'>$info_ipaddress[$z]</td>\n";
                              }
                              echo "      <td width=77%>$info_notes[$z]</td></tr>\n";
                              $row_color = ($row_color == $color1) ? $color2 : $color1;
                              $row_count++;
                              $tmp_z++;
                          }
                          echo "</table></td></tr>\n";
                          if ($row_count >= "40") {
                              $row_count = "0";
                              $page_count++;
                              $temp_page_count = $page_count + 1;
                              if (!empty($tmp_paginate)) {
                                  echo "<tr style='page-break-before:always;'><td width=100% colspan=2>\n";
                                  echo "<table width=100% aligclass='table table-hover'>\n";
                                  echo "  <tr><td class=notdisplay_rpt width=80% style='font-size:9px;color:#000000;'>Fecha del Informe FLAG 2: $rpt_time,
                                            $rpt_date (page $temp_page_count)</td>
                                            <td class=notdisplay_rpt nowrap style='font-size:9px;color:#000000;'>$rpt_name</td></tr>\n";
                                  echo "  <tr><td width=80%></td><td class=notdisplay_rpt nowrap
                                            style='font-size:9px;color:#000000;'>Rango de fechas: $from_date_eur - $to_date_eur</td></tr>\n";
                                  echo "</table></td></tr>\n";
                                  if (strtolower($user_or_display) == "display") {
                                      echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                                style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                                border-width:0px 0px 1px 0px;\"><b>$employees_displayname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                                  } else {
                                      echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                                style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                                border-width:0px 0px 1px 0px;\"><b>$employees_empfullname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                                  }
                                  echo "  <tr><td width=75% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Date</u></b></td>\n";
                                  echo "      <td width=25% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Hours
                                                  Worked</u></b></td></tr>\n";
                              }
                          }
                      }
                      $secs = 0;
                      $punch_cnt = 0;
                  }
              } else {
                  if ($y == $info_cnt - 1) {
                      if (($info_timestamp[$y] <= $rpt_stamp) && ($rpt_stamp < ($to_timestamp + $tzo)) && ($x_info_date[$y] == $rpt_date)) {
                          $secs = $secs + ($rpt_stamp - $info_timestamp[$y]);
                          $currently_punched_in = '1';
                      } elseif (($info_timestamp[$y] <= $rpt_stamp) && ($x_info_date[$y] == $rpt_date)) {
                          $secs = $secs + (($to_timestamp + $tzo) - $info_timestamp[$y]);
                          $currently_punched_in = '1';
                      } else {
                          $secs = $secs + (($info_end_time[$y] + 1) - $info_timestamp[$y]);
                      }
//                      if (($info_timestamp[$y] <= $rpt_stamp) && ($x_info_date[$y] == $rpt_date)) {
//                          $secs = $secs + ($rpt_stamp - $info_timestamp[$y]);
//                          $currently_punched_in = '1';
//                      } else {
//                          $secs = $secs + (($info_end_time[$y] + 1) - $info_timestamp[$y]);
//                      }
                  } else {
                      $status = "in";
                      $in_time = $info_timestamp[$y];
                      $previous_days_end_time = $info_end_time[$y] + 1;
                  }
                  if ($y == $info_cnt - 1) {
                      $hours = secsToHours($secs, $tmp_round_time);
                      $total_hours = $total_hours + $hours;
                      $row_color = $color2; // Initial row color
                      if (empty($y)) {
                          $yy = 0;
                          $date_formatted = date('l, ', $info_timestamp[$y]);
                      } else {
                          $yy = $y - 1;
                          $date_formatted = date('l, ', $info_timestamp[$y]);
                      }
                      echo "  <tr bgcolor=\"$row_color\" align=\"left\"><td style=\"color:#000000;border-style:solid;border-color:#888888;
                                border-width:1px 0px 0px 0px;\" nowrap>$date_formatted$x_info_date[$y]</td>\n";
                      if ($hours < 10) {
                          echo "      <td nowrap style='color:#000000;padding-left:31px;border-style:solid;border-color:#888888;
                                    border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                      } else {
                          echo "      <td nowrap style='color:#000000;padding-left:25px;border-style:solid;border-color:#888888;
                                    border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                      }
                      $row_color = ($row_color == $color1) ? $color2 : $color1;
                      $row_count++;
                      if ($tmp_show_details == "1") {
                          echo "  <tr><td width=100% colspan=2>\n";
                          echo "<table width=100% align='center' class='table table-hover'>\n";
                          for ($z=$tmp_z;$z<=$punch_cnt;$z++) {
                              $time_formatted = date($timefmt, $info_timestamp[$z]);
                              echo "  <tr bgcolor=\"$row_color\" align=\"left\">\n";
                              echo "      <td align=left width=13% nowrap style=\"color:$punchlist_color[$z];\">$info_inout[$z]</td>\n";
                              echo "      <td nowrap align=right width=10% style='padding-right:25px;'>$time_formatted</td>\n";
                              if (@$tmp_display_ip == "1") {
                                  echo "      <td nowrap align=left width=15% style='padding-right:25px;
                                            color:$punchlist_color[$z];'>$info_ipaddress[$z]</td>\n";
                              }
                              echo "      <td width=77%>$info_notes[$z]</td></tr>\n";
                              $row_color = ($row_color == $color1) ? $color2 : $color1;
                              $row_count++;
                              $tmp_z++;
                          }
                          echo "</table></td></tr>\n";
                          if ($row_count >= "40") {
                              $row_count = "0";
                              $page_count++;
                              $temp_page_count = $page_count + 1;
                              if (!empty($tmp_paginate)) {
                                  echo "<tr style='page-break-before:always;'><td width=100% colspan=2>\n";
                                  echo "<table width=100% align=center class=misc_items border=0
                                          cellpadding=3 cellspacing=0>\n";
                                  echo "  <tr><td class=notdisplay_rpt width=80% style='font-size:9px;color:#000000;'>Fecha del Informe: $rpt_time,
                                            $rpt_date (page $temp_page_count)</td>
                                            <td class=notdisplay_rpt nowrap style='font-size:9px;color:#000000;'>$rpt_name</td></tr>\n";
                                  echo "  <tr><td width=80%></td><td class=notdisplay_rpt nowrap
                                            style='font-size:9px;color:#000000;'>Rango de fechas: $from_date_eur - $to_date_eur</td></tr>\n";
                                  echo "</table></td></tr>\n";
                                  if (strtolower($user_or_display) == "display") {
                                      echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                                style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                                border-width:0px 0px 1px 0px;\"><b>$employees_displayname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                                  } else {
                                      echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                                style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                                border-width:0px 0px 1px 0px;\"><b>$employees_empfullname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                                  }
                                  echo "  <tr><td width=75% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Date</u></b></td>\n";
                                  echo "      <td width=25% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Hours
                                                  Worked</u></b></td></tr>\n";
                              }
                          }
                      }
                      $secs = 0;
                      $punch_cnt = 0;
                  }
              }
          }
      } else {

          ///// this is for the start of the first entry for the first day /////

          $tmp_info_date = $info_date[$y];
          $previous_days_end_time = $info_end_time[$y] + 1;
          if (empty($punchlist_in_or_out[$y])) {
              $out = 1;
              $status = "out";
              if ($info_date[$y] == $from_date) {
                  $secs = $info_timestamp[$y] - $from_timestamp - $tzo;
              } else {
                  $secs = $info_timestamp[$y] - $info_start_time[$y];
              }
              $out_time = $info_timestamp[$y];
              $previous_days_end_time = $info_end_time[$y] + 1;
              if ($y == $info_cnt - 1) {
                  $hours = secsToHours($secs, $tmp_round_time);
                  $total_hours = $total_hours + $hours;
                  $row_color = $color2; // Initial row color
                  if (empty($y)) {
                      $yy = 0;
                      $date_formatted = date('l, ', $info_timestamp[$y]);
                  } else {
                      $yy = $y - 1;
                      $date_formatted = date('l, ', $info_timestamp[$y]);
                  }
                  echo "  <tr bgcolor=\"$row_color\" align=\"left\"><td style=\"color:#000000;border-style:solid;border-color:#888888;
                            border-width:1px 0px 0px 0px;\" nowrap>$date_formatted$x_info_date[$y]</td>\n";
                  if ($hours < 10) {
                      echo "      <td nowrap style='color:#000000;padding-left:31px;border-style:solid;border-color:#888888;
                                border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                  } else {
                      echo "      <td nowrap style='color:#000000;padding-left:25px;border-style:solid;border-color:#888888;
                                border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                  }
                  $row_color = ($row_color == $color1) ? $color2 : $color1;
                  $row_count++;
                  if ($tmp_show_details == "1") {
                      echo "  <tr><td width=100% colspan=2>\n";
                      echo "<table width=100% align='center' class='table table-hover'>\n";
                      for ($z=$tmp_z;$z<=$punch_cnt;$z++) {
                          $time_formatted = date($timefmt, $info_timestamp[$z]);
                          echo "  <tr bgcolor=\"$row_color\" align=\"left\">\n";
                          echo "      <td align=left width=13% nowrap style=\"color:$punchlist_color[$z];\">$info_inout[$z]</td>\n";
                          echo "      <td nowrap align=right width=10% style='padding-right:25px;'>$time_formatted</td>\n";
                          if (@$tmp_display_ip == "1") {
                              echo "      <td nowrap align=left width=15% style='padding-right:25px;
                                        color:$punchlist_color[$z];'>$info_ipaddress[$z]</td>\n";
                          }
                          echo "      <td width=77%>$info_notes[$z]</td></tr>\n";
                          $row_color = ($row_color == $color1) ? $color2 : $color1;
                          $row_count++;
                          $tmp_z++;
                      }
                      echo "</table></td></tr>\n";
                      if ($row_count >= "40") {
                          $row_count = "0";
                          $page_count++;
                          $temp_page_count = $page_count + 1;
                          if (!empty($tmp_paginate)) {
                              echo "<tr style='page-break-before:always;'><td width=100% colspan=2>\n";
                              echo "<table width=100% align=center class=misc_items border=0
                                      cellpadding=3 cellspacing=0>\n";
                              echo "  <tr><td class=notdisplay_rpt width=80% style='font-size:9px;color:#000000;'>Fecha del Informe: $rpt_time,
                                        $rpt_date (page $temp_page_count)</td>
                                        <td class=notdisplay_rpt nowrap style='font-size:9px;color:#000000;'>$rpt_name</td></tr>\n";
                              echo "  <tr><td width=80%></td><td class=notdisplay_rpt nowrap
                                        style='font-size:9px;color:#000000;'>Rango de fechas: $from_date_eur - $to_date_eur</td></tr>\n";
                              echo "</table></td></tr>\n";
                              if (strtolower($user_or_display) == "display") {
                                  echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                            style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                            border-width:0px 0px 1px 0px;\"><b>$employees_displayname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                              } else {
                                  echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                            style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                            border-width:0px 0px 1px 0px;\"><b>$employees_empfullname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                              }
                              echo "  <tr><td width=75% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Date</u></b></td>\n";
                              echo "      <td width=25% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Hours
                                              Worked</u></b></td></tr>\n";
                          }
                      }
                  }
                  $secs = 0;
                  $punch_cnt = 0;
              }
          } else {
              $secs = 0;
              $status = "in";
              $in_time = $info_timestamp[$y];
              $previous_days_end_time = $info_end_time[$y] + 1;
              if ($y == $info_cnt - 1) {
                  if (($info_timestamp[$y] <= $rpt_stamp) && ($rpt_stamp < ($to_timestamp + $tzo)) && ($x_info_date[$y] == $rpt_date)) {
                      $secs = $secs + ($rpt_stamp - $info_timestamp[$y]);
                      $currently_punched_in = '1';
                  } elseif (($info_timestamp[$y] <= $rpt_stamp) && ($x_info_date[$y] == $rpt_date)) {
                      $secs = $secs + (($to_timestamp + $tzo) - $info_timestamp[$y]);
                      $currently_punched_in = '1';
                  } else {
                      $secs = $secs + (($info_end_time[$y] + 1) - $info_timestamp[$y]);
                  }
              }
              if ($y == $info_cnt - 1) {
                  $hours = secsToHours($secs, $tmp_round_time);
                  $total_hours = $total_hours + $hours;
                  $row_color = $color2; // Initial row color
                  if (empty($y)) {
                      $yy = 0;
                      $date_formatted = date('l, ', $info_timestamp[$y]);
                  } else {
                      $yy = $y - 1;
                      $date_formatted = date('l, ', $info_timestamp[$y]);
                  }
                  echo "  <tr bgcolor=\"$row_color\" align=\"left\"><td style=\"color:#000000;border-style:solid;border-color:#888888;
                            border-width:1px 0px 0px 0px;\" nowrap>$date_formatted$x_info_date[$y]</td>\n";
                  if ($hours < 10) {
                      echo "      <td nowrap style='color:#000000;padding-left:31px;border-style:solid;border-color:#888888;
                                border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                  } else {
                      echo "      <td nowrap style='color:#000000;padding-left:25px;border-style:solid;border-color:#888888;
                                border-width:1px 0px 0px 0px;'>$hours</td></tr>\n";
                  }
                  $row_color = ($row_color == $color1) ? $color2 : $color1;
                  $row_count++;
                  if ($tmp_show_details == "1") {
                      echo "  <tr><td width=100% colspan=2>\n";
                      echo "<table width=100% align='center' class='table table-hover'>\n";
                      for ($z=$tmp_z;$z<=$punch_cnt;$z++) {
                          $time_formatted = date($timefmt, $info_timestamp[$z]);
                          echo "  <tr bgcolor=\"$row_color\" align=\"left\">\n";
                          echo "      <td align=left width=13% nowrap style=\"color:$punchlist_color[$z];\">$info_inout[$z]</td>\n";
                          echo "      <td nowrap align=right width=10% style='padding-right:25px;'>$time_formatted</td>\n";
                          if (@$tmp_display_ip == "1") {
                              echo "      <td nowrap align=left width=15% style='padding-right:25px;
                                        color:$punchlist_color[$z];'>$info_ipaddress[$z]</td>\n";
                          }
                          echo "      <td width=77%>$info_notes[$z]</td></tr>\n";
                          $row_color = ($row_color == $color1) ? $color2 : $color1;
                          $row_count++;
                          $tmp_z++;
                      }
                      echo "</table></td></tr>\n";
                      if ($row_count >= "40") {
                          $row_count = "0";
                          $page_count++;
                          $temp_page_count = $page_count + 1;
                          if (!empty($tmp_paginate)) {
                              echo "<tr style='page-break-before:always;'><td width=100% colspan=2>\n";
                              echo "<table width=100% align=center class=misc_items border=0
                                      cellpadding=3 cellspacing=0>\n";
                              echo "  <tr><td class=notdisplay_rpt width=80% style='font-size:9px;color:#000000;'>Fecha del Informe: $rpt_time,
                                        $rpt_date (page $temp_page_count)</td>
                                        <td class=notdisplay_rpt nowrap style='font-size:9px;color:#000000;'>$rpt_name</td></tr>\n";
                              echo "  <tr><td width=80%></td><td class=notdisplay_rpt nowrap
                                        style='font-size:9px;color:#000000;'>Rango de fechas: $from_date_eur - $to_date_eur</td></tr>\n";
                              echo "</table></td></tr>\n";
                              if (strtolower($user_or_display) == "display") {
                                  echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                            style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                            border-width:0px 0px 1px 0px;\"><b>$employees_displayname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                              } else {
                                  echo "  <tr><td class=notdisplay_rpt width=100% colspan=2
                                            style=\"font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                                            border-width:0px 0px 1px 0px;\"><b>$employees_empfullname[$x]</b>&nbsp;(cont'd)</td></tr>\n";
                              }
                              echo "  <tr><td width=75% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Date</u></b></td>\n";
                              echo "      <td width=25% class=notdisplay_rpt nowrap align=left style='color:#27408b;'><b><u>Hours
                                              Worked</u></b></td></tr>\n";
                          }
                      }
                  }
                  $secs = 0;
                  $punch_cnt = 0;
              }
          }
      } // ends if (isset($tmp_info_date))
    } // ends for $y

    unset($in_time);
    unset($out_time);
    unset($previous_days_end_time);
    unset($status);
    unset($tmp_info_date);
    unset($date_formatted);
    unset($x_info_date);
    $my_total_hours = number_format($total_hours, 2);
    if (isset($currently_punched_in)) {
        echo "  </table>\n";
        echo "    <table width=80% align=center class=misc_items border=0 cellpadding=0 cellspacing=0>\n";
        echo "              <tr align=\"left\"><td width=12% nowrap style='font-size:14px;color:#000000;border-style:solid;border-color:#888888;
                              border-width:1px 0px 0px 0px;padding-left:3px;'><b>Horas totales</b></td>
                              <td width=63% align=left style='padding-left:10px;color:#FF0000;border-style:solid;border-color:#888888;
                              border-width:1px 0px 0px 0px;'><b>$employees_empfullname[$x] is currently punched in.</b></td>\n";
        if ($my_total_hours < 10) {
            echo "                <td nowrap style='font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                              border-width:1px 0px 0px 0px;padding-left:30px;'><b>$my_total_hours</b></td></tr>\n";
        } elseif ($my_total_hours < 100) {
            echo "                <td nowrap style='font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                              border-width:1px 0px 0px 0px;padding-left:23px;'><b>$my_total_hours</b></td></tr>\n";
        } else {
            echo "                <td nowrap style='font-size:11px;color:#000000;border-style:solid;border-color:#888888;
                              border-width:1px 0px 0px 0px;padding-left:15px;'><b>$my_total_hours</b></td></tr>\n";
        }
        echo "              <tr><td height=40 colspan=3 style='border-style:solid;border-color:#888888;border-width:1px 0px 0px 0px;'>&nbsp;</td></tr>\n";
        echo " </table></td></tr><table width=80% align=center class=misc_items border=0 cellpadding=0 cellspacing=0>\n";
    } else {
        echo "              <tr align=\"left\"><td nowrap style='font-size:14px;color:#000000;border-style:solid;border-color:#888888;
                              border-width:1px 0px 0px 0px;'><b>Horas totales</b></td>\n";

    if ($my_total_hours < 10) {
        echo "                <td nowrap style='font-size:14px;color:#000000;border-style:solid;border-color:#888888;
                          border-width:1px 0px 0px 0px;padding-left:30px;'><b>$my_total_hours</b></td></tr>
                        </table>\n";
                echo '</div></div></div></div>'; //row - float_window - box-info - box-body
    } elseif ($my_total_hours < 100) {
        echo "                <td nowrap style='font-size:14px;color:#000000;border-style:solid;border-color:#888888;
                          border-width:1px 0px 0px 0px;padding-left:23px;'><b>$my_total_hours</b></td></tr>
                        </table>\n";
        echo '</div></div></div></div>'; //row - float_window - box-info - box-body
    } else {
        echo "                <td nowrap style='font-size:14px;color:#000000;border-style:solid;border-color:#888888;
                          border-width:1px 0px 0px 0px;padding-left:15px;'><b>$my_total_hours</b></td></tr>
                        </table>\n";
        echo '</div></div></div></div>'; //row - float_window - box-info - box-body
    }
  //  echo "              <tr><td height=40 colspan=2 style='border-style:solid;border-color:#888888;border-width:1px 0px 0px 0px;'>&nbsp;</td></tr>\n";
    }
    $row_count++;

    $row_count = "0";
    $page_count++;
    $temp_page_count = $page_count + 1;

    if (!empty($tmp_paginate)) {
        if ($x != ($employees_cnt - 1)) {
        echo "            </table>\n";
        echo '  <div class="row">
                  <div id="float_window" class="col-md-10">
                    <div class="box box-info">
                      <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-list"></i> Datos</h3>
                      </div>

                      <div class="box-body">';
        echo "            <table style='page-break-before:always;' class='table table-hover'>\n";
        echo "              <tr>
                              <td>
                                Fecha del informe: $rpt_time, $rpt_date (página $temp_page_count)
                              </td>
                              <td>
                                $rpt_name
                              </td>
                              <td>
                                Rango de fechas: $from_date_eur to $to_date_eur
                              <td>
                            </tr>\n";
        echo "            </table>\n";
        echo '</div></div></div></div>';
        echo '  <div class="row">
                  <div id="float_window" class="col-md-10">
                    <div class="box box-info">
                      <div class="box-header">';
        echo "            <table width='100%' align='center' class='table table-hover'>\n";
        }
    }

    //// reset everything before running the loop on the next user ////

    $tmp_z = 0;
    $row_count = 0;
    $total_hours = 0;
    $my_total_hours = 0;
    $info_cnt = 0;
    $punch_cnt = 0;
    $secs = 0;
    unset($info_fullname);
    unset($info_inout);
    unset($info_timestamp);
    unset($info_notes);
    unset($info_ipaddress);
    unset($punchlist_in_or_out);
    unset($punchlist_punchitems);
    unset($punchlist_color);
    unset($info_date);
    unset($info_start_time);
    unset($info_end_time);
    unset($tmp_info_date);
    unset($hours);
    unset($date_formatted);
    unset($currently_punched_in);
    unset($x_info_date);
    } // end if
} // end for $x
}
echo "            </table>\n";
exit;
?>
